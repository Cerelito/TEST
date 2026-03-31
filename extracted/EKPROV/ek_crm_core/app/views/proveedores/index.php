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
                        <th style="width: 90px;">ID</th>
                        <th style="width: 130px;">RFC</th>
                        <th>Razón Social / Nombre</th>
                        <th style="width: 110px;">Estatus</th>
                        <th style="width: 80px; text-align: center;">CÍAs</th>
                        <th style="width: 110px;">Registro</th>
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
                                <td style="text-align: center;">
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

<style>
    /* ==========================================
   GLASSMORPHISM THEME - PROVEEDORES
   ========================================== */

    :root {
        /* Colores Base Azules */
        --glass-primary: #3b82f6;
        --glass-primary-dark: #2563eb;
        --glass-primary-light: rgba(59, 130, 246, 0.15);
        --glass-success: #10b981;
        --glass-warning: #f59e0b;
        --glass-danger: #ef4444;
        --glass-info: #06b6d4;

        /* Backgrounds Glass - MODO CLARO */
        --glass-bg: rgba(255, 255, 255, 0.4);
        --glass-bg-card: rgba(255, 255, 255, 0.6);
        --glass-bg-input: rgba(255, 255, 255, 0.7);
        --glass-border: rgba(59, 130, 246, 0.25);

        /* Text Colors - MODO CLARO */
        --glass-text-main: #1e293b;
        --glass-text-muted: #64748b;
        --glass-text-light: #94a3b8;

        /* Effects */
        --glass-blur: blur(30px);
        --glass-shadow: 0 8px 32px 0 rgba(59, 130, 246, 0.2);
        --glass-shadow-hover: 0 12px 40px 0 rgba(59, 130, 246, 0.3);
        --glass-radius: 16px;
    }

    body.dark-mode {
        /* Backgrounds Glass - MODO OSCURO */
        --glass-bg: rgba(30, 41, 59, 0.5);
        --glass-bg-card: rgba(30, 41, 59, 0.65);
        --glass-bg-input: rgba(15, 23, 42, 0.55);
        --glass-border: rgba(59, 130, 246, 0.35);

        /* Text Colors - MODO OSCURO */
        --glass-text-main: rgba(255, 255, 255, 0.95);
        --glass-text-muted: rgba(255, 255, 255, 0.65);
        --glass-text-light: rgba(255, 255, 255, 0.45);

        /* Effects Oscuros */
        --glass-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.5);
        --glass-shadow-hover: 0 12px 40px 0 rgba(0, 0, 0, 0.6);
    }

    /* ==========================================
   LAYOUT
   ========================================== */
    .proveedores-page {
        padding: 2rem;
        max-width: 1600px;
        margin: 0 auto;
        animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ==========================================
   HERO HEADER GLASS
   ========================================== */
    .page-hero-proveedores {
        background: var(--glass-bg-card);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        border-radius: var(--glass-radius);
        padding: 2rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--glass-shadow), inset 0 1px 0 0 rgba(255, 255, 255, 0.3);
        animation: slideUp 0.6s ease-out;
    }

    .hero-content-proveedores {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 2rem;
    }

    .hero-tag-proveedores {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--glass-bg-input);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        padding: 0.4rem 1rem;
        border-radius: 50px;
        color: var(--glass-text-main);
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
    }

    .hero-title-proveedores {
        font-size: 1.875rem;
        font-weight: 800;
        color: var(--glass-text-main);
        margin: 0 0 0.5rem 0;
    }

    .hero-subtitle-proveedores {
        color: var(--glass-text-muted);
        font-size: 1rem;
        margin: 0;
    }

    .hero-actions-proveedores {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .btn-hero-action-glass {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-hero-action-glass.primary {
        background: var(--glass-primary);
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-hero-action-glass.primary:hover {
        background: var(--glass-primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        color: white;
    }

    .btn-hero-action-glass.success {
        background: var(--glass-success);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-hero-action-glass.success:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
        color: white;
    }

    .btn-hero-action-glass.secondary {
        background: var(--glass-bg-card);
        color: var(--glass-text-main);
        border: 2px solid var(--glass-border);
    }

    .btn-hero-action-glass.secondary:hover {
        background: var(--glass-bg-input);
        border-color: var(--glass-primary);
        transform: translateY(-2px);
        color: var(--glass-text-main);
    }

    /* ==========================================
   FILTERS GLASS
   ========================================== */
    .filters-card-proveedores {
        background: var(--glass-bg-card);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        border-radius: var(--glass-radius);
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--glass-shadow);
        animation: slideUp 0.6s ease-out 0.1s both;
    }

    .filters-form-proveedores {
        display: flex;
        gap: 1rem;
        align-items: flex-end;
        flex-wrap: wrap;
    }

    .filter-group-proveedores {
        flex: 1;
        min-width: 180px;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .filter-group-proveedores.search {
        flex: 2;
        min-width: 250px;
    }

    .filter-label-proveedores {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--glass-text-main);
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .filter-input-wrapper-proveedores {
        position: relative;
        display: flex;
        align-items: center;
    }

    .filter-input-wrapper-proveedores i {
        position: absolute;
        left: 1rem;
        color: var(--glass-text-muted);
        font-size: 0.9rem;
    }

    .filter-input-proveedores,
    .filter-select-proveedores {
        width: 100%;
        padding: 0.75rem 1rem;
        background: var(--glass-bg-input);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        border-radius: 10px;
        font-size: 0.875rem;
        color: var(--glass-text-main);
        transition: all 0.3s ease;
    }

    .filter-input-proveedores {
        padding-left: 2.75rem;
    }

    .filter-input-proveedores:focus,
    .filter-select-proveedores:focus {
        outline: none;
        border-color: var(--glass-primary);
        background: rgba(255, 255, 255, 0.8);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
    }

    body.dark-mode .filter-input-proveedores:focus,
    body.dark-mode .filter-select-proveedores:focus {
        background: rgba(15, 23, 42, 0.7);
    }

    .filter-actions-proveedores {
        display: flex;
        gap: 0.5rem;
    }

    .btn-filter-proveedores {
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 700;
    }

    .btn-filter-proveedores.primary {
        background: var(--glass-primary);
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-filter-proveedores.primary:hover {
        background: var(--glass-primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
    }

    .btn-filter-proveedores.secondary {
        background: var(--glass-bg-card);
        color: var(--glass-text-main);
        border: 2px solid var(--glass-border);
        padding: 0.75rem 1rem;
    }

    .btn-filter-proveedores.secondary:hover {
        background: var(--glass-bg-input);
        border-color: var(--glass-primary);
        transform: translateY(-2px);
    }

    /* ==========================================
   TABLE GLASS
   ========================================== */
    .table-card-proveedores {
        background: var(--glass-bg-card);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        border-radius: var(--glass-radius);
        overflow: hidden;
        box-shadow: var(--glass-shadow);
        animation: slideUp 0.6s ease-out 0.2s both;
    }

    .table-wrapper-proveedores {
        overflow-x: auto;
    }

    .table-proveedores {
        width: 100%;
        border-collapse: collapse;
    }

    .table-proveedores thead {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.08) 0%, rgba(139, 92, 246, 0.08) 100%);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
    }

    .table-proveedores th {
        padding: 1rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--glass-text-main);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--glass-border);
    }

    .table-actions-header-proveedores {
        text-align: right;
        position: sticky;
        right: 0;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.08) 0%, rgba(139, 92, 246, 0.08) 100%);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border-left: 2px solid var(--glass-border);
        z-index: 10;
    }

    .table-row-proveedores {
        border-bottom: 1px solid var(--glass-border);
        transition: all 0.2s ease;
    }

    .table-row-proveedores:hover {
        background: rgba(59, 130, 246, 0.05);
    }

    .table-proveedores td {
        padding: 1rem;
        font-size: 0.875rem;
        color: var(--glass-text-main);
    }

    .table-actions-cell-proveedores {
        text-align: right;
        position: sticky;
        right: 0;
        background: var(--glass-bg-card);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border-left: 2px solid var(--glass-border);
        z-index: 9;
    }

    .table-row-proveedores:hover .table-actions-cell-proveedores {
        background: rgba(59, 130, 246, 0.05);
    }

    /* Cell Styles */
    .id-badge-proveedores {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        background: var(--glass-primary-light);
        color: var(--glass-primary);
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.8rem;
    }

    .text-muted-proveedores {
        color: var(--glass-text-muted);
        font-style: italic;
    }

    .rfc-code-proveedores {
        font-family: 'Courier New', monospace;
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--glass-primary);
        background: var(--glass-primary-light);
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
    }

    .proveedor-info-cell {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .proveedor-name {
        color: var(--glass-text-main);
        font-weight: 600;
        font-size: 0.875rem;
    }

    .proveedor-comercial {
        color: var(--glass-text-muted);
        font-size: 0.75rem;
    }

    .status-badge-proveedores {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .status-dot-proveedores {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
            transform: scale(1);
        }

        50% {
            opacity: 0.7;
            transform: scale(0.95);
        }
    }

    .status-approved {
        background: rgba(16, 185, 129, 0.15);
        color: #047857;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .status-approved .status-dot-proveedores {
        background: #047857;
        box-shadow: 0 0 8px rgba(4, 120, 87, 0.6);
    }

    .status-pending {
        background: rgba(245, 158, 11, 0.15);
        color: #d97706;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .status-pending .status-dot-proveedores {
        background: #d97706;
        box-shadow: 0 0 8px rgba(217, 119, 6, 0.6);
    }

    .status-rejected {
        background: rgba(239, 68, 68, 0.15);
        color: #b91c1c;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .status-rejected .status-dot-proveedores {
        background: #b91c1c;
        box-shadow: 0 0 8px rgba(185, 28, 28, 0.6);
    }

    .status-default {
        background: rgba(100, 116, 139, 0.15);
        color: #64748b;
        border: 1px solid rgba(100, 116, 139, 0.3);
    }

    .cias-badge-proveedores {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.75rem;
        background: rgba(6, 182, 212, 0.15);
        color: #0891b2;
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .fecha-cell-proveedores {
        color: var(--glass-text-muted);
        font-size: 0.8rem;
    }

    .actions-group-proveedores {
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
    }

    .btn-action-proveedores {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        font-size: 0.9rem;
    }

    .btn-action-proveedores.view {
        background: var(--glass-bg-input);
        color: var(--glass-text-main);
        border: 2px solid var(--glass-border);
    }

    .btn-action-proveedores.view:hover {
        background: var(--glass-primary-light);
        color: var(--glass-primary);
        border-color: var(--glass-primary);
        transform: translateY(-2px);
    }

    .btn-action-proveedores.edit {
        background: var(--glass-primary);
        color: white;
    }

    .btn-action-proveedores.edit:hover {
        background: var(--glass-primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }

    .btn-action-proveedores.request {
        background: var(--glass-warning);
        color: white;
    }

    .btn-action-proveedores.request:hover {
        background: #d97706;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    }

    .btn-action-proveedores.delete {
        background: transparent;
        color: var(--glass-danger);
        border: 2px solid var(--glass-border);
    }

    .btn-action-proveedores.delete:hover {
        background: var(--glass-danger);
        color: white;
        border-color: var(--glass-danger);
        transform: translateY(-2px);
    }

    .empty-state-proveedores {
        text-align: center;
        padding: 5rem 2rem !important;
    }

    .empty-content-proveedores {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }

    .empty-content-proveedores i {
        font-size: 4rem;
        color: var(--glass-text-light);
        opacity: 0.3;
    }

    .empty-content-proveedores p {
        color: var(--glass-text-muted);
        font-size: 1rem;
        margin: 0;
    }

    /* ==========================================
   PAGINATION GLASS
   ========================================== */
    .pagination-container-proveedores {
        padding: 1.25rem 1.5rem;
        border-top: 2px solid var(--glass-border);
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.05) 0%, rgba(139, 92, 246, 0.05) 100%);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .pagination-info-proveedores {
        font-size: 0.875rem;
        color: var(--glass-text-muted);
    }

    .pagination-info-proveedores strong {
        color: var(--glass-text-main);
        font-weight: 700;
    }

    .pagination-proveedores {
        display: flex;
        gap: 0.5rem;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .page-item-proveedores {
        list-style: none;
    }

    .page-link-proveedores {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        padding: 0.5rem 0.75rem;
        background: var(--glass-bg-card);
        color: var(--glass-text-main);
        border: 2px solid var(--glass-border);
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .page-link-proveedores:hover {
        background: var(--glass-primary-light);
        color: var(--glass-primary);
        border-color: var(--glass-primary);
        transform: translateY(-2px);
    }

    .page-item-proveedores.active .page-link-proveedores {
        background: var(--glass-primary);
        color: white;
        border-color: var(--glass-primary);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .page-item-proveedores.disabled .page-link-proveedores {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }

    /* ==========================================
   RESPONSIVE
   ========================================== */
    @media (max-width: 992px) {
        .proveedores-page {
            padding: 1rem;
        }

        .hero-content-proveedores {
            flex-direction: column;
            align-items: flex-start;
        }

        .hero-actions-proveedores {
            width: 100%;
            justify-content: stretch;
        }

        .btn-hero-action-glass {
            flex: 1;
            justify-content: center;
        }

        .filters-form-proveedores {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-group-proveedores,
        .filter-group-proveedores.search {
            width: 100%;
        }

        .filter-actions-proveedores {
            width: 100%;
        }

        .btn-filter-proveedores {
            flex: 1;
        }

        .table-wrapper-proveedores {
            overflow-x: scroll;
        }

        .pagination-container-proveedores {
            flex-direction: column;
            text-align: center;
        }

        .pagination-proveedores {
            justify-content: center;
        }
    }

    @media (max-width: 640px) {
        .hero-title-proveedores {
            font-size: 1.5rem;
        }

        .actions-group-proveedores {
            flex-wrap: wrap;
        }
    }
</style>

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