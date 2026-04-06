<?php
$pagina_actual = 'colecciones_admin';
$titulo = 'Colecciones | Ekuora Admin';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<style>
    /* ============================================
       EKUORA ADMIN - GESTIÓN DE COLECCIONES
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
        margin: 0 0 3rem;
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

    /* ============================================
       BUTTONS
    ============================================ */
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
        width: 64px;
        height: 64px;
        background: var(--ek-sky-pale);
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 2px solid var(--glass-border);
        transition: var(--transition);
    }

    .ek-table tbody tr:hover .ek-avatar {
        border-color: var(--ek-orange);
    }

    .ek-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .ek-avatar i {
        font-size: 1.75rem;
        color: var(--ek-sky);
    }

    /* Collection Name */
    .ek-collection-name {
        font-family: 'Outfit', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        color: var(--ek-navy);
    }

    /* Code/Slug */
    .ek-code {
        font-family: 'SF Mono', 'Fira Code', monospace;
        font-size: 0.85rem;
        background: var(--ek-sky-pale);
        color: var(--ek-navy);
        padding: 0.4rem 0.75rem;
        border-radius: 8px;
        border: 1px solid var(--glass-border);
    }

    /* Order Badge */
    .ek-order {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--ek-orange) 0%, var(--ek-orange-light) 100%);
        border-radius: var(--radius-sm);
        font-weight: 700;
        color: white;
        font-size: 0.95rem;
        box-shadow: 0 2px 8px rgba(237, 139, 0, 0.3);
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
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--radius-sm);
        border: none;
        font-size: 1rem;
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
    }

    .ek-action-btn.edit {
        background: rgba(0, 43, 73, 0.08);
        color: var(--ek-navy);
    }

    .ek-action-btn.edit:hover {
        background: var(--ek-navy);
        color: white;
    }

    .ek-action-btn.delete {
        background: rgba(239, 68, 68, 0.08);
        color: #ef4444;
    }

    .ek-action-btn.delete:hover {
        background: #ef4444;
        color: white;
    }

    /* ============================================
       EMPTY STATE
    ============================================ */
    .ek-empty-state {
        text-align: center;
        padding: 5rem 2rem;
    }

    .ek-empty-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--ek-sky-pale);
        border-radius: 50%;
        font-size: 3rem;
        color: var(--ek-sky);
    }

    .ek-empty-title {
        font-family: 'Outfit', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--ek-navy);
        margin-bottom: 0.75rem;
    }

    .ek-empty-text {
        font-size: 1rem;
        color: var(--ek-slate);
        margin-bottom: 2rem;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }

    /* ============================================
       INFO CARD
    ============================================ */
    .ek-info-card {
        background: linear-gradient(135deg, rgba(0, 43, 73, 0.03) 0%, transparent 100%);
        border-left: 4px solid var(--ek-orange);
        border-radius: var(--radius-md);
        padding: 1.25rem 1.5rem;
        margin-top: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .ek-info-card-icon {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(237, 139, 0, 0.1);
        border-radius: var(--radius-sm);
        color: var(--ek-orange);
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .ek-info-card h4 {
        font-family: 'Outfit', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        color: var(--ek-navy);
        margin: 0 0 0.35rem;
    }

    .ek-info-card p {
        font-size: 0.9rem;
        color: var(--ek-slate);
        line-height: 1.6;
        margin: 0;
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
            padding: 1.25rem;
            margin-bottom: 1rem;
            background: white;
            border-radius: var(--radius-md);
            border: 1px solid var(--glass-border);
            box-shadow: 0 2px 8px rgba(0, 43, 73, 0.05);
        }

        .ek-table tbody tr:hover {
            background: white;
            border-color: var(--ek-orange);
        }

        .ek-table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px dashed var(--glass-border);
        }

        .ek-table tbody td:first-child {
            padding-top: 0;
        }

        .ek-table tbody td:last-child {
            border-bottom: none;
            padding-bottom: 0;
            justify-content: flex-end;
        }

        .ek-table tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            color: var(--ek-slate);
            font-size: 0.8rem;
            text-transform: uppercase;
        }

        .ek-avatar {
            width: 56px;
            height: 56px;
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
            <div class="ek-admin-hero-badge">Organización Premium</div>
            <h1 class="ek-admin-hero-title">
                <i class="bi bi-collection"></i>
                Colecciones
            </h1>
            <p class="ek-admin-hero-subtitle">Crea grupos especiales de productos para destacar en tu catálogo.</p>
        </div>
        <a href="<?= BASE_URL ?>colecciones/crear" class="ek-btn ek-btn-primary">
            <i class="bi bi-plus-lg"></i> Nueva Colección
        </a>
    </div>
</section>

<!-- Stats Grid -->
<div class="ek-stats-grid ek-fade-up" style="animation-delay: 0.1s;">
    <div class="ek-stat-card">
        <div class="ek-stat-icon navy">
            <i class="bi bi-collection-fill"></i>
        </div>
        <div>
            <div class="ek-stat-value">
                <?= count($colecciones) ?>
            </div>
            <div class="ek-stat-label">Total Colecciones</div>
        </div>
    </div>

    <div class="ek-stat-card">
        <div class="ek-stat-icon green">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div>
            <div class="ek-stat-value">
                <?= count(array_filter($colecciones, fn($c) => $c['activo'])) ?>
            </div>
            <div class="ek-stat-label">Colecciones Activas</div>
        </div>
    </div>

    <div class="ek-stat-card">
        <div class="ek-stat-icon orange">
            <i class="bi bi-image-fill"></i>
        </div>
        <div>
            <div class="ek-stat-value">
                <?= count(array_filter($colecciones, fn($c) => !empty($c['imagen']))) ?>
            </div>
            <div class="ek-stat-label">Con Imagen</div>
        </div>
    </div>
</div>

<!-- Main Card -->
<div class="ek-card ek-fade-up" style="animation-delay: 0.2s;">
    <div class="ek-card-header">
        <div class="ek-card-header-left">
            <div class="ek-card-icon">
                <i class="bi bi-list-stars"></i>
            </div>
            <div>
                <h3 class="ek-card-title">Listado de Colecciones</h3>
                <p class="ek-card-subtitle">Gestiona el orden y la visibilidad de tus colecciones</p>
            </div>
        </div>
        <a href="<?= BASE_URL ?>colecciones/crear" class="ek-btn ek-btn-secondary ek-btn-sm">
            <i class="bi bi-plus"></i> Agregar
        </a>
    </div>

    <div class="ek-table-container">
        <?php if (empty($colecciones)): ?>
            <div class="ek-empty-state">
                <div class="ek-empty-icon">
                    <i class="bi bi-collection"></i>
                </div>
                <h3 class="ek-empty-title">Sin colecciones creadas</h3>
                <p class="ek-empty-text">Las colecciones te permiten agrupar productos especiales para destacarlos en tu
                    catálogo.</p>
                <a href="<?= BASE_URL ?>colecciones/crear" class="ek-btn ek-btn-primary">
                    <i class="bi bi-plus-lg"></i> Crear Primera Colección
                </a>
            </div>
        <?php else: ?>
            <table class="ek-table">
                <thead>
                    <tr>
                        <th style="width: 90px;">Imagen</th>
                        <th>Nombre</th>
                        <th>Slug</th>
                        <th style="width: 90px; text-align: center;">Orden</th>
                        <th style="width: 130px;">Estado</th>
                        <th style="width: 130px; text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($colecciones as $c): ?>
                        <tr>
                            <td data-label="Imagen">
                                <div class="ek-avatar">
                                    <?php if ($c['imagen']): ?>
                                        <img src="<?= e(asset($c['imagen'])) ?>" alt="<?= e($c['nombre']) ?>">
                                    <?php else: ?>
                                        <i class="bi bi-collection"></i>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td data-label="Nombre">
                                <span class="ek-collection-name">
                                    <?= e($c['nombre']) ?>
                                </span>
                            </td>
                            <td data-label="Slug">
                                <code class="ek-code"><?= e($c['slug']) ?></code>
                            </td>
                            <td data-label="Orden" style="text-align: center;">
                                <span class="ek-order">
                                    <?= $c['orden'] ?>
                                </span>
                            </td>
                            <td data-label="Estado">
                                <?php if ($c['activo']): ?>
                                    <span class="ek-status active">Activa</span>
                                <?php else: ?>
                                    <span class="ek-status inactive">Inactiva</span>
                                <?php endif; ?>
                            </td>
                            <td data-label="Acciones">
                                <div class="ek-actions">
                                    <a href="<?= BASE_URL ?>colecciones/editar/<?= $c['id'] ?>" class="ek-action-btn edit"
                                        title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form id="form-eliminar-<?= $c['id'] ?>"
                                        action="<?= BASE_URL ?>colecciones/eliminar/<?= $c['id'] ?>" method="POST"
                                        style="display: none;">
                                        <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">
                                    </form>
                                    <button type="button" class="ek-action-btn delete" title="Eliminar"
                                        onclick="confirmDelete('¿Estás seguro de eliminar esta colección?', 'form-eliminar-<?= $c['id'] ?>')">
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
<div class="ek-info-card ek-fade-up" style="animation-delay: 0.3s;">
    <div class="ek-info-card-icon">
        <i class="bi bi-lightbulb"></i>
    </div>
    <div>
        <h4>¿Qué son las Colecciones?</h4>
        <p>
            Las colecciones son grupos especiales de productos que puedes usar para crear secciones destacadas en tu
            catálogo,
            como "Lo más vendido", "Nuevos lanzamientos", "Ofertas especiales" o colecciones temáticas.
            El <strong>orden</strong> determina su posición en el catálogo (número menor = aparece primero).
        </p>
    </div>
</div>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>