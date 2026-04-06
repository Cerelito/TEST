<?php
/**
 * Nuevo Banner - Ultra Glass Design System
 * Crear nuevo slide para el carrusel
 */
$titulo = 'Nuevo Banner | ' . APP_NAME;
$pagina_actual = 'banners_admin';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<!-- Hero Section -->
<section class="ug-hero violet ug-fade-up">
    <div class="ug-hero-blur"></div>
    <div class="ug-hero-content">
        <div class="ug-hero-text">
            <a href="<?= BASE_URL ?>banners/admin" class="ug-back-link">
                <i class="bi bi-arrow-left"></i> Volver a banners
            </a>
            <div class="ug-chip"><span class="ug-chip-dot"></span> Gestion de Contenido</div>
            <h1 class="ug-hero-title"><i class="bi bi-plus-circle"></i> Nuevo Banner</h1>
            <p class="ug-hero-subtitle">Crea un nuevo slide para el carrusel de inicio</p>
        </div>
        <div class="ug-hero-badge">
            <div class="ug-hero-badge-icon"><i class="bi bi-images"></i></div>
            <div class="ug-hero-badge-text">
                <strong>Slider Principal</strong>
                <span>1920 x 800 px</span>
            </div>
        </div>
    </div>
</section>

<form action="<?= BASE_URL ?>banners/guardar" method="POST" enctype="multipart/form-data" id="formBanner">
    <?= csrf_field() ?>

    <!-- Contenido del Banner -->
    <div class="ug-card ug-fade-up" style="animation-delay: 0.1s;">
        <div class="ug-card-header">
            <div class="ug-card-header-left">
                <div class="ug-icon violet"><i class="bi bi-type"></i></div>
                <div>
                    <h3>Contenido del Banner</h3>
                    <p>Textos que se mostraran sobre la imagen</p>
                </div>
            </div>
        </div>
        <div class="ug-card-body">
            <div class="ug-grid-2">
                <div class="ug-form-group">
                    <label class="ug-label"><i class="bi bi-type-h1"></i> Titulo Principal</label>
                    <input type="text" name="titulo" class="ug-input" placeholder="Ej: ORGANIZA">
                    <span class="ug-hint">Texto grande y llamativo</span>
                </div>
                <div class="ug-form-group">
                    <label class="ug-label"><i class="bi bi-type-italic"></i> Subtitulo (Accent)</label>
                    <input type="text" name="subtitulo" class="ug-input" placeholder="Ej: con estilo">
                    <span class="ug-hint">Texto secundario destacado</span>
                </div>
            </div>

            <div class="ug-grid-3-1">
                <div class="ug-form-group">
                    <label class="ug-label"><i class="bi bi-cursor-fill"></i> Texto del Boton</label>
                    <input type="text" name="texto_boton" class="ug-input" placeholder="Ej: EXPLORAR COLECCION"
                        value="EXPLORAR COLECCION">
                </div>
                <div class="ug-form-group" style="grid-column: span 2;">
                    <label class="ug-label"><i class="bi bi-link-45deg"></i> Enlace (URL)</label>
                    <input type="text" name="enlace" class="ug-input" placeholder="Ej: productos/categoria/hidratacion">
                    <span class="ug-hint">Ruta relativa sin BASE_URL</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Imagen de Fondo -->
    <div class="ug-card ug-fade-up" style="animation-delay: 0.15s;">
        <div class="ug-card-header">
            <div class="ug-card-header-left">
                <div class="ug-icon amber"><i class="bi bi-image-fill"></i></div>
                <div>
                    <h3>Imagen de Fondo</h3>
                    <p>Imagen principal del banner</p>
                </div>
            </div>
            <div class="ug-badge amber"><i class="bi bi-aspect-ratio"></i> 1920 x 800</div>
        </div>
        <div class="ug-card-body">
            <div class="ug-upload-zone" id="upload-zone-banner">
                <!-- Estado vacio -->
                <div class="ug-upload-empty" onclick="document.getElementById('imagen').click()">
                    <div class="ug-upload-icon violet">
                        <i class="bi bi-cloud-arrow-up-fill"></i>
                    </div>
                    <div class="ug-upload-text">
                        <strong>Arrastra tu imagen aqui o haz clic para seleccionar</strong>
                        <span>PNG, JPG o WebP - Recomendado 1920 x 800 pixeles</span>
                    </div>
                    <div class="ug-upload-specs">
                        <div class="ug-spec"><i class="bi bi-arrows-fullscreen"></i> 1920 x 800 px</div>
                        <div class="ug-spec"><i class="bi bi-file-earmark-image"></i> Max 5MB</div>
                        <div class="ug-spec"><i class="bi bi-palette"></i> RGB / sRGB</div>
                    </div>
                    <input type="file" id="imagen" name="imagen" hidden required accept="image/*"
                        onchange="previewBannerImage(this)">
                </div>

                <!-- Preview -->
                <div class="ug-upload-preview">
                    <img src="" alt="Vista previa" id="preview-img-banner">
                    <div class="ug-upload-overlay">
                        <button type="button" class="ug-btn-icon light"
                            onclick="document.getElementById('imagen').click()">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button type="button" class="ug-btn-icon danger" onclick="removeBannerImage()">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </div>
                    <div class="ug-upload-info">
                        <span id="img-name">imagen.jpg</span>
                        <span id="img-size">0 KB</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Configuracion -->
    <div class="ug-card ug-fade-up" style="animation-delay: 0.2s;">
        <div class="ug-card-header">
            <div class="ug-card-header-left">
                <div class="ug-icon emerald"><i class="bi bi-gear-fill"></i></div>
                <div>
                    <h3>Configuracion</h3>
                    <p>Ubicacion, orden y estado del banner</p>
                </div>
            </div>
        </div>
        <div class="ug-card-body">
            <div class="ug-config-grid">
                <div class="ug-form-group">
                    <label class="ug-label"><i class="bi bi-layout-split"></i> Ubicacion</label>
                    <div class="ug-select-wrapper">
                        <select name="seccion" class="ug-input ug-select">
                            <option value="hero">Slider Principal (Hero)</option>
                            <option value="recommend">Seccion Recomendados</option>
                        </select>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <span class="ug-hint">Donde aparecera el banner</span>
                </div>

                <div class="ug-form-group">
                    <label class="ug-label"><i class="bi bi-sort-numeric-down"></i> Orden</label>
                    <input type="number" name="orden" class="ug-input ug-input-number" value="0" min="0">
                    <span class="ug-hint">Posicion en el carrusel</span>
                </div>

                <div class="ug-form-group">
                    <label class="ug-label"><i class="bi bi-toggle-on"></i> Estado</label>
                    <div class="ug-toggle-card">
                        <label class="ug-toggle">
                            <input type="checkbox" name="activo" id="activo" checked>
                            <span class="ug-toggle-slider"></span>
                        </label>
                        <div class="ug-toggle-text">
                            <strong id="toggle-status">Activo</strong>
                            <span id="toggle-desc">El banner sera visible</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dock de Acciones -->
    <div class="ug-dock ug-fade-up" style="animation-delay: 0.25s;">
        <div class="ug-dock-info">
            <i class="bi bi-info-circle"></i>
            <span>El banner se publicara inmediatamente si esta activo</span>
        </div>
        <div class="ug-dock-actions">
            <a href="<?= BASE_URL ?>banners/admin" class="ug-btn-outline">
                <i class="bi bi-x-lg"></i> Cancelar
            </a>
            <button type="submit" class="ug-btn-solid" id="btnGuardar">
                <i class="bi bi-check-lg"></i> Guardar Banner
            </button>
        </div>
    </div>
