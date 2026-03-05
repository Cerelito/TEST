<?php
$pagina_actual = 'cambiar-password';
$titulo = 'Cambiar Contraseña';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<div class="d-flex justify-between align-center mb-4">
    <div>
        <h1 class="section-title">
            <i class="bi bi-shield-lock"></i> Cambiar Contraseña
        </h1>
        <p class="section-subtitle">
            Actualice su contraseña de acceso al sistema
        </p>
    </div>
    <?php if (!usuarioActual()['debe_cambiar_password']): ?>
        <a href="<?= BASE_URL ?>dashboard" class="btn btn-glass">
            <i class="bi bi-arrow-left"></i> Volver al Dashboard
        </a>
    <?php endif; ?>
</div>

<div class="container-narrow">
    <div class="glass-panel">

        <?php if (usuarioActual()['debe_cambiar_password']): ?>
            <div class="alert alert-warning mb-4">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>
                    <strong>Acción requerida:</strong> Por seguridad, debe cambiar su contraseña temporal antes de continuar
                    navegando en el sistema.
                </div>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>cambiar-password" id="formCambiarPassword">
            <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">

            <div class="form-group mb-4">
                <label for="password_actual" class="form-label">Contraseña Actual <span
                        class="text-danger">*</span></label>
                <div class="input-password-wrapper">
                    <input type="password" id="password_actual" name="password_actual" class="form-control" required
                        autofocus placeholder="Ingrese su contraseña actual">
                    <button type="button" class="btn btn-sm btn-glass toggle-password btn-toggle-password"
                        onclick="togglePassword('password_actual')">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <div class="grid-2 mb-4">
                <div class="form-group">
                    <label for="password_nueva" class="form-label">Nueva Contraseña <span
                            class="text-danger">*</span></label>
                    <div class="input-password-wrapper">
                        <input type="password" id="password_nueva" name="password_nueva" class="form-control" required
                            minlength="8" placeholder="Mínimo 8 caracteres">
                        <button type="button" class="btn btn-sm btn-glass toggle-password btn-toggle-password"
                            onclick="togglePassword('password_nueva')">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmar" class="form-label">Confirmar Nueva Contraseña <span
                            class="text-danger">*</span></label>
                    <div class="input-password-wrapper">
                        <input type="password" id="password_confirmar" name="password_confirmar" class="form-control"
                            required minlength="8" placeholder="Repita la nueva contraseña">
                        <button type="button" class="btn btn-sm btn-glass toggle-password btn-toggle-password"
                            onclick="togglePassword('password_confirmar')">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="alert alert-info mb-4 alert-md-text">
                <strong>Requisitos de seguridad:</strong>
                <ul class="mb-0 mt-2 requirements-list">
                    <li>Mínimo 8 caracteres de longitud</li>
                    <li>Se recomienda usar mayúsculas, minúsculas y números</li>
                    <li>No use información personal obvia (ej. su nombre o fecha de nacimiento)</li>
                </ul>
            </div>

            <div class="d-flex justify-end">
                <button type="submit" class="btn btn-primary btn-min-w-200" id="btnCambiar">
                    <i class="bi bi-check-lg"></i> Cambiar Contraseña
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        // Buscar el icono dentro del botón pulsado
        // Nota: onclick pasa el ID del input, necesitamos encontrar el botón que llamó a la función
        // Una forma más robusta es buscar el botón hermano siguiente al input
        const button = input.nextElementSibling;
        const icon = button.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }

    // Validar que las contraseñas coincidan
    document.getElementById('formCambiarPassword').addEventListener('submit', function (e) {
        const nueva = document.getElementById('password_nueva').value;
        const confirmar = document.getElementById('password_confirmar').value;

        if (nueva !== confirmar) {
            e.preventDefault();
            alertError('Contraseñas No Coinciden', 'Las contraseñas ingresadas no son iguales');
            return false;
        }

        if (nueva.length < 8) {
            e.preventDefault();
            alertError('Contraseña Inválida', 'La contraseña debe tener al menos 8 caracteres');
            return false;
        }

        const btnCambiar = document.getElementById('btnCambiar');
        btnCambiar.disabled = true;
        btnCambiar.innerHTML = '<i class="bi bi-hourglass-split"></i> Procesando...';
    });
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>