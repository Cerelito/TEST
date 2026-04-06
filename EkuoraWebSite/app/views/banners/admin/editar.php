<?php
$titulo = 'Editar Banner | Ekuora Admin';
$pagina_actual = 'banners_admin';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<style>
    /* ============================================
       EKUORA ADMIN - EDITAR BANNER
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

    .ek-admin-hero-subtitle strong {
        color: white;
    }

    .ek-breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .ek-breadcrumb a {
        color: var(--ek-sky-light);
        text-decoration: none;
        font-size: 0.9rem;
        transition: var(--transition);
    }

    .ek-breadcrumb a:hover {
        color: var(--ek-orange);
    }

    .ek-breadcrumb span {
        color: rgba(255, 255, 255, 0.5);
    }

    .ek-breadcrumb .current {
        color: white;
        font-weight: 600;
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
        background: linear-gradient(135deg, var(--ek-orange) 0%, var(--ek-orange-light) 100%);
        color: white;
        box-shadow: 0 4px 20px rgba(237, 139, 0, 0.4);
    }

    .ek-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(237, 139, 0, 0.5);
        color: white;
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

    .ek-btn-outline {
        background: transparent;
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .ek-btn-outline:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: white;
        color: white;
    }

    /* ============================================
       FORM LAYOUT
    ============================================ */
    .ek-form-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 2rem;
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

    .ek-card+.ek-card {
        margin-top: 1.5rem;
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

    .ek-card-icon.orange {
        background: rgba(237, 139, 0, 0.1);
        color: var(--ek-orange);
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

    .ek-card-body {
        padding: 1.5rem;
    }

    /* ============================================
       FORM ELEMENTS
    ============================================ */
    .ek-form-group {
        margin-bottom: 1.5rem;
    }

    .ek-form-group:last-child {
        margin-bottom: 0;
    }

    .ek-form-label {
        display: block;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--ek-navy);
        margin-bottom: 0.5rem;
    }

    .ek-form-label .optional {
        color: var(--ek-sky);
        font-weight: 400;
        font-size: 0.85rem;
    }

    .ek-form-input,
    .ek-form-select,
    .ek-form-textarea {
        width: 100%;
        padding: 1rem 1.25rem;
        background: white;
        border: 2px solid var(--glass-border);
        border-radius: var(--radius-md);
        font-size: 1rem;
        color: var(--ek-navy);
        transition: var(--transition);
    }

    .ek-form-input:focus,
    .ek-form-select:focus,
    .ek-form-textarea:focus {
        outline: none;
        border-color: var(--ek-orange);
        box-shadow: 0 0 0 4px rgba(237, 139, 0, 0.1);
    }

    .ek-form-input::placeholder,
    .ek-form-textarea::placeholder {
        color: var(--ek-sky);
    }

    .ek-form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23425563' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1.25rem center;
        padding-right: 3rem;
        cursor: pointer;
    }

    .ek-form-hint {
        font-size: 0.8rem;
        color: var(--ek-slate);
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .ek-form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .ek-form-row-3 {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 1.5rem;
    }

    /* ============================================
       TOGGLE SWITCH
    ============================================ */
    .ek-toggle-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.25rem;
        background: var(--ek-sky-pale);
        border-radius: var(--radius-md);
    }

    .ek-toggle-label {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .ek-toggle-label i {
        font-size: 1.25rem;
        color: var(--ek-navy);
    }

    .ek-toggle-info {
        font-weight: 600;
        color: var(--ek-navy);
    }

    .ek-toggle-desc {
        font-size: 0.8rem;
        color: var(--ek-slate);
    }

    .ek-toggle {
        position: relative;
        width: 56px;
        height: 30px;
    }

    .ek-toggle input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .ek-toggle-slider {
        position: absolute;
        cursor: pointer;
        inset: 0;
        background: var(--glass-border);
        border-radius: 30px;
        transition: var(--transition);
    }

    .ek-toggle-slider::before {
        content: '';
        position: absolute;
        height: 24px;
        width: 24px;
        left: 3px;
        bottom: 3px;
        background: white;
        border-radius: 50%;
        transition: var(--transition);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .ek-toggle input:checked+.ek-toggle-slider {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    }

    .ek-toggle input:checked+.ek-toggle-slider::before {
        transform: translateX(26px);
    }

    /* ============================================
       IMAGE PREVIEW & DROPZONE
    ============================================ */
    .ek-current-image {
        margin-bottom: 1.5rem;
    }

    .ek-current-image-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--ek-slate);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.75rem;
        display: block;
    }

    .ek-image-preview {
        position: relative;
        width: 100%;
        aspect-ratio: 16/7;
        background: var(--ek-sky-pale);
        border-radius: var(--radius-md);
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid var(--glass-border);
    }

    .ek-image-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .ek-image-preview .placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
        color: var(--ek-sky);
    }

    .ek-image-preview .placeholder i {
        font-size: 3rem;
    }

    .ek-image-preview .placeholder span {
        font-size: 0.9rem;
    }

    .ek-dropzone {
        position: relative;
        border: 2px dashed var(--glass-border);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: var(--transition);
        background: rgba(255, 255, 255, 0.5);
    }

    .ek-dropzone:hover,
    .ek-dropzone.dragover {
        border-color: var(--ek-orange);
        background: rgba(237, 139, 0, 0.05);
    }

    .ek-dropzone input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
    }

    .ek-dropzone-icon {
        width: 50px;
        height: 50px;
        margin: 0 auto 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--ek-sky-pale);
        border-radius: 50%;
        color: var(--ek-sky);
        font-size: 1.25rem;
        transition: var(--transition);
    }

    .ek-dropzone:hover .ek-dropzone-icon {
        background: rgba(237, 139, 0, 0.1);
        color: var(--ek-orange);
    }

    .ek-dropzone-text {
        font-size: 0.9rem;
        color: var(--ek-slate);
    }

    .ek-dropzone-text strong {
        color: var(--ek-orange);
    }

    .ek-dropzone-hint {
        font-size: 0.8rem;
        color: var(--ek-sky);
        margin-top: 0.35rem;
    }

    /* New Image Preview */
    .ek-new-preview {
        margin-top: 1rem;
        text-align: center;
        display: none;
    }

    .ek-new-preview.show {
        display: block;
    }

    .ek-new-preview img {
        max-height: 120px;
        border-radius: var(--radius-sm);
        border: 3px solid var(--ek-orange);
        box-shadow: 0 4px 16px rgba(237, 139, 0, 0.2);
    }

    .ek-new-preview-label {
        margin-top: 0.5rem;
        font-size: 0.8rem;
        color: var(--ek-orange);
        font-weight: 600;
    }

    /* ============================================
       INFO TIP
    ============================================ */
    .ek-info-tip {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 1rem;
        background: rgba(0, 43, 73, 0.03);
        border-radius: var(--radius-sm);
        margin-top: 1rem;
    }

    .ek-info-tip i {
        color: var(--ek-orange);
        font-size: 1rem;
        flex-shrink: 0;
        margin-top: 0.1rem;
    }

    .ek-info-tip p {
        font-size: 0.85rem;
        color: var(--ek-slate);
        line-height: 1.5;
        margin: 0;
    }

    .ek-info-tip strong {
        color: var(--ek-navy);
    }

    /* ============================================
       RESPONSIVE
    ============================================ */
    @media (max-width: 992px) {
        .ek-form-grid {
            grid-template-columns: 1fr;
        }

        .ek-admin-hero-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .ek-admin-hero-title {
            font-size: 1.75rem;
        }

        .ek-form-row,
        .ek-form-row-3 {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .ek-admin-hero {
            padding: 2rem 1.5rem;
            margin: -1rem -1rem 1.5rem;
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
            <div class="ek-admin-hero-badge">Edición de Slide</div>
            <h1 class="ek-admin-hero-title">
                <i class="bi bi-pencil-square"></i>
                Editar Banner
            </h1>
            <p class="ek-admin-hero-subtitle">Modificando: <strong>
                    <?= e($banner['titulo'] ?: 'Banner #' . $banner['id']) ?>
                </strong></p>
            <nav class="ek-breadcrumb">
                <a href="<?= BASE_URL ?>dashboard"><i class="bi bi-house"></i></a>
                <span>/</span>
                <a href="<?= BASE_URL ?>banners/admin">Banners</a>
                <span>/</span>
                <span class="current">Editar</span>
            </nav>
        </div>
        <a href="<?= BASE_URL ?>banners/admin" class="ek-btn ek-btn-outline">
            <i class="bi bi-arrow-left"></i> Volver al Listado
        </a>
    </div>
</section>

<form action="<?= BASE_URL ?>banners/actualizar/<?= $banner['id'] ?>" method="POST" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="ek-form-grid">
        <!-- Columna Principal -->
        <div class="ek-main-column">
            <!-- Contenido del Banner -->
            <div class="ek-card ek-fade-up" style="animation-delay: 0.1s;">
                <div class="ek-card-header">
                    <div class="ek-card-icon">
                        <i class="bi bi-card-text"></i>
                    </div>
                    <div>
                        <h3 class="ek-card-title">Contenido del Banner</h3>
                        <p class="ek-card-subtitle">Textos principales del slide</p>
                    </div>
                </div>
                <div class="ek-card-body">
                    <div class="ek-form-row">
                        <div class="ek-form-group">
                            <label class="ek-form-label">Título Principal</label>
                            <input type="text" name="titulo" class="ek-form-input" value="<?= e($banner['titulo']) ?>"
                                placeholder="Ej: Gran Promoción de Verano">
                        </div>
                        <div class="ek-form-group">
                            <label class="ek-form-label">Subtítulo / Accent</label>
                            <input type="text" name="subtitulo" class="ek-form-input"
                                value="<?= e($banner['subtitulo']) ?>" placeholder="Ej: Hasta 50% de descuento">
                        </div>
                    </div>

                    <div class="ek-form-row-3">
                        <div class="ek-form-group">
                            <label class="ek-form-label">Texto del Botón</label>
                            <input type="text" name="texto_boton" class="ek-form-input"
                                value="<?= e($banner['texto_boton']) ?>" placeholder="Ver Ofertas">
                        </div>
                        <div class="ek-form-group">
                            <label class="ek-form-label">Enlace (URL)</label>
                            <input type="text" name="enlace" class="ek-form-input" value="<?= e($banner['enlace']) ?>"
                                placeholder="https://tutienda.com/ofertas">
                            <p class="ek-form-hint">
                                <i class="bi bi-link-45deg"></i>
                                URL completa o ruta relativa
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configuración -->
            <div class="ek-card ek-fade-up" style="animation-delay: 0.2s;">
                <div class="ek-card-header">
                    <div class="ek-card-icon orange">
                        <i class="bi bi-sliders"></i>
                    </div>
                    <div>
                        <h3 class="ek-card-title">Configuración</h3>
                        <p class="ek-card-subtitle">Ubicación, orden y estado</p>
                    </div>
                </div>
                <div class="ek-card-body">
                    <div class="ek-form-row">
                        <div class="ek-form-group">
                            <label class="ek-form-label">Ubicación del Banner</label>
                            <select name="seccion" class="ek-form-select">
                                <option value="hero" <?= $banner['seccion'] === 'hero' ? 'selected' : '' ?>>
                                    Slider Principal (Hero)
                                </option>
                                <option value="recommend" <?= $banner['seccion'] === 'recommend' ? 'selected' : '' ?>>
                                    Sección Recomendados
                                </option>
                            </select>
                            <p class="ek-form-hint">
                                <i class="bi bi-info-circle"></i>
                                Elige en qué parte de la página aparecerá
                            </p>
                        </div>
                        <div class="ek-form-group">
                            <label class="ek-form-label">Orden de Aparición</label>
                            <input type="number" name="orden" class="ek-form-input"
                                value="<?= (int) $banner['orden'] ?>" min="0">
                            <p class="ek-form-hint">
                                <i class="bi bi-sort-numeric-up"></i>
                                Número menor = aparece primero
                            </p>
                        </div>
                    </div>

                    <div class="ek-form-group">
                        <label class="ek-form-label">Estado</label>
                        <div class="ek-toggle-container">
                            <div class="ek-toggle-label">
                                <i class="bi bi-eye"></i>
                                <div>
                                    <div class="ek-toggle-info">Banner Activo</div>
                                    <div class="ek-toggle-desc">Se mostrará en el sitio público</div>
                                </div>
                            </div>
                            <label class="ek-toggle">
                                <input type="checkbox" name="activo" value="1" <?= $banner['activo'] ? 'checked' : '' ?>>
                                <span class="ek-toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Lateral -->
        <div class="ek-side-column">
            <!-- Imagen -->
            <div class="ek-card ek-fade-up" style="animation-delay: 0.15s;">
                <div class="ek-card-header">
                    <div class="ek-card-icon">
                        <i class="bi bi-image"></i>
                    </div>
                    <div>
                        <h3 class="ek-card-title">Imagen de Fondo</h3>
                        <p class="ek-card-subtitle">Visual principal del banner</p>
                    </div>
                </div>
                <div class="ek-card-body">
                    <!-- Current Image -->
                    <?php if ($banner['imagen']): ?>
                        <div class="ek-current-image">
                            <span class="ek-current-image-label">Imagen Actual</span>
                            <div class="ek-image-preview">
                                <img src="<?= e(asset($banner['imagen'])) ?>" alt="<?= e($banner['titulo'] ?? 'Banner') ?>">
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="ek-current-image">
                            <span class="ek-current-image-label">Sin imagen</span>
                            <div class="ek-image-preview">
                                <div class="placeholder">
                                    <i class="bi bi-images"></i>
                                    <span>No hay imagen</span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Upload New Image -->
                    <div class="ek-dropzone" id="dropzone">
                        <input type="file" name="imagen" accept="image/*" id="file-input">
                        <div class="ek-dropzone-icon">
                            <i class="bi bi-cloud-arrow-up"></i>
                        </div>
                        <div class="ek-dropzone-text">
                            <strong>Subir nueva</strong> para reemplazar
                        </div>
                        <div class="ek-dropzone-hint">PNG, JPG o WEBP</div>
                    </div>

                    <!-- New Image Preview -->
                    <div class="ek-new-preview" id="new-preview">
                        <img src="" alt="Nueva imagen" id="preview-img">
                        <div class="ek-new-preview-label">
                            <i class="bi bi-check-circle"></i> Nueva imagen
                        </div>
                    </div>

                    <div class="ek-info-tip">
                        <i class="bi bi-aspect-ratio"></i>
                        <p>
                            <strong>Hero:</strong> 1920x600px<br>
                            <strong>Recomendados:</strong> 800x400px
                        </p>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="ek-card ek-fade-up" style="animation-delay: 0.25s;">
                <div class="ek-card-body">
                    <button type="submit" class="ek-btn ek-btn-primary" style="width: 100%; margin-bottom: 1rem;">
                        <i class="bi bi-check-lg"></i> Guardar Cambios
                    </button>
                    <a href="<?= BASE_URL ?>banners/admin" class="ek-btn ek-btn-secondary" style="width: 100%;">
                        <i class="bi bi-x-lg"></i> Cancelar
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('file-input');
        const newPreview = document.getElementById('new-preview');
        const previewImg = document.getElementById('preview-img');

        // Drag & Drop
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, () => dropzone.classList.add('dragover'), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, () => dropzone.classList.remove('dragover'), false);
        });

        dropzone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length) {
                fileInput.files = files;
                previewFile(files[0]);
            }
        }

        // File input change
        fileInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                previewFile(this.files[0]);
            }
        });

        function previewFile(file) {
            if (!file.type.startsWith('image/')) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                previewImg.src = e.target.result;
                newPreview.classList.add('show');
            }
            reader.readAsDataURL(file);
        }
    });
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>