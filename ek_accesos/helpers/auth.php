<?php
/**
 * Authentication helper functions.
 *
 * All functions interact with the PHP session started by startSession().
 * The session stores a 'user' key containing the authenticated user array.
 */

// ─────────────────────────────────────────────────────────────────────────────
// Session management
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Start the PHP session with secure settings.
 * Must be called before any output is sent.
 */
function startSession(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return; // Already started
    }

    $lifetime = defined('SESSION_LIFETIME') ? (int) SESSION_LIFETIME : 3600;

    session_set_cookie_params([
        'lifetime' => $lifetime,
        'path'     => '/',
        'domain'   => '',
        'secure'   => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_name('EK_ACCESOS_SESS');
    session_start();

    // Regenerate the session ID periodically to mitigate fixation attacks
    if (!isset($_SESSION['_last_regenerated'])) {
        session_regenerate_id(true);
        $_SESSION['_last_regenerated'] = time();
    } elseif (time() - $_SESSION['_last_regenerated'] > 300) {
        session_regenerate_id(true);
        $_SESSION['_last_regenerated'] = time();
    }
}

// ─────────────────────────────────────────────────────────────────────────────
// Login / logout
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Store an authenticated user in the session.
 *
 * @param array $user  Associative array from the `usuarios` table row.
 */
function login(array $user): void
{
    session_regenerate_id(true);
    $_SESSION['_last_regenerated'] = time();
    $_SESSION['user']              = $user;
}

/**
 * Destroy the current session and clear the session cookie.
 */
function logout(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}

// ─────────────────────────────────────────────────────────────────────────────
// Session state queries
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Check whether the current visitor is logged in.
 */
function isLoggedIn(): bool
{
    return !empty($_SESSION['user']['id']);
}

/**
 * Return the current authenticated user array, or null if not logged in.
 *
 * @return array|null
 */
function currentUser(): ?array
{
    return $_SESSION['user'] ?? null;
}

/**
 * Return the current user's ID, or null if not logged in.
 */
function currentUserId(): ?int
{
    return isset($_SESSION['user']['id']) ? (int) $_SESSION['user']['id'] : null;
}

// ─────────────────────────────────────────────────────────────────────────────
// Access guards
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Redirect to the login page if the visitor is not authenticated.
 * Stores the originally requested URL so it can be restored after login.
 */
function requireAuth(): void
{
    if (!isLoggedIn()) {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? '';
        }

        $loginUrl = defined('BASE_URL')
            ? rtrim(BASE_URL, '/') . '/auth/login'
            : '/auth/login';

        header('Location: ' . $loginUrl, true, 302);
        exit;
    }
}

/**
 * Check whether the current user holds at least one of the given roles.
 *
 * Roles are compared against the `rol` field in the session user array
 * (e.g. 'superadmin', 'admin', 'operador', 'residente').
 *
 * @param string|string[] $roles  One role slug or an array of acceptable roles.
 * @return bool  true if the user has a matching role, false otherwise.
 */
function requireRole(string|array $roles): void
{
    if (!isLoggedIn()) {
        redirect('auth/login');
    }

    $roles       = is_array($roles) ? $roles : [$roles];
    $currentRole = $_SESSION['user']['rol'] ?? '';

    // Superadmin/admin has access to everything
    if (in_array($currentRole, ['superadmin', 'admin'])) return;

    if (!in_array($currentRole, $roles, true)) {
        http_response_code(403);
        die('<h1 style="font-family:sans-serif;text-align:center;margin-top:60px;">403 — Sin autorización</h1>');
    }
}

function isRole(string|array $roles): bool
{
    if (!isLoggedIn()) return false;
    $roles       = is_array($roles) ? $roles : [$roles];
    $currentRole = $_SESSION['user']['rol'] ?? '';
    if (in_array($currentRole, ['superadmin', 'admin'])) return true;
    return in_array($currentRole, $roles, true);
}

// Alias functions expected by controllers
function loginUser(array $user): void { login($user); }
function logoutUser(): void { logout(); }

// ─────────────────────────────────────────────────────────────────────────────
// Permission check
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Check whether the current user's programa_nivel grants access to a
 * specific module action.
 *
 * Queries the `permisos` table:
 *   CREATE TABLE permisos (
 *       id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 *       programa_nivel  VARCHAR(50)  NOT NULL,
 *       modulo          VARCHAR(100) NOT NULL,
 *       accion          VARCHAR(100) NOT NULL,
 *       UNIQUE KEY uq_permiso (programa_nivel, modulo, accion)
 *   );
 *
 * Superadmins bypass permission checks and always return true.
 *
 * @param string $modulo  Module identifier (e.g. 'residentes', 'accesos').
 * @param string $accion  Action identifier  (e.g. 'ver', 'crear', 'editar', 'eliminar').
 * @return bool
 */
function hasPermission(string $modulo, string $accion): bool
{
    if (!isLoggedIn()) {
        return false;
    }

    // Superadmins have unrestricted access
    if (($_SESSION['user']['rol'] ?? '') === 'superadmin') {
        return true;
    }

    $programaNivel = $_SESSION['user']['programa_nivel'] ?? null;

    if (empty($programaNivel)) {
        return false;
    }

    // Cache permissions in the session to avoid repeated DB queries
    $cacheKey = "permisos_{$programaNivel}";

    if (!isset($_SESSION[$cacheKey])) {
        try {
            $pdo  = getDB();
            $stmt = $pdo->prepare(
                'SELECT modulo, accion FROM permisos WHERE programa_nivel = :nivel'
            );
            $stmt->execute([':nivel' => $programaNivel]);

            $cache = [];
            foreach ($stmt->fetchAll() as $row) {
                $cache[$row['modulo']][$row['accion']] = true;
            }

            $_SESSION[$cacheKey] = $cache;
        } catch (PDOException $e) {
            error_log('[EK Accesos] hasPermission query failed: ' . $e->getMessage());
            return false;
        }
    }

    return isset($_SESSION[$cacheKey][$modulo][$accion]);
}
