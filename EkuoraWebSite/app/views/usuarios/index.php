<?php
$pagina_actual = 'usuarios';
$titulo = 'Gestion de Usuarios | Ekuora Admin';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<style>
    /* ============================================
       EKUORA USUARIOS - ULTRA GLASS PANTONE
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
       HERO USUARIOS
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
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .ek-card-header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .ek-icon {
        width: 48px;
        height: 48px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
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

    .ek-card-header-left h3 {
        font-family: 'Outfit', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--ek-navy);
        margin: 0 0 0.25rem;
    }

    .ek-card-header-left p {
        font-size: 0.85rem;
        color: var(--ek-slate);
        margin: 0;
    }

    .ek-card-body {
        padding: 1.5rem;
    }

    /* ============================================
       FILTROS
    ============================================ */
    .ek-filters {
        display: flex;
        gap: 1rem;
        align-items: flex-end;
        flex-wrap: wrap;
    }

    .ek-filter-group {
        flex: 1;
        min-width: 200px;
    }

    .ek-filter-group.search {
        min-width: 280px;
    }

    .ek-filter-label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--ek-navy);
        margin-bottom: 0.5rem;
    }

    .ek-search-box {
        position: relative;
    }

    .ek-search-box i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--ek-sky);
    }

    .ek-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-md);
        background: white;
        color: var(--ek-navy);
        font-size: 0.95rem;
        transition: var(--transition);
    }

    .ek-input.with-icon {
        padding-left: 2.75rem;
    }

    .ek-input:focus {
        outline: none;
        border-color: var(--ek-orange);
        box-shadow: 0 0 0 3px rgba(237, 139, 0, 0.15);
    }

    .ek-select {
        width: 100%;
        padding: 0.75rem 1rem;
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

    .ek-filter-actions {
        display: flex;
        gap: 0.5rem;
    }

    .ek-btn-icon {
        width: 48px;
        height: 48px;
        border: none;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1.1rem;
        transition: var(--transition);
        text-decoration: none;
    }

    .ek-btn-icon.primary {
        background: linear-gradient(135deg, var(--ek-orange), var(--ek-orange-light));
        color: white;
        box-shadow: 0 4px 15px rgba(237, 139, 0, 0.3);
    }

    .ek-btn-icon.secondary {
        background: var(--ek-sky-pale);
        color: var(--ek-slate);
    }

    .ek-btn-icon:hover {
        transform: translateY(-2px);
    }

    /* ============================================
       TABLA
    ============================================ */
    .ek-table-container {
        overflow-x: auto;
    }

    .ek-table {
        width: 100%;
        border-collapse: collapse;
    }

    .ek-table th {
        text-align: left;
        padding: 1rem 1.25rem;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--ek-slate);
        font-weight: 700;
        border-bottom: 2px solid var(--glass-border);
        background: var(--ek-sky-pale);
    }

    .ek-table td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--glass-border);
        vertical-align: middle;
    }

    .ek-table tr:last-child td {
        border-bottom: none;
    }

    .ek-table tr:hover td {
        background: rgba(122, 153, 172, 0.08);
    }

    /* Avatar */
    .ek-avatar {
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, var(--ek-navy), var(--ek-navy-light));
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1rem;
        color: white;
        flex-shrink: 0;
    }

    .ek-user-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .ek-user-name {
        font-weight: 600;
        color: var(--ek-navy);
    }

    .ek-user-username {
        font-size: 0.8rem;
        color: var(--ek-slate);
    }

    /* Badges */
    .ek-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.75rem;
        border-radius: var(--radius-full);
        font-size: 0.8rem;
        font-weight: 600;
    }

    .ek-badge.navy {
        background: rgba(0, 43, 73, 0.1);
        color: var(--ek-navy);
    }

    .ek-badge.orange {
        background: rgba(237, 139, 0, 0.1);
        color: var(--ek-orange);
    }

    /* Status */
    .ek-status {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.75rem;
        border-radius: var(--radius-full);
        font-size: 0.8rem;
        font-weight: 600;
    }

    .ek-status.active {
        background: rgba(34, 197, 94, 0.1);
        color: var(--ek-green);
    }

    .ek-status.active::before {
        content: '';
        width: 6px;
        height: 6px;
        background: var(--ek-green);
        border-radius: 50%;
    }

    .ek-status.inactive {
        background: rgba(148, 163, 184, 0.15);
        color: #64748b;
    }

    /* Acciones */
    .ek-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }

    .ek-action-btn {
        width: 36px;
        height: 36px;
        border: none;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1rem;
        transition: var(--transition);
        text-decoration: none;
    }

    .ek-action-btn.edit {
        background: rgba(0, 43, 73, 0.1);
        color: var(--ek-navy);
    }

    .ek-action-btn.toggle {
        background: rgba(237, 139, 0, 0.1);
        color: var(--ek-orange);
    }

    .ek-action-btn.delete {
        background: rgba(244, 63, 94, 0.1);
        color: #f43f5e;
    }

    .ek-action-btn:hover {
        transform: scale(1.1);
    }

    /* Empty State */
    .ek-empty {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--ek-slate);
    }

    .ek-empty i {
        font-size: 3rem;
        color: var(--ek-sky-light);
        margin-bottom: 1rem;
    }

    /* Animaciones */
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

    /* Responsive */
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

        .ek-filters {
            flex-direction: column;
        }

        .ek-filter-group {
            width: 100%;
        }

        .ek-table th,
        .ek-table td {
            padding: 0.75rem;
        }
    }
