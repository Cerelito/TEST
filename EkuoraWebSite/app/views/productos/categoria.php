<?php
/**
 * categoria.php - Vista de productos por categoría
 * Estilo Ekuora Ultra Glass - Pantone Edition
 */
?>
<!DOCTYPE html>
<html lang="es" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($categoria['nombre']) ?> | <?= APP_NAME ?? 'Ekuora' ?></title>

    <link rel="icon" type="image/png" href="<?= e(asset('favicon.png')) ?>">
    <link rel="shortcut icon" href="<?= e(asset('favicon.png')) ?>" type="image/png">
    <link rel="apple-touch-icon" href="<?= e(asset('favicon.png')) ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        /* ============================================
           EKUORA ULTRA GLASS - CATEGORIA VIEW
           Pantone: 296C, 144C, 5425C, 7546C
        ============================================ */

        :root {
            --ek-navy: #002B49;
            --ek-orange: #ED8B00;
            --ek-sky: #7A99AC;
            --ek-slate: #425563;
            --ek-navy-light: #003d66;
            --ek-navy-dark: #001a2e;
            --ek-orange-light: #ff9d1a;
            --ek-orange-dark: #cc7600;
            --ek-sky-light: #9bb5c4;
            --ek-sky-pale: #e8eff3;

            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
            --glass-shadow: 0 8px 32px rgba(0, 43, 73, 0.12);

            --radius-sm: 12px;
            --radius-md: 16px;
            --radius-lg: 24px;
            --radius-xl: 32px;
            --radius-full: 9999px;

            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* NEW: Text Backgrounds for Readability */
        .ek-text-pill {
            background: #002B49; /* Navy Blue Brand Color */
            padding: 0.6rem 1.75rem;
            border-radius: var(--radius-full);
            display: inline-block;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            margin-bottom: 1rem;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .ek-cat-hero-title.ek-text-pill {
            padding: 0.5rem 1.5rem;
            margin-bottom: 1rem;
        }

        [data-theme="dark"] {
            --ek-sky-pale: #1a2530;
            --glass-bg: rgba(0, 43, 73, 0.85);
            --glass-border: rgba(122, 153, 172, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, var(--ek-sky-pale) 50%, #f1f5f9 100%);
            color: var(--ek-slate);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        [data-theme="dark"] body {
            background: linear-gradient(135deg, #0a1520 0%, #001a2e 50%, #0d1f2d 100%);
            color: var(--ek-sky-light);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* ============================================
           HERO CATEGORIA
        ============================================ */
        .ek-cat-hero {
            position: relative;
            min-height: 400px;
            display: flex;
            align-items: center;
            overflow: hidden;
            border-radius: 0 0 var(--radius-xl) var(--radius-xl);
        }

        .ek-cat-hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            opacity: 1; /* Full visibility as requested */
        }

        .ek-cat-hero-overlay {
            position: absolute;
            inset: 0;
            background: transparent; /* Overlay removed as pills provide legibility */
        }

        .ek-cat-hero-content {
            position: relative;
            z-index: 10;
            padding: 4rem 0;
            width: 100%;
            text-align: left;
        }

        /* Breadcrumb */
        .ek-breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .ek-breadcrumb a {
            color: var(--ek-sky-light);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.75rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: var(--radius-full);
            transition: var(--transition);
        }

        .ek-breadcrumb a:hover {
            background: var(--ek-orange);
            color: white;
        }

        .ek-breadcrumb-separator {
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.8rem;
        }

        .ek-breadcrumb-current {
            color: white;
            font-size: 0.9rem;
            font-weight: 600;
            padding: 0.4rem 1rem;
            background: var(--ek-orange);
            border-radius: var(--radius-full);
        }

        .ek-cat-hero-title {
            font-family: 'Montserrat', sans-serif;
            font-size: clamp(1.2rem, 3vw, 1.8rem);
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
            line-height: 1.2;
            padding: 0.45rem 1.25rem !important;
            background: #002B49 !important;
            border-radius: var(--radius-full);
            display: inline-block;
            width: fit-content;
        }

        .ek-cat-hero-title span {
            color: var(--ek-orange);
        }

        .ek-cat-hero-desc {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.9);
            max-width: 500px;
            line-height: 1.5;
            margin-bottom: 1rem;
            padding: 0.4rem 1.25rem !important;
            background: #002B49 !important;
            border-radius: var(--radius-full);
            display: inline-block;
            width: fit-content;
        }

        .ek-cat-hero-stats {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .ek-cat-stat {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(0, 43, 73, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: var(--radius-md);
            transition: var(--transition);
        }

        /* Familia-specific view: REMOVE BOXES and align left */
        .ek-cat-hero-stats {
            display: none !important;
        }

        .ek-cat-hero-content {
            padding-left: 0 !important;
            margin-left: 0 !important;
            text-align: left !important;
            max-width: 100% !important;
        }

        .ek-cat-hero .container {
            margin-left: 0 !important;
            padding-left: 1.5rem !important;
            max-width: 100% !important;
        }

        .ek-cat-stat-icon {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--ek-orange);
            border-radius: var(--radius-sm);
            color: white;
            font-size: 0.7rem;
        }

        .ek-cat-stat-info {
            display: flex;
            flex-direction: column;
        }

        .ek-cat-stat-value {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: white;
            line-height: 1;
        }

        .ek-cat-stat-label {
            display: none;
        }

        /* ============================================
           FILTROS Y ORDENAMIENTO
        ============================================ */
        .ek-filters-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            padding: 1.5rem 0;
            margin-bottom: 2rem;
            border-bottom: 1px solid var(--glass-border);
        }

        .ek-filters-left {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .ek-filter-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius-full);
            color: var(--ek-slate);
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
        }

        .ek-filter-btn:hover {
            border-color: var(--ek-orange);
            color: var(--ek-orange);
        }

        .ek-filter-btn.active {
            background: var(--ek-navy);
            border-color: var(--ek-navy);
            color: white;
        }

        .ek-sort-select {
            padding: 0.75rem 2.5rem 0.75rem 1rem;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius-full);
            color: var(--ek-slate);
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23425563' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            transition: var(--transition);
        }

        .ek-sort-select:hover,
        .ek-sort-select:focus {
            border-color: var(--ek-orange);
            outline: none;
        }

        .ek-view-toggle {
            display: flex;
            gap: 0.5rem;
        }

        .ek-view-btn {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius-md);
            color: var(--ek-slate);
            font-size: 1.1rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .ek-view-btn:hover {
            border-color: var(--ek-orange);
            color: var(--ek-orange);
        }

        .ek-view-btn.active {
            background: var(--ek-navy);
            border-color: var(--ek-navy);
            color: white;
        }

        /* ============================================
           PRODUCTOS GRID
        ============================================ */
        .ek-productos-section {
            padding: 3rem 0 5rem;
        }

        .ek-productos-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
        }

        .ek-productos-grid.list-view {
            grid-template-columns: 1fr;
        }

        .ek-producto-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius-lg);
            overflow: hidden;
            transition: var(--transition);
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .ek-producto-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 50px rgba(0, 43, 73, 0.15);
            border-color: var(--ek-orange);
        }

        .list-view .ek-producto-card {
            flex-direction: row;
            max-height: 220px;
        }

        .ek-producto-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            z-index: 10;
            padding: 0.4rem 1rem;
            background: var(--ek-orange);
            color: white;
            font-size: 0.75rem;
            font-weight: 700;
            border-radius: var(--radius-full);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .ek-producto-img {
            position: relative;
            height: 240px;
            background: linear-gradient(135deg, var(--ek-sky-pale) 0%, #fff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .list-view .ek-producto-img {
            width: 280px;
            min-width: 280px;
            height: 100%;
        }

        .ek-producto-img img {
            max-width: 85%;
            max-height: 85%;
            object-fit: contain;
            transition: transform 0.5s ease;
        }

        .ek-producto-card:hover .ek-producto-img img {
            transform: scale(1.1);
        }

        .ek-producto-img-placeholder {
            font-size: 4rem;
            color: var(--ek-sky);
        }

        .ek-producto-actions {
            position: absolute;
            top: 1rem;
            right: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            opacity: 0;
            transform: translateX(10px);
            transition: var(--transition);
        }

        .ek-producto-card:hover .ek-producto-actions {
            opacity: 1;
            transform: translateX(0);
        }

        .ek-producto-action-btn {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border: none;
            border-radius: var(--radius-full);
            color: var(--ek-navy);
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .ek-producto-action-btn:hover {
            background: var(--ek-orange);
            color: white;
        }

        .ek-producto-body {
            padding: 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .ek-producto-categoria {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: var(--ek-sky);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }

        .ek-producto-categoria i {
            color: var(--ek-orange);
        }

        .ek-producto-nombre {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.15rem;
            font-weight: 600;
            color: var(--ek-navy);
            margin-bottom: 0.75rem;
            line-height: 1.4;
        }

        [data-theme="dark"] .ek-producto-nombre {
            color: white;
        }

        .ek-producto-nombre a {
            color: inherit;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .ek-producto-nombre a:hover {
            color: var(--ek-orange);
        }

        .ek-producto-desc {
            font-size: 0.9rem;
            color: var(--ek-slate);
            line-height: 1.5;
            margin-bottom: 1rem;
            flex: 1;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        [data-theme="dark"] .ek-producto-desc {
            color: var(--ek-sky-light);
        }

        .ek-producto-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid var(--glass-border);
        }

        .ek-producto-precio {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--ek-orange);
        }

        .ek-producto-btn {
            padding: 0.4rem 1rem;
            background: #002B49;
            color: white;
            border-radius: var(--radius-full);
            font-size: 0.75rem;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            transition: var(--transition);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .ek-producto-btn:hover {
            background: var(--ek-orange);
            transform: scale(1.05);
        }

        /* ============================================
           EMPTY STATE
        ============================================ */
        .ek-empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 5rem 2rem;
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            border-radius: var(--radius-xl);
            border: 2px dashed var(--glass-border);
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
            font-family: 'Montserrat', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--ek-navy);
            margin-bottom: 0.75rem;
        }

        [data-theme="dark"] .ek-empty-title {
            color: white;
        }

        .ek-empty-text {
            font-size: 1rem;
            color: var(--ek-slate);
            margin-bottom: 2rem;
        }

        .ek-empty-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 2rem;
            background: var(--ek-orange);
            color: white;
            text-decoration: none;
            font-weight: 600;
            border-radius: var(--radius-full);
            transition: var(--transition);
            box-shadow: 0 4px 20px rgba(237, 139, 0, 0.4);
        }

        .ek-empty-btn:hover {
            background: var(--ek-orange-light);
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(237, 139, 0, 0.5);
        }

        /* ============================================
           RELACIONADOS
        ============================================ */
        .ek-related-section {
            padding: 4rem 0;
            background: var(--ek-sky-pale);
        }

        .ek-section-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .ek-section-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.25rem;
            background: linear-gradient(135deg, var(--ek-navy) 0%, var(--ek-slate) 100%);
            border-radius: var(--radius-full);
            color: white;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .ek-section-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 2.25rem;
            font-weight: 700;
            color: var(--ek-navy);
            margin-bottom: 0.5rem;
        }

        [data-theme="dark"] .ek-section-title {
            color: white;
        }

        .ek-section-subtitle {
            font-size: 1rem;
            color: var(--ek-slate);
        }

        /* ============================================
           ANIMACIONES
        ============================================ */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-in {
            animation: fadeInUp 0.5s ease forwards;
        }

        /* ============================================
           RESPONSIVE
        ============================================ */
        @media (max-width: 1200px) {
            .ek-productos-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 992px) {
            .ek-cat-hero {
                min-height: 350px;
            }

            .ek-cat-hero-title {
                font-size: 2.5rem;
            }

            .ek-productos-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .list-view .ek-producto-card {
                flex-direction: column;
                max-height: none;
            }

            .list-view .ek-producto-img {
                width: 100%;
                height: 200px;
            }
        }

        @media (max-width: 768px) {
            .ek-cat-hero {
                min-height: 300px;
                border-radius: 0 0 var(--radius-lg) var(--radius-lg);
            }

            .ek-cat-hero-content {
                padding: 2.5rem 0;
            }

            .ek-cat-hero-title {
                font-size: 2rem;
            }

            .ek-cat-hero-desc {
                font-size: 1rem;
            }

            .ek-cat-hero-stats {
                gap: 1rem;
            }

            .ek-cat-stat {
                padding: 0.75rem 1rem;
            }

            .ek-filters-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .ek-filters-left {
                justify-content: center;
            }

            .ek-section-title {
                font-size: 1.75rem;
            }
        }

        @media (max-width: 768px) {
            .ek-cat-hero-content {
                padding: 1.5rem 0;
            }

            .ek-cat-hero-title {
                font-size: 1.3rem !important;
                padding: 0.35rem 1rem !important;
            }

            .ek-cat-hero-desc {
                font-size: 0.8rem !important;
                padding: 0.3rem 0.9rem !important;
                max-width: 300px;
                line-height: 1.4;
            }
        }

        @media (max-width: 576px) {
            .ek-cat-hero-title {
                font-size: 1.15rem !important;
                padding: 0.3rem 0.9rem !important;
            }

            .ek-breadcrumb {
                font-size: 0.8rem;
            }

            .ek-productos-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .ek-producto-img {
                height: 200px;
            }

            .ek-producto-body {
                padding: 1.25rem;
            }

            .ek-cat-stat {
                flex: 1;
                min-width: 140px;
            }
        }
    </style>
