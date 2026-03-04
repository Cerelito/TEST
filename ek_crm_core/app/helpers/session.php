<?php
// app/helpers/session.php - Manejo de sesiones y mensajes flash

/**
 * Iniciar sesión de forma segura
 * Incluye protección contra Session Hijacking validando el User-Agent
 */
function iniciarSesion()
{
    if (session_status() === PHP_SESSION_NONE) {
        // Configuración segura de cookies antes de iniciar
        // Evita que se redefinan si ya se llamaron antes
        if (!defined('SESSION_CONFIGURED')) {
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_only_cookies', 1);

            // Solo activar secure si estamos en HTTPS
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                ini_set('session.cookie_secure', 1);
            }

            define('SESSION_CONFIGURED', true);
        }
        session_start();
    }

    // --- BLOQUE DE SEGURIDAD (ANTI-HIJACKING) ---
    // Validar que la sesión pertenezca al mismo navegador que la creó
    $userAgentActual = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';

    if (!isset($_SESSION['user_agent'])) {
        // Primera vez: guardamos la huella del navegador
        $_SESSION['user_agent'] = $userAgentActual;
    } elseif ($_SESSION['user_agent'] !== $userAgentActual) {
        // Si el navegador cambia drásticamente, destruimos la sesión (posible robo de cookie)

        // 1. Vaciar y destruir
        session_unset();
        session_destroy();

        // 2. Iniciar una nueva sesión limpia
        session_start();
        session_regenerate_id(true);

        // 3. Guardar el nuevo agente y avisar al usuario
        $_SESSION['user_agent'] = $userAgentActual;
        $_SESSION['flash']['error'] = 'Por seguridad, su sesión ha sido cerrada debido a un cambio de navegador o IP.';
    }
    // ---------------------------------------------
}

/**
 * Establecer mensaje flash
 */
function setFlash($tipo, $mensaje)
{
    iniciarSesion();
    $_SESSION['flash'][$tipo] = $mensaje;
}

/**
 * Obtener mensaje flash (y eliminarlo)
 */
function getFlash($tipo)
{
    iniciarSesion();

    if (isset($_SESSION['flash'][$tipo])) {
        $mensaje = $_SESSION['flash'][$tipo];
        unset($_SESSION['flash'][$tipo]);
        return $mensaje;
    }

    return null;
}

/**
 * Verificar si hay mensaje flash
 */
function hasFlash($tipo)
{
    iniciarSesion();
    return isset($_SESSION['flash'][$tipo]);
}

/**
 * Limpiar todos los mensajes flash
 */
function clearFlashes()
{
    iniciarSesion();
    unset($_SESSION['flash']);
}

/**
 * Establecer variable de sesión
 */
function setSession($key, $value)
{
    iniciarSesion();
    $_SESSION[$key] = $value;
}

/**
 * Obtener variable de sesión
 */
function getSession($key, $default = null)
{
    iniciarSesion();
    return $_SESSION[$key] ?? $default;
}

/**
 * Verificar si existe variable de sesión
 */
function hasSession($key)
{
    iniciarSesion();
    return isset($_SESSION[$key]);
}

/**
 * Eliminar variable de sesión
 */
function deleteSession($key)
{
    iniciarSesion();
    unset($_SESSION[$key]);
}

/**
 * Destruir sesión completa
 */
function destruirSesion()
{
    iniciarSesion();
    $_SESSION = [];

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    session_destroy();
}

/**
 * Regenerar ID de sesión (seguridad)
 */
function regenerarSesion()
{
    iniciarSesion();
    session_regenerate_id(true);
}