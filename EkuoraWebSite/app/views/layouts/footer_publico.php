<style>
    /* ============================================
       CERELIT · LIQUID CRYSTAL FOOTER
    ============================================ */

    .cl-footer {
        background: linear-gradient(180deg, var(--cl-blue) 0%, var(--cl-blue-dark) 100%);
        color: white;
        margin-top: auto;
        position: relative;
        overflow: hidden;
    }

    .cl-footer::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(58,157,138,0.5), transparent);
    }

    /* Decorative orb */
    .cl-footer::after {
        content: '';
        position: absolute;
        top: -30%;
        right: -10%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(58,157,138,0.1) 0%, transparent 65%);
        border-radius: 50%;
        pointer-events: none;
    }

    .cl-footer-main {
        position: relative;
        z-index: 1;
        max-width: 1280px;
        margin: 0 auto;
        padding: 4.5rem 1.5rem 3.5rem;
        display: grid;
        grid-template-columns: 1.4fr 1fr 1fr;
        gap: 3rem;
        align-items: start;
    }

    /* Brand Column */
    .cl-footer-brand {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .cl-footer-logo img {
        height: 52px;
        width: auto;
        object-fit: contain;
        filter: brightness(0) invert(1);
    }

    .cl-footer-desc {
        color: rgba(255,255,255,0.65);
        font-family: var(--font-body, 'Inter', sans-serif);
        font-size: 0.92rem;
        line-height: 1.75;
        max-width: 300px;
    }

    .cl-footer-social {
        display: flex;
        gap: 0.65rem;
        flex-wrap: wrap;
    }

    .cl-footer-social a,
    .cl-social-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 999px;
        color: rgba(255,255,255,0.8);
        font-size: 1rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .cl-footer-social a:hover,
    .cl-social-link:hover {
        background: var(--cl-green, #3A9D8A);
        border-color: var(--cl-green, #3A9D8A);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(58,157,138,0.4);
    }

    /* Link Columns */
    .cl-footer-col h4 {
        font-family: var(--font-display, 'Plus Jakarta Sans', sans-serif);
        font-size: 1rem;
        font-weight: 700;
        color: white;
        margin-bottom: 1.5rem;
        position: relative;
        padding-bottom: 0.75rem;
    }

    .cl-footer-col h4::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 32px;
        height: 3px;
        background: var(--cl-green, #3A9D8A);
        border-radius: 999px;
    }

    .cl-footer-links {
        display: flex;
        flex-direction: column;
        gap: 0.7rem;
    }

    .cl-footer-links a {
        color: rgba(255,255,255,0.6);
        text-decoration: none;
        font-family: var(--font-body, 'Inter', sans-serif);
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.25s ease;
    }

    .cl-footer-links a:hover {
        color: var(--cl-green-light, #4db8a3);
        padding-left: 6px;
    }

    .cl-footer-links a i {
        font-size: 0.55rem;
        color: var(--cl-orange, #FF8C00);
        opacity: 0;
        transition: opacity 0.25s;
    }

    .cl-footer-links a:hover i {
        opacity: 1;
    }

    /* Contact Info */
    .cl-footer-contact {
        display: flex;
        flex-direction: column;
        gap: 0.9rem;
    }

    .cl-footer-contact-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        color: rgba(255,255,255,0.6);
        font-family: var(--font-body, 'Inter', sans-serif);
        font-size: 0.9rem;
        word-break: break-word;
    }

    .cl-footer-contact-item i {
        color: var(--cl-green, #3A9D8A);
        margin-top: 2px;
        flex-shrink: 0;
        font-size: 1rem;
    }

    .cl-footer-contact-item a {
        color: rgba(255,255,255,0.6);
        text-decoration: none;
        transition: color 0.25s;
    }

    .cl-footer-contact-item a:hover {
        color: var(--cl-green-light, #4db8a3);
    }

    /* Bottom Bar */
    .cl-footer-bottom {
        position: relative;
        z-index: 1;
        border-top: 1px solid rgba(255,255,255,0.08);
    }

    .cl-footer-bottom-inner {
        max-width: 1280px;
        margin: 0 auto;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .cl-footer-copyright {
        color: rgba(255,255,255,0.45);
        font-family: var(--font-body, 'Inter', sans-serif);
        font-size: 0.85rem;
    }

    .cl-footer-copyright a {
        color: var(--cl-green, #3A9D8A);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.25s;
    }

    .cl-footer-copyright a:hover {
        color: var(--cl-green-light, #4db8a3);
    }

    .cl-footer-legal {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .cl-footer-legal a {
        color: rgba(255,255,255,0.45);
        text-decoration: none;
        font-family: var(--font-body, 'Inter', sans-serif);
        font-size: 0.82rem;
        transition: color 0.25s;
    }

    .cl-footer-legal a:hover {
        color: rgba(255,255,255,0.85);
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 992px) {
        .cl-footer-main {
            grid-template-columns: 1fr 1fr;
            gap: 2.5rem;
            padding: 3.5rem 1.5rem 2.5rem;
        }

        .cl-footer-brand {
            grid-column: 1 / -1;
            align-items: center;
            text-align: center;
        }

        .cl-footer-desc { max-width: 420px; text-align: center; }
        .cl-footer-col { display: flex; flex-direction: column; align-items: flex-start; }
        .cl-footer-col h4::after { left: 0; }
    }

    @media (max-width: 768px) {
        .cl-footer-main {
            grid-template-columns: 1fr;
            text-align: center;
            padding: 3rem 1.25rem 2rem;
        }

        .cl-footer-brand { align-items: center; }
        .cl-footer-social { justify-content: center; }
        .cl-footer-col { align-items: center; }
        .cl-footer-col h4::after { left: 50%; transform: translateX(-50%); }
        .cl-footer-contact { align-items: center; }
        .cl-footer-contact-item { justify-content: center; text-align: left; }
        .cl-footer-bottom-inner { flex-direction: column; text-align: center; padding: 1.25rem 1rem; }
        .cl-footer-legal { justify-content: center; }
    }

    @media (max-width: 480px) {
        .cl-footer-main { padding: 2.5rem 1rem 1.5rem; gap: 1.75rem; }
        .cl-footer-logo img { height: 44px; }
        .cl-footer-legal { flex-direction: column; gap: 0.4rem; }
    }
</style>

<!-- ========== FOOTER ========== -->
<footer class="cl-footer">
    <div class="cl-footer-main">

        <!-- Col 1: Brand -->
        <div class="cl-footer-brand" data-reveal="up">
            <?php
            $logoFooter = $ajustes['logo_navbar'] ?? 'logo.svg';
            if (strpos($logoFooter, 'uploads_privados/') === false && $logoFooter !== 'logo.svg') {
                $logoFooter = 'uploads_privados/' . $logoFooter;
            }
            ?>
            <div class="cl-footer-logo">
                <img src="<?= e(asset($logoFooter)) ?>" alt="<?= APP_NAME ?? 'Cerelit' ?>">
            </div>
            <p class="cl-footer-desc">
                <?= !empty($ajustes['footer_texto']) ? e($ajustes['footer_texto']) : 'Transformamos espacios con diseño y tecnología de vanguardia.' ?>
            </p>

            <?php
            $hasSocials = !empty($ajustes['footer_facebook']) || !empty($ajustes['footer_instagram'])
                       || !empty($ajustes['footer_youtube'])  || !empty($ajustes['footer_tiktok']);
            if ($hasSocials): ?>
                <div class="cl-footer-social">
                    <?php if (!empty($ajustes['footer_facebook'])): ?>
                        <a href="<?= e($ajustes['footer_facebook']) ?>" target="_blank" rel="noopener" class="cl-social-link" aria-label="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($ajustes['footer_instagram'])): ?>
                        <a href="<?= e($ajustes['footer_instagram']) ?>" target="_blank" rel="noopener" class="cl-social-link" aria-label="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($ajustes['footer_youtube'])): ?>
                        <a href="<?= e($ajustes['footer_youtube']) ?>" target="_blank" rel="noopener" class="cl-social-link" aria-label="YouTube">
                            <i class="bi bi-youtube"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($ajustes['footer_tiktok'])): ?>
                        <a href="<?= e($ajustes['footer_tiktok']) ?>" target="_blank" rel="noopener" class="cl-social-link" aria-label="TikTok">
                            <i class="bi bi-tiktok"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Col 2: Contact Info -->
        <div class="cl-footer-col" data-reveal="up" data-delay="2">
            <h4>Contacto</h4>
            <div class="cl-footer-contact">
                <?php if (!empty($ajustes['footer_email'])): ?>
                    <div class="cl-footer-contact-item">
                        <i class="bi bi-envelope"></i>
                        <a href="mailto:<?= e($ajustes['footer_email']) ?>"><?= e($ajustes['footer_email']) ?></a>
                    </div>
                <?php endif; ?>
                <?php if (!empty($ajustes['footer_telefono'])): ?>
                    <div class="cl-footer-contact-item">
                        <i class="bi bi-telephone"></i>
                        <span><?= e($ajustes['footer_telefono']) ?></span>
                    </div>
                <?php endif; ?>
                <?php if (!empty($ajustes['footer_direccion'])): ?>
                    <div class="cl-footer-contact-item">
                        <i class="bi bi-geo-alt"></i>
                        <span><?= e($ajustes['footer_direccion']) ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Col 3: Quick links -->
        <div class="cl-footer-col" data-reveal="up" data-delay="3">
            <h4>Explorar</h4>
            <div class="cl-footer-links">
                <a href="<?= BASE_URL ?>productos">
                    <i class="bi bi-circle-fill"></i> Catálogo
                </a>
                <a href="<?= BASE_URL ?>#colecciones">
                    <i class="bi bi-circle-fill"></i> Colecciones
                </a>
                <a href="<?= BASE_URL ?>#about">
                    <i class="bi bi-circle-fill"></i> Acerca de
                </a>
                <a href="<?= BASE_URL ?>login">
                    <i class="bi bi-circle-fill"></i> Acceso Admin
                </a>
            </div>
        </div>

    </div>

    <!-- Bottom Bar -->
    <div class="cl-footer-bottom">
        <div class="cl-footer-bottom-inner">
            <p class="cl-footer-copyright">
                &copy; <?= date('Y') ?> <?= APP_NAME ?? 'Cerelit' ?> &mdash;
                Desarrollado por <a href="https://www.apotemaone.com" target="_blank" rel="noopener">Apotema One</a>
            </p>
            <div class="cl-footer-legal">
                <a href="#">Términos y Condiciones</a>
                <a href="#">Privacidad</a>
                <a href="#">Cookies</a>
            </div>
        </div>
    </div>
</footer>

<?php include_once VIEWS_PATH . 'layouts/cookies.php'; ?>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    (function () {
        document.documentElement.setAttribute('data-theme', 'light');
    })();

    document.addEventListener('DOMContentLoaded', function () {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            background: 'rgba(255, 255, 255, 0.97)',
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        <?php
        $flashSuccess = getFlash('success');
        $flashError   = getFlash('error');
        $flashWarning = getFlash('warning');
        $flashInfo    = getFlash('info');
        ?>
        <?php if ($flashSuccess): ?>Toast.fire({ icon: 'success', title: '<?= e($flashSuccess) ?>' });<?php endif; ?>
        <?php if ($flashError):   ?>Toast.fire({ icon: 'error',   title: '<?= e($flashError) ?>' });<?php endif; ?>
        <?php if ($flashWarning): ?>Toast.fire({ icon: 'warning', title: '<?= e($flashWarning) ?>' });<?php endif; ?>
        <?php if ($flashInfo):    ?>Toast.fire({ icon: 'info',    title: '<?= e($flashInfo) ?>' });<?php endif; ?>
    });

    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href === '#' || !href.startsWith('#')) return;
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) target.scrollIntoView({ behavior: 'smooth' });
        });
    });
</script>

<?php if (isset($scripts)): ?>
    <?php foreach ($scripts as $script): ?>
        <script src="<?= BASE_URL ?>js/<?= $script ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>
