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
                        <th style="width: 70px;">ID</th>
                        <th style="min-width: 180px;">Proveedor</th>
                        <th style="min-width: 160px;">Tipo</th>
                        <th style="min-width: 140px;">Solicitante</th>
                        <th style="min-width: 110px;">Fecha</th>
                        <th style="min-width: 90px;">Urgencia</th>
                        <th style="min-width: 90px;">Estatus</th>
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

<style>
    /* ==========================================
   GLASSMORPHISM THEME - SOLICITUDES
   ========================================== */

    /* ==========================================
   LAYOUT
   ========================================== */
    .solicitudes-page {
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
    .page-hero-solicitudes {
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

    .hero-tag-solicitudes {
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

    .hero-title-solicitudes {
        font-size: 1.875rem;
        font-weight: 800;
        color: var(--glass-text-main);
        margin: 0 0 0.5rem 0;
    }

    .hero-subtitle-solicitudes {
        color: var(--glass-text-muted);
        font-size: 1rem;
        margin: 0;
    }

    /* ==========================================
   STATS CARDS GLASS
   ========================================== */
    .stats-grid-solicitudes {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
        animation: slideUp 0.6s ease-out 0.1s both;
    }

    .stat-card-glass {
        background: var(--glass-bg-card);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        border-radius: 12px;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: var(--glass-shadow);
        transition: all 0.3s ease;
    }

    .stat-card-glass:hover {
        transform: translateY(-4px);
        box-shadow: var(--glass-shadow-hover);
    }

    .stat-icon-glass {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .stat-icon-glass.warning {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(251, 191, 36, 0.15) 100%);
        color: var(--glass-warning);
    }

    .stat-icon-glass.success {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(5, 150, 105, 0.15) 100%);
        color: var(--glass-success);
    }

    .stat-icon-glass.danger {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(220, 38, 38, 0.15) 100%);
        color: var(--glass-danger);
    }

    .stat-icon-glass.primary {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(37, 99, 235, 0.15) 100%);
        color: var(--glass-primary);
    }

    .stat-content-glass {
        display: flex;
        flex-direction: column;
    }

    .stat-label-glass {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--glass-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .stat-value-glass {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--glass-text-main);
        line-height: 1.2;
    }

    /* ==========================================
   FILTERS GLASS
   ========================================== */
    .filters-card-glass {
        background: var(--glass-bg-card);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        border-radius: var(--glass-radius);
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--glass-shadow);
        animation: slideUp 0.6s ease-out 0.2s both;
    }

    .filters-form-glass {
        display: flex;
        gap: 1rem;
        align-items: flex-end;
        flex-wrap: wrap;
    }

    .filter-group-glass {
        flex: 1;
        min-width: 200px;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .filter-label-glass {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--glass-text-main);
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .filter-input-wrapper-glass {
        position: relative;
        display: flex;
        align-items: center;
    }

    .filter-input-wrapper-glass i {
        position: absolute;
        left: 1rem;
        color: var(--glass-text-muted);
        font-size: 0.9rem;
    }

    .filter-input-glass,
    .filter-select-glass {
        width: 100%;
        padding: 0.75rem 1rem;
        padding-left: 2.75rem;
        background: var(--glass-bg-input);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        border-radius: 10px;
        font-size: 0.875rem;
        color: var(--glass-text-main);
        transition: all 0.3s ease;
    }

    .filter-select-glass {
        padding-left: 1rem;
    }

    .filter-input-glass:focus,
    .filter-select-glass:focus {
        outline: none;
        border-color: var(--glass-primary);
        background: rgba(255, 255, 255, 0.8);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
    }

    body.dark-mode .filter-input-glass:focus,
    body.dark-mode .filter-select-glass:focus {
        background: rgba(15, 23, 42, 0.7);
    }

    .filter-actions-glass {
        display: flex;
        gap: 0.5rem;
    }

    .btn-filter-glass {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        font-size: 1rem;
    }

    .btn-filter-glass.primary {
        background: var(--glass-primary);
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-filter-glass.primary:hover {
        background: var(--glass-primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
    }

    .btn-filter-glass.secondary {
        background: var(--glass-bg-card);
        color: var(--glass-text-main);
        border: 2px solid var(--glass-border);
    }

    .btn-filter-glass.secondary:hover {
        background: var(--glass-bg-input);
        border-color: var(--glass-primary);
        transform: translateY(-2px);
    }

    /* ==========================================
   TABLE GLASS
   ========================================== */
    .table-card-glass {
        background: var(--glass-bg-card);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        border-radius: var(--glass-radius);
        overflow: hidden;
        box-shadow: var(--glass-shadow);
        animation: slideUp 0.6s ease-out 0.3s both;
    }

    .table-wrapper-glass {
        overflow-x: auto;
    }

    .table-glass {
        width: 100%;
        border-collapse: collapse;
    }

    .table-glass thead {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.08) 0%, rgba(139, 92, 246, 0.08) 100%);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
    }

    .table-glass th {
        padding: 1rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--glass-text-main);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--glass-border);
    }

    .table-actions-header-glass {
        text-align: right;
        position: sticky;
        right: 0;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.08) 0%, rgba(139, 92, 246, 0.08) 100%);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border-left: 2px solid var(--glass-border);
        z-index: 10;
    }

    .table-row-glass {
        border-bottom: 1px solid var(--glass-border);
        transition: all 0.2s ease;
    }

    .table-row-glass:hover {
        background: rgba(59, 130, 246, 0.05);
    }

    .table-glass td {
        padding: 1rem;
        font-size: 0.875rem;
        color: var(--glass-text-main);
    }

    .table-actions-cell-glass {
        text-align: right;
        position: sticky;
        right: 0;
        background: var(--glass-bg-card);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border-left: 2px solid var(--glass-border);
        z-index: 9;
    }

    .table-row-glass:hover .table-actions-cell-glass {
        background: rgba(59, 130, 246, 0.05);
    }

    /* Table Cell Styles */
    .id-badge-glass {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        background: var(--glass-primary-light);
        color: var(--glass-primary);
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.8rem;
    }

    .proveedor-cell-glass {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .proveedor-cell-glass strong {
        color: var(--glass-text-main);
        font-size: 0.875rem;
    }

    .proveedor-cell-glass small {
        color: var(--glass-text-muted);
        font-size: 0.75rem;
    }

    .type-badge-glass {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.4rem 0.75rem;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .type-bancario {
        background: rgba(99, 102, 241, 0.15);
        color: #4f46e5;
        border: 1px solid rgba(99, 102, 241, 0.3);
    }

    .type-contacto {
        background: rgba(16, 185, 129, 0.15);
        color: #059669;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .type-fiscal {
        background: rgba(245, 158, 11, 0.15);
        color: #d97706;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .type-alta {
        background: rgba(59, 130, 246, 0.15);
        color: #2563eb;
        border: 1px solid rgba(59, 130, 246, 0.3);
    }

    .type-general {
        background: rgba(100, 116, 139, 0.15);
        color: #475569;
        border: 1px solid rgba(100, 116, 139, 0.3);
    }

    .solicitante-cell-glass {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--glass-text-main);
    }

    .solicitante-cell-glass i {
        color: var(--glass-text-muted);
    }

    .fecha-cell-glass {
        color: var(--glass-text-muted);
        font-size: 0.8rem;
    }

    .urgency-badge-glass {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .urgency-critical {
        background: rgba(239, 68, 68, 0.15);
        color: #dc2626;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .urgency-high {
        background: rgba(245, 158, 11, 0.15);
        color: #d97706;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .urgency-medium {
        background: rgba(6, 182, 212, 0.15);
        color: #0891b2;
        border: 1px solid rgba(6, 182, 212, 0.3);
    }

    .urgency-low {
        background: rgba(100, 116, 139, 0.15);
        color: #64748b;
        border: 1px solid rgba(100, 116, 139, 0.3);
    }

    .status-badge-glass {
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

    .status-dot-glass {
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

    .status-approved .status-dot-glass {
        background: #047857;
        box-shadow: 0 0 8px rgba(4, 120, 87, 0.6);
    }

    .status-pending {
        background: rgba(245, 158, 11, 0.15);
        color: #d97706;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .status-pending .status-dot-glass {
        background: #d97706;
        box-shadow: 0 0 8px rgba(217, 119, 6, 0.6);
    }

    .status-rejected {
        background: rgba(239, 68, 68, 0.15);
        color: #b91c1c;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .status-rejected .status-dot-glass {
        background: #b91c1c;
        box-shadow: 0 0 8px rgba(185, 28, 28, 0.6);
    }

    .status-default {
        background: rgba(100, 116, 139, 0.15);
        color: #64748b;
        border: 1px solid rgba(100, 116, 139, 0.3);
    }

    .actions-group-glass {
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
    }

    .btn-action-table-glass {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.5rem 0.875rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-action-table-glass.primary {
        background: var(--glass-primary);
        color: white;
    }

    .btn-action-table-glass.primary:hover {
        background: var(--glass-primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        color: white;
    }

    .btn-action-table-glass.danger {
        background: transparent;
        color: var(--glass-danger);
        border: 2px solid var(--glass-border);
        padding: 0.5rem 0.75rem;
    }

    .btn-action-table-glass.danger:hover {
        background: var(--glass-danger);
        color: white;
        border-color: var(--glass-danger);
        transform: translateY(-2px);
    }

    .empty-state-table-glass {
        text-align: center;
        padding: 5rem 2rem !important;
    }

    .empty-content-glass {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }

    .empty-content-glass i {
        font-size: 4rem;
        color: var(--glass-text-light);
        opacity: 0.3;
    }

    .empty-content-glass p {
        color: var(--glass-text-muted);
        font-size: 1rem;
        margin: 0;
    }

    /* ==========================================
   PAGINATION GLASS
   ========================================== */
    .pagination-container-glass {
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

    .pagination-info-glass {
        font-size: 0.875rem;
        color: var(--glass-text-muted);
    }

    .pagination-info-glass strong {
        color: var(--glass-text-main);
        font-weight: 700;
    }

    .pagination-glass {
        display: flex;
        gap: 0.5rem;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .page-item-glass {
        list-style: none;
    }

    .page-link-glass {
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

    .page-link-glass:hover {
        background: var(--glass-primary-light);
        color: var(--glass-primary);
        border-color: var(--glass-primary);
        transform: translateY(-2px);
    }

    .page-item-glass.active .page-link-glass {
        background: var(--glass-primary);
        color: white;
        border-color: var(--glass-primary);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .page-item-glass.disabled .page-link-glass {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }

    /* ==========================================
   RESPONSIVE
   ========================================== */
    @media (max-width: 992px) {
        .solicitudes-page {
            padding: 1rem;
        }

        .stats-grid-solicitudes {
            grid-template-columns: repeat(2, 1fr);
        }

        .filters-form-glass {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-group-glass {
            width: 100%;
        }

        .filter-actions-glass {
            width: 100%;
            justify-content: stretch;
        }

        .btn-filter-glass {
            flex: 1;
        }

        .table-wrapper-glass {
            overflow-x: scroll;
        }

        .pagination-container-glass {
            flex-direction: column;
            text-align: center;
        }

        .pagination-glass {
            justify-content: center;
        }
    }

    @media (max-width: 640px) {
        .stats-grid-solicitudes {
            grid-template-columns: 1fr;
        }

        .hero-title-solicitudes {
            font-size: 1.5rem;
        }

        .actions-group-glass {
            flex-direction: column;
            width: 100%;
        }

        .btn-action-table-glass {
            width: 100%;
            justify-content: center;
        }
    }
</style>

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