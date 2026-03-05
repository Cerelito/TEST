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

<script>
    // Se utiliza la función global verDocumento definida en app.js
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>