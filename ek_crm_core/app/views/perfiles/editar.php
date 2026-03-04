<?php
$pagina_actual = 'perfiles';
$titulo = 'Editar Perfil';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<div class="d-flex justify-between align-center mb-4">
    <div>
        <h1 class="section-title">
            <i class="bi bi-pencil-square"></i> Editar Perfil
        </h1>
        <p class="section-subtitle">
            <?= e($perfil['nombre']) ?>
        </p>
    </div>
    <a href="<?= BASE_URL ?>perfiles" class="btn btn-glass">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<form method="POST" action="<?= BASE_URL ?>perfiles/actualizar/<?= $perfil['id'] ?>" id="formPerfil">
    <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">

    <div class="glass-panel mb-4">
        <h2 class="card-title">
            <i class="bi bi-info-circle"></i> Información del Perfil
        </h2>

        <div class="grid-2">
            <div class="form-group">
                <label for="nombre" class="form-label">Nombre del Perfil <span class="text-danger">*</span></label>
                <input type="text" id="nombre" name="nombre" class="form-control" required maxlength="50"
                    value="<?= e($perfil['nombre']) ?>" autofocus>
            </div>

            <div class="form-group">
                <label for="descripcion" class="form-label">Descripción</label>
                <input type="text" id="descripcion" name="descripcion" class="form-control" maxlength="255"
                    value="<?= e($perfil['descripcion'] ?? '') ?>">
            </div>
        </div>

        <?php if (!empty($perfil['total_usuarios'])): ?>
            <div class="alert alert-info mt-3 mb-0">
                <i class="bi bi-people"></i>
                <strong><?= $perfil['total_usuarios'] ?></strong> usuario(s) tienen asignado este perfil
            </div>
        <?php endif; ?>
    </div>

    <div class="glass-panel mb-4">
        <div class="d-flex justify-between align-center mb-3">
            <h2 class="card-title mb-0">
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

        <div class="grid-container" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)) !important;">
            <?php foreach ($permisos_agrupados as $moduloKey => $grupo): ?>
                <div class="glass-panel" style="background: var(--bg-secondary); padding: 1.25rem; margin-bottom: 0 !important;">
                    <div class="d-flex justify-between align-center mb-3 pb-2 border-bottom">
                        <h3 style="font-size: 1rem; font-weight: 600; color: var(--primary); margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="bi <?= $grupo['info']['icono'] ?>"></i> 
                            <?= $grupo['info']['titulo'] ?>
                        </h3>
                        <button type="button" class="btn btn-sm btn-glass" onclick="toggleModulo('<?= $moduloKey ?>')" title="Seleccionar módulo">
                            <i class="bi bi-check-square"></i>
                        </button>
                    </div>

                    <div class="permisos-modulo" data-modulo="<?= $moduloKey ?>">
                        <?php if (!empty($grupo['permisos'])): ?>
                            <?php foreach ($grupo['permisos'] as $perm): ?>
                                <div class="form-check mb-2">
                                    <input type="checkbox" 
                                           id="perm_<?= $perm['id'] ?>" 
                                           name="permisos[]" 
                                           value="<?= $perm['id'] ?>"
                                           class="form-check-input" 
                                           data-modulo="<?= $moduloKey ?>" 
                                           <?= in_array($perm['id'], $permisos_asignados) ? 'checked' : '' ?>>
                                    
                                    <label for="perm_<?= $perm['id'] ?>" class="form-check-label" style="font-size: 0.9rem;">
                                        <strong><?= $perm['nombre'] ?></strong>
                                        <?php if (!empty($perm['descripcion'])): ?>
                                            <br><span class="text-muted" style="font-size: 0.8rem; font-weight: normal;"><?= e($perm['descripcion']) ?></span>
                                        <?php endif; ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted small">No hay permisos definidos.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-4 p-3 rounded" style="background: var(--bg-secondary); border-left: 3px solid var(--success);">
            <strong>Permisos seleccionados: <span id="contador">0</span></strong>
        </div>
    </div>

    <div class="glass-panel mb-4" style="background: var(--bg-secondary);">
        <h3 class="card-title" style="font-size: 1rem; color: var(--text-secondary);">
            <i class="bi bi-clock-history"></i> Información de Auditoría
        </h3>
        <div class="grid-2" style="font-size: 0.875rem;">
            <div>
                <strong>Creado:</strong><br>
                <?= formatoFechaHora($perfil['created_at']) ?>
            </div>
            <?php if (!empty($perfil['updated_at'])): ?>
                <div>
                    <strong>Última actualización:</strong><br>
                    <?= formatoFechaHora($perfil['updated_at']) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="d-flex gap-2 justify-end mb-5">
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
    document.getElementById('formPerfil').addEventListener('submit', async function (e) {
        e.preventDefault();

        const permisosSeleccionados = document.querySelectorAll('input[name="permisos[]"]:checked').length;

        if (permisosSeleccionados === 0) {
            alertWarning('Permisos Requeridos', 'Debe seleccionar al menos un permiso para el perfil');
            return false;
        }

        const confirmed = await confirmDialog(
            '¿Guardar Cambios?',
            'Esto afectará a todos los usuarios con este perfil',
            'Sí, guardar',
            'Cancelar'
        );

        if (!confirmed) {
            return false;
        }

        const btnGuardar = document.getElementById('btnGuardar');
        btnGuardar.disabled = true;
        btnGuardar.innerHTML = '<i class="bi bi-hourglass-split"></i> Guardando...';

        // Enviar el formulario
        this.submit();
    });

    // Inicializar contador
    actualizarContador();
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>