<style>
    /* ============================================
       EKUORA FOOTER - ULTRA GLASS PANTONE
       VERSIÓN RESPONSIVA MEJORADA
    ============================================ */

    .ek-footer {
        background: linear-gradient(180deg, var(--ek-navy) 0%, #001a2e 100%);
        color: white;
        margin-top: auto;
    }

    .ek-footer-main {
        max-width: 1400px;
        margin: 0 auto;
        padding: 4rem 1.5rem 3rem;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 3rem;
        align-items: start;
    }

    /* Brand Column */
    .ek-footer-brand {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 1.5rem;
    }

    .ek-footer-logo img {
        height: 60px;
        width: auto;
    }

    .ek-footer-desc {
        color: var(--ek-sky-light);
        font-size: 0.95rem;
        line-height: 1.7;
        margin-bottom: 1.5rem;
        max-width: 280px;
    }

    .ek-footer-social {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .ek-footer-social a,
    .ek-social-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        background: rgba(122, 153, 172, 0.15);
        border: 1px solid rgba(122, 153, 172, 0.2);
        border-radius: 12px;
        color: white;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .ek-footer-social a:hover,
    .ek-social-link:hover {
        background: var(--ek-orange);
        border-color: var(--ek-orange);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(237, 139, 0, 0.3);
    }

    /* Links Columns */
    .ek-footer-col h4 {
        font-family: 'Rocknroll One', sans-serif;
        font-size: 1.1rem;
        font-weight: 400;
        color: white;
        margin-bottom: 1.5rem;
        position: relative;
        padding-bottom: 0.75rem;
    }

    .ek-footer-col h4::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 3px;
        background: var(--ek-orange);
        border-radius: 2px;
    }

    .ek-footer-links {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .ek-footer-links a {
        color: var(--ek-sky-light);
        text-decoration: none;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .ek-footer-links a:hover {
        color: var(--ek-orange);
        padding-left: 8px;
    }

    .ek-footer-links a i {
        font-size: 0.6rem;
        color: var(--ek-orange);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .ek-footer-links a:hover i {
        opacity: 1;
    }

    /* Contact Info */
    .ek-footer-contact {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .ek-footer-contact-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        color: var(--ek-sky-light);
        font-size: 0.95rem;
        word-break: break-word;
    }

    .ek-footer-contact-item i {
        font-size: 1.1rem;
        color: var(--ek-orange);
        margin-top: 2px;
        flex-shrink: 0;
    }

    .ek-footer-contact-item a {
        color: var(--ek-sky-light);
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .ek-footer-contact-item a:hover {
        color: var(--ek-orange);
    }

    /* Newsletter (Optional) */
    .ek-footer-newsletter {
        margin-top: 1rem;
    }

    .ek-footer-newsletter-form {
        display: flex;
        gap: 0.5rem;
    }

    .ek-footer-newsletter input {
        flex: 1;
        padding: 0.75rem 1rem;
        background: rgba(122, 153, 172, 0.15);
        border: 1px solid rgba(122, 153, 172, 0.2);
        border-radius: 50px;
        color: white;
        font-size: 0.9rem;
        outline: none;
        transition: all 0.3s ease;
    }

    .ek-footer-newsletter input::placeholder {
        color: var(--ek-sky);
    }

    .ek-footer-newsletter input:focus {
        border-color: var(--ek-orange);
        background: rgba(122, 153, 172, 0.25);
    }

    .ek-footer-newsletter button {
        padding: 0.75rem 1.5rem;
        background: var(--ek-orange);
        border: none;
        border-radius: 50px;
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .ek-footer-newsletter button:hover {
        background: var(--ek-orange-light);
        transform: scale(1.05);
    }

    /* Bottom Bar */
    .ek-footer-bottom {
        border-top: 1px solid rgba(122, 153, 172, 0.15);
    }

    .ek-footer-bottom-inner {
        max-width: 1400px;
        margin: 0 auto;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .ek-footer-copyright {
        color: var(--ek-sky);
        font-size: 0.9rem;
    }

    .ek-footer-copyright a {
        color: var(--ek-orange);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .ek-footer-copyright a:hover {
        color: var(--ek-orange-light);
    }

    .ek-footer-legal {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .ek-footer-legal a {
        color: var(--ek-sky);
        text-decoration: none;
        font-size: 0.85rem;
        transition: color 0.3s ease;
    }

    .ek-footer-legal a:hover {
        color: white;
    }

    /* ========== RESPONSIVE ========== */

    /* Tablet */
    @media (max-width: 992px) {
        .ek-footer-main {
            grid-template-columns: 1fr 1fr;
            gap: 2.5rem;
            padding: 3rem 1.5rem 2.5rem;
        }

        .ek-footer-brand {
            grid-column: 1 / -1;
            align-items: center;
            text-align: center;
        }

        .ek-footer-desc {
            max-width: 400px;
            text-align: center;
        }

        .ek-footer-col {
            display: flex;
            flex-direction: column;
        }

        .ek-footer-col h4::after {
            left: 50%;
            transform: translateX(-50%);
        }
    }

    /* Mobile */
    @media (max-width: 768px) {
        .ek-footer-main {
            grid-template-columns: 1fr;
            gap: 2rem;
            text-align: center;
            padding: 2.5rem 1rem 2rem;
        }

        .ek-footer-brand {
            align-items: center;
            gap: 1rem;
        }

        .ek-footer-brand .ek-footer-logo {
            height: 60px !important;
        }

        .ek-footer-desc {
            max-width: 100%;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .ek-footer-social {
            justify-content: center;
        }

        .ek-footer-col {
            align-items: center;
        }

        .ek-footer-col h4 {
            text-align: center;
            margin-bottom: 1.25rem;
        }

        .ek-footer-col h4::after {
            left: 50%;
            transform: translateX(-50%);
        }

        .ek-footer-contact {
            align-items: center;
        }

        .ek-footer-contact-item {
            justify-content: center;
            text-align: left;
        }

        .ek-footer-bottom-inner {
            flex-direction: column;
            text-align: center;
            padding: 1.25rem 1rem;
            gap: 0.75rem;
        }

        .ek-footer-copyright {
            font-size: 0.85rem;
            order: 2;
        }

        .ek-footer-legal {
            justify-content: center;
            gap: 1rem;
            order: 1;
        }

        .ek-footer-legal a {
            font-size: 0.8rem;
        }
    }

    /* Mobile pequeño */
    @media (max-width: 480px) {
        .ek-footer-main {
            padding: 2rem 0.75rem 1.5rem;
            gap: 1.75rem;
        }

        .ek-footer-brand .ek-footer-logo {
            height: 50px !important;
        }

        .ek-footer-desc {
            font-size: 0.85rem;
            line-height: 1.6;
        }

        .ek-footer-social a,
        .ek-social-link {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }

        .ek-footer-contact-item {
            font-size: 0.85rem;
        }

        .ek-footer-legal {
            flex-direction: column;
            gap: 0.5rem;
        }

        .ek-footer-copyright {
            font-size: 0.8rem;
        }
    }
</style>

<!-- ========== FOOTER EKUORA ULTRA GLASS ========== -->
<footer class="ek-footer">
    <div class="ek-footer-main">
        <!-- Col 1: Brand & Description -->
        <div class="ek-footer-brand">
            <?php
            $logoFooter = $ajustes['logo_navbar'] ?? 'logo.svg';
            if (strpos($logoFooter, 'uploads_privados/') === false && $logoFooter !== 'logo.svg') {
                $logoFooter = 'uploads_privados/' . $logoFooter;
            }
            ?>
            <img src="<?= e(asset($logoFooter)) ?>" alt="<?= APP_NAME ?? 'Ekuora' ?>" class="ek-footer-logo"
                style="height: 80px; width: auto; object-fit: contain; max-width: 200px;">
            <p class="ek-footer-desc">
                <?= !empty($ajustes['footer_texto']) ? e($ajustes['footer_texto']) : 'Transformamos espacios con diseño.' ?>
            </p>
        </div>

        <!-- Col 2: Contact Info -->
        <div class="ek-footer-col">
            <div class="ek-footer-contact">
                <?php if (!empty($ajustes['footer_email'])): ?>
                    <div class="ek-footer-contact-item">
                        <i class="bi bi-envelope"></i>
                        <a href="mailto:<?= e($ajustes['footer_email']) ?>">
                            <?= e($ajustes['footer_email']) ?>
                        </a>
                    </div>
                <?php endif; ?>

                <?php if (!empty($ajustes['footer_telefono'])): ?>
                    <div class="ek-footer-contact-item">
                        <i class="bi bi-telephone"></i>
                        <span>
                            <?= e($ajustes['footer_telefono']) ?>
                        </span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($ajustes['footer_direccion'])): ?>
                    <div class="ek-footer-contact-item">
                        <i class="bi bi-geo-alt"></i>
                        <span>
                            <?= e($ajustes['footer_direccion']) ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Col 3: Social Icons -->
        <div class="ek-footer-col">
            <?php
            $hasSocials = !empty($ajustes['footer_facebook']) || !empty($ajustes['footer_instagram']) || !empty($ajustes['footer_youtube']) || !empty($ajustes['footer_tiktok']);
            if ($hasSocials):
                ?>
                <h4 style="margin: 0 0 1rem 0; font-size: 1rem; color: var(--ek-orange); font-weight: 600;">Síguenos</h4>
                <div class="ek-footer-social">
                    <?php if (!empty($ajustes['footer_facebook'])): ?>
                        <a href="<?= e($ajustes['footer_facebook']) ?>" target="_blank" rel="noopener" class="ek-social-link"
                            aria-label="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($ajustes['footer_instagram'])): ?>
                        <a href="<?= e($ajustes['footer_instagram']) ?>" target="_blank" rel="noopener" class="ek-social-link"
                            aria-label="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($ajustes['footer_youtube'])): ?>
                        <a href="<?= e($ajustes['footer_youtube']) ?>" target="_blank" rel="noopener" class="ek-social-link"
                            aria-label="YouTube">
                            <i class="bi bi-youtube"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($ajustes['footer_tiktok'])): ?>
                        <a href="<?= e($ajustes['footer_tiktok']) ?>" target="_blank" rel="noopener" class="ek-social-link"
                            aria-label="TikTok">
                            <i class="bi bi-tiktok"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="ek-footer-bottom">
        <div class="ek-footer-bottom-inner">
            <p class="ek-footer-copyright">
                &copy;
                <?= date('Y') ?>
                <?= APP_NAME ?? 'Ekuora' ?> &mdash; Desarrollado por
                <a href="https://www.apotemaone.com" target="_blank" rel="noopener">Apotema One</a>
            </p>
            <div class="ek-footer-legal">
                <a href="#">Términos y Condiciones</a>
                <a href="#">Política de Privacidad</a>
                <a href="#">Cookies</a>
            </div>
        </div>
    </div>
</footer>

<?php include_once VIEWS_PATH . 'layouts/cookies.php'; ?>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- ========== SCRIPTS ========== -->
<script>
    // Load saved theme (Force Light for now if preference exists)
    (function () {
        document.documentElement.setAttribute('data-theme', 'light');
    })();

    // Search Logic (Implementation in nav_publico.php, this is a bridge if needed)
    function toggleSearch() {
        const overlay = document.getElementById('searchOverlay');
        if (overlay) {
            overlay.classList.toggle('active');
            if (overlay.classList.contains('active')) {
                overlay.querySelector('input').focus();
            }
        }
    }

    // Process Flash Messages with SweetAlert2
    document.addEventListener('DOMContentLoaded', function () {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            background: 'rgba(255, 255, 255, 0.95)',
            backdrop: '#002B49',
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        <?php
        $flashSuccess = getFlash('success');
        $flashError = getFlash('error');
        $flashWarning = getFlash('warning');
        $flashInfo = getFlash('info');
        ?>

        <?php if ($flashSuccess): ?>
            Toast.fire({ icon: 'success', title: '<?= e($flashSuccess) ?>' });
        <?php endif; ?>

        <?php if ($flashError): ?>
            Toast.fire({ icon: 'error', title: '<?= e($flashError) ?>' });
        <?php endif; ?>

        <?php if ($flashWarning): ?>
            Toast.fire({ icon: 'warning', title: '<?= e($flashWarning) ?>' });
        <?php endif; ?>

        <?php if ($flashInfo): ?>
            Toast.fire({ icon: 'info', title: '<?= e($flashInfo) ?>' });
        <?php endif; ?>
    });

    // Smooth scroll for anchors
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href === '#' || !href.startsWith('#')) return;
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
</script>

<?php if (isset($scripts)): ?>
    <?php foreach ($scripts as $script): ?>
        <script src="<?= BASE_URL ?>js/<?= $script ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>