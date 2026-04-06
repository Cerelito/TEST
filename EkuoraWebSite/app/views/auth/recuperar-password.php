<!DOCTYPE html>
<html lang="es" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña - <?= APP_NAME ?></title>

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
                    <i class="bi bi-key" style="font-size: 3rem; color: var(--primary);"></i>
                </div>
                <h1 style="font-size: 1.75rem; font-weight: 700; margin: 1rem 0 0.5rem; color: var(--text-primary);">
                    Restablecer Contraseña
                </h1>
                <p style="color: var(--text-muted); margin: 0;">
                    Ingrese su nueva contraseña
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

            <!-- Usuario -->
            <div class="alert alert-success" style="margin-bottom: 1.5rem;">
                <i class="bi bi-person-check"></i>
                <strong>Restableciendo contraseña para:</strong>
                <div style="margin-top: 0.25rem;">
                    <?= e($usuario['nombre']) ?> (<?= e($usuario['email']) ?>)
                </div>
            </div>

            <!-- Formulario -->
            <form method="POST" action="<?= BASE_URL ?>recuperar-password?token=<?= e($_GET['token']) ?>"
                id="formRestablecer">
                <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">

                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="bi bi-shield-lock"></i> Nueva Contraseña
                    </label>
                    <div style="position: relative;">
                        <input type="password" id="password" name="password" class="form-control" required minlength="8"
                            autofocus>
                        <button type="button" class="toggle-password" onclick="togglePassword('password')">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <small class="form-text">Mínimo 8 caracteres</small>
                </div>

                <div class="form-group">
                    <label for="password_confirmar" class="form-label">
                        <i class="bi bi-check2-circle"></i> Confirmar Contraseña
                    </label>
                    <div style="position: relative;">
                        <input type="password" id="password_confirmar" name="password_confirmar" class="form-control"
                            required minlength="8">
                        <button type="button" class="toggle-password" onclick="togglePassword('password_confirmar')">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Requisitos de contraseña -->
                <div class="alert alert-info" style="margin-bottom: 1.5rem;">
                    <strong>Requisitos de la contraseña:</strong>
                    <ul style="margin: 0.5rem 0 0 1.25rem; font-size: 0.875rem;">
                        <li>Mínimo 8 caracteres</li>
                        <li id="req-mayuscula" style="color: var(--text-muted);">
                            <i class="bi bi-circle"></i> Al menos una mayúscula
                        </li>
                        <li id="req-minuscula" style="color: var(--text-muted);">
                            <i class="bi bi-circle"></i> Al menos una minúscula
                        </li>
                        <li id="req-numero" style="color: var(--text-muted);">
                            <i class="bi bi-circle"></i> Al menos un número
                        </li>
                    </ul>
                </div>

                <button type="submit" class="btn btn-primary" id="btnRestablecer" style="width: 100%;">
                    <i class="bi bi-check-lg"></i> Restablecer Contraseña
                </button>

                <a href="<?= BASE_URL ?>login" class="btn btn-glass" style="width: 100%; margin-top: 1rem;">
                    <i class="bi bi-arrow-left"></i> Volver al Login
                </a>
            </form>

            <!-- Información de seguridad -->
            <div
                style="margin-top: 2rem; padding: 1rem; background: var(--bg-secondary); border-radius: 8px; border-left: 3px solid var(--info);">
                <p style="margin: 0; font-size: 0.8125rem; color: var(--text-muted);">
                    <i class="bi bi-info-circle"></i>
                    Una vez restablecida su contraseña, podrá iniciar sesión normalmente.
                    Por seguridad, se recomienda cerrar sesión en todos los dispositivos.
                </p>
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
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const button = input.nextElementSibling;
            const icon = button.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }

        // Validación de fortaleza de contraseña en tiempo real
        document.getElementById('password').addEventListener('input', function (e) {
            const password = e.target.value;

            // Mayúscula
            const reqMayuscula = document.getElementById('req-mayuscula');
            if (/[A-Z]/.test(password)) {
                reqMayuscula.innerHTML = '<i class="bi bi-check-circle-fill" style="color: var(--success);"></i> Al menos una mayúscula';
                reqMayuscula.style.color = 'var(--success)';
            } else {
                reqMayuscula.innerHTML = '<i class="bi bi-circle"></i> Al menos una mayúscula';
                reqMayuscula.style.color = 'var(--text-muted)';
            }

            // Minúscula
            const reqMinuscula = document.getElementById('req-minuscula');
            if (/[a-z]/.test(password)) {
                reqMinuscula.innerHTML = '<i class="bi bi-check-circle-fill" style="color: var(--success);"></i> Al menos una minúscula';
                reqMinuscula.style.color = 'var(--success)';
            } else {
                reqMinuscula.innerHTML = '<i class="bi bi-circle"></i> Al menos una minúscula';
                reqMinuscula.style.color = 'var(--text-muted)';
            }

            // Número
            const reqNumero = document.getElementById('req-numero');
            if (/[0-9]/.test(password)) {
                reqNumero.innerHTML = '<i class="bi bi-check-circle-fill" style="color: var(--success);"></i> Al menos un número';
                reqNumero.style.color = 'var(--success)';
            } else {
                reqNumero.innerHTML = '<i class="bi bi-circle"></i> Al menos un número';
                reqNumero.style.color = 'var(--text-muted)';
            }
        });

        // Validar formulario
        document.getElementById('formRestablecer').addEventListener('submit', function (e) {
            const password = document.getElementById('password').value;
            const confirmar = document.getElementById('password_confirmar').value;

            if (password !== confirmar) {
                e.preventDefault();
                alert('Las contraseñas no coinciden');
                return false;
            }

            if (password.length < 8) {
                e.preventDefault();
                alert('La contraseña debe tener al menos 8 caracteres');
                return false;
            }

            const btnRestablecer = document.getElementById('btnRestablecer');
            btnRestablecer.disabled = true;
            btnRestablecer.innerHTML = '<i class="bi bi-hourglass-split"></i> Restableciendo...';
        });

        // Auto-close alerts
        setTimeout(() => {
            document.querySelectorAll('.alert-success, .alert-danger').forEach(alert => {
                if (!alert.querySelector('strong')) {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }
            });
        }, 5000);
    </script>
</body>

</html>