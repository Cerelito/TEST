<?php
// app/helpers/session.php - Manejo de sesiones y mensajes flash

/**
 * Iniciar sesión si no está activa
 */
function iniciarSesion() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Establecer mensaje flash
 */
function setFlash($tipo, $mensaje) {
    iniciarSesion();
    $_SESSION['flash'][$tipo] = $mensaje;
}

/**
 * Obtener mensaje flash (y eliminarlo)
 */
function getFlash($tipo) {
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
function hasFlash($tipo) {
    iniciarSesion();
    return isset($_SESSION['flash'][$tipo]);
}

/**
 * Limpiar todos los mensajes flash
 */
function clearFlashes() {
    iniciarSesion();
    unset($_SESSION['flash']);
}

/**
 * Establecer variable de sesión
 */
function setSession($key, $value) {
    iniciarSesion();
    $_SESSION[$key] = $value;
}

/**
 * Obtener variable de sesión
 */
function getSession($key, $default = null) {
    iniciarSesion();
    return $_SESSION[$key] ?? $default;
}

/**
 * Verificar si existe variable de sesión
 */
function hasSession($key) {
    iniciarSesion();
    return isset($_SESSION[$key]);
}

/**
 * Eliminar variable de sesión
 */
function deleteSession($key) {
    iniciarSesion();
    unset($_SESSION[$key]);
}

/**
 * Destruir sesión completa
 */
function destruirSesion() {
    iniciarSesion();
    $_SESSION = [];

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_destroy();
}

/**
 * Regenerar ID de sesión (seguridad)
 */
function regenerarSesion() {
    iniciarSesion();
    session_regenerate_id(true);
}
