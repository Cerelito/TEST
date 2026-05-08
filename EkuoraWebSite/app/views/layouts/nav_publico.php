<style>
    /* ============================================
       CERELIT - LIQUID CRYSTAL NAVBAR
       Floating Pill · Glassmorphism · Plus Jakarta Sans
    ============================================ */

    :root {
        --cl-green:        #3A9D8A;
        --cl-green-light:  #4db8a3;
        --cl-blue:         #2A4D69;
        --cl-blue-dark:    #1e3a4f;
        --cl-orange:       #FF8C00;
        --cl-orange-light: #ffaa33;
        --cl-cream:        #F4F4F4;
        --cl-white:        #FFFFFF;
        --cl-text:         #1E293B;
        --cl-text-muted:   #64748b;

        --radius-pill: 9999px;
        --radius-xl:   3rem;
        --radius-lg:   2rem;
        --radius-md:   1.5rem;
        --radius-sm:   1rem;

        --glass-bg:     rgba(255, 255, 255, 0.88);
        --glass-border: rgba(58, 157, 138, 0.18);
        --shadow-nav:   0 8px 40px rgba(42, 77, 105, 0.12), 0 2px 8px rgba(0,0,0,0.06);

        --transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* ========== FLOATING PILL NAVBAR ========== */
    .cl-navbar {
        position: fixed;
        top: 1rem;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1000;
        width: calc(100% - 3rem);
        max-width: 1200px;
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-pill);
        box-shadow: var(--shadow-nav);
        transition: all var(--transition);
    }

    .cl-navbar.at-top {
        background: rgba(255, 255, 255, 0.72);
        border-color: rgba(58, 157, 138, 0.1);
    }

    .cl-navbar.scrolled {
        background: rgba(255, 255, 255, 0.96);
        box-shadow: 0 12px 48px rgba(42, 77, 105, 0.18), 0 2px 8px rgba(0,0,0,0.08);
        border-color: rgba(58, 157, 138, 0.25);
    }

    .cl-navbar-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 1.5rem;
        height: 64px;
    }

    /* Logo */
    .cl-navbar-brand {
        display: flex;
        align-items: center;
        text-decoration: none;
        flex-shrink: 0;
    }

    .cl-navbar-logo {
        height: 40px;
        width: auto;
        transition: transform var(--transition);
    }

    .cl-navbar-brand:hover .cl-navbar-logo {
        transform: scale(1.05);
    }

    /* Navigation Links */
    .cl-navbar-links {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .cl-navbar-item {
        position: relative;
    }

    .cl-navbar-link {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 0.55rem 1.1rem;
        color: var(--cl-text);
        text-decoration: none;
        font-family: 'Inter', sans-serif;
        font-size: 0.9rem;
        font-weight: 500;
        border-radius: var(--radius-pill);
        transition: all var(--transition);
        white-space: nowrap;
    }

    .cl-navbar-link:hover {
        color: var(--cl-green);
        background: rgba(58, 157, 138, 0.08);
    }

    .cl-navbar-link.active {
        color: var(--cl-white);
        background: var(--cl-green);
        box-shadow: 0 4px 16px rgba(58, 157, 138, 0.35);
    }

    .cl-navbar-link i.chevron {
        font-size: 0.65rem;
        transition: transform var(--transition);
    }

    .cl-navbar-item:hover .cl-navbar-link i.chevron {
        transform: rotate(180deg);
    }

    /* Dropdown */
    .cl-dropdown {
        position: absolute;
        top: calc(100% + 0.5rem);
        left: 50%;
        transform: translateX(-50%) translateY(8px);
        min-width: 220px;
        background: var(--cl-white);
        border: 1px solid rgba(58, 157, 138, 0.12);
        border-radius: var(--radius-md);
        box-shadow: 0 20px 50px rgba(42, 77, 105, 0.18);
        padding: 0.6rem;
        opacity: 0;
        visibility: hidden;
        transition: all var(--transition);
        z-index: 100;
    }

    .cl-navbar-item:hover .cl-dropdown {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(0);
    }

    .cl-dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 0.65rem 1rem;
        color: var(--cl-text);
        text-decoration: none;
        font-family: 'Inter', sans-serif;
        font-size: 0.88rem;
        font-weight: 500;
        border-radius: var(--radius-sm);
        transition: all 0.2s ease;
    }

    .cl-dropdown-item:hover {
        background: rgba(58, 157, 138, 0.08);
        color: var(--cl-green);
        padding-left: 1.25rem;
    }

    .cl-dropdown-item i {
        font-size: 0.5rem;
        color: var(--cl-orange);
        flex-shrink: 0;
    }

    /* Actions */
    .cl-navbar-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-shrink: 0;
    }

    .cl-navbar-icon-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: transparent;
        border: 1.5px solid rgba(42, 77, 105, 0.12);
        border-radius: var(--radius-pill);
        color: var(--cl-text);
        font-size: 1rem;
        cursor: pointer;
        transition: all var(--transition);
        text-decoration: none;
    }

    .cl-navbar-icon-btn:hover {
        background: var(--cl-green);
        border-color: var(--cl-green);
        color: white;
        transform: scale(1.08);
        box-shadow: 0 4px 16px rgba(58, 157, 138, 0.4);
    }

    .cl-navbar-cta {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1.25rem;
        background: var(--cl-orange);
        color: white;
        text-decoration: none;
        font-family: 'Inter', sans-serif;
        font-size: 0.88rem;
        font-weight: 600;
        border-radius: var(--radius-pill);
        transition: all var(--transition);
        box-shadow: 0 4px 16px rgba(255, 140, 0, 0.3);
        border: none;
        cursor: pointer;
    }

    .cl-navbar-cta:hover {
        background: var(--cl-orange-light);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(255, 140, 0, 0.4);
    }

    /* Mobile toggle */
    .cl-navbar-mobile-btn {
        display: none;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: rgba(42, 77, 105, 0.06);
        border: 1.5px solid rgba(42, 77, 105, 0.12);
        border-radius: var(--radius-sm);
        color: var(--cl-text);
        font-size: 1.4rem;
        cursor: pointer;
        transition: all var(--transition);
    }

    .cl-navbar-mobile-btn:hover {
        background: var(--cl-green);
        border-color: var(--cl-green);
        color: white;
    }

    /* ========== MOBILE DRAWER ========== */
    .cl-mobile-overlay {
        position: fixed;
        inset: 0;
        background: rgba(30, 41, 59, 0.5);
        backdrop-filter: blur(4px);
        z-index: 1998;
        opacity: 0;
        visibility: hidden;
        transition: all var(--transition);
    }

    .cl-mobile-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .cl-mobile-menu {
        position: fixed;
        top: 0;
        right: -100%;
        width: min(380px, 100vw);
        height: 100dvh;
        background: var(--cl-white);
        z-index: 1999;
        display: flex;
        flex-direction: column;
        transition: right 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: -20px 0 60px rgba(42, 77, 105, 0.2);
        border-radius: var(--radius-lg) 0 0 var(--radius-lg);
        overflow: hidden;
    }

    .cl-mobile-menu.active {
        right: 0;
    }

    .cl-mobile-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, var(--cl-green) 0%, var(--cl-blue) 100%);
    }

    .cl-mobile-header img {
        height: 36px;
        width: auto;
        filter: brightness(0) invert(1);
    }

    .cl-mobile-close {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        background: rgba(255,255,255,0.2);
        border: none;
        border-radius: var(--radius-sm);
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
        transition: all var(--transition);
    }

    .cl-mobile-close:hover {
        background: rgba(255,255,255,0.35);
    }

    .cl-mobile-links {
        flex: 1;
        overflow-y: auto;
        padding: 1.25rem 0;
    }

    .cl-mobile-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.95rem 1.5rem;
        color: var(--cl-text);
        text-decoration: none;
        font-family: 'Inter', sans-serif;
        font-size: 0.95rem;
        font-weight: 500;
        transition: all var(--transition);
        border-left: 3px solid transparent;
    }

    .cl-mobile-link:hover,
    .cl-mobile-link.active {
        background: rgba(58, 157, 138, 0.06);
        color: var(--cl-green);
        border-left-color: var(--cl-green);
    }

    .cl-mobile-link i {
        font-size: 0.85rem;
        color: var(--cl-text-muted);
    }

    .cl-mobile-sublink {
        padding-left: 2.5rem;
        font-size: 0.88rem;
        color: var(--cl-text-muted);
    }

    .cl-mobile-sublink:hover {
        color: var(--cl-green);
    }

    .cl-mobile-footer {
        padding: 1.25rem 1.5rem;
        border-top: 1px solid rgba(42, 77, 105, 0.08);
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .cl-mobile-cta {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.65rem;
        width: 100%;
        padding: 0.95rem;
        background: var(--cl-orange);
        color: white;
        text-decoration: none;
        font-family: 'Inter', sans-serif;
        font-weight: 600;
        border-radius: var(--radius-pill);
        transition: all var(--transition);
        font-size: 0.95rem;
    }

    .cl-mobile-cta:hover {
        background: var(--cl-orange-light);
        transform: scale(1.02);
        color: white;
    }

    .cl-mobile-social {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
    }

    .cl-mobile-social a {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        background: rgba(58, 157, 138, 0.08);
        border: 1px solid rgba(58, 157, 138, 0.15);
        border-radius: var(--radius-pill);
        color: var(--cl-green);
        font-size: 1rem;
        transition: all var(--transition);
        text-decoration: none;
    }

    .cl-mobile-social a:hover {
        background: var(--cl-green);
        color: white;
        transform: scale(1.1);
        box-shadow: 0 4px 16px rgba(58, 157, 138, 0.4);
    }

    /* ========== SEARCH OVERLAY ========== */
    .cl-search-overlay {
        position: fixed;
        inset: 0;
        background: rgba(255, 255, 255, 0.97);
        backdrop-filter: blur(20px);
        z-index: 3000;
        opacity: 0;
        pointer-events: none;
        transition: opacity var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cl-search-overlay.active {
        opacity: 1;
        pointer-events: all;
    }

    .cl-search-container {
        width: 100%;
        max-width: 700px;
        padding: 0 2rem;
        position: relative;
    }

    .cl-search-form {
        display: flex;
        align-items: center;
        border-bottom: 2px solid var(--cl-green);
        padding-bottom: 0.75rem;
    }

    .cl-search-input {
        flex: 1;
        background: transparent;
        border: none;
        color: var(--cl-text);
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 2rem;
        font-weight: 300;
        outline: none;
    }

    .cl-search-input::placeholder {
        color: rgba(30, 41, 59, 0.35);
    }

    .cl-search-submit {
        background: transparent;
        border: none;
        color: var(--cl-green);
        font-size: 1.75rem;
        cursor: pointer;
        transition: transform 0.2s;
        padding: 0;
    }

    .cl-search-submit:hover {
        transform: scale(1.12);
    }

    .cl-search-close {
        position: absolute;
        top: -80px;
        right: 0;
        background: transparent;
        border: none;
        color: var(--cl-text-muted);
        font-size: 1.75rem;
        cursor: pointer;
        transition: color var(--transition);
        padding: 0;
    }

    .cl-search-close:hover {
        color: var(--cl-orange);
    }

    /* ========== NAVBAR SPACER ========== */
    .cl-navbar-spacer {
        height: 90px;
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 1024px) {
        .cl-navbar-links {
            display: none;
        }

        .cl-navbar-mobile-btn {
            display: flex;
        }
    }

    @media (max-width: 768px) {
        .cl-navbar {
            top: 0.75rem;
            width: calc(100% - 2rem);
        }

        .cl-navbar-container {
            height: 58px;
            padding: 0 1.25rem;
        }

        .cl-navbar-logo {
            height: 34px;
        }

        .cl-navbar-spacer {
            height: 82px;
        }

        .cl-search-input {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 480px) {
        .cl-navbar {
            top: 0.5rem;
            width: calc(100% - 1.5rem);
        }

        .cl-navbar-spacer {
            height: 74px;
        }
    }
</style>

<!-- ========== FLOATING PILL NAVBAR ========== -->
<nav class="cl-navbar at-top" id="clNavbar">
    <div class="cl-navbar-container">

        <!-- Logo -->
        <a href="<?= BASE_URL ?>" class="cl-navbar-brand">
            <?php
            $logoPath = $ajustes['logo_navbar'] ?? 'logo.svg';
            if (strpos($logoPath, 'uploads_privados/') === false && $logoPath !== 'logo.svg') {
                $logoPath = 'uploads_privados/' . $logoPath;
            }
            ?>
            <img src="<?= e(asset($logoPath)) ?>" alt="<?= APP_NAME ?? 'Cerelit' ?>" class="cl-navbar-logo">
        </a>

        <!-- Desktop Nav Links -->
        <div class="cl-navbar-links">
            <div class="cl-navbar-item">
                <a href="<?= BASE_URL ?>productos"
                    class="cl-navbar-link <?= ($pagina_actual ?? '') === 'productos' && !isset($categoria) ? 'active' : '' ?>">
                    Ver Todo
                </a>
            </div>

            <?php if (!empty($categorias)): ?>
                <?php foreach ($categorias as $cat): ?>
                    <?php if (empty($cat['destacado'])) continue; ?>
                    <div class="cl-navbar-item">
                        <a href="<?= BASE_URL ?>productos/categoria/<?= e($cat['slug']) ?>"
                            class="cl-navbar-link <?= (isset($categoria) && $categoria['slug'] === $cat['slug']) ? 'active' : '' ?>">
                            <?= e($cat['nombre']) ?>
                            <?php if (!empty($cat['familias'])): ?>
                                <i class="bi bi-chevron-down chevron"></i>
                            <?php endif; ?>
                        </a>
                        <?php if (!empty($cat['familias'])): ?>
                            <div class="cl-dropdown">
                                <?php foreach ($cat['familias'] as $fam): ?>
                                    <a href="<?= BASE_URL ?>productos/categoria/<?= e($cat['slug']) ?>?familia=<?= e($fam['slug']) ?>"
                                        class="cl-dropdown-item">
                                        <i class="bi bi-circle-fill"></i>
                                        <?= e($fam['nombre']) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="cl-navbar-item">
                <a href="<?= BASE_URL ?>#about" class="cl-navbar-link">Acerca de</a>
            </div>
        </div>

        <!-- Actions -->
        <div class="cl-navbar-actions">
            <button class="cl-navbar-icon-btn" onclick="clToggleSearch()" aria-label="Buscar">
                <i class="bi bi-search"></i>
            </button>

            <?php if (isset($_SESSION['usuario'])): ?>
                <a href="<?= BASE_URL ?>dashboard" class="cl-navbar-cta">
                    <i class="bi bi-speedometer2"></i> Admin
                </a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>login" class="cl-navbar-icon-btn" aria-label="Cuenta">
                    <i class="bi bi-person"></i>
                </a>
            <?php endif; ?>

            <button class="cl-navbar-mobile-btn" onclick="clToggleMobileMenu()" aria-label="Menú">
                <i class="bi bi-list"></i>
            </button>
        </div>
    </div>

    <!-- Search Overlay -->
    <div class="cl-search-overlay" id="clSearchOverlay">
        <div class="cl-search-container">
            <form action="<?= BASE_URL ?>productos/buscar" method="GET" class="cl-search-form">
                <input type="text" name="q" class="cl-search-input" placeholder="¿Qué estás buscando?" autocomplete="off" autofocus>
                <button type="submit" class="cl-search-submit"><i class="bi bi-search"></i></button>
            </form>
            <button class="cl-search-close" onclick="clToggleSearch()"><i class="bi bi-x-lg"></i></button>
        </div>
    </div>
</nav>

<!-- Mobile Overlay -->
<div class="cl-mobile-overlay" id="clMobileOverlay" onclick="clToggleMobileMenu()"></div>

<!-- Mobile Drawer -->
<div class="cl-mobile-menu" id="clMobileMenu">
    <div class="cl-mobile-header">
        <img src="<?= e(asset($logoPath)) ?>" alt="<?= APP_NAME ?? 'Cerelit' ?>">
        <button class="cl-mobile-close" onclick="clToggleMobileMenu()"><i class="bi bi-x-lg"></i></button>
    </div>

    <div class="cl-mobile-links">
        <a href="<?= BASE_URL ?>productos" class="cl-mobile-link <?= ($pagina_actual ?? '') === 'productos' ? 'active' : '' ?>">
            Ver Todo <i class="bi bi-chevron-right"></i>
        </a>

        <?php if (!empty($categorias)):
            $seen_mobile = [];
            foreach ($categorias as $cat):
                if (in_array($cat['id'], $seen_mobile)) continue;
                $seen_mobile[] = $cat['id'];
                ?>
                <a href="<?= BASE_URL ?>productos/categoria/<?= e($cat['slug']) ?>" class="cl-mobile-link">
                    <?= e($cat['nombre']) ?> <i class="bi bi-chevron-right"></i>
                </a>
                <?php if (!empty($cat['familias'])): ?>
                    <?php foreach ($cat['familias'] as $fam): ?>
                        <a href="<?= BASE_URL ?>productos/categoria/<?= e($cat['slug']) ?>?familia=<?= e($fam['slug']) ?>"
                            class="cl-mobile-link cl-mobile-sublink">
                            <span><i class="bi bi-dot"></i> <?= e($fam['nombre']) ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach;
        endif; ?>

        <a href="<?= BASE_URL ?>#about" class="cl-mobile-link">
            Acerca de <i class="bi bi-chevron-right"></i>
        </a>
    </div>

    <div class="cl-mobile-footer">
        <?php if (isset($_SESSION['usuario'])): ?>
            <a href="<?= BASE_URL ?>dashboard" class="cl-mobile-cta">
                <i class="bi bi-speedometer2"></i> Ir al Dashboard
            </a>
        <?php else: ?>
            <a href="<?= BASE_URL ?>login" class="cl-mobile-cta">
                <i class="bi bi-person"></i> Iniciar Sesión
            </a>
        <?php endif; ?>

        <div class="cl-mobile-social">
            <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
            <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
            <a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
            <a href="#" aria-label="TikTok"><i class="bi bi-tiktok"></i></a>
        </div>
    </div>
</div>

<!-- Spacer -->
<div class="cl-navbar-spacer"></div>

<script>
    // Navbar scroll glass effect
    (function () {
        const nav = document.getElementById('clNavbar');
        const onScroll = () => {
            if (window.scrollY > 40) {
                nav.classList.remove('at-top');
                nav.classList.add('scrolled');
            } else {
                nav.classList.add('at-top');
                nav.classList.remove('scrolled');
            }
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    })();

    function clToggleMobileMenu() {
        const menu = document.getElementById('clMobileMenu');
        const overlay = document.getElementById('clMobileOverlay');
        const isOpen = menu.classList.toggle('active');
        overlay.classList.toggle('active', isOpen);
        document.body.style.overflow = isOpen ? 'hidden' : '';
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            const menu = document.getElementById('clMobileMenu');
            if (menu && menu.classList.contains('active')) clToggleMobileMenu();
            const search = document.getElementById('clSearchOverlay');
            if (search && search.classList.contains('active')) clToggleSearch();
        }
    });

    function clToggleSearch() {
        const overlay = document.getElementById('clSearchOverlay');
        const isOpen = overlay.classList.toggle('active');
        document.body.style.overflow = isOpen ? 'hidden' : '';
        if (isOpen) {
            setTimeout(() => overlay.querySelector('.cl-search-input')?.focus(), 200);
        }
    }

    // Keep backward-compat alias used in footer
    function toggleSearch() { clToggleSearch(); }
</script>
