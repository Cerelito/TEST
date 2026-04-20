<?php
$pagina_actual = 'perfiles';
$titulo = 'Editar Perfil';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<div class="d-flex justify-between align-center mb-4">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.25rem;">
            <i class="bi bi-pencil-square"></i> Editar Perfil
        </h1>
        <p style="color: var(--text-muted); margin: 0;">
            <?= e($perfil['nombre'] ?? $perfil['Nombre'] ?? '') ?>
        </p>
    </div>
    <a href="<?= BASE_URL ?>perfiles" class="btn btn-glass">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<form method="POST" action="<?= BASE_URL ?>perfiles/actualizar/<?= $perfil['id'] ?? $perfil['Id'] ?>" id="formPerfil">
    <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">

    <div class="glass-panel mb-4">
        <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem; color: var(--text-primary);">
            <i class="bi bi-info-circle"></i> Información del Perfil
        </h2>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
            <div class="form-group">
                <label for="nombre" class="form-label">Nombre del Perfil <span class="text-danger">*</span></label>
                <input type="text" id="nombre" name="nombre" class="form-control" required maxlength="50"
                    value="<?= e($perfil['nombre'] ?? $perfil['Nombre'] ?? '') ?>" autofocus>
            </div>

            <div class="form-group">
                <label for="descripcion" class="form-label">Descripción</label>
                <input type="text" id="descripcion" name="descripcion" class="form-control" maxlength="255"
                    value="<?= e($perfil['descripcion'] ?? $perfil['Descripcion'] ?? '') ?>">
            </div>
        </div>

        <?php if (!empty($perfil['total_usuarios'])): ?>
            <div class="alert alert-info mt-3">
                <i class="bi bi-people"></i>
                <strong><?= $perfil['total_usuarios'] ?></strong> usuario(s) tienen asignado este perfil
            </div>
        <?php endif; ?>
    </div>

    <div class="glass-panel mb-4">
        <div class="d-flex justify-between align-center mb-3">
            <h2 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin: 0;">
                <i class="bi bi-key"></i> Permisos <span class="text-danger">*</span>
            </h2>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-glass" onclick="seleccionarTodos()">
                    <i class="bi bi-check-all"></i> Todos
                </button>
                <button type="button" class="btn btn-sm btn-glass" onclick="deseleccionarTodos()">
                    <i class="bi bi-x-lg"></i> Ninguno
                </button>
            </div>
        </div>

        <div class="alert alert-warning mb-4">
            <i class="bi bi-exclamation-triangle"></i>
            <strong>Importante:</strong> Los cambios en los permisos afectarán a todos los usuarios con este perfil.
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
            <?php foreach ($permisos_agrupados as $identificador => $grupo): ?>
                <div class="glass-panel" style="background: var(--bg-secondary); padding: 1.25rem;">
                    <div class="d-flex justify-between align-center mb-3">
                        <h3
                            style="font-size: 1rem; font-weight: 600; color: var(--primary); margin: 0; text-transform: capitalize;">
                            <i class="bi <?= $grupo['icono'] ?? 'bi-folder' ?>"></i>
                            <?= $grupo['nombre'] ?? $identificador ?>
                        </h3>
                        <button type="button" class="btn btn-sm btn-glass" onclick="toggleModulo('<?= $identificador ?>')">
                            <i class="bi bi-check-square"></i>
                        </button>
                    </div>

                    <div class="permisos-modulo" data-modulo="<?= $identificador ?>">
                        <?php foreach ($grupo['permisos'] as $perm): ?>
                            <div class="form-check" style="margin-bottom: 0.75rem;">
                                <?php $p_id = $perm['id'] ?? $perm['Id']; ?>
                                <input type="checkbox" id="perm_<?= $p_id ?>" name="permisos[]" value="<?= $p_id ?>"
                                    class="form-check-input" data-modulo="<?= $identificador ?>" <?= in_array($p_id, $permisos_asignados) ? 'checked' : '' ?>>
                                <label for="perm_<?= $p_id ?>" class="form-check-label">
                                    <strong><?= ucfirst(explode('.', $perm['clave'] ?? $perm['Clave'])[1] ?? ($perm['clave'] ?? $perm['Clave'])) ?></strong>
                                    <?php if (!empty($perm['descripcion'] ?? $perm['Descripcion'])): ?>
                                        <br><small
                                            style="color: var(--text-muted);"><?= e($perm['descripcion'] ?? $perm['Descripcion']) ?></small>
                                    <?php endif; ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-3"
            style="padding: 1rem; background: var(--bg-secondary); border-radius: 8px; border-left: 3px solid var(--success);">
            <strong>Permisos seleccionados: <span id="contador">0</span></strong>
        </div>
    </div>

    <!-- Información de Auditoría -->
    <div class="glass-panel mb-4" style="background: var(--bg-secondary);">
        <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.75rem; color: var(--text-secondary);">
            <i class="bi bi-clock-history"></i> Información de Auditoría
        </h3>
        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; font-size: 0.875rem;">
            <div>
                <strong>Creado:</strong><br>
                <?= formatoFechaHora($perfil['created_at'] ?? '') ?>
            </div>
            <?php if (!empty($perfil['updated_at'])): ?>
                <div>
                    <strong>Última actualización:</strong><br>
                    <?= formatoFechaHora($perfil['updated_at'] ?? '') ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="d-flex gap-2 justify-end">
        <a href="<?= BASE_URL ?>perfiles" class="btn btn-glass">
            <i class="bi bi-x-lg"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-primary" id="btnGuardar">
            <i class="bi bi-check-lg"></i> Guardar Cambios
        </button>
    </div>
