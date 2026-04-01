<?php
// app/helpers/ratelimit.php - Rate limiting basado en sesión

/**
 * Verificar si se ha superado el límite de intentos.
 * Retorna true si todavía está permitido, false si se bloqueó.
 *
 * @param string $accion      Nombre del action (ej. 'login', 'recuperacion')
 * @param int    $max         Máximo de intentos permitidos
 * @param int    $minutos     Ventana de tiempo en minutos
 * @param string $identificador  Username, email u otro discriminador
 */
function verificarRateLimit($accion, $max, $minutos, $identificador = '')
{
    iniciarSesion();

    $key      = 'rl_' . $accion . '_' . md5($identificador);
    $key_time = 'rl_t_' . $accion . '_' . md5($identificador);
    $ventana  = $minutos * 60;
    $ahora    = time();

    // Reiniciar contador si la ventana de tiempo ya pasó
    if (!isset($_SESSION[$key_time]) || ($ahora - $_SESSION[$key_time]) >= $ventana) {
        $_SESSION[$key]      = 0;
        $_SESSION[$key_time] = $ahora;
    }

    $_SESSION[$key]++;

    return $_SESSION[$key] <= $max;
}

/**
 * Segundos restantes del bloqueo activo
 */
function tiempoBloqueoRestante($accion, $minutos, $identificador = '')
{
    iniciarSesion();

    $key_time = 'rl_t_' . $accion . '_' . md5($identificador);

    if (!isset($_SESSION[$key_time])) {
        return 0;
    }

    $ventana  = $minutos * 60;
    $elapsed  = time() - $_SESSION[$key_time];

    return max(0, $ventana - $elapsed);
}

/**
 * Limpiar rate limit (tras acción exitosa)
 */
function limpiarRateLimit($accion, $identificador = '')
{
    iniciarSesion();

    $key      = 'rl_' . $accion . '_' . md5($identificador);
    $key_time = 'rl_t_' . $accion . '_' . md5($identificador);

    unset($_SESSION[$key], $_SESSION[$key_time]);
}
