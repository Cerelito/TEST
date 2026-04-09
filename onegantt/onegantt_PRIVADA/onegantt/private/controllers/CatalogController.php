<?php
class CatalogController
{
    private Auth     $auth;
    private Database $db;
    private UserModel $userModel;

    public function __construct(Auth $auth)
    {
        $this->auth      = $auth;
        $this->db        = Database::getInstance();
        $this->userModel = new UserModel();
    }

    // ══════════════════════════════════════════════════════
    //  ESTATUS
    // ══════════════════════════════════════════════════════

    public function statuses(?string $param = null): void
    {
        $this->auth->requireRole(['admin']);
        $auth      = $this->auth;
        $statuses  = $this->db->fetchAll('SELECT * FROM statuses ORDER BY orden');
        $csrfField = $this->auth->csrfField();
        include ROOT_PATH . 'views/catalogs/statuses/index.php';
    }

    public function statusCreate(?string $param = null): void
    {
        $this->auth->requireRole(['admin']);
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->auth->validateCsrf()) {
                $error = 'Token inválido.';
            } else {
                $nombre = trim(Sanitizer::post('nombre'));
                $color  = Sanitizer::post('color') ?: '#888888';
                $orden  = Sanitizer::post('orden', 'int') ?: 0;

                if (empty($nombre)) {
                    $error = 'El nombre del estatus es obligatorio.';
                } else {
                    $this->db->insert(
                        'INSERT INTO statuses (nombre, color, orden) VALUES (:n, :c, :o)',
                        [':n' => $nombre, ':c' => $color, ':o' => $orden]
                    );
                    Router::redirectWithFlash('catalogs/statuses', 'Estatus creado correctamente.');
                }
            }
        }

        $auth      = $this->auth;
        $status    = null;
        $csrfField = $this->auth->csrfField();
        include ROOT_PATH . 'views/catalogs/statuses/form.php';
    }

    public function statusEdit(?string $id = null): void
    {
        $this->auth->requireRole(['admin']);
        $status = $this->db->fetchOne('SELECT * FROM statuses WHERE id = :id', [':id' => (int)$id]);
        if (!$status) { http_response_code(404); die('Estatus no encontrado.'); }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->auth->validateCsrf()) {
                $error = 'Token inválido.';
            } else {
                $nombre = trim(Sanitizer::post('nombre'));
                $color  = Sanitizer::post('color') ?: '#888888';
                $orden  = Sanitizer::post('orden', 'int') ?: 0;

                if (empty($nombre)) {
                    $error = 'El nombre del estatus es obligatorio.';
                } else {
                    $this->db->execute(
                        'UPDATE statuses SET nombre = :n, color = :c, orden = :o WHERE id = :id',
                        [':n' => $nombre, ':c' => $color, ':o' => $orden, ':id' => (int)$id]
                    );
                    Router::redirectWithFlash('catalogs/statuses', 'Estatus actualizado correctamente.');
                }
            }
        }

        $auth      = $this->auth;
        $csrfField = $this->auth->csrfField();
        include ROOT_PATH . 'views/catalogs/statuses/form.php';
    }

    public function statusDelete(?string $id = null): void
    {
        $this->auth->requireRole(['admin']);
        if (!$this->auth->validateCsrf()) Router::redirect('catalogs/statuses');

        // No permitir borrar si hay tareas que usan este estatus
        $count = $this->db->fetchOne(
            'SELECT COUNT(*) AS n FROM tasks WHERE estatus_id = :id',
            [':id' => (int)$id]
        )['n'] ?? 0;

        if ($count > 0) {
            Router::redirectWithFlash(
                'catalogs/statuses',
                "No se puede eliminar: {$count} tarea(s) usan este estatus.",
                'error'
            );
        }

        $this->db->execute('DELETE FROM statuses WHERE id = :id', [':id' => (int)$id]);
        Router::redirectWithFlash('catalogs/statuses', 'Estatus eliminado.');
    }

    // ══════════════════════════════════════════════════════
    //  USUARIOS
    // ══════════════════════════════════════════════════════

    public function users(?string $param = null): void
    {
        $this->auth->requireRole(['admin']);
        $auth      = $this->auth;
        $usuarios  = $this->userModel->all();
        $csrfField = $this->auth->csrfField();
        include ROOT_PATH . 'views/catalogs/users/index.php';
    }

    public function userCreate(?string $param = null): void
    {
        $this->auth->requireRole(['admin']);
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->auth->validateCsrf()) {
                $error = 'Token inválido.';
            } else {
                $nombre = trim(Sanitizer::post('nombre'));
                $email  = trim(Sanitizer::post('email'));
                $pass   = $_POST['password'] ?? '';
                $rol_id = Sanitizer::post('rol_id', 'int') ?: 3;

                if (empty($nombre) || empty($email) || empty($pass)) {
                    $error = 'Nombre, email y contraseña son obligatorios.';
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = 'El email no tiene un formato válido.';
                } elseif (strlen($pass) < 8) {
                    $error = 'La contraseña debe tener al menos 8 caracteres.';
                } else {
                    // Verificar email único
                    $exists = $this->db->fetchOne(
                        'SELECT id FROM users WHERE email = :e',
                        [':e' => $email]
                    );
                    if ($exists) {
                        $error = 'Ya existe un usuario con ese email.';
                    } else {
                        $this->userModel->create([
                            'nombre'   => $nombre,
                            'email'    => $email,
                            'password' => $pass,
                            'rol_id'   => $rol_id,
                        ]);
                        Router::redirectWithFlash('catalogs/users', 'Usuario creado correctamente.');
                    }
                }
            }
        }

        $auth      = $this->auth;
        $usuario   = null;
        $roles     = $this->userModel->roles();
        $csrfField = $this->auth->csrfField();
        include ROOT_PATH . 'views/catalogs/users/form.php';
    }

    public function userEdit(?string $id = null): void
    {
        $this->auth->requireRole(['admin']);
        $usuario = $this->userModel->find((int)$id);
        if (!$usuario) { http_response_code(404); die('Usuario no encontrado.'); }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->auth->validateCsrf()) {
                $error = 'Token inválido.';
            } else {
                $nombre = trim(Sanitizer::post('nombre'));
                $email  = trim(Sanitizer::post('email'));
                $pass   = $_POST['password'] ?? '';
                $rol_id = Sanitizer::post('rol_id', 'int') ?: 3;
                $activo = isset($_POST['activo']) ? 1 : 0;

                if (empty($nombre) || empty($email)) {
                    $error = 'Nombre y email son obligatorios.';
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = 'El email no tiene un formato válido.';
                } elseif (!empty($pass) && strlen($pass) < 8) {
                    $error = 'La contraseña debe tener al menos 8 caracteres.';
                } else {
                    // Verificar email único (excluyendo el usuario actual)
                    $exists = $this->db->fetchOne(
                        'SELECT id FROM users WHERE email = :e AND id != :id',
                        [':e' => $email, ':id' => (int)$id]
                    );
                    if ($exists) {
                        $error = 'Ya existe otro usuario con ese email.';
                    } else {
                        $data = [
                            'nombre' => $nombre,
                            'email'  => $email,
                            'rol_id' => $rol_id,
                            'activo' => $activo,
                        ];
                        if (!empty($pass)) $data['password'] = $pass;
                        $this->userModel->update((int)$id, $data);
                        Router::redirectWithFlash('catalogs/users', 'Usuario actualizado correctamente.');
                    }
                }
            }
        }

        $auth      = $this->auth;
        $roles     = $this->userModel->roles();
        $csrfField = $this->auth->csrfField();
        include ROOT_PATH . 'views/catalogs/users/form.php';
    }

    public function userToggle(?string $id = null): void
    {
        $this->auth->requireRole(['admin']);
        if (!$this->auth->validateCsrf()) Router::redirect('catalogs/users');

        $usuario = $this->userModel->find((int)$id);
        if (!$usuario) Router::redirect('catalogs/users');

        // No desactivar al propio admin logueado
        if ((int)$id === $this->auth->userId()) {
            Router::redirectWithFlash('catalogs/users', 'No puedes desactivar tu propio usuario.', 'error');
        }

        $nuevoEstado = $usuario['activo'] ? 0 : 1;
        $this->userModel->update((int)$id, ['activo' => $nuevoEstado]);
        $msg = $nuevoEstado ? 'Usuario activado.' : 'Usuario desactivado.';
        Router::redirectWithFlash('catalogs/users', $msg);
    }
}
