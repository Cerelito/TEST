<?php
$pagina_actual = 'proveedores';
$titulo = 'Importación Masiva';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<div class="d-flex justify-between align-center mb-4">
    <h1 class="section-title"><i class="bi bi-cloud-upload"></i> Importar Proveedores</h1>
    <a href="<?= BASE_URL ?>proveedores" class="btn btn-glass"><i class="bi bi-arrow-left"></i> Volver</a>
</div>

<div class="grid-2">
    <div class="glass-panel">
        <h2 class="card-title"><i class="bi bi-1-circle"></i> 1. Descargar Plantilla</h2>
        <p class="text-muted">Utilice nuestra plantilla oficial para evitar errores de formato.</p>

        <div class="alert alert-info alert-text-sm">
            <ul class="alert-list-compact">
                <li>Formato <strong>.CSV</strong> (Delimitado por comas).</li>
                <li>No modifique el orden de las columnas.</li>
                <li>El <strong>RFC</strong> es obligatorio y único.</li>
            </ul>
        </div>

        <div class="d-flex flex-column gap-2">
            <a href="<?= BASE_URL ?>proveedores/descargarPlantilla" class="btn btn-primary w-100">
                <i class="bi bi-download"></i> Descargar Plantilla Vacía
            </a>
            <a href="<?= BASE_URL ?>proveedores/descargarPlantillaConDatos" class="btn btn-warning w-100">
                <i class="bi bi-file-earmark-spreadsheet"></i> Descargar con Todos los Proveedores
            </a>
            <small class="text-muted text-center">Ideal para ediciones masivas.</small>
        </div>
    </div>

    <div class="glass-panel">
        <h2 class="card-title"><i class="bi bi-2-circle"></i> 2. Subir Archivo</h2>
        <p class="text-muted">Seleccione su archivo completado para procesarlo.</p>

        <form method="POST" action="<?= BASE_URL ?>proveedores/procesarImportacion" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">

            <div class="form-group mb-4">
                <input type="file" name="archivo_csv" class="form-control" accept=".csv" required>
            </div>

            <button type="submit" class="btn btn-success w-100">
                <i class="bi bi-check-lg"></i> Importar Ahora
            </button>
        </form>
    </div>
</div>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>