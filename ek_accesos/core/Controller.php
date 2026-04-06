<?php
/**
 * Base Controller
 *
 * All application controllers should extend this class.
 * Provides render, redirect, JSON response, and authentication guards.
 */
class Controller
{
    /**
     * Render a view file, optionally extracting an associative data array
     * into local variables available inside the view.
     *
     * @param string $view  Relative path to the view (without .php extension),
     *                      e.g. 'dashboard/index' or 'auth/login'.
     * @param array  $data  Associative array of variables to pass to the view.
     */
    protected function render(string $view, array $data = []): void
    {
        // Make data keys available as variables inside the view
        if (!empty($data)) {
            extract($data, EXTR_SKIP);
        }

        $viewFile = VIEWS_PATH . ltrim($view, '/') . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(500);
            die('Vista no encontrada: ' . htmlspecialchars($viewFile, ENT_QUOTES, 'UTF-8'));
        }

        include $viewFile;
    }

    /**
     * Perform an HTTP redirect.
     *
     * @param string $url  Absolute URL or a path relative to BASE_URL.
     */
    protected function redirect(string $url): void
    {
        // If it doesn't look like an absolute URL, prepend BASE_URL
        if (!preg_match('#^https?://#i', $url)) {
            $url = rtrim(BASE_URL, '/') . '/' . ltrim($url, '/');
        }

        header('Location: ' . $url, true, 302);
        exit;
    }

    /**
     * Send a JSON response and terminate execution.
     *
     * @param mixed $data        Data to encode as JSON.
     * @param int   $statusCode  HTTP status code (default 200).
     */
    protected function json(mixed $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    /**
     * Ensure the current visitor is authenticated.
     * Redirects to the login page if no valid session exists.
     */
    protected function requireAuth(): void
    {
        if (!isLoggedIn()) {
            // Store the originally requested URL so we can redirect back after login
            if (session_status() === PHP_SESSION_ACTIVE) {
                $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? '';
            }

            $this->redirect('auth/login');
        }
    }

    /**
     * Ensure the current user has at least one of the required roles.
     * Redirects to the dashboard with a flash message on failure.
     *
     * @param string|string[] $role  A single role slug or an array of acceptable roles.
     */
    protected function requireRole(string|array $role): void
    {
        $this->requireAuth();

        $roles       = is_array($role) ? $role : [$role];
        $currentRole = $_SESSION['user']['rol'] ?? '';

        // superadmin/admin bypass all role checks
        if (in_array($currentRole, ['superadmin', 'admin'], true)) {
            return;
        }

        if (!in_array($currentRole, $roles, true)) {
            setFlash('error', 'No tienes permisos para acceder a esa sección.');
            $this->redirect('dashboard');
        }
    }
}
