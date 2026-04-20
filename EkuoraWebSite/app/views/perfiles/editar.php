<?php
$pagina_actual = 'perfiles';
$titulo = 'Editar Perfil | Ekuora Admin';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<style>
    /* ============================================
       EKUORA ADMIN - EDITAR PERFIL
       Ultra Glass Pantone Edition
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

    .ek-admin-hero {
        background: linear-gradient(135deg, var(--ek-navy) 0%, var(--ek-navy-light) 100%);
        border-radius: 0 0 var(--radius-xl) var(--radius-xl);
        padding: 3rem 2rem;
        margin: -1.5rem -1.5rem 2rem;
        position: relative;
        overflow: hidden;
    }

    .ek-admin-hero::before {
        content: '';
        position: absolute;
        top: -100px;
        right: -100px;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(237, 139, 0, 0.3) 0%, transparent 70%);
        border-radius: 50%;
    }

    .ek-admin-hero-content {
        position: relative;
        z-index: 10;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 2rem;
    }

    .ek-admin-hero-badge {
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

    .ek-admin-hero-badge::before {
        content: '';
        width: 8px;
        height: 8px;
        background: var(--ek-orange);
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .ek-admin-hero-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .ek-admin-hero-title i { color: var(--ek-orange); }
    .ek-admin-hero-subtitle { font-size: 1.1rem; color: var(--ek-sky-light); }

    .ek-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 1rem 2rem;
        border-radius: var(--radius-full);
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        transition: var(--transition);
        cursor: pointer;
        border: none;
    }

    .ek-btn-primary {
        background: var(--ek-orange);
        color: white;
        box-shadow: 0 4px 20px rgba(237, 139, 0, 0.4);
    }

    .ek-btn-primary:hover {
        background: var(--ek-orange-light);
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(237, 139, 0, 0.5);
        color: white;
    }

    .ek-btn-secondary {
        background: white;
        color: var(--ek-navy);
        box-shadow: var(--glass-shadow);
    }

    .ek-btn-secondary:hover { background: var(--ek-sky-pale); color: var(--ek-navy); }
    .ek-btn-sm { padding: 0.6rem 1.25rem; font-size: 0.9rem; }

    /* Cards */
    .ek-card {
        background: var(--glass-bg);
        backdrop-filter: var(--glass-blur);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        margin-bottom: 1.5rem;
        transition: var(--transition);
    }

    .ek-card:hover { box-shadow: 0 12px 40px rgba(0, 43, 73, 0.15); }

    .ek-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.5rem;
        border-bottom: 1px solid var(--glass-border);
        background: linear-gradient(135deg, rgba(0, 43, 73, 0.03) 0%, transparent 100%);
    }

    .ek-card-header-left { display: flex; align-items: center; gap: 1rem; }

    .ek-card-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 43, 73, 0.1);
        border-radius: var(--radius-md);
        color: var(--ek-navy);
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .ek-card-icon.orange { background: rgba(237, 139, 0, 0.12); color: var(--ek-orange); }
    .ek-card-icon.sky { background: rgba(122, 153, 172, 0.15); color: var(--ek-sky); }

    .ek-card-title { font-size: 1.1rem; font-weight: 700; color: var(--ek-navy); margin: 0 0 0.2rem; }
    .ek-card-subtitle { font-size: 0.85rem; color: var(--ek-slate); margin: 0; }
    .ek-card-body { padding: 1.5rem; }

    /* Form */
    .ek-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .ek-form-group { display: flex; flex-direction: column; gap: 0.5rem; }

    .ek-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--ek-navy);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .ek-label i { color: var(--ek-sky); }
    .ek-label .req { color: #ef4444; }

    .ek-input {
        padding: 0.85rem 1rem;
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-md);
        background: white;
        color: var(--ek-navy);
        font-size: 0.95rem;
        transition: var(--transition);
        width: 100%;
    }

    .ek-input:focus {
        outline: none;
        border-color: var(--ek-orange);
        box-shadow: 0 0 0 3px rgba(237, 139, 0, 0.15);
    }

    /* Users alert */
    .ek-users-alert {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.85rem 1.25rem;
        background: rgba(122, 153, 172, 0.1);
        border: 1px solid rgba(122, 153, 172, 0.3);
        border-radius: var(--radius-md);
        margin-top: 1.25rem;
        font-size: 0.9rem;
        color: var(--ek-slate);
    }

    .ek-users-alert i { color: var(--ek-sky); font-size: 1.1rem; }
    .ek-users-alert strong { color: var(--ek-navy); }

    /* Warning alert */
    .ek-warning-alert {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        background: rgba(237, 139, 0, 0.08);
        border: 1px solid rgba(237, 139, 0, 0.3);
        border-left: 4px solid var(--ek-orange);
        border-radius: var(--radius-md);
        padding: 1rem 1.25rem;
        margin-bottom: 1.25rem;
        font-size: 0.9rem;
        color: var(--ek-slate);
    }

    .ek-warning-alert i { color: var(--ek-orange); font-size: 1.1rem; flex-shrink: 0; margin-top: 2px; }

    /* Permisos Grid */
    .ek-perms-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 1.25rem;
    }

    .ek-perm-module {
        background: var(--ek-sky-pale);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-md);
        padding: 1.25rem;
        transition: var(--transition);
    }

    .ek-perm-module:hover {
        border-color: var(--ek-orange);
        box-shadow: 0 4px 16px rgba(0, 43, 73, 0.1);
    }

    .ek-perm-module-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .ek-perm-module-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--ek-navy);
        margin: 0;
        text-transform: capitalize;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .ek-perm-module-title i { color: var(--ek-orange); }

    .ek-toggle-btn {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(237, 139, 0, 0.12);
        color: var(--ek-orange);
        border: none;
        border-radius: var(--radius-sm);
        cursor: pointer;
        font-size: 0.85rem;
        transition: var(--transition);
    }

    .ek-toggle-btn:hover { background: var(--ek-orange); color: white; }

    .ek-check-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.5rem 0;
        border-bottom: 1px dashed var(--glass-border);
    }

    .ek-check-item:last-child { border-bottom: none; padding-bottom: 0; }

    .ek-check-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: var(--ek-orange);
        flex-shrink: 0;
        margin-top: 2px;
    }

    .ek-check-label {
        font-size: 0.875rem;
        color: var(--ek-navy);
        cursor: pointer;
        line-height: 1.4;
    }

    .ek-check-label strong { font-weight: 600; }
    .ek-check-label small { color: var(--ek-slate); font-size: 0.8rem; }

    /* Counter */
    .ek-counter-bar {
        background: rgba(0, 43, 73, 0.04);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-md);
        padding: 1rem 1.25rem;
        margin-top: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .ek-counter-bar i { color: var(--ek-orange); }
    .ek-counter-bar span { color: var(--ek-navy); font-size: 0.9rem; }
    .ek-counter-bar strong { color: var(--ek-orange); font-size: 1.1rem; }

    /* Audit card */
    .ek-audit-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .ek-audit-item { font-size: 0.875rem; }
    .ek-audit-item strong { display: block; color: var(--ek-navy); margin-bottom: 0.2rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .ek-audit-item span { color: var(--ek-slate); }

    /* Dock */
    .ek-dock {
        position: sticky;
        bottom: 1.5rem;
        background: var(--glass-bg);
        backdrop-filter: var(--glass-blur);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-lg);
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: var(--glass-shadow);
        margin-top: 1.5rem;
    }

    .ek-dock-info { display: flex; align-items: center; gap: 0.5rem; color: var(--ek-slate); font-size: 0.85rem; }
    .ek-dock-info i { color: var(--ek-orange); }
    .ek-dock-actions { display: flex; gap: 0.75rem; }

    /* Animations */
    .ek-fade-up {
        animation: fadeUp 0.6s ease forwards;
        opacity: 0;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 992px) {
        .ek-admin-hero-content { flex-direction: column; align-items: flex-start; }
        .ek-form-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 768px) {
        .ek-admin-hero { padding: 2rem 1.5rem; margin: -1rem -1rem 1.5rem; }
        .ek-admin-hero-title { font-size: 1.75rem; }
        .ek-dock { flex-direction: column; gap: 1rem; }
        .ek-dock-actions { width: 100%; }
        .ek-btn { width: 100%; justify-content: center; }
    }
