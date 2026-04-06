<?php
/**
 * detalle.php - Vista de detalle de producto
 * Estilo Ekuora Ultra Glass - Pantone Edition
 * VERSIÓN RESPONSIVA MEJORADA CON LIGHTBOX AVANZADO
 */
?>
<!DOCTYPE html>
<html lang="es" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>
        <?= e($producto['nombre']) ?> |
        <?= APP_NAME ?? 'Ekuora' ?>
    </title>

    <link rel="icon" type="image/png" href="<?= e(asset('favicon.png')) ?>">
    <link rel="shortcut icon" href="<?= e(asset('favicon.png')) ?>" type="image/png">
    <link rel="apple-touch-icon" href="<?= e(asset('favicon.png')) ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        /* ============================================
           EKUORA ULTRA GLASS - DETALLE PRODUCTO
           Pantone: 296C, 144C, 5425C, 7546C
           VERSIÓN RESPONSIVA MEJORADA CON LIGHTBOX AVANZADO
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

            --glass-bg: rgba(255, 255, 255, 1); /* Solid white */
            --glass-border: #002B49; /* Navy Blue border */
            --glass-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            --glass-blur: none;

            --radius-sm: 12px;
            --radius-md: 16px;
            --radius-lg: 24px;
            --radius-xl: 32px;
            --radius-full: 9999px;

            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        [data-theme="dark"] {
            --ek-sky-pale: #1a2530;
            --glass-bg: rgba(0, 43, 73, 0.9);
            --glass-border: rgba(122, 153, 172, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            overflow-x: hidden;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, var(--ek-sky-pale) 50%, #f1f5f9 100%);
            color: var(--ek-slate);
            min-height: 100vh;
            overflow-x: hidden;
            width: 100%;
            max-width: 100vw;
            display: flex;
            flex-direction: column;
        }

        [data-theme="dark"] body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            color: var(--ek-sky-light);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1.5rem;
            width: 100%;
        }

        @media (max-width: 576px) {
            .container {
                padding: 0 1rem;
            }
        }

        /* ============================================
           DETALLE SECTION
        ============================================ */
        .ek-detalle {
            padding: 2rem 0 5rem;
            flex: 1;
        }

        /* Breadcrumb */
        .ek-breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            font-size: 0.85rem;
        }

        .ek-breadcrumb a {
            color: var(--ek-sky);
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            transition: var(--transition);
            white-space: nowrap;
        }

        .ek-breadcrumb a:hover {
            color: var(--ek-orange);
        }

        .ek-breadcrumb-sep {
            color: var(--ek-sky);
            opacity: 0.5;
        }

        .ek-breadcrumb-current {
            color: var(--ek-navy);
            font-weight: 600;
            word-break: break-word;
        }

        [data-theme="dark"] .ek-breadcrumb-current {
            color: white;
        }

        @media (max-width: 768px) {
            .ek-breadcrumb {
                margin-bottom: 1.5rem;
                padding-top: 0.5rem;
                font-size: 0.8rem;
            }

            .ek-breadcrumb-current {
                display: none;
            }

            .ek-breadcrumb-sep:last-of-type {
                display: none;
            }
        }

        /* Grid Principal */
        .ek-detalle-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: start;
        }

        @media (max-width: 992px) {
            .ek-detalle-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
        }

        /* ============================================
           GALERÍA
        ============================================ */
        .ek-galeria {
            position: relative;
            width: 100%;
        }

        .ek-galeria-main {
            position: relative;
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius-xl);
            overflow: hidden;
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            width: 100%;
        }

        .ek-galeria-main img#imagenPrincipal {
            max-width: 85%;
            max-height: 85%;
            width: auto;
            height: auto;
            object-fit: contain;
            transition: transform 0.5s ease, opacity 0.3s ease;
        }

        .ek-galeria-main:hover img#imagenPrincipal {
            transform: scale(1.05);
        }

        @media (max-width: 576px) {
            .ek-galeria-main {
                border-radius: var(--radius-lg);
                aspect-ratio: 1;
            }

            .ek-galeria-main img#imagenPrincipal {
                max-width: 90%;
                max-height: 90%;
            }
        }

        .ek-galeria-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            padding: 0.4rem 1rem;
            background: var(--ek-orange);
            color: white;
            font-size: 0.75rem;
            font-weight: 700;
            border-radius: var(--radius-full);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            z-index: 2;
        }

        @media (max-width: 576px) {
            .ek-galeria-badge {
                top: 0.75rem;
                left: 0.75rem;
                padding: 0.35rem 0.8rem;
                font-size: 0.7rem;
            }
        }

        .ek-galeria-logo {
            position: absolute;
            bottom: 1rem;
            left: 1rem;
            height: clamp(35px, 6vw, 70px);
            z-index: 5;
            opacity: 0.6;
            pointer-events: none;
        }

        .ek-galeria-zoom {
            position: absolute;
            bottom: 1rem;
            right: 1rem;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border: none;
            border-radius: var(--radius-full);
            color: var(--ek-navy);
            font-size: 1.1rem;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            z-index: 2;
        }

        .ek-galeria-zoom:hover {
            background: var(--ek-orange);
            color: white;
            transform: scale(1.1);
        }

        @media (max-width: 576px) {
            .ek-galeria-zoom {
                width: 40px;
                height: 40px;
                bottom: 0.75rem;
                right: 0.75rem;
                font-size: 1rem;
            }

            .ek-galeria-logo {
                height: 30px;
                bottom: 0.75rem;
                left: 0.75rem;
            }
        }

        .ek-galeria-thumbs {
            display: flex;
            gap: 0.75rem;
            overflow-x: auto;
            padding-bottom: 0.5rem;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: var(--ek-sky) transparent;
        }

        .ek-galeria-thumbs::-webkit-scrollbar {
            height: 4px;
        }

        .ek-galeria-thumbs::-webkit-scrollbar-track {
            background: transparent;
        }

        .ek-galeria-thumbs::-webkit-scrollbar-thumb {
            background: var(--ek-sky);
            border-radius: 4px;
        }

        .ek-galeria-thumb {
            flex-shrink: 0;
            width: 70px;
            height: 70px;
            background: var(--glass-bg);
            border: 2px solid var(--glass-border);
            border-radius: var(--radius-md);
            overflow: hidden;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .ek-galeria-thumb:hover {
            border-color: var(--ek-sky);
        }

        .ek-galeria-thumb.active {
            border-color: var(--ek-orange);
            box-shadow: 0 0 0 3px rgba(237, 139, 0, 0.2);
        }

        .ek-galeria-thumb img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: white;
        }

        @media (max-width: 576px) {
            .ek-galeria-thumbs {
                gap: 0.5rem;
            }

            .ek-galeria-thumb {
                width: 60px;
                height: 60px;
                border-radius: var(--radius-sm);
            }
        }

        /* ============================================
           INFO PRODUCTO
        ============================================ */
        .ek-producto-info {
            padding-top: 0;
            width: 100%;
        }

        @media (min-width: 993px) {
            .ek-producto-info {
                padding-top: 1rem;
            }
        }

        .ek-producto-categoria {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: var(--ek-navy);
            color: white;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: var(--radius-full);
            margin-bottom: 1rem;
            text-decoration: none;
            transition: var(--transition);
        }

        .ek-producto-categoria:hover {
            background: var(--ek-orange);
            color: white;
        }

        @media (max-width: 576px) {
            .ek-producto-categoria {
                padding: 0.4rem 0.9rem;
                font-size: 0.7rem;
            }
        }

        .ek-producto-titulo {
            font-family: 'Montserrat', sans-serif;
            font-size: clamp(1.5rem, 5vw, 2.5rem);
            font-weight: 400;
            color: var(--ek-navy);
            line-height: 1.2;
            margin-bottom: 0.75rem;
            word-break: break-word;
        }

        [data-theme="dark"] .ek-producto-titulo {
            color: white;
        }

        .ek-producto-sku {
            display: inline-block;
            font-size: 0.75rem;
            color: var(--ek-sky);
            margin-bottom: 0;
            background: rgba(0, 0, 0, 0.05);
            padding: 0.2rem 0.6rem;
            border-radius: var(--radius-sm);
        }

        .ek-producto-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.25rem;
            flex-wrap: wrap;
        }

        .ek-meta-item {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--ek-slate);
        }

        [data-theme="dark"] .ek-meta-item {
            color: var(--ek-sky-light);
        }

        .ek-meta-item i {
            color: var(--ek-orange);
        }

        .ek-producto-precio {
            display: flex;
            align-items: baseline;
            gap: 1rem;
            margin-bottom: 1.25rem;
            flex-wrap: wrap;
        }

        .ek-precio-actual {
            font-family: 'Montserrat', sans-serif;
            font-size: clamp(1.5rem, 5vw, 2.5rem);
            font-weight: 400;
            color: var(--ek-orange);
        }

        .ek-precio-consultar {
            font-size: clamp(1.1rem, 3vw, 1.5rem);
            color: var(--ek-navy);
            font-weight: 700;
        }

        [data-theme="dark"] .ek-precio-consultar {
            color: var(--ek-sky-light);
        }

        .ek-precio-anterior {
            font-size: 1.1rem;
            color: var(--ek-sky);
            text-decoration: line-through;
        }

        .ek-producto-desc {
            font-size: 0.95rem;
            color: var(--ek-slate);
            line-height: 1.7;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--glass-border);
            word-break: break-word;
        }
        
        /* Reduce gap in info container */
        .ek-producto-info {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        [data-theme="dark"] .ek-producto-desc {
            color: var(--ek-sky-light);
        }

        @media (max-width: 576px) {
            .ek-producto-desc {
                font-size: 0.9rem;
                line-height: 1.6;
            }
        }

        /* Cantidad y CTA */
        .ek-cantidad-cta {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .ek-cantidad {
            display: flex;
            align-items: center;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius-full);
            overflow: hidden;
        }

        .ek-cantidad-btn {
            width: 46px;
            height: 46px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: none;
            color: var(--ek-navy);
            font-size: 1.2rem;
            cursor: pointer;
            transition: var(--transition);
        }

        [data-theme="dark"] .ek-cantidad-btn {
            color: white;
        }

        .ek-cantidad-btn:hover {
            background: var(--ek-sky-pale);
            color: var(--ek-orange);
        }

        .ek-cantidad-input {
            width: 50px;
            height: 46px;
            border: none;
            background: transparent;
            text-align: center;
            font-size: 1rem;
            font-weight: 600;
            color: var(--ek-navy);
        }

        [data-theme="dark"] .ek-cantidad-input {
            color: white;
        }

        .ek-btn-whatsapp {
            flex: 1;
            min-width: 200px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 0.9rem 1.5rem;
            background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
            color: white;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 700;
            border-radius: var(--radius-full);
            transition: var(--transition);
            box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
        }

        .ek-btn-whatsapp:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(37, 211, 102, 0.5);
            color: white;
        }

        .ek-btn-whatsapp i {
            font-size: 1.3rem;
        }

        @media (max-width: 576px) {
            .ek-cantidad-cta {
                flex-direction: column;
            }

            .ek-cantidad {
                justify-content: center;
                width: 100%;
            }

            .ek-btn-whatsapp {
                width: 100%;
                min-width: unset;
            }
        }

        /* Features */
        .ek-features {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .ek-features {
                grid-template-columns: 1fr;
                gap: 0.6rem;
            }
        }

        .ek-feature {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.8rem;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius-md);
        }

        .ek-feature-icon {
            width: 40px;
            height: 40px;
            min-width: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--ek-sky-pale);
            border-radius: var(--radius-sm);
            color: var(--ek-orange);
            font-size: 1.1rem;
        }

        .ek-feature-text {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--ek-navy);
        }

        [data-theme="dark"] .ek-feature-text {
            color: white;
        }

        /* ============================================
           ACCORDION
        ============================================ */
        .ek-accordion {
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        .ek-accordion-item {
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius-lg);
            overflow: hidden;
            transition: var(--transition);
        }

        .ek-accordion-item.active {
            border-color: var(--ek-orange);
            box-shadow: 0 4px 20px rgba(237, 139, 0, 0.1);
        }

        .ek-accordion-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            cursor: pointer;
            transition: var(--transition);
            gap: 0.75rem;
        }

        .ek-accordion-header:hover {
            background: var(--ek-sky-pale);
        }

        .ek-accordion-title {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--ek-navy);
            flex: 1;
            min-width: 0;
        }

        [data-theme="dark"] .ek-accordion-title {
            color: white;
        }

        .ek-accordion-title i:first-child {
            width: 32px;
            height: 32px;
            min-width: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--ek-sky-pale);
            border-radius: var(--radius-sm);
            color: var(--ek-orange);
            font-size: 0.9rem;
        }

        .ek-accordion-icon {
            width: 28px;
            height: 28px;
            min-width: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--ek-sky-pale);
            border-radius: var(--radius-full);
            color: var(--ek-slate);
            transition: transform 0.3s ease, background 0.3s ease, color 0.3s ease;
            font-size: 0.8rem;
        }

        .ek-accordion-item.active .ek-accordion-icon {
            transform: rotate(180deg);
            background: var(--ek-orange);
            color: white;
        }

        .ek-accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .ek-accordion-item.active .ek-accordion-content {
            max-height: 500px;
        }

        .ek-accordion-body {
            padding: 0 1.25rem 1.25rem;
            font-size: 0.9rem;
            color: var(--ek-slate);
            line-height: 1.6;
        }

        [data-theme="dark"] .ek-accordion-body {
            color: var(--ek-sky-light);
        }

        @media (max-width: 576px) {
            .ek-accordion-header {
                padding: 0.9rem 1rem;
            }

            .ek-accordion-title {
                font-size: 0.85rem;
            }

            .ek-accordion-title i:first-child {
                width: 28px;
                height: 28px;
                min-width: 28px;
                font-size: 0.85rem;
            }

            .ek-accordion-body {
                padding: 0 1rem 1rem;
                font-size: 0.85rem;
            }
        }

        .ek-spec-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .ek-spec-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.6rem 0;
            border-bottom: 1px dashed var(--glass-border);
            gap: 1rem;
            flex-wrap: wrap;
        }

        .ek-spec-item:last-child {
            border-bottom: none;
        }

        .ek-spec-label {
            color: var(--ek-sky);
            font-weight: 500;
            font-size: 0.85rem;
        }

        .ek-spec-value {
            font-weight: 600;
            color: var(--ek-navy);
            font-size: 0.85rem;
            text-align: right;
        }

        [data-theme="dark"] .ek-spec-value {
            color: white;
        }

        .ek-disponible {
            color: #22c55e;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        /* ============================================
           LIGHTBOX MEJORADO - TAMAÑO AJUSTADO
        ============================================ */
        .ek-lightbox {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.98);
            background: rgba(255, 255, 255, 0.45);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .ek-lightbox.active {
            display: flex;
            opacity: 1;
        }

        .ek-lightbox-wrapper {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* Área de imagen principal */
        .ek-lightbox-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 80px 20px 160px;
            position: relative;
            overflow: hidden;
        }

        @media (max-width: 768px) {
            .ek-lightbox-content {
                padding: 60px 15px 180px;
            }
        }

        .ek-lightbox-image-container {
            position: relative;
            width: 100%;
            height: 100%;
            max-width: 800px;
            max-height: calc(100vh - 280px);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: grab;
            touch-action: none;
        }

        @media (max-width: 768px) {
            .ek-lightbox-image-container {
                max-width: 90vw;
                max-height: calc(100vh - 280px);
            }
        }

        .ek-lightbox-image-container.zoomed {
            cursor: move;
        }

        .ek-lightbox-image-container.grabbing {
            cursor: grabbing;
        }

        .ek-lightbox-img {
            width: 100%;
            height: 100%;
            max-width: 100%;
            max-height: 100%;
            border-radius: var(--radius-lg);
            box-shadow: 0 10px 60px rgba(0, 43, 73, 0.15);
            object-fit: contain;
            transition: transform 0.3s ease;
            user-select: none;
            -webkit-user-drag: none;
        }

        /* Logo en lightbox */
        .ek-lightbox-logo {
            position: absolute;
            bottom: 1.5rem;
            left: 1.5rem;
            height: clamp(50px, 8vw, 80px);
            z-index: 10;
            opacity: 0.4;
            pointer-events: none;
        }

        @media (max-width: 768px) {
            .ek-lightbox-logo {
                height: 40px;
                bottom: 1rem;
                left: 1rem;
            }
        }

        /* Botón cerrar */
        .ek-lightbox-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 48px;
            height: 48px;
            background: white;
            background: rgba(255, 255, 255, 0.85);
            border: 2px solid var(--glass-border);
            border-radius: 50%;
            color: var(--ek-navy);
            font-size: 24px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            z-index: 10001;
            box-shadow: 0 4px 20px rgba(0, 43, 73, 0.1);
        }

        .ek-lightbox-close:hover {
            transform: scale(1.1) rotate(90deg);
            background: var(--ek-orange);
            color: white;
            border-color: var(--ek-orange);
        }

        @media (max-width: 768px) {
            .ek-lightbox-close {
                width: 44px;
                height: 44px;
                font-size: 20px;
            }
        }

        /* Controles de zoom */
        .ek-lightbox-zoom-controls {
            position: absolute;
            top: 1rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 0.5rem;
            background: white;
            background: rgba(255, 255, 255, 0.45);
            border: 2px solid var(--glass-border);
            border-radius: var(--radius-full);
            padding: 0.5rem;
            z-index: 10001;
            box-shadow: 0 4px 20px rgba(0, 43, 73, 0.1);
        }

        .ek-zoom-btn {
            width: 40px;
            height: 40px;
            background: transparent;
            border: none;
            border-radius: 50%;
            color: var(--ek-navy);
            font-size: 1.2rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .ek-zoom-btn:hover {
            background: var(--ek-sky-pale);
            color: var(--ek-orange);
            transform: scale(1.1);
        }

        .ek-zoom-btn:active {
            transform: scale(0.95);
        }

        @media (max-width: 768px) {
            .ek-zoom-btn {
                width: 36px;
                height: 36px;
                font-size: 1rem;
            }
        }

        /* Flechas de navegación */
        .ek-lightbox-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 50px;
            height: 50px;
            background: white;
            background: rgba(255, 255, 255, 0.45);
            border: 2px solid var(--glass-border);
            border-radius: 50%;
            color: var(--ek-navy);
            font-size: 1.5rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            z-index: 10001;
            box-shadow: 0 4px 20px rgba(0, 43, 73, 0.1);
        }

        .ek-lightbox-nav:hover {
            background: var(--ek-orange);
            color: white;
            border-color: var(--ek-orange);
            transform: translateY(-50%) scale(1.1);
        }

        .ek-lightbox-prev {
            left: 1rem;
        }

        .ek-lightbox-next {
            right: 1rem;
        }

        @media (max-width: 768px) {
            .ek-lightbox-nav {
                width: 44px;
                height: 44px;
                font-size: 1.2rem;
            }

            .ek-lightbox-prev {
                left: 0.5rem;
            }

            .ek-lightbox-next {
                right: 0.5rem;
            }
        }

        /* Galería de miniaturas en lightbox */
        .ek-lightbox-thumbs-container {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            background: rgba(0, 0, 0, 0.45);
            border-top: 2px solid var(--glass-border);
            padding: 1.25rem;
            z-index: 10000;
            box-shadow: 0 -4px 20px rgba(0, 43, 73, 0.08);
        }

        .ek-lightbox-thumbs {
            display: flex;
            gap: 0.75rem;
            overflow-x: auto;
            padding: 0.5rem 0;
            justify-content: center;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: var(--ek-sky) transparent;
        }

        .ek-lightbox-thumbs::-webkit-scrollbar {
            height: 6px;
        }

        .ek-lightbox-thumbs::-webkit-scrollbar-track {
            background: transparent;
        }

        .ek-lightbox-thumbs::-webkit-scrollbar-thumb {
            background: var(--ek-sky);
            border-radius: 3px;
        }

        .ek-lightbox-thumb {
            flex-shrink: 0;
            width: 80px;
            height: 80px;
            background: var(--ek-sky-pale);
            border: 2px solid var(--glass-border);
            border-radius: var(--radius-md);
            overflow: hidden;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.25rem;
        }

        .ek-lightbox-thumb:hover {
            border-color: var(--ek-sky);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 43, 73, 0.1);
        }

        .ek-lightbox-thumb.active {
            border-color: var(--ek-orange);
            box-shadow: 0 0 0 2px rgba(237, 139, 0, 0.3);
            transform: translateY(-2px);
        }

        .ek-lightbox-thumb img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        @media (max-width: 768px) {
            .ek-lightbox-thumbs-container {
                padding: 0.75rem;
            }

            .ek-lightbox-thumbs {
                gap: 0.5rem;
                justify-content: flex-start;
            }

            .ek-lightbox-thumb {
                width: 60px;
                height: 60px;
            }
        }

        /* Contador de imágenes */
        .ek-lightbox-counter {
            position: absolute;
            top: 1rem;
            right: 5rem;
            background: white;
            background: rgba(255, 255, 255, 0.45);
            border: 2px solid var(--glass-border);
            padding: 0.5rem 1rem;
            border-radius: var(--radius-full);
            color: var(--ek-navy);
            font-size: 0.85rem;
            font-weight: 600;
            z-index: 10001;
            box-shadow: 0 4px 15px rgba(0, 43, 73, 0.1);
        }

        @media (max-width: 768px) {
            .ek-lightbox-counter {
                font-size: 0.75rem;
                padding: 0.4rem 0.8rem;
                right: auto;
                left: 1rem;
                top: auto;
                bottom: 1rem;
            }
        }

        /* ============================================
           RESPONSIVE FINAL TOUCHES
        ============================================ */
        @media (max-width: 992px) {
            .ek-detalle {
                padding: 1.5rem 0 3rem;
            }
        }

        @media (max-width: 768px) {
            .ek-detalle {
                padding: 1rem 0 2.5rem;
            }
        }

        @media (max-width: 576px) {
            .ek-detalle {
                padding: 0.75rem 0 2rem;
            }
        }

        /* Animación de fade */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .ek-lightbox.active .ek-lightbox-img {
            animation: fadeIn 0.3s ease;
        }
    </style>
