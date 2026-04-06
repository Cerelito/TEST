<?php
$pagina_actual = 'productos';
$titulo = 'Importar Productos Masivamente';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<style>
    /* ============================================
       EKUORA ADMIN - IMPORTACIÓN MASIVA
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

    .ek-btn-sm {
        padding: 0.6rem 1.25rem;
        font-size: 0.9rem;
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
        padding: 1.5rem;
        border-bottom: 1px solid var(--glass-border);
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

    .ek-card-icon.green {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
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

    /* Info Card */
    .ek-info-card {
        background: linear-gradient(135deg, rgba(0, 43, 73, 0.05) 0%, transparent 100%);
        border-left: 4px solid var(--ek-navy);
    }

    .ek-info-card .ek-card-body ol {
        margin: 0;
        padding-left: 1.25rem;
        color: var(--ek-slate);
        line-height: 2;
    }

    .ek-info-card .ek-card-body ol li {
        margin-bottom: 0.5rem;
    }

    .ek-info-card .ek-card-body ul {
        margin: 0.5rem 0;
        padding-left: 1.5rem;
        color: var(--ek-sky);
    }

    .ek-info-card .ek-card-body ul li {
        margin-bottom: 0.35rem;
        font-size: 0.9rem;
    }

    /* Download Section */
    .ek-download-section {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        padding: 1.5rem;
        background: linear-gradient(135deg, rgba(237, 139, 0, 0.1) 0%, rgba(237, 139, 0, 0.05) 100%);
        border-radius: var(--radius-lg);
        border: 1px dashed var(--ek-orange);
        margin-bottom: 1.5rem;
    }

    .ek-download-icon {
        width: 64px;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--ek-orange);
        border-radius: var(--radius-md);
        color: white;
        font-size: 1.75rem;
        flex-shrink: 0;
    }

    .ek-download-info h4 {
        font-family: 'Outfit', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--ek-navy);
        margin: 0 0 0.5rem;
    }

    .ek-download-info p {
        font-size: 0.9rem;
        color: var(--ek-slate);
        margin: 0 0 1rem;
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
    }

    .ek-table tbody tr {
        border-bottom: 1px solid var(--glass-border);
        transition: var(--transition);
    }

    .ek-table tbody tr:hover {
        background: rgba(237, 139, 0, 0.05);
    }

    .ek-table tbody td {
        padding: 1rem 1.25rem;
        vertical-align: middle;
        font-size: 0.95rem;
        color: var(--ek-slate);
    }

    .ek-table tbody td strong {
        color: var(--ek-navy);
    }

    .ek-table tbody td code {
        background: var(--ek-sky-pale);
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.85rem;
        color: var(--ek-navy);
    }

    .ek-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.75rem;
        border-radius: var(--radius-full);
        font-size: 0.75rem;
        font-weight: 600;
    }

    .ek-badge.required {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .ek-badge.optional {
        background: rgba(122, 153, 172, 0.2);
        color: var(--ek-slate);
    }

    /* ============================================
       CATEGORIES GRID
    ============================================ */
    .ek-categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
    }

    .ek-category-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.25rem;
        background: white;
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-md);
        transition: var(--transition);
    }

    .ek-category-item:hover {
        border-color: var(--ek-orange);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 43, 73, 0.1);
    }

    .ek-category-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--ek-sky-pale);
        border-radius: var(--radius-sm);
        color: var(--ek-orange);
        font-size: 1.1rem;
    }

    .ek-category-info h5 {
        font-family: 'Outfit', sans-serif;
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--ek-navy);
        margin: 0 0 0.25rem;
    }

    .ek-category-info code {
        font-size: 0.8rem;
        color: var(--ek-orange);
        background: rgba(237, 139, 0, 0.1);
        padding: 0.15rem 0.5rem;
        border-radius: 4px;
    }

    .ek-category-note {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 1rem;
        padding: 1rem;
        background: var(--ek-sky-pale);
        border-radius: var(--radius-md);
        font-size: 0.9rem;
        color: var(--ek-slate);
    }

    .ek-category-note i {
        color: var(--ek-orange);
    }

    .ek-category-note a {
        color: var(--ek-orange);
        font-weight: 600;
        text-decoration: none;
    }

    .ek-category-note a:hover {
        text-decoration: underline;
    }

    /* ============================================
       UPLOAD FORM
    ============================================ */
    .ek-upload-card {
        border: 2px solid var(--ek-orange);
        background: linear-gradient(135deg, rgba(237, 139, 0, 0.05) 0%, white 100%);
    }

    .ek-upload-card .ek-card-header {
        background: linear-gradient(135deg, rgba(237, 139, 0, 0.1) 0%, transparent 100%);
    }

    .ek-file-upload {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .ek-file-label {
        display: block;
        font-weight: 600;
        color: var(--ek-navy);
        margin-bottom: 0.75rem;
    }

    .ek-file-label i {
        color: var(--ek-orange);
        margin-right: 0.5rem;
    }

    .ek-file-dropzone {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem 2rem;
        background: white;
        border: 2px dashed var(--glass-border);
        border-radius: var(--radius-lg);
        cursor: pointer;
        transition: var(--transition);
        text-align: center;
    }

    .ek-file-dropzone:hover {
        border-color: var(--ek-orange);
        background: rgba(237, 139, 0, 0.02);
    }

    .ek-file-dropzone.dragover {
        border-color: var(--ek-orange);
        background: rgba(237, 139, 0, 0.05);
    }

    .ek-file-dropzone-icon {
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--ek-sky-pale);
        border-radius: 50%;
        color: var(--ek-orange);
        font-size: 2.5rem;
        margin-bottom: 1.5rem;
    }

    .ek-file-dropzone h4 {
        font-family: 'Outfit', sans-serif;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--ek-navy);
        margin: 0 0 0.5rem;
    }

    .ek-file-dropzone p {
        font-size: 0.9rem;
        color: var(--ek-slate);
        margin: 0 0 1rem;
    }

    .ek-file-dropzone span {
        font-size: 0.85rem;
        color: var(--ek-sky);
    }

    .ek-file-input {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
    }

    .ek-file-name {
        display: none;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.25rem;
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.3);
        border-radius: var(--radius-md);
        margin-top: 1rem;
    }

    .ek-file-name.active {
        display: flex;
    }

    .ek-file-name i {
        color: #22c55e;
        font-size: 1.5rem;
    }

    .ek-file-name span {
        font-weight: 600;
        color: var(--ek-navy);
    }

    /* Form Actions */
    .ek-form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1.5rem;
        border-top: 1px solid var(--glass-border);
        margin-top: 1.5rem;
    }

    /* ============================================
       ERROR PANEL
    ============================================ */
    .ek-error-card {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(239, 68, 68, 0.05) 100%);
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-left: 4px solid #ef4444;
    }

    .ek-error-card .ek-card-icon {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
    }

    .ek-error-card .ek-card-title {
        color: #dc2626;
    }

    .ek-error-list {
        max-height: 300px;
        overflow-y: auto;
        margin: 0;
        padding-left: 1.5rem;
    }

    .ek-error-list li {
        color: #7f1d1d;
        padding: 0.5rem 0;
        border-bottom: 1px dashed rgba(239, 68, 68, 0.2);
    }

    .ek-error-list li:last-child {
        border-bottom: none;
    }

    /* ============================================
       RESPONSIVE
    ============================================ */
    @media (max-width: 992px) {
        .ek-admin-hero-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .ek-download-section {
            flex-direction: column;
            text-align: center;
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

        .ek-form-actions {
            flex-direction: column;
            gap: 1rem;
        }

        .ek-form-actions .ek-btn {
            width: 100%;
            justify-content: center;
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
            <div class="ek-admin-hero-badge">Herramienta Avanzada</div>
            <h1 class="ek-admin-hero-title">
                <i class="bi bi-cloud-arrow-up"></i>
                Importación Masiva de Productos
            </h1>
            <p class="ek-admin-hero-subtitle">Carga múltiples productos de forma rápida y eficiente mediante archivo
                CSV.</p>
        </div>
        <a href="<?= BASE_URL ?>productos/admin" class="ek-btn ek-btn-outline">
            <i class="bi bi-arrow-left"></i> Volver a Productos
        </a>
    </div>
</section>

<!-- Instrucciones -->
<div class="ek-card ek-info-card ek-fade-up" style="animation-delay: 0.1s;">
    <div class="ek-card-header">
        <div class="ek-card-icon navy">
            <i class="bi bi-info-circle"></i>
        </div>
        <div>
            <h3 class="ek-card-title">Instrucciones de Importación</h3>
            <p class="ek-card-subtitle">Sigue estos pasos para importar tus productos correctamente</p>
        </div>
    </div>
    <div class="ek-card-body">
        <ol>
            <li><strong>Descarga la plantilla CSV</strong> usando el botón de abajo</li>
            <li><strong>Llena el archivo</strong> con los datos de tus productos</li>
            <li>Las <strong>imágenes</strong> pueden ser:
                <ul>
                    <li>Rutas absolutas del servidor (ej: /var/www/imagenes/producto1.jpg)</li>
                    <li>URLs de internet (ej: https://ejemplo.com/imagen.jpg)</li>
                    <li>Rutas relativas dentro de uploads (ej: uploads_privados/productos/img.jpg)</li>
                    <li>Dejar vacío si no tienes imagen</li>
                </ul>
            </li>
            <li>Las <strong>categorías</strong> deben existir previamente (usa el slug de la categoría)</li>
            <li>Si el <strong>SKU</strong> ya existe, el producto se <strong>actualizará</strong> en lugar de crearse
            </li>
            <li><strong>Sube el archivo CSV</strong> y haz clic en Importar</li>
        </ol>
    </div>
</div>

<!-- Descargar Plantilla -->
<div class="ek-download-section ek-fade-up" style="animation-delay: 0.15s;">
    <div class="ek-download-icon">
        <i class="bi bi-file-earmark-spreadsheet"></i>
    </div>
    <div class="ek-download-info">
        <h4>Plantilla CSV</h4>
        <p>El archivo incluye 3 productos de ejemplo para que veas el formato correcto</p>
        <a href="<?= BASE_URL ?>productos/descargar-plantilla" class="ek-btn ek-btn-primary ek-btn-sm">
            <i class="bi bi-download"></i> Descargar Plantilla CSV
        </a>
    </div>
</div>

<!-- Formato del CSV -->
<div class="ek-card ek-fade-up" style="animation-delay: 0.2s;">
    <div class="ek-card-header">
        <div class="ek-card-icon orange">
            <i class="bi bi-table"></i>
        </div>
        <div>
            <h3 class="ek-card-title">Columnas del CSV</h3>
            <p class="ek-card-subtitle">Referencia de campos disponibles para la importación</p>
        </div>
    </div>
    <div class="ek-table-container">
        <table class="ek-table">
            <thead>
                <tr>
                    <th>Columna</th>
                    <th>Descripción</th>
                    <th>Requerido</th>
                    <th>Ejemplo</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>nombre</strong></td>
                    <td>Nombre del producto</td>
                    <td><span class="ek-badge required">Sí</span></td>
                    <td><code>Organizador de Especias</code></td>
                </tr>
                <tr>
                    <td><strong>categoria_slug</strong></td>
                    <td>Slug de la categoría</td>
                    <td><span class="ek-badge required">Sí</span></td>
                    <td><code>cook-bake</code></td>
                </tr>
                <tr>
                    <td><strong>descripcion</strong></td>
                    <td>Descripción completa</td>
                    <td><span class="ek-badge optional">No</span></td>
                    <td>Organizador expandible para cajones...</td>
                </tr>
                <tr>
                    <td><strong>descripcion_corta</strong></td>
                    <td>Descripción breve para la tarjeta</td>
                    <td><span class="ek-badge optional">No</span></td>
                    <td>Organizador expandible</td>
                </tr>
                <tr>
                    <td><strong>ruta_imagen</strong></td>
                    <td>Ruta o URL de la imagen</td>
                    <td><span class="ek-badge optional">No</span></td>
                    <td><code>/ruta/imagen.jpg</code> o <code>https://...</code></td>
                </tr>
                <tr>
                    <td><strong>sku</strong></td>
                    <td>Código único del producto</td>
                    <td><span class="ek-badge optional">No</span></td>
                    <td><code>ORG-001</code></td>
                </tr>
                <tr>
                    <td><strong>marca</strong></td>
                    <td>Marca del producto</td>
                    <td><span class="ek-badge optional">No</span></td>
                    <td>Ekuora</td>
                </tr>
                <tr>
                    <td><strong>orden</strong></td>
                    <td>Orden de visualización</td>
                    <td><span class="ek-badge optional">No</span></td>
                    <td><code>0</code></td>
                </tr>
                <tr>
                    <td><strong>activo</strong></td>
                    <td>1 o si = activo, 0 o no = inactivo</td>
                    <td><span class="ek-badge optional">No</span></td>
                    <td><code>1</code></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Categorías Disponibles -->
<div class="ek-card ek-fade-up" style="animation-delay: 0.25s;">
    <div class="ek-card-header">
        <div class="ek-card-icon green">
            <i class="bi bi-grid-3x3-gap"></i>
        </div>
        <div>
            <h3 class="ek-card-title">Categorías Disponibles</h3>
            <p class="ek-card-subtitle">Usa estos slugs en la columna <code
                    style="background: var(--ek-sky-pale); padding: 0.15rem 0.5rem; border-radius: 4px;">categoria_slug</code>
            </p>
        </div>
    </div>
    <div class="ek-card-body">
        <div class="ek-categories-grid">
            <?php foreach ($categorias as $cat): ?>
                <div class="ek-category-item">
                    <div class="ek-category-icon">
                        <i class="bi <?= e($cat['icono'] ?? 'bi-folder') ?>"></i>
                    </div>
                    <div class="ek-category-info">
                        <h5>
                            <?= e($cat['nombre']) ?>
                        </h5>
                        <code><?= e($cat['slug']) ?></code>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="ek-category-note">
            <i class="bi bi-exclamation-circle"></i>
            <span>Si necesitas crear nuevas categorías, hazlo primero en <a
                    href="<?= BASE_URL ?>productos/categorias">Gestión de Categorías</a></span>
        </div>
    </div>
</div>

<!-- Formulario de Importación -->
<div class="ek-card ek-upload-card ek-fade-up" style="animation-delay: 0.3s;">
    <div class="ek-card-header">
        <div class="ek-card-icon orange">
            <i class="bi bi-upload"></i>
        </div>
        <div>
            <h3 class="ek-card-title">Subir Archivo CSV</h3>
            <p class="ek-card-subtitle">Selecciona tu archivo con los productos a importar</p>
        </div>
    </div>
    <div class="ek-card-body">
        <form action="<?= BASE_URL ?>productos/procesar-importacion" method="POST" enctype="multipart/form-data"
            data-loading>
            <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">

            <div class="ek-file-upload">
                <label class="ek-file-label">
                    <i class="bi bi-file-earmark-spreadsheet"></i>
                    Archivo CSV *
                </label>

                <div class="ek-file-dropzone" id="dropzone">
                    <div class="ek-file-dropzone-icon">
                        <i class="bi bi-cloud-arrow-up"></i>
                    </div>
                    <h4>Arrastra tu archivo aquí</h4>
                    <p>o haz clic para seleccionar</p>
                    <span>Solo archivos CSV (separados por comas). Máximo 5MB.</span>

                    <input type="file" id="archivo_csv" name="archivo_csv" class="ek-file-input" accept=".csv,.txt"
                        required>
                </div>

                <div class="ek-file-name" id="fileName">
                    <i class="bi bi-file-earmark-check"></i>
                    <span id="fileNameText">archivo.csv</span>
                </div>
            </div>

            <div class="ek-form-actions">
                <a href="<?= BASE_URL ?>productos/admin" class="ek-btn ek-btn-secondary">
                    <i class="bi bi-x-lg"></i> Cancelar
                </a>
                <button type="submit" class="ek-btn ek-btn-primary">
                    <i class="bi bi-cloud-upload"></i> Importar Productos
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Errores de importación anterior -->
<?php if (isset($_SESSION['import_errors']) && !empty($_SESSION['import_errors'])): ?>
    <div class="ek-card ek-error-card ek-fade-up" style="animation-delay: 0.35s;">
        <div class="ek-card-header">
            <div class="ek-card-icon">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div>
                <h3 class="ek-card-title">Errores en la última importación</h3>
                <p class="ek-card-subtitle">Revisa y corrige los siguientes errores</p>
            </div>
        </div>
        <div class="ek-card-body">
            <ul class="ek-error-list">
                <?php foreach ($_SESSION['import_errors'] as $error): ?>
                    <li>
                        <?= htmlspecialchars($error) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php unset($_SESSION['import_errors']); ?>
<?php endif; ?>

<script>
    // File Upload Handling
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('archivo_csv');
    const fileNameDiv = document.getElementById('fileName');
    const fileNameText = document.getElementById('fileNameText');

    // Drag & Drop
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
            fileInput.files = files;
            updateFileName(files[0].name);
        }
    });

    // File Input Change
    fileInput.addEventListener('change', function () {
        if (this.files.length) {
            updateFileName(this.files[0].name);
        }
    });

    function updateFileName(name) {
        fileNameText.textContent = name;
        fileNameDiv.classList.add('active');
    }
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>