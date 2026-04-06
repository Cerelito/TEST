<style>
    /* ============================================
       EKUORA NAVBAR - ULTRA GLASS PANTONE
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
    }

    /* ========== NAVBAR ========== */
    .ek-navbar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        background: #002B49; /* Solid Navy Blue as requested */
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
    }

    .ek-navbar.scrolled {
        background: #00233b; /* Slightly darker on scroll */
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.25);
    }

    .ek-navbar-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 72px;
    }

    /* Logo */
    .ek-navbar-brand {
        display: flex;
        align-items: center;
        text-decoration: none;
    }

    .ek-navbar-logo {
        height: 168px;
        width: auto;
        transition: transform 0.3s ease;
    }

    .ek-navbar-brand:hover .ek-navbar-logo {
        transform: scale(1.05);
    }

    /* Navigation Links */
    .ek-navbar-links {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        flex-wrap: wrap;
        justify-content: center;
    }

    .ek-navbar-item {
        position: relative;
    }

    .ek-navbar-link {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 0.6rem 1.25rem;
        color: rgba(255, 255, 255, 0.85);
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 500;
        border-radius: 50px;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .ek-navbar-link:hover {
        color: white;
        background: rgba(237, 139, 0, 0.2);
    }

    .ek-navbar-link.active {
        color: white;
        background: var(--ek-orange);
        box-shadow: 0 4px 15px rgba(237, 139, 0, 0.4);
    }

    .ek-navbar-link i {
        font-size: 0.7rem;
        transition: transform 0.3s ease;
    }

    .ek-navbar-item:hover .ek-navbar-link i {
        transform: rotate(180deg);
    }

    /* Dropdown */
    .ek-dropdown {
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%) translateY(10px);
        min-width: 240px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 15px 50px rgba(0, 43, 73, 0.25);
        padding: 0.75rem 0;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 1000;
        margin-top: 8px;
    }

    .ek-dropdown::before {
        content: '';
        position: absolute;
        top: -8px;
        left: 50%;
        transform: translateX(-50%);
        border-left: 10px solid transparent;
        border-right: 10px solid transparent;
        border-bottom: 10px solid white;
    }

    .ek-navbar-item:hover .ek-dropdown {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(0);
    }

    .ek-dropdown-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0.75rem 1.5rem;
        color: var(--ek-slate);
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .ek-dropdown-item:hover {
        background: var(--ek-sky-pale);
        color: var(--ek-orange);
        padding-left: 1.75rem;
    }

    .ek-dropdown-item i {
        font-size: 0.6rem;
        color: var(--ek-orange);
    }

    /* Actions */
    .ek-navbar-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .ek-navbar-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        background: rgba(255, 255, 255, 0.05); /* Lighter background */
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        color: white;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .ek-navbar-btn:hover {
        background: var(--ek-orange);
        border-color: var(--ek-orange);
        transform: scale(1.1);
        box-shadow: 0 4px 20px rgba(237, 139, 0, 0.4);
    }

    /* Theme Toggle Icons */
    .ek-navbar-btn .theme-icon-dark {
        display: none;
    }

    [data-theme="dark"] .ek-navbar-btn .theme-icon-light {
        display: none;
    }

    [data-theme="dark"] .ek-navbar-btn .theme-icon-dark {
        display: block;
    }

    /* Mobile Button */
    .ek-navbar-mobile-btn {
        display: none;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .ek-navbar-mobile-btn:hover {
        background: var(--ek-orange);
        border-color: var(--ek-orange);
    }

    /* ========== MOBILE MENU ========== */
    .ek-mobile-menu {
        position: fixed;
        top: 0;
        right: -100%;
        width: 100%;
        max-width: 380px;
        height: 100vh;
        background: linear-gradient(180deg, var(--ek-navy) 0%, var(--ek-navy-light) 100%);
        z-index: 2000;
        display: flex;
        flex-direction: column;
        transition: right 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: -10px 0 50px rgba(0, 0, 0, 0.3);
    }

    .ek-mobile-menu.active {
        right: 0;
    }

    .ek-mobile-menu-overlay {
        position: fixed;
        inset: 0;
        background: #002B49;
        z-index: 1999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .ek-mobile-menu-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .ek-mobile-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(122, 153, 172, 0.2);
    }

    .ek-mobile-title {
        font-family: 'Rocknroll One', sans-serif;
        font-size: 1.25rem;
        font-weight: 400;
        color: white;
    }

    .ek-mobile-close {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.1);
        border: none;
        border-radius: 12px;
        color: white;
        font-size: 1.25rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .ek-mobile-close:hover {
        background: var(--ek-orange);
    }

    .ek-mobile-links {
        flex: 1;
        overflow-y: auto;
        padding: 1rem 0;
    }

    .ek-mobile-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.5rem;
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
        font-size: 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }

    .ek-mobile-link:hover,
    .ek-mobile-link.active {
        background: rgba(237, 139, 0, 0.15);
        color: var(--ek-orange);
        border-left-color: var(--ek-orange);
    }

    .ek-mobile-link i {
        font-size: 0.9rem;
        opacity: 0.5;
    }

    .ek-mobile-sublink {
        padding-left: 2.5rem;
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.7);
    }

    .ek-mobile-sublink:hover {
        color: var(--ek-orange-light);
    }

    .ek-mobile-footer {
        padding: 1.5rem;
        border-top: 1px solid rgba(122, 153, 172, 0.2);
    }

    .ek-mobile-cta {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        width: 100%;
        padding: 1rem;
        background: var(--ek-orange);
        color: white;
        text-decoration: none;
        font-weight: 600;
        border-radius: 50px;
        transition: all 0.3s ease;
        margin-bottom: 1.25rem;
    }

    .ek-mobile-cta:hover {
        background: var(--ek-orange-light);
        transform: scale(1.02);
    }

    .ek-mobile-social {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
    }

    .ek-mobile-social a {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        color: white;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }

    .ek-mobile-social a:hover {
        background: var(--ek-orange);
        transform: scale(1.1);
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 992px) {
        .ek-navbar-links {
            display: none;
        }

        .ek-navbar-mobile-btn {
            display: flex;
        }

        .ek-navbar-actions {
            gap: 0.25rem;
        }

        .ek-navbar-btn {
            width: 40px;
            height: 40px;
        }
    }

    @media (max-width: 576px) {
        .ek-navbar-container {
            padding: 0 1rem;
            height: 64px;
        }

        .ek-navbar-logo {
            height: 144px;
        }

        .ek-mobile-menu {
            max-width: 100%;
        }
    }

    /* Spacer for fixed navbar */
    .ek-navbar-spacer {
        height: 72px;
        background: var(--ek-navy);
        /* Match hero/navbar theme to avoid white gap */
    }

    @media (max-width: 992px) {
        .ek-navbar-spacer {
            height: 80px;
            /* More space for the prominent logo */
        }
    }

    @media (max-width: 576px) {
        .ek-navbar-spacer {
            height: 72px;
        }
    }
