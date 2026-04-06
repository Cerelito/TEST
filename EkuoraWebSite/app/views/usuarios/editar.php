<?php
$pagina_actual = 'usuarios';
$titulo = 'Editar Usuario | Ekuora Admin';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<style>
    /* ============================================
       EKUORA EDITAR USUARIO - ULTRA GLASS PANTONE
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
       HERO
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

    .ek-hero-user {
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
       BOTONES
    ============================================ */
    .ek-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.85rem 1.5rem;
        border-radius: var(--radius-full);
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: var(--transition);
        cursor: pointer;
        border: none;
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
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .ek-btn-back:hover {
        background: rgba(255, 255, 255, 0.25);
        color: white;
    }

    .ek-btn-secondary {
        background: var(--ek-sky-pale);
        color: var(--ek-slate);
    }

    .ek-btn-secondary:hover {
        background: var(--ek-sky-light);
        color: var(--ek-navy);
    }

    /* ============================================
       CARDS
    ============================================ */
    .ek-card {
        background: var(--glass-bg);
        backdrop-filter: var(--glass-blur);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        margin-bottom: 1.5rem;
        transition: var(--transition);
    }

    .ek-card:hover {
        box-shadow: 0 12px 40px rgba(0, 43, 73, 0.15);
    }

    .ek-card-header {
        background: linear-gradient(135deg, rgba(0, 43, 73, 0.05) 0%, rgba(122, 153, 172, 0.05) 100%);
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--glass-border);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .ek-icon {
        width: 44px;
        height: 44px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }

    .ek-icon.navy {
        background: rgba(0, 43, 73, 0.15);
        color: var(--ek-navy);
    }

    .ek-icon.orange {
        background: rgba(237, 139, 0, 0.15);
        color: var(--ek-orange);
    }

    .ek-icon.sky {
        background: rgba(122, 153, 172, 0.2);
        color: var(--ek-sky);
    }

    .ek-icon.green {
        background: rgba(34, 197, 94, 0.15);
        color: var(--ek-green);
    }

    .ek-card-header h3 {
        font-family: 'Outfit', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--ek-navy);
        margin: 0;
    }

    .ek-card-body {
        padding: 1.5rem;
    }

    /* ============================================
       FORMULARIO
    ============================================ */
    .ek-grid-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .ek-form-group {
        margin-bottom: 1.25rem;
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
        padding: 0.85rem 1rem;
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-md);
        background: white;
        color: var(--ek-navy);
        font-size: 0.95rem;
        transition: var(--transition);
    }

    .ek-input:focus {
        outline: none;
        border-color: var(--ek-orange);
        box-shadow: 0 0 0 3px rgba(237, 139, 0, 0.15);
    }

    .ek-input:disabled {
        background: var(--ek-sky-pale);
        color: var(--ek-slate);
        cursor: not-allowed;
    }

    .ek-input.with-toggle {
        padding-right: 3rem;
    }

    .ek-select {
        width: 100%;
        padding: 0.85rem 1rem;
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-md);
        background: white;
        color: var(--ek-navy);
        font-size: 0.95rem;
        cursor: pointer;
        transition: var(--transition);
    }

    .ek-select:focus {
        outline: none;
        border-color: var(--ek-orange);
        box-shadow: 0 0 0 3px rgba(237, 139, 0, 0.15);
    }

    .ek-input-hint {
        display: block;
        font-size: 0.8rem;
        color: var(--ek-slate);
        margin-top: 0.35rem;
    }

    .ek-toggle-password {
        position: absolute;
        right: 0.75rem;
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

    /* ============================================
       ALERTAS
    ============================================ */
    .ek-alert {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 1rem 1.25rem;
        border-radius: var(--radius-md);
        margin-bottom: 1.25rem;
        font-size: 0.9rem;
    }

    .ek-alert i {
        font-size: 1.15rem;
        margin-top: 2px;
    }

    .ek-alert.warning {
        background: rgba(237, 139, 0, 0.1);
        border: 1px solid rgba(237, 139, 0, 0.3);
        color: var(--ek-orange);
    }

    .ek-alert.info {
        background: rgba(122, 153, 172, 0.15);
        border: 1px solid rgba(122, 153, 172, 0.3);
        color: var(--ek-slate);
    }

    /* ============================================
       CHECKBOX
    ============================================ */
    .ek-checkbox-group {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 1rem;
        background: var(--ek-sky-pale);
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: var(--transition);
    }

    .ek-checkbox-group:hover {
        background: var(--ek-sky-light);
    }

    .ek-checkbox {
        width: 20px;
        height: 20px;
        border: 2px solid var(--ek-sky);
        border-radius: 6px;
        cursor: pointer;
        accent-color: var(--ek-orange);
        flex-shrink: 0;
        margin-top: 2px;
    }

    .ek-checkbox:checked {
        background: var(--ek-orange);
        border-color: var(--ek-orange);
    }

    .ek-checkbox-label strong {
        display: block;
        color: var(--ek-navy);
        font-size: 0.95rem;
    }

    .ek-checkbox-label small {
        color: var(--ek-slate);
        font-size: 0.85rem;
    }

    /* ============================================
       INFO BOX
    ============================================ */
    .ek-info-box {
        background: linear-gradient(135deg, rgba(0, 43, 73, 0.05) 0%, rgba(122, 153, 172, 0.08) 100%);
        border-left: 4px solid var(--ek-navy);
        border-radius: var(--radius-md);
        padding: 1.25rem;
        margin-top: 1.25rem;
    }

    .ek-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
    }

    .ek-info-item strong {
        display: block;
        font-size: 0.8rem;
        color: var(--ek-slate);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }

    .ek-info-item span {
        color: var(--ek-navy);
        font-size: 0.9rem;
    }

    /* ============================================
       DOCK (BARRA FLOTANTE)
    ============================================ */
    .ek-dock {
        position: sticky;
        bottom: 1.5rem;
        margin-top: 1.5rem;
        background: var(--glass-bg);
        backdrop-filter: var(--glass-blur);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-lg);
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 1rem;
        box-shadow: var(--glass-shadow);
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

        .ek-dock {
            flex-direction: column;
        }

        .ek-dock .ek-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<!-- Hero Section -->
