<?php
$pagina_actual = 'productos_admin';
$titulo = 'Editar Producto | Ekuora Admin';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<style>
    /* ============================================
       EKUORA ADMIN - EDITAR PRODUCTO
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
        }

        50% {
            opacity: 0.5;
        }
    }

    .ek-admin-hero-title {
        font-family: 'Outfit', sans-serif;
        font-size: 2.25rem;
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
       FORM GRID
    ============================================ */
    .ek-form-grid {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 2rem;
        align-items: start;
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
    }

    .ek-card-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--glass-border);
        background: linear-gradient(135deg, rgba(0, 43, 73, 0.03) 0%, transparent 100%);
    }

    .ek-card-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--radius-md);
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .ek-card-icon.navy {
        background: rgba(0, 43, 73, 0.1);
        color: var(--ek-navy);
    }

    .ek-card-icon.orange {
        background: rgba(237, 139, 0, 0.1);
        color: var(--ek-orange);
    }

    .ek-card-icon.sky {
        background: rgba(122, 153, 172, 0.2);
        color: var(--ek-sky);
    }

    .ek-card-title {
        font-family: 'Outfit', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--ek-navy);
        margin: 0 0 0.2rem;
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
        margin-bottom: 1.25rem;
    }

    .ek-form-group:last-child {
        margin-bottom: 0;
    }

    .ek-form-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--ek-navy);
        margin-bottom: 0.5rem;
    }

    .ek-form-label i {
        color: var(--ek-orange);
        font-size: 0.9rem;
    }

    .ek-form-label .required {
        color: #ef4444;
    }

    .ek-input {
        width: 100%;
        padding: 0.875rem 1.25rem;
        background: white;
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-md);
        font-size: 0.95rem;
        color: var(--ek-navy);
        transition: var(--transition);
    }

    .ek-input:focus {
        outline: none;
        border-color: var(--ek-orange);
        box-shadow: 0 0 0 3px rgba(237, 139, 0, 0.1);
    }

    .ek-input::placeholder {
        color: var(--ek-sky);
    }

    .ek-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23425563' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        padding-right: 2.5rem;
        cursor: pointer;
    }

    .ek-textarea {
        min-height: 120px;
        resize: vertical;
    }

    .ek-form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    /* ============================================
       FILE UPLOAD
    ============================================ */
    .ek-current-image {
        text-align: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px dashed var(--glass-border);
    }

    .ek-current-image-box {
        width: 120px;
        height: 120px;
        margin: 0 auto 0.75rem;
        background: var(--ek-sky-pale);
        border: 2px solid var(--glass-border);
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .ek-current-image-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .ek-current-image-label {
        font-size: 0.85rem;
        color: var(--ek-slate);
    }

    .ek-file-upload {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem 1.5rem;
        background: white;
        border: 2px dashed var(--glass-border);
        border-radius: var(--radius-lg);
        cursor: pointer;
        transition: var(--transition);
        text-align: center;
    }

    .ek-file-upload:hover {
        border-color: var(--ek-orange);
        background: rgba(237, 139, 0, 0.02);
    }

    .ek-file-upload.dragover {
        border-color: var(--ek-orange);
        background: rgba(237, 139, 0, 0.05);
    }

    .ek-file-upload input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
    }

    .ek-file-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--ek-sky-pale);
        border-radius: 50%;
        color: var(--ek-orange);
        font-size: 1.75rem;
        margin-bottom: 1rem;
    }

    .ek-file-title {
        font-family: 'Outfit', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        color: var(--ek-navy);
        margin-bottom: 0.25rem;
    }

    .ek-file-hint {
        font-size: 0.85rem;
        color: var(--ek-sky);
    }

    .ek-image-preview {
        display: none;
        text-align: center;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px dashed var(--glass-border);
    }

    .ek-image-preview.active {
        display: block;
    }

    .ek-preview-box {
        width: 100px;
        height: 100px;
        margin: 0 auto 0.5rem;
        border: 2px solid var(--ek-orange);
        border-radius: var(--radius-md);
        overflow: hidden;
    }

    .ek-preview-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .ek-preview-label {
        font-size: 0.85rem;
        color: var(--ek-orange);
        font-weight: 600;
    }

    /* ============================================
       SWITCHES
    ============================================ */
    .ek-switches {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .ek-switch-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.25rem;
        background: white;
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-md);
        transition: var(--transition);
    }

    .ek-switch-item:hover {
        border-color: var(--ek-orange);
    }

    .ek-switch-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .ek-switch-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--ek-sky-pale);
        border-radius: var(--radius-sm);
        color: var(--ek-slate);
        font-size: 1.1rem;
    }

    .ek-switch-label {
        font-weight: 600;
        color: var(--ek-navy);
        font-size: 0.95rem;
    }

    .ek-switch-desc {
        font-size: 0.8rem;
        color: var(--ek-sky);
    }

    /* Toggle Switch */
    .ek-toggle {
        position: relative;
        width: 52px;
        height: 28px;
        cursor: pointer;
    }

    .ek-toggle input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .ek-toggle-slider {
        position: absolute;
        inset: 0;
        background: var(--glass-border);
        border-radius: var(--radius-full);
        transition: var(--transition);
    }

    .ek-toggle-slider::before {
        content: '';
        position: absolute;
        width: 22px;
        height: 22px;
        left: 3px;
        top: 3px;
        background: white;
        border-radius: 50%;
        transition: var(--transition);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .ek-toggle input:checked+.ek-toggle-slider {
        background: var(--ek-orange);
    }

    .ek-toggle input:checked+.ek-toggle-slider::before {
        transform: translateX(24px);
    }

    /* ============================================
       SUBMIT BUTTON
    ============================================ */
    .ek-submit-section {
        margin-top: 1.5rem;
    }

    .ek-submit-btn {
        width: 100%;
        padding: 1.25rem 2rem;
        background: linear-gradient(135deg, var(--ek-orange) 0%, var(--ek-orange-light) 100%);
        color: white;
        border: none;
        border-radius: var(--radius-lg);
        font-family: 'Outfit', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        box-shadow: 0 4px 20px rgba(237, 139, 0, 0.4);
    }

    .ek-submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(237, 139, 0, 0.5);
    }

    .ek-submit-btn i {
        font-size: 1.25rem;
    }

    /* ============================================
       RESPONSIVE
    ============================================ */
    @media (max-width: 992px) {
        .ek-admin-hero-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .ek-form-grid {
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

        .ek-form-row {
            grid-template-columns: 1fr;
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
            <div class="ek-admin-hero-badge">Edición de Registro</div>
            <h1 class="ek-admin-hero-title">
                <i class="bi bi-pencil-square"></i>
                Editar Producto
            </h1>
            <p class="ek-admin-hero-subtitle">
                Modifica los detalles del producto: <strong><?= e($producto['nombre']) ?></strong>
            </p>
        </div>
        <a href="<?= BASE_URL ?>productos/admin" class="ek-btn ek-btn-outline">
            <i class="bi bi-arrow-left"></i> Volver al listado
        </a>
    </div>
</section>

<!-- Form -->
<form action="<?= BASE_URL ?>productos/actualizar/<?= $producto['id'] ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">

    <div class="ek-form-grid">
        <!-- Columna Izquierda: Datos Principales -->
        <div class="ek-fade-up" style="animation-delay: 0.1s;">
            <div class="ek-card">
                <div class="ek-card-header">
                    <div class="ek-card-icon navy">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <div>
                        <h3 class="ek-card-title">Información Básica</h3>
                        <p class="ek-card-subtitle">Datos esenciales del producto</p>
                    </div>
                </div>
                <div class="ek-card-body">
                    <div class="ek-form-group">
                        <label class="ek-form-label">
                            <i class="bi bi-tag"></i>
                            Nombre del Producto <span class="required">*</span>
                        </label>
                        <input type="text" name="nombre" class="ek-input" value="<?= e($producto['nombre']) ?>" required
                            placeholder="Ej. Organizador de Especias Premium">
                    </div>

                    <div class="ek-form-row">
                        <div class="ek-form-group">
                            <label class="ek-form-label">
                                <i class="bi bi-folder2"></i>
                                Categoría <span class="required">*</span>
                            </label>
                            <select name="categoria_id" class="ek-input ek-select" required
                                onchange="cargarFamilias(this.value)">
                                <option value="">Selecciona...</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $producto['categoria_id'] ? 'selected' : '' ?>>
                                        <?= e($cat['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="ek-form-group">
                            <label class="ek-form-label">
                                <i class="bi bi-diagram-2"></i>
                                Familia <span class="required">*</span>
                            </label>
                            <select id="familia_id" name="familia_id" class="ek-input ek-select" required>
                                <option value="">Selecciona...</option>
                                <?php if (!empty($familias)): ?>
                                    <?php foreach ($familias as $fam): ?>
                                        <option value="<?= $fam['id'] ?>" <?= $fam['id'] == ($producto['familia_id'] ?? 0) ? 'selected' : '' ?>>
                                            <?= e($fam['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="ek-form-row">
                        <div class="ek-form-group">
                            <label class="ek-form-label">
                                <i class="bi bi-upc-scan"></i>
                                SKU / Referencia
                            </label>
                            <input type="text" name="sku" class="ek-input" value="<?= e($producto['sku'] ?? '') ?>"
                                placeholder="Ej. ORG-ESP-001">
                        </div>
                        <div class="ek-form-group">
                            <label class="ek-form-label">
                                <i class="bi bi-award"></i>
                                Marca
                            </label>
                            <input type="text" name="marca" class="ek-input" value="<?= e($producto['marca'] ?? '') ?>"
                                placeholder="Ej. Ekuora">
                        </div>
                    </div>

                    <div class="ek-form-group">
                        <label class="ek-form-label">
                            <i class="bi bi-text-left"></i>
                            Descripción Corta
                        </label>
                        <input type="text" name="descripcion_corta" class="ek-input"
                            value="<?= e($producto['descripcion_corta'] ?? '') ?>"
                            placeholder="Breve resumen del producto (se muestra en tarjetas)">
                    </div>

                    <div class="ek-form-group">
                        <label class="ek-form-label">
                            <i class="bi bi-card-text"></i>
                            Descripción Detallada
                        </label>
                        <textarea name="descripcion" class="ek-input ek-textarea" rows="5"
                            placeholder="Explica las características, beneficios y especificaciones del producto..."><?= e($producto['descripcion'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Multimedia y Ajustes -->
        <div class="ek-fade-up" style="animation-delay: 0.2s;">
            <!-- Imagen Principal -->
            <div class="ek-card">
                <div class="ek-card-header">
                    <div class="ek-card-icon orange">
                        <i class="bi bi-image"></i>
                    </div>
                    <div>
                        <h3 class="ek-card-title">Imagen Principal</h3>
                        <p class="ek-card-subtitle">Formatos JPG, PNG, WebP. Máx 2MB</p>
                    </div>
                </div>
                <div class="ek-card-body">
                    <?php if ($producto['imagen_principal']): ?>
                        <div class="ek-current-image">
                            <div class="ek-current-image-box">
                                <img src="<?= e(asset($producto['imagen_principal'])) ?>" alt="Imagen actual">
                            </div>
                            <span class="ek-current-image-label">Imagen actual</span>
                        </div>
                    <?php endif; ?>

                    <div class="ek-file-upload" id="dropzone">
                        <input type="file" name="imagen_principal" accept="image/*" onchange="previewImage(this)">
                        <div class="ek-file-icon">
                            <i class="bi bi-cloud-arrow-up"></i>
                        </div>
                        <span class="ek-file-title">Click para subir nueva imagen</span>
                        <span class="ek-file-hint">O arrastra el archivo aquí</span>
                    </div>

                    <div class="ek-image-preview" id="imagePreview">
                        <div class="ek-preview-box">
                            <img src="" alt="Previsualización" id="previewImg">
                        </div>
                        <span class="ek-preview-label">Nueva imagen seleccionada</span>
                    </div>
                </div>
            </div>

            <!-- Configuración -->
            <div class="ek-card">
                <div class="ek-card-header">
                    <div class="ek-card-icon sky">
                        <i class="bi bi-sliders"></i>
                    </div>
                    <div>
                        <h3 class="ek-card-title">Configuración</h3>
                        <p class="ek-card-subtitle">Estado y opciones de visualización</p>
                    </div>
                </div>
                <div class="ek-card-body">
                    <div class="ek-form-group">
                        <label class="ek-form-label">
                            <i class="bi bi-images"></i>
                            Imágenes Adicionales
                        </label>
                        <div class="ek-dropzone" style="padding: 1rem;">
                            <input type="file" name="imagenes_adicionales[]" multiple accept="image/*" max="3">
                            <div class="text-center">
                                <i class="bi bi-cloud-plus" style="font-size: 1.5rem; color: var(--ek-sky);"></i>
                                <p style="margin: 0.5rem 0; font-size: 0.9rem;">Seleccionar hasta 3 imágenes</p>
                            </div>
                        </div>
                        <?php if (!empty($imagenes)): ?>
                            <style>
                                .ek-img-gallery {
                                    display: grid;
                                    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
                                    gap: 0.75rem;
                                    margin-top: 0.75rem;
                                }
                                .ek-img-thumb {
                                    position: relative;
                                    border-radius: var(--radius-md);
                                    overflow: hidden;
                                    border: 2px solid var(--glass-border);
                                    background: var(--ek-sky-pale);
                                    aspect-ratio: 1;
                                    transition: var(--transition);
                                    cursor: default;
                                }
                                .ek-img-thumb:hover { border-color: var(--ek-orange); }
                                .ek-img-thumb img {
                                    width: 100%; height: 100%; object-fit: cover; display: block;
                                }
                                /* Número de orden */
                                .ek-img-order-badge {
                                    position: absolute;
                                    top: 6px; left: 6px;
                                    width: 26px; height: 26px;
                                    background: var(--ek-navy);
                                    color: white;
                                    border-radius: 50%;
                                    font-size: 0.75rem;
                                    font-weight: 800;
                                    display: flex; align-items: center; justify-content: center;
                                    box-shadow: 0 2px 6px rgba(0,0,0,0.3);
                                    z-index: 2;
                                }
                                /* Panel de acciones visible al hover */
                                .ek-img-actions {
                                    position: absolute; bottom: 0; left: 0; right: 0;
                                    display: flex; flex-wrap: wrap; gap: 2px; padding: 4px;
                                    background: rgba(0,0,0,0.72);
                                    opacity: 0;
                                    transition: opacity 0.2s ease;
                                }
                                .ek-img-thumb:hover .ek-img-actions { opacity: 1; }
                                .ek-img-act-btn {
                                    flex: 1; min-width: 28px; padding: 5px 3px;
                                    border: none; border-radius: 5px;
                                    font-size: 0.68rem; font-weight: 700;
                                    cursor: pointer; transition: var(--transition);
                                    display: flex; align-items: center; justify-content: center; gap: 2px;
                                    white-space: nowrap;
                                }
                                .ek-img-act-btn.mov  { background: rgba(255,255,255,0.15); color: #fff; }
                                .ek-img-act-btn.mov:hover  { background: rgba(255,255,255,0.35); }
                                .ek-img-act-btn.principal { background: var(--ek-orange); color: white; }
                                .ek-img-act-btn.principal:hover { background: var(--ek-orange-light); }
                                .ek-img-act-btn.eliminar  { background: #ef4444; color: white; }
                                .ek-img-act-btn.eliminar:hover  { background: #dc2626; }
                                .ek-img-act-btn:disabled { opacity: 0.4; cursor: not-allowed; }
                                /* ---- CUSTOM MODAL ---- */
                                .ek-modal-overlay {
                                    position: fixed; inset: 0; z-index: 9999;
                                    background: rgba(0,43,73,0.55);
                                    backdrop-filter: blur(4px);
                                    display: flex; align-items: center; justify-content: center;
                                    opacity: 0; pointer-events: none;
                                    transition: opacity 0.25s ease;
                                }
                                .ek-modal-overlay.show { opacity: 1; pointer-events: all; }
                                .ek-modal {
                                    background: white;
                                    border-radius: 20px;
                                    padding: 2rem 2rem 1.5rem;
                                    max-width: 400px; width: 90%;
                                    box-shadow: 0 20px 60px rgba(0,43,73,0.25);
                                    transform: translateY(20px) scale(0.95);
                                    transition: transform 0.25s ease;
                                    text-align: center;
                                }
                                .ek-modal-overlay.show .ek-modal { transform: translateY(0) scale(1); }
                                .ek-modal-icon {
                                    width: 64px; height: 64px; border-radius: 50%;
                                    display: flex; align-items: center; justify-content: center;
                                    font-size: 1.75rem; margin: 0 auto 1rem;
                                }
                                .ek-modal-icon.warning { background: rgba(237,139,0,0.12); color: var(--ek-orange); }
                                .ek-modal-icon.danger  { background: rgba(239,68,68,0.12);  color: #ef4444; }
                                .ek-modal-title {
                                    font-family: 'Outfit', sans-serif;
                                    font-size: 1.15rem; font-weight: 700;
                                    color: var(--ek-navy); margin-bottom: 0.5rem;
                                }
                                .ek-modal-body {
                                    font-size: 0.9rem; color: var(--ek-slate); margin-bottom: 1.5rem;
                                }
                                .ek-modal-btns {
                                    display: flex; gap: 0.75rem; justify-content: center;
                                }
                                .ek-modal-btn {
                                    padding: 0.75rem 1.75rem; border-radius: 9999px;
                                    border: none; font-weight: 700; font-size: 0.9rem;
                                    cursor: pointer; transition: all 0.2s ease;
                                }
                                .ek-modal-btn.cancel { background: var(--ek-sky-pale); color: var(--ek-slate); }
                                .ek-modal-btn.cancel:hover { background: #dce8f0; }
                                .ek-modal-btn.confirm-orange { background: var(--ek-orange); color: white; box-shadow: 0 4px 14px rgba(237,139,0,0.4); }
                                .ek-modal-btn.confirm-orange:hover { background: var(--ek-orange-light); }
                                .ek-modal-btn.confirm-red { background: #ef4444; color: white; box-shadow: 0 4px 14px rgba(239,68,68,0.3); }
                                .ek-modal-btn.confirm-red:hover { background: #dc2626; }
                                /* ---- TOAST ---- */
                                #ek-toast-container {
                                    position: fixed; bottom: 1.5rem; right: 1.5rem;
                                    z-index: 10000;
                                    display: flex; flex-direction: column; gap: 0.6rem;
                                    pointer-events: none;
                                }
                                .ek-toast {
                                    display: flex; align-items: center; gap: 0.75rem;
                                    padding: 0.85rem 1.25rem;
                                    background: white;
                                    border-radius: 14px;
                                    box-shadow: 0 8px 30px rgba(0,43,73,0.18);
                                    border-left: 4px solid #22c55e;
                                    font-size: 0.9rem; font-weight: 600;
                                    color: var(--ek-navy);
                                    pointer-events: all;
                                    transform: translateX(120%);
                                    transition: transform 0.35s cubic-bezier(0.4,0,0.2,1);
                                    min-width: 240px; max-width: 340px;
                                }
                                .ek-toast.show { transform: translateX(0); }
                                .ek-toast.error { border-left-color: #ef4444; }
                                .ek-toast.warning { border-left-color: var(--ek-orange); }
                                .ek-toast-icon { font-size: 1.1rem; flex-shrink: 0; }
                                .ek-toast-icon.success { color: #22c55e; }
                                .ek-toast-icon.error   { color: #ef4444; }
                                .ek-toast-icon.warning { color: var(--ek-orange); }
                            </style>

                            <!-- Custom Modal -->
                            <div class="ek-modal-overlay" id="ek-modal-overlay">
                                <div class="ek-modal">
                                    <div class="ek-modal-icon" id="ek-modal-icon"></div>
                                    <div class="ek-modal-title" id="ek-modal-title"></div>
                                    <div class="ek-modal-body"  id="ek-modal-body"></div>
                                    <div class="ek-modal-btns">
                                        <button class="ek-modal-btn cancel" onclick="ekModalCancel()">Cancelar</button>
                                        <button class="ek-modal-btn" id="ek-modal-confirm">Confirmar</button>
                                    </div>
                                </div>
                            </div>
                            <!-- Toast container -->
                            <div id="ek-toast-container"></div>

                            <p style="font-size:0.82rem; color:var(--ek-slate); margin:0.5rem 0 0.25rem;">
                                <i class="bi bi-images"></i> Imágenes adicionales — pasa el cursor para reordenar, establecer principal o eliminar:
                            </p>
                            <div class="ek-img-gallery" id="gallery-adicionales">
                                <?php foreach ($imagenes as $idx => $img): ?>
                                    <div class="ek-img-thumb" id="img-card-<?= $img['id'] ?>" data-id="<?= $img['id'] ?>">
                                        <span class="ek-img-order-badge"><?= $idx + 1 ?></span>
                                        <img src="<?= BASE_URL . e($img['ruta']) ?>" alt="Imagen adicional">
                                        <div class="ek-img-actions">
                                            <button type="button" class="ek-img-act-btn mov"
                                                onclick="moverImg(this, -1)" title="Mover antes">
                                                <i class="bi bi-chevron-left"></i>
                                            </button>
                                            <button type="button" class="ek-img-act-btn mov"
                                                onclick="moverImg(this, 1)" title="Mover después">
                                                <i class="bi bi-chevron-right"></i>
                                            </button>
                                            <button type="button" class="ek-img-act-btn principal"
                                                onclick="hacerPrincipal(<?= $img['id'] ?>, this)"
                                                title="Establecer como imagen principal">
                                                <i class="bi bi-star-fill"></i>
                                            </button>
                                            <button type="button" class="ek-img-act-btn eliminar"
                                                onclick="eliminarImgAdicional(<?= $img['id'] ?>, this)"
                                                title="Eliminar imagen">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="ek-switches">
                        <div class="ek-switch-item">
                            <div class="ek-switch-info">
                                <div class="ek-switch-icon">
                                    <i class="bi bi-eye"></i>
                                </div>
                                <div>
                                    <div class="ek-switch-label">Activo</div>
                                    <div class="ek-switch-desc">Visible en el catálogo</div>
                                </div>
                            </div>
                            <label class="ek-toggle">
                                <input type="checkbox" name="activo" <?= $producto['activo'] ? 'checked' : '' ?>>
                                <span class="ek-toggle-slider"></span>
                            </label>
                        </div>

                        <div class="ek-switch-item">
                            <div class="ek-switch-info">
                                <div class="ek-switch-icon">
                                    <i class="bi bi-star"></i>
                                </div>
                                <div>
                                    <div class="ek-switch-label">Destacado</div>
                                    <div class="ek-switch-desc">Aparece en la página de inicio</div>
                                </div>
                            </div>
                            <label class="ek-toggle">
                                <input type="checkbox" name="destacado" <?= $producto['destacado'] ? 'checked' : '' ?>>
                                <span class="ek-toggle-slider"></span>
                            </label>
                        </div>

                        <div class="ek-switch-item">
                            <div class="ek-switch-info">
                                <div class="ek-switch-icon">
                                    <i class="bi bi-lightning"></i>
                                </div>
                                <div>
                                    <div class="ek-switch-label">Nuevo</div>
                                    <div class="ek-switch-desc">Muestra etiqueta de novedad</div>
                                </div>
                            </div>
                            <label class="ek-toggle">
                                <input type="checkbox" name="nuevo" <?= $producto['nuevo'] ? 'checked' : '' ?>>
                                <span class="ek-toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="ek-submit-section">
                <button type="submit" class="ek-submit-btn">
                    <i class="bi bi-check-lg"></i>
                    Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    // Cargar familias dinámicamente
    function cargarFamilias(categoriaId) {
        const selectFamilia = document.getElementById('familia_id');
        const currentFamilyId = <?= $producto['familia_id'] ?? 'null' ?>;
        selectFamilia.innerHTML = '<option value="">Cargando...</option>';

        if (!categoriaId) {
            selectFamilia.innerHTML = '<option value="">-- Selecciona categoría primero --</option>';
            return;
        }

        fetch('<?= BASE_URL ?>productos/get-familias-ajax/' + categoriaId)
            .then(response => response.json())
            .then(data => {
                selectFamilia.innerHTML = '<option value="">Selecciona...</option>';
                data.forEach(familia => {
                    const option = document.createElement('option');
                    option.value = familia.id;
                    option.textContent = familia.nombre;
                    if (familia.id == currentFamilyId) option.selected = true;
                    selectFamilia.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                selectFamilia.innerHTML = '<option value="">Error al cargar familias</option>';
            });
    }

    // Preview de imagen
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').classList.add('active');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Drag & Drop para el dropzone
    const dropzone = document.getElementById('dropzone');

    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropzone.classList.add('dragover');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropzone.classList.remove('dragover');
        });
    });

    dropzone.addEventListener('drop', (e) => {
        const files = e.dataTransfer.files;
        if (files.length) {
            const input = dropzone.querySelector('input[type="file"]');
            input.files = files;
            previewImage(input);
        }
    });

    const CSRF_TOKEN = '<?= generarToken() ?>';
    const BASE_URL_JS = '<?= BASE_URL ?>';

    /* ================================================
       SISTEMA DE MODAL PERSONALIZADO
       Reemplaza confirm() / alert() del browser
    ================================================ */
    let _modalResolve = null;

    function ekConfirm({ title, body, type = 'warning', confirmText = 'Confirmar' }) {
        return new Promise(resolve => {
            _modalResolve = resolve;
            const overlay = document.getElementById('ek-modal-overlay');
            const iconEl  = document.getElementById('ek-modal-icon');
            const titleEl = document.getElementById('ek-modal-title');
            const bodyEl  = document.getElementById('ek-modal-body');
            const btnConf = document.getElementById('ek-modal-confirm');

            const icons   = { warning: 'bi-exclamation-triangle-fill', danger: 'bi-trash3-fill' };
            const classes = { warning: 'confirm-orange', danger: 'confirm-red' };

            iconEl.className  = 'ek-modal-icon ' + type;
            iconEl.innerHTML  = '<i class="bi ' + (icons[type] || icons.warning) + '"></i>';
            titleEl.textContent = title;
            bodyEl.textContent  = body;
            btnConf.className = 'ek-modal-btn ' + (classes[type] || classes.warning);
            btnConf.textContent = confirmText;
            btnConf.onclick   = () => ekModalConfirm();

            overlay.classList.add('show');
        });
    }

    function ekModalConfirm() {
        document.getElementById('ek-modal-overlay').classList.remove('show');
        if (_modalResolve) { _modalResolve(true); _modalResolve = null; }
    }
    function ekModalCancel() {
        document.getElementById('ek-modal-overlay').classList.remove('show');
        if (_modalResolve) { _modalResolve(false); _modalResolve = null; }
    }
    // Cerrar con clic en backdrop
    document.getElementById('ek-modal-overlay')?.addEventListener('click', function(e) {
        if (e.target === this) ekModalCancel();
    });

    /* ================================================
       SISTEMA DE TOAST / NOTIFICACIONES
    ================================================ */
    function ekToast(message, type = 'success', duration = 3500) {
        const container = document.getElementById('ek-toast-container');
        const toast = document.createElement('div');
        const icons = { success: 'bi-check-circle-fill', error: 'bi-x-circle-fill', warning: 'bi-exclamation-triangle-fill' };
        toast.className = 'ek-toast ' + type;
        toast.innerHTML = '<i class="bi ' + (icons[type] || icons.success) + ' ek-toast-icon ' + type + '"></i>' +
                          '<span>' + message + '</span>';
        container.appendChild(toast);
        requestAnimationFrame(() => { requestAnimationFrame(() => toast.classList.add('show')); });
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 400);
        }, duration);
    }

    /* ================================================
       REORDENAR IMÁGENES
    ================================================ */
    function refreshOrderBadges() {
        const gallery = document.getElementById('gallery-adicionales');
        if (!gallery) return;
        gallery.querySelectorAll('.ek-img-thumb').forEach((card, idx) => {
            const badge = card.querySelector('.ek-img-order-badge');
            if (badge) badge.textContent = idx + 1;
        });
    }

    function moverImg(btn, direccion) {
        const card    = btn.closest('.ek-img-thumb');
        const gallery = document.getElementById('gallery-adicionales');
        const cards   = [...gallery.querySelectorAll('.ek-img-thumb')];
        const idx     = cards.indexOf(card);
        const target  = idx + direccion;

        if (target < 0 || target >= cards.length) return;

        // Mueve el elemento en el DOM
        if (direccion === -1) gallery.insertBefore(card, cards[target]);
        else gallery.insertBefore(cards[target], card);

        refreshOrderBadges();
        guardarOrden();
    }

    function guardarOrden() {
        const gallery = document.getElementById('gallery-adicionales');
        if (!gallery) return;
        const order = [...gallery.querySelectorAll('.ek-img-thumb')].map(c => c.dataset.id);

        const params = new URLSearchParams();
        params.append('csrf_token', CSRF_TOKEN);
        order.forEach(id => params.append('orden[]', id));

        fetch(BASE_URL_JS + 'productos/reordenar-imagenes', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: params.toString()
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) ekToast('Orden guardado', 'success', 1800);
            else ekToast('Error al guardar el orden', 'error');
        })
        .catch(() => ekToast('Error de conexión', 'error'));
    }

    /* ================================================
       HACER PRINCIPAL
    ================================================ */
    async function hacerPrincipal(imgId, btn) {
        const ok = await ekConfirm({
            title: 'Cambiar imagen principal',
            body:  '¿Establecer esta imagen como la imagen principal del producto? La imagen actual quedará como adicional.',
            type:  'warning',
            confirmText: 'Sí, establecer'
        });
        if (!ok) return;

        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i>';

        fetch(BASE_URL_JS + 'productos/establecer-imagen-principal/' + imgId, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'csrf_token=' + encodeURIComponent(CSRF_TOKEN)
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                ekToast('¡Imagen principal actualizada!', 'success');
                setTimeout(() => window.location.reload(), 1200);
            } else {
                ekToast(data.message || 'No se pudo establecer la imagen', 'error');
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-star-fill"></i>';
            }
        })
        .catch(() => {
            ekToast('Error de conexión', 'error');
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-star-fill"></i>';
        });
    }

    /* ================================================
       ELIMINAR IMAGEN
    ================================================ */
    async function eliminarImgAdicional(imgId, btn) {
        const ok = await ekConfirm({
            title: 'Eliminar imagen',
            body:  'Esta acción no se puede deshacer. ¿Confirmas que quieres eliminar esta imagen?',
            type:  'danger',
            confirmText: 'Eliminar'
        });
        if (!ok) return;

        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i>';

        fetch(BASE_URL_JS + 'productos/eliminar-imagen/' + imgId, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'csrf_token=' + encodeURIComponent(CSRF_TOKEN)
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const card = document.getElementById('img-card-' + imgId);
                if (card) {
                    card.style.transition = 'all 0.3s ease';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.8)';
                    setTimeout(() => { card.remove(); refreshOrderBadges(); }, 300);
                }
                ekToast('Imagen eliminada', 'success');
            } else {
                ekToast(data.message || 'No se pudo eliminar la imagen', 'error');
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-trash"></i>';
            }
        })
        .catch(() => {
            ekToast('Error de conexión', 'error');
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-trash"></i>';
        });
    }
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>