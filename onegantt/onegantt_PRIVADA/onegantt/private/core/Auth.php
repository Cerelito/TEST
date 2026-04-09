<?php
class Auth
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->startSession();
    }

    private function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_name(SESSION_NAME);
            ini_set('session.cookie_path', '/');
            ini_set('session.cookie_secure', '0');
            ini_set('session.cookie_httponly', '1');
            ini_set('session.cookie_samesite', 'Lax');
            ini_set('session.gc_maxlifetime', SESSION_LIFETIME);
            session_start();
        }
    }

    public function login(string $email, string $password): true|string
    {
        $email = strtolower(trim($email));
        if (empty($email) || empty($password)) {
            return 'Correo y contraseña son requeridos.';
        }
        $user = $this->db->fetchOne(
            'SELECT u.*, r.slug AS rol_slug
             FROM users u JOIN roles r ON r.id = u.rol_id
             WHERE u.email = :email AND u.activo = 1 LIMIT 1',
            [':email' => $email]
        );
        if (!$user || !password_verify($password, $user['password'])) {
            return 'Credenciales incorrectas.';
        }
        session_regenerate_id(true);
        $_SESSION['user_id']    = $user['id'];
        $_SESSION['user_name']  = $user['nombre'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['rol']        = $user['rol_slug'];
        $_SESSION['logged_at']  = time();
        $this->db->execute('UPDATE users SET ultimo_login = NOW() WHERE id = :id', [':id' => $user['id']]);
        return true;
    }

    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
    }

    public function check(): bool
    {
        return isset($_SESSION['user_id'], $_SESSION['logged_at'])
            && (time() - $_SESSION['logged_at']) < SESSION_LIFETIME;
    }

    public function requireLogin(): void
    {
        if (!$this->check()) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    public function can(array $roles): bool
    {
        return in_array($_SESSION['rol'] ?? '', $roles, true);
    }

    public function requireRole(array $roles): void
    {
        $this->requireLogin();
        if (!$this->can($roles)) {
            http_response_code(403);
            include ROOT_PATH . 'views/layouts/403.php';
            exit;
        }
    }

    public function userId(): ?int    { return $_SESSION['user_id']    ?? null; }
    public function userName(): string { return $_SESSION['user_name']  ?? ''; }
    public function userEmail(): string { return $_SESSION['user_email'] ?? ''; }
    public function rol(): string     { return $_SESSION['rol']         ?? ''; }

    // ── Comprobadores de rol ──────────────────────────────

    /** Acceso total: catálogos, usuarios, configuración */
    public function isAdmin(): bool
    {
        return $this->rol() === 'admin';
    }

    /**
     * Director: ve tareas de TODOS los usuarios en el dashboard.
     * Gestiona proyectos y tareas. Sin acceso a catálogos.
     */
    public function isDirector(): bool
    {
        return $this->rol() === 'director';
    }

    /**
     * Colaborador: ve solo sus propias tareas en el dashboard.
     * Gestiona proyectos y tareas. Sin acceso a catálogos.
     */
    public function isColaborador(): bool
    {
        return $this->rol() === 'colaborador';
    }

    /**
     * Puede gestionar (crear/editar/eliminar) proyectos y tareas.
     * TRUE para admin, director y colaborador.
     * Reemplaza el antiguo isGestor().
     */
    public function canManage(): bool
    {
        return in_array($this->rol(), ['admin', 'director', 'colaborador'], true);
    }

    /**
     * Puede ver tareas de todos los usuarios (dashboard global + filtro de usuario).
     * TRUE para admin y director.
     */
    public function canSeeAllTasks(): bool
    {
        return in_array($this->rol(), ['admin', 'director'], true);
    }

    /**
     * @deprecated Usar canManage() para nuevos desarrollos.
     *             Mantenido para compatibilidad con vistas existentes.
     */
    public function isGestor(): bool
    {
        return $this->canManage();
    }

    // ── CSRF ─────────────────────────────────────────────

    public function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public function csrfField(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($this->csrfToken()) . '">';
    }

    public function validateCsrf(): bool
    {
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        return hash_equals($_SESSION['csrf_token'] ?? '', $token);
    }
}