</style>

<!-- Hero Admin -->
<section class="ek-admin-hero ek-fade-up">
    <div class="ek-admin-hero-content">
        <div>
            <div class="ek-admin-hero-badge">Editar Acceso</div>
            <h1 class="ek-admin-hero-title">
                <i class="bi bi-pencil-square"></i>
                Editar Perfil
            </h1>
            <p class="ek-admin-hero-subtitle">
                Modificando: <strong style="color: var(--ek-orange);"><?= e($perfil['nombre'] ?? $perfil['Nombre'] ?? '') ?></strong>
            </p>
        </div>
        <a href="<?= BASE_URL ?>perfiles" class="ek-btn ek-btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</section>

<form method="POST" action="<?= BASE_URL ?>perfiles/actualizar/<?= $perfil['id'] ?? $perfil['Id'] ?>" id="formPerfil">
    <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">

    <!-- Información Básica -->
    <div class="ek-card ek-fade-up" style="animation-delay: 0.1s;">
        <div class="ek-card-header">
            <div class="ek-card-header-left">
                <div class="ek-card-icon"><i class="bi bi-info-circle-fill"></i></div>
                <div>
                    <h3 class="ek-card-title">Información del Perfil</h3>
                    <p class="ek-card-subtitle">Nombre e identificación del rol</p>
                </div>
            </div>
        </div>
        <div class="ek-card-body">
            <div class="ek-form-grid">
                <div class="ek-form-group">
                    <label class="ek-label" for="nombre">
                        <i class="bi bi-person-badge"></i>
                        Nombre del Perfil <span class="req">*</span>
                    </label>
                    <input type="text" id="nombre" name="nombre" class="ek-input" required maxlength="50"
                        value="<?= e($perfil['nombre'] ?? $perfil['Nombre'] ?? '') ?>" autofocus>
                </div>

                <div class="ek-form-group">
                    <label class="ek-label" for="descripcion">
                        <i class="bi bi-text-paragraph"></i>
                        Descripción
                    </label>
                    <input type="text" id="descripcion" name="descripcion" class="ek-input" maxlength="255"
                        value="<?= e($perfil['descripcion'] ?? $perfil['Descripcion'] ?? '') ?>">
                </div>
            </div>

            <?php if (!empty($perfil['total_usuarios'])): ?>
                <div class="ek-users-alert">
                    <i class="bi bi-people-fill"></i>
                    <span><strong><?= $perfil['total_usuarios'] ?></strong> usuario(s) tienen asignado este perfil. Los cambios les afectarán.</span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Permisos -->
    <div class="ek-card ek-fade-up" style="animation-delay: 0.15s;">
        <div class="ek-card-header">
            <div class="ek-card-header-left">
                <div class="ek-card-icon orange"><i class="bi bi-key-fill"></i></div>
                <div>
                    <h3 class="ek-card-title">Permisos del Perfil <span style="color:#ef4444;">*</span></h3>
                    <p class="ek-card-subtitle">Acciones que puede realizar este rol en el sistema</p>
                </div>
            </div>
            <div style="display:flex; gap:0.5rem;">
                <button type="button" class="ek-btn ek-btn-secondary ek-btn-sm" onclick="seleccionarTodos()">
                    <i class="bi bi-check-all"></i> Todos
                </button>
                <button type="button" class="ek-btn ek-btn-secondary ek-btn-sm" onclick="deseleccionarTodos()">
                    <i class="bi bi-x-lg"></i> Ninguno
                </button>
            </div>
        </div>
        <div class="ek-card-body">
            <div class="ek-warning-alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span><strong>Importante:</strong> Los cambios en los permisos afectarán a todos los usuarios con este perfil asignado.</span>
            </div>

            <div class="ek-perms-grid">
                <?php foreach ($permisos_agrupados as $identificador => $grupo): ?>
                    <div class="ek-perm-module">
                        <div class="ek-perm-module-header">
                            <h4 class="ek-perm-module-title">
                                <i class="bi <?= $grupo['icono'] ?? 'bi-folder-fill' ?>"></i>
                                <?= $grupo['nombre'] ?? $identificador ?>
                            </h4>
                            <button type="button" class="ek-toggle-btn" onclick="toggleModulo('<?= $identificador ?>')" title="Seleccionar módulo">
                                <i class="bi bi-check-square"></i>
                            </button>
                        </div>

                        <div class="permisos-modulo" data-modulo="<?= $identificador ?>">
                            <?php foreach ($grupo['permisos'] as $perm): ?>
                                <?php $p_id = $perm['id'] ?? $perm['Id']; ?>
                                <div class="ek-check-item">
                                    <input type="checkbox"
                                        id="perm_<?= $p_id ?>"
                                        name="permisos[]"
                                        value="<?= $p_id ?>"
                                        data-modulo="<?= $identificador ?>"
                                        <?= in_array($p_id, $permisos_asignados) ? 'checked' : '' ?>>
                                    <label class="ek-check-label" for="perm_<?= $p_id ?>">
                                        <strong><?= ucfirst(explode('.', $perm['clave'] ?? $perm['Clave'])[1] ?? ($perm['clave'] ?? $perm['Clave'])) ?></strong>
                                        <?php if (!empty($perm['descripcion'] ?? $perm['Descripcion'])): ?>
                                            <br><small><?= e($perm['descripcion'] ?? $perm['Descripcion']) ?></small>
                                        <?php endif; ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="ek-counter-bar">
                <i class="bi bi-check-circle-fill"></i>
                <span>Permisos seleccionados: <strong id="contador">0</strong></span>
            </div>
        </div>
    </div>

    <!-- Auditoría -->
    <div class="ek-card ek-fade-up" style="animation-delay: 0.2s;">
        <div class="ek-card-header">
            <div class="ek-card-header-left">
                <div class="ek-card-icon sky"><i class="bi bi-clock-history"></i></div>
                <div>
                    <h3 class="ek-card-title">Información de Auditoría</h3>
                    <p class="ek-card-subtitle">Historial de cambios en este perfil</p>
                </div>
            </div>
        </div>
        <div class="ek-card-body">
            <div class="ek-audit-grid">
                <div class="ek-audit-item">
                    <strong>Creado</strong>
                    <span><?= formatoFechaHora($perfil['created_at'] ?? '') ?></span>
                </div>
                <?php if (!empty($perfil['updated_at'])): ?>
                    <div class="ek-audit-item">
                        <strong>Última Actualización</strong>
                        <span><?= formatoFechaHora($perfil['updated_at'] ?? '') ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Dock de Acciones -->
    <div class="ek-dock ek-fade-up" style="animation-delay: 0.25s;">
        <div class="ek-dock-info">
            <i class="bi bi-exclamation-circle-fill"></i>
            <span>Los cambios afectarán a todos los usuarios con este perfil</span>
        </div>
        <div class="ek-dock-actions">
            <a href="<?= BASE_URL ?>perfiles" class="ek-btn ek-btn-secondary">
                <i class="bi bi-x-lg"></i> Cancelar
            </a>
            <button type="submit" class="ek-btn ek-btn-primary" id="btnGuardar">
                <i class="bi bi-check-lg"></i> Guardar Cambios
            </button>
        </div>
    </div>
