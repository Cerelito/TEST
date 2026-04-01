<?php
/**
 * index.php - Punto de entrada de la aplicación
 * Esta es la versión para la estructura de public_html
 */

// Definir rutas principales
define('PUBLIC_PATH', __DIR__ . '/');

// Buscar la carpeta privada (puede llamarse EkuoraWebSite o CARPETA_PRIVADA)
$posibles_nombres = ['EkuoraWebSite', 'CARPETA_PRIVADA'];
$private_path = null;

foreach ($posibles_nombres as $nombre) {
    // 1. Un nivel arriba de __DIR__ (Estructura estándar)
    $test_path = realpath(dirname(__DIR__) . '/' . $nombre) . DIRECTORY_SEPARATOR;
    if ($test_path && file_exists($test_path . 'app/config/config.php')) {
        $private_path = $test_path;
        break;
    }

    // 2. Misma carpeta que __DIR__ (Estructura plana)
    $test_path = realpath(__DIR__ . '/' . $nombre) . DIRECTORY_SEPARATOR;
    if ($test_path && file_exists($test_path . 'app/config/config.php')) {
        $private_path = $test_path;
        break;
    }
}

if (!$private_path) {
    die("Error crítico: No se encontró la carpeta privada del sistema. Verifique que 'EkuoraWebSite' o 'CARPETA_PRIVADA' existan.");
}

define('PRIVATE_PATH', $private_path);
define('ROOT_PATH', PRIVATE_PATH);

require_once PRIVATE_PATH . 'app/config/config.php';

// --- ENRUTADOR SIMPLE ---
$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$parts = array_filter(explode('/', $url));

// Valores por defecto
$controllerName = 'ProductosController';
$methodName = 'index';
$params = [];

if (!empty($parts)) {
    $firstPart = strtolower(array_shift($parts));

    // Mapeo de rutas especiales
    if ($firstPart === 'login' || $firstPart === 'logout' || $firstPart === 'cambiar-password' || $firstPart === 'recuperar-password' || $firstPart === 'solicitar-recuperacion') {
        $controllerName = 'AuthController';
        if ($firstPart === 'login')
            $methodName = 'index';
        elseif ($firstPart === 'logout')
            $methodName = 'logout';
        else
            $methodName = lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $firstPart))));
    } else {
        // Convertir kebab-case a PascalCase para controladores (ej. categorias-admin -> CategoriasAdminController)
        $controllerName = str_replace(' ', '', ucwords(str_replace('-', ' ', $firstPart))) . 'Controller';

        // Intentar obtener el método
        if (!empty($parts)) {
            $methodName = array_shift($parts);
            // Convertir kebab-case a camelCase para métodos (ej. crear-categoria -> crearCategoria)
            if (strpos($methodName, '-') !== false) {
                $methodName = lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $methodName))));
            }
        } else {
            $methodName = 'index';
        }
    }
    $params = array_values($parts);
}

// Controladores públicos (no requieren login)
$publicControllers = ['AuthController', 'ProductosController'];

// Verificar si el usuario está autenticado
if (!in_array($controllerName, $publicControllers) && !estaAutenticado()) {
    redirect('login');
}

// Cargar y ejecutar el controlador
if (file_exists(PRIVATE_PATH . "app/controllers/{$controllerName}.php")) {
    require_once PRIVATE_PATH . "app/controllers/{$controllerName}.php";
    $controller = new $controllerName();

    if (method_exists($controller, $methodName)) {
        call_user_func_array([$controller, $methodName], $params);
    } else {
        // Método no encontrado -> 404
        if (APP_DEBUG) {
            die("Error: El método {$methodName} no existe en {$controllerName}");
        }
        header("HTTP/1.0 404 Not Found");
        include VIEWS_PATH . 'errors/404.php';
    }
} else {
    // Controlador no encontrado -> 404
    if (APP_DEBUG) {
        die("Error: El controlador {$controllerName} no existe");
    }
    header("HTTP/1.0 404 Not Found");
    include VIEWS_PATH . 'errors/404.php';
}
