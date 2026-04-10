<?php
$rootPath = getenv('ROOT_PATH') ?: '/home1/erickedu/Controladores-Apotema/onegantt/';
define('ROOT_PATH', rtrim($rootPath, '/') . '/');

require_once ROOT_PATH . 'config/app.php';

spl_autoload_register(function (string $class): void {
    foreach (['core', 'models', 'controllers', 'helpers', 'exports'] as $dir) {
        $file1 = ROOT_PATH . $dir . '/' . $class . '.php';
        if (file_exists($file1)) { require_once $file1; return; }
        $file2 = ROOT_PATH . $dir . '/' . strtolower($class) . '.php';
        if (file_exists($file2)) { require_once $file2; return; }
    }
});

$auth  = new Auth();
$route = trim($_GET['route'] ?? '', '/');
$route = $route === '' ? 'dashboard' : $route;

if (!preg_match('/^[a-zA-Z0-9_\/\-]+$/', $route)) {
    http_response_code(400); die('Ruta inválida.');
}

$parts = explode('/', $route);

$routeMap = [
    // ── Auth
    'login'                     => ['AuthController',     'login'],
    'logout'                    => ['AuthController',     'logout'],

    // ── Core
    'dashboard'                 => ['DashboardController','index'],

    // ── Proyectos
    'projects'                  => ['ProjectController',  'index'],
    'projects/create'           => ['ProjectController',  'create'],
    'projects/edit'             => ['ProjectController',  'edit'],
    'projects/delete'           => ['ProjectController',  'delete'],

    // ── Tareas
    'tasks'                     => ['TaskController',     'index'],
    'tasks/create'              => ['TaskController',     'create'],
    'tasks/edit'                => ['TaskController',     'edit'],
    'tasks/gantt'               => ['TaskController',     'gantt'],
    'tasks/delete'              => ['TaskController',     'delete'],

    // ── Reportes
    'reports'                   => ['ReportController',   'index'],
    'reports/export'            => ['ReportController',   'export'],
    'reports/import'            => ['ReportController',   'import'],

    // ── Catálogos (solo admin)
    'catalogs/statuses'         => ['CatalogController',  'statuses'],
    'catalogs/statuses/create'  => ['CatalogController',  'statusCreate'],
    'catalogs/statuses/edit'    => ['CatalogController',  'statusEdit'],
    'catalogs/statuses/delete'  => ['CatalogController',  'statusDelete'],

    'catalogs/users'            => ['CatalogController',  'users'],
    'catalogs/users/create'     => ['CatalogController',  'userCreate'],
    'catalogs/users/edit'       => ['CatalogController',  'userEdit'],
    'catalogs/users/toggle'     => ['CatalogController',  'userToggle'],

    'catalogs/roles'            => ['CatalogController',  'roles'],
    'catalogs/roles/edit'       => ['CatalogController',  'roleEdit'],

    'catalogs/projects'         => ['ProjectController',  'index'],
    'catalogs/projects/create'  => ['ProjectController',  'create'],
    'catalogs/projects/edit'    => ['ProjectController',  'edit'],
    'catalogs/projects/delete'  => ['ProjectController',  'delete'],
];

// Construir clave: soporta hasta 3 segmentos (catalogs/statuses/edit)
$key = match(count($parts)) {
    1       => $parts[0],
    2       => $parts[0].'/'.$parts[1],
    default => $parts[0].'/'.$parts[1].'/'.$parts[2],
};
$param = $parts[3] ?? $parts[2] ?? null;
// Si la clave de 3 segmentos no existe, intentar con 2 + param
if (!isset($routeMap[$key]) && count($parts) >= 3) {
    $key2 = $parts[0].'/'.$parts[1];
    if (isset($routeMap[$key2])) {
        $key   = $key2;
        $param = $parts[2];
    }
}

if (!isset($routeMap[$key])) {
    http_response_code(404);
    include ROOT_PATH . 'views/layouts/404.php'; exit;
}

[$ctrl, $action] = $routeMap[$key];
(new $ctrl($auth))->$action($param);
