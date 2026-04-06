<?php
declare(strict_types=1);

/**
 * EK Accesos – Application Entry Point
 *
 * Every HTTP request is routed through this file.
 * The .htaccess RewriteRule forwards all requests here via ?url=…
 */

// ---------------------------------------------------------------------------
// Core requires
// ---------------------------------------------------------------------------

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers/functions.php';
require_once __DIR__ . '/helpers/auth.php';
require_once __DIR__ . '/helpers/csrf.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Router.php';

// ---------------------------------------------------------------------------
// Session
// ---------------------------------------------------------------------------

startSession();

// ---------------------------------------------------------------------------
// Autoloader – Controllers & Models
// ---------------------------------------------------------------------------

/**
 * PSR-0-style autoloader for the application's controllers and models.
 *
 * Naming convention:
 *   Controllers  → app/controllers/ClassName.php
 *   Models       → app/models/ClassName.php
 *
 * Classes that do not match those directories are silently ignored so that
 * other requires/includes can handle them.
 */
spl_autoload_register(function (string $class): void {
    $directories = [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
    ];

    foreach ($directories as $dir) {
        $file = $dir . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// ---------------------------------------------------------------------------
// Dispatch
// ---------------------------------------------------------------------------

$router = new Router();
$router->dispatch();
