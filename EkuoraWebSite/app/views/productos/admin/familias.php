<?php
$pagina_actual = 'familias_admin';
$titulo = 'Familias | Ekuora Admin';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<style>
    /* ============================================
       EKUORA ADMIN - GESTIÓN DE FAMILIAS
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

    /* ============================================
       HERO ADMIN
    ============================================ */
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

        0%,
        100% {
            opacity: 1;
            transform: scale(1);
        }

        50% {
            opacity: 0.5;
            transform: scale(1.2);
        }
    }

    .ek-admin-hero-title {
        font-family: 'Outfit', sans-serif;
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .ek-admin-hero-title i {
        color: var(--ek-orange);
    }

    .ek-admin-hero-subtitle {
        font-size: 1.1rem;
        color: var(--ek-sky-light);
    }

    .ek-btn {
        display: inline-flex;
        align-items: center;
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

    .ek-btn-secondary {
        background: white;
        color: var(--ek-navy);
        box-shadow: var(--glass-shadow);
    }

    .ek-btn-secondary:hover {
        background: var(--ek-sky-pale);
        color: var(--ek-navy);
    }

    /* ============================================
       STATS GRID
    ============================================ */
    .ek-stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .ek-stat-card {
        background: var(--glass-bg);
        backdrop-filter: var(--glass-blur);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        transition: var(--transition);
    }

    .ek-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 43, 73, 0.15);
        border-color: var(--ek-orange);
    }

    .ek-stat-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--radius-md);
        font-size: 1.75rem;
        flex-shrink: 0;
    }

    .ek-stat-icon.navy {
        background: rgba(0, 43, 73, 0.1);
        color: var(--ek-navy);
    }

    .ek-stat-icon.orange {
        background: rgba(237, 139, 0, 0.1);
        color: var(--ek-orange);
    }

    .ek-stat-icon.green {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
    }

    .ek-stat-value {
        font-family: 'Outfit', sans-serif;
        font-size: 2rem;
        font-weight: 800;
        color: var(--ek-navy);
        line-height: 1;
    }

    .ek-stat-label {
        font-size: 0.9rem;
        color: var(--ek-slate);
        margin-top: 0.25rem;
    }

    /* ============================================
       CARD
    ============================================ */
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
        justify-content: space-between;
        padding: 1.5rem;
        border-bottom: 1px solid var(--glass-border);
        background: linear-gradient(135deg, rgba(0, 43, 73, 0.03) 0%, transparent 100%);
    }

    .ek-card-header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
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
        font-family: 'Outfit', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--ek-navy);
        margin: 0 0 0.25rem;
    }

    .ek-card-subtitle {
        font-size: 0.85rem;
        color: var(--ek-slate);
        margin: 0;
    }

    /* ============================================
       TABLE
    ============================================ */
    .ek-table-container {
        overflow-x: auto;
    }

    .ek-table {
        width: 100%;
        border-collapse: collapse;
    }

    .ek-table thead {
        background: linear-gradient(135deg, var(--ek-navy) 0%, var(--ek-navy-light) 100%);
    }

    .ek-table thead th {
        padding: 1rem 1.25rem;
        text-align: left;
        font-size: 0.85rem;
        font-weight: 600;
        color: white;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .ek-table thead th:last-child {
        text-align: right;
    }

    .ek-table tbody tr {
        border-bottom: 1px solid var(--glass-border);
        transition: var(--transition);
    }

    .ek-table tbody tr:hover {
        background: rgba(237, 139, 0, 0.05);
    }

    .ek-table tbody tr:last-child {
        border-bottom: none;
    }

    .ek-table tbody td {
        padding: 1rem 1.25rem;
        vertical-align: middle;
    }

    /* Avatar/Image */
    .ek-avatar {
        width: 56px;
        height: 56px;
        background: var(--ek-sky-pale);
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .ek-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .ek-avatar i {
        font-size: 1.5rem;
        color: var(--ek-sky);
    }

    /* Family Info */
    .ek-family-name {
        font-family: 'Outfit', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        color: var(--ek-navy);
    }

    /* Badge */
    .ek-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.4rem 0.85rem;
        border-radius: var(--radius-full);
        font-size: 0.8rem;
        font-weight: 600;
    }

    .ek-badge.orange {
        background: rgba(237, 139, 0, 0.15);
        color: var(--ek-orange);
    }

    .ek-badge.navy {
        background: rgba(0, 43, 73, 0.1);
        color: var(--ek-navy);
    }

    .ek-badge i {
        font-size: 0.7rem;
    }

    /* Code */
    .ek-code {
        font-family: 'SF Mono', 'Fira Code', monospace;
        font-size: 0.85rem;
        background: var(--ek-sky-pale);
        color: var(--ek-navy);
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
    }

    /* Order Number */
    .ek-order {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        background: var(--ek-sky-pale);
        border-radius: var(--radius-sm);
        font-weight: 700;
        color: var(--ek-slate);
        font-size: 0.9rem;
    }

    /* Status */
    .ek-status {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: var(--radius-full);
        font-size: 0.8rem;
        font-weight: 600;
    }

    .ek-status::before {
        content: '';
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .ek-status.active {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
    }

    .ek-status.active::before {
        background: #22c55e;
    }

    .ek-status.inactive {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .ek-status.inactive::before {
        background: #ef4444;
    }

    /* Actions */
    .ek-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }

    .ek-action-btn {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--radius-sm);
        border: none;
        font-size: 1rem;
        cursor: pointer;
        transition: var(--transition);
    }

    .ek-action-btn.edit {
        background: rgba(0, 43, 73, 0.1);
        color: var(--ek-navy);
    }

    .ek-action-btn.edit:hover {
        background: var(--ek-navy);
        color: white;
    }

    .ek-action-btn.delete {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .ek-action-btn.delete:hover {
        background: #ef4444;
        color: white;
    }

    /* Empty State */
    .ek-empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .ek-empty-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--ek-sky-pale);
        border-radius: 50%;
        font-size: 2.5rem;
        color: var(--ek-sky);
    }

    .ek-empty-title {
        font-family: 'Outfit', sans-serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--ek-navy);
        margin-bottom: 0.5rem;
    }

    .ek-empty-text {
        font-size: 0.95rem;
        color: var(--ek-slate);
        margin-bottom: 1.5rem;
    }

    /* ============================================
       RESPONSIVE
    ============================================ */
    @media (max-width: 1200px) {
        .ek-stats-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 992px) {
        .ek-admin-hero-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .ek-stats-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .ek-admin-hero {
            padding: 2rem 1.5rem;
            margin: -1rem -1rem 1.5rem;
        }

        .ek-admin-hero-title {
            font-size: 1.75rem;
        }

        .ek-table thead {
            display: none;
        }

        .ek-table tbody tr {
            display: block;
            padding: 1rem;
            margin-bottom: 1rem;
            background: white;
            border-radius: var(--radius-md);
            border: 1px solid var(--glass-border);
        }

        .ek-table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px dashed var(--glass-border);
        }

        .ek-table tbody td:last-child {
            border-bottom: none;
            justify-content: flex-end;
        }

        .ek-table tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            color: var(--ek-slate);
            font-size: 0.8rem;
            text-transform: uppercase;
        }
    }

    /* Fade animations */
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
</style>

