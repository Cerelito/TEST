<?php
// app/controllers/AuthController.php

class AuthController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Mostrar formulario de login
     */
    public function index()
    {
        // Si ya está autenticado, redirigir a dashboard
        if (estaAutenticado()) {
            redirect('dashboard');
        }

        require_once VIEWS_PATH . 'auth/login.php';
    }

    /**
     * Procesar login
     */
    public function procesar()
    {
        verificarCSRF();

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        // Rate limiting
        if (!verificarRateLimit('login', 5, 15, $username)) {
            $tiempo = tiempoBloqueoRestante('login', 15, $username);
            setFlash('error', "Demasiados intentos fallidos. Intente nuevamente en " . ceil($tiempo / 60) . " minutos.");
            redirect('login');
        }

        // Verificar si está bloqueado
        if ($this->userModel->estaBloqueado($username)) {
            setFlash('error', 'Su cuenta ha sido bloqueada por múltiples intentos fallidos. Intente más tarde.');
            redirect('login');
        }

        // Buscar usuario
        $usuario = $this->userModel->getByUsernameOrEmail($username);

        if (!$usuario || !password_verify($password, $usuario['password_hash'])) {
            $this->userModel->registrarIntentoFallido($username);
            logSeguridad('login_fallido', "Intento de login fallido para: $username", null, 'warning');
            setFlash('error', 'Usuario o contraseña incorrectos.');
            redirect('login');
        }

        // Verificar si está activo
        if (!$usuario['activo']) {
            setFlash('error', 'Su cuenta está inactiva. Contacte al administrador.');
            redirect('login');
        }

        // Obtener permisos
        $usuario['permisos'] = $this->userModel->getPermisos($usuario['id']);

        // Login exitoso
        $this->userModel->limpiarIntentos($usuario['id']);
        limpiarRateLimit('login', $username);
        loginUsuario($usuario);

        // Si debe cambiar contraseña
        if ($usuario['debe_cambiar_password']) {
            redirect('cambiar-password');
        }

        redirect('dashboard');
    }

    /**
     * Logout
     */
    public function logout()
    {
        logoutUsuario();
        setFlash('success', 'Sesión cerrada exitosamente.');
        redirect('login');
    }

    /**
     * Formulario cambiar contraseña
     */
    public function cambiarPassword()
    {
        requireAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verificarCSRF();

            $password_actual = $_POST['password_actual'] ?? '';
            $password_nueva = $_POST['password_nueva'] ?? '';
            $password_confirmar = $_POST['password_confirmar'] ?? '';

            // Validaciones
            if (strlen($password_nueva) < 8) {
                setFlash('error', 'La contraseña debe tener al menos 8 caracteres.');
                redirect('cambiar-password');
            }

            if ($password_nueva !== $password_confirmar) {
                setFlash('error', 'Las contraseñas no coinciden.');
                redirect('cambiar-password');
            }

            // Verificar contraseña actual
            $usuario = $this->userModel->getById(usuarioId());

            if (!password_verify($password_actual, $usuario['password_hash'])) {
                setFlash('error', 'La contraseña actual es incorrecta.');
                redirect('cambiar-password');
            }

            // Actualizar contraseña
            if ($this->userModel->updatePassword(usuarioId(), $password_nueva)) {
                setFlash('success', 'Contraseña actualizada exitosamente.');
                redirect('dashboard');
            } else {
                setFlash('error', 'Error al actualizar la contraseña.');
                redirect('cambiar-password');
            }
        }

        require_once VIEWS_PATH . 'auth/cambiar-password.php';
    }

    /**
     * Solicitar recuperación de contraseña
     */
    public function solicitarRecuperacion()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verificarCSRF();

            $email = trim($_POST['email'] ?? '');

            // Rate limiting
            if (!verificarRateLimit('recuperacion', 3, 60, $email)) {
                setFlash('error', 'Demasiados intentos. Intente más tarde.');
                redirect('solicitar-recuperacion');
            }

            $usuario = $this->userModel->getByUsernameOrEmail($email);

            if ($usuario) {
                $token = $this->userModel->crearTokenRecuperacion($usuario['email']);

                if ($token) {
                    $mailer = new EmailHelper();
                    $mailer->recuperarPassword($usuario['email'], $usuario['nombre'], $token);
                }
            }

            // Siempre mostrar el mismo mensaje (seguridad)
            setFlash('success', 'Si el email existe, recibirá instrucciones para recuperar su contraseña.');
            redirect('login');
        }

        require_once VIEWS_PATH . 'auth/solicitar-recuperacion.php';
    }

    /**
     * Recuperar contraseña con token
     */
    public function recuperarPassword()
    {
        $token = $_GET['token'] ?? '';

        if (empty($token)) {
            setFlash('error', 'Token inválido.');
            redirect('login');
        }

        $usuario = $this->userModel->verificarTokenRecuperacion($token);

        if (!$usuario) {
            setFlash('error', 'Token inválido o expirado.');
            redirect('login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verificarCSRF();

            $password = $_POST['password'] ?? '';
            $password_confirmar = $_POST['password_confirmar'] ?? '';

            if (strlen($password) < 8) {
                setFlash('error', 'La contraseña debe tener al menos 8 caracteres.');
                redirect('recuperar-password?token=' . $token);
            }

            if ($password !== $password_confirmar) {
                setFlash('error', 'Las contraseñas no coinciden.');
                redirect('recuperar-password?token=' . $token);
            }

            if ($this->userModel->updatePassword($usuario['id'], $password)) {
                $this->userModel->limpiarTokenRecuperacion($usuario['id']);
                setFlash('success', 'Contraseña actualizada exitosamente. Ya puede iniciar sesión.');
                redirect('login');
            } else {
                setFlash('error', 'Error al actualizar la contraseña.');
                redirect('recuperar-password?token=' . $token);
            }
        }

        require_once VIEWS_PATH . 'auth/recuperar-password.php';
    }
}