</head>

<body>

    <?php
    $pagina_actual = 'productos';
    $is_familia = !empty($_GET['familia']); // Check if viewing a family
    require_once VIEWS_PATH . 'layouts/nav_publico.php';
    ?>

    <!-- ========== HERO CATEGORIA ========== -->
    <section class="ek-cat-hero <?= $is_familia ? 'is-familia' : '' ?>">
        <?php if (!empty($categoria['imagen'])): ?>
            <div class="ek-cat-hero-bg" style="background-image: url('<?= e(asset($categoria['imagen'])) ?>');"></div>
        <?php else: ?>
            <div class="ek-cat-hero-bg" style="background-image: url('<?= e(asset('img/banner1.png')) ?>');"></div>
        <?php endif; ?>

        <div class="container">
            <div class="ek-cat-hero-content">
                <!-- Breadcrumb -->
                <nav class="ek-breadcrumb">
                    <a href="<?= BASE_URL ?>"><i class="bi bi-house-door"></i> Inicio</a>
                    <span class="ek-breadcrumb-separator"><i class="bi bi-chevron-right"></i></span>
                    <span class="ek-breadcrumb-current"><?= e($categoria['nombre']) ?></span>
                </nav>

                <h1 class="ek-cat-hero-title ek-text-pill">
                    <span><?= e($categoria['nombre']) ?></span>
                </h1>

                <?php if (!empty($categoria['descripcion'])): ?>
                    <p class="ek-cat-hero-desc ek-text-pill"><?= e($categoria['descripcion']) ?></p>
                <?php else: ?>
                    <p class="ek-cat-hero-desc ek-text-pill">Explora nuestra selección de <?= strtolower(e($categoria['nombre'])) ?> de
                        alta calidad, diseñados para transformar tu espacio.</p>
                <?php endif; ?>

                <?php /* Statistics removed as per user request for all category views */ ?>
            </div>
        </div>
        </div>
    </section>

    <!-- ========== PRODUCTOS DE LA CATEGORÍA ========== -->
    <section class="ek-productos-section">
        <div class="container">
            <!-- Barra de Filtros -->
            <div class="ek-filters-bar">
                <div class="ek-filters-left">
                    <button class="ek-filter-btn active">
                        <i class="bi bi-collection"></i> Todos
                    </button>
                    <button class="ek-filter-btn">
                        <i class="bi bi-star"></i> Destacados
                    </button>
                    <button class="ek-filter-btn">
                        <i class="bi bi-lightning"></i> Nuevos
                    </button>
                </div>

                <div style="display: flex; align-items: center; gap: 1rem;">
                    <select class="ek-sort-select">
                        <option>Ordenar por: Relevancia</option>
                        <option>Precio: Menor a Mayor</option>
                        <option>Precio: Mayor a Menor</option>
                        <option>Nombre: A-Z</option>
                        <option>Más Recientes</option>
                    </select>

                    <div class="ek-view-toggle">
                        <button class="ek-view-btn active" onclick="setView('grid')" title="Vista en cuadrícula">
                            <i class="bi bi-grid-3x3-gap"></i>
                        </button>
                        <button class="ek-view-btn" onclick="setView('list')" title="Vista en lista">
                            <i class="bi bi-list-ul"></i>
                        </button>
                    </div>
                </div>
            </div>

            <?php if (empty($productos)): ?>
                <!-- Empty State -->
                <div class="ek-empty-state">
                    <div class="ek-empty-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <h3 class="ek-empty-title">Sin productos disponibles</h3>
                    <p class="ek-empty-text">No hay productos en esta categoría por el momento. ¡Vuelve pronto!</p>
                    <a href="<?= BASE_URL ?>productos" class="ek-empty-btn">
                        <i class="bi bi-arrow-left"></i> Ver todas las categorías
                    </a>
                </div>
            <?php else: ?>
                <!-- Grid de Productos -->
                <div class="ek-productos-grid" id="productosGrid">
                    <?php foreach ($productos as $index => $producto): ?>
                        <div class="ek-producto-card animate-in" style="animation-delay: <?= $index * 0.1 ?>s">
                            <?php if (!empty($producto['nuevo'])): ?>
                                <div class="ek-producto-badge">Nuevo</div>
                            <?php endif; ?>

                            <div class="ek-producto-actions">
                                <button class="ek-producto-action-btn" title="Añadir a favoritos">
                                    <i class="bi bi-heart"></i>
                                </button>
                                <button class="ek-producto-action-btn" title="Vista rápida">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="ek-producto-action-btn" title="Compartir">
                                    <i class="bi bi-share"></i>
                                </button>
                            </div>

                            <div class="ek-producto-img">
                                <?php if ($producto['imagen_principal']): ?>
                                    <img src="<?= e(asset($producto['imagen_principal'])) ?>" alt="<?= e($producto['nombre']) ?>"
                                        loading="lazy">
                                <?php else: ?>
                                    <div class="ek-producto-img-placeholder">
                                        <i class="bi bi-image"></i>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="ek-producto-body">
                                <div class="ek-producto-categoria">
                                    <i class="bi bi-tag-fill"></i>
                                    <?= e($categoria['nombre']) ?>
                                </div>

                                <h3 class="ek-producto-nombre">
                                    <a href="<?= BASE_URL ?>productos/detalle/<?= e($producto['slug']) ?>">
                                        <?= e($producto['nombre']) ?>
                                    </a>
                                </h3>

                                <?php if (!empty($producto['descripcion_corta'])): ?>
                                    <p class="ek-producto-desc"><?= e($producto['descripcion_corta']) ?></p>
                                <?php endif; ?>

                                <div class="ek-producto-footer">
                                    <?php if (!empty($producto['precio'])): ?>
                                        <span class="ek-producto-precio">$<?= number_format($producto['precio'], 2) ?></span>
                                    <?php else: ?>
                                        <span class="ek-producto-precio" style="color: var(--ek-sky);">Consultar</span>
                                    <?php endif; ?>

                                    <a href="<?= BASE_URL ?>productos/detalle/<?= e($producto['slug']) ?>"
                                        class="ek-producto-btn">
                                        <i class="bi bi-eye"></i> Ver Detalles
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- ========== OTRAS CATEGORÍAS ========== -->
    <?php if (!empty($otras_categorias)): ?>
        <section class="ek-related-section">
            <div class="container">
                <div class="ek-section-header">
                    <div class="ek-section-badge">
                        <i class="bi bi-collection"></i> Explorar Más
                    </div>
                    <h2 class="ek-section-title">Otras Colecciones</h2>
                    <p class="ek-section-subtitle">Descubre más productos en nuestras otras categorías</p>
                </div>

                <div class="ek-productos-grid" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));">
                    <?php foreach (array_slice($otras_categorias, 0, 4) as $cat): ?>
                        <a href="<?= BASE_URL ?>productos/categoria/<?= e($cat['slug']) ?>" class="ek-collection-card"
                            style="text-decoration: none;">
                            <div class="ek-collection-img"
                                style="height: 180px; background: linear-gradient(135deg, var(--ek-sky-pale) 0%, var(--ek-sky-light) 100%); display: flex; align-items: center; justify-content: center;">
                                <?php if (!empty($cat['imagen'])): ?>
                                    <img src="<?= e(asset($cat['imagen'])) ?>" alt="<?= e($cat['nombre']) ?>"
                                        style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <i class="bi bi-grid-3x3-gap" style="font-size: 3rem; color: var(--ek-sky);"></i>
                                <?php endif; ?>
                            </div>
                            <div style="padding: 1.25rem; text-align: center;">
                                <h3
                                    style="font-family: 'Outfit', sans-serif; font-size: 1.15rem; font-weight: 600; color: var(--ek-navy); margin-bottom: 0.5rem;">
                                    <?= e($cat['nombre']) ?>
                                </h3>
                                <span style="font-size: 0.9rem; color: var(--ek-orange); font-weight: 500;">Ver productos
                                    →</span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php require_once VIEWS_PATH . 'layouts/footer_publico.php'; ?>

    <script>
        // Toggle Grid/List View
        function setView(view) {
            const grid = document.getElementById('productosGrid');
            const btns = document.querySelectorAll('.ek-view-btn');

            btns.forEach(btn => btn.classList.remove('active'));

            if (view === 'list') {
                grid.classList.add('list-view');
                document.querySelector('.ek-view-btn:last-child').classList.add('active');
            } else {
                grid.classList.remove('list-view');
                document.querySelector('.ek-view-btn:first-child').classList.add('active');
            }
        }

        // Filter buttons toggle
        document.querySelectorAll('.ek-filter-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.ek-filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Animate cards on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.ek-producto-card').forEach(card => {
            observer.observe(card);
        });
    </script>
</body>

</html>