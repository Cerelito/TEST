<?php
$pagina_actual = 'dashboard';
$titulo = 'Dashboard | Ekuora Admin';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<style>
    /* ============================================
       EKUORA ADMIN DASHBOARD - ULTRA GLASS PANTONE
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
        margin: 0 0 3rem;
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
        bottom: -50px;
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
        }

        50% {
            opacity: 0.5;
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
        gap: 1rem;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
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

    .ek-stat-icon.sky {
        background: rgba(122, 153, 172, 0.2);
        color: var(--ek-sky);
    }

    .ek-stat-icon.green {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
    }

    .ek-stat-info {
        flex: 1;
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

    .ek-stat-trend {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--radius-sm);
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
        font-size: 1.25rem;
    }

    /* ============================================
       CARDS & ACTIONS
    ============================================ */
    .ek-section-title {
        font-family: 'Outfit', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--ek-navy);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .ek-section-title i {
        color: var(--ek-orange);
    }

    .ek-actions-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .ek-action-card {
        background: var(--glass-bg);
        backdrop-filter: var(--glass-blur);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        text-decoration: none;
        transition: var(--transition);
        border-left: 4px solid transparent;
    }

    .ek-action-card:hover {
        transform: translateX(8px);
        box-shadow: 0 12px 40px rgba(0, 43, 73, 0.15);
    }

    .ek-action-card.navy {
        border-left-color: var(--ek-navy);
    }

    .ek-action-card.orange {
        border-left-color: var(--ek-orange);
    }

    .ek-action-card.sky {
        border-left-color: var(--ek-sky);
    }

    .ek-action-card.green {
        border-left-color: #22c55e;
    }

    .ek-action-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .ek-action-icon {
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--radius-md);
        font-size: 1.5rem;
    }

    .ek-action-text h4 {
        font-family: 'Outfit', sans-serif;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--ek-navy);
        margin: 0 0 0.25rem;
    }

    .ek-action-text p {
        font-size: 0.85rem;
        color: var(--ek-slate);
        margin: 0;
    }

    .ek-action-arrow {
        font-size: 1.5rem;
        color: var(--ek-sky);
        opacity: 0.5;
        transition: var(--transition);
    }

    .ek-action-card:hover .ek-action-arrow {
        opacity: 1;
        transform: translateX(4px);
        color: var(--ek-orange);
    }

    /* ============================================
       SYSTEM STATUS
    ============================================ */
    .ek-system-card {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.1) 0%, rgba(34, 197, 94, 0.05) 100%);
        border: 2px solid rgba(34, 197, 94, 0.3);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .ek-system-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .ek-system-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(34, 197, 94, 0.2);
        border-radius: var(--radius-md);
        color: #22c55e;
        font-size: 1.5rem;
    }

    .ek-system-title {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        color: #22c55e;
        margin: 0;
    }

    .ek-system-subtitle {
        font-size: 0.85rem;
        color: var(--ek-slate);
        margin: 0;
    }

    .ek-system-items {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .ek-system-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .ek-system-label {
        font-size: 0.9rem;
        color: var(--ek-slate);
    }

    .ek-status {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.35rem 0.75rem;
        border-radius: var(--radius-full);
        font-size: 0.8rem;
        font-weight: 600;
    }

    .ek-status.active {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
    }

    .ek-status.active::before {
        content: '';
        width: 6px;
        height: 6px;
        background: #22c55e;
        border-radius: 50%;
    }

    /* ============================================
       DESIGN PANEL
    ============================================ */
    .ek-design-card {
        background: linear-gradient(135deg, rgba(237, 139, 0, 0.1) 0%, rgba(0, 43, 73, 0.1) 100%);
        border: 1px solid rgba(237, 139, 0, 0.3);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
    }

    .ek-design-header {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .ek-design-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(237, 139, 0, 0.2);
        border-radius: var(--radius-md);
        color: var(--ek-orange);
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .ek-design-title {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        color: var(--ek-orange);
        margin: 0 0 0.5rem;
    }

    .ek-design-text {
        font-size: 0.9rem;
        color: var(--ek-slate);
        line-height: 1.6;
        margin: 0;
    }

    .ek-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: var(--ek-orange);
        color: white;
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: var(--radius-full);
        margin-top: 1rem;
    }

    /* ============================================
       PRODUCTOS DESTACADOS
    ============================================ */
    .ek-productos-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
    }

    .ek-producto-mini {
        background: var(--glass-bg);
        backdrop-filter: var(--glass-blur);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        text-align: center;
        transition: var(--transition);
    }

    .ek-producto-mini:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0, 43, 73, 0.15);
        border-color: var(--ek-orange);
    }

    .ek-producto-mini-img {
        width: 100px;
        height: 100px;
        margin: 0 auto 1rem;
        background: var(--ek-sky-pale);
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .ek-producto-mini-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .ek-producto-mini-img i {
        font-size: 2.5rem;
        color: var(--ek-sky);
    }

    .ek-producto-mini h4 {
        font-family: 'Outfit', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        color: var(--ek-navy);
        margin: 0 0 0.25rem;
    }

    .ek-producto-mini p {
        font-size: 0.85rem;
        color: var(--ek-slate);
        margin: 0;
    }

    /* ============================================
       RESPONSIVE
    ============================================ */
    @media (max-width: 1200px) {
        .ek-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .ek-productos-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 992px) {
        .ek-admin-hero-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .ek-actions-grid {
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

        .ek-stats-grid {
            grid-template-columns: 1fr;
        }

        .ek-productos-grid {
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
            <div class="ek-admin-hero-badge">Panel de Control</div>
            <h1 class="ek-admin-hero-title">
                <i class="bi bi-speedometer2"></i>
                ¡Hola,
                <?= e(usuarioActual()['nombre'] ?? 'Admin') ?>!
            </h1>
            <p class="ek-admin-hero-subtitle">Aquí tienes el resumen ejecutivo de tu catálogo actualizado al instante.
            </p>
        </div>
        <a href="<?= BASE_URL ?>productos/crear" class="ek-btn ek-btn-primary">
            <i class="bi bi-plus-lg"></i> Nuevo Producto
        </a>
    </div>
</section>

<!-- Estadísticas -->
<div class="ek-stats-grid ek-fade-up" style="animation-delay: 0.1s;">
    <div class="ek-stat-card">
        <div class="ek-stat-icon orange">
            <i class="bi bi-grid-3x3-gap"></i>
        </div>
        <div class="ek-stat-info">
            <div class="ek-stat-value">
                <?= $stats['total_categorias'] ?>
            </div>
            <div class="ek-stat-label">Categorías Activas</div>
        </div>
        <div class="ek-stat-trend"><i class="bi bi-arrow-up-short"></i></div>
    </div>

    <div class="ek-stat-card">
        <div class="ek-stat-icon navy">
            <i class="bi bi-box-seam"></i>
        </div>
        <div class="ek-stat-info">
            <div class="ek-stat-value">
                <?= $stats['total_productos'] ?>
            </div>
            <div class="ek-stat-label">Productos Registrados</div>
        </div>
        <div class="ek-stat-trend"><i class="bi bi-graph-up"></i></div>
    </div>

    <div class="ek-stat-card">
        <div class="ek-stat-icon green">
            <i class="bi bi-eye-fill"></i>
        </div>
        <div class="ek-stat-info">
            <div class="ek-stat-value">
                <?= number_format($stats['total_vistas']) ?>
            </div>
            <div class="ek-stat-label">Vistas Totales</div>
        </div>
        <div class="ek-stat-trend"><span style="font-size: 0.8rem; font-weight: 700;">+12%</span></div>
    </div>

    <div class="ek-stat-card">
        <div class="ek-stat-icon sky">
            <i class="bi bi-collection-fill"></i>
        </div>
        <div class="ek-stat-info">
            <div class="ek-stat-value">
                <?= $stats['total_familias'] ?? 0 ?>
            </div>
            <div class="ek-stat-label">Familias de Productos</div>
        </div>
    </div>
</div>

<!-- Grid Principal -->
<div class="row g-4">
    <!-- Acciones Rápidas -->
    <div class="col-lg-8">
        <div class="ek-fade-up" style="animation-delay: 0.2s;">
            <h3 class="ek-section-title"><i class="bi bi-lightning-fill"></i> Acciones Rápidas</h3>

            <div class="ek-actions-grid">
                <a href="<?= BASE_URL ?>productos/crear" class="ek-action-card navy">
                    <div class="ek-action-left">
                        <div class="ek-action-icon" style="background: rgba(0, 43, 73, 0.1); color: var(--ek-navy);">
                            <i class="bi bi-plus-circle-fill"></i>
                        </div>
                        <div class="ek-action-text">
                            <h4>Nuevo Producto</h4>
                            <p>Añade un artículo al inventario</p>
                        </div>
                    </div>
                    <i class="bi bi-arrow-right ek-action-arrow"></i>
                </a>

                <a href="<?= BASE_URL ?>productos/categorias" class="ek-action-card orange">
                    <div class="ek-action-left">
                        <div class="ek-action-icon"
                            style="background: rgba(237, 139, 0, 0.1); color: var(--ek-orange);">
                            <i class="bi bi-tags-fill"></i>
                        </div>
                        <div class="ek-action-text">
                            <h4>Gestionar Categorías</h4>
                            <p>Organiza tu clasificación</p>
                        </div>
                    </div>
                    <i class="bi bi-arrow-right ek-action-arrow"></i>
                </a>

                <a href="<?= BASE_URL ?>productos/familias" class="ek-action-card green">
                    <div class="ek-action-left">
                        <div class="ek-action-icon" style="background: rgba(34, 197, 94, 0.1); color: #22c55e;">
                            <i class="bi bi-grid-1x2-fill"></i>
                        </div>
                        <div class="ek-action-text">
                            <h4>Administrar Familias</h4>
                            <p>Subcategorías y agrupación</p>
                        </div>
                    </div>
                    <i class="bi bi-arrow-right ek-action-arrow"></i>
                </a>

                <a href="<?= BASE_URL ?>productos" target="_blank" class="ek-action-card sky">
                    <div class="ek-action-left">
                        <div class="ek-action-icon" style="background: rgba(122, 153, 172, 0.2); color: var(--ek-sky);">
                            <i class="bi bi-globe2"></i>
                        </div>
                        <div class="ek-action-text">
                            <h4>Catálogo Público</h4>
                            <p>Ver sitio en línea</p>
                        </div>
                    </div>
                    <i class="bi bi-box-arrow-up-right ek-action-arrow"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Panel Lateral -->
    <div class="col-lg-4">
        <div class="ek-fade-up" style="animation-delay: 0.3s;">
            <h3 class="ek-section-title"><i class="bi bi-shield-check"></i> Monitor del Sistema</h3>
            <!-- Estado del Sistema -->
            <div class="ek-system-card">
                <div class="ek-system-header">
                    <div class="ek-system-icon"><i class="bi bi-check-circle-fill"></i></div>
                    <div>
                        <h4 class="ek-system-title">Sistema Activo</h4>
                        <p class="ek-system-subtitle">Todos los servicios funcionando</p>
                    </div>
                </div>
                <div class="ek-system-items">
                    <div class="ek-system-item">
                        <span class="ek-system-label">Base de Datos</span>
                        <span class="ek-status active">Conectada</span>
                    </div>
                    <div class="ek-system-item">
                        <span class="ek-system-label">Catálogo Público</span>
                        <span class="ek-status active">En Línea</span>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

<!-- Productos Destacados -->
<?php if (!empty($productos_destacados)): ?>
    <div class="mt-5 ek-fade-up" style="animation-delay: 0.4s;">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h3 class="ek-section-title" style="margin-bottom: 0;"><i class="bi bi-star-fill"></i> Productos Destacados</h3>
            <a href="<?= BASE_URL ?>productos" class="ek-btn"
                style="background: var(--ek-navy); color: white; padding: 0.75rem 1.5rem; font-size: 0.9rem;">
                Ver Todos <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="ek-productos-grid">
            <?php foreach (array_slice($productos_destacados, 0, 4) as $prod): ?>
                <div class="ek-producto-mini">
                    <div class="ek-producto-mini-img">
                        <?php if (!empty($prod['imagen_principal'])): ?>
                            <img src="<?= e(asset($prod['imagen_principal'])) ?>" alt="<?= e($prod['nombre']) ?>">
                        <?php else: ?>
                            <i class="bi bi-image"></i>
                        <?php endif; ?>
                    </div>
                    <h4>
                        <?= e($prod['nombre']) ?>
                    </h4>
                    <p>
                        <?= e($prod['categoria_nombre'] ?? 'Sin categoría') ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>