</style>

<!-- Hero Section -->
<section class="ek-hero ek-fade-up">
    <div class="ek-hero-content">
        <div>
            <div class="ek-hero-badge">Control de Acceso</div>
            <h1 class="ek-hero-title">
                <i class="bi bi-people"></i>
                Usuarios del Sistema
            </h1>
            <p class="ek-hero-subtitle">Administra los permisos y perfiles de acceso a la plataforma.</p>
        </div>
        <?php if (puedeCrear('usuarios')): ?>
            <a href="<?= BASE_URL ?>usuarios/crear" class="ek-btn ek-btn-primary">
                <i class="bi bi-person-plus"></i> Nuevo Usuario
            </a>
        <?php endif; ?>
    </div>
</section>

<!-- Filtros -->
<div class="ek-card ek-fade-up" style="animation-delay: 0.1s;">
    <div class="ek-card-header">
        <div class="ek-card-header-left">
            <div class="ek-icon navy"><i class="bi bi-filter"></i></div>
            <div>
                <h3>Filtros de Busqueda</h3>
                <p>Encuentra usuarios por nombre, email o perfil</p>
            </div>
        </div>
    </div>
    <div class="ek-card-body">
        <form method="GET" action="<?= BASE_URL ?>usuarios" class="ek-filters">
            <div class="ek-filter-group search">
                <label class="ek-filter-label">Busqueda</label>
                <div class="ek-search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" name="busqueda" class="ek-input with-icon"
                        placeholder="Nombre, usuario, email..." value="<?= e($_GET['busqueda'] ?? '') ?>">
                </div>
            </div>
            <div class="ek-filter-group">
                <label class="ek-filter-label">Perfil</label>
                <select name="perfil_id" class="ek-select">
                    <option value="">Todos los perfiles</option>
                    <?php foreach ($perfiles as $perfil): ?>
                        <option value="<?= $perfil['id'] ?? $perfil['Id'] ?>" <?= ($_GET['perfil_id'] ?? '') == ($perfil['id'] ?? $perfil['Id']) ? 'selected' : '' ?>>
                            <?= e($perfil['nombre'] ?? $perfil['Nombre'] ?? '') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="ek-filter-group">
                <label class="ek-filter-label">Estado</label>
                <select name="estado" class="ek-select">
                    <option value="">Todos</option>
                    <option value="1" <?= ($_GET['estado'] ?? '') === '1' ? 'selected' : '' ?>>Activos</option>
                    <option value="0" <?= ($_GET['estado'] ?? '') === '0' ? 'selected' : '' ?>>Inactivos</option>
                </select>
            </div>
            <div class="ek-filter-actions">
                <button type="submit" class="ek-btn-icon primary" title="Buscar">
                    <i class="bi bi-search"></i>
                </button>
                <a href="<?= BASE_URL ?>usuarios" class="ek-btn-icon secondary" title="Limpiar">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de Usuarios -->
