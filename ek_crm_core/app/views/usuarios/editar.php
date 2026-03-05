<?php
$pagina_actual = 'usuarios';
$titulo = 'Editar Usuario';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<div class="d-flex justify-between align-center mb-4">
    <div>
        <h1 class="section-title">
            <i class="bi bi-pencil-square"></i> Editar Usuario
        </h1>
        <p class="section-subtitle">
            <?= e($usuario['nombre']) ?> (@<?= e($usuario['username']) ?>)
        </p>
    </div>
    <a href="<?= BASE_URL ?>usuarios" class="btn btn-glass">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<form method="POST" action="<?= BASE_URL ?>usuarios/actualizar/<?= $usuario['id'] ?>" id="formUsuario">
    <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">

    <div class="glass-panel mb-4">
        <h2 class="card-title">
            <i class="bi bi-person-badge"></i> Información Personal
        </h2>

        <div class="grid-2">
            <div class="form-group">
                <label for="nombre" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                <input type="text" id="nombre" name="nombre" class="form-control" required maxlength="100"
                    value="<?= e($usuario['nombre']) ?>" autofocus>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" id="email" name="email" class="form-control" required maxlength="100"
                    value="<?= e($usuario['email']) ?>">
            </div>
        </div>
    </div>

    <div class="glass-panel mb-4">
        <h2 class="card-title">
            <i class="bi bi-key"></i> Credenciales de Acceso
        </h2>

        <div class="grid-2">
            <div class="form-group">
                <label for="username" class="form-label">Usuario</label>
                <input type="text" id="username" class="form-control" value="<?= e($usuario['username']) ?>" disabled
                    class="input-disabled-look">
                <small class="form-text text-muted">El nombre de usuario no se puede modificar</small>
            </div>

            <div class="form-group">
                <label for="perfil_id" class="form-label">Perfil <span class="text-danger">*</span></label>
                <select id="perfil_id" name="perfil_id" class="form-control" required>
                    <option value="">Seleccione un perfil...</option>
                    <?php foreach ($perfiles as $perfil): ?>
                        <option value="<?= $perfil['id'] ?>" <?= $usuario['perfil_id'] == $perfil['id'] ? 'selected' : '' ?>>
                            <?= e($perfil['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <div class="glass-panel mb-4">
        <h2 class="card-title">
            <i class="bi bi-shield-lock"></i> Cambiar Contraseña (Opcional)
        </h2>

        <div class="alert alert-warning mb-3">
            <i class="bi bi-exclamation-triangle"></i>
            <div>
                <strong>Solo complete estos campos si desea cambiar la contraseña.</strong><br>
                Deje los campos vacíos para mantener la actual.
            </div>
        </div>

        <div class="grid-2">
            <div class="form-group position-relative">
                <label for="nueva_password" class="form-label">Nueva Contraseña</label>
                <input type="password" id="nueva_password" name="nueva_password" class="form-control" minlength="8"
                    maxlength="255">
                <small class="form-text">Mínimo 8 caracteres</small>
            </div>

            <div class="form-group position-relative">
                <label for="confirmar_password" class="form-label">Confirmar Contraseña</label>
                <input type="password" id="confirmar_password" name="confirmar_password" class="form-control"
                    minlength="8" maxlength="255">
            </div>
        </div>

        <div class="form-check mt-3">
            <input type="checkbox" id="enviar_email_password" name="enviar_email_password" value="1"
                class="form-check-input">
            <label for="enviar_email_password" class="form-check-label">
                Enviar nueva contraseña por email al usuario
            </label>
        </div>
    </div>

    <div class="glass-panel mb-4">
        <h2 class="card-title">
            <i class="bi bi-gear"></i> Configuración
        </h2>

        <div class="form-group">
            <div class="form-check">
                <input type="checkbox" id="activo" name="activo" value="1" class="form-check-input"
                    <?= $usuario['activo'] ? 'checked' : '' ?>>
                <label for="activo" class="form-check-label">
                    <strong>Usuario activo</strong>
                    <br>
                    <small class="text-muted">El usuario podrá iniciar sesión en el sistema</small>
                </label>
            </div>
        </div>

        <?php if (!empty($usuario['ultimo_acceso'])): ?>
            <div class="mt-3 p-3 rounded info-panel-highlight">
                <div class="grid-3 text-sm">
                    <div>
                        <strong>Último acceso:</strong><br>
                        <?= formatoFechaHora($usuario['ultimo_acceso']) ?>
                    </div>
                    <div>
                        <strong>Fecha de creación:</strong><br>
                        <?= formatoFechaHora($usuario['created_at']) ?>
                    </div>
                    <?php if (!empty($usuario['updated_at'])): ?>
                        <div>
                            <strong>Última actualización:</strong><br>
                            <?= formatoFechaHora($usuario['updated_at']) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="d-flex gap-2 justify-end mb-5">
        <a href="<?= BASE_URL ?>usuarios" class="btn btn-glass">
            <i class="bi bi-x-lg"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-primary" id="btnGuardar">
            <i class="bi bi-check-lg"></i> Guardar Cambios
        </button>
    </div>
</form>

<script>
    document.getElementById('formUsuario').addEventListener('submit', function (e) {
        const nuevaPassword = document.getElementById('nueva_password').value;
        const confirmarPassword = document.getElementById('confirmar_password').value;

        if (nuevaPassword || confirmarPassword) {
            if (nuevaPassword !== confirmarPassword) {
                e.preventDefault();
                alertError('Contraseñas No Coinciden', 'Las contraseñas ingresadas no son iguales');
                return false;
            }

            if (nuevaPassword.length < 8) {
                e.preventDefault();
                alertError('Contraseña Inválida', 'La contraseña debe tener al menos 8 caracteres');
                return false;
            }
        }

        const btnGuardar = document.getElementById('btnGuardar');
        btnGuardar.disabled = true;
        btnGuardar.innerHTML = '<i class="bi bi-hourglass-split"></i> Guardando...';
    });

    // Mostrar/ocultar contraseña
    document.querySelectorAll('input[type="password"]').forEach(input => {
        // Crear wrapper para posicionamiento relativo
        const wrapper = document.createElement('div');
        wrapper.style.position = 'relative';
        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);

        const toggleBtn = document.createElement('button');
        toggleBtn.type = 'button';
        toggleBtn.className = 'btn btn-sm btn-glass';
        toggleBtn.innerHTML = '<i class="bi bi-eye"></i>';
        toggleBtn.style.position = 'absolute';
        toggleBtn.style.right = '0.5rem';
        toggleBtn.style.top = '50%';
        toggleBtn.style.transform = 'translateY(-50%)';
        toggleBtn.style.border = 'none';
        toggleBtn.style.padding = '0.25rem 0.5rem';
        toggleBtn.style.zIndex = '10';

        toggleBtn.addEventListener('click', function () {
            if (input.type === 'password') {
                input.type = 'text';
                this.innerHTML = '<i class="bi bi-eye-slash"></i>';
            } else {
                input.type = 'password';
                this.innerHTML = '<i class="bi bi-eye"></i>';
            }
        });

        wrapper.appendChild(toggleBtn);
    });
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>