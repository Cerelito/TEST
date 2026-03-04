<?php
/**
 * index.php - Punto de entrada público para EK Proveedores MVC v2.1
 *
 * UBICACIÓN: /public_html/urbano/ekprov/index.php
 *
 * Este archivo debe estar en la carpeta pública (accesible desde web).
 * Los archivos de la aplicación están en una ubicación privada y segura.
 */

// =====================================================
// CONFIGURACIÓN DE RUTAS PARA HOSTGATOR
// =====================================================

// IMPORTANTE: Ajusta esta ruta a la ubicación real en tu servidor Hostgator
// Esta es la ruta ABSOLUTA a la carpeta privada donde está app/, database/, etc.
define('PRIVATE_PATH', '/home1/erickedu/Controladores-Apotema/ek_crm_core/');
define('ROOT_PATH', PRIVATE_PATH);

// Ruta pública (donde está este archivo)
define('PUBLIC_PATH', __DIR__ . '/');

// =====================================================
// VERIFICAR QUE LA RUTA PRIVADA EXISTE
// =====================================================

if (!is_dir(PRIVATE_PATH)) {
    die('
    <h1 style="color: red;">ERROR DE CONFIGURACIÓN</h1>
    <p><strong>La ruta privada no existe o no es accesible:</strong></p>
    <pre>' . PRIVATE_PATH . '</pre>
    <hr>
    <p><strong>SOLUCIÓN:</strong></p>
    <ol>
        <li>Abre el archivo <code>index.php</code></li>
        <li>En la línea 15, cambia <code>TUUSUARIO</code> por tu usuario real de Hostgator</li>
        <li>Ejemplo: <code>/home1/erickedu/Controladores-Apotema/ek_crm_core/</code></li>
    </ol>
    <p><strong>Ruta actual del servidor:</strong> ' . __DIR__ . '</p>
    ');
}

// =====================================================
// INICIALIZACIÓN DEL SISTEMA
// =====================================================


// Cargar configuración desde la ubicación privada
require_once PRIVATE_PATH . 'app/config/config.php';

// Iniciar sesión (después de cargar la configuración para que los ini_set funcionan)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cargar base de datos
require_once PRIVATE_PATH . 'app/config/db.php';

// =====================================================
// ENRUTAMIENTO MVC
// =====================================================

// Obtener controlador y acción de la URL
$request_uri = $_SERVER['REQUEST_URI'];
$base_path = rtrim(parse_url(BASE_URL, PHP_URL_PATH), '/');
$request_path = substr(parse_url($request_uri, PHP_URL_PATH), strlen($base_path));
$request_path = trim($request_path, '/');

// Si la ruta está vacía, redirigir a login o dashboard
if (empty($request_path)) {
    if (estaAutenticado()) {
        $request_path = 'dashboard';
    } else {
        $request_path = 'auth';
    }
}

// Parsear ruta: controller/action/param1/param2
$parts = explode('/', $request_path);
$controller_name = $parts[0] ?? 'dashboard';
$action_name = $parts[1] ?? 'index';
$params = array_slice($parts, 2);

// Mapear rutas especiales (aliases)
$route_aliases = [
    'login' => 'auth',
    'logout' => 'auth',
    'cambiar-password' => 'auth',
    'solicitar-recuperacion' => 'auth',
    'recuperar-password' => 'auth'
];

// Si es un alias, reemplazar controller y action
if (isset($route_aliases[$controller_name])) {
    $original_route = $controller_name;
    $controller_name = $route_aliases[$controller_name];

    // Convertir el nombre original a método camelCase
    if ($original_route === 'login') {
        $action_name = $action_name === 'index' ? 'index' : $action_name;
    } elseif ($original_route === 'logout') {
        $action_name = 'logout';
    } elseif ($original_route === 'cambiar-password') {
        $action_name = 'cambiarPassword';
    } elseif ($original_route === 'solicitar-recuperacion') {
        $action_name = 'solicitarRecuperacion';
    } elseif ($original_route === 'recuperar-password') {
        $action_name = 'recuperarPassword';
    }
}

// Convertir controller-name a ControllerName
$controller_name = str_replace('-', '', ucwords($controller_name, '-'));
$controller_class = ucfirst($controller_name) . 'Controller';
$controller_file = PRIVATE_PATH . 'app/controllers/' . $controller_class . '.php';

// Verificar que el controlador existe
if (!file_exists($controller_file)) {
    // Si es un recurso estático (css, js, imagenes), no redirigir ni poner flash
    $ext = pathinfo($request_path, PATHINFO_EXTENSION);
    if (in_array($ext, ['css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'svg', 'ico', 'woff', 'woff2', 'ttf'])) {
        http_response_code(404);
        exit;
    }

    // Error 404
    http_response_code(404);
    setFlash('error', 'Página no encontrada');
    redirect('dashboard');
    exit;
}

// Cargar controlador
require_once $controller_file;

// Verificar que la clase existe
if (!class_exists($controller_class)) {
    die("Error: Clase $controller_class no encontrada");
}

try {
    // Instanciar controlador
    $controller = new $controller_class();

    // Verificar que el método existe
    if (!method_exists($controller, $action_name)) {
        http_response_code(404);
        setFlash('error', 'Acción no encontrada');
        redirect('dashboard');
        exit;
    }

    // Verificar expiración de sesión (excepto en auth)
    if ($controller_name !== 'Auth' && estaAutenticado()) {
        verificarExpiracionSesion();
    }

    // Ejecutar acción con parámetros
    call_user_func_array([$controller, $action_name], $params);

} catch (Throwable $e) {
    // CAPTURA DE ERROR FATAL
    http_response_code(500);
    echo '<div style="background:#f8d7da; color:#721c24; padding:20px; font-family:sans-serif; border:1px solid #f5c6cb; margin:20px;">';
    echo '<h3>🔴 ERROR AL EJECUTAR CONTROLADOR</h3>';
    echo '<p><strong>Mensaje:</strong> ' . $e->getMessage() . '</p>';
    echo '<p><strong>Archivo:</strong> ' . $e->getFile() . ':' . $e->getLine() . '</p>';
    echo '<pre style="background:#fff; padding:15px; border:1px solid #ddd; overflow:auto;">' . $e->getTraceAsString() . '</pre>';
    echo '</div>';
    exit;
}
