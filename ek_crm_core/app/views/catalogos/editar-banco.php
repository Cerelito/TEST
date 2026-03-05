<?php
$pagina_actual = 'catalogos';
$titulo = 'Editar Banco';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<div class="d-flex justify-between align-center mb-4">
    <div>
        <h1 class="section-title">
            <i class="bi bi-bank"></i> Editar Banco
        </h1>
        <p class="section-subtitle">
            Modificar información del banco
        </p>
    </div>
    <a href="<?= BASE_URL ?>catalogos" class="btn btn-glass">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<form method="POST" action="<?= BASE_URL ?>catalogos/actualizarBanco/<?= $banco['Id'] ?? $banco['id'] ?? '' ?>" id="formBanco">
    <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">

    <div class="glass-panel mb-4">
        <h2 class="card-title">
            <i class="bi bi-info-circle"></i> Datos del Banco
        </h2>

        <div class="grid-container grid-auto-fit-250">
            <div class="form-group">
                <label for="CLABE" class="form-label">CLABE <span class="text-danger">*</span></label>
                <input type="text" id="CLABE" name="CLABE" class="form-control" required maxlength="10"
                    value="<?= e($banco['CLABE'] ?? $banco['clabe'] ?? '') ?>" autofocus>
                <small class="form-text">Código único del banco (3 dígitos)</small>
            </div>

            <div class="form-group">
                <label for="Nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                <input type="text" id="Nombre" name="Nombre" class="form-control" required maxlength="100"
                    value="<?= e($banco['Nombre'] ?? $banco['nombre'] ?? '') ?>">
                <small class="form-text">Nombre completo del banco</small>
            </div>

            <div class="form-group">
                <label class="form-label">Estado</label>
                <div class="form-check">
                    <input type="checkbox" id="Activo" name="Activo" class="form-check-input" 
                        <?= ($banco['Activo'] ?? $banco['activo'] ?? 0) ? 'checked' : '' ?>>
                    <label for="Activo" class="form-check-label">
                        Activo
                    </label>
                </div>
                <small class="form-text">Los bancos inactivos no se mostrarán en los formularios</small>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 justify-end mb-5">
        <a href="<?= BASE_URL ?>catalogos" class="btn btn-glass">
            <i class="bi bi-x-lg"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg"></i> Actualizar Banco
        </button>
    </div>
</form>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>