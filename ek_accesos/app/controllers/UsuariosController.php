<?php

class UsuariosController extends Controller
{
    private Usuario $model;

    public function __construct()
    {
        requireAuth();
        $this->model = new Usuario();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // index — list users with KPI stats
    // ─────────────────────────────────────────────────────────────────────────

    public function index(): void
    {
        requireRole(['admin']);

        $filter = $_GET['filter'] ?? 'todos';  // pendiente | activo | todos

        $filters = ['buscar' => $_GET['buscar'] ?? ''];

        if ($filter === 'pendiente') {
            $filters['aprobado'] = 0;
            $filters['activo']   = 1;
        } elseif ($filter === 'activo') {
            $filters['activo']   = 1;
            $filters['aprobado'] = 1;
        }

        $usuarios = $this->model->getAll(
            array_filter($filters, fn($v) => $v !== '')
        );

        $pdo = Database::getInstance();

        $stats = [
            'total'      => (int)$pdo->query(
                "SELECT COUNT(*) FROM usuarios WHERE deleted_at IS NULL"
            )->fetchColumn(),
            'activos'    => (int)$pdo->query(
                "SELECT COUNT(*) FROM usuarios WHERE activo=1 AND aprobado=1 AND deleted_at IS NULL"
            )->fetchColumn(),
            'pendientes' => (int)$pdo->query(
                "SELECT COUNT(*) FROM usuarios WHERE aprobado=0 AND deleted_at IS NULL"
            )->fetchColumn(),
            'inactivos'  => (int)$pdo->query(
                "SELECT COUNT(*) FROM usuarios WHERE activo=0 AND deleted_at IS NULL"
            )->fetchColumn(),
        ];

        $this->render('usuarios/index', [
            'title'    => 'Usuarios del Sistema',
            'usuarios' => $usuarios,
            'stats'    => $stats,
            'filters'  => $filters,
            'filter'   => $filter,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // crear — show creation form
    // ─────────────────────────────────────────────────────────────────────────

    public function crear(): void
    {
        requireRole(['admin', 'capturista']);

        $programas  = (new ProgramaNivel())->getSelectList();
        $empleados  = (new Empleado())->getAll(['activo' => '1']);

        $this->render('usuarios/crear', [
            'title'     => 'Nuevo Usuario',
            'programas' => $programas,
            'empleados' => $empleados,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // guardar — create user (POST)
    // User starts with aprobado=0; admin receives approval email
    // ─────────────────────────────────────────────────────────────────────────

    public function guardar(): void
    {
        requireRole(['admin', 'capturista']);
        verifyCSRF();

        $nombre    = sanitize($_POST['nombre']    ?? '');
        $apellido  = sanitize($_POST['apellido']  ?? '');
        $email     = sanitize($_POST['email']     ?? '');
        $username  = sanitize($_POST['username']  ?? '');
        $password  = $_POST['password']           ?? '';
        $rol       = in_array($_POST['rol'] ?? '', ['superadmin', 'admin', 'capturista', 'viewer'])
                     ? $_POST['rol'] : 'viewer';
        $pnId      = (int)($_POST['programa_nivel_id'] ?? 0);
        $empId     = (int)($_POST['empleado_id']       ?? 0);

        // Validation
        if (!$nombre || !$email || !$username || !$password) {
            setFlash('error', 'Nombre, email, usuario y contraseña son obligatorios.');
            redirect('usuarios/crear');
        }
        if (strlen($password) < 6) {
            setFlash('error', 'La contraseña debe tener al menos 6 caracteres.');
            redirect('usuarios/crear');
        }
        if ($this->model->existeEmail($email)) {
            setFlash('error', 'El correo ya está registrado.');
            redirect('usuarios/crear');
        }
        if ($this->model->existeUsername($username)) {
            setFlash('error', 'El nombre de usuario ya está en uso.');
            redirect('usuarios/crear');
        }

        $currentUser = currentUser();
        $esAdmin     = in_array($currentUser['rol'] ?? $currentUser['tipo_usuario'] ?? '', ['admin', 'superadmin']);

        $userId = $this->model->createUser([
            'nombre'            => $nombre,
            'apellido'          => $apellido,
            'email'             => $email,
            'username'          => $username,
            'password'          => $password,
            'rol'               => $esAdmin ? $rol : 'viewer',
            'activo'            => $esAdmin ? 1 : 0,
            'aprobado'          => 0,          // always starts un-approved
            'debe_cambiar_pwd'  => 0,
        ]);

        // Link employee if provided
        if ($empId) {
            $pdo = Database::getInstance();
            $pdo->prepare(
                "INSERT INTO empleado_usuario (empleado_id, usuario_id)
                 VALUES (?,?)
                 ON DUPLICATE KEY UPDATE usuario_id = VALUES(usuario_id)"
            )->execute([$empId, $userId]);

            if ($pnId) {
                (new Empleado())->asignarProgramaNivel($empId, $pnId, currentUserId() ?? 0);
            }
        }

        // Send approval request to admin
        $this->enviarEmailSolicitudAprobacion($userId, $nombre, $apellido, $email, $username, $pnId);

        logAction('crear', 'usuarios', "Usuario creado: $email (ID: $userId)");
        setFlash('success', 'Usuario registrado. Se envió solicitud de aprobación al administrador.');
        redirect('usuarios');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // editar — show edit form
    // ─────────────────────────────────────────────────────────────────────────

    public function editar($id): void
    {
        requireRole(['admin']);

        $usuario = $this->model->find((int)$id);
        if (!$usuario) {
            setFlash('error', 'Usuario no encontrado.');
            redirect('usuarios');
        }

        $programas = (new ProgramaNivel())->getSelectList();

        $this->render('usuarios/editar', [
            'title'     => 'Editar: ' . htmlspecialchars($usuario['nombre']),
            'usuario'   => $usuario,
            'programas' => $programas,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // actualizar — update user data (POST)
    // ─────────────────────────────────────────────────────────────────────────

    public function actualizar($id): void
    {
        requireRole(['admin']);
        verifyCSRF();

        $usuario = $this->model->find((int)$id);
        if (!$usuario) {
            setFlash('error', 'Usuario no encontrado.');
            redirect('usuarios');
        }

        $email    = sanitize($_POST['email']    ?? '');
        $username = sanitize($_POST['username'] ?? '');

        if ($this->model->existeEmail($email, (int)$id)) {
            setFlash('error', 'El correo ya está registrado por otro usuario.');
            redirect('usuarios/editar/' . $id);
        }
        if ($this->model->existeUsername($username, (int)$id)) {
            setFlash('error', 'El nombre de usuario ya está en uso.');
            redirect('usuarios/editar/' . $id);
        }

        $data = [
            'nombre'   => sanitize($_POST['nombre']   ?? ''),
            'apellido' => sanitize($_POST['apellido'] ?? ''),
            'email'    => $email,
            'username' => $username,
            'rol'      => in_array($_POST['rol'] ?? '', ['superadmin', 'admin', 'capturista', 'viewer'])
                          ? $_POST['rol'] : 'viewer',
            'activo'   => isset($_POST['activo']) ? 1 : 0,
        ];

        // Optional password change
        if (!empty($_POST['nueva_password'])) {
            if ($_POST['nueva_password'] !== ($_POST['confirmar_password'] ?? '')) {
                setFlash('error', 'Las contraseñas no coinciden.');
                redirect('usuarios/editar/' . $id);
            }
            if (strlen($_POST['nueva_password']) < 6) {
                setFlash('error', 'La contraseña debe tener al menos 6 caracteres.');
                redirect('usuarios/editar/' . $id);
            }
            $data['password_hash'] = password_hash(
                $_POST['nueva_password'],
                PASSWORD_BCRYPT,
                ['cost' => 12]
            );
        }

        $this->model->update((int)$id, $data);

        logAction('editar', 'usuarios', "Usuario actualizado ID: $id");
        setFlash('success', 'Usuario actualizado.');
        redirect('usuarios');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // aprobar — set aprobado=1 and send welcome email (POST)
    // ─────────────────────────────────────────────────────────────────────────

    public function aprobar($id): void
    {
        requireRole(['admin']);
        verifyCSRF();

        $usuario = $this->model->find((int)$id);
        if (!$usuario) {
            setFlash('error', 'Usuario no encontrado.');
            redirect('usuarios');
        }

        $this->model->aprobar((int)$id, currentUserId() ?? 0);

        // Welcome email to user
        sendEmail(
            $usuario['email'],
            'Tu cuenta fue aprobada — EK Accesos',
            $this->buildWelcomeEmail($usuario)
        );

        logAction('aprobar', 'usuarios', "Usuario aprobado ID: $id");
        setFlash('success', 'Usuario aprobado exitosamente.');
        redirect('usuarios');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // rechazar — set activo=0, aprobado=0 (POST)
    // ─────────────────────────────────────────────────────────────────────────

    public function rechazar($id): void
    {
        requireRole(['admin']);
        verifyCSRF();

        $this->model->execute(
            "UPDATE usuarios SET activo = 0, aprobado = 0 WHERE id = ?",
            [(int)$id]
        );

        logAction('rechazar', 'usuarios', "Usuario rechazado ID: $id");
        setFlash('info', 'Usuario rechazado.');
        redirect('usuarios');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // toggleActivo — toggle activo field (POST)
    // ─────────────────────────────────────────────────────────────────────────

    public function toggleActivo($id): void
    {
        requireRole(['admin']);
        verifyCSRF();

        if ((int)$id === currentUserId()) {
            setFlash('error', 'No puedes desactivar tu propio usuario.');
            redirect('usuarios');
        }

        $this->model->execute(
            "UPDATE usuarios SET activo = IF(activo = 1, 0, 1) WHERE id = ?",
            [(int)$id]
        );

        logAction('toggle_activo', 'usuarios', "Toggle activo usuario ID: $id");

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            $this->json(['ok' => true]);
        }
        redirect('usuarios');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // eliminar — soft delete (POST)
    // ─────────────────────────────────────────────────────────────────────────

    public function eliminar($id): void
    {
        requireRole(['admin']);
        verifyCSRF();

        if ((int)$id === currentUserId()) {
            setFlash('error', 'No puedes eliminar tu propio usuario.');
            redirect('usuarios');
        }

        $this->model->delete((int)$id);

        logAction('eliminar', 'usuarios', "Usuario eliminado ID: $id");
        setFlash('success', 'Usuario eliminado.');
        redirect('usuarios');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Private email helpers
    // ─────────────────────────────────────────────────────────────────────────

    private function enviarEmailSolicitudAprobacion(
        int    $userId,
        string $nombre,
        string $apellido,
        string $email,
        string $username,
        int    $pnId
    ): void {
        $pnNombre     = '';
        if ($pnId) {
            $pn       = (new ProgramaNivel())->find($pnId);
            $pnNombre = $pn['nombre'] ?? '';
        }

        $aprobarLink  = BASE_URL . '/usuarios/aprobar/'  . $userId;
        $rechazarLink = BASE_URL . '/usuarios/rechazar/' . $userId;
        $nombreCompleto = htmlspecialchars(trim("$nombre $apellido"));

        $body = <<<HTML
        <div style="font-family:-apple-system,sans-serif;max-width:600px;margin:0 auto;background:#0a0e1a;color:#f1f5f9;padding:32px;border-radius:16px;">
            <h2 style="color:#818cf8;margin-bottom:8px;">EK Accesos — Nuevo usuario pendiente</h2>
            <p style="color:#94a3b8;margin-bottom:24px;">Un usuario requiere aprobación para acceder al sistema:</p>
            <table style="width:100%;border-collapse:collapse;margin-bottom:28px;">
                <tr><td style="padding:10px 12px;background:rgba(255,255,255,0.04);font-weight:600;color:#94a3b8;border-radius:4px 0 0 4px;">Nombre</td>
                    <td style="padding:10px 12px;color:#f1f5f9;">$nombreCompleto</td></tr>
                <tr><td style="padding:10px 12px;background:rgba(255,255,255,0.04);font-weight:600;color:#94a3b8;">Email</td>
                    <td style="padding:10px 12px;color:#f1f5f9;">$email</td></tr>
                <tr><td style="padding:10px 12px;background:rgba(255,255,255,0.04);font-weight:600;color:#94a3b8;">Usuario</td>
                    <td style="padding:10px 12px;color:#f1f5f9;">$username</td></tr>
                <tr><td style="padding:10px 12px;background:rgba(255,255,255,0.04);font-weight:600;color:#94a3b8;">Programa Nivel</td>
                    <td style="padding:10px 12px;color:#f1f5f9;">$pnNombre</td></tr>
            </table>
            <div style="display:flex;gap:12px;">
                <a href="$aprobarLink"  style="background:linear-gradient(135deg,#10b981,#059669);color:#fff;padding:12px 28px;border-radius:10px;text-decoration:none;font-weight:600;">Aprobar</a>
                <a href="$rechazarLink" style="background:rgba(239,68,68,0.15);color:#f87171;padding:12px 28px;border-radius:10px;text-decoration:none;font-weight:600;border:1px solid rgba(239,68,68,0.3);">Rechazar</a>
            </div>
        </div>
        HTML;

        sendEmail(ADMIN_EMAIL, "Nuevo usuario pendiente de aprobación — EK Accesos", $body);
    }

    private function buildWelcomeEmail(array $usuario): string
    {
        $nombre    = htmlspecialchars($usuario['nombre'] ?? '');
        $loginUrl  = BASE_URL . '/auth/login';

        return <<<HTML
        <div style="font-family:-apple-system,sans-serif;max-width:600px;margin:0 auto;background:#0a0e1a;color:#f1f5f9;padding:32px;border-radius:16px;">
            <h2 style="color:#818cf8;margin-bottom:8px;">¡Bienvenido a EK Accesos!</h2>
            <p style="color:#94a3b8;margin-bottom:24px;">Hola <strong>$nombre</strong>, tu cuenta ha sido aprobada y ya puedes acceder al sistema.</p>
            <a href="$loginUrl" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;padding:14px 32px;border-radius:10px;text-decoration:none;font-weight:600;display:inline-block;">
                Iniciar Sesión
            </a>
            <p style="color:#64748b;margin-top:24px;font-size:13px;">Si no solicitaste esta cuenta, ignora este mensaje.</p>
        </div>
        HTML;
    }
}