</style>

<!-- ========== NAVBAR EKUORA ULTRA GLASS ========== -->
<nav class="ek-navbar" id="ekNavbar">
    <div class="ek-navbar-container">
        <!-- Logo -->
        <a href="<?= BASE_URL ?>" class="ek-navbar-brand">
            <!-- Logo Dinámico -->
            <?php
            $logoPath = $ajustes['logo_navbar'] ?? 'logo.svg';
            if (strpos($logoPath, 'uploads_privados/') === false && $logoPath !== 'logo.svg') {
                $logoPath = 'uploads_privados/' . $logoPath;
            }
            ?>
            <img src="<?= e(asset($logoPath)) ?>" alt="<?= APP_NAME ?? 'Ekuora' ?>" class="ek-navbar-logo">
        </a>

        <!-- Navigation Links -->
        <div class="ek-navbar-links">
            <div class="ek-navbar-item">
                <a href="<?= BASE_URL ?>productos"
                    class="ek-navbar-link <?= ($pagina_actual ?? '') === 'productos' && !isset($categoria) ? 'active' : '' ?>">
                    Ver Todo
                </a>
            </div>

            <?php if (!empty($categorias)): ?>
                <?php foreach ($categorias as $cat): ?>
                    <?php if (empty($cat['destacado']))
                        continue; ?>
                    <div class="ek-navbar-item">
                        <a href="<?= BASE_URL ?>productos/categoria/<?= e($cat['slug']) ?>"
                            class="ek-navbar-link <?= (isset($categoria) && $categoria['slug'] === $cat['slug']) ? 'active' : '' ?>">
                            <?= e($cat['nombre']) ?>
                            <?php if (!empty($cat['familias'])): ?>
                                <i class="bi bi-chevron-down"></i>
                            <?php endif; ?>
                        </a>
                        <?php if (!empty($cat['familias'])): ?>
                            <div class="ek-dropdown">
                                <?php foreach ($cat['familias'] as $fam): ?>
                                    <a href="<?= BASE_URL ?>productos/categoria/<?= e($cat['slug']) ?>?familia=<?= e($fam['slug']) ?>"
                                        class="ek-dropdown-item">
                                        <i class="bi bi-circle-fill"></i>
                                        <?= e($fam['nombre']) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="ek-navbar-item">
                <a href="<?= BASE_URL ?>#about" class="ek-navbar-link">
                    Acerca de
                </a>
            </div>
        </div>

        <!-- Actions -->
        <div class="ek-navbar-actions">
            <button class="ek-navbar-btn" onclick="toggleSearch()" aria-label="Buscar">
                <i class="bi bi-search"></i>
            </button>

            <?php if (isset($_SESSION['usuario'])): ?>
                <a href="<?= BASE_URL ?>dashboard" class="ek-navbar-link active" style="margin-left: 0.5rem;">
                    <i class="bi bi-speedometer2"></i> Admin
                </a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>login" class="ek-navbar-btn" aria-label="Cuenta">
                    <i class="bi bi-person"></i>
                </a>
            <?php endif; ?>

            <button class="ek-navbar-mobile-btn" onclick="toggleMobileMenu()" aria-label="Menú">
                <i class="bi bi-list"></i>
            </button>
        </div>
    </div>
    <!-- Search Overlay -->
    <div class="ek-search-overlay" id="searchOverlay">
        <div class="ek-search-container">
            <form action="<?= BASE_URL ?>productos/buscar" method="GET" class="ek-search-form">
                <input type="text" name="q" class="ek-search-input" placeholder="¿Qué estás buscando?"
                    autocomplete="off" autofocus>
                <button type="submit" class="ek-search-submit"><i class="bi bi-search"></i></button>
            </form>
            <button class="ek-search-close" onclick="toggleSearch()"><i class="bi bi-x-lg"></i></button>
        </div>
    </div>