<div class="ek-card ek-fade-up" style="animation-delay: 0.2s;">
    <div class="ek-table-container">
        <table class="ek-table">
            <thead>
                <tr>
                    <th>Colaborador</th>
                    <th>Email</th>
                    <th>Perfil</th>
                    <th>Estado</th>
                    <th>Ultimo Acceso</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($usuarios)): ?>
                    <?php foreach ($usuarios as $user): ?>
                        <tr>
                            <td>
                                <div class="ek-user-info">
                                    <div class="ek-avatar">
                                        <?= strtoupper(substr($user['nombre'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="ek-user-name">
                                            <?= e($user['nombre']) ?>
                                        </div>
                                        <div class="ek-user-username">@
                                            <?= e($user['username']) ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?= e($user['email']) ?>
                            </td>
                            <td>
                                <span class="ek-badge navy">
                                    <?= e($user['perfil_nombre']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($user['activo']): ?>
                                    <span class="ek-status active">Activo</span>
                                <?php else: ?>
                                    <span class="ek-status inactive">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small style="color: var(--ek-slate);">
                                    <?= $user['ultimo_acceso'] ? formatoFechaHora($user['ultimo_acceso']) : 'Sin acceso' ?>
                                </small>
                            </td>
                            <td>
                                <div class="ek-actions">
                                    <?php if (puedeEditar('usuarios')): ?>
                                        <a href="<?= BASE_URL ?>usuarios/editar/<?= $user['id'] ?>" class="ek-action-btn edit"
                                            title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if (puedeEditar('usuarios') && $user['id'] != usuarioId()): ?>
                                        <button
                                            onclick="toggleEstado(<?= $user['id'] ?>, '<?= e($user['nombre']) ?>', <?= $user['activo'] ? 'true' : 'false' ?>)"
                                            class="ek-action-btn toggle" title="<?= $user['activo'] ? 'Pausar' : 'Activar' ?>">
                                            <i class="bi bi-<?= $user['activo'] ? 'pause' : 'play' ?>-fill"></i>
                                        </button>
                                    <?php endif; ?>

                                    <?php if (puedeEliminar('usuarios') && $user['id'] != usuarioId()): ?>
                                        <button onclick="confirmarEliminacion(<?= $user['id'] ?>, '<?= e($user['nombre']) ?>')"
                                            class="ek-action-btn delete" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">
                            <div class="ek-empty">
                                <i class="bi bi-search"></i>
                                <p>No se encontraron resultados para tu busqueda.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleEstado(id, nombre, activo) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '¿Confirmar cambio?',
                text: `¿Estás seguro de ${activo ? 'desactivar' : 'activar'} al usuario "${nombre}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ED8B00',
                cancelButtonColor: '#425563',
                confirmButtonText: 'Sí, cambiar',
                cancelButtonText: 'Cancelar',
                backdrop: `rgba(0,43,73,0.5) blur(4px)`
            }).then((result) => {
                if (result.isConfirmed) {
                    ejecutarToggle(id);
                }
            });
        } else {
            if (confirm(`¿Esta seguro de ${activo ? 'desactivar' : 'activar'} al usuario "${nombre}"?`)) {
                ejecutarToggle(id);
            }
        }
    }

    function ejecutarToggle(id) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= BASE_URL ?>usuarios/toggleEstado/' + id;
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = 'csrf_token';
        csrfInput.value = '<?= generarToken() ?>';
        form.appendChild(csrfInput);
        document.body.appendChild(form);
        form.submit();
    }

    function confirmarEliminacion(id, nombre) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '¿ELIMINAR USUARIO?',
                text: `¿Estás seguro de eliminar permanentemente a "${nombre}"? Esta acción no se puede deshacer.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f43f5e',
                cancelButtonColor: '#425563',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                backdrop: `rgba(0,43,73,0.5) blur(4px)`
            }).then((result) => {
                if (result.isConfirmed) {
                    ejecutarEliminacion(id);
                }
            });
        } else {
            if (confirm(`¿ELIMINAR PERMANENTEMENTE al usuario "${nombre}"?`)) {
                ejecutarEliminacion(id);
            }
        }
    }

    function ejecutarEliminacion(id) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= BASE_URL ?>usuarios/eliminar/' + id;
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