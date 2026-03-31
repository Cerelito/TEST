</main>

<!-- Footer Glass -->
<footer class="glass-footer">
    <div class="footer-content">
        <p class="footer-text">
            ©
            <?= date('Y') ?>
            <?= APP_NAME ?>
        </p>
        <div class="footer-divider"></div>
        <p class="footer-credits">
            Desarrollado con <i class="bi bi-heart-fill footer-heart"></i> por
            <a href="https://www.apotemaone.com" target="_blank" class="footer-link">
                Apotema One
            </a>
        </p>
    </div>
</footer>

</div>
</div>

<style>
    /* ==========================================
           FOOTER GLASS
           ========================================== */
    .glass-footer {
        background: var(--glass-bg-card);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border-top: 2px solid var(--glass-border);
        padding: 1.5rem 2rem;
        margin-top: auto;
        box-shadow: 0 -8px 32px 0 rgba(59, 130, 246, 0.15);
    }

    .footer-content {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.875rem;
    }

    .footer-text {
        margin: 0;
        color: var(--glass-text-muted);
        font-weight: 500;
    }

    .footer-divider {
        width: 1px;
        height: 16px;
        background: var(--glass-border);
    }

    .footer-credits {
        margin: 0;
        color: var(--glass-text-muted);
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .footer-heart {
        color: #ef4444;
        font-size: 0.75rem;
        animation: heartbeat 1.5s ease-in-out infinite;
    }

    @keyframes heartbeat {

        0%,
        100% {
            transform: scale(1);
        }

        10%,
        30% {
            transform: scale(1.1);
        }

        20%,
        40% {
            transform: scale(1);
        }
    }

    .footer-link {
        color: var(--glass-primary);
        text-decoration: none;
        font-weight: 700;
        transition: all 0.3s ease;
        position: relative;
    }

    .footer-link::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: var(--glass-primary);
        transition: width 0.3s ease;
    }

    .footer-link:hover {
        color: var(--glass-primary-dark);
    }

    .footer-link:hover::after {
        width: 100%;
    }

    /* Responsive */
    @media (max-width: 640px) {
        .glass-footer {
            padding: 1.25rem 1rem;
        }

        .footer-content {
            flex-direction: column;
            gap: 0.5rem;
        }

        .footer-divider {
            display: none;
        }
    }
</style>

<script src="<?= BASE_URL ?>public/js/theme-toggle.js"></script>
<script src="<?= BASE_URL ?>public/js/app.js"></script>
<script src="<?= BASE_URL ?>public/js/sidebar.js"></script>

<?php if (isset($scripts) && is_array($scripts)): ?>
    <?php foreach ($scripts as $script): ?>
        <script src="<?= BASE_URL ?>public/js/<?= $script ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

</body>

</html>