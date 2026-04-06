<?php
$titulo = 'Gestión de Banners | Ekuora Admin';
$pagina_actual = 'banners_admin';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<style>
    /* ============================================
       EKUORA ADMIN - GESTIÓN DE BANNERS
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
        grid-template-columns: repeat(4, 1fr);
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

    .ek-stat-icon.sky {
        background: rgba(122, 153, 172, 0.15);
        color: var(--ek-sky);
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

    /* Banner Preview */
    .ek-banner-preview {
        width: 140px;
        height: 70px;
        background: var(--ek-sky-pale);
        border-radius: var(--radius-sm);
        overflow: hidden;
        border: 2px solid var(--glass-border);
        transition: var(--transition);
        position: relative;
    }

    .ek-table tbody tr:hover .ek-banner-preview {
        border-color: var(--ek-orange);
        box-shadow: 0 4px 12px rgba(237, 139, 0, 0.2);
    }

    .ek-banner-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .ek-banner-preview .placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--ek-sky);
        font-size: 1.5rem;
    }

    /* Banner Info */
    .ek-banner-info {
        max-width: 280px;
    }

    .ek-banner-title {
        font-family: 'Outfit', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        color: var(--ek-navy);
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .ek-banner-subtitle {
        font-size: 0.85rem;
        color: var(--ek-slate);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Location Badge */
    .ek-location {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.5rem 0.85rem;
        border-radius: var(--radius-full);
        font-size: 0.8rem;
        font-weight: 600;
    }

    .ek-location.hero {
        background: rgba(0, 43, 73, 0.1);
        color: var(--ek-navy);
    }

    .ek-location.hero i {
        color: var(--ek-navy);
    }

    .ek-location.recommended {
        background: rgba(237, 139, 0, 0.1);
        color: var(--ek-orange);
    }

    .ek-location.recommended i {
        color: var(--ek-orange);
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

    .ek-info-card strong {
        color: var(--ek-navy);
    }

    /* ============================================
       RESPONSIVE
    ============================================ */
    @media (max-width: 1400px) {
        .ek-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 992px) {
        .ek-admin-hero-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .ek-stats-grid {
            grid-template-columns: repeat(2, 1fr);
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

        .ek-stats-grid {
            grid-template-columns: 1fr;
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
            justify-content: center;
        }

        .ek-table tbody td:first-child .ek-banner-preview {
            width: 100%;
            height: 120px;
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

        .ek-banner-info {
            max-width: none;
            text-align: right;
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
            <div class="ek-admin-hero-badge">Impacto Visual</div>
            <h1 class="ek-admin-hero-title">
                <i class="bi bi-images"></i>
                Banners de Inicio
            </h1>
            <p class="ek-admin-hero-subtitle">Administra los mensajes principales y promociones de tu portada.</p>
        </div>
        <a href="<?= BASE_URL ?>banners/crear" class="ek-btn ek-btn-primary">
            <i class="bi bi-plus-lg"></i> Nuevo Banner
        </a>
    </div>
</section>

<!-- Stats Grid -->
<div class="ek-stats-grid ek-fade-up" style="animation-delay: 0.1s;">
    <div class="ek-stat-card">
        <div class="ek-stat-icon navy">
            <i class="bi bi-images"></i>
        </div>
        <div>
            <div class="ek-stat-value">
                <?= count($banners) ?>
            </div>
            <div class="ek-stat-label">Total Banners</div>
        </div>
    </div>

    <div class="ek-stat-card">
        <div class="ek-stat-icon green">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div>
            <div class="ek-stat-value">
                <?= count(array_filter($banners, fn($b) => $b['activo'])) ?>
            </div>
            <div class="ek-stat-label">Banners Activos</div>
        </div>
    </div>

    <div class="ek-stat-card">
        <div class="ek-stat-icon sky">
            <i class="bi bi-display"></i>
        </div>
        <div>
            <div class="ek-stat-value">
                <?= count(array_filter($banners, fn($b) => ($b['seccion'] ?? 'hero') === 'hero')) ?>
            </div>
            <div class="ek-stat-label">En Hero Slider</div>
        </div>
    </div>

    <div class="ek-stat-card">
        <div class="ek-stat-icon orange">
            <i class="bi bi-star-fill"></i>
        </div>
        <div>
            <div class="ek-stat-value">
                <?= count(array_filter($banners, fn($b) => ($b['seccion'] ?? 'hero') !== 'hero')) ?>
            </div>
            <div class="ek-stat-label">En Recomendados</div>
        </div>
    </div>
</div>

<!-- Main Card -->
<div class="ek-card ek-fade-up" style="animation-delay: 0.2s;">
    <div class="ek-card-header">
        <div class="ek-card-header-left">
            <div class="ek-card-icon">
                <i class="bi bi-camera-reels"></i>
            </div>
            <div>
                <h3 class="ek-card-title">Secuencia de Banners</h3>
                <p class="ek-card-subtitle">Ordena y activa los slides del carrusel</p>
            </div>
        </div>
        <a href="<?= BASE_URL ?>banners/crear" class="ek-btn ek-btn-secondary ek-btn-sm">
            <i class="bi bi-plus"></i> Agregar
        </a>
    </div>

    <div class="ek-table-container">
        <?php if (empty($banners)): ?>
            <div class="ek-empty-state">
                <div class="ek-empty-icon">
                    <i class="bi bi-images"></i>
                </div>
                <h3 class="ek-empty-title">Sin banners configurados</h3>
                <p class="ek-empty-text">Los banners te permiten destacar promociones, productos destacados o mensajes
                    importantes en tu página de inicio.</p>
                <a href="<?= BASE_URL ?>banners/crear" class="ek-btn ek-btn-primary">
                    <i class="bi bi-plus-lg"></i> Crear Primer Banner
                </a>
            </div>
        <?php else: ?>
            <table class="ek-table">
                <thead>
                    <tr>
                        <th style="width: 160px;">Vista Previa</th>
                        <th>Título / Subtítulo</th>
                        <th style="width: 150px;">Ubicación</th>
                        <th style="width: 90px; text-align: center;">Orden</th>
                        <th style="width: 130px;">Estado</th>
                        <th style="width: 130px; text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($banners as $b): ?>
                        <tr>
                            <td data-label="">
                                <div class="ek-banner-preview">
                                    <?php if (!empty($b['imagen'])): ?>
                                        <img src="<?= e(asset($b['imagen'])) ?>" alt="<?= e($b['titulo'] ?? 'Banner') ?>">
                                    <?php else: ?>
                                        <div class="placeholder">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td data-label="Título">
                                <div class="ek-banner-info">
                                    <div class="ek-banner-title">
                                        <?= e($b['titulo'] ?: '(Sin título)') ?>
                                    </div>
                                    <div class="ek-banner-subtitle">
                                        <?= e($b['subtitulo'] ?: '(Sin subtítulo)') ?>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Ubicación">
                                <?php if (($b['seccion'] ?? 'hero') === 'hero'): ?>
                                    <span class="ek-location hero">
                                        <i class="bi bi-display"></i>
                                        Hero Slider
                                    </span>
                                <?php else: ?>
                                    <span class="ek-location recommended">
                                        <i class="bi bi-star"></i>
                                        Recomendados
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td data-label="Orden" style="text-align: center;">
                                <span class="ek-order">
                                    <?= (int) $b['orden'] ?>
                                </span>
                            </td>
                            <td data-label="Estado">
                                <?php if ($b['activo']): ?>
                                    <span class="ek-status active">Activo</span>
                                <?php else: ?>
                                    <span class="ek-status inactive">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td data-label="Acciones">
                                <div class="ek-actions">
                                    <a href="<?= BASE_URL ?>banners/editar/<?= $b['id'] ?>" class="ek-action-btn edit"
                                        title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form id="form-eliminar-<?= $b['id'] ?>"
                                        action="<?= BASE_URL ?>banners/eliminar/<?= $b['id'] ?>" method="POST"
                                        style="display: none;">
                                        <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">
                                    </form>
                                    <button type="button" class="ek-action-btn delete" title="Eliminar"
                                        onclick="confirmDelete('¿Estás seguro de eliminar este banner?', 'form-eliminar-<?= $b['id'] ?>')">
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
        <h4>Consejos para tus Banners</h4>
        <p>
            El <strong>orden</strong> determina la secuencia en el carrusel (número menor = aparece primero).
            Usa imágenes de <strong>1920x600px</strong> para Hero Slider y <strong>800x400px</strong> para Recomendados.
            Los banners <strong>inactivos</strong> no se mostrarán en el sitio público.
        </p>
    </div>
</div>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>