<!DOCTYPE html>
<html lang="es" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | <?= APP_NAME ?></title>

    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/themes.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/app.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Toggle Tema -->
    <button class="theme-toggle" aria-label="Cambiar tema">
        <i class="bi bi-sun-fill theme-toggle-icon sun"></i>
        <i class="bi bi-moon-fill theme-toggle-icon moon"></i>
    </button>

    <div class="login-container">
        <div class="login-card">
            <div style="text-align: center; margin-bottom: 2rem;">
                <div
                    style="width: 80px; height: 80px; background: rgba(13, 110, 253, 0.1); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; border: 1px solid rgba(13, 110, 253, 0.2);">
                    <i class="bi bi-shield-lock" style="font-size: 2.5rem; color: var(--primary);"></i>
                </div>
                <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--text-primary); margin-bottom: 0.5rem;">
                    Bienvenido</h1>
                <p style="color: var(--text-muted);">Inicia sesión en Dublín EkProv</p>
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

                <div style="margin-bottom: 1.25rem;">
                    <label class="form-label" for="username"
                        style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--text-primary);">
                        <i class="bi bi-person"></i> Usuario o Email
                    </label>
                    <input type="text" id="username" name="username" class="form-control"
                        placeholder="Ingresa tu usuario" required autocomplete="username">
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label class="form-label" for="password"
                        style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--text-primary);">
                        <i class="bi bi-lock"></i> Contraseña
                    </label>
                    <input type="password" id="password" name="password" class="form-control"
                        placeholder="Ingresa tu contraseña" required autocomplete="current-password">
                </div>

                <button type="submit" class="btn btn-primary w-100" style="padding: 0.875rem; font-size: 1rem;">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Iniciar Sesión
                </button>
            </form>

            <div class="text-center mt-4">
                <a href="<?= BASE_URL ?>solicitar-recuperacion"
                    style="color: var(--primary); text-decoration: none; font-size: 0.9375rem;">
                </a>
            </div>

            <!-- Footer -->
            <div class="text-center mt-4" style="padding-top: 1.5rem; border-top: 1px solid var(--border-color);">
                <small style="color: var(--text-muted);">
                    Desarrollado por
                    <a href="https://www.apotemaone.com" target="_blank"
                        style="color: var(--primary); text-decoration: none; font-weight: 600;">
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