<!-- Hero Admin -->
<section class="ek-admin-hero ek-fade-up">
    <div class="ek-admin-hero-content">
        <div>
            <div class="ek-admin-hero-badge">Jerarquía de Productos</div>
            <h1 class="ek-admin-hero-title">
                <i class="bi bi-grid-1x2"></i>
                Gestión de Familias
            </h1>
            <p class="ek-admin-hero-subtitle">Administra las sub-categorías para una organización más profunda.</p>
        </div>
        <a href="<?= BASE_URL ?>productos/crear-familia" class="ek-btn ek-btn-primary">
            <i class="bi bi-plus-lg"></i> Nueva Familia
        </a>
    </div>
</section>

<!-- Stats Grid -->
<div class="ek-stats-grid ek-fade-up" style="animation-delay: 0.1s;">
    <div class="ek-stat-card">
        <div class="ek-stat-icon navy">
            <i class="bi bi-grid-1x2-fill"></i>
        </div>
        <div>
            <div class="ek-stat-value">
                <?= count($familias) ?>
            </div>
            <div class="ek-stat-label">Total Familias</div>
        </div>
    </div>

    <div class="ek-stat-card">
        <div class="ek-stat-icon green">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div>
            <div class="ek-stat-value">
                <?= count(array_filter($familias, fn($f) => $f['activo'])) ?>
            </div>
            <div class="ek-stat-label">Familias Activas</div>
        </div>
    </div>

    <div class="ek-stat-card">
        <div class="ek-stat-icon orange">
            <i class="bi bi-tags-fill"></i>
        </div>
        <div>
            <div class="ek-stat-value">
                <?= count(array_unique(array_column($familias, 'categoria_id'))) ?>
            </div>
            <div class="ek-stat-label">Categorías con Familias</div>
        </div>
    </div>
</div>

