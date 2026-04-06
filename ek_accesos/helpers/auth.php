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

    $rol = $_SESSION['user']['rol'] ?? '';

    // Superadmins/admins have unrestricted access
    if (in_array($rol, ['superadmin', 'admin'], true)) {
        return true;
    }

    // Resolve programa_nivel_id for this user
    $pnId = $_SESSION['user']['programa_nivel_id'] ?? null;

    if (!$pnId) {
        // Try to look it up once and cache it
        if (!isset($_SESSION['_pn_id_loaded'])) {
            try {
                $pdo  = getDB();
                $stmt = $pdo->prepare(
                    "SELECT programa_nivel_id FROM usuario_programa_nivel WHERE usuario_id = ? LIMIT 1"
                );
                $stmt->execute([$_SESSION['user']['id']]);
                $pnId = (int)($stmt->fetchColumn() ?: 0);
                $_SESSION['user']['programa_nivel_id'] = $pnId;
                $_SESSION['_pn_id_loaded'] = true;
            } catch (\PDOException $e) {
                error_log('[EK Accesos] hasPermission pn lookup failed: ' . $e->getMessage());
                return false;
            }
        }
    }

    if (!$pnId) {
        return false;
    }

    // Cache granted module IDs in session to avoid repeated DB queries
    $cacheKey = "pn_permisos_{$pnId}";

    if (!isset($_SESSION[$cacheKey])) {
        try {
            $pdo  = getDB();
            $stmt = $pdo->prepare(
                "SELECT m.clave
                 FROM programa_nivel_permisos pnp
                 JOIN modulos_erp m ON m.id = pnp.modulo_erp_id
                 WHERE pnp.programa_nivel_id = ? AND pnp.activo = 1 AND m.activo = 1"
            );
            $stmt->execute([$pnId]);

            $cache = [];
            foreach ($stmt->fetchAll(\PDO::FETCH_COLUMN) as $clave) {
                $cache[$clave] = true;
            }

            $_SESSION[$cacheKey] = $cache;
        } catch (\PDOException $e) {
            error_log('[EK Accesos] hasPermission query failed: ' . $e->getMessage());
            return false;
        }
    }

    // Module claves use dot-notation: "modulo" or "modulo.submodulo"
    // Check exact match or parent match (e.g. "compras" grants "compras.ver")
    $key = $modulo !== '' && $accion !== '' ? "{$modulo}.{$accion}" : $modulo;
    if (isset($_SESSION[$cacheKey][$key])) {
        return true;
    }
    // Also check if top-level module is granted
    return isset($_SESSION[$cacheKey][$modulo]);
}