</form>

<style>
    /* =============================================================================
   ESTILOS ADICIONALES PARA BANNERS (Complemento Ultra Glass)
   ============================================================================= */

    /* Back Link */
    .ug-back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        font-size: 0.85rem;
        margin-bottom: 0.75rem;
        transition: var(--ug-transition-base);
    }

    .ug-back-link:hover {
        color: white;
        transform: translateX(-4px);
    }

    /* Grid Layouts */
    .ug-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--ug-space-lg);
        margin-bottom: var(--ug-space-lg);
    }

    .ug-grid-3-1 {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: var(--ug-space-lg);
    }

    .ug-config-grid {
        display: grid;
        grid-template-columns: 1fr 150px 1fr;
        gap: var(--ug-space-lg);
        align-items: start;
    }

    /* Form Elements */
    .ug-form-group {
        display: flex;
        flex-direction: column;
        gap: var(--ug-space-sm);
    }

    .ug-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--ug-text-dark);
        display: flex;
        align-items: center;
        gap: var(--ug-space-sm);
    }

    .ug-label i {
        color: var(--ug-text-muted);
        font-size: 0.9rem;
    }

    .ug-input {
        padding: 0.85rem 1rem;
        border: 1px solid var(--ug-gray-200);
        border-radius: var(--ug-radius-md);
        background: var(--ug-gray-50);
        color: var(--ug-text-dark);
        font-size: 0.95rem;
        transition: var(--ug-transition-base);
        width: 100%;
    }

    [data-theme="dark"] .ug-input {
        background: var(--ug-gray-800);
        border-color: var(--ug-gray-700);
        color: var(--ug-text-dark);
    }

    .ug-input:focus {
        outline: none;
        border-color: var(--ug-emerald);
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
    }

    .ug-input::placeholder {
        color: var(--ug-text-light);
    }

    .ug-input-number {
        width: 100%;
        text-align: center;
    }

    .ug-hint {
        font-size: 0.75rem;
        color: var(--ug-text-muted);
    }

    /* Select */
    .ug-select-wrapper {
        position: relative;
    }

    .ug-select {
        appearance: none;
        padding-right: 2.5rem;
        cursor: pointer;
    }

    .ug-select-wrapper i {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--ug-text-muted);
        pointer-events: none;
    }

    /* Upload Zone */
    .ug-upload-zone {
        position: relative;
        border: 2px dashed var(--ug-gray-300);
        border-radius: var(--ug-radius-lg);
        overflow: hidden;
        transition: var(--ug-transition-slow);
        background: var(--ug-gray-50);
    }

    [data-theme="dark"] .ug-upload-zone {
        background: var(--ug-gray-800);
        border-color: var(--ug-gray-600);
    }

    .ug-upload-zone:hover {
        border-color: var(--ug-emerald);
    }

    .ug-upload-zone.has-image .ug-upload-empty {
        display: none;
    }

    .ug-upload-zone .ug-upload-preview {
        display: none;
    }

    .ug-upload-zone.has-image .ug-upload-preview {
        display: block;
    }

    .ug-upload-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem;
        cursor: pointer;
        min-height: 280px;
    }

    .ug-upload-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.25rem;
    }

    .ug-upload-icon.violet {
        background: linear-gradient(135deg, var(--ug-violet), var(--ug-violet-light));
        box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3);
    }

    .ug-upload-icon i {
        font-size: 2rem;
        color: white;
    }

    .ug-upload-text {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .ug-upload-text strong {
        display: block;
        color: var(--ug-text-dark);
        font-size: 1.1rem;
        margin-bottom: 6px;
    }

    .ug-upload-text span {
        font-size: 0.85rem;
        color: var(--ug-text-muted);
    }

    .ug-upload-specs {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
        justify-content: center;
    }

    .ug-spec {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        background: rgba(139, 92, 246, 0.1);
        border-radius: var(--ug-radius-full);
        font-size: 0.8rem;
        color: var(--ug-violet);
        font-weight: 500;
    }

    [data-theme="dark"] .ug-spec {
        background: rgba(139, 92, 246, 0.2);
    }

    .ug-upload-preview {
        position: relative;
    }

    .ug-upload-preview img {
        width: 100%;
        height: auto;
        max-height: 400px;
        object-fit: cover;
        display: block;
    }

    .ug-upload-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        opacity: 0;
        transition: var(--ug-transition-base);
    }

    .ug-upload-preview:hover .ug-upload-overlay {
        opacity: 1;
    }

    .ug-btn-icon {
        width: 48px;
        height: 48px;
        border: none;
        border-radius: var(--ug-radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1.1rem;
        transition: var(--ug-transition-base);
    }

    .ug-btn-icon.light {
        background: white;
        color: var(--ug-text-dark);
    }

    .ug-btn-icon.danger {
        background: var(--ug-rose);
        color: white;
    }

    .ug-btn-icon:hover {
        transform: scale(1.1);
    }

    .ug-upload-info {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 12px 16px;
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
        display: flex;
        justify-content: space-between;
        color: white;
        font-size: 0.8rem;
    }

    /* Toggle Switch */
    .ug-toggle-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: var(--ug-gray-50);
        border-radius: var(--ug-radius-md);
        border: 1px solid var(--ug-gray-200);
    }

    [data-theme="dark"] .ug-toggle-card {
        background: var(--ug-gray-800);
        border-color: var(--ug-gray-700);
    }

    .ug-toggle {
        position: relative;
        width: 52px;
        height: 28px;
        flex-shrink: 0;
    }

    .ug-toggle input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .ug-toggle-slider {
        position: absolute;
        cursor: pointer;
        inset: 0;
        background: var(--ug-gray-300);
        border-radius: 28px;
        transition: var(--ug-transition-slow);
    }

    .ug-toggle-slider:before {
        content: '';
        position: absolute;
        height: 22px;
        width: 22px;
        left: 3px;
        bottom: 3px;
        background: white;
        border-radius: 50%;
        transition: var(--ug-transition-slow);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .ug-toggle input:checked+.ug-toggle-slider {
        background: var(--ug-emerald);
    }

    .ug-toggle input:checked+.ug-toggle-slider:before {
        transform: translateX(24px);
    }

    .ug-toggle-text strong {
        display: block;
        font-size: 0.9rem;
        color: var(--ug-text-dark);
    }

    .ug-toggle-text span {
        font-size: 0.75rem;
        color: var(--ug-text-muted);
    }

    /* Dock de Acciones */
    .ug-dock {
        position: sticky;
        bottom: var(--ug-space-lg);
        margin-top: var(--ug-space-lg);
        background: var(--ug-glass-bg-solid);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--ug-glass-border);
        border-radius: var(--ug-radius-lg);
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: var(--ug-glass-shadow-lg);
        z-index: 50;
    }

    .ug-dock-info {
        display: flex;
        align-items: center;
        gap: var(--ug-space-sm);
        color: var(--ug-text-muted);
        font-size: 0.85rem;
    }

    .ug-dock-actions {
        display: flex;
        gap: var(--ug-space-md);
    }

    .ug-btn-outline {
        display: inline-flex;
        align-items: center;
        gap: var(--ug-space-sm);
        padding: 0.75rem 1.25rem;
        background: transparent;
        color: var(--ug-text-muted);
        border: 1px solid var(--ug-gray-300);
        border-radius: var(--ug-radius-md);
        font-size: 0.9rem;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        transition: var(--ug-transition-base);
    }

    .ug-btn-outline:hover {
        background: var(--ug-gray-100);
        color: var(--ug-text-dark);
        border-color: var(--ug-gray-400);
    }

    [data-theme="dark"] .ug-btn-outline:hover {
        background: var(--ug-gray-700);
    }

    .ug-btn-solid {
        display: inline-flex;
        align-items: center;
        gap: var(--ug-space-sm);
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, var(--ug-emerald), var(--ug-emerald-light));
        color: white;
        border: none;
        border-radius: var(--ug-radius-md);
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--ug-transition-slow);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
    }

    .ug-btn-solid:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(16, 185, 129, 0.4);
    }

    .ug-btn-solid:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .ug-config-grid {
            grid-template-columns: 1fr 1fr;
        }

        .ug-config-grid .ug-form-group:last-child {
            grid-column: span 2;
        }
    }

    @media (max-width: 768px) {

        .ug-grid-2,
        .ug-grid-3-1,
        .ug-config-grid {
            grid-template-columns: 1fr;
        }

        .ug-config-grid .ug-form-group:last-child {
            grid-column: span 1;
        }

        .ug-upload-specs {
            flex-direction: column;
            align-items: center;
        }

        .ug-dock {
            flex-direction: column;
            gap: var(--ug-space-md);
            text-align: center;
        }

        .ug-dock-actions {
            width: 100%;
            flex-direction: column;
        }

        .ug-btn-outline,
        .ug-btn-solid {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .ug-upload-empty {
            padding: 2rem 1rem;
            min-height: 220px;
        }

        .ug-upload-icon {
            width: 60px;
            height: 60px;
        }

        .ug-upload-icon i {
            font-size: 1.5rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Preview imagen
        window.previewBannerImage = function (input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const reader = new FileReader();

                reader.onload = function (e) {
                    document.getElementById('preview-img-banner').src = e.target.result;
                    document.getElementById('upload-zone-banner').classList.add('has-image');
                    document.getElementById('img-name').textContent = file.name;
                    document.getElementById('img-size').textContent = formatFileSize(file.size);
                }
                reader.readAsDataURL(file);
            }
        };

        // Remover imagen
        window.removeBannerImage = function () {
            document.getElementById('imagen').value = '';
            document.getElementById('upload-zone-banner').classList.remove('has-image');
        };

        // Formatear tamano
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Toggle status
        document.getElementById('activo').addEventListener('change', function () {
            document.getElementById('toggle-status').textContent = this.checked ? 'Activo' : 'Inactivo';
            document.getElementById('toggle-desc').textContent = this.checked ? 'El banner sera visible' : 'El banner estara oculto';
        });

        // Submit loading
        document.getElementById('formBanner').addEventListener('submit', function () {
            const btn = document.getElementById('btnGuardar');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Guardando...';
        });

        // Drag & Drop
        const zone = document.getElementById('upload-zone-banner');
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(e => {
            zone.addEventListener(e, ev => { ev.preventDefault(); ev.stopPropagation(); });
        });
        ['dragenter', 'dragover'].forEach(e => {
            zone.addEventListener(e, () => { zone.style.borderColor = 'var(--ug-emerald)'; });
        });
        ['dragleave', 'drop'].forEach(e => {
            zone.addEventListener(e, () => { zone.style.borderColor = ''; });
        });
        zone.addEventListener('drop', e => {
            if (e.dataTransfer.files.length) {
                document.getElementById('imagen').files = e.dataTransfer.files;
                previewBannerImage(document.getElementById('imagen'));
            }
        });
    });
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>