</form>

<script>
    function actualizarContador() {
        const checked = document.querySelectorAll('input[name="permisos[]"]:checked').length;
        document.getElementById('contador').textContent = checked;
    }

    function seleccionarTodos() {
        document.querySelectorAll('input[name="permisos[]"]').forEach(cb => cb.checked = true);
        actualizarContador();
    }

    function deseleccionarTodos() {
        document.querySelectorAll('input[name="permisos[]"]').forEach(cb => cb.checked = false);
        actualizarContador();
    }

    function toggleModulo(modulo) {
        const checkboxes = document.querySelectorAll(`input[data-modulo="${modulo}"]`);
        const algunoMarcado = Array.from(checkboxes).some(cb => cb.checked);
        checkboxes.forEach(cb => cb.checked = !algunoMarcado);
        actualizarContador();
    }

    document.querySelectorAll('input[name="permisos[]"]').forEach(cb => cb.addEventListener('change', actualizarContador));

    document.getElementById('formPerfil').addEventListener('submit', function(e) {
        const permisosSeleccionados = document.querySelectorAll('input[name="permisos[]"]:checked').length;
        if (permisosSeleccionados === 0) {
            e.preventDefault();
            if (typeof Swal !== 'undefined') {
                Swal.fire({ title: 'Atención', text: 'Debe seleccionar al menos un permiso para el perfil', icon: 'warning', confirmButtonColor: '#ED8B00' });
            } else {
                alert('Debe seleccionar al menos un permiso para el perfil');
            }
            return false;
        }

        if (typeof Swal !== 'undefined') {
            e.preventDefault();
            Swal.fire({
                title: '¿Guardar cambios?',
                text: 'Esto afectará a todos los usuarios con este perfil.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ED8B00',
                cancelButtonColor: '#425563',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'Cancelar',
                backdrop: 'rgba(0,43,73,0.5) blur(4px)'
            }).then((result) => {
                if (result.isConfirmed) {
                    const btnGuardar = document.getElementById('btnGuardar');
                    btnGuardar.disabled = true;
                    btnGuardar.innerHTML = '<i class="bi bi-hourglass-split"></i> Guardando...';
                    document.getElementById('formPerfil').submit();
                }
            });
        } else {
            const btnGuardar = document.getElementById('btnGuardar');
            btnGuardar.disabled = true;
            btnGuardar.innerHTML = '<i class="bi bi-hourglass-split"></i> Guardando...';
        }
    });

    actualizarContador();
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>
