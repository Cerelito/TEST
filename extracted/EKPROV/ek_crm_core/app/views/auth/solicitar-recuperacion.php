<!DOCTYPE html>
<html lang="es" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - <?= APP_NAME ?></title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/themes.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/app.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/responsive.css">
</head>

<body>
    <button class="theme-toggle" aria-label="Cambiar tema"
        style="position: fixed; top: 1rem; right: 1rem; box-shadow: 0 4px 12px var(--glass-shadow) !important;">
        <i class="bi bi-sun-fill theme-toggle-icon sun"></i>
        <i class="bi bi-moon-fill theme-toggle-icon moon"></i>
    </button>

    <div class="login-container">
        <div class="login-card">
            <div style="text-align: center; margin-bottom: 2rem;">
                <div
                    style="width: 70px; height: 70px; background: rgba(13, 110, 253, 0.1); border-radius: 18px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; border: 1px solid rgba(13, 110, 253, 0.2);">
                    <i class="bi bi-envelope-exclamation" style="font-size: 2.5rem; color: var(--primary);"></i>
                </div>
                <h1 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--text-primary);">
                    Recuperar Contraseña
                </h1>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin: 0;">
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

            <div class="alert alert-info mb-4" style="font-size: 0.85rem; line-height: 1.5;">
                <i class="bi bi-info-circle" style="font-size: 1.2rem;"></i>
                <div>
                    Ingrese el correo electrónico asociado a su cuenta. Si existe, recibirá un enlace para restablecer
                    su contraseña.
                </div>
            </div>

            <form method="POST" action="<?= BASE_URL ?>solicitar-recuperacion" id="formRecuperacion">
                <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">

                <div class="form-group mb-4">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <div style="position: relative;">
                        <input type="email" id="email" name="email" class="form-control" required autofocus
                            placeholder="usuario@ejemplo.com" style="padding-left: 2.5rem;">
                        <i class="bi bi-envelope"
                            style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-4" id="btnEnviar" style="padding: 0.8rem;">
                    <i class="bi bi-send"></i> Enviar Instrucciones
                </button>

                <div class="p-3 rounded mb-3"
                    style="background: var(--bg-secondary); border: 1px solid var(--border-color);">
                    <h3
                        style="font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin: 0 0 0.5rem 0;">
                        <i class="bi bi-shield-check"></i> Nota de Seguridad
                    </h3>
                    <ul
                        style="margin: 0; padding-left: 1.2rem; font-size: 0.8rem; color: var(--text-muted); line-height: 1.6;">
                        <li>El enlace de recuperación expirará en 1 hora</li>
                        <li>Solo puede usarse una vez</li>
                        <li>Si no solicitó esto, ignore este mensaje</li>
                    </ul>
                </div>

                <div class="text-center">
                    <a href="<?= BASE_URL ?>login"
                        style="color: var(--text-muted); text-decoration: none; font-size: 0.9rem;">
                        <i class="bi bi-arrow-left"></i> Volver al Login
                    </a>
                </div>
            </form>
        </div>

        <div class="text-center mt-4" style="color: var(--text-muted); font-size: 0.85rem;">
            Desarrollado por <a href="https://www.apotemaone.com" target="_blank"
                style="color: var(--primary); text-decoration: none; font-weight: 600;">Apotema One</a>
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