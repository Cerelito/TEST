<?php
$pagina_actual = 'catalogos';
$titulo = 'Editar Compañía';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<div class="d-flex justify-between align-center mb-4">
    <div>
        <h1 class="section-title">
            <i class="bi bi-building-gear"></i> Editar Compañía
        </h1>
        <p class="section-subtitle">
            Modificar información de la compañía
        </p>
    </div>
    <a href="<?= BASE_URL ?>catalogos" class="btn btn-glass">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<form method="POST" action="<?= BASE_URL ?>catalogos/actualizarCia/<?= $cia['Id'] ?? $cia['id'] ?? '' ?>" id="formCia">
    <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">

    <div class="glass-panel mb-4">
        <h2 class="card-title">
            <i class="bi bi-info-circle"></i> Datos de la Compañía
        </h2>

        <div class="grid-container" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)) !important;">
            <div class="form-group">
                <label for="Codigo" class="form-label">Código <span class="text-danger">*</span></label>
                <input type="number" id="Codigo" name="Codigo" class="form-control" required min="1" max="999"
                    value="<?= e($cia['Codigo'] ?? $cia['codigo'] ?? '') ?>">
                <small class="form-text">Código numérico único de la compañía</small>
            </div>

            <div class="form-group">
                <label for="Nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                <input type="text" id="Nombre" name="Nombre" class="form-control" required maxlength="100"
                    value="<?= e($cia['Nombre'] ?? $cia['nombre'] ?? '') ?>">
                <small class="form-text">Nombre de la compañía</small>
            </div>

            <div class="form-group">
                <label for="Descripcion" class="form-label">Descripción</label>
                <input type="text" id="Descripcion" name="Descripcion" class="form-control" maxlength="255"
                    value="<?= e($cia['Descripcion'] ?? $cia['descripcion'] ?? '') ?>">
                <small class="form-text">Información adicional de la compañía</small>
            </div>

            <div class="form-group">
                <label class="form-label">Estado</label>
                <div class="form-check">
                    <input type="checkbox" id="Activo" name="Activo" class="form-check-input" 
                        <?= ($cia['Activo'] ?? $cia['activo'] ?? 0) ? 'checked' : '' ?>>
                    <label for="Activo" class="form-check-label">
                        Activo
                    </label>
                </div>
                <small class="form-text">Las compañías inactivas no se mostrarán en los formularios</small>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 justify-end mb-5">
        <a href="<?= BASE_URL ?>catalogos" class="btn btn-glass">
            <i class="bi bi-x-lg"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg"></i> Actualizar Compañía
        </button>
    </div>
</form>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>