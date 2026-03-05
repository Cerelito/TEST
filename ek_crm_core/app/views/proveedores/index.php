<?php
$pagina_actual = 'proveedores';
$titulo = 'Proveedores';
require_once VIEWS_PATH . 'layouts/header.php';

// Helper local para evitar errores de mayúsculas/minúsculas en claves del array
$getVal = function ($row, $key) {
    return $row[$key] ?? $row[strtolower($key)] ?? $row[ucfirst($key)] ?? null;
};
?>

<div class="proveedores-page">
    <!-- Hero Header Glass -->
    <div class="page-hero-proveedores">
        <div class="hero-content-proveedores">
            <div class="hero-info-proveedores">
                <div class="hero-tag-proveedores">
                    <i class="bi bi-building"></i>
                    <span>Gestión Empresarial</span>
                </div>
                <h1 class="hero-title-proveedores">Proveedores</h1>
                <p class="hero-subtitle-proveedores">Gestión de proveedores del sistema</p>
            </div>

            <div class="hero-actions-proveedores">
                <?php if (!esCapturista()): ?>
                    <a href="<?= BASE_URL ?>proveedores/exportar" class="btn-hero-action-glass secondary"
                        title="Exportar listado a CSV">
                        <i class="bi bi-download"></i>
                        <span>Exportar</span>
                    </a>
                <?php endif; ?>

                <?php if (puedeCrear('proveedores')): ?>
                    <?php if (!esCapturista()): ?>
                        <a href="<?= BASE_URL ?>proveedores/importar" class="btn-hero-action-glass success"
                            title="Carga Masiva desde CSV">
                            <i class="bi bi-file-earmark-spreadsheet"></i>
                            <span>Importar</span>
                        </a>
                    <?php endif; ?>
                    <a href="<?= BASE_URL ?>proveedores/crear" class="btn-hero-action-glass primary">
                        <i class="bi bi-plus-lg"></i>
                        <span>Nuevo Proveedor</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Filters Glass -->
    <div class="filters-card-proveedores">
        <form method="GET" action="<?= BASE_URL ?>proveedores" class="filters-form-proveedores">
            <div class="filter-group-proveedores search">
                <label class="filter-label-proveedores">Búsqueda</label>
                <div class="filter-input-wrapper-proveedores">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" class="filter-input-proveedores" placeholder="RFC, Razón Social, ID..."
                        value="<?= e($_GET['q'] ?? '') ?>">
                </div>
            </div>

            <div class="filter-group-proveedores">
                <label class="filter-label-proveedores">Estatus</label>
                <select name="estatus" class="filter-select-proveedores">
                    <option value="">Todos los estatus</option>
                    <option value="Aprobado" <?= ($_GET['estatus'] ?? '') === 'Aprobado' ? 'selected' : '' ?>>Aprobados
                    </option>
                    <option value="Pendiente" <?= ($_GET['estatus'] ?? '') === 'Pendiente' ? 'selected' : '' ?>>Pendientes
                    </option>
                    <option value="Rechazado" <?= ($_GET['estatus'] ?? '') === 'Rechazado' ? 'selected' : '' ?>>Rechazados
                    </option>
                </select>
            </div>

            <div class="filter-actions-proveedores">
                <button type="submit" class="btn-filter-proveedores primary" title="Buscar">
                    <span>Buscar</span>
                </button>
                <?php if (!empty($_GET['q']) || !empty($_GET['estatus'])): ?>
                    <a href="<?= BASE_URL ?>proveedores" class="btn-filter-proveedores secondary" title="Limpiar">
                        <i class="bi bi-x-lg"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Table Glass -->
    <div class="table-card-proveedores">
        <div class="table-wrapper-proveedores">
            <table class="table-proveedores">
                <thead>
                    <tr>
                        <th class="col-w-90">ID</th>
                        <th class="col-w-130">RFC</th>
                        <th>Razón Social / Nombre</th>
                        <th class="col-w-110">Estatus</th>
                        <th class="col-w-80-center">CÍAs</th>
                        <th class="col-w-110">Registro</th>
                        <th class="table-actions-header-proveedores">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($proveedores)): ?>
                        <?php foreach ($proveedores as $prov): ?>
                            <?php
                            // Extracción segura de datos
                            $id = $getVal($prov, 'Id');
                            $idManual = $getVal($prov, 'IdManual');
                            $rfc = $getVal($prov, 'RFC');

                            // Lógica para Persona Física vs Moral
                            $razonSocial = $getVal($prov, 'RazonSocial');
                            if (empty($razonSocial)) {
                                // Si es persona física, construir nombre completo
                                $nombre = $getVal($prov, 'Nombre');
                                $paterno = $getVal($prov, 'ApellidoPaterno');
                                $materno = $getVal($prov, 'ApellidoMaterno');
                                $razonSocial = trim("$nombre $paterno $materno");
                            }

                            $nombreComercial = $getVal($prov, 'NombreComercial');
                            $estatus = strtoupper($getVal($prov, 'Estatus') ?? 'PENDIENTE');
                            $ciasNombres = $getVal($prov, 'cias_nombres');
                            $fechaRegistro = $getVal($prov, 'FechaRegistro');
                            ?>
                            <tr class="table-row-proveedores">
                                <td>
                                    <?php if (!empty($idManual)): ?>
                                        <span class="id-badge-proveedores">
                                            <?= e($idManual) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted-proveedores">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <code class="rfc-code-proveedores"><?= e($rfc) ?></code>
                                </td>
                                <td>
                                    <div class="proveedor-info-cell">
                                        <strong class="proveedor-name">
                                            <?= e($razonSocial) ?>
                                        </strong>
                                        <?php if (!empty($nombreComercial)): ?>
                                            <small class="proveedor-comercial">
                                                <?= e($nombreComercial) ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                    $badgeClass = match ($estatus) {
                                        'APROBADO', 'ACTIVO' => 'status-approved',
                                        'PENDIENTE' => 'status-pending',
                                        'RECHAZADO', 'INACTIVO' => 'status-rejected',
                                        default => 'status-default'
                                    };
                                    ?>
                                    <span class="status-badge-proveedores <?= $badgeClass ?>">
                                        <span class="status-dot-proveedores"></span>
                                        <?= $estatus ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="cias-badge-proveedores" title="<?= e($ciasNombres ?? 'Sin CÍAs') ?>">
                                        <i class="bi bi-building"></i>
                                        <?= substr_count($ciasNombres ?? '', ',') + (empty($ciasNombres) ? 0 : 1) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="fecha-cell-proveedores">
                                        <?= formatoFecha($fechaRegistro) ?>
                                    </span>
                                </td>
                                <td class="table-actions-cell-proveedores">
                                    <div class="actions-group-proveedores">
                                        <a href="<?= BASE_URL ?>proveedores/ver/<?= $id ?>" class="btn-action-proveedores view"
                                            title="Ver Detalle">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <?php if (puedeEditar('proveedores')): ?>
                                            <a href="<?= BASE_URL ?>proveedores/editar/<?= $id ?>"
                                                class="btn-action-proveedores edit" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        <?php endif; ?>

                                        <?php if (!esAdmin() && tienePermiso('proveedores.solicitar_cambio')): ?>
                                            <a href="<?= BASE_URL ?>proveedores/solicitarCambio/<?= $id ?>"
                                                class="btn-action-proveedores request" title="Solicitar Cambio">
                                                <i class="bi bi-file-earmark-text"></i>
                                            </a>
                                        <?php endif; ?>

                                        <?php if (puedeEliminar('proveedores')): ?>
                                            <button type="button" class="btn-action-proveedores delete" title="Eliminar"
                                                onclick="confirmarEliminacion(this)" data-id="<?= $id ?>"
                                                data-nombre="<?= e($razonSocial) ?>">
                                                <i class="bi bi-trash"></i>
                                            </button>

                                            <form method="POST" action="<?= BASE_URL ?>proveedores/eliminar/<?= $id ?>"
                                                style="display: none;" id="formEliminar<?= $id ?>">
                                                <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="empty-state-proveedores">
                                <div class="empty-content-proveedores">
                                    <i class="bi bi-inbox"></i>
                                    <p>No se encontraron proveedores registrados</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination Glass -->
        <?php if (isset($pagination) && $pagination['total'] > 1): ?>
            <div class="pagination-container-proveedores">
                <div class="pagination-info-proveedores">
                    Mostrando <strong>
                        <?= $pagination['from'] ?>
                    </strong> - <strong>
                        <?= $pagination['to'] ?>
                    </strong> de <strong>
                        <?= $pagination['totalRecords'] ?>
                    </strong> registros
                </div>

                <ul class="pagination-proveedores">
                    <!-- Botón Anterior -->
                    <li class="page-item-proveedores <?= $pagination['current'] <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link-proveedores"
                            href="?<?= http_build_query(array_merge($_GET, ['page' => $pagination['current'] - 1])) ?>">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>

                    <?php
                    $start = max(1, $pagination['current'] - 2);
                    $end = min($pagination['total'], $pagination['current'] + 2);

                    if ($start > 1): ?>
                        <li class="page-item-proveedores">
                            <a class="page-link-proveedores"
                                href="?<?= http_build_query(array_merge($_GET, ['page' => 1])) ?>">1</a>
                        </li>
                        <?php if ($start > 2): ?>
                            <li class="page-item-proveedores disabled"><span class="page-link-proveedores">...</span></li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php for ($i = $start; $i <= $end; $i++): ?>
                        <li class="page-item-proveedores <?= $i == $pagination['current'] ? 'active' : '' ?>">
                            <a class="page-link-proveedores"
                                href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($end < $pagination['total']): ?>
                        <?php if ($end < $pagination['total'] - 1): ?>
                            <li class="page-item-proveedores disabled"><span class="page-link-proveedores">...</span></li>
                        <?php endif; ?>
                        <li class="page-item-proveedores">
                            <a class="page-link-proveedores"
                                href="?<?= http_build_query(array_merge($_GET, ['page' => $pagination['total']])) ?>">
                                <?= $pagination['total'] ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Botón Siguiente -->
                    <li
                        class="page-item-proveedores <?= $pagination['current'] >= $pagination['total'] ? 'disabled' : '' ?>">
                        <a class="page-link-proveedores"
                            href="?<?= http_build_query(array_merge($_GET, ['page' => $pagination['current'] + 1])) ?>">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    async function confirmarEliminacion(btn) {
        const id = btn.getAttribute('data-id');
        const nombre = btn.getAttribute('data-nombre');

        const confirmed = await confirmDelete(
            `Proveedor: ${nombre}`,
            'Esta acción eliminará también sus cuentas bancarias y asignaciones. No se puede deshacer.'
        );

        if (confirmed) {
            document.getElementById('formEliminar' + id).submit();
        }
    }
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>