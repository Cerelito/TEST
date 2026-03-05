<?php
$pagina_actual = 'solicitudes';
$titulo = 'Solicitudes de Cambio';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<div class="solicitudes-page">
    <!-- Hero Header Glass -->
    <div class="page-hero-solicitudes">
        <div class="hero-content-solicitudes">
            <div class="hero-info-solicitudes">
                <div class="hero-tag-solicitudes">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Gestión de Cambios</span>
                </div>
                <h1 class="hero-title-solicitudes">Solicitudes de Cambio</h1>
                <p class="hero-subtitle-solicitudes">Gestión de solicitudes de modificación y altas</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards Glass -->
    <?php if (isset($stats)): ?>
        <div class="stats-grid-solicitudes">
            <div class="stat-card-glass">
                <div class="stat-icon-glass warning">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="stat-content-glass">
                    <span class="stat-label-glass">Pendientes</span>
                    <span class="stat-value-glass">
                        <?= $stats['pendientes'] ?? 0 ?>
                    </span>
                </div>
            </div>

            <div class="stat-card-glass">
                <div class="stat-icon-glass success">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-content-glass">
                    <span class="stat-label-glass">Aprobadas</span>
                    <span class="stat-value-glass">
                        <?= $stats['aprobadas'] ?? 0 ?>
                    </span>
                </div>
            </div>

            <div class="stat-card-glass">
                <div class="stat-icon-glass danger">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div class="stat-content-glass">
                    <span class="stat-label-glass">Rechazadas</span>
                    <span class="stat-value-glass">
                        <?= $stats['rechazadas'] ?? 0 ?>
                    </span>
                </div>
            </div>

            <div class="stat-card-glass">
                <div class="stat-icon-glass primary">
                    <i class="bi bi-list-check"></i>
                </div>
                <div class="stat-content-glass">
                    <span class="stat-label-glass">Total</span>
                    <span class="stat-value-glass">
                        <?= $stats['total'] ?? 0 ?>
                    </span>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Filters Glass -->
    <div class="filters-card-glass">
        <form method="GET" action="<?= BASE_URL ?>solicitudes" class="filters-form-glass">
            <div class="filter-group-glass">
                <label class="filter-label-glass">Búsqueda</label>
                <div class="filter-input-wrapper-glass">
                    <i class="bi bi-search"></i>
                    <input type="text" name="busqueda" class="filter-input-glass"
                        placeholder="RFC, proveedor, solicitante..." value="<?= e($_GET['busqueda'] ?? '') ?>">
                </div>
            </div>

            <div class="filter-group-glass">
                <label class="filter-label-glass">Estatus</label>
                <select name="estatus" class="filter-select-glass">
                    <option value="">Todos</option>
                    <option value="Pendiente" <?= ($_GET['estatus'] ?? '') === 'Pendiente' ? 'selected' : '' ?>>Pendientes
                    </option>
                    <option value="Aprobada" <?= ($_GET['estatus'] ?? '') === 'Aprobada' ? 'selected' : '' ?>>Aprobadas
                    </option>
                    <option value="Rechazada" <?= ($_GET['estatus'] ?? '') === 'Rechazada' ? 'selected' : '' ?>>Rechazadas
                    </option>
                </select>
            </div>

            <div class="filter-actions-glass">
                <button type="submit" class="btn-filter-glass primary" title="Buscar">
                    <i class="bi bi-search"></i>
                </button>
                <a href="<?= BASE_URL ?>solicitudes" class="btn-filter-glass secondary" title="Limpiar">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Table Glass -->
    <div class="table-card-glass">
        <div class="table-wrapper-glass">
            <table class="table-glass">
                <thead>
                    <tr>
                        <th class="col-w-70">ID</th>
                        <th class="col-mw-180">Proveedor</th>
                        <th class="col-mw-160">Tipo</th>
                        <th class="col-mw-140">Solicitante</th>
                        <th class="col-mw-110">Fecha</th>
                        <th class="col-mw-90">Urgencia</th>
                        <th class="col-mw-90">Estatus</th>
                        <th class="table-actions-header-glass">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($solicitudes)): ?>
                        <?php foreach ($solicitudes as $sol): ?>
                            <tr class="table-row-glass">
                                <td>
                                    <span class="id-badge-glass">#
                                        <?= $sol['Id'] ?? $sol['id'] ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="proveedor-cell-glass">
                                        <strong>
                                            <?= e($sol['RFC'] ?? $sol['rfc'] ?? '') ?>
                                        </strong>
                                        <small>
                                            <?= e($sol['RazonSocial'] ?? $sol['razonsocial'] ?? $sol['proveedor_nombre'] ?? '') ?>
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                    $tipo = $sol['TipoCambio'] ?? 'GENERAL';
                                    $config = match ($tipo) {
                                        'Cuentas Bancarias' => ['class' => 'type-bancario', 'icon' => 'bi-bank2'],
                                        'Datos Generales' => ['class' => 'type-fiscal', 'icon' => 'bi-file-earmark-person'],
                                        'Datos de Contacto' => ['class' => 'type-contacto', 'icon' => 'bi-envelope'],
                                        'ALTA NUEVO PROVEEDOR' => ['class' => 'type-alta', 'icon' => 'bi-person-plus-fill'],
                                        default => ['class' => 'type-general', 'icon' => 'bi-pencil-square']
                                    };
                                    ?>
                                    <span class="type-badge-glass <?= $config['class'] ?>">
                                        <i class="bi <?= $config['icon'] ?>"></i>
                                        <span>
                                            <?= e($tipo) ?>
                                        </span>
                                    </span>
                                </td>
                                <td>
                                    <div class="solicitante-cell-glass">
                                        <i class="bi bi-person-circle"></i>
                                        <span>
                                            <?= e($sol['solicitante_nombre']) ?>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="fecha-cell-glass">
                                        <?= formatoFechaHora($sol['FechaSolicitud'] ?? $sol['fechasolicitud'] ?? '') ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $urgencia = $sol['Urgencia'] ?? $sol['urgencia'] ?? 'Media';
                                    $urg_class = match ($urgencia) {
                                        'Crítica' => 'urgency-critical',
                                        'Alta' => 'urgency-high',
                                        'Media' => 'urgency-medium',
                                        default => 'urgency-low'
                                    };
                                    ?>
                                    <span class="urgency-badge-glass <?= $urg_class ?>">
                                        <?= e($urgencia) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $estatus = strtoupper($sol['Estatus'] ?? $sol['estatus'] ?? 'PENDIENTE');
                                    $est_class = match ($estatus) {
                                        'APROBADA', 'APROBADO' => 'status-approved',
                                        'PENDIENTE' => 'status-pending',
                                        'RECHAZADA', 'RECHAZADO' => 'status-rejected',
                                        default => 'status-default'
                                    };
                                    ?>
                                    <span class="status-badge-glass <?= $est_class ?>">
                                        <span class="status-dot-glass"></span>
                                        <?= e($sol['Estatus'] ?? 'Pendiente') ?>
                                    </span>
                                </td>
                                <td class="table-actions-cell-glass">
                                    <div class="actions-group-glass">
                                        <a href="<?= BASE_URL ?>solicitudes/revisar/<?= $sol['Id'] ?? $sol['id'] ?>"
                                            class="btn-action-table-glass primary" title="Ver detalles">
                                            <i class="bi bi-eye"></i>
                                            <span>
                                                <?= (($sol['Estatus'] ?? '') === 'PENDIENTE') && (esAdmin() || tienePermiso('solicitudes.aprobar')) ? 'Revisar' : 'Ver' ?>
                                            </span>
                                        </a>
                                        <?php if (esAdmin()): ?>
                                            <button type="button" class="btn-action-table-glass danger"
                                                onclick="eliminarSolicitud(<?= $sol['Id'] ?? $sol['id'] ?>)" title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="empty-state-table-glass">
                                <div class="empty-content-glass">
                                    <i class="bi bi-inbox"></i>
                                    <p>No se encontraron solicitudes</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination Glass -->
        <?php if ($total_paginas > 1): ?>
            <div class="pagination-container-glass">
                <div class="pagination-info-glass">
                    Mostrando <strong>
                        <?= count($solicitudes) ?>
                    </strong> de <strong>
                        <?= $total_registros ?>
                    </strong> solicitudes
                </div>
                <ul class="pagination-glass">
                    <?php if ($pagina > 1): ?>
                        <li class="page-item-glass">
                            <a class="page-link-glass"
                                href="?p=<?= $pagina - 1 ?><?= isset($_GET['estatus']) ? '&estatus=' . $_GET['estatus'] : '' ?><?= isset($_GET['busqueda']) ? '&busqueda=' . $_GET['busqueda'] : '' ?>">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php
                    $start = max(1, $pagina - 2);
                    $end = min($total_paginas, $pagina + 2);

                    if ($start > 1)
                        echo '<li class="page-item-glass disabled"><span class="page-link-glass">...</span></li>';

                    for ($i = $start; $i <= $end; $i++): ?>
                        <li class="page-item-glass <?= $i == $pagina ? 'active' : '' ?>">
                            <a class="page-link-glass"
                                href="?p=<?= $i ?><?= isset($_GET['estatus']) ? '&estatus=' . $_GET['estatus'] : '' ?><?= isset($_GET['busqueda']) ? '&busqueda=' . $_GET['busqueda'] : '' ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($end < $total_paginas)
                        echo '<li class="page-item-glass disabled"><span class="page-link-glass">...</span></li>'; ?>

                    <?php if ($pagina < $total_paginas): ?>
                        <li class="page-item-glass">
                            <a class="page-link-glass"
                                href="?p=<?= $pagina + 1 ?><?= isset($_GET['estatus']) ? '&estatus=' . $_GET['estatus'] : '' ?><?= isset($_GET['busqueda']) ? '&busqueda=' . $_GET['busqueda'] : '' ?>">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    async function eliminarSolicitud(id) {
        const confirmed = await confirmDialog(
            '¿Eliminar Solicitud?',
            'Esta acción no se puede deshacer y borrará permanentemente la solicitud.',
            'Sí, eliminar',
            'Cancelar'
        );

        if (confirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= BASE_URL ?>solicitudes/eliminar/' + id;

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = 'csrf_token';
            csrfInput.value = '<?= generarToken() ?>';

            form.appendChild(csrfInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>