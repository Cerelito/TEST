<?php
// app/helpers/security.php - Funciones de seguridad complementarias

/**
 * Verificar token CSRF pasado explícitamente (alias para ProductosController
 * y ColeccionesController que lo llaman con el token como argumento).
 *
 * BUG FIX: Esta función no existía → Fatal Error en todas las acciones de
 * productos y colecciones (guardar, actualizar, eliminar, etc.).
 */
function verificarToken($token)
{
    if (!validarToken($token)) {
        logSeguridad('csrf_invalido', 'Token CSRF inválido o expirado (verificarToken)', null, 'warning');
        setFlash('error', 'Token de seguridad inválido. Por favor, intente nuevamente.');
        redirect('dashboard');
    }
}

/**
 * Alias de logSeguridad() para compatibilidad con ProductosController
 * que llama logSecurityEvent() en lugar de logSeguridad().
 *
 * BUG FIX: logSecurityEvent() no estaba definida → Fatal Error en el
 * catch de errores de productos/importaciones.
 */
function logSecurityEvent($evento, $descripcion = null, $nivel = 'warning')
{
    logSeguridad($evento, $descripcion, usuarioId(), $nivel);
}
