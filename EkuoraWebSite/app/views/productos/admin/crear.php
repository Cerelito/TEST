<?php
$pagina_actual = 'productos_admin';
$titulo = 'Crear Producto | Ekuora Admin';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<style>
    /* ============================================
       EKUORA ADMIN - CREAR PRODUCTO
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

    .ek-btn-lg {
        padding: 1.25rem 2.5rem;
        font-size: 1.1rem;
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

    .ek-card-icon.sky {
        background: rgba(122, 153, 172, 0.15);
        color: var(--ek-sky);
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
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--ek-navy);
        margin-bottom: 0.5rem;
    }

    .ek-form-label i {
        color: var(--ek-sky);
        font-size: 1rem;
    }

    .ek-form-label .required {
        color: #ef4444;
        margin-left: 0.25rem;
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

    .ek-form-textarea {
        min-height: 140px;
        resize: vertical;
    }

    .ek-form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    /* ============================================
       FILE UPLOAD / DROPZONE
    ============================================ */
    .ek-dropzone {
        position: relative;
        border: 2px dashed var(--glass-border);
        border-radius: var(--radius-md);
        padding: 2.5rem 2rem;
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
        width: 70px;
        height: 70px;
        margin: 0 auto 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--ek-sky-pale);
        border-radius: 50%;
        color: var(--ek-sky);
        font-size: 1.75rem;
        transition: var(--transition);
    }

    .ek-dropzone:hover .ek-dropzone-icon {
        background: rgba(237, 139, 0, 0.1);
        color: var(--ek-orange);
    }

    .ek-dropzone-text {
        font-size: 1rem;
        color: var(--ek-slate);
        margin-bottom: 0.5rem;
    }

    .ek-dropzone-text strong {
        color: var(--ek-orange);
    }

    .ek-dropzone-hint {
        font-size: 0.85rem;
        color: var(--ek-sky);
    }

    /* Image Preview */
    .ek-image-preview {
        margin-top: 1.5rem;
        text-align: center;
        display: none;
    }

    .ek-image-preview.show {
        display: block;
    }

    .ek-preview-box {
        width: 150px;
        height: 150px;
        margin: 0 auto;
        border-radius: var(--radius-md);
        overflow: hidden;
        border: 3px solid var(--ek-orange);
        box-shadow: 0 8px 24px rgba(237, 139, 0, 0.2);
    }

    .ek-preview-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .ek-preview-label {
        margin-top: 0.75rem;
        font-size: 0.85rem;
        color: var(--ek-orange);
        font-weight: 600;
    }

    /* ============================================
       TOGGLE SWITCHES
    ============================================ */
    .ek-toggle-group {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .ek-toggle-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.25rem;
        background: white;
        border: 2px solid var(--glass-border);
        border-radius: var(--radius-md);
        transition: var(--transition);
    }

    .ek-toggle-item:hover {
        border-color: var(--ek-sky);
    }

    .ek-toggle-item.active {
        border-color: var(--ek-orange);
        background: rgba(237, 139, 0, 0.03);
    }

    .ek-toggle-info {
        display: flex;
        flex-direction: column;
    }

    .ek-toggle-name {
        font-weight: 600;
        color: var(--ek-navy);
        font-size: 0.95rem;
    }

    .ek-toggle-desc {
        font-size: 0.8rem;
        color: var(--ek-slate);
    }

    .ek-toggle {
        position: relative;
        width: 52px;
        height: 28px;
        flex-shrink: 0;
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
        border-radius: 28px;
        transition: var(--transition);
    }

    .ek-toggle-slider::before {
        content: '';
        position: absolute;
        height: 22px;
        width: 22px;
        left: 3px;
        bottom: 3px;
        background: white;
        border-radius: 50%;
        transition: var(--transition);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    }

    .ek-toggle input:checked+.ek-toggle-slider {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    }

    .ek-toggle input:checked+.ek-toggle-slider::before {
        transform: translateX(24px);
    }

    .ek-toggle.orange input:checked+.ek-toggle-slider {
        background: linear-gradient(135deg, var(--ek-orange) 0%, var(--ek-orange-light) 100%);
    }

    .ek-toggle.blue input:checked+.ek-toggle-slider {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    }

    /* ============================================
       PRICE INPUT
    ============================================ */
    .ek-price-input {
        position: relative;
    }

    .ek-price-input .ek-form-input {
        padding-left: 3rem;
    }

    .ek-price-symbol {
        position: absolute;
        left: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        font-weight: 700;
        color: var(--ek-navy);
        font-size: 1.1rem;
    }

    /* ============================================
       RESPONSIVE
    ============================================ */
    @media (max-width: 1200px) {
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

        .ek-admin-hero-content {
            flex-direction: column;
            align-items: flex-start;
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
            <div class="ek-admin-hero-badge">Nuevo Registro</div>
            <h1 class="ek-admin-hero-title">
                <i class="bi bi-plus-circle"></i>
                Crear Nuevo Producto
            </h1>
            <p class="ek-admin-hero-subtitle">Completa los campos para añadir un nuevo artículo al catálogo.</p>
            <nav class="ek-breadcrumb">
                <a href="<?= BASE_URL ?>dashboard"><i class="bi bi-house"></i></a>
                <span>/</span>
                <a href="<?= BASE_URL ?>productos/admin">Productos</a>
                <span>/</span>
                <span class="current">Crear</span>
            </nav>
        </div>
        <a href="<?= BASE_URL ?>productos/admin" class="ek-btn ek-btn-outline">
            <i class="bi bi-arrow-left"></i> Volver al Listado
        </a>
    </div>
</section>

<form action="<?= BASE_URL ?>productos/guardar" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">

    <div class="ek-form-grid">
        <!-- Columna Principal -->
        <div class="ek-main-column">
            <!-- Información Básica -->
            <div class="ek-card ek-fade-up" style="animation-delay: 0.1s;">
                <div class="ek-card-header">
                    <div class="ek-card-icon">
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
                        <input type="text" name="nombre" class="ek-form-input"
                            placeholder="Ej: Botella Térmica Pro 500ml" required>
                    </div>

                    <div class="ek-form-row">
                        <div class="ek-form-group">
                            <label class="ek-form-label">
                                <i class="bi bi-folder2"></i>
                                Categoría <span class="required">*</span>
                            </label>
                            <select name="categoria_id" class="ek-form-select" required
                                onchange="cargarFamilias(this.value)">
                                <option value="">Selecciona categoría...</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?= $cat['id'] ?>">
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
                            <select id="familia_id" name="familia_id" class="ek-form-select" required>
                                <option value="">-- Selecciona categoría primero --</option>
                            </select>
                        </div>
                    </div>

                    <div class="ek-form-row">
                        <div class="ek-form-group">
                            <label class="ek-form-label">
                                <i class="bi bi-hash"></i>
                                SKU / Referencia
                            </label>
                            <input type="text" name="sku" class="ek-form-input" placeholder="EK-001">
                        </div>
                        <div class="ek-form-group">
                            <label class="ek-form-label">
                                <i class="bi bi-bookmark-check"></i>
                                Marca
                            </label>
                            <input type="text" name="marca" class="ek-form-input" placeholder="Ekuora">
                        </div>
                    </div>

                    <div class="ek-form-group">
                        <label class="ek-form-label">
                            <i class="bi bi-text-left"></i>
                            Descripción Corta
                        </label>
                        <input type="text" name="descripcion_corta" class="ek-form-input"
                            placeholder="Breve resumen para el catálogo...">
                    </div>

                    <div class="ek-form-group">
                        <label class="ek-form-label">
                            <i class="bi bi-card-text"></i>
                            Descripción Detallada
                        </label>
                        <textarea name="descripcion" class="ek-form-textarea"
                            placeholder="Especificaciones, materiales, uso, dimensiones..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Lateral -->
        <div class="ek-side-column">
            <!-- Imagen Principal -->
            <div class="ek-card ek-fade-up" style="animation-delay: 0.15s;">
                <div class="ek-card-header">
                    <div class="ek-card-icon sky">
                        <i class="bi bi-image"></i>
                    </div>
                    <div>
                        <h3 class="ek-card-title">Imagen Principal</h3>
                        <p class="ek-card-subtitle">JPG, PNG, WebP (Máx. 2MB)</p>
                    </div>
                </div>
                <div class="ek-card-body">
                    <div class="ek-dropzone" id="dropzone">
                        <input type="file" name="imagen_principal" accept="image/*" id="file-input">
                        <div class="ek-dropzone-icon">
                            <i class="bi bi-cloud-arrow-up"></i>
                        </div>
                        <div class="ek-dropzone-text">
                            <strong>Clic para subir</strong> o arrastra aquí
                        </div>
                        <div class="ek-dropzone-hint">Recomendado: 800x800px</div>
                    </div>
                    <div class="ek-image-preview" id="image-preview">
                        <div class="ek-preview-box">
                            <img src="" alt="Preview" id="preview-img">
                        </div>
                        <div class="ek-preview-label">
                            <i class="bi bi-check-circle"></i> Imagen seleccionada
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
                        <p class="ek-card-subtitle">Estado y visualización</p>
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
                    </div>

                    <div class="ek-toggle-group">
                        <div class="ek-toggle-item active" id="toggle-activo">
                            <div class="ek-toggle-info">
                                <span class="ek-toggle-name">Activo</span>
                                <span class="ek-toggle-desc">Visible en el catálogo</span>
                            </div>
                            <label class="ek-toggle">
                                <input type="checkbox" name="activo" value="1" checked
                                    onchange="toggleItem(this, 'toggle-activo')">
                                <span class="ek-toggle-slider"></span>
                            </label>
                        </div>

                        <div class="ek-toggle-item" id="toggle-destacado">
                            <div class="ek-toggle-info">
                                <span class="ek-toggle-name">Destacado</span>
                                <span class="ek-toggle-desc">Aparece en inicio</span>
                            </div>
                            <label class="ek-toggle orange">
                                <input type="checkbox" name="destacado" value="1"
                                    onchange="toggleItem(this, 'toggle-destacado')">
                                <span class="ek-toggle-slider"></span>
                            </label>
                        </div>

                        <div class="ek-toggle-item" id="toggle-nuevo">
                            <div class="ek-toggle-info">
                                <span class="ek-toggle-name">Nuevo</span>
                                <span class="ek-toggle-desc">Etiqueta de novedad</span>
                            </div>
                            <label class="ek-toggle blue">
                                <input type="checkbox" name="nuevo" value="1"
                                    onchange="toggleItem(this, 'toggle-nuevo')">
                                <span class="ek-toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botón Guardar -->
            <div class="ek-fade-up" style="animation-delay: 0.25s; margin-top: 1.5rem;">
                <button type="submit" class="ek-btn ek-btn-primary ek-btn-lg" style="width: 100%;">
                    <i class="bi bi-plus-circle"></i> Crear Producto
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    function cargarFamilias(categoriaId) {
        const selectFamilia = document.getElementById('familia_id');
        selectFamilia.innerHTML = '<option value="">Cargando...</option>';

        if (!categoriaId) {
            selectFamilia.innerHTML = '<option value="">-- Selecciona categoría primero --</option>';
            return;
        }

        fetch('<?= BASE_URL ?>productos/get-familias-ajax/' + categoriaId)
            .then(response => response.json())
            .then(data => {
                selectFamilia.innerHTML = '<option value="">Selecciona familia...</option>';
                data.forEach(familia => {
                    const option = document.createElement('option');
                    option.value = familia.id;
                    option.textContent = familia.nombre;
                    selectFamilia.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                selectFamilia.innerHTML = '<option value="">Error al cargar</option>';
            });
    }

    function toggleItem(checkbox, containerId) {
        const container = document.getElementById(containerId);
        if (checkbox.checked) {
            container.classList.add('active');
        } else {
            container.classList.remove('active');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('file-input');
        const imagePreview = document.getElementById('image-preview');
        const previewImg = document.getElementById('preview-img');

        // Drag & Drop
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, e => {
                e.preventDefault();
                e.stopPropagation();
            }, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, () => dropzone.classList.add('dragover'), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, () => dropzone.classList.remove('dragover'), false);
        });

        dropzone.addEventListener('drop', e => {
            const files = e.dataTransfer.files;
            if (files.length) {
                fileInput.files = files;
                previewFile(files[0]);
            }
        }, false);

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
                imagePreview.classList.add('show');
            }
            reader.readAsDataURL(file);
        }
    });
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>