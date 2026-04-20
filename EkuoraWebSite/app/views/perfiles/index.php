<?php
$pagina_actual = 'perfiles';
$titulo = 'Perfiles y Permisos | Ekuora Admin';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<style>
    /* ============================================
       EKUORA ADMIN - PERFILES Y PERMISOS
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

    .ek-admin-hero::after {
        content: '';
        position: absolute;
        bottom: -80px;
        left: 20%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(122, 153, 172, 0.2) 0%, transparent 70%);
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
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.2); }
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

    .ek-admin-hero-subtitle {
        font-size: 1.1rem;
        color: var(--ek-sky-light);
    }

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

    .ek-btn-sm {
        padding: 0.6rem 1.25rem;
        font-size: 0.9rem;
    }

    .ek-btn-navy {
        background: var(--ek-navy);
        color: white;
        box-shadow: 0 4px 15px rgba(0, 43, 73, 0.3);
    }

    .ek-btn-navy:hover {
        background: var(--ek-navy-light);
        transform: translateY(-2px);
        color: white;
    }

    /* Profiles Grid */
    .ek-profiles-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    /* Profile Card */
    .ek-profile-card {
        background: var(--glass-bg);
        backdrop-filter: var(--glass-blur);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: var(--transition);
        display: flex;
        flex-direction: column;
    }

    .ek-profile-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 48px rgba(0, 43, 73, 0.15);
        border-color: var(--ek-orange);
    }

    .ek-profile-header {
        background: linear-gradient(135deg, rgba(0, 43, 73, 0.05) 0%, transparent 100%);
        padding: 1.5rem;
        border-bottom: 1px solid var(--glass-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .ek-profile-header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .ek-profile-icon {
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--ek-orange) 0%, var(--ek-orange-light) 100%);
        border-radius: var(--radius-md);
        color: white;
        font-size: 1.4rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(237, 139, 0, 0.3);
    }

    .ek-profile-name {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--ek-navy);
        margin: 0 0 0.2rem;
    }

    .ek-profile-desc {
        font-size: 0.85rem;
        color: var(--ek-slate);
        margin: 0;
    }

    .ek-user-count {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.4rem 0.85rem;
        background: rgba(0, 43, 73, 0.1);
        border-radius: var(--radius-full);
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--ek-navy);
        white-space: nowrap;
    }

    /* Profile Body */
    .ek-profile-body {
        padding: 1.25rem 1.5rem;
        flex: 1;
    }

    .ek-perms-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--ek-slate);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .ek-perms-label i { color: var(--ek-orange); }

    .ek-perms-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .ek-perm-tag {
        padding: 0.35rem 0.75rem;
        background: var(--ek-sky-pale);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-sm);
        font-size: 0.8rem;
        color: var(--ek-navy);
    }

    .ek-perm-tag strong {
        color: var(--ek-orange);
        text-transform: capitalize;
    }

    .ek-no-perms {
        font-size: 0.875rem;
        color: var(--ek-slate);
        font-style: italic;
    }

    /* Profile Footer */
    .ek-profile-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--glass-border);
        background: rgba(0, 43, 73, 0.02);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .ek-profile-date {
        font-size: 0.8rem;
        color: var(--ek-slate);
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .ek-profile-date i { color: var(--ek-sky); }

    .ek-actions {
        display: flex;
        gap: 0.5rem;
    }

    .ek-action-btn {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--radius-sm);
        border: none;
        font-size: 0.95rem;
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
    }

    .ek-action-btn.edit { background: rgba(0, 43, 73, 0.08); color: var(--ek-navy); }
    .ek-action-btn.edit:hover { background: var(--ek-navy); color: white; }
    .ek-action-btn.delete { background: rgba(239, 68, 68, 0.08); color: #ef4444; }
    .ek-action-btn.delete:hover { background: #ef4444; color: white; }

    /* Alert inline */
    .ek-inline-alert {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1rem;
        border-radius: var(--radius-sm);
        font-size: 0.8rem;
        font-weight: 500;
        margin-top: 0.75rem;
    }

    .ek-inline-alert.info {
        background: rgba(122, 153, 172, 0.15);
        color: var(--ek-sky);
        border: 1px solid rgba(122, 153, 172, 0.3);
    }

    .ek-inline-alert.protected {
        background: rgba(237, 139, 0, 0.1);
        color: var(--ek-orange);
        border: 1px solid rgba(237, 139, 0, 0.25);
    }

    /* Reference Card */
    .ek-card {
        background: var(--glass-bg);
        backdrop-filter: var(--glass-blur);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
    }

    .ek-card-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.5rem;
        border-bottom: 1px solid var(--glass-border);
        background: linear-gradient(135deg, rgba(0, 43, 73, 0.03) 0%, transparent 100%);
    }

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
    }

    .ek-card-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--ek-navy);
        margin: 0 0 0.2rem;
    }

    .ek-card-subtitle {
        font-size: 0.85rem;
        color: var(--ek-slate);
        margin: 0;
    }

    .ek-card-body {
        padding: 1.5rem;
    }

    .ek-ref-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .ek-ref-module h4 {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--ek-navy);
        margin: 0 0 0.6rem;
        text-transform: capitalize;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .ek-ref-module h4 i { color: var(--ek-orange); }

    .ek-ref-module ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .ek-ref-module li {
        font-size: 0.85rem;
        color: var(--ek-slate);
        padding: 0.2rem 0;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .ek-ref-module li i {
        font-size: 0.7rem;
        color: var(--ek-sky);
    }

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
    }

    @media (max-width: 768px) {
        .ek-admin-hero { padding: 2rem 1.5rem; margin: -1rem -1rem 1.5rem; }
        .ek-admin-hero-title { font-size: 1.75rem; }
        .ek-profiles-grid { grid-template-columns: 1fr; }
    }
