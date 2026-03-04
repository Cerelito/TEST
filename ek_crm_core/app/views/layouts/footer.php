</main>

<!-- Footer Glass -->
<footer class="glass-footer">
    <div class="footer-content">
        <p class="footer-text">© <?= date('Y') ?> <?= APP_NAME ?></p>
        <div class="footer-divider"></div>
        <p class="footer-credits">
            Desarrollado con <i class="bi bi-heart-fill footer-heart"></i> por
            <a href="https://www.apotemaone.com" target="_blank" class="footer-link">Apotema One</a>
        </p>
    </div>
</footer>

        </div><!-- /.main-wrapper -->
    </div><!-- /.app-layout -->

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
