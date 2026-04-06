</main>

<!-- Footer -->
<footer class="glass-footer"
    style="padding: 1.5rem; text-align: center; border-top: 1px solid var(--border-color); color: var(--text-muted); font-size: 0.875rem;">
    <p style="margin: 0;">
        © <?= date('Y') ?> <?= APP_NAME ?> - Desarrollado por
        <a href="https://www.apotemaone.com" target="_blank"
            style="color: var(--primary); text-decoration: none; font-weight: 600;">
            Apotema One
        </a>
    </p>
</footer>
</div>
</div>

<script src="<?= BASE_URL ?>js/theme-toggle.js"></script>
<script src="<?= BASE_URL ?>js/app.js"></script>
<script src="<?= BASE_URL ?>js/sidebar.js"></script>
<?php if (isset($scripts)): ?>
    <?php foreach ($scripts as $script): ?>
        <script src="<?= BASE_URL ?>js/<?= $script ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>
</body>

</html>