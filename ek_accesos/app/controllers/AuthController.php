<?php

class AuthController extends Controller
{
    private Usuario $userModel;

    public function __construct()
    {
        $this->userModel = new Usuario();
    }

    public function login()
    {
        if (isLoggedIn()) {
            redirect('dashboard');
        }
        $this->render('auth/login', ['title' => 'Iniciar Sesión']);
    }

    /** Handles the login POST from /login route (legacy form action) */
    public function loginPost()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('auth/login');
        }
        $this->doLogin();
    }

    public function doLogin()
    {
        verifyCSRF();

        $credential = trim($_POST['credential'] ?? '');
        $password   = $_POST['password'] ?? '';

        if (empty($credential) || empty($password)) {
            setFlash('error', 'Ingresa tu usuario/correo y contraseña.');
            redirect('auth/login');
        }

        // Find user by email or username
        $user = filter_var($credential, FILTER_VALIDATE_EMAIL)
            ? $this->userModel->findByEmail($credential)
            : $this->userModel->findByUsername($credential);

        if (!$user) {
            setFlash('error', 'Credenciales incorrectas.');
            redirect('auth/login');
        }

        // Check blocked
        if ($this->userModel->isBlocked($user)) {
            $mins = ceil((strtotime($user['bloqueado_hasta']) - time()) / 60);
            setFlash('error', "Cuenta bloqueada. Intenta en {$mins} minutos.");
            redirect('auth/login');
        }

        // Verify password
        if (!$this->userModel->verifyPassword($password, $user['password_hash'])) {
            $this->userModel->incrementLoginAttempts($user['id']);
            setFlash('error', 'Credenciales incorrectas.');
            redirect('auth/login');
        }

        // Check active and approved
        if (!$user['activo']) {
            setFlash('error', 'Tu cuenta está desactivada. Contacta al administrador.');
            redirect('auth/login');
        }

        if (!$user['aprobado']) {
            setFlash('error', 'Tu cuenta está pendiente de aprobación por el administrador.');
            redirect('auth/login');
        }

        // Reset attempts, set token
        $this->userModel->resetLoginAttempts($user['id']);
        $token = $this->userModel->setToken($user['id']);

        // Store in session
        loginUser($user);
        logAction('login', 'auth', "Inicio de sesión: {$user['email']}");

        redirect('dashboard');
    }

    public function logout()
    {
        $user = currentUser();
        if ($user) {
            logAction('logout', 'auth', "Cierre de sesión: {$user['email']}");
        }
        logoutUser();
        redirect('auth/login');
    }

    public function solicitarRecuperacion()
    {
        $this->render('auth/recuperar', ['title' => 'Recuperar Contraseña']);
    }

    public function enviarRecuperacion()
    {
        verifyCSRF();
        $email = trim($_POST['email'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            setFlash('error', 'Ingresa un correo válido.');
            redirect('auth/recuperar');
        }

        $user = $this->userModel->findByEmail($email);
        if ($user) {
            $token = generateToken();
            $expira = date('Y-m-d H:i:s', time() + 3600);
            $this->userModel->execute(
                "UPDATE usuarios SET token_recuperacion = ?, token_expira = ? WHERE id = ?",
                [$token, $expira, $user['id']]
            );

            $link = BASE_URL . '/auth/cambiar-password/' . $token;
            sendEmail($email, 'Recuperar contraseña - EK Accesos', "
                <p>Hola {$user['nombre']},</p>
                <p>Haz clic en el siguiente enlace para cambiar tu contraseña:</p>
                <p><a href='$link'>$link</a></p>
                <p>Este enlace expira en 1 hora.</p>
            ");
        }

        // Always show success to prevent email enumeration
        setFlash('success', 'Si el correo existe recibirás las instrucciones en minutos.');
        redirect('auth/login');
    }

    public function cambiarPassword($token = '')
    {
        if (!$token) redirect('auth/login');

        $user = $this->userModel->queryOne(
            "SELECT * FROM usuarios WHERE token_recuperacion = ? AND token_expira > NOW() LIMIT 1",
            [$token]
        );

        if (!$user) {
            setFlash('error', 'El enlace es inválido o expiró.');
            redirect('auth/recuperar');
        }

        $this->render('auth/cambiar-password', ['title' => 'Nueva Contraseña', 'token' => $token]);
    }

    public function guardarPassword()
    {
        verifyCSRF();
        $token    = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';

        $user = $this->userModel->queryOne(
            "SELECT * FROM usuarios WHERE token_recuperacion = ? AND token_expira > NOW() LIMIT 1",
            [$token]
        );

        if (!$user) {
            setFlash('error', 'El enlace es inválido o expiró.');
            redirect('auth/recuperar');
        }

        if (strlen($password) < 8) {
            setFlash('error', 'La contraseña debe tener al menos 8 caracteres.');
            redirect('auth/cambiar-password/' . $token);
        }

        if ($password !== $confirm) {
            setFlash('error', 'Las contraseñas no coinciden.');
            redirect('auth/cambiar-password/' . $token);
        }

        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $this->userModel->execute(
            "UPDATE usuarios SET password_hash = ?, token_recuperacion = NULL, token_expira = NULL,
                                 debe_cambiar_password = 0 WHERE id = ?",
            [$hash, $user['id']]
        );

        setFlash('success', 'Contraseña actualizada. Ya puedes iniciar sesión.');
        redirect('auth/login');
    }
}
