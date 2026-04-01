<?php
// app/config/config.php - Configuración general del sistema

// Cargar variables de entorno desde .env
function loadEnv($path)
{
    if (!file_exists($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }

        // Eliminar comentarios de fin de línea
        $posHash = strpos($line, ' #');
        if ($posHash !== false) {
            $line = substr($line, 0, $posHash);
        }

        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            // Quitar comillas si las hay
            $value = trim($value, '"\'');

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
define('APP_NAME', $_ENV['APP_NAME'] ?? 'Ekuora');
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
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL);
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
ini_set('session.cookie_secure', (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'production') ? 1 : 0);
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.gc_maxlifetime', SESSION_LIFETIME * 60);

// =====================================================
// HEADERS DE SEGURIDAD
// =====================================================
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

if (!APP_DEBUG) {
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com; font-src 'self' https://cdn.jsdelivr.net https://fonts.gstatic.com; img-src 'self' data: *.ekuora.com.mx ekuora.com.mx;");
}

// =====================================================
// RUTAS DE ARCHIVOS
// =====================================================
if (!defined('PUBLIC_PATH')) {
    define('PUBLIC_PATH', dirname(rtrim(ROOT_PATH, '/')) . '/public_html/');
}
define('UPLOADS_PATH', ROOT_PATH . 'uploads/');
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
        ROOT_PATH . 'app/config/'
    ];

    foreach ($directories as $directory) {
        $file = $directory . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Requerir DB explícitamente ya que es core
require_once __DIR__ . '/db.php';

// =====================================================
// HELPERS ESENCIALES
// =====================================================
require_once ROOT_PATH . 'app/helpers/functions.php';
require_once ROOT_PATH . 'app/helpers/session.php'; // Session antes que Auth
require_once ROOT_PATH . 'app/helpers/auth.php';
require_once ROOT_PATH . 'app/helpers/csrf.php';
require_once ROOT_PATH . 'app/helpers/ratelimit.php';
require_once ROOT_PATH . 'app/helpers/logs.php';
require_once ROOT_PATH . 'app/helpers/security.php';
