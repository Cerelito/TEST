<?php
/**
 * Cambiar Contrasena - Ekuora Ultra Glass Pantone
 */
$pagina_actual = 'cambiar-password';
$titulo = 'Cambiar Contrasena | ' . APP_NAME;
require_once VIEWS_PATH . 'layouts/header.php';
?>

<style>
    /* ============================================
       EKUORA CAMBIAR PASSWORD - ULTRA GLASS PANTONE
    ============================================ */
    :root {
        --ek-navy: #002B49;
        --ek-orange: #ED8B00;
        --ek-sky: #7A99AC;
        --ek-slate: #425563;
        --ek-navy-light: #003d66;
        --ek-orange-light: #ff9d1a;
        --ek-sky-light: #9bb5c4;
        --ek-sky-pale: #e8eff3;
        --ek-green: #22c55e;

        --glass-bg: rgba(255, 255, 255, 0.85);
        --glass-border: rgba(122, 153, 172, 0.3);
        --glass-shadow: 0 8px 32px rgba(0, 43, 73, 0.12);
        --glass-blur: blur(20px);

        --radius-sm: 12px;
        --radius-md: 16px;
        --radius-lg: 24px;
        --radius-xl: 32px;
        --radius-full: 9999px;

        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* ============================================
       HERO CAMBIAR PASSWORD
    ============================================ */
    .ek-hero {
        background: linear-gradient(135deg, var(--ek-navy) 0%, var(--ek-navy-light) 100%);
        border-radius: 0 0 var(--radius-xl) var(--radius-xl);
        padding: 3rem 2rem;
        margin: -1.5rem -1.5rem 2rem;
        position: relative;
        overflow: hidden;
    }

    .ek-hero::before {
        content: '';
        position: absolute;
        top: -100px;
        right: -100px;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(237, 139, 0, 0.3) 0%, transparent 70%);
        border-radius: 50%;
    }

    .ek-hero::after {
        content: '';
        position: absolute;
        bottom: -50px;
        left: 20%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(122, 153, 172, 0.2) 0%, transparent 70%);
        border-radius: 50%;
    }

    .ek-hero-content {
        position: relative;
        z-index: 10;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 2rem;
    }

    .ek-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: rgba(237, 139, 0, 0.2);
        border: 1px solid var(--ek-orange);
        border-radius: var(--radius-full);
        color: var(--ek-orange);
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .ek-hero-badge::before {
        content: '';
        width: 8px;
        height: 8px;
        background: var(--ek-orange);
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }
    }

    .ek-hero-title {
        font-family: 'Outfit', sans-serif;
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .ek-hero-title i {
        color: var(--ek-orange);
    }

    .ek-hero-subtitle {
        font-size: 1.1rem;
        color: var(--ek-sky-light);
    }

    .ek-hero-user-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: var(--radius-lg);
        padding: 1rem 1.5rem;
    }

    .ek-hero-avatar {
        width: 56px;
        height: 56px;
        background: var(--ek-orange);
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: 700;
        color: white;
        box-shadow: 0 8px 20px rgba(237, 139, 0, 0.4);
    }

    .ek-hero-user-text strong {
        display: block;
        color: white;
        font-size: 1rem;
    }

    .ek-hero-user-text span {
        color: var(--ek-sky-light);
        font-size: 0.85rem;
    }

    /* ============================================
       CARD PRINCIPAL
    ============================================ */
    .ek-card {
        background: var(--glass-bg);
        backdrop-filter: var(--glass-blur);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-lg);
        max-width: 700px;
        margin: 0 auto;
        overflow: hidden;
        transition: var(--transition);
    }

    .ek-card:hover {
        box-shadow: 0 12px 40px rgba(0, 43, 73, 0.15);
    }

    .ek-card-body {
        padding: 2rem;
    }

    /* ============================================
       ALERTAS
    ============================================ */
    .ek-alert {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 1rem 1.25rem;
        border-radius: var(--radius-md);
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
    }

    .ek-alert i {
        font-size: 1.25rem;
        margin-top: 2px;
    }

    .ek-alert.warning {
        background: rgba(237, 139, 0, 0.1);
        border: 1px solid rgba(237, 139, 0, 0.3);
        color: var(--ek-orange);
    }

    .ek-alert.danger {
        background: rgba(244, 63, 94, 0.1);
        border: 1px solid rgba(244, 63, 94, 0.3);
        color: #f43f5e;
    }

    .ek-alert.success {
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.3);
        color: var(--ek-green);
    }

    .ek-alert strong {
        display: block;
        margin-bottom: 0.25rem;
    }

    /* ============================================
       FORMULARIO
    ============================================ */
    .ek-form-group {
        margin-bottom: 1.5rem;
    }

    .ek-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--ek-navy);
        margin-bottom: 0.5rem;
    }

    .ek-label i {
        color: var(--ek-sky);
    }

    .ek-label .required {
        color: #f43f5e;
    }

    .ek-input-wrapper {
        position: relative;
    }

    .ek-input {
        width: 100%;
        padding: 0.9rem 3rem 0.9rem 1rem;
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-md);
        background: white;
        color: var(--ek-navy);
        font-size: 1rem;
        transition: var(--transition);
    }

    .ek-input:focus {
        outline: none;
        border-color: var(--ek-orange);
        box-shadow: 0 0 0 3px rgba(237, 139, 0, 0.15);
    }

    .ek-toggle-password {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--ek-sky);
        cursor: pointer;
        padding: 0.25rem;
        transition: var(--transition);
    }

    .ek-toggle-password:hover {
        color: var(--ek-orange);
    }

    .ek-input-hint {
        display: block;
        font-size: 0.8rem;
        color: var(--ek-slate);
        margin-top: 0.35rem;
    }

    .ek-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    /* ============================================
       BOX DE REQUISITOS
    ============================================ */
    .ek-requirements {
        background: rgba(122, 153, 172, 0.1);
        border: 1px solid rgba(122, 153, 172, 0.3);
        border-radius: var(--radius-md);
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
    }

    .ek-requirements-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--ek-navy);
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
    }

    .ek-requirements-title i {
        color: var(--ek-sky);
    }

    .ek-requirements ul {
        margin: 0;
        padding-left: 1.25rem;
        color: var(--ek-slate);
        font-size: 0.85rem;
    }

    .ek-requirements li {
        margin-bottom: 0.35rem;
    }

    /* ============================================
       BOTONES
    ============================================ */
    .ek-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.9rem 2rem;
        border-radius: var(--radius-full);
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        transition: var(--transition);
        cursor: pointer;
        border: none;
        width: 100%;
    }

    .ek-btn-primary {
        background: linear-gradient(135deg, var(--ek-orange), var(--ek-orange-light));
        color: white;
        box-shadow: 0 8px 20px rgba(237, 139, 0, 0.3);
    }

    .ek-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(237, 139, 0, 0.4);
        color: white;
    }

    .ek-btn-primary:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    .ek-btn-back {
        background: rgba(0, 43, 73, 0.08);
        color: var(--ek-slate);
        margin-top: 0.75rem;
    }

    .ek-btn-back:hover {
        background: rgba(0, 43, 73, 0.15);
        color: var(--ek-navy);
    }

    /* ============================================
       ANIMACIONES
    ============================================ */
    .ek-fade-up {
        animation: fadeUp 0.6s ease forwards;
        opacity: 0;
    }

    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ============================================
       RESPONSIVE
    ============================================ */
    @media (max-width: 768px) {
        .ek-hero {
            padding: 2rem 1.5rem;
            margin: -1rem -1rem 1.5rem;
        }

        .ek-hero-title {
            font-size: 1.75rem;
        }

        .ek-hero-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .ek-grid-2 {
            grid-template-columns: 1fr;
        }

        .ek-card-body {
            padding: 1.5rem;
        }
    }
