<?php
// app/helpers/csrf.php - Protección CSRF

/**
 * Generar token CSRF
 */
function generarToken() {
    iniciarSesion();

    if (!isset($_SESSION['csrf_token']) ||
        !isset($_SESSION['csrf_token_time']) ||
        (time() - $_SESSION['csrf_token_time']) > CSRF_TOKEN_EXPIRY) {

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_token_time'] = time();
    }

    return $_SESSION['csrf_token'];
}

/**
 * Validar token CSRF
 */
function validarToken($token) {
    iniciarSesion();

    if (!isset($_SESSION['csrf_token']) || !isset($_SESSION['csrf_token_time'])) {
        return false;
    }

    if ((time() - $_SESSION['csrf_token_time']) > CSRF_TOKEN_EXPIRY) {
        unset($_SESSION['csrf_token']);
        unset($_SESSION['csrf_token_time']);
        return false;
    }

    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Verificar CSRF en POST
 */
function verificarCSRF() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['csrf_token'] ?? '';

        if (!validarToken($token)) {
            logSeguridad('csrf_invalido', 'Token CSRF inválido o expirado', null, 'warning');
            setFlash('error', 'Token de seguridad inválido. Por favor, intente nuevamente.');
            redirect($_SERVER['HTTP_REFERER'] ?? 'dashboard');
        }
    }
}