</head>

<body>

    <?php
    $pagina_actual = 'productos';
    require_once VIEWS_PATH . 'layouts/nav_publico.php';
    ?>

    <main class="ek-detalle">
        <div class="container">
            <!-- Breadcrumb -->
            <nav class="ek-breadcrumb">
                <a href="<?= BASE_URL ?>"><i class="bi bi-house-door"></i> Inicio</a>
                <span class="ek-breadcrumb-sep">/</span>
                <a href="<?= BASE_URL ?>productos">Colecciones</a>
                <span class="ek-breadcrumb-sep">/</span>
                <a href="<?= BASE_URL ?>productos/categoria/<?= e($producto['categoria_slug']) ?>">
                    <?= e($producto['categoria_nombre']) ?>
                </a>
                <span class="ek-breadcrumb-sep">/</span>
                <span class="ek-breadcrumb-current">
                    <?= e($producto['nombre']) ?>
                </span>
            </nav>

            <div class="ek-detalle-grid">
                <!-- Galería -->
                <div class="ek-galeria">
                    <div class="ek-galeria-main">
                        <?php if (!empty($producto['nuevo'])): ?>
                            <span class="ek-galeria-badge">Nuevo</span>
                        <?php endif; ?>

                        <img src="<?= e(asset('logo_obscuro.svg')) ?>" alt="Ekuora" class="ek-galeria-logo">

                        <img src="<?= e(asset($producto['imagen_principal'])) ?>" id="imagenPrincipal"
                            alt="<?= e($producto['nombre']) ?>" style="cursor: pointer;" onclick="openLightbox(0)">

                        <button class="ek-galeria-zoom" title="Ampliar imagen" onclick="openLightbox(0)">
                            <i class="bi bi-zoom-in"></i>
                        </button>
                    </div>

                    <div class="ek-galeria-thumbs">
                        <!-- Thumbs - Include Main Image First -->
                        <div class="ek-galeria-thumb active" onclick="changeMainImage(this, 0)">
                            <img src="<?= e(asset($producto['imagen_principal'])) ?>"
                                alt="<?= e($producto['nombre']) ?>">
                        </div>

                        <?php
                        $imagenes = (new ProductoCatalogo())->getImagenesProducto($producto['id']);
                        if (!empty($imagenes)):
                            $index = 1;
                            foreach ($imagenes as $img): ?>
                                <div class="ek-galeria-thumb" onclick="changeMainImage(this, <?= $index ?>)">
                                    <img src="<?= e(asset($img['ruta'])) ?>" alt="<?= e($producto['nombre']) ?>">
                                </div>
                                <?php
                                $index++;
                            endforeach;
                        endif; ?>
                    </div>
                </div>

                <!-- Info -->
                <div class="ek-producto-info">
                    <a href="<?= BASE_URL ?>productos/categoria/<?= e($producto['categoria_slug']) ?>"
                        class="ek-producto-categoria">
                        <i class="bi bi-tag-fill"></i>
                        <?= e($producto['categoria_nombre']) ?>
                    </a>

                    <h1 class="ek-producto-titulo">
                        <?= e($producto['nombre']) ?>
                    </h1>

                    <!-- Enhanced Meta Info -->
                    <div class="ek-producto-meta" style="margin-top: 1rem;">
                        <div class="ek-meta-pills" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            <span class="ek-text-pill" style="padding: 0.4rem 1rem; font-size: 0.8rem;">
                                <i class="bi bi-tag-fill"></i> <?= e($producto['categoria_nombre']) ?>
                            </span>
                            <?php if ($producto['sku']): ?>
                                <span class="ek-text-pill" style="padding: 0.4rem 1rem; font-size: 0.8rem; background: #425563;">
                                    <i class="bi bi-hash"></i> <?= e($producto['sku']) ?>
                                </span>
                            <?php endif; ?>
                            <span class="ek-text-pill" style="padding: 0.4rem 1rem; font-size: 0.8rem; background: #22c55e;">
                                <i class="bi bi-check-circle-fill"></i> En Stock
                            </span>
                        </div>
                    </div>

                    <div style="margin-top: 1.5rem;">
                        <h3 style="font-family: 'Montserrat', sans-serif; color: #002B49; font-size: 1.1rem; margin-bottom: 0.75rem; border-left: 4px solid #ED8B00; padding-left: 0.75rem;">
                            Descripción
                        </h3>
                        <p class="ek-producto-desc" style="border-bottom: none; padding-bottom: 0;">
                            <?= nl2br(e($producto['descripcion'] ?? 'Este producto de alta calidad está diseñado para optimizar tu espacio y mantener todo organizado.')) ?>
                        </p>
                    </div>

                    <!-- Permanent Detailed Info Section - No more "empty" feel -->
                    <div class="ek-specs-section" style="margin-top: 2rem; background: #f8fafc; border-radius: var(--radius-lg); border: 1px solid #e2e8f0; overflow: hidden;">
                        <div style="background: #002B49; color: white; padding: 0.75rem 1.25rem; font-weight: 600; font-size: 0.9rem;">
                            <i class="bi bi-list-check"></i> Especificaciones del Producto
                        </div>
                        <div style="padding: 1.25rem;">
                            <div class="ek-spec-list" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                <div class="ek-spec-item" style="border-bottom: none; padding: 0;">
                                    <span class="ek-spec-label">Categoría</span>
                                    <span class="ek-spec-value"><?= e($producto['categoria_nombre']) ?></span>
                                </div>
                                <div class="ek-spec-item" style="border-bottom: none; padding: 0;">
                                    <span class="ek-spec-label">Disponibilidad</span>
                                    <span class="ek-spec-value" style="color: #22c55e;">En Stock</span>
                                </div>
                                <?php if ($producto['sku']): ?>
                                    <div class="ek-spec-item" style="border-bottom: none; padding: 0;">
                                        <span class="ek-spec-label">SKU</span>
                                        <span class="ek-spec-value"><?= e($producto['sku']) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Lightbox Mejorado -->
    <div class="ek-lightbox" id="ekLightbox">
        <div class="ek-lightbox-wrapper">
            <!-- Botón cerrar -->
            <button class="ek-lightbox-close" onclick="closeLightbox()">&times;</button>

            <!-- Contador -->
            <div class="ek-lightbox-counter" id="lightboxCounter">1 / 1</div>

            <!-- Controles de zoom -->
            <div class="ek-lightbox-zoom-controls">
                <button class="ek-zoom-btn" onclick="zoomIn()" title="Acercar">
                    <i class="bi bi-zoom-in"></i>
                </button>
                <button class="ek-zoom-btn" onclick="resetZoom()" title="Zoom original">
                    <i class="bi bi-aspect-ratio"></i>
                </button>
                <button class="ek-zoom-btn" onclick="zoomOut()" title="Alejar">
                    <i class="bi bi-zoom-out"></i>
                </button>
            </div>

            <!-- Contenido principal -->
            <div class="ek-lightbox-content" onclick="closeLightbox()">
                <!-- Flechas de navegación -->
                <button class="ek-lightbox-nav ek-lightbox-prev"
                    onclick="event.stopPropagation(); navigateLightbox(-1)">
                    <i class="bi bi-chevron-left"></i>
                </button>

                <div class="ek-lightbox-image-container" id="lightboxImageContainer" onclick="event.stopPropagation()">
                    <img src="<?= e(asset('logo_obscuro.svg')) ?>" alt="Ekuora" class="ek-lightbox-logo">
                    <img src="" alt="Zoom" class="ek-lightbox-img" id="ekLightboxImg">
                </div>

                <button class="ek-lightbox-nav ek-lightbox-next" onclick="event.stopPropagation(); navigateLightbox(1)">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>

            <!-- Galería de miniaturas -->
            <div class="ek-lightbox-thumbs-container">
                <div class="ek-lightbox-thumbs" id="lightboxThumbs">
                    <!-- Se llenarán dinámicamente con JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <?php require_once VIEWS_PATH . 'layouts/footer_publico.php'; ?>

    <script>
        // Array de imágenes
        const productImages = [
            {
                src: "<?= e(asset($producto['imagen_principal'])) ?>",
                alt: "<?= e($producto['nombre']) ?>"
            }
                <?php
                if (!empty($imagenes)):
                    foreach ($imagenes as $img): ?>
                    , {
                        src: "<?= e(asset($img['ruta'])) ?>",
                        alt: "<?= e($producto['nombre']) ?>"
                    }
                                        <?php endforeach;
                endif; ?>
        ];

        let currentImageIndex = 0;
        let currentZoom = 1;
        const minZoom = 1;
        const maxZoom = 3;
        const zoomStep = 0.5;

        let isDragging = false;
        let startX, startY, translateX = 0, translateY = 0;

        // Abrir lightbox
        function openLightbox(index) {
            currentImageIndex = index;
            currentZoom = 1;
            translateX = 0;
            translateY = 0;

            const lightbox = document.getElementById('ekLightbox');
            const lightboxImg = document.getElementById('ekLightboxImg');
            const container = document.getElementById('lightboxImageContainer');

            lightboxImg.src = productImages[index].src;
            lightboxImg.style.transform = 'scale(1) translate(0, 0)';
            container.classList.remove('zoomed');

            updateCounter();
            renderLightboxThumbs();

            lightbox.style.display = 'flex';
            setTimeout(() => {
                lightbox.classList.add('active');
            }, 10);
            document.body.style.overflow = 'hidden';
        }

        // Cerrar lightbox
        function closeLightbox() {
            const lightbox = document.getElementById('ekLightbox');
            lightbox.classList.remove('active');
            setTimeout(() => {
                lightbox.style.display = 'none';
                document.getElementById('ekLightboxImg').src = '';
                currentZoom = 1;
                translateX = 0;
                translateY = 0;
            }, 300);
            document.body.style.overflow = '';
        }

        // Navegar entre imágenes
        function navigateLightbox(direction) {
            currentImageIndex += direction;
            if (currentImageIndex < 0) currentImageIndex = productImages.length - 1;
            if (currentImageIndex >= productImages.length) currentImageIndex = 0;

            const lightboxImg = document.getElementById('ekLightboxImg');
            const container = document.getElementById('lightboxImageContainer');

            lightboxImg.style.opacity = '0';
            setTimeout(() => {
                lightboxImg.src = productImages[currentImageIndex].src;
                currentZoom = 1;
                translateX = 0;
                translateY = 0;
                lightboxImg.style.transform = 'scale(1) translate(0, 0)';
                container.classList.remove('zoomed');
                lightboxImg.style.opacity = '1';
                updateCounter();
                updateLightboxThumbs();
            }, 150);
        }

        // Zoom functions
        function zoomIn() {
            if (currentZoom < maxZoom) {
                currentZoom += zoomStep;
                applyZoom();
            }
        }

        function zoomOut() {
            if (currentZoom > minZoom) {
                currentZoom -= zoomStep;
                if (currentZoom <= minZoom) {
                    resetZoom();
                } else {
                    applyZoom();
                }
            }
        }

        function resetZoom() {
            currentZoom = 1;
            translateX = 0;
            translateY = 0;
            const lightboxImg = document.getElementById('ekLightboxImg');
            const container = document.getElementById('lightboxImageContainer');
            lightboxImg.style.transform = 'scale(1) translate(0, 0)';
            container.classList.remove('zoomed');
        }

        function applyZoom() {
            const lightboxImg = document.getElementById('ekLightboxImg');
            const container = document.getElementById('lightboxImageContainer');
            lightboxImg.style.transform = `scale(${currentZoom}) translate(${translateX}px, ${translateY}px)`;

            if (currentZoom > 1) {
                container.classList.add('zoomed');
            } else {
                container.classList.remove('zoomed');
            }
        }

        // Drag functionality
        const container = document.getElementById('lightboxImageContainer');

        container.addEventListener('mousedown', startDrag);
        container.addEventListener('touchstart', startDrag);

        document.addEventListener('mousemove', drag);
        document.addEventListener('touchmove', drag);

        document.addEventListener('mouseup', endDrag);
        document.addEventListener('touchend', endDrag);

        function startDrag(e) {
            if (currentZoom <= 1) return;

            isDragging = true;
            container.classList.add('grabbing');

            const clientX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
            const clientY = e.type.includes('touch') ? e.touches[0].clientY : e.clientY;

            startX = clientX - translateX;
            startY = clientY - translateY;

            e.preventDefault();
        }

        function drag(e) {
            if (!isDragging || currentZoom <= 1) return;

            const clientX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
            const clientY = e.type.includes('touch') ? e.touches[0].clientY : e.clientY;

            translateX = clientX - startX;
            translateY = clientY - startY;

            applyZoom();
            e.preventDefault();
        }

        function endDrag() {
            isDragging = false;
            container.classList.remove('grabbing');
        }

        // Zoom con rueda del mouse
        container.addEventListener('wheel', (e) => {
            e.preventDefault();
            if (e.deltaY < 0) {
                zoomIn();
            } else {
                zoomOut();
            }
        }, { passive: false });

        // Actualizar contador
        function updateCounter() {
            document.getElementById('lightboxCounter').textContent =
                `${currentImageIndex + 1} / ${productImages.length}`;
        }

        // Renderizar miniaturas en lightbox
        function renderLightboxThumbs() {
            const thumbsContainer = document.getElementById('lightboxThumbs');
            thumbsContainer.innerHTML = '';

            productImages.forEach((img, index) => {
                const thumb = document.createElement('div');
                thumb.className = 'ek-lightbox-thumb' + (index === currentImageIndex ? ' active' : '');
                thumb.onclick = (e) => {
                    e.stopPropagation();
                    changeLightboxImage(index);
                };

                const thumbImg = document.createElement('img');
                thumbImg.src = img.src;
                thumbImg.alt = img.alt;

                thumb.appendChild(thumbImg);
                thumbsContainer.appendChild(thumb);
            });
        }

        // Actualizar miniaturas activas
        function updateLightboxThumbs() {
            const thumbs = document.querySelectorAll('.ek-lightbox-thumb');
            thumbs.forEach((thumb, index) => {
                thumb.classList.toggle('active', index === currentImageIndex);
            });
        }

        // Cambiar imagen desde miniatura
        function changeLightboxImage(index) {
            if (index === currentImageIndex) return;

            currentImageIndex = index;
            const lightboxImg = document.getElementById('ekLightboxImg');
            const container = document.getElementById('lightboxImageContainer');

            lightboxImg.style.opacity = '0';
            setTimeout(() => {
                lightboxImg.src = productImages[index].src;
                currentZoom = 1;
                translateX = 0;
                translateY = 0;
                lightboxImg.style.transform = 'scale(1) translate(0, 0)';
                container.classList.remove('zoomed');
                lightboxImg.style.opacity = '1';
                updateCounter();
                updateLightboxThumbs();
            }, 150);
        }

        // Teclas de navegación
        document.addEventListener('keydown', function (e) {
            const lightbox = document.getElementById('ekLightbox');
            if (!lightbox.classList.contains('active')) return;

            switch (e.key) {
                case 'Escape':
                    closeLightbox();
                    break;
                case 'ArrowLeft':
                    navigateLightbox(-1);
                    break;
                case 'ArrowRight':
                    navigateLightbox(1);
                    break;
                case '+':
                case '=':
                    zoomIn();
                    break;
                case '-':
                case '_':
                    zoomOut();
                    break;
                case '0':
                    resetZoom();
                    break;
            }
        });

        // Cambiar imagen principal
        function changeMainImage(thumb, index) {
            const img = thumb.querySelector('img');
            const mainImg = document.getElementById('imagenPrincipal');

            mainImg.style.opacity = '0';
            setTimeout(() => {
                mainImg.src = img.src;
                mainImg.setAttribute('onclick', `openLightbox(${index})`);
                mainImg.style.opacity = '1';
            }, 150);

            document.querySelectorAll('.ek-galeria-thumb').forEach(t => t.classList.remove('active'));
            thumb.classList.add('active');
        }

        // Accordion
        function toggleAccordion(header) {
            const item = header.parentElement;
            item.classList.toggle('active');
        }
    </script>

</body>

</html>