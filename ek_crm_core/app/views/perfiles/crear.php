<?php
$pagina_actual = 'perfiles';
$titulo = 'Nuevo Perfil';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<div class="d-flex justify-between align-center mb-4">
    <div>
        <h1 class="section-title">
            <i class="bi bi-shield-plus"></i> Nuevo Perfil
        </h1>
        <p class="section-subtitle">
            Cree un nuevo perfil y asigne permisos
        </p>
    </div>
    <a href="<?= BASE_URL ?>perfiles" class="btn btn-glass">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<form method="POST" action="<?= BASE_URL ?>perfiles/guardar" id="formPerfil">
    <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">

    <div class="glass-panel mb-4">
        <h2 class="card-title">
            <i class="bi bi-info-circle"></i> Información del Perfil
        </h2>

        <div class="grid-2">
            <div class="form-group">
                <label for="nombre" class="form-label">Nombre del Perfil <span class="text-danger">*</span></label>
                <input type="text" id="nombre" name="nombre" class="form-control" required maxlength="50"
                    value="<?= e($perfil['nombre'] ?? '') ?>" autofocus placeholder="Ej: Gerente, Contador">
            </div>

            <div class="form-group">
                <label for="descripcion" class="form-label">Descripción</label>
                <input type="text" id="descripcion" name="descripcion" class="form-control" maxlength="255"
                    value="<?= e($perfil['descripcion'] ?? '') ?>" placeholder="Breve descripción del rol">
            </div>
        </div>
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

        <div class="alert alert-info mb-4">
            <i class="bi bi-info-circle"></i>
            Seleccione los permisos que tendrá este perfil. Los permisos están agrupados por módulo.
        </div>

        <div class="grid-container grid-auto-fit-280">
            <?php foreach ($permisos_agrupados as $moduloKey => $grupo): ?>
                <div class="glass-panel perfil-module-card">
                    <div class="d-flex justify-between align-center mb-3 pb-2 border-bottom">
                        <h3 class="perfil-module-title">
                            <i class="bi <?= $grupo['info']['icono'] ?>"></i>
                            <?= $grupo['info']['titulo'] ?>
                        </h3>
                        <button type="button" class="btn btn-sm btn-glass" onclick="toggleModulo('<?= $moduloKey ?>')"
                            title="Seleccionar módulo">
                            <i class="bi bi-check-square"></i>
                        </button>
                    </div>

                    <div class="permisos-modulo" data-modulo="<?= $moduloKey ?>">
                        <?php if (!empty($grupo['permisos'])): ?>
                            <?php foreach ($grupo['permisos'] as $perm): ?>
                                <div class="form-check mb-2">
                                    <input type="checkbox" id="perm_<?= $perm['id'] ?>" name="permisos[]" value="<?= $perm['id'] ?>"
                                        class="form-check-input" data-modulo="<?= $moduloKey ?>">

                                    <label for="perm_<?= $perm['id'] ?>" class="form-check-label perfil-check-label">
                                        <strong><?= $perm['nombre'] ?></strong>
                                        <?php if (!empty($perm['descripcion'])): ?>
                                            <br><span class="text-muted perfil-check-desc"><?= e($perm['descripcion']) ?></span>
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

        <div class="mt-4 p-3 rounded info-panel-warning">
            <strong>Permisos seleccionados: <span id="contador">0</span></strong>
        </div>
    </div>

    <div class="d-flex gap-2 justify-end mb-5">
        <a href="<?= BASE_URL ?>perfiles" class="btn btn-glass">
            <i class="bi bi-x-lg"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-primary" id="btnGuardar">
            <i class="bi bi-check-lg"></i> Crear Perfil
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
            e.preventDefault();
            alertWarning('Permisos Requeridos', 'Debe seleccionar al menos un permiso para el perfil');
            return false;
        }

        const btnGuardar = document.getElementById('btnGuardar');
        btnGuardar.disabled = true;
        btnGuardar.innerHTML = '<i class="bi bi-hourglass-split"></i> Creando perfil...';
    });

    // Inicializar contador
    actualizarContador();
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>