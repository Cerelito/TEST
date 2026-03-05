<!DOCTYPE html>
<html lang="es" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña - <?= APP_NAME ?></title>

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
                    <i class="bi bi-key auth-icon-lg"></i>
                </div>
                <h1 class="auth-title-sm">
                    Restablecer Contraseña
                </h1>
                <p class="auth-subtitle-sm">
                    Ingrese su nueva contraseña de acceso
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

            <div class="alert alert-info mb-4 alert-md-text">
                <div class="d-flex align-center gap-2">
                    <i class="bi bi-person-circle"></i>
                    <div>
                        <span class="d-block text-muted text-xs">Usuario:</span>
                        <strong><?= e($usuario['nombre']) ?></strong>
                        <br>
                        <small><?= e($usuario['email']) ?></small>
                    </div>
                </div>
            </div>

            <form method="POST" action="<?= BASE_URL ?>recuperar-password?token=<?= e($_GET['token']) ?>"
                id="formRestablecer">
                <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">

                <div class="form-group mb-3">
                    <label for="password" class="form-label">Nueva Contraseña</label>
                    <div class="input-password-wrapper">
                        <input type="password" id="password" name="password" class="form-control" required minlength="8"
                            autofocus placeholder="Mínimo 8 caracteres">
                        <button type="button" class="btn btn-sm btn-glass toggle-password btn-toggle-password"
                            onclick="togglePassword('password')">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="password_confirmar" class="form-label">Confirmar Contraseña</label>
                    <div class="input-password-wrapper">
                        <input type="password" id="password_confirmar" name="password_confirmar" class="form-control"
                            required minlength="8" placeholder="Repita la contraseña">
                        <button type="button" class="btn btn-sm btn-glass toggle-password btn-toggle-password"
                            onclick="togglePassword('password_confirmar')">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-4 p-3 rounded security-note-box">
                    <strong class="password-requirements-label">Requisitos:</strong>
                    <ul class="password-requirements-list">
                        <li id="req-longitud" class="mb-1"><i class="bi bi-circle"></i> Mínimo 8 caracteres</li>
                        <li id="req-mayuscula" class="mb-1"><i class="bi bi-circle"></i> Al menos una mayúscula</li>
                        <li id="req-numero" class="mb-0"><i class="bi bi-circle"></i> Al menos un número</li>
                    </ul>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3 btn-submit-md" id="btnRestablecer">
                    <i class="bi bi-check-lg"></i> Restablecer Contraseña
                </button>

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
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const button = input.nextElementSibling;
            const icon = button.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }

        // Validación de fortaleza de contraseña en tiempo real
        document.getElementById('password').addEventListener('input', function (e) {
            const password = e.target.value;
            const successColor = 'var(--success)';
            const mutedColor = 'var(--text-muted)';

            const updateReq = (id, valid) => {
                const el = document.getElementById(id);
                const icon = el.querySelector('i');
                if (valid) {
                    el.style.color = successColor;
                    icon.classList.replace('bi-circle', 'bi-check-circle-fill');
                } else {
                    el.style.color = mutedColor;
                    icon.classList.replace('bi-check-circle-fill', 'bi-circle');
                }
            };

            updateReq('req-longitud', password.length >= 8);
            updateReq('req-mayuscula', /[A-Z]/.test(password));
            updateReq('req-numero', /[0-9]/.test(password));
        });

        // Validar formulario
        document.getElementById('formRestablecer').addEventListener('submit', function (e) {
            const password = document.getElementById('password').value;
            const confirmar = document.getElementById('password_confirmar').value;

            if (password !== confirmar) {
                e.preventDefault();
                // Usamos la función global si existe, o alert nativo
                if (typeof alertError === 'function') {
                    alertError('Contraseñas No Coinciden', 'Las contraseñas ingresadas no son iguales');
                } else {
                    alert('Las contraseñas no coinciden');
                }
                return false;
            }

            if (password.length < 8) {
                e.preventDefault();
                if (typeof alertError === 'function') {
                    alertError('Contraseña Inválida', 'La contraseña debe tener al menos 8 caracteres');
                } else {
                    alert('La contraseña debe tener al menos 8 caracteres');
                }
                return false;
            }

            const btnRestablecer = document.getElementById('btnRestablecer');
            btnRestablecer.disabled = true;
            btnRestablecer.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...';
        });

        // Auto-close alerts
        setTimeout(() => {
            document.querySelectorAll('.alert-success, .alert-danger').forEach(alert => {
                if (!alert.querySelector('strong')) { // No cerrar si tiene contenido importante
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }
            });
        }, 5000);
    </script>
</body>

</html>