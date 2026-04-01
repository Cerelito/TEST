<?php
// app/helpers/session.php - Gestión de sesiones

/**
 * Iniciar sesión si no está activa
 */
function iniciarSesion()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Regenerar ID de sesión (anti-fixation)
 */
function regenerarSesion()
{
    session_regenerate_id(true);
}

/**
 * Destruir sesión completamente
 */
function destruirSesion()
{
    iniciarSesion();
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }

    session_destroy();
}

/**
 * Guardar mensaje flash (persiste solo hasta la siguiente lectura)
 */
function setFlash($tipo, $mensaje)
{
    iniciarSesion();
    $_SESSION['flash'][$tipo] = $mensaje;
}

/**
 * Obtener y borrar mensaje flash
 */
function getFlash($tipo)
{
    iniciarSesion();
    if (isset($_SESSION['flash'][$tipo])) {
        $msg = $_SESSION['flash'][$tipo];
        unset($_SESSION['flash'][$tipo]);
        return $msg;
    }
    return null;
}

/**
 * ¿Existe un mensaje flash?
 */
function hasFlash($tipo)
{
    iniciarSesion();
    return isset($_SESSION['flash'][$tipo]);
}
