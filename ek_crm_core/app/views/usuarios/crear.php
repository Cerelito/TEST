<?php
$pagina_actual = 'usuarios';
$titulo = 'Nuevo Usuario';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<div class="d-flex justify-between align-center mb-4">
    <div>
        <h1 class="section-title">
            <i class="bi bi-person-plus"></i> Nuevo Usuario
        </h1>
        <p class="section-subtitle">
            Complete el formulario para crear un nuevo usuario
        </p>
    </div>
    <a href="<?= BASE_URL ?>usuarios" class="btn btn-glass">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<form method="POST" action="<?= BASE_URL ?>usuarios/guardar" id="formUsuario">
    <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">

    <div class="glass-panel mb-4">
        <h2 class="card-title">
            <i class="bi bi-person-badge"></i> Información Personal
        </h2>

        <div class="grid-2">
            <div class="form-group">
                <label for="nombre" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                <input type="text" id="nombre" name="nombre" class="form-control" required maxlength="100"
                    value="<?= e($usuario['nombre'] ?? '') ?>" autofocus placeholder="Ej: Juan Pérez">
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" id="email" name="email" class="form-control" required maxlength="100"
                    value="<?= e($usuario['email'] ?? '') ?>" placeholder="usuario@empresa.com">
            </div>
        </div>
    </div>

    <div class="glass-panel mb-4">
        <h2 class="card-title">
            <i class="bi bi-key"></i> Credenciales de Acceso
        </h2>

        <div class="grid-2">
            <div class="form-group">
                <label for="username" class="form-label">Usuario <span class="text-danger">*</span></label>
                <input type="text" id="username" name="username" class="form-control" required
                    pattern="[a-zA-Z0-9_]{4,20}" maxlength="20" value="<?= e($usuario['username'] ?? '') ?>"
                    placeholder="Ej: jperez">
                <small class="form-text">4-20 caracteres (letras, números y guion bajo)</small>
            </div>

            <div class="form-group">
                <label for="perfil_id" class="form-label">Perfil <span class="text-danger">*</span></label>
                <select id="perfil_id" name="perfil_id" class="form-control" required>
                    <option value="">Seleccione un perfil...</option>
                    <?php foreach ($perfiles as $perfil): ?>
                        <option value="<?= $perfil['id'] ?>" <?= ($usuario['perfil_id'] ?? '') == $perfil['id'] ? 'selected' : '' ?>>
                            <?= e($perfil['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small class="form-text">Define los permisos del usuario</small>
            </div>
        </div>

        <div class="grid-2 mt-3">
            <div class="form-group">
                <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                <div class="input-password-wrapper">
                    <input type="password" id="password" name="password" class="form-control" required minlength="8"
                        placeholder="Mínimo 8 caracteres">
                    <button type="button" class="btn btn-sm btn-glass btn-toggle-password" onclick="togglePassword('password')">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label for="confirm_password" class="form-label">Confirmar Contraseña <span
                        class="text-danger">*</span></label>
                <div class="input-password-wrapper">
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required
                        minlength="8" placeholder="Repita la contraseña">
                    <button type="button" class="btn btn-sm btn-glass btn-toggle-password" onclick="togglePassword('confirm_password')">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="form-check mt-2">
            <input type="checkbox" id="cambiar_password" name="cambiar_password" value="1" class="form-check-input"
                checked>
            <label for="cambiar_password" class="form-check-label">
                Solicitar cambio de contraseña en el próximo inicio de sesión
            </label>
        </div>
    </div>

    <div class="glass-panel mb-4">
        <h2 class="card-title">
            <i class="bi bi-gear"></i> Configuración
        </h2>

        <div class="form-group mb-0">
            <div class="form-check">
                <input type="checkbox" id="activo" name="activo" value="1" class="form-check-input" checked>
                <label for="activo" class="form-check-label">
                    <strong>Usuario activo</strong>
                    <br>
                    <small class="text-muted">El usuario podrá iniciar sesión inmediatamente</small>
                </label>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 justify-end mb-5">
        <a href="<?= BASE_URL ?>usuarios" class="btn btn-glass">
            <i class="bi bi-x-lg"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-primary" id="btnGuardar">
            <i class="bi bi-check-lg"></i> Crear Usuario
        </button>
    </div>
</form>

<script>
    // Validar formulario
    document.getElementById('formUsuario').addEventListener('submit', function (e) {
        const password = document.getElementById('password').value;
        const confirm = document.getElementById('confirm_password').value;

        if (password !== confirm) {
            e.preventDefault();
            alertError('Contraseñas no coinciden', 'Por favor verifique que las contraseñas sean iguales.');
            return false;
        }

        const btnGuardar = document.getElementById('btnGuardar');
        btnGuardar.disabled = true;
        btnGuardar.innerHTML = '<i class="bi bi-hourglass-split"></i> Creando usuario...';
    });

    // Mostrar/Ocultar contraseña
    function togglePassword(fieldId) {
        const input = document.getElementById(fieldId);
        const icon = input.nextElementSibling.querySelector('i');

        if (input.type === "password") {
            input.type = "text";
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = "password";
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }

    // Convertir username a minúsculas y sin espacios
    document.getElementById('username').addEventListener('input', function () {
        this.value = this.value.toLowerCase().replace(/\s/g, '');
    });

    // Sugerir username basado en el nombre
    document.getElementById('nombre').addEventListener('blur', function () {
        const usernameInput = document.getElementById('username');
        if (!usernameInput.value) {
            const nombre = this.value.trim();
            if (nombre) {
                // Tomar primer nombre y primer apellido
                const partes = nombre.split(' ');
                let sugerencia = '';
                if (partes.length >= 2) {
                    sugerencia = (partes[0].charAt(0) + partes[1]).toLowerCase();
                } else {
                    sugerencia = partes[0].toLowerCase();
                }
                // Limpiar caracteres especiales
                sugerencia = sugerencia.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                usernameInput.value = sugerencia;
            }
        }
    });
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>