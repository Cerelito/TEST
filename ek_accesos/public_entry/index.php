<?php
declare(strict_types=1);

/**
 * EK Accesos – Punto de entrada PÚBLICO
 *
 * Este archivo va en:
 *   /home1/erickedu/public_html/urbano/ekusers/index.php
 *
 * La carpeta PRIVADA (app, config, core, helpers) va en:
 *   /home1/erickedu/ekusers/
 *
 * ─── IMPORTANTE ────────────────────────────────────────────────────────────
 * Si tu estructura de carpetas es diferente, ajusta PRIVATE_PATH abajo.
 * Desde public_html/urbano/ekusers/ subimos 3 niveles para llegar a /home1/erickedu/
 * y luego entramos a /ekusers (la carpeta privada).
 * ───────────────────────────────────────────────────────────────────────────
 */

// Ruta absoluta a la carpeta PRIVADA (fuera de public_html)
define('PRIVATE_PATH', dirname(__DIR__, 3) . '/ekusers');

// Verificación de seguridad: si la carpeta privada no existe, detener ejecución
if (!is_dir(PRIVATE_PATH)) {
    http_response_code(500);
    error_log('[EK Accesos] PRIVATE_PATH no encontrado: ' . PRIVATE_PATH);
    die('Error de configuración del servidor.');
}

// Cargar núcleo de la aplicación
require_once PRIVATE_PATH . '/config/config.php';
require_once PRIVATE_PATH . '/config/database.php';
require_once PRIVATE_PATH . '/helpers/functions.php';
require_once PRIVATE_PATH . '/helpers/auth.php';
require_once PRIVATE_PATH . '/helpers/csrf.php';
require_once PRIVATE_PATH . '/core/Controller.php';
require_once PRIVATE_PATH . '/core/Router.php';

// Sesión
startSession();

// Autoloader — Controladores y Modelos
spl_autoload_register(function (string $class): void {
    $dirs = [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
    ];
    foreach ($dirs as $dir) {
        $file = $dir . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Despachar
$router = new Router();
$router->dispatch();
