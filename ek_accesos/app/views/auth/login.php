<?php
$title = 'Iniciar Sesión';
ob_start();
?>
<style>
    .login-card {
        width: 100%;
        max-width: 420px;
        margin: 0 auto;
        background: rgba(255,255,255,0.06);
        backdrop-filter: blur(40px);
        -webkit-backdrop-filter: blur(40px);
        border: 1px solid rgba(255,255,255,0.14);
        border-radius: 28px;
        padding: 44px 40px 36px;
        box-shadow: 0 32px 80px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.05) inset;
    }
    .login-logo {
        text-align: center;
        margin-bottom: 36px;
    }
    .login-logo-mark {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 72px; height: 72px;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #06b6d4 100%);
        border-radius: 22px;
        font-size: 28px;
        font-weight: 900;
        color: white;
        letter-spacing: -2px;
        box-shadow: 0 8px 32px rgba(99,102,241,0.5), 0 2px 8px rgba(0,0,0,0.3);
        margin-bottom: 16px;
    }
    .login-title {
        font-size: 28px;
        font-weight: 800;
        background: linear-gradient(135deg, #6366f1, #8b5cf6, #06b6d4);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 6px;
        letter-spacing: -0.5px;
    }
    .login-subtitle {
        font-size: 13px;
        color: #64748b;
        letter-spacing: 0.5px;
    }
    .form-group {
        margin-bottom: 18px;
        position: relative;
    }
    .form-label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 8px;
    }
    .input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }
    .input-icon {
        position: absolute;
        left: 14px;
        color: #475569;
        display: flex;
        align-items: center;
        pointer-events: none;
    }
    .form-input {
        width: 100%;
        padding: 13px 16px 13px 44px;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 14px;
        color: #f1f5f9;
        font-size: 15px;
        font-family: -apple-system, 'SF Pro Display', sans-serif;
        outline: none;
        transition: all 0.2s;
    }
    .form-input::placeholder { color: #475569; }
    .form-input:focus {
        border-color: rgba(99,102,241,0.6);
        background: rgba(99,102,241,0.06);
        box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
    }
    .toggle-password {
        position: absolute;
        right: 14px;
        background: none;
        border: none;
        color: #475569;
        cursor: pointer;
        padding: 4px;
        border-radius: 6px;
        display: flex; align-items: center;
        transition: color 0.2s;
    }
    .toggle-password:hover { color: #94a3b8; }
    .forgot-link {
        display: block;
        text-align: right;
        font-size: 12px;
        color: #818cf8;
        text-decoration: none;
        margin-bottom: 24px;
        transition: color 0.2s;
    }
    .forgot-link:hover { color: #a5b4fc; }
    .btn-login {
        width: 100%;
        padding: 15px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border: none;
        border-radius: 14px;
        color: white;
        font-size: 16px;
        font-weight: 700;
        font-family: -apple-system, 'SF Pro Display', sans-serif;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 20px rgba(99,102,241,0.4);
        letter-spacing: 0.3px;
    }
    .btn-login:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 30px rgba(99,102,241,0.6);
        background: linear-gradient(135deg, #818cf8, #a78bfa);
    }
    .btn-login:active { transform: translateY(0); }
    .login-footer {
        text-align: center;
        margin-top: 28px;
        font-size: 12px;
        color: #334155;
    }
    .login-footer span {
        color: #475569;
    }
    .login-footer strong {
        color: #6366f1;
    }
</style>

<div class="login-card">
    <div class="login-logo">
        <div class="login-logo-mark">EK</div>
        <h1 class="login-title">Accesos</h1>
        <p class="login-subtitle">Sistema de Gestión de Accesos</p>
    </div>

    <form method="POST" action="<?php echo BASE_URL; ?>/auth/do-login" id="loginForm">
        <?php echo csrfField(); ?>

        <div class="form-group">
            <label class="form-label" for="usuario">Usuario o Correo</label>
            <div class="input-wrapper">
                <span class="input-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </span>
                <input
                    type="text"
                    id="usuario"
                    name="credential"
                    class="form-input"
                    placeholder="usuario@empresa.com"
                    autocomplete="username"
                    autofocus
                    required
                    value="<?php echo htmlspecialchars($_POST['credential'] ?? ''); ?>"
                >
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Contraseña</label>
            <div class="input-wrapper">
                <span class="input-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </span>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-input"
                    placeholder="••••••••"
                    autocomplete="current-password"
                    required
                >
                <button type="button" class="toggle-password" onclick="togglePassword()" aria-label="Mostrar contraseña">
                    <svg id="eyeIcon" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
        </div>

        <a href="<?php echo BASE_URL; ?>/recuperar" class="forgot-link">¿Olvidaste tu contraseña?</a>

        <button type="submit" class="btn-login" id="loginBtn">
            Iniciar Sesión
        </button>
    </form>

    <div class="login-footer">
        <span><?php echo APP_NAME; ?> <strong>v3.0</strong> · Sistema de Gestión de Accesos</span>
    </div>
</div>

<script>
function togglePassword() {
    var input = document.getElementById('password');
    var icon = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
    } else {
        input.type = 'password';
        icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
    }
}
document.getElementById('loginForm').addEventListener('submit', function() {
    var btn = document.getElementById('loginBtn');
    btn.textContent = 'Iniciando sesión...';
    btn.disabled = true;
    btn.style.opacity = '0.7';
});
</script>
<?php
$content = ob_get_clean();
include VIEWS_PATH . 'layouts/auth-layout.php';
?>