<section class="ek-hero ek-fade-up">
    <div class="ek-hero-content">
        <div>
            <div class="ek-hero-badge">Gestion de Usuarios</div>
            <h1 class="ek-hero-title">
                <i class="bi bi-pencil-square"></i>
                Editar Usuario
            </h1>
            <p class="ek-hero-subtitle">Modifica la informacion y permisos del colaborador.</p>
        </div>
        <div class="d-flex gap-3 align-items-center">
            <div class="ek-hero-user">
                <div class="ek-hero-avatar">
                    <?= strtoupper(substr($usuario['nombre'] ?? $usuario['Nombre'] ?? 'U', 0, 1)) ?>
                </div>
                <div class="ek-hero-user-text">
                    <strong>
                        <?= e($usuario['nombre'] ?? $usuario['Nombre'] ?? '') ?>
                    </strong>
                    <span>@
                        <?= e($usuario['username'] ?? $usuario['Username'] ?? '') ?>
                    </span>
                </div>
            </div>
            <a href="<?= BASE_URL ?>usuarios" class="ek-btn ek-btn-back">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</section>

<form method="POST" action="<?= BASE_URL ?>usuarios/actualizar/<?= $usuario['id'] ?? $usuario['Id'] ?>"
    id="formUsuario">
    <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">

    <!-- Informacion Personal -->
    <div class="ek-card ek-fade-up" style="animation-delay: 0.1s;">
        <div class="ek-card-header">
            <div class="ek-icon navy"><i class="bi bi-person-badge"></i></div>
            <h3>Informacion Personal</h3>
        </div>
        <div class="ek-card-body">
            <div class="ek-grid-2">
                <div class="ek-form-group">
                    <label class="ek-label">
                        <i class="bi bi-person"></i> Nombre Completo <span class="required">*</span>
                    </label>
                    <input type="text" id="nombre" name="nombre" class="ek-input" required maxlength="100"
                        value="<?= e($usuario['nombre'] ?? $usuario['Nombre'] ?? '') ?>" autofocus
                        placeholder="Nombre del colaborador">
                </div>

                <div class="ek-form-group">
                    <label class="ek-label">
                        <i class="bi bi-envelope"></i> Email <span class="required">*</span>
                    </label>
                    <input type="email" id="email" name="email" class="ek-input" required maxlength="100"
                        value="<?= e($usuario['email'] ?? $usuario['Email'] ?? '') ?>" placeholder="correo@ejemplo.com">
                </div>
            </div>
        </div>
    </div>

    <!-- Credenciales de Acceso -->
    <div class="ek-card ek-fade-up" style="animation-delay: 0.15s;">
        <div class="ek-card-header">
            <div class="ek-icon orange"><i class="bi bi-key"></i></div>
            <h3>Credenciales de Acceso</h3>
        </div>
        <div class="ek-card-body">
            <div class="ek-grid-2">
                <div class="ek-form-group">
                    <label class="ek-label">
                        <i class="bi bi-at"></i> Usuario
                    </label>
                    <input type="text" id="username" class="ek-input"
                        value="<?= e($usuario['username'] ?? $usuario['Username'] ?? '') ?>" disabled>
                    <span class="ek-input-hint">El nombre de usuario no se puede modificar</span>
                </div>

                <div class="ek-form-group">
                    <label class="ek-label">
                        <i class="bi bi-shield"></i> Perfil <span class="required">*</span>
                    </label>
                    <select id="perfil_id" name="perfil_id" class="ek-select" required>
                        <option value="">Seleccione un perfil...</option>
                        <?php foreach ($perfiles as $perfil): ?>
                            <?php $p_id = $perfil['id'] ?? $perfil['Id']; ?>
                            <option value="<?= $p_id ?>" <?= ($usuario['perfil_id'] ?? '') == $p_id ? 'selected' : '' ?>>
                                <?= e($perfil['nombre'] ?? $perfil['Nombre'] ?? '') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Cambiar Contrasena -->
    <div class="ek-card ek-fade-up" style="animation-delay: 0.2s;">
        <div class="ek-card-header">
            <div class="ek-icon sky"><i class="bi bi-shield-lock"></i></div>
            <h3>Cambiar Contrasena (Opcional)</h3>
        </div>
        <div class="ek-card-body">
            <div class="ek-alert warning">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>
                    <strong>Solo complete estos campos si desea cambiar la contrasena.</strong><br>
                    Deje los campos vacios para mantener la contrasena actual.
                </div>
            </div>

            <div class="ek-grid-2">
                <div class="ek-form-group">
                    <label class="ek-label">
                        <i class="bi bi-lock"></i> Nueva Contrasena
                    </label>
                    <div class="ek-input-wrapper">
                        <input type="password" id="nueva_password" name="nueva_password" class="ek-input with-toggle"
                            minlength="8" maxlength="255" placeholder="Minimo 8 caracteres">
                        <button type="button" class="ek-toggle-password" onclick="togglePassword('nueva_password')">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <span class="ek-input-hint">Minimo 8 caracteres</span>
                </div>

                <div class="ek-form-group">
                    <label class="ek-label">
                        <i class="bi bi-lock-fill"></i> Confirmar Contrasena
                    </label>
                    <div class="ek-input-wrapper">
                        <input type="password" id="confirmar_password" name="confirmar_password"
                            class="ek-input with-toggle" minlength="8" maxlength="255"
                            placeholder="Repita la contrasena">
                        <button type="button" class="ek-toggle-password" onclick="togglePassword('confirmar_password')">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <label class="ek-checkbox-group" style="margin-top: 1rem;">
                <input type="checkbox" id="enviar_email_password" name="enviar_email_password" value="1"
                    class="ek-checkbox">
                <div class="ek-checkbox-label">
                    <strong>Enviar por email</strong>
                    <small>Enviar nueva contrasena por email al usuario</small>
                </div>
            </label>
        </div>
    </div>

    <!-- Configuracion -->
    <div class="ek-card ek-fade-up" style="animation-delay: 0.25s;">
        <div class="ek-card-header">
            <div class="ek-icon green"><i class="bi bi-gear"></i></div>
            <h3>Configuracion</h3>
        </div>
        <div class="ek-card-body">
            <label class="ek-checkbox-group">
                <input type="checkbox" id="activo" name="activo" value="1" class="ek-checkbox" <?= $usuario['activo'] ? 'checked' : '' ?>>
                <div class="ek-checkbox-label">
                    <strong>Usuario activo</strong>
                    <small>El usuario podra iniciar sesion en el sistema</small>
                </div>
            </label>

            <?php if (!empty($usuario['ultimo_acceso'])): ?>
                <div class="ek-info-box">
                    <div class="ek-info-grid">
                        <div class="ek-info-item">
                            <strong>Ultimo acceso</strong>
                            <span>
                                <?= formatoFechaHora($usuario['ultimo_acceso'] ?? '') ?>
                            </span>
                        </div>
                        <div class="ek-info-item">
                            <strong>Fecha de creacion</strong>
                            <span>
                                <?= formatoFechaHora($usuario['created_at'] ?? '') ?>
                            </span>
                        </div>
                        <?php if (!empty($usuario['updated_at'])): ?>
                            <div class="ek-info-item">
                                <strong>Ultima actualizacion</strong>
                                <span>
                                    <?= formatoFechaHora($usuario['updated_at'] ?? '') ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Dock de Acciones -->
    <div class="ek-dock ek-fade-up" style="animation-delay: 0.3s;">
        <a href="<?= BASE_URL ?>usuarios" class="ek-btn ek-btn-secondary">
            <i class="bi bi-x-lg"></i> Cancelar
        </a>
        <button type="submit" class="ek-btn ek-btn-primary" id="btnGuardar">
            <i class="bi bi-check-lg"></i> Guardar Cambios
        </button>
    </div>
</form>

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

    document.getElementById('formUsuario').addEventListener('submit', function (e) {
        const nuevaPassword = document.getElementById('nueva_password').value;
        const confirmarPassword = document.getElementById('confirmar_password').value;

        if (nuevaPassword || confirmarPassword) {
            if (nuevaPassword !== confirmarPassword) {
                e.preventDefault();
                alert('Las contrasenas no coinciden');
                return false;
            }

            if (nuevaPassword.length < 8) {
                e.preventDefault();
                alert('La contrasena debe tener al menos 8 caracteres');
                return false;
            }
        }

        const btnGuardar = document.getElementById('btnGuardar');
        btnGuardar.disabled = true;
        btnGuardar.innerHTML = '<i class="bi bi-hourglass-split"></i> Guardando...';
    });
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>