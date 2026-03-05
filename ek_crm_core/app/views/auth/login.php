<!DOCTYPE html>
<html lang="es" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | <?= APP_NAME ?></title>

    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/themes.css?v=2.2.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/app.css?v=2.2.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <script>
        (function () {
            const saved = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', saved);
            if (saved === 'dark') document.body.classList.add('dark-mode');
        })();
    </script>
    <!-- Toggle Tema -->
    <button class="theme-toggle" aria-label="Cambiar tema">
        <i class="bi bi-sun-fill theme-toggle-icon sun"></i>
        <i class="bi bi-moon-fill theme-toggle-icon moon"></i>
    </button>

    <div class="login-container">
        <div class="login-card">
            <div class="auth-header">
                <div class="auth-icon-box">
                    <i class="bi bi-shield-lock auth-icon-lg"></i>
                </div>
                <h1 class="auth-title">
                    Bienvenido</h1>
                <p class="auth-subtitle">Inicia sesión en Dublín EkProv</p>
            </div>

            <?php if ($flash = getFlash('success')): ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i>
                    <span><?= e($flash) ?></span>
                </div>
            <?php endif; ?>

            <?php if ($flash = getFlash('error')): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-circle"></i>
                    <span><?= e($flash) ?></span>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>auth/procesar" method="POST" data-loading>
                <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">

                <div class="form-group-spaced">
                    <label class="form-label form-label-block" for="username">
                        <i class="bi bi-person"></i> Usuario o Email
                    </label>
                    <input type="text" id="username" name="username" class="form-control"
                        placeholder="Ingresa tu usuario" required autocomplete="username">
                </div>

                <div class="form-group-spaced-lg">
                    <label class="form-label form-label-block" for="password">
                        <i class="bi bi-lock"></i> Contraseña
                    </label>
                    <input type="password" id="password" name="password" class="form-control"
                        placeholder="Ingresa tu contraseña" required autocomplete="current-password">
                </div>

                <button type="submit" class="btn btn-primary w-100 btn-submit-lg">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Iniciar Sesión
                </button>
            </form>

            <div class="text-center mt-4">
                <a href="<?= BASE_URL ?>solicitar-recuperacion" class="auth-forgot-link">
                </a>
            </div>

            <!-- Footer -->
            <div class="text-center mt-4 auth-footer">
                <small class="auth-footer-text">
                    Desarrollado por
                    <a href="https://www.apotemaone.com" target="_blank" class="auth-footer-link">
                        Apotema One
                    </a>
                </small>
            </div>
        </div>
    </div>

    <script src="<?= BASE_URL ?>public/js/theme-toggle.js"></script>
    <script src="<?= BASE_URL ?>public/js/app.js"></script>
</body>

</html>