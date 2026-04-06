<?php
$pagina_actual = 'categorias_admin';
$titulo = 'Categorías | Ekuora Admin';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<style>
    /* ============================================
       EKUORA ADMIN - GESTIÓN DE CATEGORÍAS
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

    /* ============================================
       STATS GRID
    ============================================ */
    .ek-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .ek-stat-card {
        background: var(--glass-bg);
        backdrop-filter: var(--glass-blur);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        transition: var(--transition);
    }

    .ek-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 43, 73, 0.15);
        border-color: var(--ek-orange);
    }

    .ek-stat-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--radius-md);
        font-size: 1.75rem;
        flex-shrink: 0;
    }

    .ek-stat-icon.navy {
        background: rgba(0, 43, 73, 0.1);
        color: var(--ek-navy);
    }

    .ek-stat-icon.orange {
        background: rgba(237, 139, 0, 0.1);
        color: var(--ek-orange);
    }

    .ek-stat-icon.green {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
    }

    .ek-stat-icon.red {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .ek-stat-value {
        font-family: 'Outfit', sans-serif;
        font-size: 2rem;
        font-weight: 800;
        color: var(--ek-navy);
        line-height: 1;
    }

    .ek-stat-label {
        font-size: 0.9rem;
        color: var(--ek-slate);
        margin-top: 0.25rem;
    }

    /* ============================================
       CATEGORIES GRID
    ============================================ */
    .ek-categories-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }

    /* ============================================
       CATEGORY CARD
    ============================================ */
    .ek-category-card {
        background: var(--glass-bg);
        backdrop-filter: var(--glass-blur);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: var(--transition);
        display: flex;
        flex-direction: column;
    }

    .ek-category-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 48px rgba(0, 43, 73, 0.15);
        border-color: var(--ek-orange);
    }

    .ek-category-header {
        padding: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        flex: 1;
    }

    .ek-category-icon {
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--ek-orange) 0%, var(--ek-orange-light) 100%);
        border-radius: var(--radius-md);
        color: white;
        font-size: 1.5rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(237, 139, 0, 0.3);
    }

    .ek-category-info {
        flex: 1;
        min-width: 0;
    }

    .ek-category-name {
        font-family: 'Outfit', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--ek-navy);
        margin: 0 0 0.5rem;
        line-height: 1.3;
    }

    .ek-category-desc {
        font-size: 0.9rem;
        color: var(--ek-slate);
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin: 0;
    }

    /* Category Image (if exists) */
    .ek-category-image {
        width: 100%;
        height: 140px;
        background: var(--ek-sky-pale);
        position: relative;
        overflow: hidden;
    }

    .ek-category-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition);
    }

    .ek-category-card:hover .ek-category-image img {
        transform: scale(1.05);
    }

    .ek-category-image .ek-featured-badge {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.4rem 0.75rem;
        background: var(--ek-orange);
        border-radius: var(--radius-full);
        color: white;
        font-size: 0.75rem;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(237, 139, 0, 0.4);
    }

    .ek-category-image .placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        color: var(--ek-sky);
    }

    .ek-category-image .placeholder i {
        font-size: 2rem;
    }

    .ek-category-image .placeholder span {
        font-size: 0.8rem;
    }

    /* Category Footer */
    .ek-category-footer {
        padding: 1rem 1.5rem;
        background: rgba(0, 43, 73, 0.02);
        border-top: 1px solid var(--glass-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .ek-category-meta {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .ek-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.4rem 0.85rem;
        border-radius: var(--radius-full);
        font-size: 0.8rem;
        font-weight: 600;
    }

    .ek-badge.orange {
        background: rgba(237, 139, 0, 0.12);
        color: var(--ek-orange);
    }

    .ek-badge i {
        font-size: 0.7rem;
    }

    .ek-status {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.35rem 0.75rem;
        border-radius: var(--radius-full);
        font-size: 0.75rem;
        font-weight: 600;
    }

    .ek-status::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }

    .ek-status.active {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
    }

    .ek-status.active::before {
        background: #22c55e;
    }

    .ek-status.inactive {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .ek-status.inactive::before {
        background: #ef4444;
    }

    /* Actions */
    .ek-actions {
        display: flex;
        gap: 0.5rem;
    }

    .ek-action-btn {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--radius-sm);
        border: none;
        font-size: 0.95rem;
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
    }

    .ek-action-btn.edit {
        background: rgba(0, 43, 73, 0.08);
        color: var(--ek-navy);
    }

    .ek-action-btn.edit:hover {
        background: var(--ek-navy);
        color: white;
    }

    .ek-action-btn.delete {
        background: rgba(239, 68, 68, 0.08);
        color: #ef4444;
    }

    .ek-action-btn.delete:hover {
        background: #ef4444;
        color: white;
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
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-lg);
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
        font-family: 'Outfit', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--ek-navy);
        margin-bottom: 0.75rem;
    }

    .ek-empty-text {
        font-size: 1rem;
        color: var(--ek-slate);
        margin-bottom: 2rem;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }

    /* ============================================
       RESPONSIVE
    ============================================ */
    @media (max-width: 1400px) {
        .ek-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 1200px) {
        .ek-categories-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 992px) {
        .ek-admin-hero-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .ek-stats-grid {
            grid-template-columns: repeat(2, 1fr);
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

        .ek-categories-grid {
            grid-template-columns: 1fr;
        }

        .ek-stats-grid {
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
            <div class="ek-admin-hero-badge">Clasificación Estructural</div>
            <h1 class="ek-admin-hero-title">
                <i class="bi bi-tags"></i>
                Gestión de Categorías
            </h1>
            <p class="ek-admin-hero-subtitle">Organiza tus productos por grupos lógicos para facilitar la navegación.
            </p>
        </div>
        <a href="<?= BASE_URL ?>productos/crear-categoria" class="ek-btn ek-btn-primary">
            <i class="bi bi-plus-lg"></i> Nueva Categoría
        </a>
    </div>
</section>

<!-- Stats Grid -->
<div class="ek-stats-grid ek-fade-up" style="animation-delay: 0.1s;">
    <div class="ek-stat-card">
        <div class="ek-stat-icon navy">
            <i class="bi bi-tags-fill"></i>
        </div>
        <div>
            <div class="ek-stat-value">
                <?= count($categorias) ?>
            </div>
            <div class="ek-stat-label">Total Categorías</div>
        </div>
    </div>

    <div class="ek-stat-card">
        <div class="ek-stat-icon green">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div>
            <div class="ek-stat-value">
                <?= count(array_filter($categorias, fn($c) => $c['activo'])) ?>
            </div>
            <div class="ek-stat-label">Categorías Activas</div>
        </div>
    </div>

    <div class="ek-stat-card">
        <div class="ek-stat-icon orange">
            <i class="bi bi-star-fill"></i>
        </div>
        <div>
            <div class="ek-stat-value">
                <?= count(array_filter($categorias, fn($c) => !empty($c['destacado']) && $c['destacado'])) ?>
            </div>
            <div class="ek-stat-label">Destacadas en Home</div>
        </div>
    </div>

    <div class="ek-stat-card">
        <div class="ek-stat-icon red">
            <i class="bi bi-box-seam-fill"></i>
        </div>
        <div>
            <div class="ek-stat-value">
                <?= array_sum(array_column($categorias, 'total_productos')) ?>
            </div>
            <div class="ek-stat-label">Total Productos</div>
        </div>
    </div>
</div>

<!-- Categories Grid -->
<div class="ek-categories-grid ek-fade-up" style="animation-delay: 0.2s;">
    <?php if (empty($categorias)): ?>
        <div class="ek-empty-state">
            <div class="ek-empty-icon">
                <i class="bi bi-tags"></i>
            </div>
            <h3 class="ek-empty-title">Sin categorías registradas</h3>
            <p class="ek-empty-text">Crea tu primera categoría para comenzar a organizar tus productos de manera efectiva.
            </p>
            <a href="<?= BASE_URL ?>productos/crear-categoria" class="ek-btn ek-btn-primary">
                <i class="bi bi-plus-lg"></i> Crear Primera Categoría
            </a>
        </div>
    <?php else: ?>
        <?php foreach ($categorias as $index => $cat): ?>
            <div class="ek-category-card" style="animation-delay: <?= 0.1 + ($index * 0.05) ?>s;">
                <!-- Image Section -->
                <div class="ek-category-image">
                    <?php if (!empty($cat['imagen'])): ?>
                        <img src="<?= e(asset($cat['imagen'])) ?>" alt="<?= e($cat['nombre']) ?>">
                    <?php else: ?>
                        <div class="placeholder">
                            <i class="bi <?= e($cat['icono'] ?? 'bi-tag') ?>"></i>
                            <span>Sin imagen</span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($cat['destacado']) && $cat['destacado']): ?>
                        <div class="ek-featured-badge">
                            <i class="bi bi-star-fill"></i> Destacada
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Header Section -->
                <div class="ek-category-header">
                    <div class="ek-category-icon">
                        <i class="bi <?= e($cat['icono'] ?? 'bi-tag') ?>"></i>
                    </div>
                    <div class="ek-category-info">
                        <h3 class="ek-category-name">
                            <?= e($cat['nombre']) ?>
                        </h3>
                        <p class="ek-category-desc">
                            <?= e($cat['descripcion'] ?: 'Sin descripción detallada.') ?>
                        </p>
                        <?php if (!empty($cat['familias'])): ?>
                            <div class="ek-category-families mt-2" style="font-size: 0.8rem; color: var(--ek-sky);">
                                <strong>Familias:</strong>
                                <?php
                                $nombres = array_map(fn($f) => e($f['nombre']), $cat['familias']);
                                echo implode(', ', $nombres);
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Footer Section -->
                <div class="ek-category-footer">
                    <div class="ek-category-meta">
                        <span class="ek-badge orange">
                            <i class="bi bi-box"></i>
                            <strong>
                                <?= $cat['total_productos'] ?>
                            </strong> Productos
                        </span>
                        <?php if ($cat['activo']): ?>
                            <span class="ek-status active">Activo</span>
                        <?php else: ?>
                            <span class="ek-status inactive">Inactivo</span>
                        <?php endif; ?>
                    </div>
                    <div class="ek-actions">
                        <a href="<?= BASE_URL ?>productos/editar-categoria/<?= $cat['id'] ?>" class="ek-action-btn edit"
                            title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form id="form-eliminar-<?= $cat['id'] ?>"
                            action="<?= BASE_URL ?>productos/eliminar-categoria/<?= $cat['id'] ?>" method="POST"
                            style="display: none;">
                            <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">
                        </form>
                        <button onclick="confirmarEliminar(<?= $cat['id'] ?>, '<?= e($cat['nombre']) ?>')"
                            class="ek-action-btn delete" title="Eliminar">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
    function confirmarEliminar(id, nombre) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '¿Eliminar categoría?',
                text: `¿Estás seguro de eliminar "${nombre}"? Esta acción no se puede deshacer.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ED8B00', // ek-orange
                cancelButtonColor: '#425563', // ek-slate
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                background: 'rgba(255, 255, 255, 0.95)',
                backdrop: `rgba(0,43,73,0.5) blur(4px)`
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-eliminar-' + id).submit();
                }
            });
        } else {
            if (confirm(`¿Estás seguro de eliminar la categoría "${nombre}"?\n\nEsta acción no se puede deshacer.`)) {
                document.getElementById('form-eliminar-' + id).submit();
            }
        }
    }
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>