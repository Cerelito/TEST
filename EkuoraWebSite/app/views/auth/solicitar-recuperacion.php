<!DOCTYPE html>
<html lang="es" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - <?= APP_NAME ?></title>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>css/themes.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/app.css">
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Header -->
            <div class="auth-header">
                <div class="auth-logo">
                    <i class="bi bi-envelope-exclamation" style="font-size: 3rem; color: var(--primary);"></i>
                </div>
                <h1 style="font-size: 1.75rem; font-weight: 700; margin: 1rem 0 0.5rem; color: var(--text-primary);">
                    Recuperar Contraseña
                </h1>
                <p style="color: var(--text-muted); margin: 0;">
                    Ingrese su email para recibir instrucciones
                </p>
            </div>

            <!-- Flash Messages -->
            <?php if (hasFlash('error')): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-x-circle"></i>
                    <?= getFlash('error') ?>
                </div>
            <?php endif; ?>

            <?php if (hasFlash('success')): ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i>
                    <?= getFlash('success') ?>
                </div>
            <?php endif; ?>

            <!-- Instrucciones -->
            <div class="alert alert-info" style="margin-bottom: 1.5rem;">
                <i class="bi bi-info-circle"></i>
                <p style="margin: 0.5rem 0 0 0; font-size: 0.9375rem;">
                    Ingrese el correo electrónico asociado a su cuenta. Si existe, recibirá un enlace para restablecer
                    su contraseña.
                </p>
            </div>

            <!-- Formulario -->
            <form method="POST" action="<?= BASE_URL ?>solicitar-recuperacion" id="formRecuperacion">
                <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">

                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="bi bi-envelope"></i> Correo Electrónico
                    </label>
                    <input type="email" id="email" name="email" class="form-control" required autofocus
                        placeholder="usuario@ejemplo.com">
                </div>

                <button type="submit" class="btn btn-primary" id="btnEnviar" style="width: 100%;">
                    <i class="bi bi-send"></i> Enviar Instrucciones
                </button>

                <a href="<?= BASE_URL ?>login" class="btn btn-glass" style="width: 100%; margin-top: 1rem;">
                    <i class="bi bi-arrow-left"></i> Volver al Login
                </a>
            </form>

            <!-- Información de seguridad -->
            <div
                style="margin-top: 2rem; padding: 1rem; background: var(--bg-secondary); border-radius: 8px; border-left: 3px solid var(--warning);">
                <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin: 0 0 0.5rem 0;">
                    <i class="bi bi-shield-check"></i> Nota de Seguridad
                </h3>
                <ul
                    style="margin: 0; padding-left: 1.25rem; font-size: 0.8125rem; color: var(--text-muted); line-height: 1.6;">
                    <li>El enlace de recuperación expirará en 1 hora</li>
                    <li>Solo puede usarse una vez</li>
                    <li>Si no solicitó esto, puede ignorar este mensaje</li>
                    <li>Contacte al administrador si tiene problemas</li>
                </ul>
            </div>

            <!-- Theme Toggle -->
            <div class="theme-toggle-container">
                <button id="theme-toggle" class="theme-toggle" aria-label="Cambiar tema">
                    <i class="bi bi-moon-stars"></i>
                </button>
            </div>
        </div>

        <!-- Footer -->
        <div class="auth-footer">
            Desarrollado por <a href="https://www.apotemaone.com" target="_blank">Apotema One</a>
        </div>
    </div>

    <script src="<?= BASE_URL ?>js/theme-toggle.js"></script>
    <script>
        document.getElementById('formRecuperacion').addEventListener('submit', function (e) {
            const btnEnviar = document.getElementById('btnEnviar');
            btnEnviar.disabled = true;
            btnEnviar.innerHTML = '<i class="bi bi-hourglass-split"></i> Enviando...';
        });

        // Auto-close alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>

</html>