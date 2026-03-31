<?php
// app/views/proveedores/ver.php

$pagina_actual = 'proveedores';
$titulo = 'Detalles del Proveedor';
require_once VIEWS_PATH . 'layouts/header.php';

// HELPER: Evita errores de "Undefined array key"
$getVal = function ($row, $key) {
    if (!is_array($row))
        return null;
    return $row[$key] ?? $row[strtolower($key)] ?? $row[ucfirst($key)] ?? null;
};

// Variables principales
$id = $getVal($proveedor, 'Id');
$rfc = $getVal($proveedor, 'RFC');
$razonSocial = $getVal($proveedor, 'RazonSocial');
$idManual = $getVal($proveedor, 'IdManual');
$estatus = strtoupper($getVal($proveedor, 'Estatus') ?? 'PENDIENTE');
$tipoPersona = strtoupper($getVal($proveedor, 'TipoPersona') ?? '');

// Lógica de nombre para Personas Físicas
$nombre = $getVal($proveedor, 'Nombre');
$apellidoPaterno = $getVal($proveedor, 'ApellidoPaterno');
$apellidoMaterno = $getVal($proveedor, 'ApellidoMaterno');
$nombreCompleto = trim(($nombre ?? '') . ' ' . ($apellidoPaterno ?? '') . ' ' . ($apellidoMaterno ?? ''));
$displayName = ($tipoPersona === 'FISICA' && !empty($nombreCompleto)) ? $nombreCompleto : $razonSocial;
if (empty($displayName))
    $displayName = $nombreCompleto ?: $razonSocial ?: 'SIN NOMBRE';
?>



