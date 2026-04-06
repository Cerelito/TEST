<!DOCTYPE html>
<html lang="es" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Catálogo de Productos |
        <?= APP_NAME ?? 'Ekuora' ?>
    </title>

    <link rel="icon" type="image/png" href="<?= e(asset('favicon.png')) ?>">
    <link rel="shortcut icon" href="<?= e(asset('favicon.png')) ?>" type="image/png">
    <link rel="apple-touch-icon" href="<?= e(asset('favicon.png')) ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        /* ============================================
           EKUORA - CATALOGO PREMIUM
           Glassmorphism + Animaciones Optimizadas
        ============================================ */

        :root {
            /* Paleta Principal - Tonos más cálidos y sutiles */
            --ek-primary: #1a365d;
            --ek-primary-light: #2c5282;
            --ek-accent: #ED8B00;
            --ek-accent-light: #f6ad55;
            --ek-neutral: #64748b;
            --ek-neutral-light: #94a3b8;
            --ek-surface: #f8fafc;
            --ek-surface-warm: #faf5f0;

            /* Glassmorphism */
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);

            /* Radios */
            --radius-sm: 12px;
            --radius-md: 16px;
            --radius-lg: 24px;
            --radius-xl: 32px;
            --radius-full: 9999px;

            /* Tipografía */
            --ek-font-body: 'Montserrat', sans-serif;
            --ek-font-brand: 'Montserrat', sans-serif;

            /* Transiciones optimizadas - más rápidas */
            --transition-fast: 0.15s ease-out;
            --transition: 0.25s ease-out;
            --transition-slow: 0.4s ease-out;
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

        .ek-hero-title.ek-text-pill {
            padding: 0.75rem 2rem;
            margin-bottom: 1.5rem;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        body {
            font-family: var(--ek-font-body);
            background: linear-gradient(180deg, var(--ek-surface) 0%, var(--ek-surface-warm) 100%);
            color: var(--ek-neutral);
            min-height: 100vh;
            overflow-x: hidden;
            width: 100%;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1.5rem;
            width: 100%;
        }

        /* ============================================
           ANIMACIONES OPTIMIZADAS (GPU)
        ============================================ */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translate3d(0, 30px, 0);
            }

            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale3d(0.95, 0.95, 1);
            }

            to {
                opacity: 1;
                transform: scale3d(1, 1, 1);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translate3d(0, 0, 0);
            }

            50% {
                transform: translate3d(0, -8px, 0);
            }
        }

        @keyframes glow {

            0%,
            100% {
                box-shadow: 0 0 20px rgba(237, 139, 0, 0.3);
            }

            50% {
                box-shadow: 0 0 40px rgba(237, 139, 0, 0.5);
            }
        }

        /* Clases de animación con mejor rendimiento */
        .animate-on-scroll {
            opacity: 0;
            transform: translate3d(0, 25px, 0);
            transition: opacity var(--transition-slow), transform var(--transition-slow);
            will-change: opacity, transform;
        }

        .animate-on-scroll.is-visible {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }

        /* ============================================
           HERO SECTION - Degradados Suaves
        ============================================ */
        .ek-hero {
            position: relative;
            margin-bottom: 3rem;
        }

        .ek-hero-swiper {
            width: 100%;
            height: 520px;
            border-radius: 0 0 var(--radius-xl) var(--radius-xl);
            overflow: hidden;
        }

        .ek-hero-slide {
            position: relative;
            display: flex;
            align-items: center;
            background: #002B49; /* Solid background just in case image fails */
        }

        .ek-hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            opacity: 1; /* Full visibility as requested */
        }

        .ek-hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: transparent; /* Overlay removed as pills provide legibility */
        }

        .ek-hero-content {
            position: relative;
            z-index: 10;
            padding: 8rem 0;
            max-width: 100%;
            text-align: left;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .ek-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #ED8B00; /* Solid orange for contrast */
            border-radius: var(--radius-full);
            color: white;
            font-size: 0.8rem;
            font-weight: 700;
            margin-bottom: 1.25rem;
            box-shadow: 0 4px 15px rgba(237, 139, 0, 0.3);
        }

        .ek-hero-title {
            font-family: var(--ek-font-brand);
            font-size: clamp(1.75rem, 5vw, 2.8rem);
            font-weight: 700;
            color: white;
            line-height: 1.2;
            margin-bottom: 0.75rem;
            padding: 0.5rem 1.5rem !important;
            background: #002B49 !important;
            border-radius: var(--radius-full);
            display: inline-block;
            width: fit-content;
        }

        .ek-hero-title span {
            color: var(--ek-accent);
            text-shadow: 0 0 30px rgba(237, 139, 0, 0.4);
        }

        .ek-hero-subtitle {
            font-size: clamp(0.95rem, 2vw, 1.15rem);
            color: rgba(255, 255, 255, 0.95);
            max-width: 600px;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            padding: 0.4rem 1.25rem !important;
            background: #002B49 !important;
            border-radius: var(--radius-full);
            display: inline-block;
            width: fit-content;
        }

        .ek-hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        /* ============================================
           BOTONES
        ============================================ */
        .ek-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.9rem 1.75rem;
            border-radius: var(--radius-full);
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all var(--transition);
            cursor: pointer;
            border: none;
            -webkit-tap-highlight-color: transparent;
            will-change: transform;
        }

        .ek-btn-primary {
            background: linear-gradient(135deg, var(--ek-accent) 0%, var(--ek-accent-light) 100%);
            color: white;
            box-shadow: 0 4px 20px rgba(237, 139, 0, 0.35);
        }

        .ek-btn-primary:hover {
            transform: translate3d(0, -3px, 0);
            box-shadow: 0 8px 30px rgba(237, 139, 0, 0.45);
            color: white;
        }

        .ek-btn-primary:active {
            transform: translate3d(0, 0, 0) scale(0.98);
        }

        .ek-btn-outline {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.35);
        }

        .ek-btn-outline:hover {
            background: rgba(255, 255, 255, 0.85);
            color: white;
            transform: translate3d(0, -2px, 0);
        }

        /* Swiper Pagination */
        .ek-hero-swiper .swiper-button-next,
        .ek-hero-swiper .swiper-button-prev {
            display: none;
        }

        .ek-hero-swiper .swiper-pagination {
            bottom: 1.5rem;
        }

        .ek-hero-swiper .swiper-pagination-bullet {
            width: 10px;
            height: 10px;
            background: rgba(255, 255, 255, 0.4);
            opacity: 1;
            transition: all var(--transition);
        }

        .ek-hero-swiper .swiper-pagination-bullet-active {
            background: var(--ek-accent);
            width: 28px;
            border-radius: var(--radius-full);
        }

        /* ============================================
           SECTION HEADERS
        ============================================ */
        .ek-section-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .ek-section-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.45rem 1rem;
            background: linear-gradient(135deg, var(--ek-primary) 0%, var(--ek-primary-light) 100%);
            border-radius: var(--radius-full);
            color: white;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        .ek-section-title {
            font-family: var(--ek-font-brand);
            font-size: clamp(1.5rem, 4vw, 2.25rem);
            font-weight: 400;
            color: var(--ek-primary);
            margin-bottom: 0.5rem;
        }

        .ek-section-subtitle {
            font-size: clamp(0.9rem, 2vw, 1rem);
            color: var(--ek-neutral);
        }

        /* ============================================
           COLECCIONES
        ============================================ */
        .ek-colecciones {
            padding: 3.5rem 0;
        }

        .ek-collections-swiper {
            padding: 1rem 0 2.5rem;
        }

        .ek-collection-card {
            display: block;
            text-decoration: none;
            background: var(--glass-bg);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: var(--radius-lg);
            overflow: hidden;
            transition: all var(--transition);
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
        }

        .ek-collection-card:hover {
            transform: translate3d(0, -8px, 0);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.12);
            border-color: var(--ek-accent);
        }

        .ek-collection-img {
            position: relative;
            height: 180px;
            overflow: hidden;
            background: linear-gradient(135deg, var(--ek-surface) 0%, var(--ek-surface-warm) 100%);
        }

        .ek-collection-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform var(--transition-slow);
        }

        .ek-collection-card:hover .ek-collection-img img {
            transform: scale(1.08);
        }

        .ek-collection-img-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            font-size: 2.5rem;
            color: var(--ek-neutral-light);
        }

        .ek-collection-body {
            padding: 1.25rem;
            text-align: center;
        }

        .ek-collection-name {
            font-family: var(--ek-font-brand);
            font-size: 1.1rem;
            font-weight: 400;
            color: var(--ek-primary);
            margin-bottom: 0.35rem;
        }

        .ek-collection-count {
            font-size: 0.85rem;
            color: var(--ek-accent);
            font-weight: 500;
        }

        /* ============================================
           PROMO BANNER - Degradados Suaves
        ============================================ */
        .ek-promo {
            padding: 2.5rem 0;
        }

        .ek-promo-card {
            position: relative;
            border-radius: var(--radius-xl);
            overflow: hidden;
            min-height: 360px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
        }

        .ek-nav-overlay {
            position: absolute;
            inset: 0;
            background: transparent;
            z-index: 1;
        }

        .ek-promo-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .ek-promo-bg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        /* Overlay sutil - Navy Solid */
        .ek-promo-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: transparent; /* Overlay removed as pills provide legibility */
            z-index: 2;
        }

        .ek-promo-content {
            position: relative;
            z-index: 3;
            padding: 3.5rem;
            max-width: 650px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            min-height: 360px;
            text-align: left;
        }

        .ek-promo-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.25rem;
            background: linear-gradient(135deg, var(--ek-accent) 0%, var(--ek-accent-light) 100%);
            border-radius: var(--radius-full);
            color: white;
            font-size: 0.75rem;
            font-weight: 700;
            width: fit-content;
            margin-bottom: 1.25rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(237, 139, 0, 0.4);
        }

        .ek-promo-title {
            font-family: var(--ek-font-brand);
            font-size: clamp(1.5rem, 4vw, 2.2rem);
            font-weight: 700;
            color: white;
            line-height: 1.2;
            margin-bottom: 0.75rem;
            padding: 0.5rem 1.5rem !important;
            background: #002B49 !important;
            border-radius: var(--radius-full);
            display: inline-block;
            width: fit-content;
        }

        .ek-promo-text {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 1.75rem;
            line-height: 1.5;
            padding: 0.4rem 1.25rem !important;
            background: #002B49 !important;
            border-radius: var(--radius-full);
            display: inline-block;
            width: fit-content;
        }

        .ek-promo-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.9rem 1.75rem;
            background: linear-gradient(135deg, var(--ek-accent) 0%, var(--ek-accent-light) 100%);
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            border-radius: var(--radius-full);
            transition: all var(--transition);
            width: fit-content;
            box-shadow: 0 4px 20px rgba(237, 139, 0, 0.35);
        }

        .ek-promo-btn:hover {
            transform: translate3d(0, -3px, 0);
            box-shadow: 0 8px 30px rgba(237, 139, 0, 0.5);
            color: white;
        }

        /* ============================================
           RECOMENDACIONES - Degradados Suaves
        ============================================ */
        .ek-recommendations {
            padding: 3.5rem 0;
            background: white;
        }

        .ek-recommendations-swiper {
            height: 380px;
            border-radius: var(--radius-xl);
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
        }

        .ek-recommendations-swiper .swiper-button-next,
        .ek-recommendations-swiper .swiper-button-prev {
            display: none;
        }

        .ek-rec-slide {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }

        .ek-rec-bg {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
        }

        /* Overlay suave - tonos Navy Solid */
        .ek-rec-overlay {
            position: absolute;
            inset: 0;
            background: transparent; /* Overlay removed as pills provide legibility */
        }

        .ek-rec-content {
            position: relative;
            z-index: 10;
            text-align: left;
            padding: 2.5rem 4rem;
            max-width: 100%;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .ek-rec-title {
            font-family: var(--ek-font-brand);
            font-size: clamp(1.4rem, 4vw, 2rem);
            font-weight: 700;
            color: white;
            margin-bottom: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0.5rem 1.5rem !important;
            background: #002B49 !important;
            border-radius: var(--radius-full);
            display: inline-block;
            width: fit-content;
        }

        .ek-rec-text {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 1.75rem;
            line-height: 1.6;
            padding: 0.4rem 1.25rem !important;
            background: #002B49 !important;
            border-radius: var(--radius-full);
            display: inline-block;
            width: fit-content;
        }

        .ek-recommendations-swiper .swiper-pagination {
            bottom: 1.25rem;
        }

        .ek-recommendations-swiper .swiper-pagination-bullet {
            width: 8px;
            height: 8px;
            background: rgba(255, 255, 255, 0.4);
            opacity: 1;
            transition: all var(--transition);
        }

        .ek-recommendations-swiper .swiper-pagination-bullet-active {
            background: var(--ek-accent);
            width: 24px;
            border-radius: var(--radius-full);
        }

        /* ============================================
           PRODUCTOS DESTACADOS
        ============================================ */
        .ek-productos {
            padding: 3.5rem 0 4.5rem;
        }

        .ek-productos-intro {
            text-align: center;
            font-size: clamp(0.9rem, 2vw, 1rem);
            color: var(--ek-neutral);
            max-width: 550px;
            margin: 0 auto;
        }

        .ek-productos-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-top: 2.5rem;
        }

        .ek-producto-card {
            background: var(--glass-bg);
            /* Removed blur as per general request to clean up views */
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: var(--radius-lg);
            overflow: hidden;
            transition: all var(--transition);
            position: relative;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            will-change: transform;
        }

        .ek-producto-card:hover {
            transform: translate3d(0, -8px, 0);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border-color: var(--ek-accent);
        }

        .ek-producto-badge {
            position: absolute;
            top: 0.9rem;
            left: 0.9rem;
            z-index: 10;
            padding: 0.35rem 0.9rem;
            background: linear-gradient(135deg, var(--ek-accent) 0%, var(--ek-accent-light) 100%);
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            border-radius: var(--radius-full);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 10px rgba(237, 139, 0, 0.3);
        }

        .ek-producto-img {
            position: relative;
            height: 200px;
            background: linear-gradient(135deg, var(--ek-surface) 0%, var(--ek-surface-warm) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .ek-producto-img img {
            max-width: 80%;
            max-height: 80%;
            object-fit: contain;
            transition: transform var(--transition);
        }

        .ek-producto-card:hover .ek-producto-img img {
            transform: scale(1.08);
        }

        .ek-producto-img-placeholder {
            font-size: 3.5rem;
            color: var(--ek-neutral-light);
        }

        .ek-producto-actions {
            position: absolute;
            top: 0.9rem;
            right: 0.9rem;
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
            opacity: 0;
            transform: translate3d(10px, 0, 0);
            transition: all var(--transition);
        }

        .ek-producto-card:hover .ek-producto-actions {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }

        .ek-producto-action-btn {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: var(--radius-full);
            color: var(--ek-primary);
            font-size: 0.95rem;
            cursor: pointer;
            transition: all var(--transition);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .ek-producto-action-btn:hover {
            background: var(--ek-accent);
            color: white;
            transform: scale(1.1);
        }

        .ek-producto-body {
            padding: 1.25rem;
        }

        .ek-producto-categoria {
            font-size: 0.75rem;
            color: var(--ek-neutral-light);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.4rem;
        }

        .ek-producto-nombre {
            font-family: var(--ek-font-brand);
            font-size: 1rem;
            font-weight: 400;
            color: var(--ek-primary);
            margin-bottom: 0.6rem;
            line-height: 1.4;
        }

        .ek-producto-nombre a {
            color: inherit;
            text-decoration: none;
            transition: color var(--transition-fast);
        }

        .ek-producto-nombre a:hover {
            color: var(--ek-accent);
        }

        .ek-producto-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 0.9rem;
            padding-top: 0.9rem;
            border-top: 1px solid rgba(0, 0, 0, 0.06);
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .ek-producto-precio {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--ek-accent);
        }

        .ek-producto-btn {
            padding: 0.5rem 1.1rem;
            background: var(--ek-primary);
            color: white;
            border: none;
            border-radius: var(--radius-full);
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition);
            text-decoration: none;
        }

        .ek-producto-btn:hover {
            background: var(--ek-accent);
            color: white;
            transform: translate3d(0, -2px, 0);
        }

        /* Empty State */
        .ek-empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 3.5rem 2rem;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border-radius: var(--radius-lg);
            border: 2px dashed rgba(0, 0, 0, 0.1);
        }

        .ek-empty-state i {
            font-size: 3.5rem;
            color: var(--ek-neutral-light);
            margin-bottom: 1rem;
        }

        .ek-empty-state p {
            font-size: 1rem;
            color: var(--ek-neutral);
        }

        /* ============================================
           ACERCA DE (ABOUT)
        ============================================ */
        .ek-about {
            padding: 5rem 0;
            background: linear-gradient(180deg, white 0%, var(--ek-surface-warm) 100%);
            position: relative;
            overflow: hidden;
        }

        .ek-about::before {
            content: '';
            position: absolute;
            top: -10%;
            right: -5%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(237, 139, 0, 0.05) 0%, transparent 70%);
            border-radius: 50%;
            z-index: 0;
        }

        .ek-about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .ek-about-image-container {
            position: relative;
        }

        .ek-about-image-wrapper {
            position: relative;
            border-radius: var(--radius-xl);
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0, 43, 73, 0.15);
            background: var(--ek-navy);
            aspect-ratio: 4/5;
        }

        .ek-about-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.9;
            transition: transform var(--transition-slow);
        }

        .ek-about-image-container:hover img {
            transform: scale(1.05);
        }

        .ek-about-floating-card {
            position: absolute;
            bottom: -2rem;
            right: -2rem;
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(0, 43, 73, 0.1);
            padding: 2rem;
            border-radius: var(--radius-lg);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            max-width: 240px;
            animation: float 4s ease-in-out infinite;
        }

        .ek-about-floating-card i {
            font-size: 2.5rem;
            color: var(--ek-accent);
            margin-bottom: 1rem;
            display: block;
        }

        .ek-about-floating-card h4 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #002B49;
            margin-bottom: 0.5rem;
        }

        .ek-about-floating-card p {
            font-size: 0.85rem;
            color: #002B49;
            line-height: 1.5;
            opacity: 0.9;
        }

        .ek-about-content {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .ek-about-text {
            font-size: 1.05rem;
            line-height: 1.8;
            color: var(--ek-neutral);
        }

        .ek-about-features {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .ek-about-feature-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 1.25rem;
        }

        .ek-about-feature-icon {
            width: 54px;
            height: 54px;
            background: rgba(237, 139, 0, 0.1);
            border: 1px solid rgba(237, 139, 0, 0.2);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--ek-accent);
            font-size: 1.5rem;
            flex-shrink: 0;
            transition: all var(--transition);
        }

        .ek-about-feature-item:hover .ek-about-feature-icon {
            background: var(--ek-accent);
            color: white;
            transform: translateY(-5px) rotate(8deg);
            box-shadow: 0 8px 20px rgba(237, 139, 0, 0.3);
        }

        .ek-about-feature-text h5 {
            font-size: 1rem;
            font-weight: 700;
            color: #002B49;
            margin-bottom: 0.25rem;
        }

        .ek-about-feature-text p {
            font-size: 0.85rem;
            color: #002B49;
            opacity: 0.8;
        }

        @media (max-width: 992px) {
            .ek-about-grid {
                grid-template-columns: 1fr;
                gap: 3rem;
            }

            .ek-about-image-container {
                max-width: 500px;
                margin: 0 auto;
            }

            .ek-about-content {
                text-align: center;
                align-items: center;
            }

            .ek-about-features {
                text-align: left;
            }
        }

        @media (max-width: 576px) {
            .ek-about-floating-card {
                position: relative;
                bottom: auto;
                right: auto;
                max-width: 100%;
                margin: -3rem 1rem 0;
                text-align: center;
            }

            .ek-about-features {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
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
            .container {
                padding: 0 1.25rem;
            }

            .ek-hero-swiper {
                height: 450px;
            }

            .ek-hero-overlay {
                background: linear-gradient(180deg,
                        rgba(26, 54, 93, 0.75) 0%,
                        rgba(44, 82, 130, 0.5) 50%,
                        rgba(100, 116, 139, 0.3) 100%);
            }

            .ek-productos-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .ek-promo-overlay {
                background: linear-gradient(180deg,
                        rgba(26, 54, 93, 0.85) 0%,
                        rgba(44, 82, 130, 0.6) 50%,
                        rgba(100, 116, 139, 0.3) 100%);
            }

            .ek-promo-content {
                padding: 2.5rem;
                max-width: 100%;
            }

            .ek-colecciones,
            .ek-recommendations,
            .ek-productos {
                padding: 3rem 0;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }

            .ek-hero {
                margin-bottom: 2rem;
            }

            .ek-hero-swiper {
                height: 400px;
                border-radius: 0 0 var(--radius-lg) var(--radius-lg);
            }

            .ek-hero-content {
                padding: 2rem 1.5rem;
            }

            .ek-promo {
                padding: 2rem 0;
            }

            .ek-promo-card {
                min-height: 380px;
                border-radius: var(--radius-lg);
            }

            .ek-promo-content {
                padding: 2rem 1.5rem;
                text-align: center;
                align-items: center;
                min-height: 380px;
            }

            .ek-recommendations-swiper {
                height: 340px;
                border-radius: var(--radius-lg);
            }

            .ek-promo-text {
                font-size: 0.8rem !important;
                padding: 0.25rem 0.85rem !important;
                margin-bottom: 1rem;
            }

            .ek-rec-content {
                padding: 1.5rem 1rem !important;
            }

            .ek-rec-title {
                font-size: 1.2rem !important;
                padding: 0.35rem 1rem !important;
            }

            .ek-rec-text {
                font-size: 0.8rem !important;
                padding: 0.25rem 0.85rem !important;
                margin-bottom: 1rem;
            }

            .ek-section-header {
                margin-bottom: 2rem;
            }

            .ek-collection-img {
                height: 150px;
            }
        }

        @media (max-width: 576px) {
            .ek-hero-swiper {
                height: 360px;
            }

            .ek-hero-content {
                padding: 1.5rem;
            }

            .ek-hero-buttons {
                flex-direction: column;
                gap: 0.75rem;
            }

            .ek-hero-buttons .ek-btn {
                width: 100%;
                justify-content: center;
            }

            .ek-btn {
                padding: 0.85rem 1.5rem;
                font-size: 0.9rem;
            }

            .ek-productos-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .ek-producto-img {
                height: 180px;
            }

            .ek-producto-body {
                padding: 1rem;
            }

            .ek-promo-card {
                min-height: 340px;
            }

            .ek-promo-content {
                min-height: 340px;
                padding: 1.5rem;
            }

            .ek-recommendations-swiper {
                height: 300px;
            }

            .ek-colecciones,
            .ek-recommendations,
            .ek-productos {
                padding: 2.5rem 0;
            }

            .ek-empty-state {
                padding: 2.5rem 1.5rem;
            }
        }

        /* ============================================
           PREFERENCIAS DE MOVIMIENTO REDUCIDO
        ============================================ */
        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.15s !important;
            }
        }
    </style>
