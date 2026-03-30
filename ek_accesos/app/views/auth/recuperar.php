<?php
$title = 'Recuperar Contraseña';
ob_start();
?>
<style>
    .recovery-card {
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
    .recovery-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 64px; height: 64px;
        background: rgba(99,102,241,0.15);
        border: 1px solid rgba(99,102,241,0.3);
        border-radius: 18px;
        margin: 0 auto 20px;
        color: #818cf8;
    }
    .recovery-title {
        font-size: 24px;
        font-weight: 800;
        color: #f1f5f9;
        text-align: center;
        margin-bottom: 8px;
        letter-spacing: -0.5px;
    }
    .recovery-desc {
        font-size: 14px;
        color: #64748b;
        text-align: center;
        margin-bottom: 32px;
        line-height: 1.6;
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
        margin-bottom: 24px;
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
    .btn-recovery {
        width: 100%;
        padding: 15px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border: none;
        border-radius: 14px;
        color: white;
        font-size: 15px;
        font-weight: 700;
        font-family: -apple-system, 'SF Pro Display', sans-serif;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 20px rgba(99,102,241,0.4);
        margin-bottom: 16px;
    }
    .btn-recovery:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 30px rgba(99,102,241,0.6);
    }
    .back-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-size: 13px;
        color: #64748b;
        text-decoration: none;
        transition: color 0.2s;
    }
    .back-link:hover { color: #818cf8; }
</style>

<div class="recovery-card">
    <div class="recovery-icon">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="16" x2="12" y2="12"/>
            <line x1="12" y1="8" x2="12.01" y2="8"/>
        </svg>
    </div>

    <h1 class="recovery-title">Recuperar Acceso</h1>
    <p class="recovery-desc">
        Ingresa tu correo electrónico y te enviaremos las instrucciones para restablecer tu contraseña.
    </p>

    <form method="POST" action="<?php echo BASE_URL; ?>/recuperar" id="recoveryForm">
        <?php echo csrfField(); ?>

        <label class="form-label" for="email">Correo Electrónico</label>
        <div class="input-wrapper">
            <span class="input-icon">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                </svg>
            </span>
            <input
                type="email"
                id="email"
                name="email"
                class="form-input"
                placeholder="correo@empresa.com"
                autocomplete="email"
                autofocus
                required
                value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
            >
        </div>

        <button type="submit" class="btn-recovery">
            Enviar Instrucciones
        </button>
    </form>

    <a href="<?php echo BASE_URL; ?>/login" class="back-link">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
        Volver al inicio de sesión
    </a>
</div>
<?php
$content = ob_get_clean();
include VIEWS_PATH . 'layouts/auth-layout.php';
?>