</form>

<script>
    // Actualizar contador de permisos
    function actualizarContador() {
        const checked = document.querySelectorAll('input[name="permisos[]"]:checked').length;
        document.getElementById('contador').textContent = checked;
    }

    // Seleccionar todos los permisos
    function seleccionarTodos() {
        document.querySelectorAll('input[name="permisos[]"]').forEach(cb => {
            cb.checked = true;
        });
        actualizarContador();
    }

    // Deseleccionar todos los permisos
    function deseleccionarTodos() {
        document.querySelectorAll('input[name="permisos[]"]').forEach(cb => {
            cb.checked = false;
        });
        actualizarContador();
    }

    // Toggle permisos de un módulo
    function toggleModulo(modulo) {
        const checkboxes = document.querySelectorAll(`input[data-modulo="${modulo}"]`);
        const algunoMarcado = Array.from(checkboxes).some(cb => cb.checked);

        checkboxes.forEach(cb => {
            cb.checked = !algunoMarcado;
        });

        actualizarContador();
    }

    // Escuchar cambios en checkboxes
    document.querySelectorAll('input[name="permisos[]"]').forEach(cb => {
        cb.addEventListener('change', actualizarContador);
    });

    // Validar formulario
    document.getElementById('formPerfil').addEventListener('submit', function (e) {
        const permisosSeleccionados = document.querySelectorAll('input[name="permisos[]"]:checked').length;

        if (permisosSeleccionados === 0) {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Error', 'Debe seleccionar al menos un permiso para el perfil', 'error');
            } else {
                alert('Debe seleccionar al menos un permiso para el perfil');
            }
            e.preventDefault();
            return false;
        }

        if (typeof Swal !== 'undefined') {
            e.preventDefault();
            Swal.fire({
                title: '¿Guardar cambios?',
                text: 'Esto afectará a todos los usuarios con este perfil.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#002B49',
                cancelButtonColor: '#425563',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const btnGuardar = document.getElementById('btnGuardar');
                    btnGuardar.disabled = true;
                    btnGuardar.innerHTML = '<i class="bi bi-hourglass-split"></i> Guardando...';
                    document.getElementById('formPerfil').submit();
                }
            });
            return false;
        }

        return false;
    }

        const btnGuardar = document.getElementById('btnGuardar');
    btnGuardar.disabled = true;
    btnGuardar.innerHTML = '<i class="bi bi-hourglass-split"></i> Guardando...';
    });

    // Inicializar contador
    actualizarContador();
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>