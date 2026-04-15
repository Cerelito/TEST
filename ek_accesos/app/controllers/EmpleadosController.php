<?php

class EmpleadosController extends Controller
{
    private Empleado $model;

    public function __construct()
    {
        requireAuth();
        $this->model = new Empleado();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // index — list employees
    // ─────────────────────────────────────────────────────────────────────────

    public function index(): void
    {
        $filters = [
            'buscar'     => $_GET['buscar']     ?? '',
            'empresa_id' => $_GET['empresa_id'] ?? '',
            'activo'     => $_GET['activo']     ?? '',
            'aprobado'   => $_GET['aprobado']   ?? '',
        ];

        $empleados = $this->model->getAll(
            array_filter($filters, fn($v) => $v !== '')
        );
        $empresas = $this->model->getEmpresasList();

        $this->render('empleados/index', [
            'title'     => 'Empleados',
            'empleados' => $empleados,
            'empresas'  => $empresas,
            'filters'   => $filters,
            'total'     => count($empleados),
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // crear — show creation form
    // ─────────────────────────────────────────────────────────────────────────

    public function crear(): void
    {
        requireRole(['admin', 'superadmin', 'capturista']);

        $empresas = $this->model->getEmpresasList();
        $pn       = (new ProgramaNivel())->getSelectList();

        $this->render('empleados/crear', [
            'title'    => 'Nuevo Empleado',
            'empresas' => $empresas,
            'programas'=> $pn,
            'esAdmin'  => isRole(['admin', 'superadmin']),
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // guardar — process creation (POST)
    // ─────────────────────────────────────────────────────────────────────────

    public function guardar(): void
    {
        requireRole(['admin', 'superadmin', 'capturista']);
        verifyCSRF();

        $esAdmin  = isRole(['admin', 'superadmin']);
        $nombre   = sanitize($_POST['nombre']           ?? '');
        $apPat    = sanitize($_POST['apellido_paterno'] ?? '');
        $apMat    = sanitize($_POST['apellido_materno'] ?? '');
        $puesto   = sanitize($_POST['puesto']           ?? '');
        $email    = sanitize($_POST['email']            ?? '');
        $telefono = sanitize($_POST['telefono']         ?? '');
        $empId    = (int)($_POST['empresa_id']          ?? 0);
        $jefeId   = (int)($_POST['jefe_id']             ?? 0) ?: null;
        $pnId     = (int)($_POST['programa_nivel_id']   ?? 0);

        if (!$nombre || !$empId) {
            setFlash('error', 'Nombre y empresa son obligatorios.');
            redirect('empleados/crear');
        }

        $data = [
            'nombre'           => $nombre,
            'apellido_paterno' => $apPat,
            'apellido_materno' => $apMat,
            'puesto'           => $puesto,
            'email'            => $email,
            'telefono'         => $telefono,
            'empresa_id'       => $empId,
            'jefe_id'          => $jefeId,
            'activo'           => 1,
            'aprobado'         => $esAdmin ? 1 : 0,
            'creado_por'       => currentUserId() ?? 0,
        ];

        // Admin-only fields
        if ($esAdmin) {
            $userId  = sanitize($_POST['user_id'] ?? '');
            $pwdEk   = trim($_POST['password_ek'] ?? '');
            $pinEk   = trim($_POST['pin_ek']      ?? '');
            if ($userId)  $data['user_id']    = $userId;
            if ($pwdEk)   $data['password_ek'] = encryptEK($pwdEk);
            if ($pinEk)   $data['pin_ek']      = encryptEK($pinEk);
        }

        $id = $this->model->create($data);

        if ($pnId) {
            $this->model->asignarProgramaNivel($id, $pnId, currentUserId() ?? 0);
        }

        $centros = $_POST['centros'] ?? [];
        if (!empty($centros) && is_array($centros)) {
            $this->model->saveCentrosCosto($id, $centros);
            $this->syncRequisitorComprador($id, $centros);
        }

        $nombreCompleto = trim("$nombre $apPat $apMat");

        if ($esAdmin) {
            // Send welcome email to employee if they have an email
            if ($email) {
                $pwdEkPlain = trim($_POST['password_ek'] ?? '');
                $pinEkPlain = trim($_POST['pin_ek']      ?? '');
                $this->sendWelcomeEmailEmpleado($email, $nombreCompleto, $data['user_id'] ?? '', $pwdEkPlain, $pinEkPlain);
            }
            logAction('crear', 'empleados', "Empleado creado: $nombreCompleto (ID: $id)");
            setFlash('success', "Empleado <strong>" . htmlspecialchars($nombreCompleto) . "</strong> registrado exitosamente.");
        } else {
            // Capturista: notify admin
            $this->notifyAdminNewEmployee($id, $nombreCompleto, currentUser()['nombre'] ?? 'Capturista');
            logAction('crear', 'empleados', "Empleado propuesto por capturista: $nombreCompleto (ID: $id)");
            setFlash('success', "Propuesta de registro enviada. El administrador será notificado para completar el registro.");
        }

        redirect('empleados');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // editar — show edit form
    // ─────────────────────────────────────────────────────────────────────────

    public function editar($id): void
    {
        requireRole(['admin', 'superadmin', 'capturista']);

        $empleado = $this->model->getById((int)$id);
        if (!$empleado) {
            setFlash('error', 'Empleado no encontrado.');
            redirect('empleados');
        }

        $empresas = $this->model->getEmpresasList();
        $pn       = (new ProgramaNivel())->getSelectList();

        $this->render('empleados/editar', [
            'title'             => 'Editar: ' . htmlspecialchars($empleado['nombre']),
            'empleado'          => $empleado,
            'empresas'          => $empresas,
            'programas'         => $pn,
            'centros_asignados' => $empleado['centros_costo'] ?? [],
            'esAdmin'           => isRole(['admin', 'superadmin']),
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // actualizar — process update (POST)
    // ─────────────────────────────────────────────────────────────────────────

    public function actualizar($id): void
    {
        requireRole(['admin', 'superadmin', 'capturista']);
        verifyCSRF();

        $empleado = $this->model->find((int)$id);
        if (!$empleado) {
            setFlash('error', 'Empleado no encontrado.');
            redirect('empleados');
        }

        $esAdmin  = isRole(['admin', 'superadmin']);
        $nombre   = sanitize($_POST['nombre']           ?? '');
        $apPat    = sanitize($_POST['apellido_paterno'] ?? '');
        $apMat    = sanitize($_POST['apellido_materno'] ?? '');
        $puesto   = sanitize($_POST['puesto']           ?? '');
        $email    = sanitize($_POST['email']            ?? '');
        $telefono = sanitize($_POST['telefono']         ?? '');
        $empId    = (int)($_POST['empresa_id']          ?? 0);
        $jefeId   = (int)($_POST['jefe_id']             ?? 0) ?: null;
        $pnId     = (int)($_POST['programa_nivel_id']   ?? 0);

        if (!$nombre || !$empId) {
            setFlash('error', 'Nombre y empresa son obligatorios.');
            redirect('empleados/editar/' . $id);
        }

        $data = [
            'nombre'           => $nombre,
            'apellido_paterno' => $apPat,
            'apellido_materno' => $apMat,
            'puesto'           => $puesto,
            'email'            => $email,
            'telefono'         => $telefono,
            'empresa_id'       => $empId,
            'jefe_id'          => $jefeId,
            'activo'           => isset($_POST['activo']) ? 1 : 0,
        ];

        $wasApproved = (int)($empleado['aprobado'] ?? 1);
        $sendWelcome = false;
        $pwdEkPlain  = '';
        $pinEkPlain  = '';

        if ($esAdmin) {
            $userId  = sanitize($_POST['user_id'] ?? '');
            $pwdEkIn = trim($_POST['password_ek'] ?? '');
            $pinEkIn = trim($_POST['pin_ek']      ?? '');

            $data['user_id']  = $userId;
            $data['aprobado'] = isset($_POST['aprobado']) ? 1 : 0;

            if ($pwdEkIn) {
                $data['password_ek'] = encryptEK($pwdEkIn);
                $pwdEkPlain = $pwdEkIn;
            }
            if ($pinEkIn) {
                $data['pin_ek'] = encryptEK($pinEkIn);
                $pinEkPlain = $pinEkIn;
            }

            // If being approved for the first time → send welcome emails
            if ($wasApproved === 0 && isset($_POST['aprobado'])) {
                $sendWelcome = true;
            }
        }

        $this->model->update((int)$id, $data);

        if ($pnId) {
            $this->model->asignarProgramaNivel((int)$id, $pnId, currentUserId() ?? 0);
        }

        $centros = $_POST['centros'] ?? [];
        if (is_array($centros)) {
            $this->model->saveCentrosCosto((int)$id, $centros);
            $this->syncRequisitorComprador((int)$id, $centros);
        }

        $nombreCompleto = trim("$nombre $apPat $apMat");

        if ($sendWelcome && $email) {
            $this->sendWelcomeEmailEmpleado($email, $nombreCompleto, $_POST['user_id'] ?? '', $pwdEkPlain, $pinEkPlain);
            // Notify capturist who created the record
            if (!empty($empleado['creado_por'])) {
                $this->sendRegistroCompletadoEmail($empleado['creado_por'], $nombreCompleto);
            }
        }

        logAction('editar', 'empleados', "Empleado actualizado: $nombreCompleto (ID: $id)");
        setFlash('success', 'Empleado actualizado exitosamente.');
        redirect('empleados');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // eliminar — soft delete (POST) — admin only
    // ─────────────────────────────────────────────────────────────────────────

    public function eliminar($id): void
    {
        requireRole(['admin', 'superadmin']);
        verifyCSRF();

        $empleado = $this->model->find((int)$id);
        if ($empleado) {
            $this->model->delete((int)$id);
            logAction('eliminar', 'empleados', "Empleado eliminado ID: $id");
            setFlash('success', 'Empleado eliminado.');
        } else {
            setFlash('error', 'Empleado no encontrado.');
        }
        redirect('empleados');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // toggleActivo — toggle active flag (POST)
    // ─────────────────────────────────────────────────────────────────────────

    public function toggleActivo($id): void
    {
        requireRole(['admin', 'superadmin', 'capturista']);
        verifyCSRF();

        $this->model->toggleActivo((int)$id);
        logAction('toggle_activo', 'empleados', "Toggle activo empleado ID: $id");

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            $this->json(['ok' => true]);
        }
        redirect('empleados');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // buscar — AJAX: search employees by name for jefe selector
    // ─────────────────────────────────────────────────────────────────────────

    public function buscar(): void
    {
        $q = sanitize($_GET['q'] ?? '');
        if (strlen($q) < 2) {
            $this->json([]);
        }

        $rows = $this->model->query(
            "SELECT e.id,
                    CONCAT(e.nombre, IF(e.apellido_paterno IS NOT NULL AND e.apellido_paterno != '', CONCAT(' ', e.apellido_paterno), ''),
                           IF(e.apellido_materno IS NOT NULL AND e.apellido_materno != '', CONCAT(' ', e.apellido_materno), '')) AS nombre,
                    emp.nombre AS empresa
             FROM empleados e
             LEFT JOIN empresas emp ON emp.id = e.empresa_id
             WHERE e.deleted_at IS NULL AND e.activo = 1
               AND (e.nombre LIKE ? OR e.apellido_paterno LIKE ? OR e.apellido_materno LIKE ?)
             ORDER BY e.nombre LIMIT 20",
            ['%' . $q . '%', '%' . $q . '%', '%' . $q . '%']
        );

        $this->json($rows);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────────────────────

    private function syncRequisitorComprador(int $empleadoId, array $centros): void
    {
        $esRequisitor = false;
        $esComprador  = false;

        foreach ($centros as $cc) {
            $tipo = $cc['tipo'] ?? '';
            $elab = (int)($cc['elab'] ?? 0);

            if ($elab === 1) {
                if (in_array($tipo, ['REQ', 'AMBOS'], true)) $esRequisitor = true;
                if (in_array($tipo, ['OC',  'AMBOS'], true)) $esComprador  = true;
            }
        }

        if ($esRequisitor) $this->upsertRequisitor($empleadoId);
        if ($esComprador)  $this->upsertComprador($empleadoId);
    }

    private function upsertRequisitor(int $empleadoId): void
    {
        $row = $this->pdo()->prepare("SELECT id FROM requisitores WHERE empleado_id = ?");
        $row->execute([$empleadoId]);
        $existing = $row->fetch(\PDO::FETCH_ASSOC);

        if ($existing) {
            $this->pdo()->prepare("UPDATE requisitores SET activo = 1 WHERE id = ?")->execute([$existing['id']]);
        } else {
            $this->pdo()->prepare("INSERT INTO requisitores (empleado_id, activo) VALUES (?, 1)")->execute([$empleadoId]);
        }
    }

    private function upsertComprador(int $empleadoId): void
    {
        $row = $this->pdo()->prepare("SELECT id FROM compradores WHERE empleado_id = ?");
        $row->execute([$empleadoId]);
        $existing = $row->fetch(\PDO::FETCH_ASSOC);

        if ($existing) {
            $this->pdo()->prepare("UPDATE compradores SET activo = 1 WHERE id = ?")->execute([$existing['id']]);
        } else {
            $this->pdo()->prepare("INSERT INTO compradores (empleado_id, activo) VALUES (?, 1)")->execute([$empleadoId]);
        }
    }

    private function pdo(): \PDO
    {
        return Database::getInstance();
    }

    // ─── Email helpers ────────────────────────────────────────────────────────

    /**
     * Send welcome email to the employee with their ERP credentials.
     */
    private function sendWelcomeEmailEmpleado(string $to, string $nombre, string $userId, string $pwdEk, string $pinEk): void
    {
        $subject = 'Bienvenido al Sistema EK Accesos — Tus credenciales';
        $body    = $this->buildWelcomeBodyEmpleado($nombre, $userId, $pwdEk, $pinEk);
        @sendEmail($to, $subject, $body);
    }

    /**
     * Notify admin that a capturist created a new employee pending approval.
     */
    private function notifyAdminNewEmployee(int $empleadoId, string $nombreEmp, string $capturistaNombre): void
    {
        $adminEmail = defined('ADMIN_EMAIL') ? ADMIN_EMAIL : '';
        if (!$adminEmail) return;

        $url     = defined('BASE_URL') ? BASE_URL . '/empleados/' . $empleadoId . '/editar' : '';
        $subject = 'Nueva propuesta de empleado pendiente de aprobación';
        $body    = "
        <div style=\"font-family:-apple-system,Arial,sans-serif;max-width:600px;margin:0 auto;background:#0a0e1a;color:#f1f5f9;border-radius:16px;overflow:hidden;\">
            <div style=\"background:linear-gradient(135deg,#6366f1,#8b5cf6);padding:32px 40px;\">
                <h1 style=\"margin:0;font-size:22px;color:#fff;\">EK Accesos</h1>
                <p style=\"margin:6px 0 0;color:rgba(255,255,255,.8);font-size:14px;\">Nueva solicitud de registro de empleado</p>
            </div>
            <div style=\"padding:32px 40px;\">
                <p style=\"font-size:15px;margin-bottom:20px;\">El capturista <strong style=\"color:#818cf8;\">" . htmlspecialchars($capturistaNombre) . "</strong> registró un nuevo empleado que requiere tu revisión y aprobación:</p>
                <div style=\"background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:20px 24px;margin-bottom:24px;\">
                    <p style=\"margin:0;font-size:16px;font-weight:700;color:#f1f5f9;\">" . htmlspecialchars($nombreEmp) . "</p>
                    <p style=\"margin:6px 0 0;color:#64748b;font-size:13px;\">ID de empleado en sistema: #$empleadoId</p>
                </div>
                <p style=\"font-size:13px;color:#94a3b8;margin-bottom:24px;\">Es necesario que completes los datos de acceso ERP (ID ERP, contraseña y NIP) y apruebes el registro.</p>
                " . ($url ? "<a href=\"$url\" style=\"display:inline-block;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;padding:13px 28px;border-radius:10px;text-decoration:none;font-weight:700;font-size:14px;\">Revisar y Aprobar →</a>" : '') . "
            </div>
            <div style=\"padding:20px 40px;border-top:1px solid rgba(255,255,255,.08);font-size:12px;color:#475569;\">
                EK Accesos · Mensaje automático · No responder a este correo.
            </div>
        </div>";

        @sendEmail($adminEmail, $subject, $body);
    }

    /**
     * Notify capturist that the employee they created has been approved.
     */
    private function sendRegistroCompletadoEmail(int $creadoPorId, string $nombreEmp): void
    {
        $stmt = $this->pdo()->prepare("SELECT email, nombre FROM usuarios WHERE id = ? LIMIT 1");
        $stmt->execute([$creadoPorId]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$user || empty($user['email'])) return;

        $subject = '✓ Registro completado — ' . $nombreEmp;
        $body    = "
        <div style=\"font-family:-apple-system,Arial,sans-serif;max-width:600px;margin:0 auto;background:#0a0e1a;color:#f1f5f9;border-radius:16px;overflow:hidden;\">
            <div style=\"background:linear-gradient(135deg,#10b981,#06b6d4);padding:32px 40px;\">
                <h1 style=\"margin:0;font-size:22px;color:#fff;\">EK Accesos</h1>
                <p style=\"margin:6px 0 0;color:rgba(255,255,255,.8);font-size:14px;\">Registro completado exitosamente</p>
            </div>
            <div style=\"padding:32px 40px;\">
                <p style=\"font-size:15px;margin-bottom:20px;\">Hola <strong style=\"color:#34d399;\">" . htmlspecialchars($user['nombre']) . "</strong>,</p>
                <p style=\"font-size:15px;margin-bottom:20px;\">El registro del empleado que propusiste ha sido <strong style=\"color:#34d399;\">aprobado y completado</strong> por el administrador:</p>
                <div style=\"background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.2);border-radius:12px;padding:20px 24px;margin-bottom:24px;\">
                    <p style=\"margin:0;font-size:16px;font-weight:700;color:#f1f5f9;\">" . htmlspecialchars($nombreEmp) . "</p>
                    <p style=\"margin:6px 0 0;color:#64748b;font-size:13px;\">Registro Completado ✓</p>
                </div>
                <p style=\"font-size:13px;color:#94a3b8;\">El empleado ahora tiene acceso completo al sistema con sus credenciales asignadas.</p>
            </div>
            <div style=\"padding:20px 40px;border-top:1px solid rgba(255,255,255,.08);font-size:12px;color:#475569;\">
                EK Accesos · Mensaje automático · No responder a este correo.
            </div>
        </div>";

        @sendEmail($user['email'], $subject, $body);
    }

    private function buildWelcomeBodyEmpleado(string $nombre, string $userId, string $pwdEk, string $pinEk): string
    {
        $appName = defined('APP_NAME') ? APP_NAME : 'EK Accesos';
        return "
        <div style=\"font-family:-apple-system,Arial,sans-serif;max-width:600px;margin:0 auto;background:#0a0e1a;color:#f1f5f9;border-radius:16px;overflow:hidden;\">
            <div style=\"background:linear-gradient(135deg,#6366f1,#8b5cf6);padding:32px 40px;\">
                <h1 style=\"margin:0;font-size:24px;color:#fff;\">$appName</h1>
                <p style=\"margin:6px 0 0;color:rgba(255,255,255,.8);font-size:14px;\">Bienvenido al sistema</p>
            </div>
            <div style=\"padding:32px 40px;\">
                <p style=\"font-size:15px;margin-bottom:24px;\">Hola <strong style=\"color:#818cf8;\">" . htmlspecialchars($nombre) . "</strong>, tu registro en el sistema EK ha sido completado. A continuación encontrarás tus credenciales de acceso:</p>

                <div style=\"background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);border-radius:14px;overflow:hidden;margin-bottom:24px;\">
                    <div style=\"padding:16px 24px;border-bottom:1px solid rgba(255,255,255,.06);background:rgba(99,102,241,.08);\">
                        <span style=\"font-size:11px;font-weight:700;letter-spacing:1px;color:#818cf8;text-transform:uppercase;\">Credenciales ERP</span>
                    </div>
                    " . ($userId ? "
                    <div style=\"padding:14px 24px;border-bottom:1px solid rgba(255,255,255,.04);display:flex;justify-content:space-between;\">
                        <span style=\"color:#64748b;font-size:13px;\">ID de Usuario ERP</span>
                        <strong style=\"color:#f1f5f9;font-size:14px;font-family:monospace;\">" . htmlspecialchars($userId) . "</strong>
                    </div>" : '') . "
                    " . ($pwdEk ? "
                    <div style=\"padding:14px 24px;border-bottom:1px solid rgba(255,255,255,.04);display:flex;justify-content:space-between;\">
                        <span style=\"color:#64748b;font-size:13px;\">Contraseña ERP</span>
                        <strong style=\"color:#f1f5f9;font-size:14px;font-family:monospace;letter-spacing:2px;\">" . htmlspecialchars($pwdEk) . "</strong>
                    </div>" : '') . "
                    " . ($pinEk ? "
                    <div style=\"padding:14px 24px;display:flex;justify-content:space-between;\">
                        <span style=\"color:#64748b;font-size:13px;\">NIP</span>
                        <strong style=\"color:#f1f5f9;font-size:14px;font-family:monospace;letter-spacing:4px;\">" . htmlspecialchars($pinEk) . "</strong>
                    </div>" : '') . "
                </div>

                <div style=\"background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.2);border-radius:10px;padding:14px 18px;font-size:12px;color:#fbbf24;\">
                    ⚠️ Por seguridad, guarda estas credenciales en un lugar seguro y no las compartas con nadie.
                </div>
            </div>
            <div style=\"padding:20px 40px;border-top:1px solid rgba(255,255,255,.08);font-size:12px;color:#475569;\">
                $appName · Mensaje automático generado al completar tu registro.
            </div>
        </div>";
    }
}