</style>

<!-- Hero Section -->
<section class="ek-hero ek-fade-up">
    <div class="ek-hero-content">
        <div>
            <div class="ek-hero-badge">Seguridad de Cuenta</div>
            <h1 class="ek-hero-title">
                <i class="bi bi-shield-lock"></i>
                Cambiar Contrasena
            </h1>
            <p class="ek-hero-subtitle">Actualice su contrasena de acceso al sistema para mantener su cuenta segura.</p>
        </div>
        <div class="ek-hero-user-info">
            <div class="ek-hero-avatar">
                <?= strtoupper(substr(usuarioActual()['nombre'], 0, 1)) ?>
            </div>
            <div class="ek-hero-user-text">
                <strong>
                    <?= e(usuarioActual()['nombre']) ?>
                </strong>
                <span>@
                    <?= e(usuarioActual()['username']) ?>
                </span>
            </div>
        </div>
    </div>
</section>

<div class="ek-card ek-fade-up" style="animation-delay: 0.1s;">
    <div class="ek-card-body">

        <!-- Alerta: Debe cambiar -->
        <?php if (usuarioActual()['debe_cambiar_password']): ?>
            <div class="ek-alert warning">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>
                    <strong>Accion requerida</strong>
                    Debe cambiar su contrasena temporal antes de continuar navegando en el sistema.
                </div>
            </div>
        <?php endif; ?>

        <!-- Flash Messages -->
        <?php if (hasFlash('error')): ?>
            <div class="ek-alert danger">
                <i class="bi bi-x-circle-fill"></i>
                <span>
                    <?= getFlash('error') ?>
                </span>
            </div>
        <?php endif; ?>

        <?php if (hasFlash('success')): ?>
            <div class="ek-alert success">
                <i class="bi bi-check-circle-fill"></i>
                <span>
                    <?= getFlash('success') ?>
                </span>
            </div>
        <?php endif; ?>

        <!-- Formulario -->
        <form method="POST" action="<?= BASE_URL ?>cambiar-password" id="formCambiarPassword">
            <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">

            <div class="ek-form-group">
                <label class="ek-label">
                    <i class="bi bi-key"></i> Contrasena Actual <span class="required">*</span>
                </label>
                <div class="ek-input-wrapper">
                    <input type="password" id="password_actual" name="password_actual" class="ek-input" required
                        autofocus placeholder="Ingrese su contrasena actual">
                    <button type="button" class="ek-toggle-password" onclick="togglePassword('password_actual')">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <div class="ek-grid-2">
                <div class="ek-form-group">
                    <label class="ek-label">
                        <i class="bi bi-shield-check"></i> Nueva Contrasena <span class="required">*</span>
                    </label>
                    <div class="ek-input-wrapper">
                        <input type="password" id="password_nueva" name="password_nueva" class="ek-input" required
                            minlength="8" placeholder="Minimo 8 caracteres">
                        <button type="button" class="ek-toggle-password" onclick="togglePassword('password_nueva')">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <span class="ek-input-hint">Minimo 8 caracteres</span>
                </div>

                <div class="ek-form-group">
                    <label class="ek-label">
                        <i class="bi bi-check2-circle"></i> Confirmar Nueva <span class="required">*</span>
                    </label>
                    <div class="ek-input-wrapper">
                        <input type="password" id="password_confirmar" name="password_confirmar" class="ek-input"
                            required minlength="8" placeholder="Repita la nueva contrasena">
                        <button type="button" class="ek-toggle-password" onclick="togglePassword('password_confirmar')">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Requirements -->
            <div class="ek-requirements">
                <div class="ek-requirements-title">
                    <i class="bi bi-info-circle"></i> Requisitos de la contrasena
                </div>
                <ul>
                    <li>Minimo 8 caracteres</li>
                    <li>Se recomienda usar mayusculas, minusculas y numeros</li>
                    <li>Evite usar informacion personal obvia</li>
                </ul>
            </div>

            <button type="submit" class="ek-btn ek-btn-primary" id="btnCambiar">
                <i class="bi bi-check-lg"></i> Cambiar Contrasena
            </button>

            <?php if (!usuarioActual()['debe_cambiar_password']): ?>
                <a href="<?= BASE_URL ?>dashboard" class="ek-btn ek-btn-back">
                    <i class="bi bi-arrow-left"></i> Volver al Dashboard
                </a>
            <?php endif; ?>
        </form>
    </div>
</div>

<script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = input.nextElementSibling.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }

    document.getElementById('formCambiarPassword').addEventListener('submit', function (e) {
        const nueva = document.getElementById('password_nueva').value;
        const confirmar = document.getElementById('password_confirmar').value;

        if (nueva !== confirmar) {
            e.preventDefault();
            alert('Las contrasenas no coinciden');
            return false;
        }

        if (nueva.length < 8) {
            e.preventDefault();
            alert('La contrasena debe tener al menos 8 caracteres');
            return false;
        }

        const btn = document.getElementById('btnCambiar');
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Cambiando...';
    });

    // Auto-close alerts
    setTimeout(() => {
        document.querySelectorAll('.ek-alert').forEach(alert => {
            if (!alert.classList.contains('warning')) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        });
    }, 5000);
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>