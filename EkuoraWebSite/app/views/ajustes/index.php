<?php
/**
 * Ajustes del Sitio - Ekuora Ultra Glass Pantone
 */
$pagina_actual = 'ajustes';
$titulo = 'Ajustes del Sitio | ' . APP_NAME;
require_once VIEWS_PATH . 'layouts/header.php';
?>

<style>
    /* ============================================
       EKUORA AJUSTES - ULTRA GLASS PANTONE
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
        --ek-green: #22c55e;

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
       HERO AJUSTES
    ============================================ */
    .ek-hero {
        background: linear-gradient(135deg, var(--ek-navy) 0%, var(--ek-navy-light) 100%);
        border-radius: 0 0 var(--radius-xl) var(--radius-xl);
        padding: 3rem 2rem;
        margin: 0 0 3rem;
        position: relative;
        overflow: hidden;
    }

    .ek-hero::before {
        content: '';
        position: absolute;
        top: -100px;
        right: -100px;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(237, 139, 0, 0.3) 0%, transparent 70%);
        border-radius: 50%;
    }

    .ek-hero::after {
        content: '';
        position: absolute;
        bottom: -50px;
        left: 20%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(122, 153, 172, 0.2) 0%, transparent 70%);
        border-radius: 50%;
    }

    .ek-hero-content {
        position: relative;
        z-index: 10;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 2rem;
    }

    .ek-hero-badge {
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

    .ek-hero-badge::before {
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

    .ek-hero-title {
        font-family: 'Outfit', sans-serif;
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .ek-hero-title i {
        color: var(--ek-orange);
    }

    .ek-hero-subtitle {
        font-size: 1.1rem;
        color: var(--ek-sky-light);
    }

    .ek-hero-icon-box {
        display: flex;
        align-items: center;
        gap: 1rem;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: var(--radius-lg);
        padding: 1rem 1.5rem;
    }

    .ek-hero-icon-box-icon {
        width: 56px;
        height: 56px;
        background: var(--ek-orange);
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        box-shadow: 0 8px 20px rgba(237, 139, 0, 0.4);
    }

    .ek-hero-icon-box-text strong {
        display: block;
        color: white;
        font-size: 1rem;
    }

    .ek-hero-icon-box-text span {
        color: var(--ek-sky-light);
        font-size: 0.85rem;
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
        margin-bottom: 2rem;
        transition: var(--transition);
    }

    .ek-card:hover {
        box-shadow: 0 12px 40px rgba(0, 43, 73, 0.15);
    }

    .ek-card-header {
        background: linear-gradient(135deg, rgba(0, 43, 73, 0.05) 0%, rgba(122, 153, 172, 0.05) 100%);
        padding: 1.5rem;
        border-bottom: 1px solid var(--glass-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .ek-card-header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .ek-icon {
        width: 48px;
        height: 48px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .ek-icon.orange {
        background: rgba(237, 139, 0, 0.15);
        color: var(--ek-orange);
    }

    .ek-icon.navy {
        background: rgba(0, 43, 73, 0.15);
        color: var(--ek-navy);
    }

    .ek-icon.sky {
        background: rgba(122, 153, 172, 0.2);
        color: var(--ek-sky);
    }

    .ek-icon.green {
        background: rgba(34, 197, 94, 0.15);
        color: var(--ek-green);
    }

    .ek-card-header-left h3 {
        font-family: 'Outfit', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--ek-navy);
        margin: 0 0 0.25rem;
    }

    .ek-card-header-left p {
        font-size: 0.85rem;
        color: var(--ek-slate);
        margin: 0;
    }

    .ek-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: var(--radius-full);
        font-size: 0.8rem;
        font-weight: 600;
    }

    .ek-badge.orange {
        background: var(--ek-orange);
        color: white;
    }

    .ek-badge.navy {
        background: var(--ek-navy);
        color: white;
    }

    .ek-badge.sky {
        background: var(--ek-sky);
        color: white;
    }

    .ek-card-body {
        padding: 1.5rem;
    }

    /* ============================================
       FORMULARIO
    ============================================ */
    .ek-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .ek-form-fields {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .ek-form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .ek-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--ek-navy);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .ek-label i {
        color: var(--ek-sky);
    }

    .ek-input {
        padding: 0.85rem 1rem;
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-md);
        background: white;
        color: var(--ek-navy);
        font-size: 0.95rem;
        transition: var(--transition);
        width: 100%;
    }

    .ek-input:focus {
        outline: none;
        border-color: var(--ek-orange);
        box-shadow: 0 0 0 3px rgba(237, 139, 0, 0.15);
    }

    .ek-textarea {
        resize: vertical;
        min-height: 100px;
    }

    /* ============================================
       UPLOAD ZONE
    ============================================ */
    .ek-upload-zone {
        position: relative;
        border: 2px dashed var(--glass-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: var(--transition);
        background: var(--ek-sky-pale);
        min-height: 250px;
    }

    .ek-upload-zone:hover {
        border-color: var(--ek-orange);
    }

    .ek-upload-zone.has-image .ek-upload-empty {
        display: none;
    }

    .ek-upload-zone .ek-upload-preview {
        display: none;
    }

    .ek-upload-zone.has-image .ek-upload-preview {
        display: block;
    }

    .ek-upload-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        cursor: pointer;
        min-height: 250px;
    }

    .ek-upload-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, var(--ek-orange), var(--ek-orange-light));
        box-shadow: 0 8px 25px rgba(237, 139, 0, 0.3);
    }

    .ek-upload-icon i {
        font-size: 1.75rem;
        color: white;
    }

    .ek-upload-text {
        text-align: center;
    }

    .ek-upload-text strong {
        display: block;
        color: var(--ek-navy);
        margin-bottom: 4px;
    }

    .ek-upload-text span {
        font-size: 0.8rem;
        color: var(--ek-slate);
    }

    .ek-upload-preview {
        position: relative;
        min-height: 250px;
    }

    .ek-upload-preview img {
        width: 100%;
        height: 250px;
        object-fit: cover;
    }

    .ek-upload-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 43, 73, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        opacity: 0;
        transition: var(--transition);
    }

    .ek-upload-preview:hover .ek-upload-overlay {
        opacity: 1;
    }

    .ek-btn-icon {
        width: 48px;
        height: 48px;
        border: none;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1.1rem;
        transition: var(--transition);
    }

    .ek-btn-icon.light {
        background: white;
        color: var(--ek-navy);
    }

    .ek-btn-icon.danger {
        background: #f43f5e;
        color: white;
    }

    .ek-btn-icon:hover {
        transform: scale(1.1);
    }

    /* ============================================
       DOCK (BARRA FLOTANTE)
    ============================================ */
    .ek-dock {
        position: sticky;
        bottom: 1.5rem;
        margin-top: 1.5rem;
        background: var(--glass-bg);
        backdrop-filter: var(--glass-blur);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-lg);
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: var(--glass-shadow);
    }

    .ek-dock-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--ek-slate);
        font-size: 0.85rem;
    }

    .ek-dock-info i {
        color: var(--ek-sky);
    }

    .ek-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.85rem 1.5rem;
        border-radius: var(--radius-full);
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: var(--transition);
        cursor: pointer;
        border: none;
    }

    .ek-btn-primary {
        background: linear-gradient(135deg, var(--ek-orange), var(--ek-orange-light));
        color: white;
        box-shadow: 0 8px 20px rgba(237, 139, 0, 0.3);
    }

    .ek-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(237, 139, 0, 0.4);
        color: white;
    }

    .ek-btn-navy {
        background: linear-gradient(135deg, var(--ek-navy), var(--ek-navy-light));
        color: white;
        box-shadow: 0 8px 20px rgba(0, 43, 73, 0.3);
    }

    .ek-btn-navy:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(0, 43, 73, 0.4);
        color: white;
    }

    /* ============================================
       ANIMACIONES
    ============================================ */
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

    /* ============================================
       RESPONSIVE
    ============================================ */
    @media (max-width: 768px) {
        .ek-hero {
            padding: 2rem 1.5rem;
            margin: -1rem -1rem 1.5rem;
        }

        .ek-hero-title {
            font-size: 1.75rem;
        }

        .ek-hero-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .ek-grid-2 {
            grid-template-columns: 1fr;
        }

        .ek-dock {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .ek-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<!-- Hero Section -->
<section class="ek-hero ek-fade-up">
    <div class="ek-hero-content">
        <div>
            <div class="ek-hero-badge">Configuracion Global</div>
            <h1 class="ek-hero-title">
                <i class="bi bi-sliders"></i>
                Ajustes del Sitio
            </h1>
            <p class="ek-hero-subtitle">Gestiona las secciones especiales, colores y configuracion del catalogo.</p>
        </div>
        <div class="ek-hero-icon-box">
            <div class="ek-hero-icon-box-icon">
                <i class="bi bi-gear-wide-connected"></i>
            </div>
            <div class="ek-hero-icon-box-text">
                <strong>Panel de Control</strong>
                <span>Configuracion Avanzada</span>
            </div>
        </div>
    </div>
</section>

<form action="<?= BASE_URL ?>ajustes/guardar" method="POST" enctype="multipart/form-data" id="formAjustes">
    <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">

    <!-- Seccion Promocional -->
    <div class="ek-card ek-fade-up" style="animation-delay: 0.1s;">
        <div class="ek-card-header">
            <div class="ek-card-header-left">
                <div class="ek-icon orange"><i class="bi bi-megaphone-fill"></i></div>
                <div>
                    <h3>Seccion Promocional (Banner Home)</h3>
                    <p>Mensaje principal que aparece en la mitad de la pagina de inicio</p>
                </div>
            </div>
            <div class="ek-badge orange"><i class="bi bi-star-fill"></i> Banner Home</div>
        </div>
        <div class="ek-card-body">
            <div class="ek-grid-2">
                <div class="ek-form-fields">
                    <div class="ek-form-group">
                        <label class="ek-label"><i class="bi bi-type-h1"></i> Titulo Principal</label>
                        <input type="text" name="promo_titulo" class="ek-input"
                            value="<?= e($ajustes['promo_titulo'] ?? '') ?>" placeholder="Ej. Fresh for Life">
                    </div>

                    <div class="ek-form-group">
                        <label class="ek-label"><i class="bi bi-text-paragraph"></i> Subtitulo / Descripcion</label>
                        <textarea name="promo_subtitulo" class="ek-input ek-textarea" rows="3"
                            placeholder="Descripcion de la promocion..."><?= e($ajustes['promo_subtitulo'] ?? '') ?></textarea>
                    </div>

                    <div class="ek-grid-2">
                        <div class="ek-form-group">
                            <label class="ek-label"><i class="bi bi-cursor-fill"></i> Texto del Boton</label>
                            <input type="text" name="promo_texto_boton" class="ek-input"
                                value="<?= e($ajustes['promo_texto_boton'] ?? '') ?>" placeholder="Ej. VER AHORA">
                        </div>
                        <div class="ek-form-group">
                            <label class="ek-label"><i class="bi bi-link-45deg"></i> Enlace del Boton</label>
                            <input type="text" name="promo_enlace" class="ek-input"
                                value="<?= e($ajustes['promo_enlace'] ?? '') ?>"
                                placeholder="Ej. productos/categoria/sale">
                        </div>
                    </div>
                </div>

                <div class="ek-form-group">
                    <label class="ek-label"><i class="bi bi-image-fill"></i> Imagen de Promocion</label>
                    <div class="ek-upload-zone <?= !empty($ajustes['promo_imagen']) ? 'has-image' : '' ?>"
                        id="upload-zone-promo">
                        <div class="ek-upload-empty" onclick="document.getElementById('promo_imagen').click()">
                            <div class="ek-upload-icon">
                                <i class="bi bi-cloud-arrow-up-fill"></i>
                            </div>
                            <div class="ek-upload-text">
                                <strong>Haz clic para subir</strong>
                                <span>PNG, JPG o WebP hasta 5MB</span>
                            </div>
                            <input type="file" id="promo_imagen" name="promo_imagen" hidden accept="image/*"
                                onchange="previewImage(this, 'promo')">
                        </div>
                        <div class="ek-upload-preview">
                            <img src="<?= e(asset($ajustes['promo_imagen'] ?? '')) ?>" alt="Vista previa"
                                id="preview-img-promo">
                            <div class="ek-upload-overlay">
                                <button type="button" class="ek-btn-icon light"
                                    onclick="document.getElementById('promo_imagen').click()">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <button type="button" class="ek-btn-icon danger" onclick="removeImage('promo')">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Personalizacion Grafica -->
    <div class="ek-card ek-fade-up" style="animation-delay: 0.15s;">
        <div class="ek-card-header">
            <div class="ek-card-header-left">
                <div class="ek-icon green"><i class="bi bi-palette-fill"></i></div>
                <div>
                    <h3>Personalización Gráfica</h3>
                    <p>Define la identidad visual de la barra superior</p>
                </div>
            </div>
            <div class="ek-badge green"><i class="bi bi-brush"></i> Branding</div>
        </div>
        <div class="ek-card-body">
            <div class="ek-form-group">
                <label class="ek-label"><i class="bi bi-image"></i> Logo Barra Superior</label>
                <div class="ek-upload-zone <?= !empty($ajustes['logo_navbar']) ? 'has-image' : '' ?>"
                    id="upload-zone-logo">
                    <div class="ek-upload-empty" onclick="document.getElementById('logo_navbar').click()">
                        <div class="ek-upload-icon" style="background: var(--ek-green);">
                            <i class="bi bi-cloud-arrow-up-fill"></i>
                        </div>
                        <div class="ek-upload-text">
                            <strong>Haz clic para subir logo</strong>
                            <span>SVG, PNG o JPG (Recomendado SVG)</span>
                        </div>
                        <input type="file" id="logo_navbar" name="logo_navbar" hidden accept="image/*"
                            onchange="previewImage(this, 'logo')">
                    </div>
                    <div class="ek-upload-preview" style="background: var(--ek-navy); padding: 2rem;">
                        <?php $logoPreview = asset($ajustes['logo_navbar'] ?? ''); ?>
                        <img src="<?= $logoPreview ?>" alt="Vista previa" id="preview-img-logo"
                            style="width: auto; max-height: 80px; object-fit: contain; display: <?= !empty($logoPreview) ? 'block' : 'none' ?>;">
                        <div class="ek-upload-overlay">
                            <button type="button" class="ek-btn-icon light"
                                onclick="document.getElementById('logo_navbar').click()">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <button type="button" class="ek-btn-icon danger" onclick="removeImage('logo')">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sobre Nosotros -->
    <div class="ek-card ek-fade-up" style="animation-delay: 0.18s;">
        <div class="ek-card-header">
            <div class="ek-card-header-left">
                <div class="ek-icon orange"><i class="bi bi-info-circle-fill"></i></div>
                <div>
                    <h3>Sección "Sobre Nosotros"</h3>
                    <p>Configura la información de la sección "Acerca de" en el Home</p>
                </div>
            </div>
            <div class="ek-badge orange"><i class="bi bi-building"></i> Acerca de</div>
        </div>
        <div class="ek-card-body">
            <div class="ek-grid-2">
                <div class="ek-form-fields">
                    <div class="ek-form-group">
                        <label class="ek-label"><i class="bi bi-tag-fill"></i> Etiqueta (Badge)</label>
                        <input type="text" name="about_badge" class="ek-input"
                            value="<?= e($ajustes['about_badge'] ?? 'Nuestra Esencia') ?>"
                            placeholder="Ej. Nuestra Esencia">
                    </div>

                    <div class="ek-form-group">
                        <label class="ek-label"><i class="bi bi-type-h1"></i> Título de la Sección</label>
                        <input type="text" name="about_titulo" class="ek-input"
                            value="<?= e($ajustes['about_titulo'] ?? 'Diseñamos espacios con alma propia') ?>"
                            placeholder="Título principal...">
                    </div>

                    <div class="ek-form-group">
                        <label class="ek-label"><i class="bi bi-text-paragraph"></i> Descripción Detallada</label>
                        <textarea name="about_descripcion" class="ek-input ek-textarea" rows="5"
                            placeholder="Describe la historia o misión de la marca..."><?= e($ajustes['about_descripcion'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="ek-form-group">
                    <label class="ek-label"><i class="bi bi-image-fill"></i> Imagen de la Sección</label>
                    <div class="ek-upload-zone <?= !empty($ajustes['about_imagen']) ? 'has-image' : '' ?>"
                        id="upload-zone-about">
                        <div class="ek-upload-empty" onclick="document.getElementById('about_imagen').click()">
                            <div class="ek-upload-icon">
                                <i class="bi bi-cloud-arrow-up-fill"></i>
                            </div>
                            <div class="ek-upload-text">
                                <strong>Haz clic para subir</strong>
                                <span>PNG, JPG o WebP hasta 5MB</span>
                            </div>
                            <input type="file" id="about_imagen" name="about_imagen" hidden accept="image/*"
                                onchange="previewImage(this, 'about')">
                        </div>
                        <div class="ek-upload-preview">
                            <img src="<?= e(asset($ajustes['about_imagen'] ?? '')) ?>" alt="Vista previa"
                                id="preview-img-about">
                            <div class="ek-upload-overlay">
                                <button type="button" class="ek-btn-icon light"
                                    onclick="document.getElementById('about_imagen').click()">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <button type="button" class="ek-btn-icon danger" onclick="removeImage('about')">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr style="margin: 2rem 0; border-color: var(--glass-border);">
            <label class="ek-label mb-3"><i class="bi bi-star-fill"></i> Características (4 Iconos en Home)</label>

            <div class="ek-grid-2">
                <!-- Característica 1 -->
                <div class="ek-form-group p-3 border rounded-3" style="background: rgba(0,0,0,0.02);">
                    <label class="ek-label" style="font-size: 0.8rem;">Característica 1</label>
                    <input type="text" name="about_f1_titulo" class="ek-input mb-2"
                        value="<?= e($ajustes['about_f1_titulo'] ?? 'Diseño Exclusivo') ?>" placeholder="Título">
                    <input type="text" name="about_f1_texto" class="ek-input"
                        value="<?= e($ajustes['about_f1_texto'] ?? 'Piezas únicas que no encontraras en ningun otro lugar.') ?>"
                        placeholder="Subtítulo">
                </div>
                <!-- Característica 2 -->
                <div class="ek-form-group p-3 border rounded-3" style="background: rgba(0,0,0,0.02);">
                    <label class="ek-label" style="font-size: 0.8rem;">Característica 2</label>
                    <input type="text" name="about_f2_titulo" class="ek-input mb-2"
                        value="<?= e($ajustes['about_f2_titulo'] ?? 'Sostenibilidad') ?>" placeholder="Título">
                    <input type="text" name="about_f2_texto" class="ek-input"
                        value="<?= e($ajustes['about_f2_texto'] ?? 'Comprometidos con materiales responsables.') ?>"
                        placeholder="Subtítulo">
                </div>
                <!-- Característica 3 -->
                <div class="ek-form-group p-3 border rounded-3" style="background: rgba(0,0,0,0.02);">
                    <label class="ek-label" style="font-size: 0.8rem;">Característica 3</label>
                    <input type="text" name="about_f3_titulo" class="ek-input mb-2"
                        value="<?= e($ajustes['about_f3_titulo'] ?? 'Envio Seguro') ?>" placeholder="Título">
                    <input type="text" name="about_f3_texto" class="ek-input"
                        value="<?= e($ajustes['about_f3_texto'] ?? 'Cuidamos tu pedido desde nuestro almacen hasta tu puerta.') ?>"
                        placeholder="Subtítulo">
                </div>
                <!-- Característica 4 -->
                <div class="ek-form-group p-3 border rounded-3" style="background: rgba(0,0,0,0.02);">
                    <label class="ek-label" style="font-size: 0.8rem;">Característica 4</label>
                    <input type="text" name="about_f4_titulo" class="ek-input mb-2"
                        value="<?= e($ajustes['about_f4_titulo'] ?? 'Atencion VIP') ?>" placeholder="Título">
                    <input type="text" name="about_f4_texto" class="ek-input"
                        value="<?= e($ajustes['about_f4_texto'] ?? 'Soporte personalizado para tus proyectos de interiorismo.') ?>"
                        placeholder="Subtítulo">
                </div>
            </div>
        </div>
    </div>

    <!-- Pie de Pagina -->
    <div class="ek-card ek-fade-up" style="animation-delay: 0.2s;">
        <div class="ek-card-header">
            <div class="ek-card-header-left">
                <div class="ek-icon navy"><i class="bi bi-layout-wtf"></i></div>
                <div>
                    <h3>Pie de Página</h3>
                    <p>Información y redes sociales del footer</p>
                </div>
            </div>
            <div class="ek-badge navy"><i class="bi bi-info-circle-fill"></i> Footer</div>
        </div>
        <div class="ek-card-body">
            <div class="ek-form-group mb-4">
                <label class="ek-label"><i class="bi bi-card-text"></i> Texto Informativo (Sobre Nosotros)</label>
                <textarea name="footer_texto" class="ek-input ek-textarea" rows="2"
                    placeholder="Breve descripción de la empresa..."><?= e($ajustes['footer_texto'] ?? '') ?></textarea>
            </div>

            <label class="ek-label mb-2"><i class="bi bi-person-lines-fill"></i> Información de Contacto</label>
            <div class="ek-grid-2 mb-4">
                <div class="ek-form-group">
                    <label class="ek-label" style="font-size: 0.8rem;"><i class="bi bi-envelope"></i> Email</label>
                    <input type="text" name="footer_email" class="ek-input"
                        value="<?= e($ajustes['footer_email'] ?? '') ?>" placeholder="info@ekuora.com">
                </div>
                <div class="ek-form-group">
                    <label class="ek-label" style="font-size: 0.8rem;"><i class="bi bi-telephone"></i> Teléfono</label>
                    <input type="text" name="footer_telefono" class="ek-input"
                        value="<?= e($ajustes['footer_telefono'] ?? '') ?>" placeholder="+52 (55) 1234-5678">
                </div>
                <div class="ek-form-group" style="grid-column: span 2;">
                    <label class="ek-label" style="font-size: 0.8rem;"><i class="bi bi-geo-alt"></i> Dirección</label>
                    <input type="text" name="footer_direccion" class="ek-input"
                        value="<?= e($ajustes['footer_direccion'] ?? '') ?>" placeholder="Dirección completa...">
                </div>
            </div>

            <label class="ek-label mb-2"><i class="bi bi-share-fill"></i> Redes Sociales (Deja vacío para
                ocultar)</label>
            <div class="ek-grid-2">
                <div class="ek-form-group">
                    <label class="ek-label" style="font-size: 0.8rem;"><i class="bi bi-facebook"></i> Facebook</label>
                    <input type="text" name="footer_facebook" class="ek-input"
                        value="<?= e($ajustes['footer_facebook'] ?? '') ?>" placeholder="URL completa">
                </div>
                <div class="ek-form-group">
                    <label class="ek-label" style="font-size: 0.8rem;"><i class="bi bi-instagram"></i> Instagram</label>
                    <input type="text" name="footer_instagram" class="ek-input"
                        value="<?= e($ajustes['footer_instagram'] ?? '') ?>" placeholder="URL completa">
                </div>
                <div class="ek-form-group">
                    <label class="ek-label" style="font-size: 0.8rem;"><i class="bi bi-youtube"></i> YouTube</label>
                    <input type="text" name="footer_youtube" class="ek-input"
                        value="<?= e($ajustes['footer_youtube'] ?? '') ?>" placeholder="URL completa">
                </div>
                <div class="ek-form-group">
                    <label class="ek-label" style="font-size: 0.8rem;"><i class="bi bi-tiktok"></i> TikTok</label>
                    <input type="text" name="footer_tiktok" class="ek-input"
                        value="<?= e($ajustes['footer_tiktok'] ?? '') ?>" placeholder="URL completa">
                </div>
            </div>
        </div>
    </div>

    <!-- Dock de Acciones -->
    <div class="ek-dock ek-fade-up" style="animation-delay: 0.15s;">
        <div class="ek-dock-info">
            <i class="bi bi-info-circle"></i>
            <span>Los cambios se aplicaran inmediatamente al guardar</span>
        </div>
        <button type="submit" class="ek-btn ek-btn-primary" id="btnGuardar">
            <i class="bi bi-check-lg"></i> Guardar Cambios
        </button>
    </div>
</form>

<script>
    function previewImage(input, tipo) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('preview-img-' + tipo).src = e.target.result;
                document.getElementById('upload-zone-' + tipo).classList.add('has-image');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeImage(tipo) {
        document.getElementById(tipo === 'promo' ? 'promo_imagen' : 'logo_navbar').value = '';
        document.getElementById('upload-zone-' + tipo).classList.remove('has-image');
    }

    document.getElementById('formAjustes').addEventListener('submit', function () {
        const btn = document.getElementById('btnGuardar');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Guardando...';
    });
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>