<div class="proveedor-detail-page">
    <!-- Hero Header Glass -->
    <div class="hero-header-proveedor-glass">
        <div class="hero-content-proveedor">
            <div class="hero-left-proveedor">
                <a href="<?= BASE_URL ?>proveedores" class="btn-back-proveedor-glass">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="provider-avatar-glass">
                    <i class="bi bi-<?= ($tipoPersona === 'MORAL') ? 'building' : 'person-badge' ?>"></i>
                </div>
                <div class="hero-info-proveedor">
                    <div class="hero-badges-row">
                        <?php
                        $badge_class = match ($estatus) {
                            'APROBADO', 'ACTIVO' => 'status-active',
                            'PENDIENTE' => 'status-pending',
                            'RECHAZADO', 'INACTIVO' => 'status-inactive',
                            default => 'status-default'
                        };
                        ?>
                        <span class="status-badge-hero <?= $badge_class ?>">
                            <span class="status-dot-hero"></span>
                            <?= e($estatus) ?>
                        </span>
                        <span class="type-badge-hero">
                            <?= ($tipoPersona === 'MORAL') ? '<i class="bi bi-building"></i> Moral' : '<i class="bi bi-person"></i> Física' ?>
                        </span>
                    </div>
                    <h1 class="hero-title-proveedor"><?= e($displayName) ?></h1>
                    <div class="hero-meta-proveedor">
                        <span class="meta-item-glass">
                            <i class="bi bi-upc-scan"></i>
                            <strong>RFC:</strong> <?= e($rfc) ?>
                        </span>
                        <?php if (!empty($idManual)): ?>
                            <span class="meta-divider">•</span>
                            <span class="meta-item-glass">
                                <i class="bi bi-hash"></i>
                                <strong>ID:</strong> <?= e($idManual) ?>
                            </span>
                        <?php endif; ?>
                        <span class="meta-divider">•</span>
                        <span class="meta-item-glass">
                            <i class="bi bi-calendar-check"></i>
                            <?= formatoFecha($getVal($proveedor, 'FechaRegistro') ?? date('Y-m-d')) ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="hero-actions-proveedor">
                <?php if (puedeEditar('proveedores')): ?>
                    <a href="<?= BASE_URL ?>proveedores/editar/<?= $id ?>" class="btn-hero-action-glass primary">
                        <i class="bi bi-pencil-fill"></i>
                        <span>Editar</span>
                    </a>
                <?php endif; ?>
                <?php if (!esAdmin() && tienePermiso('proveedores.solicitar_cambio')): ?>
                    <a href="<?= BASE_URL ?>proveedores/solicitarCambio/<?= $id ?>" class="btn-hero-action-glass warning">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>Solicitar Cambio</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Alert Solicitud Pendiente -->
    <?php if (!empty($solicitudPendiente)): ?>
        <div class="alert-glass warning">
            <div class="alert-icon-glass">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <div class="alert-content-glass">
                <h4>¡Atención! Hay una solicitud pendiente</h4>
                <p>
                    <strong>Tipo:</strong> <?= e($solicitudPendiente['TipoCambio']) ?>
                    <span class="mx-2">•</span>
                    <strong>Fecha:</strong> <?= formatoFecha($solicitudPendiente['FechaSolicitud']) ?>
                </p>
            </div>
            <?php if (esAdmin() || tienePermiso('solicitudes.aprobar')): ?>
                <a href="<?= BASE_URL ?>solicitudes/revisar/<?= $solicitudPendiente['Id'] ?>" class="btn-alert-action-glass">
                    <i class="bi bi-eye-fill"></i> Revisar
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Main Content Grid -->
    <div class="content-grid-proveedor">

        <!-- Left Column -->
        <div class="left-column-proveedor">

            <!-- Datos Generales -->
            <div class="info-card-glass">
                <div class="card-header-detail-glass">
                    <div class="card-icon-detail-glass primary">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <h3>Datos Generales</h3>
                </div>
                <div class="card-body-detail-glass">
                    <div class="info-grid-glass">
                        <div class="info-item-glass">
                            <label>RFC</label>
                            <div class="info-value-glass highlight"><?= e($rfc) ?></div>
                        </div>

                        <div class="info-item-glass">
                            <label><?= ($tipoPersona === 'FISICA') ? 'Nombre Completo' : 'Razón Social' ?></label>
                            <div class="info-value-glass"><?= e($displayName) ?></div>
                        </div>

                        <div class="info-item-glass">
                            <label>Nombre Comercial</label>
                            <div class="info-value-glass primary">
                                <?= e($getVal($proveedor, 'NombreComercial') ?: 'NO ESPECIFICADO') ?>
                            </div>
                        </div>

                        <div class="info-item-glass">
                            <label>Tipo de Proveedor</label>
                            <div class="info-value-glass">
                                <span class="mini-badge-glass info">
                                    <?= e($getVal($proveedor, 'TipoProveedor') ?? 'NO DEFINIDO') ?>
                                </span>
                            </div>
                        </div>

                        <div class="info-item-glass full">
                            <label>Régimen Fiscal</label>
                            <div class="info-value-glass">
                                <?php
                                $regNom = $getVal($proveedor, 'regimen_nombre') ?? $getVal($proveedor, 'RegimenFiscal') ?? null;
                                if ($regNom === $razonSocial || $regNom === $displayName) {
                                    echo '<span class="text-muted-glass italic">Pendiente de actualizar</span>';
                                } else {
                                    echo e($regNom ?: 'No especificado');
                                }
                                ?>
                            </div>
                        </div>

                        <?php if ($limite = $getVal($proveedor, 'LimiteCredito')): ?>
                            <div class="info-item-glass">
                                <label>Límite de Crédito</label>
                                <div class="info-value-glass success">
                                    $ <?= number_format((float) $limite, 2) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Dirección Fiscal -->
            <div class="info-card-glass">
                <div class="card-header-detail-glass">
                    <div class="card-icon-detail-glass location">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <h3>Dirección Fiscal</h3>
                </div>
                <div class="card-body-detail-glass">
                    <div class="address-text-glass">
                        <div class="address-line-glass">
                            <i class="bi bi-signpost-2"></i>
                            <span>
                                <strong>Calle:</strong> <?= e($getVal($proveedor, 'Calle') ?? 'Sin calle') ?>
                                <?php if ($ext = $getVal($proveedor, 'NumeroExterior')): ?>
                                    #<?= e($ext) ?>
                                <?php endif; ?>
                                <?php if ($int = $getVal($proveedor, 'NumeroInterior')): ?>
                                    Int. <?= e($int) ?>
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="address-line-glass">
                            <i class="bi bi-geo"></i>
                            <span><strong>Colonia:</strong>
                                <?= e($getVal($proveedor, 'Colonia') ?? 'Sin colonia') ?></span>
                        </div>
                        <div class="address-line-glass">
                            <i class="bi bi-mailbox"></i>
                            <span><strong>C.P.:</strong> <?= e($getVal($proveedor, 'CP') ?? '00000') ?></span>
                        </div>
                        <div class="address-line-glass">
                            <i class="bi bi-pin-map-fill"></i>
                            <span>
                                <strong>Ubicación:</strong>
                                <?= e($getVal($proveedor, 'Municipio') ?? '') ?>,
                                <?= e($getVal($proveedor, 'Estado') ?? '') ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column -->
        <div class="right-column-proveedor">

            <!-- Contacto -->
            <div class="info-card-glass">
                <div class="card-header-detail-glass">
                    <div class="card-icon-detail-glass contact">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <h3>Información de Contacto</h3>
                </div>
                <div class="card-body-detail-glass">
                    <div class="contact-grid-glass">
                        <div class="contact-item-glass">
                            <div class="contact-icon-glass">
                                <i class="bi bi-person-circle"></i>
                            </div>
                            <div class="contact-info-glass">
                                <label>Responsable</label>
                                <strong><?= e($getVal($proveedor, 'Responsable') ?? 'No especificado') ?></strong>
                            </div>
                        </div>

                        <div class="contact-item-glass">
                            <div class="contact-icon-glass">
                                <i class="bi bi-envelope-at"></i>
                            </div>
                            <div class="contact-info-glass">
                                <label>Email Interno</label>
                                <?php if ($mailI = $getVal($proveedor, 'CorreoPagosInterno')): ?>
                                    <a href="mailto:<?= e($mailI) ?>" class="contact-link-glass">
                                        <?= e($mailI) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted-glass">No especificado</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="contact-item-glass">
                            <div class="contact-icon-glass">
                                <i class="bi bi-envelope-check"></i>
                            </div>
                            <div class="contact-info-glass">
                                <label>Email Proveedor</label>
                                <?php if ($mailP = $getVal($proveedor, 'CorreoProveedor')): ?>
                                    <a href="mailto:<?= e($mailP) ?>" class="contact-link-glass">
                                        <?= e($mailP) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted-glass">No especificado</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Compañías Asignadas -->
            <div class="info-card-glass">
                <div class="card-header-detail-glass">
                    <div class="card-icon-detail-glass companies">
                        <i class="bi bi-building-gear"></i>
                    </div>
                    <h3>Compañías Asignadas</h3>
                    <a href="<?= BASE_URL ?>datos-bancarios/index/<?= $id ?>" class="btn-card-action-glass">
                        <i class="bi bi-bank"></i> Gestionar
                    </a>
                </div>
                <div class="card-body-detail-glass">
                    <?php if (!empty($cias_asignadas)): ?>
                        <div class="companies-grid-glass">
                            <?php foreach ($cias_asignadas as $cia): ?>
                                <div class="company-chip-glass">
                                    <i class="bi bi-building-fill"></i>
                                    <span><?= e($getVal($cia, 'Nombre')) ?></span>
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="info-note-glass">
                            <i class="bi bi-info-circle"></i>
                            <span>Para ver las cuentas bancarias de cada compañía, haga clic en "Gestionar".</span>
                        </div>
                    <?php else: ?>
                        <div class="empty-state-small-glass">
                            <i class="bi bi-building-slash"></i>
                            <p>Sin compañías asignadas</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Documentación -->
            <div class="info-card-glass">
                <div class="card-header-detail-glass">
                    <div class="card-icon-detail-glass docs">
                        <i class="bi bi-file-earmark-pdf-fill"></i>
                    </div>
                    <h3>Documentación Fiscal</h3>
                </div>
                <div class="card-body-detail-glass">
                    <div class="docs-grid-glass">
                        <!-- CSF -->
                        <div class="doc-item-glass">
                            <div class="doc-header-glass">
                                <div class="doc-icon-glass">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>
                                <div class="doc-info-glass">
                                    <strong>Constancia Fiscal (CSF)</strong>
                                    <?php if ($getVal($proveedor, 'RutaConstancia')): ?>
                                        <span class="doc-status-glass success">
                                            <i class="bi bi-check-circle-fill"></i> Cargado
                                        </span>
                                    <?php else: ?>
                                        <span class="doc-status-glass danger">
                                            <i class="bi bi-x-circle-fill"></i> Pendiente
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if ($ruta = $getVal($proveedor, 'RutaConstancia')):
                                $ext = strtolower(pathinfo($ruta, PATHINFO_EXTENSION));
                                $isImg = in_array($ext, ['jpg', 'jpeg', 'png']);
                                $url = BASE_URL . "proveedores/verArchivo/csf/" . $id . ($isImg ? "?isImage=1" : "");
                                ?>
                                <button type="button"
                                    onclick="verDocumento('<?= $url ?>', 'Constancia de Situación Fiscal')"
                                    class="btn-doc-glass view">
                                    <i class="bi bi-eye"></i> Ver Documento
                                </button>
                            <?php else: ?>
                                <button class="btn-doc-glass disabled" disabled>
                                    <i class="bi bi-file-earmark-x"></i> No disponible
                                </button>
                            <?php endif; ?>
                        </div>

                        <!-- Carátulas Bancarias -->
                        <div class="doc-item-glass secondary">
                            <div class="doc-header-glass">
                                <div class="doc-icon-glass">
                                    <i class="bi bi-bank"></i>
                                </div>
                                <div class="doc-info-glass">
                                    <strong>Carátulas Bancarias</strong>
                                    <span class="doc-note-glass">Asociadas a cuentas</span>
                                </div>
                            </div>
                            <a href="<?= BASE_URL ?>datos-bancarios/index/<?= $id ?>" class="btn-doc-glass link">
                                <i class="bi bi-arrow-right-circle"></i> Ver Documentos
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* ==========================================
   GLASSMORPHISM THEME - PROVEEDOR DETAIL
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
        --glass-bg: rgba(30, 41, 59, 0.5);
        --glass-bg-card: rgba(30, 41, 59, 0.65);
        --glass-bg-input: rgba(15, 23, 42, 0.55);
        --glass-border: rgba(59, 130, 246, 0.35);
        --glass-text-main: rgba(255, 255, 255, 0.95);
        --glass-text-muted: rgba(255, 255, 255, 0.65);
        --glass-text-light: rgba(255, 255, 255, 0.45);
        --glass-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.5);
        --glass-shadow-hover: 0 12px 40px 0 rgba(0, 0, 0, 0.6);
    }

    /* ==========================================
   LAYOUT
   ========================================== */
    .proveedor-detail-page {
        padding: 2rem;
        max-width: 1400px;
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
    .hero-header-proveedor-glass {
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

    .hero-content-proveedor {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 2rem;
    }

    .hero-left-proveedor {
        display: flex;
        align-items: flex-start;
        gap: 1.5rem;
        flex: 1;
    }

    .btn-back-proveedor-glass {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: var(--glass-bg-input);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--glass-text-main);
        text-decoration: none;
        font-size: 1.25rem;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .btn-back-proveedor-glass:hover {
        background: var(--glass-primary-light);
        transform: translateX(-5px);
        color: var(--glass-text-main);
    }

    .provider-avatar-glass {
        width: 80px;
        height: 80px;
        border-radius: 16px;
        background: linear-gradient(135deg, var(--glass-primary) 0%, #8b5cf6 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: white;
        flex-shrink: 0;
        box-shadow: 0 8px 24px rgba(59, 130, 246, 0.4);
    }

    .hero-info-proveedor {
        flex: 1;
    }

    .hero-badges-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }

    .status-badge-hero {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.4rem 0.85rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .status-dot-hero {
        width: 8px;
        height: 8px;
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

    .status-active {
        background: rgba(16, 185, 129, 0.15);
        color: #047857;
        border: 2px solid rgba(16, 185, 129, 0.3);
    }

    .status-active .status-dot-hero {
        background: #047857;
        box-shadow: 0 0 8px rgba(4, 120, 87, 0.6);
    }

    .status-pending {
        background: rgba(245, 158, 11, 0.15);
        color: #d97706;
        border: 2px solid rgba(245, 158, 11, 0.3);
    }

    .status-pending .status-dot-hero {
        background: #d97706;
        box-shadow: 0 0 8px rgba(217, 119, 6, 0.6);
    }

    .status-inactive {
        background: rgba(239, 68, 68, 0.15);
        color: #b91c1c;
        border: 2px solid rgba(239, 68, 68, 0.3);
    }

    .status-inactive .status-dot-hero {
        background: #b91c1c;
        box-shadow: 0 0 8px rgba(185, 28, 28, 0.6);
    }

    .status-default {
        background: rgba(100, 116, 139, 0.15);
        color: #64748b;
        border: 2px solid rgba(100, 116, 139, 0.3);
    }

    .type-badge-hero {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.4rem 0.85rem;
        background: var(--glass-primary-light);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        color: var(--glass-primary);
        border: 2px solid rgba(59, 130, 246, 0.3);
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .hero-title-proveedor {
        font-size: 2rem;
        font-weight: 800;
        color: var(--glass-text-main);
        margin: 0 0 0.75rem 0;
        line-height: 1.2;
    }

    .hero-meta-proveedor {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
        color: var(--glass-text-muted);
        font-size: 0.9rem;
    }

    .meta-item-glass {
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .meta-divider {
        color: var(--glass-text-light);
    }

    .hero-actions-proveedor {
        display: flex;
        gap: 0.75rem;
    }

    .btn-hero-action-glass {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        white-space: nowrap;
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

    .btn-hero-action-glass.warning {
        background: var(--glass-warning);
        color: white;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .btn-hero-action-glass.warning:hover {
        background: #d97706;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(245, 158, 11, 0.4);
        color: white;
    }

    /* ==========================================
   ALERT GLASS
   ========================================== */
    .alert-glass {
        background: var(--glass-bg-card);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        border-left: 4px solid var(--glass-warning);
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        box-shadow: var(--glass-shadow);
        animation: slideUp 0.6s ease-out 0.1s both;
    }

    .alert-glass.warning {
        border-left-color: var(--glass-warning);
    }

    .alert-icon-glass {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: rgba(245, 158, 11, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: var(--glass-warning);
        flex-shrink: 0;
    }

    .alert-content-glass {
        flex: 1;
    }

    .alert-content-glass h4 {
        font-size: 1rem;
        font-weight: 700;
        color: var(--glass-text-main);
        margin: 0 0 0.35rem 0;
    }

    .alert-content-glass p {
        font-size: 0.875rem;
        color: var(--glass-text-muted);
        margin: 0;
    }

    .btn-alert-action-glass {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: var(--glass-warning);
        color: white;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-alert-action-glass:hover {
        background: #d97706;
        transform: translateY(-2px);
        color: white;
    }

    /* ==========================================
   CONTENT GRID
   ========================================== */
    .content-grid-proveedor {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        animation: slideUp 0.6s ease-out 0.2s both;
    }

    .left-column-proveedor,
    .right-column-proveedor {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    /* ==========================================
   INFO CARDS GLASS
   ========================================== */
    .info-card-glass {
        background: var(--glass-bg-card);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        border-radius: var(--glass-radius);
        overflow: hidden;
        box-shadow: var(--glass-shadow);
        transition: all 0.3s ease;
    }

    .info-card-glass:hover {
        transform: translateY(-4px);
        box-shadow: var(--glass-shadow-hover);
    }

    .card-header-detail-glass {
        padding: 1.25rem;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.08) 0%, rgba(139, 92, 246, 0.08) 100%);
        border-bottom: 2px solid var(--glass-border);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .card-icon-detail-glass {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
        flex-shrink: 0;
    }

    .card-icon-detail-glass.primary {
        background: linear-gradient(135deg, var(--glass-primary) 0%, #8b5cf6 100%);
    }

    .card-icon-detail-glass.location {
        background: linear-gradient(135deg, #ef4444 0%, #f59e0b 100%);
    }

    .card-icon-detail-glass.contact {
        background: linear-gradient(135deg, #10b981 0%, #06b6d4 100%);
    }

    .card-icon-detail-glass.companies {
        background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%);
    }

    .card-icon-detail-glass.docs {
        background: linear-gradient(135deg, #f59e0b 0%, #eab308 100%);
    }

    .card-header-detail-glass h3 {
        font-size: 1rem;
        font-weight: 700;
        color: var(--glass-text-main);
        margin: 0;
        flex: 1;
    }

    .btn-card-action-glass {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.5rem 0.875rem;
        background: var(--glass-primary);
        color: white;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-card-action-glass:hover {
        background: var(--glass-primary-dark);
        transform: translateY(-2px);
        color: white;
    }

    .card-body-detail-glass {
        padding: 1.25rem;
    }

    /* ==========================================
   INFO ITEMS
   ========================================== */
    .info-grid-glass {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }

    .info-item-glass {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .info-item-glass.full {
        grid-column: 1 / -1;
    }

    .info-item-glass label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--glass-text-light);
        letter-spacing: 0.3px;
    }

    .info-value-glass {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--glass-text-main);
    }

    .info-value-glass.highlight {
        font-family: 'Courier New', monospace;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: var(--glass-primary);
    }

    .info-value-glass.primary {
        color: var(--glass-primary);
        font-weight: 700;
    }

    .info-value-glass.success {
        color: var(--glass-success);
        font-weight: 700;
    }

    .text-muted-glass {
        color: var(--glass-text-muted);
        font-style: italic;
    }

    .mini-badge-glass {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .mini-badge-glass.info {
        background: rgba(6, 182, 212, 0.15);
        color: #0891b2;
        border: 1px solid rgba(6, 182, 212, 0.3);
    }

    /* ==========================================
   ADDRESS
   ========================================== */
    .address-text-glass {
        display: flex;
        flex-direction: column;
        gap: 0.875rem;
    }

    .address-line-glass {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        font-size: 0.9rem;
        line-height: 1.6;
    }

    .address-line-glass i {
        color: var(--glass-primary);
        font-size: 1.1rem;
        margin-top: 0.1rem;
        flex-shrink: 0;
    }

    /* ==========================================
   CONTACT
   ========================================== */
    .contact-grid-glass {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .contact-item-glass {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem;
        background: var(--glass-bg-input);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        border-radius: 10px;
    }

    .contact-icon-glass {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--glass-primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--glass-primary);
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .contact-info-glass {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .contact-info-glass label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--glass-text-light);
        letter-spacing: 0.3px;
    }

    .contact-info-glass strong {
        font-size: 0.95rem;
        color: var(--glass-text-main);
    }

    .contact-link-glass {
        font-size: 0.9rem;
        color: var(--glass-primary);
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .contact-link-glass:hover {
        color: var(--glass-primary-dark);
        text-decoration: underline;
    }

    /* ==========================================
   COMPANIES
   ========================================== */
    .companies-grid-glass {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .company-chip-glass {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 1rem;
        background: var(--glass-bg-input);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--glass-text-main);
        transition: all 0.3s ease;
    }

    .company-chip-glass:hover {
        background: var(--glass-primary-light);
        border-color: var(--glass-primary);
        transform: translateY(-2px);
    }

    .company-chip-glass i:first-child {
        color: var(--glass-primary);
        margin-right: 0.5rem;
    }

    .company-chip-glass i:last-child {
        color: var(--glass-success);
    }

    .info-note-glass {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        background: rgba(59, 130, 246, 0.05);
        border-radius: 8px;
        font-size: 0.8rem;
        color: var(--glass-text-muted);
    }

    .info-note-glass i {
        color: var(--glass-primary);
        flex-shrink: 0;
        margin-top: 0.1rem;
    }

    .empty-state-small-glass {
        text-align: center;
        padding: 2rem;
        color: var(--glass-text-light);
    }

    .empty-state-small-glass i {
        font-size: 2.5rem;
        display: block;
        margin-bottom: 0.75rem;
        opacity: 0.4;
    }

    .empty-state-small-glass p {
        margin: 0;
        font-size: 0.9rem;
    }

    /* ==========================================
   DOCUMENTS
   ========================================== */
    .docs-grid-glass {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .doc-item-glass {
        padding: 1.25rem;
        background: var(--glass-bg-input);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .doc-item-glass.secondary {
        opacity: 0.85;
    }

    .doc-header-glass {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .doc-icon-glass {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: var(--glass-primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--glass-primary);
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .doc-info-glass {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }

    .doc-info-glass strong {
        font-size: 0.95rem;
        color: var(--glass-text-main);
    }

    .doc-status-glass {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .doc-status-glass.success {
        color: var(--glass-success);
    }

    .doc-status-glass.danger {
        color: var(--glass-danger);
    }

    .doc-note-glass {
        font-size: 0.75rem;
        color: var(--glass-text-muted);
    }

    .btn-doc-glass {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-doc-glass.view {
        background: var(--glass-primary);
        color: white;
    }

    .btn-doc-glass.view:hover {
        background: var(--glass-primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }

    .btn-doc-glass.link {
        background: var(--glass-bg-card);
        color: var(--glass-text-main);
        border: 2px solid var(--glass-border);
    }

    .btn-doc-glass.link:hover {
        background: var(--glass-primary-light);
        border-color: var(--glass-primary);
        color: var(--glass-primary);
        transform: translateY(-2px);
    }

    .btn-doc-glass.disabled {
        background: var(--glass-bg);
        color: var(--glass-text-light);
        border: 2px solid var(--glass-border);
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* ==========================================
   RESPONSIVE
   ========================================== */
    @media (max-width: 1024px) {
        .content-grid-proveedor {
            grid-template-columns: 1fr;
        }

        .info-grid-glass {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .proveedor-detail-page {
            padding: 1rem;
        }

        .hero-content-proveedor {
            flex-direction: column;
        }

        .hero-left-proveedor {
            width: 100%;
        }

        .hero-actions-proveedor {
            width: 100%;
            flex-direction: column;
        }

        .btn-hero-action-glass {
            width: 100%;
            justify-content: center;
        }

        .provider-avatar-glass {
            width: 64px;
            height: 64px;
            font-size: 2rem;
        }

        .hero-title-proveedor {
            font-size: 1.5rem;
        }

        .alert-glass {
            flex-direction: column;
            text-align: center;
        }

        .companies-grid-glass {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    // Se utiliza la función global verDocumento definida en app.js
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>