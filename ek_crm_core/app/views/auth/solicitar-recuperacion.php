<!DOCTYPE html>
<html lang="es" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - <?= APP_NAME ?></title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/themes.css?v=2.2.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/app.css?v=2.2.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/responsive.css?v=2.2.0">
</head>

<body>
    <script>
        (function () {
            const saved = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', saved);
            if (saved === 'dark') document.body.classList.add('dark-mode');
        })();
    </script>
    <button class="theme-toggle theme-toggle-fixed" aria-label="Cambiar tema">
        <i class="bi bi-sun-fill theme-toggle-icon sun"></i>
        <i class="bi bi-moon-fill theme-toggle-icon moon"></i>
    </button>

    <div class="login-container">
        <div class="login-card">
            <div class="auth-header">
                <div class="auth-icon-box-sm">
                    <i class="bi bi-envelope-exclamation auth-icon-lg"></i>
                </div>
                <h1 class="auth-title-sm">
                    Recuperar Contraseña
                </h1>
                <p class="auth-subtitle-sm">
                    Ingrese su email para recibir instrucciones
                </p>
            </div>

            <?php if (hasFlash('error')): ?>
                <div class="alert alert-danger mb-4">
                    <i class="bi bi-exclamation-circle"></i>
                    <span><?= getFlash('error') ?></span>
                </div>
            <?php endif; ?>

            <?php if (hasFlash('success')): ?>
                <div class="alert alert-success mb-4">
                    <i class="bi bi-check-circle"></i>
                    <span><?= getFlash('success') ?></span>
                </div>
            <?php endif; ?>

            <div class="alert alert-info mb-4 alert-sm-text">
                <i class="bi bi-info-circle alert-icon-md"></i>
                <div>
                    Ingrese el correo electrónico asociado a su cuenta. Si existe, recibirá un enlace para restablecer
                    su contraseña.
                </div>
            </div>

            <form method="POST" action="<?= BASE_URL ?>solicitar-recuperacion" id="formRecuperacion">
                <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">

                <div class="form-group mb-4">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <div class="input-icon-wrapper">
                        <input type="email" id="email" name="email" class="form-control" required autofocus
                            placeholder="usuario@ejemplo.com" class="input-padded-left">
                        <i class="bi bi-envelope input-icon-left"></i>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-4 btn-submit-md" id="btnEnviar">
                    <i class="bi bi-send"></i> Enviar Instrucciones
                </button>

                <div class="p-3 rounded mb-3 security-note-box">
                    <h3 class="security-note-title">
                        <i class="bi bi-shield-check"></i> Nota de Seguridad
                    </h3>
                    <ul class="security-note-list">
                        <li>El enlace de recuperación expirará en 1 hora</li>
                        <li>Solo puede usarse una vez</li>
                        <li>Si no solicitó esto, ignore este mensaje</li>
                    </ul>
                </div>

                <div class="text-center">
                    <a href="<?= BASE_URL ?>login" class="auth-back-link">
                        <i class="bi bi-arrow-left"></i> Volver al Login
                    </a>
                </div>
            </form>
        </div>

        <div class="text-center mt-4 auth-footer-text">
            Desarrollado por <a href="https://www.apotemaone.com" target="_blank" class="auth-footer-link">Apotema One</a>
        </div>
    </div>

    <script src="<?= BASE_URL ?>public/js/theme-toggle.js"></script>
    <script src="<?= BASE_URL ?>public/js/app.js"></script>
    <script>
        document.getElementById('formRecuperacion').addEventListener('submit', function (e) {
            const btnEnviar = document.getElementById('btnEnviar');
            btnEnviar.disabled = true;
            btnEnviar.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enviando...';
        });

        // Auto-close alerts
        setTimeout(() => {
            document.querySelectorAll('.alert-success, .alert-danger').forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>

</html>