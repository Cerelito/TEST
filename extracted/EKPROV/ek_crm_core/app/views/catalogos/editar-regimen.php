<?php
$pagina_actual = 'catalogos';
$titulo = 'Editar Régimen Fiscal';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<div class="d-flex justify-between align-center mb-4">
    <div>
        <h1 class="section-title">
            <i class="bi bi-file-earmark-ruled"></i> Editar Régimen Fiscal
        </h1>
        <p class="section-subtitle">
            Modificar información del régimen fiscal
        </p>
    </div>
    <a href="<?= BASE_URL ?>catalogos" class="btn btn-glass">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<form method="POST" action="<?= BASE_URL ?>catalogos/actualizarRegimen/<?= $regimen['Id'] ?? $regimen['id'] ?? '' ?>" id="formRegimen">
    <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">

    <div class="glass-panel mb-4">
        <h2 class="card-title">
            <i class="bi bi-info-circle"></i> Datos del Régimen Fiscal
        </h2>

        <div class="grid-container" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)) !important;">
            <div class="form-group">
                <label for="Clave" class="form-label">Clave SAT <span class="text-danger">*</span></label>
                <input type="text" id="Clave" name="Clave" class="form-control" required maxlength="10"
                    value="<?= e($regimen['Clave'] ?? $regimen['clave'] ?? '') ?>" autofocus>
                <small class="form-text">Clave oficial del SAT</small>
            </div>

            <div class="form-group">
                <label for="Descripcion" class="form-label">Descripción <span class="text-danger">*</span></label>
                <input type="text" id="Descripcion" name="Descripcion" class="form-control" required maxlength="200"
                    value="<?= e($regimen['Descripcion'] ?? $regimen['descripcion'] ?? '') ?>">
                <small class="form-text">Descripción del régimen fiscal</small>
            </div>

            <div class="form-group">
                <label for="TipoPersona" class="form-label">Tipo de Persona <span class="text-danger">*</span></label>
                <select id="TipoPersona" name="TipoPersona" class="form-control" required>
                    <?php $tipo = $regimen['TipoPersona'] ?? $regimen['tipopersona'] ?? 'Ambas'; ?>
                    <option value="Ambas" <?= $tipo === 'Ambas' ? 'selected' : '' ?>>Ambas</option>
                    <option value="Física" <?= $tipo === 'Física' || $tipo === 'Fisica' ? 'selected' : '' ?>>Persona Física</option>
                    <option value="Moral" <?= $tipo === 'Moral' ? 'selected' : '' ?>>Persona Moral</option>
                </select>
                <small class="form-text">Tipo de persona que puede usar este régimen</small>
            </div>

            <div class="form-group">
                <label class="form-label">Estado</label>
                <div class="form-check">
                    <input type="checkbox" id="Activo" name="Activo" class="form-check-input" 
                        <?= ($regimen['Activo'] ?? $regimen['activo'] ?? 0) ? 'checked' : '' ?>>
                    <label for="Activo" class="form-check-label">
                        Activo
                    </label>
                </div>
                <small class="form-text">Los regímenes inactivos no se mostrarán en los formularios</small>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 justify-end mb-5">
        <a href="<?= BASE_URL ?>catalogos" class="btn btn-glass">
            <i class="bi bi-x-lg"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg"></i> Actualizar Régimen
        </button>
    </div>
</form>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>