<!-- Main Card -->
<div class="ek-card ek-fade-up" style="animation-delay: 0.2s;">
    <div class="ek-card-header">
        <div class="ek-card-header-left">
            <div class="ek-card-icon">
                <i class="bi bi-list-nested"></i>
            </div>
            <div>
                <h3 class="ek-card-title">Familias Registradas</h3>
                <p class="ek-card-subtitle">Sub-niveles de organización por categoría</p>
            </div>
        </div>
        <a href="<?= BASE_URL ?>productos/categorias" class="ek-btn ek-btn-secondary ek-btn-sm">
            <i class="bi bi-grid-3x3-gap"></i> Ver Categorías
        </a>
    </div>

    <div class="ek-table-container">
        <?php if (empty($familias)): ?>
            <div class="ek-empty-state">
                <div class="ek-empty-icon">
                    <i class="bi bi-grid-1x2"></i>
                </div>
                <h3 class="ek-empty-title">Sin familias registradas</h3>
                <p class="ek-empty-text">Crea tu primera familia para organizar mejor tus productos dentro de cada categoría
                </p>
                <a href="<?= BASE_URL ?>productos/crear-familia" class="ek-btn ek-btn-primary">
                    <i class="bi bi-plus-lg"></i> Crear Primera Familia
                </a>
            </div>
        <?php else: ?>
            <table class="ek-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">Imagen</th>
                        <th>Familia</th>
                        <th>Categoría Padre</th>
                        <th>Slug</th>
                        <th style="width: 80px; text-align: center;">Orden</th>
                        <th style="width: 120px;">Estado</th>
                        <th style="width: 140px; text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($familias as $f): ?>
                        <tr>
                            <td data-label="Imagen">
                                <div class="ek-avatar">
                                    <?php if ($f['imagen']): ?>
                                        <img src="<?= e(asset($f['imagen'])) ?>" alt="<?= e($f['nombre']) ?>">
                                    <?php else: ?>
                                        <i class="bi bi-grid-1x2"></i>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td data-label="Familia">
                                <span class="ek-family-name">
                                    <?= e($f['nombre']) ?>
                                </span>
                            </td>
                            <td data-label="Categoría">
                                <span class="ek-badge orange">
                                    <i class="bi bi-folder-fill"></i>
                                    <?= e($f['categoria_nombre']) ?>
                                </span>
                            </td>
                            <td data-label="Slug">
                                <code class="ek-code"><?= e($f['slug']) ?></code>
                            </td>
                            <td data-label="Orden" style="text-align: center;">
                                <span class="ek-order">
                                    <?= $f['orden'] ?>
                                </span>
                            </td>
                            <td data-label="Estado">
                                <?php if ($f['activo']): ?>
                                    <span class="ek-status active">Activa</span>
                                <?php else: ?>
                                    <span class="ek-status inactive">Inactiva</span>
                                <?php endif; ?>
                            </td>
                            <td data-label="Acciones">
                                <div class="ek-actions">
                                    <a href="<?= BASE_URL ?>productos/editar-familia/<?= $f['id'] ?>" class="ek-action-btn edit"
                                        title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form id="form-eliminar-<?= $f['id'] ?>"
                                        action="<?= BASE_URL ?>productos/eliminar-familia/<?= $f['id'] ?>" method="POST"
                                        style="display: none;">
                                        <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">
                                    </form>
                                    <button type="button" class="action-btn delete" title="Eliminar"
                                        onclick="confirmDelete('¿Estás seguro de eliminar esta familia?', 'form-eliminar-<?= $f['id'] ?>')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<!-- Info Card -->
<div class="ek-card ek-fade-up"
    style="animation-delay: 0.3s; margin-top: 1.5rem; border-left: 4px solid var(--ek-orange);">
    <div style="padding: 1.5rem; display: flex; align-items: flex-start; gap: 1rem;">
        <div class="ek-card-icon" style="background: rgba(237, 139, 0, 0.1); color: var(--ek-orange);">
            <i class="bi bi-lightbulb"></i>
        </div>
        <div>
            <h4 style="font-family: 'Outfit', sans-serif; font-weight: 700; color: var(--ek-navy); margin: 0 0 0.5rem;">
                ¿Qué son las Familias?
            </h4>
            <p style="font-size: 0.95rem; color: var(--ek-slate); line-height: 1.7; margin: 0;">
                Las familias son <strong>sub-categorías</strong> que te permiten organizar tus productos de manera más
                específica.
                Por ejemplo, dentro de la categoría "Cocina" puedes tener las familias "Especieros", "Organizadores de
                Cajón" y "Porta Utensilios".
                Esto facilita la navegación y filtrado de productos para tus clientes.
            </p>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>