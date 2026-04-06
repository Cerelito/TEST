<?php
$pagina_actual = 'productos_admin';
$titulo = 'Catálogo | Ekuora Admin';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<style>
    /* ============================================
       EKUORA ADMIN - CATÁLOGO PRODUCTOS
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
       FILTERS CARD
    ============================================ */
    .ek-card {
        background: var(--glass-bg);
        backdrop-filter: var(--glass-blur);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: var(--transition);
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

    /* Filters Area */
    .ek-filters {
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
        border-bottom: 1px solid var(--glass-border);
    }

    .ek-search-box {
        position: relative;
        flex: 1;
        max-width: 400px;
    }

    .ek-search-input {
        width: 100%;
        padding: 0.875rem 1rem 0.875rem 3rem;
        background: white;
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-full);
        font-size: 0.95rem;
        color: var(--ek-navy);
        transition: var(--transition);
    }

    .ek-search-input:focus {
        outline: none;
        border-color: var(--ek-orange);
        box-shadow: 0 0 0 3px rgba(237, 139, 0, 0.1);
    }

    .ek-search-input::placeholder {
        color: var(--ek-sky);
    }

    .ek-search-icon {
        position: absolute;
        left: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--ek-sky);
        font-size: 1rem;
    }

    .ek-select {
        padding: 0.875rem 2.5rem 0.875rem 1rem;
        background: white;
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-full);
        font-size: 0.95rem;
        color: var(--ek-navy);
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23425563' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        min-width: 200px;
        transition: var(--transition);
    }

    .ek-select:focus {
        outline: none;
        border-color: var(--ek-orange);
        box-shadow: 0 0 0 3px rgba(237, 139, 0, 0.1);
    }

    .ek-filters-right {
        margin-left: auto;
        display: flex;
        gap: 0.75rem;
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

    .ek-table thead th:first-child {
        border-radius: 0;
    }

    .ek-table thead th:last-child {
        border-radius: 0;
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

    /* Product Cell */
    .ek-product-cell {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .ek-product-img {
        width: 56px;
        height: 56px;
        background: var(--ek-sky-pale);
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
    }

    .ek-product-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .ek-product-img i {
        font-size: 1.5rem;
        color: var(--ek-sky);
    }

    .ek-product-info h4 {
        font-family: 'Outfit', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        color: var(--ek-navy);
        margin: 0 0 0.25rem;
    }

    .ek-product-info span {
        font-size: 0.8rem;
        color: var(--ek-sky);
    }

    /* Structure Cell */
    .ek-structure-cell {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }

    .ek-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.75rem;
        border-radius: var(--radius-full);
        font-size: 0.75rem;
        font-weight: 600;
        width: fit-content;
    }

    .ek-badge.navy {
        background: rgba(0, 43, 73, 0.1);
        color: var(--ek-navy);
    }

    .ek-badge.orange {
        background: rgba(237, 139, 0, 0.1);
        color: var(--ek-orange);
    }

    .ek-family-label {
        font-size: 0.8rem;
        color: var(--ek-slate);
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .ek-family-label i {
        font-size: 0.7rem;
        opacity: 0.5;
    }

    /* Star */
    .ek-star {
        font-size: 1.25rem;
    }

    .ek-star.filled {
        color: var(--ek-orange);
    }

    .ek-star.empty {
        color: var(--ek-sky);
        opacity: 0.3;
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

    .ek-action-btn.view {
        background: rgba(122, 153, 172, 0.15);
        color: var(--ek-sky);
    }

    .ek-action-btn.view:hover {
        background: var(--ek-sky);
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

        .ek-filters {
            flex-direction: column;
            align-items: stretch;
        }

        .ek-search-box {
            max-width: none;
        }

        .ek-filters-right {
            margin-left: 0;
            justify-content: center;
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

        .ek-product-cell {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
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
            <div class="ek-admin-hero-badge">Gestión de Inventario</div>
            <h1 class="ek-admin-hero-title">
                <i class="bi bi-box-seam"></i>
                Catálogo de Productos
            </h1>
            <p class="ek-admin-hero-subtitle">Administra y organiza tus productos con precisión.</p>
        </div>
        <div style="display: flex; gap: 1rem;">
            <a href="<?= BASE_URL ?>productos/importar-masivo" class="ek-btn ek-btn-secondary">
                <i class="bi bi-cloud-upload"></i> Importar
            </a>
            <a href="<?= BASE_URL ?>productos/crear" class="ek-btn ek-btn-primary">
                <i class="bi bi-plus-lg"></i> Nuevo Producto
            </a>
        </div>
    </div>
</section>

<!-- Stats Grid -->
<div class="ek-stats-grid ek-fade-up" style="animation-delay: 0.1s;">
    <div class="ek-stat-card">
        <div class="ek-stat-icon navy">
            <i class="bi bi-boxes"></i>
        </div>
        <div>
            <div class="ek-stat-value">
                <?= count($productos) ?>
            </div>
            <div class="ek-stat-label">Total Productos</div>
        </div>
    </div>

    <div class="ek-stat-card">
        <div class="ek-stat-icon green">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div>
            <div class="ek-stat-value">
                <?= $estadisticas['activos'] ?? 0 ?>
            </div>
            <div class="ek-stat-label">Productos Activos</div>
        </div>
    </div>

    <div class="ek-stat-card">
        <div class="ek-stat-icon orange">
            <i class="bi bi-tags-fill"></i>
        </div>
        <div>
            <div class="ek-stat-value">
                <?= count($categorias) ?>
            </div>
            <div class="ek-stat-label">Categorías</div>
        </div>
    </div>
</div>

<!-- Main Card -->
<div class="ek-card ek-fade-up" style="animation-delay: 0.2s;">
    <!-- Header -->
    <div class="ek-card-header">
        <div class="ek-card-header-left">
            <div class="ek-card-icon">
                <i class="bi bi-filter"></i>
            </div>
            <div>
                <h3 class="ek-card-title">Filtros y Búsqueda</h3>
                <p class="ek-card-subtitle">Localiza rápidamente cualquier artículo en tu inventario</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="ek-filters">
        <div class="ek-search-box">
            <i class="bi bi-search ek-search-icon"></i>
            <input type="text" class="ek-search-input" placeholder="Buscar por nombre o SKU..."
                data-table-search="tabla-productos">
        </div>

        <select class="ek-select" id="filtro-categoria">
            <option value="">Todas las categorías</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['id'] ?>">
                    <?= e($cat['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div class="ek-filters-right">
            <a href="<?= BASE_URL ?>productos/categorias" class="ek-btn ek-btn-secondary ek-btn-sm">
                <i class="bi bi-grid-3x3-gap"></i> Categorías
            </a>
            <a href="<?= BASE_URL ?>productos/familias" class="ek-btn ek-btn-secondary ek-btn-sm">
                <i class="bi bi-grid-1x2"></i> Familias
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="ek-table-container">
        <?php if (empty($productos)): ?>
            <div class="ek-empty-state">
                <div class="ek-empty-icon">
                    <i class="bi bi-box-seam"></i>
                </div>
                <h3 class="ek-empty-title">Sin productos registrados</h3>
                <p class="ek-empty-text">Comienza agregando tu primer producto al catálogo</p>
                <a href="<?= BASE_URL ?>productos/crear" class="ek-btn ek-btn-primary">
                    <i class="bi bi-plus-lg"></i> Agregar Producto
                </a>
            </div>
        <?php else: ?>
            <table class="ek-table" id="tabla-productos">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Estructura</th>
                        <th style="width: 100px; text-align: center;">Destacado</th>
                        <th style="width: 120px;">Estado</th>
                        <th style="width: 150px; text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $p): ?>
                        <tr data-categoria="<?= $p['categoria_id'] ?>">
                            <td data-label="Producto">
                                <div class="ek-product-cell">
                                    <div class="ek-product-img">
                                        <?php if (!empty($p['imagen_principal'])): ?>
                                            <img src="<?= e(asset($p['imagen_principal'])) ?>" alt="<?= e($p['nombre']) ?>">
                                        <?php else: ?>
                                            <i class="bi bi-image"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="ek-product-info">
                                        <h4>
                                            <?= e($p['nombre']) ?>
                                        </h4>
                                        <span>SKU:
                                            <?= e($p['sku'] ?? 'N/A') ?>
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Estructura">
                                <div class="ek-structure-cell">
                                    <span class="ek-badge navy">
                                        <i class="bi bi-folder"></i>
                                        <?= e($p['categoria_nombre']) ?>
                                    </span>
                                    <span class="ek-family-label">
                                        <i class="bi bi-arrow-return-right"></i>
                                        <?= e($p['familia_nombre'] ?? 'Sin Familia') ?>
                                    </span>
                                </div>
                            </td>
                            <td data-label="Destacado" style="text-align: center;">
                                <?php if ($p['destacado']): ?>
                                    <i class="bi bi-star-fill ek-star filled"></i>
                                <?php else: ?>
                                    <i class="bi bi-star ek-star empty"></i>
                                <?php endif; ?>
                            </td>
                            <td data-label="Estado">
                                <?php if ($p['activo']): ?>
                                    <span class="ek-status active">Activo</span>
                                <?php else: ?>
                                    <span class="ek-status inactive">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td data-label="Acciones">
                                <div class="ek-actions">
                                    <a href="<?= BASE_URL ?>productos/editar/<?= $p['id'] ?>" class="ek-action-btn edit"
                                        title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>productos/detalle/<?= $p['slug'] ?>" target="_blank"
                                        class="ek-action-btn view" title="Ver en sitio">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form id="form-eliminar-<?= $p['id'] ?>"
                                        action="<?= BASE_URL ?>productos/eliminar/<?= $p['id'] ?>" method="POST"
                                        style="display: none;">
                                        <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">
                                    </form>
                                    <button type="button" class="ek-action-btn delete" title="Eliminar"
                                        onclick="confirmDelete('¿Estás seguro de eliminar este producto?', 'form-eliminar-<?= $p['id'] ?>')">
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

<script>
    // Filtro por categoría
    document.getElementById('filtro-categoria').addEventListener('change', function () {
        const val = this.value;
        const rows = document.querySelectorAll('#tabla-productos tbody tr');

        rows.forEach(row => {
            const match = !val || row.dataset.categoria === val;
            row.style.display = match ? '' : 'none';

            // Animación
            if (match) {
                row.style.opacity = '0';
                row.style.transform = 'translateY(10px)';
                setTimeout(() => {
                    row.style.transition = 'all 0.3s ease';
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, 50);
            }
        });
    });

    // Búsqueda en tabla
    const searchInput = document.querySelector('[data-table-search="tabla-productos"]');
    if (searchInput) {
        searchInput.addEventListener('keyup', function () {
            const val = this.value.toLowerCase();
            const rows = document.querySelectorAll('#tabla-productos tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(val) ? '' : 'none';
            });
        });
    }
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>