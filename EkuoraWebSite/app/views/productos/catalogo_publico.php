<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Catálogo | <?= APP_NAME ?? 'Cerelit' ?></title>

    <link rel="icon" type="image/png" href="<?= e(asset('favicon.png')) ?>">
    <link rel="shortcut icon" href="<?= e(asset('favicon.png')) ?>" type="image/png">
    <link rel="apple-touch-icon" href="<?= e(asset('favicon.png')) ?>">

    <!-- Fonts: Plus Jakarta Sans + Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons & Swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        /* ============================================
           CERELIT · LIQUID CRYSTAL EXPRESSIVE
           Glassmorphism · Pill Shapes · Rich Animations
        ============================================ */

        :root {
            /* Cerelit Brand Colors */
            --cl-green:          #3A9D8A;
            --cl-green-light:    #4db8a3;
            --cl-green-pale:     rgba(58, 157, 138, 0.08);
            --cl-blue:           #2A4D69;
            --cl-blue-dark:      #1e3a4f;
            --cl-blue-pale:      rgba(42, 77, 105, 0.06);
            --cl-orange:         #FF8C00;
            --cl-orange-light:   #ffaa33;
            --cl-orange-glow:    rgba(255, 140, 0, 0.35);
            --cl-cream:          #F4F4F4;
            --cl-white:          #FFFFFF;
            --cl-text:           #1E293B;
            --cl-text-muted:     #64748b;
            --cl-text-light:     #94a3b8;

            /* Glass */
            --glass-white:       rgba(255, 255, 255, 0.90);
            --glass-border:      rgba(255, 255, 255, 0.6);
            --glass-green:       rgba(58, 157, 138, 0.12);
            --glass-blur:        blur(20px);

            /* Radii */
            --r-pill: 9999px;
            --r-xl:   3rem;
            --r-lg:   2rem;
            --r-md:   1.5rem;
            --r-sm:   1rem;

            /* Typography */
            --font-display: 'Plus Jakarta Sans', sans-serif;
            --font-body:    'Inter', sans-serif;

            /* Transitions */
            --t-fast:   0.15s ease-out;
            --t:        0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --t-slow:   0.5s cubic-bezier(0.4, 0, 0.2, 1);

            /* Shadows */
            --shadow-sm:   0 2px 8px rgba(42, 77, 105, 0.06);
            --shadow-md:   0 8px 32px rgba(42, 77, 105, 0.10);
            --shadow-lg:   0 20px 60px rgba(42, 77, 105, 0.14);
            --shadow-green: 0 8px 32px rgba(58, 157, 138, 0.25);
        }

        /* ========== RESET ========== */
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        html {
            scroll-behavior: smooth;
            overflow-x: hidden;
        }

        body {
            font-family: var(--font-body);
            background: var(--cl-cream);
            color: var(--cl-text);
            overflow-x: hidden;
            width: 100%;
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1.5rem;
            width: 100%;
        }

        /* ========== SCROLL ANIMATION CLASSES ========== */
        [data-reveal] {
            opacity: 0;
            transition: opacity var(--t-slow), transform var(--t-slow);
            will-change: opacity, transform;
        }

        [data-reveal="up"]    { transform: translateY(48px); }
        [data-reveal="down"]  { transform: translateY(-32px); }
        [data-reveal="left"]  { transform: translateX(-56px); }
        [data-reveal="right"] { transform: translateX(56px); }
        [data-reveal="scale"] { transform: scale(0.88); }
        [data-reveal="fade"]  { transform: none; }

        [data-reveal].revealed {
            opacity: 1;
            transform: none;
        }

        /* Stagger delay utilities */
        [data-delay="1"] { transition-delay: 0.1s; }
        [data-delay="2"] { transition-delay: 0.2s; }
        [data-delay="3"] { transition-delay: 0.3s; }
        [data-delay="4"] { transition-delay: 0.4s; }
        [data-delay="5"] { transition-delay: 0.5s; }
        [data-delay="6"] { transition-delay: 0.6s; }
        [data-delay="7"] { transition-delay: 0.7s; }
        [data-delay="8"] { transition-delay: 0.8s; }

        /* ========== KEYFRAMES ========== */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-12px); }
        }

        @keyframes pulse-ring {
            0%   { transform: scale(0.9); opacity: 0.7; }
            100% { transform: scale(1.6); opacity: 0; }
        }

        @keyframes shimmer {
            0%   { background-position: -200% center; }
            100% { background-position:  200% center; }
        }

        @keyframes gradient-shift {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }

        /* ========== GLOBAL BUTTONS ========== */
        .cl-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.85rem 1.75rem;
            border-radius: var(--r-pill);
            font-family: var(--font-body);
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all var(--t);
            -webkit-tap-highlight-color: transparent;
        }

        .cl-btn-primary {
            background: var(--cl-green);
            color: var(--cl-white);
            box-shadow: var(--shadow-green);
        }

        .cl-btn-primary:hover {
            background: var(--cl-green-light);
            color: var(--cl-white);
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(58, 157, 138, 0.4);
        }

        .cl-btn-orange {
            background: var(--cl-orange);
            color: var(--cl-white);
            box-shadow: 0 6px 24px var(--cl-orange-glow);
        }

        .cl-btn-orange:hover {
            background: var(--cl-orange-light);
            color: var(--cl-white);
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(255, 140, 0, 0.45);
        }

        .cl-btn-ghost {
            background: rgba(255, 255, 255, 0.15);
            color: var(--cl-white);
            border: 1.5px solid rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(8px);
        }

        .cl-btn-ghost:hover {
            background: rgba(255, 255, 255, 0.28);
            color: var(--cl-white);
            transform: translateY(-2px);
        }

        .cl-btn-ghost-green {
            background: transparent;
            color: var(--cl-green);
            border: 1.5px solid var(--cl-green);
        }

        .cl-btn-ghost-green:hover {
            background: var(--cl-green);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-green);
        }

        /* ========== SECTION HEADER ========== */
        .cl-section-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .cl-section-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.45rem 1.1rem;
            background: var(--cl-green-pale);
            border: 1px solid rgba(58, 157, 138, 0.2);
            border-radius: var(--r-pill);
            color: var(--cl-green);
            font-family: var(--font-body);
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .cl-section-title {
            font-family: var(--font-display);
            font-size: clamp(1.6rem, 4vw, 2.4rem);
            font-weight: 700;
            color: var(--cl-text);
            line-height: 1.2;
            letter-spacing: -0.01em;
            margin-bottom: 0.75rem;
        }

        .cl-section-subtitle {
            font-size: 1rem;
            color: var(--cl-text-muted);
            max-width: 540px;
            margin: 0 auto;
            line-height: 1.7;
        }

        /* ========== HERO SECTION ========== */
        .cl-hero {
            position: relative;
            overflow: hidden;
        }

        .cl-hero-swiper {
            width: 100%;
            height: 580px;
        }

        .cl-hero-slide {
            position: relative;
            display: flex;
            align-items: center;
        }

        .cl-hero-bg {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            transition: transform 8s ease-out;
            will-change: transform;
        }

        .swiper-slide-active .cl-hero-bg {
            transform: scale(1.06);
        }

        .cl-hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                135deg,
                rgba(42, 77, 105, 0.85) 0%,
                rgba(58, 157, 138, 0.45) 60%,
                transparent 100%
            );
        }

        .cl-hero-content {
            position: relative;
            z-index: 10;
            padding: 5rem 0;
            max-width: 640px;
        }

        .cl-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.1rem;
            background: var(--cl-orange);
            border-radius: var(--r-pill);
            color: white;
            font-family: var(--font-body);
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 20px var(--cl-orange-glow);
            animation: float 3.5s ease-in-out infinite;
        }

        .cl-hero-title {
            font-family: var(--font-display);
            font-size: clamp(2rem, 5.5vw, 3.2rem);
            font-weight: 800;
            color: var(--cl-white);
            line-height: 1.15;
            letter-spacing: -0.02em;
            margin-bottom: 1.25rem;
        }

        .cl-hero-title span {
            color: var(--cl-orange);
        }

        .cl-hero-subtitle {
            font-size: clamp(0.95rem, 2vw, 1.1rem);
            color: rgba(255, 255, 255, 0.88);
            line-height: 1.7;
            margin-bottom: 2rem;
            max-width: 520px;
        }

        .cl-hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        /* Swiper pagination */
        .cl-hero-swiper .swiper-pagination {
            bottom: 1.75rem;
        }

        .cl-hero-swiper .swiper-pagination-bullet {
            width: 10px;
            height: 10px;
            background: rgba(255,255,255,0.35);
            opacity: 1;
            transition: all var(--t);
        }

        .cl-hero-swiper .swiper-pagination-bullet-active {
            background: var(--cl-orange);
            width: 28px;
            border-radius: var(--r-pill);
        }

        /* Scroll indicator */
        .cl-hero-scroll {
            position: absolute;
            bottom: 2rem;
            right: 2rem;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            color: rgba(255,255,255,0.6);
            font-family: var(--font-body);
            font-size: 0.75rem;
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .cl-hero-scroll-line {
            width: 1px;
            height: 40px;
            background: linear-gradient(to bottom, rgba(255,255,255,0.6), transparent);
            animation: scroll-line 2s ease-in-out infinite;
        }

        @keyframes scroll-line {
            0%, 100% { opacity: 0.6; transform: scaleY(1); transform-origin: top; }
            50%       { opacity: 0.2; transform: scaleY(0.5); transform-origin: top; }
        }

        /* ========== STATS BAR ========== */
        .cl-stats {
            background: var(--cl-blue);
            padding: 2.5rem 0;
        }

        .cl-stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }

        .cl-stat-item {
            text-align: center;
            padding: 1.25rem;
            border-right: 1px solid rgba(255,255,255,0.1);
        }

        .cl-stat-item:last-child {
            border-right: none;
        }

        .cl-stat-number {
            font-family: var(--font-display);
            font-size: clamp(1.8rem, 4vw, 2.5rem);
            font-weight: 800;
            color: var(--cl-white);
            line-height: 1;
            margin-bottom: 0.35rem;
        }

        .cl-stat-number span.accent {
            color: var(--cl-orange);
        }

        .cl-stat-label {
            font-family: var(--font-body);
            font-size: 0.85rem;
            color: rgba(255,255,255,0.65);
            font-weight: 500;
        }

        /* ========== COLECCIONES ========== */
        .cl-colecciones {
            padding: 5rem 0 4rem;
        }

        .cl-collections-swiper {
            padding: 0.5rem 0 2.5rem;
            overflow: visible;
        }

        .cl-collection-card {
            display: block;
            text-decoration: none;
            background: var(--glass-white);
            border: 1px solid var(--glass-border);
            border-radius: var(--r-lg);
            overflow: hidden;
            transition: all var(--t);
            box-shadow: var(--shadow-sm);
            backdrop-filter: blur(12px);
        }

        .cl-collection-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
            border-color: rgba(58, 157, 138, 0.3);
        }

        .cl-collection-img {
            position: relative;
            height: 190px;
            overflow: hidden;
            background: linear-gradient(135deg, var(--cl-cream) 0%, #eaf7f4 100%);
        }

        .cl-collection-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform var(--t-slow);
        }

        .cl-collection-card:hover .cl-collection-img img {
            transform: scale(1.1);
        }

        .cl-collection-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            font-size: 3rem;
            color: var(--cl-green-light);
            opacity: 0.5;
        }

        .cl-collection-body {
            padding: 1.25rem 1.4rem;
        }

        .cl-collection-name {
            font-family: var(--font-display);
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--cl-text);
            margin-bottom: 0.4rem;
        }

        .cl-collection-link {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--cl-green);
        }

        .cl-collection-card:hover .cl-collection-link i {
            transform: translateX(4px);
        }

        .cl-collection-link i {
            font-size: 0.75rem;
            transition: transform var(--t);
        }

        /* ========== PROMO BANNER ========== */
        .cl-promo {
            padding: 2.5rem 0;
        }

        .cl-promo-card {
            position: relative;
            border-radius: var(--r-xl);
            overflow: hidden;
            min-height: 380px;
            box-shadow: var(--shadow-lg);
        }

        .cl-promo-bg {
            position: absolute;
            inset: 0;
        }

        .cl-promo-bg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .cl-promo-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                100deg,
                rgba(42, 77, 105, 0.88) 0%,
                rgba(58, 157, 138, 0.6) 60%,
                transparent 100%
            );
        }

        .cl-promo-content {
            position: relative;
            z-index: 3;
            padding: 4rem 3.5rem;
            max-width: 600px;
            min-height: 380px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .cl-promo-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.1rem;
            background: var(--cl-orange);
            border-radius: var(--r-pill);
            color: white;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 1.25rem;
            width: fit-content;
            box-shadow: 0 4px 16px var(--cl-orange-glow);
        }

        .cl-promo-title {
            font-family: var(--font-display);
            font-size: clamp(1.6rem, 4vw, 2.4rem);
            font-weight: 800;
            color: white;
            line-height: 1.2;
            letter-spacing: -0.02em;
            margin-bottom: 1rem;
        }

        .cl-promo-text {
            font-size: 1rem;
            color: rgba(255,255,255,0.85);
            line-height: 1.65;
            margin-bottom: 2rem;
        }

        /* ========== PRODUCTOS ========== */
        .cl-productos {
            padding: 5rem 0;
        }

        .cl-productos-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-top: 2.5rem;
        }

        .cl-producto-card {
            background: var(--glass-white);
            border: 1px solid rgba(255,255,255,0.7);
            border-radius: var(--r-lg);
            overflow: hidden;
            transition: all var(--t);
            position: relative;
            box-shadow: var(--shadow-sm);
            backdrop-filter: blur(12px);
        }

        .cl-producto-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
            border-color: rgba(58, 157, 138, 0.25);
        }

        .cl-producto-badge {
            position: absolute;
            top: 0.9rem;
            left: 0.9rem;
            z-index: 10;
            padding: 0.35rem 0.85rem;
            background: var(--cl-orange);
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            border-radius: var(--r-pill);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 12px var(--cl-orange-glow);
        }

        .cl-producto-img {
            position: relative;
            height: 210px;
            background: linear-gradient(135deg, #f0faf8 0%, #e8f4f2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .cl-producto-img img {
            max-width: 80%;
            max-height: 80%;
            object-fit: contain;
            transition: transform var(--t);
        }

        .cl-producto-card:hover .cl-producto-img img {
            transform: scale(1.1);
        }

        .cl-producto-img-placeholder {
            font-size: 3.5rem;
            color: var(--cl-green-light);
            opacity: 0.35;
        }

        .cl-producto-actions {
            position: absolute;
            top: 0.9rem;
            right: 0.9rem;
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
            opacity: 0;
            transform: translateX(14px);
            transition: all var(--t);
        }

        .cl-producto-card:hover .cl-producto-actions {
            opacity: 1;
            transform: translateX(0);
        }

        .cl-producto-action-btn {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.92);
            border: none;
            border-radius: var(--r-pill);
            color: var(--cl-text);
            font-size: 0.9rem;
            cursor: pointer;
            transition: all var(--t);
            box-shadow: var(--shadow-sm);
        }

        .cl-producto-action-btn:hover {
            background: var(--cl-green);
            color: white;
            transform: scale(1.1);
            box-shadow: var(--shadow-green);
        }

        .cl-producto-body {
            padding: 1.25rem;
        }

        .cl-producto-categoria {
            font-size: 0.72rem;
            color: var(--cl-text-light);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 0.45rem;
        }

        .cl-producto-nombre {
            font-family: var(--font-display);
            font-size: 0.98rem;
            font-weight: 700;
            color: var(--cl-text);
            margin-bottom: 0.75rem;
            line-height: 1.4;
        }

        .cl-producto-nombre a {
            color: inherit;
            text-decoration: none;
            transition: color var(--t-fast);
        }

        .cl-producto-nombre a:hover {
            color: var(--cl-green);
        }

        .cl-producto-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 0.85rem;
            border-top: 1px solid rgba(42, 77, 105, 0.06);
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .cl-producto-precio {
            font-family: var(--font-display);
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--cl-green);
        }

        .cl-producto-btn {
            padding: 0.5rem 1.1rem;
            background: var(--cl-blue);
            color: white;
            border: none;
            border-radius: var(--r-pill);
            font-family: var(--font-body);
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--t);
            text-decoration: none;
        }

        .cl-producto-btn:hover {
            background: var(--cl-green);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-green);
        }

        .cl-empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 4rem 2rem;
            background: var(--glass-white);
            border: 2px dashed rgba(58, 157, 138, 0.2);
            border-radius: var(--r-lg);
        }

        .cl-empty-state i {
            font-size: 3.5rem;
            color: var(--cl-green-light);
            opacity: 0.4;
            display: block;
            margin-bottom: 1rem;
        }

        .cl-empty-state p {
            color: var(--cl-text-muted);
        }

        /* ========== RECOMENDACIONES ========== */
        .cl-recommendations {
            padding: 5rem 0;
            background: var(--cl-white);
        }

        .cl-rec-swiper {
            height: 400px;
            border-radius: var(--r-xl);
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        .cl-rec-slide {
            position: relative;
            display: flex;
            align-items: center;
        }

        .cl-rec-bg {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
        }

        .cl-rec-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                100deg,
                rgba(42, 77, 105, 0.85) 0%,
                rgba(58, 157, 138, 0.4) 65%,
                transparent 100%
            );
        }

        .cl-rec-content {
            position: relative;
            z-index: 10;
            padding: 3rem 4rem;
            max-width: 580px;
        }

        .cl-rec-title {
            font-family: var(--font-display);
            font-size: clamp(1.4rem, 3.5vw, 2rem);
            font-weight: 800;
            color: white;
            letter-spacing: -0.01em;
            margin-bottom: 0.75rem;
        }

        .cl-rec-text {
            font-size: 0.95rem;
            color: rgba(255,255,255,0.85);
            line-height: 1.65;
            margin-bottom: 1.75rem;
        }

        .cl-rec-swiper .swiper-pagination {
            bottom: 1.5rem;
        }

        .cl-rec-swiper .swiper-pagination-bullet {
            background: rgba(255,255,255,0.4);
            opacity: 1;
            transition: all var(--t);
        }

        .cl-rec-swiper .swiper-pagination-bullet-active {
            background: var(--cl-orange);
            width: 24px;
            border-radius: var(--r-pill);
        }

        /* ========== ABOUT ========== */
        .cl-about {
            padding: 6rem 0;
            background: var(--cl-cream);
            position: relative;
            overflow: hidden;
        }

        .cl-about::before {
            content: '';
            position: absolute;
            top: -20%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(58, 157, 138, 0.07) 0%, transparent 65%);
            border-radius: 50%;
            pointer-events: none;
        }

        .cl-about::after {
            content: '';
            position: absolute;
            bottom: -15%;
            left: -8%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(42, 77, 105, 0.06) 0%, transparent 65%);
            border-radius: 50%;
            pointer-events: none;
        }

        .cl-about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5rem;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .cl-about-image-wrap {
            position: relative;
        }

        .cl-about-img-container {
            border-radius: var(--r-xl);
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            aspect-ratio: 4/5;
            position: relative;
        }

        .cl-about-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform var(--t-slow);
        }

        .cl-about-image-wrap:hover .cl-about-img-container img {
            transform: scale(1.04);
        }

        .cl-about-float-card {
            position: absolute;
            bottom: -1.5rem;
            right: -2rem;
            background: var(--cl-white);
            border: 1px solid rgba(58, 157, 138, 0.15);
            padding: 1.75rem;
            border-radius: var(--r-lg);
            box-shadow: var(--shadow-md);
            max-width: 230px;
            animation: float 4.5s ease-in-out infinite;
        }

        .cl-about-float-card i {
            font-size: 2rem;
            color: var(--cl-green);
            display: block;
            margin-bottom: 0.75rem;
        }

        .cl-about-float-card h4 {
            font-family: var(--font-display);
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--cl-text);
            margin-bottom: 0.4rem;
        }

        .cl-about-float-card p {
            font-size: 0.82rem;
            color: var(--cl-text-muted);
            line-height: 1.6;
        }

        .cl-about-content {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .cl-about-text {
            font-size: 1.05rem;
            line-height: 1.85;
            color: var(--cl-text-muted);
        }

        .cl-about-features {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .cl-feature-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 1rem;
        }

        .cl-feature-icon {
            width: 56px;
            height: 56px;
            background: var(--cl-green-pale);
            border: 1px solid rgba(58, 157, 138, 0.2);
            border-radius: var(--r-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--cl-green);
            font-size: 1.5rem;
            transition: all var(--t);
            flex-shrink: 0;
        }

        .cl-feature-item:hover .cl-feature-icon {
            background: var(--cl-green);
            color: white;
            transform: translateY(-6px) rotate(8deg);
            box-shadow: var(--shadow-green);
        }

        .cl-feature-text h5 {
            font-family: var(--font-display);
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--cl-text);
            margin-bottom: 0.25rem;
        }

        .cl-feature-text p {
            font-size: 0.82rem;
            color: var(--cl-text-muted);
            line-height: 1.55;
        }

        /* ========== DECORATIVE ELEMENTS ========== */
        .cl-bg-orb {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            filter: blur(80px);
            opacity: 0.45;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 1280px) {
            .cl-productos-grid { grid-template-columns: repeat(3, 1fr); }
        }

        @media (max-width: 1024px) {
            .cl-stats-grid { grid-template-columns: repeat(2, 1fr); }
            .cl-stat-item { border-right: none; border-bottom: 1px solid rgba(255,255,255,0.1); }
            .cl-stat-item:nth-child(odd) { border-right: 1px solid rgba(255,255,255,0.1); }
            .cl-stat-item:last-child, .cl-stat-item:nth-last-child(2):nth-child(odd) { border-bottom: none; }
            .cl-about-grid { grid-template-columns: 1fr; gap: 3.5rem; }
            .cl-about-image-wrap { max-width: 480px; margin: 0 auto; }
            .cl-about-content { text-align: center; align-items: center; }
        }

        @media (max-width: 768px) {
            .cl-hero-swiper { height: 480px; }
            .cl-productos-grid { grid-template-columns: repeat(2, 1fr); }
            .cl-hero-scroll { display: none; }
            .cl-promo-content { padding: 2.5rem 1.75rem; }
            .cl-rec-content { padding: 2rem 1.75rem; }
            .cl-about-features { grid-template-columns: repeat(3, 1fr); }
            .cl-colecciones, .cl-productos, .cl-recommendations, .cl-about { padding: 4rem 0; }
        }

        @media (max-width: 576px) {
            .cl-hero-swiper { height: 420px; }
            .cl-hero-content { padding: 3.5rem 0; }
            .cl-hero-buttons { flex-direction: column; }
            .cl-hero-buttons .cl-btn { justify-content: center; }
            .cl-productos-grid { grid-template-columns: 1fr; gap: 1rem; }
            .cl-about-float-card { position: relative; bottom: auto; right: auto; max-width: 100%; margin: -2rem 1rem 0; animation: none; }
            .cl-about-features { grid-template-columns: 1fr; gap: 1.5rem; }
            .cl-rec-swiper { height: 340px; }
            .cl-stats-grid { grid-template-columns: repeat(2, 1fr); }
            .cl-promo-card { border-radius: var(--r-lg); }
            .cl-promo-content { padding: 2rem 1.5rem; min-height: 340px; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
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
    <section class="cl-hero">
        <div class="swiper cl-hero-swiper">
            <div class="swiper-wrapper">
                <?php if (empty($banners_hero)): ?>
                    <div class="swiper-slide cl-hero-slide">
                        <div class="cl-hero-bg" style="background-image: url('<?= e(asset('img/banner1.png')) ?>');"></div>
                        <div class="cl-hero-overlay"></div>
                        <div class="container">
                            <div class="cl-hero-content">
                                <div class="cl-hero-badge">
                                    <i class="bi bi-stars"></i> Nueva Colección 2025
                                </div>
                                <h1 class="cl-hero-title">
                                    <?= e($ajustes['hero_titulo'] ?? 'Premium Home') ?><br>
                                    <span>&amp; Style</span>
                                </h1>
                                <p class="cl-hero-subtitle">
                                    <?= e($ajustes['hero_subtitulo'] ?? 'Elegancia y funcionalidad para cada rincón de tu hogar contemporáneo.') ?>
                                </p>
                                <div class="cl-hero-buttons">
                                    <a href="<?= BASE_URL ?>productos" class="cl-btn cl-btn-orange">
                                        Ver Catálogo <i class="bi bi-arrow-right"></i>
                                    </a>
                                    <a href="#colecciones" class="cl-btn cl-btn-ghost">
                                        <i class="bi bi-grid-3x3-gap"></i> Colecciones
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($banners_hero as $b): ?>
                        <div class="swiper-slide cl-hero-slide">
                            <div class="cl-hero-bg" style="background-image: url('<?= e(asset($b['imagen'])) ?>');"></div>
                            <div class="cl-hero-overlay"></div>
                            <div class="container">
                                <div class="cl-hero-content">
                                    <div class="cl-hero-badge">
                                        <i class="bi bi-stars"></i> Destacado
                                    </div>
                                    <h1 class="cl-hero-title">
                                        <?= e($b['titulo']) ?>
                                        <?php if (!empty($b['subtitulo'])): ?>
                                            <br><span><?= e($b['subtitulo']) ?></span>
                                        <?php endif; ?>
                                    </h1>
                                    <?php if (!empty($b['texto_boton'])): ?>
                                        <div class="cl-hero-buttons">
                                            <a href="<?= $b['enlace'] ? (strpos($b['enlace'], 'http') === 0 ? e($b['enlace']) : BASE_URL . e($b['enlace'])) : '#productos' ?>"
                                                class="cl-btn cl-btn-orange">
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

        <!-- Scroll indicator -->
        <div class="cl-hero-scroll">
            <div class="cl-hero-scroll-line"></div>
            <span>Scroll</span>
        </div>
    </section>

    <!-- ========== STATS BAR ========== -->
    <section class="cl-stats">
        <div class="container">
            <div class="cl-stats-grid">
                <div class="cl-stat-item" data-reveal="up" data-delay="1">
                    <div class="cl-stat-number" data-count="+100">+100</div>
                    <div class="cl-stat-label">Clientes satisfechos</div>
                </div>
                <div class="cl-stat-item" data-reveal="up" data-delay="2">
                    <div class="cl-stat-number">24<span class="accent">/</span>7</div>
                    <div class="cl-stat-label">Soporte disponible</div>
                </div>
                <div class="cl-stat-item" data-reveal="up" data-delay="3">
                    <div class="cl-stat-number">99<span class="accent">.</span>9<span class="accent">%</span></div>
                    <div class="cl-stat-label">Uptime garantizado</div>
                </div>
                <div class="cl-stat-item" data-reveal="up" data-delay="4">
                    <div class="cl-stat-number" data-count="+150">+150</div>
                    <div class="cl-stat-label">Integraciones</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== COLECCIONES ========== -->
    <section class="cl-colecciones" id="colecciones">
        <div class="container">
            <div class="cl-section-header" data-reveal="up">
                <div class="cl-section-badge">
                    <i class="bi bi-collection"></i> Explora
                </div>
                <h2 class="cl-section-title">
                    <?= !empty($colecciones) ? 'Nuestras Colecciones' : 'Nuestras Categorías' ?>
                </h2>
                <p class="cl-section-subtitle">
                    <?= !empty($colecciones) ? 'Diseños pensados para cada espacio de tu vida' : 'Explora nuestros productos por categoría' ?>
                </p>
            </div>

            <div class="swiper cl-collections-swiper">
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
                        if (in_array($item_id, $processed_ids)) continue;
                        $processed_ids[] = $item_id;
                        $is_col   = $item['tipo_item'] === 'coleccion';
                        $item_slug = e($item['slug']);
                        $item_nombre = e($item['nombre']);
                        $item_link = BASE_URL . 'productos/' . ($is_col ? 'coleccion' : 'categoria') . '/' . $item_slug;
                        ?>
                        <div class="swiper-slide">
                            <a href="<?= $item_link ?>" class="cl-collection-card">
                                <div class="cl-collection-img">
                                    <?php if (!empty($item['imagen'])): ?>
                                        <img src="<?= e(asset($item['imagen'])) ?>" alt="<?= $item_nombre ?>" loading="lazy">
                                    <?php else: ?>
                                        <div class="cl-collection-placeholder">
                                            <i class="bi <?= e($item['icono'] ?? 'bi-grid-3x3-gap') ?>"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="cl-collection-body">
                                    <h3 class="cl-collection-name"><?= e($item['nombre']) ?></h3>
                                    <span class="cl-collection-link">
                                        Ver productos <i class="bi bi-arrow-right"></i>
                                    </span>
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
        <section class="cl-promo">
            <div class="container">
                <div class="cl-promo-card" data-reveal="scale">
                    <?php if (!empty($ajustes['promo_imagen'])): ?>
                        <div class="cl-promo-bg">
                            <img src="<?= e(asset($ajustes['promo_imagen'])) ?>" alt="<?= e($ajustes['promo_titulo']) ?>">
                        </div>
                    <?php else: ?>
                        <div class="cl-promo-bg" style="background: linear-gradient(135deg, var(--cl-blue) 0%, var(--cl-green) 100%);"></div>
                    <?php endif; ?>
                    <div class="cl-promo-overlay"></div>
                    <div class="cl-promo-content">
                        <div class="cl-promo-tag">
                            <i class="bi bi-megaphone-fill"></i>
                            <?= e($ajustes['promo_etiqueta'] ?? 'Especial') ?>
                        </div>
                        <h2 class="cl-promo-title"><?= e($ajustes['promo_titulo']) ?></h2>
                        <p class="cl-promo-text">
                            <?= e($ajustes['promo_texto'] ?? 'Descubre nuestra selección premium con beneficios únicos.') ?>
                        </p>
                        <?php if (!empty($ajustes['promo_link'])): ?>
                            <a href="<?= e($ajustes['promo_link']) ?>" class="cl-btn cl-btn-orange">
                                <?= e($ajustes['promo_boton'] ?? 'Ver Promoción') ?>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- ========== PRODUCTOS DESTACADOS ========== -->
    <section class="cl-productos" id="productos">
        <div class="container">
            <div class="cl-section-header" data-reveal="up">
                <div class="cl-section-badge">
                    <i class="bi bi-star-fill"></i> Lo Mejor
                </div>
                <h2 class="cl-section-title">Productos Destacados</h2>
                <p class="cl-section-subtitle">Nuestros productos mejor valorados con diseño y calidad premium</p>
            </div>

            <?php if (empty($productos_destacados)): ?>
                <div class="cl-productos-grid">
                    <div class="cl-empty-state">
                        <i class="bi bi-box-seam"></i>
                        <p>No hay productos destacados por el momento</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="cl-productos-grid">
                    <?php foreach (array_slice($productos_destacados, 0, 8) as $index => $producto): ?>
                        <div class="cl-producto-card" data-reveal="up" data-delay="<?= min($index + 1, 8) ?>">
                            <?php if (!empty($producto['nuevo'])): ?>
                                <div class="cl-producto-badge">Nuevo</div>
                            <?php endif; ?>

                            <div class="cl-producto-actions">
                                <button class="cl-producto-action-btn" title="Favoritos">
                                    <i class="bi bi-heart"></i>
                                </button>
                                <button class="cl-producto-action-btn" title="Vista rápida">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>

                            <div class="cl-producto-img">
                                <?php if ($producto['imagen_principal']): ?>
                                    <img src="<?= e(asset($producto['imagen_principal'])) ?>"
                                         alt="<?= e($producto['nombre']) ?>" loading="lazy">
                                <?php else: ?>
                                    <div class="cl-producto-img-placeholder">
                                        <i class="bi bi-image"></i>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="cl-producto-body">
                                <div class="cl-producto-categoria">
                                    <?= e($producto['categoria_nombre'] ?? 'Producto') ?>
                                </div>
                                <h3 class="cl-producto-nombre">
                                    <a href="<?= BASE_URL ?>productos/detalle/<?= e($producto['slug']) ?>">
                                        <?= e($producto['nombre']) ?>
                                    </a>
                                </h3>
                                <div class="cl-producto-footer">
                                    <span class="cl-producto-precio">
                                        <?= !empty($producto['precio_referencia']) ? '$' . number_format($producto['precio_referencia'], 2) : '' ?>
                                    </span>
                                    <a href="<?= BASE_URL ?>productos/detalle/<?= e($producto['slug']) ?>"
                                       class="cl-producto-btn">
                                        Ver Más
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
    <section class="cl-recommendations">
        <div class="container">
            <div class="cl-section-header" data-reveal="up">
                <div class="cl-section-badge">
                    <i class="bi bi-hand-thumbs-up"></i> Para Ti
                </div>
                <h2 class="cl-section-title">Recomendado para Ti</h2>
                <p class="cl-section-subtitle">Selecciones especiales basadas en lo que más te gusta</p>
            </div>

            <div class="swiper cl-rec-swiper" data-reveal="scale">
                <div class="swiper-wrapper">
                    <?php
                    $recom = !empty($banners_recom) ? $banners_recom : $productos_destacados;
                    foreach (array_slice($recom, 0, 3) as $r):
                        ?>
                        <div class="swiper-slide cl-rec-slide">
                            <div class="cl-rec-bg"
                                 style="background-image: url('<?= e(asset($r['imagen'] ?? $r['imagen_principal'])) ?>');"></div>
                            <div class="cl-rec-overlay"></div>
                            <div class="cl-rec-content">
                                <h3 class="cl-rec-title"><?= e($r['titulo'] ?? $r['nombre']) ?></h3>
                                <p class="cl-rec-text">
                                    <?= e($r['subtitulo'] ?? $r['descripcion_corta'] ?? 'Diseño funcional para tu hogar contemporáneo.') ?>
                                </p>
                                <a href="<?= BASE_URL ?>productos" class="cl-btn cl-btn-orange">
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
    <section class="cl-about" id="about">
        <div class="container">
            <div class="cl-about-grid">
                <!-- Image column -->
                <div class="cl-about-image-wrap" data-reveal="left">
                    <div class="cl-about-img-container">
                        <?php
                        $aboutImg = !empty($ajustes['about_imagen'])
                            ? asset($ajustes['about_imagen'])
                            : 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=1200';
                        ?>
                        <img src="<?= $aboutImg ?>" alt="Cerelit">
                    </div>
                    <div class="cl-about-float-card">
                        <i class="bi bi-patch-check-fill"></i>
                        <h4>Calidad Premium</h4>
                        <p>Cada pieza seleccionada bajo los más altos estándares de durabilidad y estilo.</p>
                    </div>
                </div>

                <!-- Content column -->
                <div class="cl-about-content" data-reveal="right">
                    <div class="cl-section-header" style="text-align:left; margin-bottom:1rem;">
                        <div class="cl-section-badge">
                            <i class="bi bi-info-circle"></i> <?= e($ajustes['about_badge'] ?? 'Nuestra Esencia') ?>
                        </div>
                        <h2 class="cl-section-title" style="text-align:left;">
                            <?= e($ajustes['about_titulo'] ?? 'Diseñamos espacios con alma propia') ?>
                        </h2>
                    </div>

                    <div class="cl-about-text">
                        <?php if (!empty($ajustes['about_descripcion'])): ?>
                            <?= nl2br(e($ajustes['about_descripcion'])) ?>
                        <?php else: ?>
                            <p>En <strong style="color:var(--cl-green);">Cerelit</strong>, creemos que tu hogar es más que un lugar; es el reflejo de tu identidad. Nos apasiona curar colecciones que combinan la funcionalidad moderna con una estética atemporal.</p>
                            <p style="margin-top:1rem;">Nuestra misión es transformar cada rincón en una experiencia de confort y elegancia, ofreciendo productos de vanguardia que cuentan historias y crean recuerdos duraderos.</p>
                        <?php endif; ?>
                    </div>

                    <div class="cl-about-features">
                        <div class="cl-feature-item" data-reveal="up" data-delay="2">
                            <div class="cl-feature-icon"><i class="bi bi-lightbulb"></i></div>
                            <div class="cl-feature-text">
                                <h5>Innovación.</h5>
                                <p>Parte esencial de lo que hacemos cada día.</p>
                            </div>
                        </div>
                        <div class="cl-feature-item" data-reveal="up" data-delay="3">
                            <div class="cl-feature-icon"><i class="bi bi-patch-check"></i></div>
                            <div class="cl-feature-text">
                                <h5>Excelencia.</h5>
                                <p>El buen diseño accesible para todos.</p>
                            </div>
                        </div>
                        <div class="cl-feature-item" data-reveal="up" data-delay="4">
                            <div class="cl-feature-icon"><i class="bi bi-rocket-takeoff"></i></div>
                            <div class="cl-feature-text">
                                <h5>Visión.</h5>
                                <p>Cuestionamos la cotidianidad y la mejoramos.</p>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top:2rem;">
                        <a href="<?= BASE_URL ?>productos" class="cl-btn cl-btn-primary">
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

        /* ========== SWIPERS ========== */
        new Swiper('.cl-hero-swiper', {
            loop: true,
            autoplay: { delay: 5500, disableOnInteraction: false },
            effect: 'fade',
            fadeEffect: { crossFade: true },
            speed: 700,
            pagination: { el: '.cl-hero-swiper .swiper-pagination', clickable: true }
        });

        new Swiper('.cl-collections-swiper', {
            slidesPerView: 1.2,
            spaceBetween: 16,
            speed: 450,
            centeredSlides: false,
            pagination: { el: '.cl-collections-swiper .swiper-pagination', clickable: true },
            breakpoints: {
                480:  { slidesPerView: 2,   spaceBetween: 18 },
                768:  { slidesPerView: 3,   spaceBetween: 20 },
                1024: { slidesPerView: 4,   spaceBetween: 24 }
            }
        });

        new Swiper('.cl-rec-swiper', {
            effect: 'fade',
            fadeEffect: { crossFade: true },
            loop: true,
            speed: 700,
            autoplay: { delay: 4500, disableOnInteraction: false },
            pagination: { el: '.cl-rec-swiper .swiper-pagination', clickable: true }
        });

        /* ========== SCROLL REVEAL ========== */
        const revealEls = document.querySelectorAll('[data-reveal]');

        const revealObs = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    requestAnimationFrame(() => entry.target.classList.add('revealed'));
                    revealObs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' });

        revealEls.forEach(el => revealObs.observe(el));

        /* ========== PARALLAX HERO ========== */
        const heroSection = document.querySelector('.cl-hero');
        const heroObserver = new IntersectionObserver(([entry]) => {
            if (!entry.isIntersecting) return;
            window.addEventListener('scroll', onHeroScroll, { passive: true });
        }, { threshold: 0 });

        if (heroSection) heroObserver.observe(heroSection);

        function onHeroScroll() {
            const scrolled = window.scrollY;
            const heroBgs  = document.querySelectorAll('.cl-hero-bg');
            heroBgs.forEach(bg => {
                bg.style.transform = `translateY(${scrolled * 0.28}px)`;
            });
            if (scrolled > heroSection.offsetHeight) {
                window.removeEventListener('scroll', onHeroScroll);
            }
        }

        /* ========== COUNT-UP ANIMATION ========== */
        const countEls = document.querySelectorAll('[data-count]');
        const countObs = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                const el     = entry.target;
                const target = parseInt(el.dataset.count);
                const prefix = el.dataset.count.startsWith('+') ? '+' : '';
                let start = 0;
                const duration = 1600;
                const startTime = performance.now();

                function update(now) {
                    const elapsed  = now - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    // Ease out quad
                    const eased = 1 - Math.pow(1 - progress, 3);
                    el.textContent = prefix + Math.floor(eased * target);
                    if (progress < 1) requestAnimationFrame(update);
                }

                requestAnimationFrame(update);
                countObs.unobserve(el);
            });
        }, { threshold: 0.5 });

        countEls.forEach(el => countObs.observe(el));

        /* ========== SMOOTH LAZY IMAGES ========== */
        document.querySelectorAll('img[loading="lazy"]').forEach(img => {
            if (img.complete) return;
            img.style.opacity = '0';
            img.style.transition = 'opacity 0.4s ease-out';
            img.addEventListener('load', () => {
                requestAnimationFrame(() => { img.style.opacity = '1'; });
            }, { once: true });
        });

        /* ========== TOUCH RIPPLE (Mobile) ========== */
        if ('ontouchstart' in window) {
            document.querySelectorAll('.cl-btn, .cl-producto-card, .cl-collection-card').forEach(el => {
                el.addEventListener('touchstart', () => {
                    el.style.transform = el.style.transform.includes('translateY')
                        ? el.style.transform + ' scale(0.97)'
                        : 'scale(0.97)';
                }, { passive: true });
                el.addEventListener('touchend', () => {
                    el.style.transform = '';
                }, { passive: true });
            });
        }

        /* ========== STAGGER PRODUCT CARDS ========== */
        // Assign stagger delays to product cards within visible viewport
        const productCards = document.querySelectorAll('.cl-producto-card');
        productCards.forEach((card, i) => {
            card.style.transitionDelay = (i % 4) * 0.08 + 's';
        });

    });
    </script>

</body>
</html>
