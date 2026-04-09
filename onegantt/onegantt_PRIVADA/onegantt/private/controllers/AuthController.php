<?php
class AuthController
{
    private Auth $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function login(?string $param = null): void
    {
        if ($this->auth->check()) Router::redirect('dashboard');

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->auth->validateCsrf()) {
                $error = 'Token de seguridad inválido. Recarga la página.';
            } else {
                $result = $this->auth->login(
                    Sanitizer::post('email', 'email'),
                    $_POST['password'] ?? ''
                );
                if ($result === true) {
                    Router::redirectWithFlash('dashboard', 'Bienvenido, ' . $this->auth->userName());
                } else {
                    $error = $result;
                }
            }
        }

        $csrfField = $this->auth->csrfField();
        include ROOT_PATH . 'views/auth/login.php';
    }

    public function logout(?string $param = null): void
    {
        $this->auth->logout();
        Router::redirect('login');
    }
}