</nav>

<!-- Mobile Menu Overlay -->
<div class="ek-mobile-menu-overlay" id="mobileOverlay" onclick="toggleMobileMenu()"></div>

<!-- ========== MOBILE MENU ========== -->
<div class="ek-mobile-menu" id="mobileMenu">
    <div class="ek-mobile-header">
        <span class="ek-mobile-title">Menú</span>
        <button class="ek-mobile-close" onclick="toggleMobileMenu()">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <div class="ek-mobile-links">
        <a href="<?= BASE_URL ?>productos" class="ek-mobile-link">
            Ver Todo <i class="bi bi-chevron-right"></i>
        </a>

        <?php if (!empty($categorias)): ?>
            <?php
            $processed_mobile_ids = [];
            foreach ($categorias as $cat):
                if (in_array($cat['id'], $processed_mobile_ids))
                    continue;
                $processed_mobile_ids[] = $cat['id'];
                ?>
                <a href="<?= BASE_URL ?>productos/categoria/<?= e($cat['slug']) ?>" class="ek-mobile-link">
                    <?= e($cat['nombre']) ?> <i class="bi bi-chevron-right"></i>
                </a>
                <?php if (!empty($cat['familias'])): ?>
                    <?php foreach ($cat['familias'] as $fam): ?>
                        <a href="<?= BASE_URL ?>productos/categoria/<?= e($cat['slug']) ?>?familia=<?= e($fam['slug']) ?>"
                            class="ek-mobile-link ek-mobile-sublink">
                            <span><i class="bi bi-dot"></i>
                                <?= e($fam['nombre']) ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>

        <a href="<?= BASE_URL ?>#about" class="ek-mobile-link">
            Acerca de <i class="bi bi-chevron-right"></i>
        </a>
    </div>

    <div class="ek-mobile-footer">
        <?php if (isset($_SESSION['usuario'])): ?>
            <a href="<?= BASE_URL ?>dashboard" class="ek-mobile-cta">
                <i class="bi bi-speedometer2"></i> Ir al Dashboard
            </a>
        <?php else: ?>
            <a href="<?= BASE_URL ?>login" class="ek-mobile-cta">
                <i class="bi bi-person"></i> Iniciar Sesión
            </a>
        <?php endif; ?>

        <div class="ek-mobile-social">
            <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
            <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
            <a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
            <a href="#" aria-label="TikTok"><i class="bi bi-tiktok"></i></a>
        </div>
    </div>
