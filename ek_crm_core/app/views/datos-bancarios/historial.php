<?php
$pagina_actual = 'proveedores';
$titulo = 'Historial de Cuenta';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<div class="d-flex justify-between align-center mb-4">
    <div>
        <h1 class="section-title">
            <i class="bi bi-clock-history"></i> Historial de Movimientos
        </h1>
        <p class="section-subtitle">
            Cuenta: <strong><?= e($cuenta['Cuenta'] ?? 'N/A') ?></strong> - <?= e($cuenta['BancoNombre']) ?>
        </p>
    </div>
    <a href="<?= BASE_URL ?>datos-bancarios/index/<?= $cuenta['ProveedorId'] ?>" class="btn btn-glass">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="grid-2">
    <div class="glass-panel">
        <h2 class="card-title mb-4"><i class="bi bi-list-check"></i> Actividad Reciente</h2>

        <?php if (empty($historial)): ?>
            <div class="alert alert-light border d-flex align-center gap-2 text-muted">
                <i class="bi bi-info-circle"></i>
                <div>No hay registros históricos para esta cuenta.</div>
            </div>
        <?php else: ?>
            <div class="timeline-container">
                <?php foreach ($historial as $evento): ?>
                    <div class="p-3 mb-3 rounded border historial-event">
                        <div class="d-flex justify-between align-center mb-1">
                            <span class="badge badge-primary"><?= e($evento['Accion']) ?></span>
                            <small class="text-muted text-xs">
                                <i class="bi bi-calendar3"></i> <?= formatoFechaHora($evento['Fecha']) ?>
                            </small>
                        </div>

                        <div class="small text-muted mb-2 mt-2">
                            Por: <strong><?= e($evento['UsuarioNombre'] ?? 'Sistema') ?></strong>
                        </div>

                        <?php if (!empty($evento['Notas'])): ?>
                            <div class="p-2 rounded bg-white small border text-secondary">
                                <i class="bi bi-chat-quote-fill opacity-50"></i> <em><?= e($evento['Notas']) ?></em>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="glass-panel">
        <div class="d-flex justify-between align-center mb-3">
            <h2 class="card-title mb-0"><i class="bi bi-paperclip"></i> Archivos Adjuntos</h2>
            <span class="badge badge-secondary"><?= count($adjuntos) ?></span>
        </div>

        <form method="POST" action="<?= BASE_URL ?>datos-bancarios/agregarAdjunto/<?= $cuenta['Id'] ?>"
            enctype="multipart/form-data" class="mb-4 pb-4 border-bottom bg-light p-3 rounded border">
            <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">

            <label class="form-label small fw-bold text-primary mb-2">Nuevo Documento</label>
            <div class="d-flex flex-column gap-2">
                <div class="d-flex gap-2">
                    <select name="TipoDocumento" class="form-control form-control-sm select-flex">
                        <option value="OTRO">Otro Documento</option>
                        <option value="ESTADO_CUENTA">Estado de Cuenta</option>
                        <option value="CONTRATO">Contrato</option>
                        <option value="CARTA">Carta</option>
                    </select>
                </div>
                <div class="d-flex gap-2">
                    <input type="file" name="Archivo" class="form-control form-control-sm" accept=".pdf" required>
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="bi bi-upload"></i>
                    </button>
                </div>
            </div>
        </form>

        <?php if (empty($adjuntos)): ?>
            <div class="text-center py-4 text-muted opacity-50">
                <i class="bi bi-folder-x icon-lg"></i>
                <p class="small mt-2">No hay documentos adicionales.</p>
            </div>
        <?php else: ?>
            <div class="d-flex flex-column gap-2 custom-scroll historial-scroll">
                <?php foreach ($adjuntos as $adj): ?>
                    <div class="d-flex align-center justify-between p-2 rounded border bg-white hover-bg transition">
                        <div class="overflow-hidden d-flex align-center gap-2">
                            <i class="bi bi-file-earmark-pdf text-danger fs-5"></i>
                            <div class="historial-file-info">
                                <div class="fw-bold small text-truncate text-dark">
                                    <?= e($adj['NombreArchivo']) ?>
                                </div>
                                <div class="text-muted historial-file-date">
                                    <span class="text-uppercase"><?= e($adj['TipoDocumento']) ?></span> •
                                    <?= formatoFecha($adj['FechaSubida']) ?>
                                </div>
                            </div>
                        </div>

                        <a href="<?= BASE_URL ?>datos-bancarios/verArchivo/adjunto/<?= $adj['Id'] ?>" target="_blank"
                            class="btn btn-sm btn-glass text-primary" title="Ver Archivo">
                            <i class="bi bi-eye"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>