</style>

<!-- Hero Admin -->
<section class="ek-admin-hero ek-fade-up">
    <div class="ek-admin-hero-content">
        <div>
            <div class="ek-admin-hero-badge">Control de Acceso</div>
            <h1 class="ek-admin-hero-title">
                <i class="bi bi-shield-check"></i>
                Perfiles y Permisos
            </h1>
            <p class="ek-admin-hero-subtitle">Administra los roles y niveles de acceso del sistema.</p>
        </div>
        <?php if (puedeCrear('perfiles')): ?>
            <a href="<?= BASE_URL ?>perfiles/crear" class="ek-btn ek-btn-primary">
                <i class="bi bi-plus-lg"></i> Nuevo Perfil
            </a>
        <?php endif; ?>
    </div>
</section>

<!-- Profiles Grid -->
<div class="ek-profiles-grid ek-fade-up" style="animation-delay: 0.1s;">
    <?php foreach ($perfiles as $perfil): ?>
        <?php
        $nombre = e($perfil['nombre'] ?? $perfil['Nombre'] ?? 'Sin Nombre');
        $id     = $perfil['id'] ?? $perfil['Id'];
        $totalU = $perfil['total_usuarios'] ?? 0;
        $protegido = in_array($perfil['nombre'] ?? $perfil['Nombre'] ?? '', ['Administrador', 'Supervisor', 'Capturista']);

        $permisos_agrupados = [];
        foreach ($perfil['permisos'] ?? [] as $perm) {
            $codigo = $perm['codigo'] ?? '';
            if (strpos($codigo, '.') !== false) {
                [$modulo, $accion] = explode('.', $codigo, 2);
                $permisos_agrupados[$modulo][] = $accion;
            } else {
                $permisos_agrupados['otros'][] = $codigo;
            }
        }
        ?>
        <div class="ek-profile-card">
            <div class="ek-profile-header">
                <div class="ek-profile-header-left">
                    <div class="ek-profile-icon">
                        <i class="bi bi-award"></i>
                    </div>
                    <div>
                        <h3 class="ek-profile-name"><?= $nombre ?></h3>
                        <?php if (!empty($perfil['descripcion'])): ?>
                            <p class="ek-profile-desc"><?= e($perfil['descripcion']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if ($totalU > 0): ?>
                    <span class="ek-user-count">
                        <i class="bi bi-people-fill"></i> <?= $totalU ?>
                    </span>
                <?php endif; ?>
            </div>

            <div class="ek-profile-body">
                <div class="ek-perms-label">
                    <i class="bi bi-key-fill"></i>
                    Permisos (<?= count($perfil['permisos'] ?? []) ?>)
                </div>

                <?php if (!empty($permisos_agrupados)): ?>
                    <div class="ek-perms-list">
                        <?php foreach ($permisos_agrupados as $modulo => $acciones): ?>
                            <div class="ek-perm-tag">
                                <strong><?= $modulo ?>:</strong>
                                <span><?= implode(', ', $acciones) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="ek-no-perms">Sin permisos asignados</p>
                <?php endif; ?>

                <?php if ($totalU > 0): ?>
                    <div class="ek-inline-alert info">
                        <i class="bi bi-info-circle-fill"></i>
                        No se puede eliminar: tiene <?= $totalU ?> usuario(s) asignado(s)
                    </div>
                <?php elseif ($protegido): ?>
                    <div class="ek-inline-alert protected">
                        <i class="bi bi-shield-lock-fill"></i>
                        Perfil del sistema protegido
                    </div>
                <?php endif; ?>
            </div>

            <div class="ek-profile-footer">
                <span class="ek-profile-date">
                    <i class="bi bi-calendar3"></i>
                    <?= formatoFecha($perfil['created_at'] ?? '') ?>
                </span>
                <div class="ek-actions">
                    <?php if (puedeEditar('perfiles')): ?>
                        <a href="<?= BASE_URL ?>perfiles/editar/<?= $id ?>" class="ek-action-btn edit" title="Editar perfil">
                            <i class="bi bi-pencil"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (puedeEliminar('perfiles') && $totalU == 0 && !$protegido): ?>
                        <button onclick="confirmarEliminacion(<?= $id ?>, '<?= $nombre ?>')"
                            class="ek-action-btn delete" title="Eliminar perfil">
                            <i class="bi bi-trash"></i>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Referencia de Permisos -->
<div class="ek-card ek-fade-up" style="animation-delay: 0.2s;">
    <div class="ek-card-header">
        <div class="ek-card-icon"><i class="bi bi-key"></i></div>
        <div>
            <h3 class="ek-card-title">Permisos Disponibles en el Sistema</h3>
            <p class="ek-card-subtitle">Referencia de todos los módulos y acciones configurables</p>
        </div>
    </div>
    <div class="ek-card-body">
        <div class="ek-ref-grid">
            <?php
            $todos_permisos = [];
            foreach ($perfiles as $p) {
                foreach ($p['permisos'] ?? [] as $perm) {
                    $parts = explode('.', $perm['codigo'] ?? '', 2);
                    if (count($parts) === 2) {
                        [$mod, $acc] = $parts;
                        if (!in_array($acc, $todos_permisos[$mod] ?? [])) {
                            $todos_permisos[$mod][] = $acc;
                        }
                    }
                }
            }
            ksort($todos_permisos);
            ?>
            <?php foreach ($todos_permisos as $modulo => $acciones): ?>
                <div class="ek-ref-module">
                    <h4><i class="bi bi-folder-fill"></i> <?= ucfirst($modulo) ?></h4>
                    <ul>
                        <?php foreach ($acciones as $accion): ?>
                            <li><i class="bi bi-chevron-right"></i> <?= ucfirst($accion) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
    function confirmarEliminacion(id, nombre) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '¿Eliminar Perfil?',
                text: `¿Estás seguro de eliminar el perfil "${nombre}"? Esta acción no se puede deshacer.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ED8B00',
                cancelButtonColor: '#425563',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                backdrop: `rgba(0,43,73,0.5) blur(4px)`
            }).then((result) => {
                if (result.isConfirmed) { ejecutarEliminacion(id); }
            });
        } else {
            if (confirm(`¿Está seguro de eliminar el perfil "${nombre}"?\n\nEsta acción no se puede deshacer.`)) {
                ejecutarEliminacion(id);
            }
        }
    }

    function ejecutarEliminacion(id) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= BASE_URL ?>perfiles/eliminar/' + id;
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = 'csrf_token';
        csrfInput.value = '<?= generarToken() ?>';
        form.appendChild(csrfInput);
        document.body.appendChild(form);
        form.submit();
    }
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>
