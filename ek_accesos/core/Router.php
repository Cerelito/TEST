<?php
/**
 * Router — maps incoming URLs to controller actions.
 *
 * URL convention:  /controller/action/param1/param2/...
 *
 * Special mappings (short aliases → Controller@action):
 *   /                       → DashboardController@index
 *   /auth/login             → AuthController@login
 *   /auth/logout            → AuthController@logout
 *   /auth/register          → AuthController@register
 *   /auth/forgot-password   → AuthController@forgotPassword
 *   /auth/reset-password    → AuthController@resetPassword
 */
class Router
{
    /**
     * Explicit route map: 'segment/segment' => ['Controller', 'method']
     * Takes precedence over the automatic controller/action resolution.
     */
    private array $routes = [
        // Root
        ''                              => ['DashboardController',    'index'],
        'dashboard'                     => ['DashboardController',    'index'],
        // Auth
        'login'                         => ['AuthController',         'loginPost'],
        'logout'                        => ['AuthController',         'logout'],
        'auth/login'                    => ['AuthController',         'login'],
        'auth/logout'                   => ['AuthController',         'logout'],
        'auth/do-login'                 => ['AuthController',         'doLogin'],
        'auth/recuperar'                => ['AuthController',         'solicitarRecuperacion'],
        'auth/enviar-recuperacion'      => ['AuthController',         'enviarRecuperacion'],
        'auth/cambiar-password'         => ['AuthController',         'cambiarPassword'],
        'auth/guardar-password'         => ['AuthController',         'guardarPassword'],
        'auth/forgot-password'          => ['AuthController',         'solicitarRecuperacion'],
        'auth/reset-password'           => ['AuthController',         'cambiarPassword'],
        // Empleados
        'empleados'                     => ['EmpleadosController',    'index'],
        'empleados/crear'               => ['EmpleadosController',    'crear'],
        'empleados/guardar'             => ['EmpleadosController',    'guardar'],
        // Programa Nivel
        'programa-nivel'                => ['ProgramaNivelController','index'],
        'programa-nivel/crear'          => ['ProgramaNivelController','crear'],
        'programa-nivel/guardar'        => ['ProgramaNivelController','guardar'],
        // Requisitores/Compradores
        'requisitores'                  => ['RequisitorController',   'index'],
        'requisitores/asignar'          => ['RequisitorController',   'asignar'],
        'compradores'                   => ['CompradorController',     'index'],
        'compradores/asignar'           => ['CompradorController',     'asignar'],
        // Centros de Costo
        'centros-costo'                 => ['CentrosCostoController', 'index'],
        'centros-costo/guardar'         => ['CentrosCostoController', 'guardar'],
        // Organigrama
        'organigrama'                   => ['OrganigramaController',  'index'],
        'organigrama/data'              => ['OrganigramaController',  'data'],
        // Usuarios
        'usuarios'                      => ['UsuariosController',     'index'],
        'usuarios/crear'                => ['UsuariosController',     'crear'],
        'usuarios/guardar'              => ['UsuariosController',     'guardar'],
    ];

    /**
     * Resolve the current URL and dispatch to the appropriate controller/action.
     */
    public function dispatch(): void
    {
        $url = $this->getUrl();

        // Try explicit map first (first two segments as key)
        $segments  = array_values(array_filter(explode('/', $url)));
        $routeKey  = implode('/', array_slice($segments, 0, 2));
        $routeKey1 = $segments[0] ?? '';

        if (isset($this->routes[$routeKey])) {
            [$controllerName, $action] = $this->routes[$routeKey];
            $params = array_slice($segments, 2);
        } elseif (isset($this->routes[$routeKey1])) {
            [$controllerName, $action] = $this->routes[$routeKey1];
            $params = array_slice($segments, 1);
        } else {
            // Automatic resolution: segments[0]=controller, segments[1]=action
            $controllerName = $this->toControllerName($segments[0] ?? 'dashboard');
            $action         = $this->toCamelCase($segments[1] ?? 'index');
            $params         = array_slice($segments, 2);
        }

        $this->callAction($controllerName, $action, $params);
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    /**
     * Read the URL from $_GET['url'] (rewrite rule) or PATH_INFO, then clean it.
     */
    private function getUrl(): string
    {
        if (!empty($_GET['url'])) {
            $url = $_GET['url'];
        } elseif (!empty($_SERVER['PATH_INFO'])) {
            $url = $_SERVER['PATH_INFO'];
        } else {
            return '';
        }

        // Strip leading slash, sanitize
        $url = ltrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = rtrim($url, '/');

        return $url;
    }

    /**
     * Convert a URL slug to a PascalCase controller class name.
     * e.g. "user-profile" → "UserProfileController"
     */
    private function toControllerName(string $slug): string
    {
        $parts = explode('-', strtolower($slug));
        $name  = implode('', array_map('ucfirst', $parts));
        return $name . 'Controller';
    }

    /**
     * Convert a URL slug to camelCase for a method name.
     * e.g. "forgot-password" → "forgotPassword"
     */
    private function toCamelCase(string $slug): string
    {
        $parts = explode('-', strtolower($slug));
        return $parts[0] . implode('', array_map('ucfirst', array_slice($parts, 1)));
    }

    /**
     * Instantiate the controller and invoke the action with params.
     */
    private function callAction(string $controllerName, string $action, array $params): void
    {
        // Guard: class must be loaded (autoloader in index.php handles this)
        if (!class_exists($controllerName)) {
            $this->notFound("Controller '{$controllerName}' not found.");
            return;
        }

        $controller = new $controllerName();

        if (!method_exists($controller, $action)) {
            $this->notFound("Action '{$action}' not found in '{$controllerName}'.");
            return;
        }

        // Call the action, spreading URL params as positional arguments
        call_user_func_array([$controller, $action], $params);
    }

    /**
     * Send a 404 response.
     */
    private function notFound(string $message = 'Página no encontrada.'): void
    {
        http_response_code(404);

        $viewFile = defined('VIEWS_PATH') ? VIEWS_PATH . 'errors/404.php' : null;

        if ($viewFile && file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo '<h1>404 — Página no encontrada</h1>';
            if (defined('APP_VERSION')) {
                // Only expose detail in dev; suppress in production
                echo '<p>' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</p>';
            }
        }
    }
}
