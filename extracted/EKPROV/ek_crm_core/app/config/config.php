<?php
// app/config/config.php - Configuración general del sistema

// Cargar variables de entorno desde .env
function loadEnv($path)
{
    if (!file_exists($path)) {
        die("Error: Archivo .env no encontrado en: $path");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// Cargar .env
loadEnv(__DIR__ . '/../../.env');

// =====================================================
// CONSTANTES DE APLICACIÓN
// =====================================================
define('APP_NAME', $_ENV['APP_NAME'] ?? 'Dublin Ek Prov.');
define('APP_ENV', $_ENV['APP_ENV'] ?? 'production');
define('APP_DEBUG', filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN));
define('BASE_URL', rtrim($_ENV['APP_URL'] ?? '', '/') . '/');

// ROOT_PATH debe ser definido por index.php (usando PRIVATE_PATH)
// Si no existe, usar fallback
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', rtrim($_ENV['ROOT_PATH'] ?? dirname(__DIR__), '/') . '/');
}

// =====================================================
// ZONA HORARIA
// =====================================================
date_default_timezone_set('America/Mexico_City');

// =====================================================
// MANEJO DE ERRORES
// =====================================================
if (APP_DEBUG) {
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
    ini_set('display_errors', 1);
    ini_set('log_errors', 1);
    ini_set('error_log', ROOT_PATH . 'logs/php-errors.log');
} else {
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', ROOT_PATH . 'logs/php-errors.log');
}

// =====================================================
// SEGURIDAD
// =====================================================
define('BCRYPT_COST', (int) ($_ENV['BCRYPT_COST'] ?? 12));
define('SESSION_LIFETIME', (int) ($_ENV['SESSION_LIFETIME'] ?? 30));
define('CSRF_TOKEN_EXPIRY', (int) ($_ENV['CSRF_TOKEN_EXPIRY'] ?? 3600));

// =====================================================
// CONFIGURACIÓN DE SESIONES
// =====================================================
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', !APP_DEBUG); // HTTPS en producción
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.gc_maxlifetime', SESSION_LIFETIME * 60);

// =====================================================
// HEADERS DE SEGURIDAD
// =====================================================
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// COMENTADO TEMPORALMENTE PARA ARREGLAR EL DISEÑO
/*
if (!APP_DEBUG) {
    // Content Security Policy (CSP) para prevenir XSS
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com; font-src 'self' https://cdn.jsdelivr.net https://fonts.gstatic.com; img-src 'self' data:;");
    
    // HSTS - HTTP Strict Transport Security (Forzar HTTPS)
    header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
}
*/

// =====================================================
// RUTAS DE ARCHIVOS
// =====================================================
define('UPLOADS_PATH', ROOT_PATH . 'uploads_privados/');

// =====================================================
// GOOGLE DRIVE CONFIG
// =====================================================
define('GOOGLE_DRIVE_CREDENTIALS', ROOT_PATH . 'app/config/credentials.json');
// ID de la carpeta raíz en Drive donde se crearán los proveedores
// Reemplazar con el ID real de la carpeta compartida con el Service Account
define('GOOGLE_DRIVE_ROOT_FOLDER_ID', $_ENV['GOOGLE_DRIVE_ROOT_FOLDER_ID'] ?? 'root');

if (!defined('PUBLIC_PATH')) {
    define('PUBLIC_PATH', dirname(__DIR__, 2) . '/public/');
}

define('VIEWS_PATH', ROOT_PATH . 'app/views/');

// Crear carpetas si no existen
if (!is_dir(UPLOADS_PATH)) {
    mkdir(UPLOADS_PATH, 0755, true);
}
if (!is_dir(ROOT_PATH . 'logs')) {
    mkdir(ROOT_PATH . 'logs', 0755, true);
}

// =====================================================
// AUTOLOADER SIMPLE
// =====================================================
spl_autoload_register(function ($class) {
    $directories = [
        ROOT_PATH . 'app/models/',
        ROOT_PATH . 'app/controllers/',
        ROOT_PATH . 'app/helpers/',
        ROOT_PATH . 'app/config/',
        ROOT_PATH . 'app/services/'
    ];

    foreach ($directories as $directory) {
        $file = $directory . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// =====================================================
// HELPERS ESENCIALES
// =====================================================
$helpers = ['functions.php', 'logs.php', 'ratelimit.php', 'auth.php', 'csrf.php', 'session.php'];

foreach ($helpers as $helper) {
    $path = ROOT_PATH . 'app/helpers/' . $helper;
    if (file_exists($path)) {
        require_once $path;
    } else {
        die("<h1>ERROR CRÍTICO DEL SISTEMA</h1>
             <p>No se pudo encontrar el archivo helper: <strong>$helper</strong></p>
             <p>Ruta buscada: <code>$path</code></p>
             <hr>
             <p>Por favor, verifique que la carpeta <code>app/helpers/</code> contenga todos los archivos necesarios.</p>");
    }
}