</head>

<body>

    <?php
    $pagina_actual = 'productos';
    require_once VIEWS_PATH . 'layouts/nav_publico.php';
    ?>

    <!-- ========== HERO ========== -->
    <section class="ek-hero">
        <div class="swiper ek-hero-swiper">
            <div class="swiper-wrapper">
                <?php if (empty($banners_hero)): ?>
                    <div class="swiper-slide ek-hero-slide">
                        <div class="ek-hero-bg" style="background-image: url('<?= e(asset('img/banner1.png')) ?>');"></div>
                        <div class="container">
                            <div class="ek-hero-content">
                                <div class="ek-hero-badge">
                                    <i class="bi bi-stars"></i> Nueva Coleccion 2025
                                </div>
                                <h1 class="ek-hero-title ek-text-pill animate-on-scroll">
                                    <?= e($ajustes['hero_titulo'] ?? 'Premium Home & Style') ?>
                                </h1>
                                <p class="ek-hero-subtitle ek-text-pill animate-on-scroll">
                                    <?= e($ajustes['hero_subtitulo'] ?? 'Elegancia y funcionalidad para cada rincon de tu hogar contemporaneo.') ?>
                                </p>
                                <div class="ek-hero-buttons">
                                    <a href="<?= BASE_URL ?>productos" class="ek-btn ek-btn-primary">
                                        Ver Catalogo <i class="bi bi-arrow-right"></i>
                                    </a>
                                    <a href="#colecciones" class="ek-btn ek-btn-outline">
                                        <i class="bi bi-grid-3x3-gap"></i> Colecciones
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($banners_hero as $b): ?>
                        <div class="swiper-slide ek-hero-slide">
                            <div class="ek-hero-bg" style="background-image: url('<?= e(asset($b['imagen'])) ?>');"></div>
                            <div class="container">
                                <div class="ek-hero-content">
                                    <div class="ek-hero-badge">
                                        <i class="bi bi-stars"></i> Destacado
                                    </div>
                                    <h1 class="ek-hero-title ek-text-pill">
                                        <?= e($b['titulo']) ?>
                                        <?php if (!empty($b['subtitulo'])): ?>
                                            <br><span>
                                                <?= e($b['subtitulo']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </h1>
                                    <?php if (!empty($b['texto_boton'])): ?>
                                        <div class="ek-hero-buttons">
                                            <a href="<?= $b['enlace'] ? (strpos($b['enlace'], 'http') === 0 ? e($b['enlace']) : BASE_URL . e($b['enlace'])) : '#productos' ?>"
                                                class="ek-btn ek-btn-primary">
                                                <?= e($b['texto_boton']) ?> <i class="bi bi-arrow-right"></i>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <!-- ========== COLECCIONES ========== -->
    <section class="ek-colecciones" id="colecciones">
        <div class="container">
            <div class="ek-section-header animate-on-scroll">
                <div class="ek-section-badge">
                    <i class="bi bi-collection"></i> Explora
                </div>
                <h2 class="ek-section-title">
                    <?= !empty($colecciones) ? 'Nuestras Colecciones' : 'Nuestras Categorias' ?>
                </h2>
                <p class="ek-section-subtitle">
                    <?= !empty($colecciones) ? 'Disenos pensados para cada espacio de tu vida' : 'Explora nuestros productos por categoria' ?>
                </p>
            </div>

            <div class="swiper ek-collections-swiper">
                <div class="swiper-wrapper">
                    <?php
                    $items_home = [];
                    if (!empty($colecciones)) {
                        foreach ($colecciones as $col) {
                            $col['tipo_item'] = 'coleccion';
                            $items_home[] = $col;
                        }
                    }
                    if (!empty($categorias_destacadas)) {
                        foreach ($categorias_destacadas as $cat) {
                            $cat['tipo_item'] = 'categoria';
                            $items_home[] = $cat;
                        }
                    }

                    $processed_ids = [];
                    foreach ($items_home as $item):
                        $item_id = ($item['tipo_item'] === 'coleccion' ? 'col_' : 'cat_') . $item['id'];
                        if (in_array($item_id, $processed_ids))
                            continue;
                        $processed_ids[] = $item_id;

                        $is_col = $item['tipo_item'] === 'coleccion';
                        $item_slug = e($item['slug']);
                        $item_nombre = e($item['nombre']);
                        $item_link = BASE_URL . "productos/" . ($is_col ? 'coleccion' : 'categoria') . "/" . $item_slug;
                        ?>
                        <div class="swiper-slide">
                            <a href="<?= $item_link ?>" class="ek-collection-card">
                                <div class="ek-collection-img">
                                    <?php if (!empty($item['imagen'])): ?>
                                        <img src="<?= e(asset($item['imagen'])) ?>" alt="<?= $item_nombre ?>" loading="lazy">
                                    <?php else: ?>
                                        <div class="ek-collection-img-placeholder">
                                            <i class="bi <?= e($item['icono'] ?? 'bi-grid-3x3-gap') ?>"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="ek-collection-body">
                                    <h3 class="ek-collection-name">
                                        <?= e($item['nombre']) ?>
                                    </h3>
                                    <span class="ek-collection-count">Ver productos</span>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <!-- ========== PROMO ========== -->
    <?php if (!empty($ajustes['promo_titulo'])): ?>
        <section class="ek-promo">
            <div class="container">
                <div class="ek-promo-card animate-on-scroll">
                    <?php if (!empty($ajustes['promo_imagen'])): ?>
                        <div class="ek-promo-bg">
                            <img src="<?= e(asset($ajustes['promo_imagen'])) ?>" alt="<?= e($ajustes['promo_titulo']) ?>">
                        </div>
                    <?php endif; ?>
                    <div class="ek-promo-content">
                        <div class="ek-promo-tag">
                            <i class="bi bi-megaphone-fill"></i>
                            <?= e($ajustes['promo_etiqueta'] ?? 'Especial') ?>
                        </div>
                        <h2 class="ek-promo-title ek-text-pill">
                            <?= e($ajustes['promo_titulo']) ?>
                        </h2>
                        <p class="ek-promo-text ek-text-pill">
                            <?= e($ajustes['promo_texto'] ?? 'Descubre nuestra seleccion premium con beneficios unicos.') ?>
                        </p>
                        <?php if (!empty($ajustes['promo_link'])): ?>
                            <a href="<?= e($ajustes['promo_link']) ?>" class="ek-promo-btn">
                                <?= e($ajustes['promo_boton'] ?? 'Ver Promoción') ?>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- ========== PRODUCTOS ========== -->
    <section class="ek-productos" id="productos">
        <div class="container">
            <div class="ek-section-header animate-on-scroll">
                <div class="ek-section-badge">
                    <i class="bi bi-star-fill"></i> Lo Mejor
                </div>
                <h2 class="ek-section-title">Productos Destacados</h2>
                <p class="ek-productos-intro">Nuestros productos mejor valorados con diseno y calidad premium</p>
            </div>

            <?php if (empty($productos_destacados)): ?>
                <div class="ek-productos-grid">
                    <div class="ek-empty-state">
                        <i class="bi bi-box-seam"></i>
                        <p>No hay productos destacados por el momento</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="ek-productos-grid" id="productos-grid">
                    <?php foreach (array_slice($productos_destacados, 0, 8) as $index => $producto): ?>
                        <div class="ek-producto-card animate-on-scroll">
                            <?php if (!empty($producto['nuevo'])): ?>
                                <div class="ek-producto-badge">Nuevo</div>
                            <?php endif; ?>

                            <div class="ek-producto-actions">
                                <button class="ek-producto-action-btn" title="Favoritos">
                                    <i class="bi bi-heart"></i>
                                </button>
                                <button class="ek-producto-action-btn" title="Vista rapida">
                                    <i class="bi bi-eye"></i>
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
                                    <?= e($producto['categoria_nombre'] ?? 'Producto') ?>
                                </div>
                                <h3 class="ek-producto-nombre">
                                    <a href="<?= BASE_URL ?>productos/detalle/<?= e($producto['slug']) ?>">
                                        <?= e($producto['nombre']) ?>
                                    </a>
                                </h3>
                                <div class="ek-producto-footer">
                                    <span class="ek-producto-precio">
                                        <?= !empty($producto['precio_referencia']) ? '$' . number_format($producto['precio_referencia'], 2) : '' ?>
                                    </span>
                                    <a href="<?= BASE_URL ?>productos/detalle/<?= e($producto['slug']) ?>"
                                        class="ek-producto-btn">
                                        Ver Mas
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- ========== RECOMENDACIONES ========== -->
    <section class="ek-recommendations">
        <div class="container">
            <div class="ek-section-header animate-on-scroll">
                <div class="ek-section-badge">
                    <i class="bi bi-hand-thumbs-up"></i> Para Ti
                </div>
                <h2 class="ek-section-title">Recomendado para Ti</h2>
                <p class="ek-section-subtitle">Selecciones especiales basadas en lo que mas te gusta</p>
            </div>

            <div class="swiper ek-recommendations-swiper">
                <div class="swiper-wrapper">
                    <?php
                    $recom = !empty($banners_recom) ? $banners_recom : $productos_destacados;
                    foreach (array_slice($recom, 0, 3) as $r):
                        ?>
                        <div class="swiper-slide ek-rec-slide">
                            <div class="ek-rec-bg"
                                style="background-image: url('<?= e(asset($r['imagen'] ?? $r['imagen_principal'])) ?>');">
                            </div>
                                <div class="ek-rec-content">
                                <h3 class="ek-rec-title ek-text-pill">
                                    <?= e($r['titulo'] ?? $r['nombre']) ?>
                                </h3>
                                <p class="ek-rec-text ek-text-pill">
                                    <?= e($r['subtitulo'] ?? $r['descripcion_corta'] ?? 'Diseno funcional para tu hogar contemporaneo.') ?>
                                </p>
                                <a href="<?= BASE_URL ?>productos" class="ek-btn ek-btn-primary">
                                    Explorar Ahora <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <!-- ========== ACERCA DE ========== -->
    <section class="ek-about" id="about">
        <div class="container">
            <div class="ek-about-grid">
                <div class="ek-about-image-container animate-on-scroll">
                    <div class="ek-about-image-wrapper">
                        <?php
                        $aboutImg = !empty($ajustes['about_imagen']) ? asset($ajustes['about_imagen']) : 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=1200';
                        ?>
                        <img src="<?= $aboutImg ?>" alt="Ekuora Concept">
                    </div>
                    <div class="ek-about-floating-card">
                        <i class="bi bi-patch-check-fill"></i>
                        <h4>Calidad Premium</h4>
                        <p>Cada pieza es seleccionada bajo los mas altos estandares de durabilidad y estilo.</p>
                    </div>
                </div>

                <div class="ek-about-content animate-on-scroll">
                    <div class="ek-section-header" style="text-align: left; margin-bottom: 1.5rem;">
                        <div class="ek-section-badge">
                            <i class="bi bi-info-circle"></i> <?= e($ajustes['about_badge'] ?? 'Nuestra Esencia') ?>
                        </div>
                        <h2 class="ek-section-title" style="font-family: var(--ek-font-brand); font-weight: 400;">
                            <?= e($ajustes['about_titulo'] ?? 'Diseñamos espacios con alma propia') ?>
                        </h2>
                    </div>

                    <div class="ek-about-text">
                        <?php if (!empty($ajustes['about_descripcion'])): ?>
                            <?= nl2br(e($ajustes['about_descripcion'])) ?>
                        <?php else: ?>
                            <p>En <strong>Ekuora</strong>, creemos que tu hogar es mas que un lugar; es el reflejo de tu
                                identidad. Nos apasiona curar colecciones que combinan la funcionalidad moderna con una
                                estetica atemporal.</p>
                            <p style="margin-top: 1rem;">Nuestra mision es transformar cada rincon en una experiencia de
                                confort y elegancia, ofreciendo productos de vanguardia que cuentan historias y crean
                                recuerdos duraderos.</p>
                        <?php endif; ?>
                    </div>

                    <div class="ek-about-features">
                        <div class="ek-about-feature-item">
                            <div class="ek-about-feature-icon">
                                <i class="bi bi-lightbulb"></i>
                            </div>
                            <div class="ek-about-feature-text">
                                <h5>Innovación cotidiana.</h5>
                                <p>Creemos en la innovación cotidiana como parte esencial de lo que hacemos.</p>
                            </div>
                        </div>
                        <div class="ek-about-feature-item">
                            <div class="ek-about-feature-icon">
                                <i class="bi bi-patch-check"></i>
                            </div>
                            <div class="ek-about-feature-text">
                                <h5>Excelencia accesible.</h5>
                                <p>Creemos que el buen diseño no debe de ser un lujo, si no algo accesible para todos.
                                </p>
                            </div>
                        </div>
                        <div class="ek-about-feature-item">
                            <div class="ek-about-feature-icon">
                                <i class="bi bi-rocket-takeoff"></i>
                            </div>
                            <div class="ek-about-feature-text">
                                <h5>Ingenioso, Curioso...</h5>
                                <p style="font-size: 0.8rem;">El que se atreve a cuestionarse la cotidianidad y
                                    mejorarla día a día.</p>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 2rem;">
                        <a href="<?= BASE_URL ?>productos" class="ek-btn ek-btn-primary">
                            Explorar Colección <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <?php require_once VIEWS_PATH . 'layouts/footer_publico.php'; ?>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // ========== SWIPERS ==========

            // Hero Swiper
            new Swiper('.ek-hero-swiper', {
                loop: true,
                autoplay: {
                    delay: 5500,
                    disableOnInteraction: false
                },
                effect: 'fade',
                fadeEffect: { crossFade: true },
                speed: 600,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true
                }
            });

            // Collections Swiper
            new Swiper('.ek-collections-swiper', {
                slidesPerView: 1,
                spaceBetween: 16,
                speed: 400,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true
                },
                breakpoints: {
                    576: { slidesPerView: 2, spaceBetween: 20 },
                    768: { slidesPerView: 3, spaceBetween: 20 },
                    1200: { slidesPerView: 4, spaceBetween: 24 }
                }
            });

            // Recommendations Swiper
            new Swiper('.ek-recommendations-swiper', {
                effect: 'fade',
                fadeEffect: { crossFade: true },
                loop: true,
                speed: 600,
                autoplay: {
                    delay: 4500,
                    disableOnInteraction: false
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true
                }
            });

            // ========== ANIMACIONES OPTIMIZADAS ==========

            const animatedElements = document.querySelectorAll('.animate-on-scroll');

            // Usar IntersectionObserver para mejor rendimiento
            const observerOptions = {
                threshold: 0.15,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // Usar requestAnimationFrame para mejor rendimiento
                        requestAnimationFrame(() => {
                            entry.target.classList.add('is-visible');
                        });
                        // Dejar de observar una vez visible
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            animatedElements.forEach(el => observer.observe(el));

            // ========== LAZY LOAD OPTIMIZADO ==========

            const lazyImages = document.querySelectorAll('img[loading="lazy"]');

            lazyImages.forEach(img => {
                if (!img.complete) {
                    img.style.opacity = '0';
                    img.style.transition = 'opacity 0.3s ease-out';

                    img.addEventListener('load', function () {
                        requestAnimationFrame(() => {
                            this.style.opacity = '1';
                        });
                    }, { once: true });
                }
            });

            // ========== TOUCH FEEDBACK (Mobile) ==========

            if ('ontouchstart' in window) {
                const interactiveElements = document.querySelectorAll('.ek-btn, .ek-producto-card, .ek-collection-card');

                interactiveElements.forEach(el => {
                    el.addEventListener('touchstart', function () {
                        this.style.transform = 'scale(0.98)';
                    }, { passive: true });

                    el.addEventListener('touchend', function () {
                        this.style.transform = '';
                    }, { passive: true });
                });
            }

        });
    </script>
</body>

</html>