</div>

<!-- Spacer -->
<div class="ek-navbar-spacer"></div>

<script>
    // Navbar scroll effect
    window.addEventListener('scroll', function () {
        const navbar = document.getElementById('ekNavbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Mobile menu toggle
    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        const overlay = document.getElementById('mobileOverlay');
        menu.classList.toggle('active');
        overlay.classList.toggle('active');
        document.body.style.overflow = menu.classList.contains('active') ? 'hidden' : '';
    }

    // Close mobile menu on Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            const menu = document.getElementById('mobileMenu');
            if (menu && menu.classList.contains('active')) {
                toggleMobileMenu();
            }
        }
    });
    // Search Toggle
    function toggleSearch() {
        const overlay = document.getElementById('searchOverlay');
        const body = document.body;

        overlay.classList.toggle('active');

        if (overlay.classList.contains('active')) {
            body.style.overflow = 'hidden';
            setTimeout(() => {
                overlay.querySelector('.ek-search-input').focus();
            }, 300);
        } else {
            body.style.overflow = '';
        }
    }
</script>

<style>
    /* Search Overlay Styles */
    .ek-search-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background: rgba(255, 255, 255, 0.95);
        z-index: 3000;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ek-search-overlay.active {
        opacity: 1;
        pointer-events: all;
    }

    .ek-search-container {
        width: 100%;
        max-width: 800px;
        padding: 0 2rem;
        position: relative;
    }

    .ek-search-form {
        display: flex;
        align-items: center;
        border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        padding-bottom: 1rem;
    }

    .ek-search-input {
        width: 100%;
        background: transparent;
        border: none;
        color: white;
        font-size: 2rem;
        font-weight: 300;
        outline: none;
    }

    .ek-search-input::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }

    .ek-search-submit {
        background: transparent;
        border: none;
        color: var(--ek-orange);
        font-size: 2rem;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .ek-search-submit:hover {
        transform: scale(1.1);
    }

    .ek-search-close {
        position: absolute;
        top: -100px;
        right: 0;
        background: transparent;
        border: none;
        color: white;
        font-size: 2rem;
        cursor: pointer;
        transition: color 0.2s;
    }

    .ek-search-close:hover {
        color: var(--ek-orange);
    }
</style>