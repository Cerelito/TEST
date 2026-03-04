<?php
$pagina_actual = 'solicitudes';
$titulo = 'Revisar Solicitud';
require_once VIEWS_PATH . 'layouts/header.php';

// Asegurar que tenemos el catálogo de bancos disponible
if (!isset($bancos)) {
    $bancos = (new Catalogo())->getBancos(true);
}

// Analizar datos de la solicitud
$datosSolicitados = $solicitud['DatosSolicitados'] ?? [];
$tipoCambio = $datosSolicitados['TipoCambio'] ?? $solicitud['TipoCambio'] ?? '';
$esCambioBancario = ($tipoCambio === 'Cuentas Bancarias');

// ID Seguro
$solicitudId = $solicitud['Id'] ?? $solicitud['id'];

// Lógica para detectar Persona Física vs Moral
$rfc = $proveedor['RFC'];
$esFisica = (strlen($rfc) === 13) || (isset($proveedor['TipoPersona']) && strtoupper($proveedor['TipoPersona']) === 'FISICA');
?>



<div class="revisar-solicitud-page">

    <!-- Hero Header -->
    <div class="page-hero-revisar">
        <div class="hero-left-revisar">
            <a href="<?= BASE_URL ?>solicitudes" class="btn-back-revisar">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div class="hero-info-revisar">
                <div class="hero-tag-revisar">
                    <i class="bi bi-file-earmark-check"></i>
                    <span>Solicitud #
                        <?= $solicitudId ?>
                    </span>
                </div>
                <h1 class="hero-title-revisar">Revisar Solicitud</h1>
                <p class="hero-subtitle-revisar">
                    <strong>
                        <?= e($proveedor['RazonSocial'] ?: $proveedor['Nombre'] . ' ' . $proveedor['ApellidoPaterno']) ?>
                    </strong>
                </p>
            </div>
        </div>
        <div class="hero-actions-revisar">
            <?php if (esAdmin()): ?>
                <button type="button" class="btn-hero-action-revisar danger" onclick="eliminarSolicitudActual()">
                    <i class="bi bi-trash"></i>
                    <span>Eliminar</span>
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Info Card -->
    <div class="card-revisar">
        <div class="card-header-revisar">
            <div class="card-title-section-revisar">
                <div class="card-icon-revisar">
                    <i class="bi bi-info-circle"></i>
                </div>
                <h2>Información</h2>
            </div>
            <?php
            $estatus_class = match ($solicitud['estatus'] ?? $solicitud['Estatus']) {
                'Aprobada', 'APROBADO' => 'status-approved',
                'Pendiente', 'PENDIENTE' => 'status-pending',
                'Rechazada', 'RECHAZADO' => 'status-rejected',
                default => 'status-default'
            };
            ?>
            <span class="status-badge-revisar <?= $estatus_class ?>">
                <span class="status-dot-revisar"></span>
                <?= e($solicitud['estatus'] ?? $solicitud['Estatus']) ?>
            </span>
        </div>
        <div class="card-body-revisar">
            <div class="info-grid-revisar">
                <div class="info-item-revisar">
                    <label>Tipo de Cambio</label>
                    <div class="info-badge-revisar primary">
                        <?= e($tipoCambio ?: 'No especificado') ?>
                    </div>
                </div>
                <div class="info-item-revisar">
                    <label>Urgencia</label>
                    <div class="info-badge-revisar secondary">
                        <?= e($solicitud['urgencia'] ?? $solicitud['Urgencia'] ?? 'Media') ?>
                    </div>
                </div>
                <div class="info-item-revisar">
                    <label>Solicitado por</label>
                    <div class="info-value-revisar">
                        <?= e($solicitud['solicitante_nombre']) ?>
                    </div>
                </div>
                <div class="info-item-revisar">
                    <label>Fecha</label>
                    <div class="info-value-revisar">
                        <?= formatoFechaHora($solicitud['fechasolicitud'] ?? $solicitud['FechaSolicitud']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Datos Actuales -->
    <div class="card-revisar">
        <div class="card-header-revisar">
            <div class="card-icon-revisar">
                <i class="bi bi-building"></i>
            </div>
            <h2>Datos Actuales del Proveedor</h2>
        </div>
        <div class="card-body-revisar">
            <div class="grid-revisar-3">
                <div class="data-item-revisar">
                    <label>RFC</label>
                    <div class="data-value-revisar mono">
                        <?= e($proveedor['RFC']) ?>
                    </div>
                </div>
                <div class="data-item-revisar">
                    <label>
                        <?= $esFisica ? 'Nombre Completo' : 'Razón Social' ?>
                    </label>
                    <div class="data-value-revisar">
                        <?= e($proveedor['RazonSocial'] ?: $proveedor['Nombre'] . ' ' . $proveedor['ApellidoPaterno'] . ' ' . $proveedor['ApellidoMaterno']) ?>
                    </div>
                </div>
                <div class="data-item-revisar">
                    <label>Tipo Persona</label>
                    <div class="info-badge-revisar info">
                        <?= $esFisica ? 'FÍSICA' : 'MORAL' ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detalles del Cambio -->
    <div class="card-revisar">
        <div class="card-header-revisar">
            <div class="card-icon-revisar primary">
                <i class="bi bi-eye"></i>
            </div>
            <h2>Detalles del Cambio</h2>
        </div>
        <div class="card-body-revisar">

            <?php if ($tipoCambio === 'Datos de Contacto' && ($solicitud['estatus'] ?? $solicitud['Estatus']) === 'PENDIENTE'): ?>

                <div class="alert-revisar info">
                    <i class="bi bi-pencil-square"></i>
                    <span>Verifique los datos propuestos y edite si es necesario antes de aprobar.</span>
                </div>

                <form id="formDatosContacto" class="form-revisar">
                    <div class="grid-revisar-2">
                        <div class="form-group-revisar">
                            <label class="form-label-revisar">Responsable <span class="required-revisar">*</span></label>
                            <input type="text" name="Responsable" id="inputResponsable" class="form-input-revisar"
                                value="<?= e($datosSolicitados['Responsable'] ?? $proveedor['Responsable']) ?>" required>
                            <small class="form-hint-revisar">Actual:
                                <?= e($proveedor['Responsable']) ?>
                            </small>
                        </div>

                        <div class="form-group-revisar">
                            <label class="form-label-revisar">Límite de Crédito</label>
                            <div class="input-group-revisar">
                                <span class="input-prefix-revisar">$</span>
                                <input type="number" name="LimiteCredito" id="inputLimite" class="form-input-revisar"
                                    step="0.01"
                                    value="<?= e($datosSolicitados['LimiteCredito'] ?? $proveedor['LimiteCredito'] ?? 0) ?>">
                            </div>
                            <small class="form-hint-revisar">Actual: $
                                <?= number_format($proveedor['LimiteCredito'] ?? 0, 2) ?>
                            </small>
                        </div>

                        <div class="form-group-revisar">
                            <label class="form-label-revisar">Email Interno <span class="required-revisar">*</span></label>
                            <input type="email" name="CorreoPagosInterno" id="inputEmailPagos" class="form-input-revisar"
                                value="<?= e($datosSolicitados['CorreoPagosInterno'] ?? $proveedor['CorreoPagosInterno']) ?>"
                                required>
                            <small class="form-hint-revisar">Actual:
                                <?= e($proveedor['CorreoPagosInterno']) ?>
                            </small>
                        </div>

                        <div class="form-group-revisar">
                            <label class="form-label-revisar">Nombre Comercial</label>
                            <input type="text" name="NombreComercial" id="inputNombreComercial" class="form-input-revisar"
                                value="<?= e($datosSolicitados['NombreComercial'] ?? $proveedor['NombreComercial']) ?>">
                            <small class="form-hint-revisar">Actual:
                                <?= e($proveedor['NombreComercial']) ?>
                            </small>
                        </div>

                        <div class="form-group-revisar" style="grid-column: 1 / -1;">
                            <label class="form-label-revisar">Email Proveedor <span
                                    class="required-revisar">*</span></label>
                            <input type="email" name="CorreoProveedor" id="inputEmailProv" class="form-input-revisar"
                                value="<?= e($datosSolicitados['CorreoProveedor'] ?? $proveedor['CorreoProveedor']) ?>"
                                required>
                            <small class="form-hint-revisar">Actual:
                                <?= e($proveedor['CorreoProveedor']) ?>
                            </small>
                        </div>
                    </div>

                    <button type="button" class="btn-submit-revisar success" onclick="aprobarDatosContacto()">
                        <i class="bi bi-check-circle-fill"></i>
                        ACTUALIZAR Y APROBAR CONTACTO
                    </button>
                </form>

            <?php elseif ($tipoCambio === 'Datos de Contacto'): ?>

                <div class="comparison-table-revisar">
                    <table class="table-revisar">
                        <thead>
                            <tr>
                                <th>Campo</th>
                                <th>Actual</th>
                                <th>Propuesto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Responsable</td>
                                <td class="text-muted-revisar">
                                    <?= e($proveedor['Responsable']) ?>
                                </td>
                                <td class="text-primary-revisar">
                                    <?= e($datosSolicitados['Responsable'] ?? '-') ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Email Interno</td>
                                <td class="text-muted-revisar">
                                    <?= e($proveedor['CorreoPagosInterno']) ?>
                                </td>
                                <td class="text-primary-revisar">
                                    <?= e($datosSolicitados['CorreoPagosInterno'] ?? '-') ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Nombre Comercial</td>
                                <td class="text-muted-revisar">
                                    <?= e($proveedor['NombreComercial']) ?>
                                </td>
                                <td class="text-primary-revisar">
                                    <?= e($datosSolicitados['NombreComercial'] ?? '-') ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Email Proveedor</td>
                                <td class="text-muted-revisar">
                                    <?= e($proveedor['CorreoProveedor']) ?>
                                </td>
                                <td class="text-primary-revisar">
                                    <?= e($datosSolicitados['CorreoProveedor'] ?? '-') ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Límite Crédito</td>
                                <td class="text-muted-revisar">$
                                    <?= number_format($proveedor['LimiteCredito'] ?? 0, 2) ?>
                                </td>
                                <td class="text-primary-revisar">$
                                    <?= number_format($datosSolicitados['LimiteCredito'] ?? 0, 2) ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            <?php elseif ($tipoCambio === 'Compañías Asignadas'): ?>

                <div class="alert-revisar info">
                    <i class="bi bi-info-circle"></i>
                    <span>Se agregarán las siguientes compañías:</span>
                </div>
                <div class="companies-list-revisar">
                    <?php foreach (($datosSolicitados['Cias'] ?? []) as $ciaId): ?>
                        <span class="company-badge-revisar">CIA ID:
                            <?= e($ciaId) ?>
                        </span>
                    <?php endforeach; ?>
                </div>

            <?php elseif ($tipoCambio === 'ALTA NUEVO PROVEEDOR'): ?>

                <div class="alert-revisar success">
                    <i class="bi bi-person-plus-fill"></i>
                    <div>
                        <strong>Solicitud de Alta:</strong> Este es un nuevo proveedor registrado desde la importación o
                        formulario manual.
                        <br>
                        <small>Al aprobar, el estatus pasará a ACTIVO/APROBADO.</small>
                    </div>
                </div>

                <?php if (!empty($datosSolicitados['NombreComercial'])): ?>
                    <div class="data-highlight-revisar">
                        <label>Nombre Comercial Propuesto</label>
                        <div class="data-value-revisar primary">
                            <?= e($datosSolicitados['NombreComercial']) ?>
                        </div>
                    </div>
                <?php endif; ?>

            <?php elseif ($tipoCambio === 'Datos Generales' && ($solicitud['estatus'] ?? $solicitud['Estatus']) === 'PENDIENTE'): ?>

                <div class="alert-revisar warning">
                    <i class="bi bi-pencil"></i>
                    <span>Revise la CSF y actualice los datos abajo antes de aprobar.</span>
                </div>

                <div class="split-layout-revisar">
                    <form id="formDatosGenerales" class="form-revisar">
                        <h4 class="section-title-revisar">Datos a Actualizar</h4>

                        <input type="hidden" id="tipoPersonaHidden" value="<?= $esFisica ? 'FISICA' : 'MORAL' ?>">

                        <div class="form-group-revisar">
                            <label class="form-label-revisar">RFC <span class="required-revisar">*</span></label>
                            <input type="text" name="RFC" id="inputRFC" class="form-input-revisar mono"
                                value="<?= e($proveedor['RFC']) ?>" required>
                        </div>

                        <?php if ($esFisica): ?>
                            <div class="form-group-revisar">
                                <label class="form-label-revisar">Nombre(s) <span class="required-revisar">*</span></label>
                                <input type="text" name="Nombre" id="inputNombre" class="form-input-revisar"
                                    value="<?= e($proveedor['Nombre']) ?>" required>
                            </div>
                            <div class="grid-revisar-2">
                                <div class="form-group-revisar">
                                    <label class="form-label-revisar">Apellido Paterno <span
                                            class="required-revisar">*</span></label>
                                    <input type="text" name="ApellidoPaterno" id="inputPaterno" class="form-input-revisar"
                                        value="<?= e($proveedor['ApellidoPaterno']) ?>" required>
                                </div>
                                <div class="form-group-revisar">
                                    <label class="form-label-revisar">Apellido Materno</label>
                                    <input type="text" name="ApellidoMaterno" id="inputMaterno" class="form-input-revisar"
                                        value="<?= e($proveedor['ApellidoMaterno']) ?>">
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="form-group-revisar">
                                <label class="form-label-revisar">Razón Social <span class="required-revisar">*</span></label>
                                <input type="text" name="RazonSocial" id="inputRazonSocial" class="form-input-revisar"
                                    value="<?= e($proveedor['RazonSocial']) ?>" required>
                                <small class="form-hint-revisar">Tal cual aparece en la CSF</small>
                            </div>
                        <?php endif; ?>

                        <div class="form-group-revisar">
                            <label class="form-label-revisar">Régimen Fiscal <span class="required-revisar">*</span></label>
                            <select name="RegimenFiscalId" id="inputRegimen" class="form-select-revisar" required>
                                <option value="">Seleccione...</option>
                                <?php foreach ($regimenes ?? [] as $reg): ?>
                                    <option value="<?= $reg['Id'] ?>" <?= ($datosSolicitados['RegimenFiscalId'] ?? $proveedor['RegimenFiscalId']) == $reg['Id'] ? 'selected' : '' ?>>
                                        <?= e($reg['Descripcion']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="button" class="btn-submit-revisar success" onclick="aprobarDatosGenerales()">
                            <i class="bi bi-check-circle-fill"></i>
                            ACTUALIZAR Y APROBAR
                        </button>
                    </form>

                    <?php if (!empty($solicitud['RutaConstanciaNueva'])): ?>
                        <div class="viewer-revisar">
                            <div class="viewer-header-revisar">
                                <span>Nueva Documentación</span>
                                <?php
                                $extConst = strtolower(pathinfo($solicitud['RutaConstanciaNueva'], PATHINFO_EXTENSION));
                                $isImgConst = in_array($extConst, ['jpg', 'jpeg', 'png']);
                                $urlConst = BASE_URL . "solicitudes/verArchivo/constancia/" . $solicitudId . ($isImgConst ? "?isImage=1" : "");
                                ?>
                                <button onclick="verDocumento('<?= $urlConst ?>', 'Nueva CSF')" class="btn-expand-revisar">
                                    <i class="bi bi-arrows-fullscreen"></i>
                                </button>
                            </div>
                            <div class="viewer-content-revisar">
                                <?php
                                $ext = strtolower(pathinfo($solicitud['RutaConstanciaNueva'], PATHINFO_EXTENSION));
                                $url = BASE_URL . "solicitudes/verArchivo/constancia/" . $solicitudId;
                                if (in_array($ext, ['jpg', 'jpeg', 'png'])): ?>
                                    <img src="<?= $url ?>" class="viewer-image-revisar" alt="CSF">
                                <?php else: ?>
                                    <iframe src="<?= $url ?>#toolbar=0" class="viewer-iframe-revisar"></iframe>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

            <?php elseif ($esCambioBancario && ($solicitud['estatus'] ?? $solicitud['Estatus']) === 'PENDIENTE'): ?>

                <div class="alert-revisar info">
                    <i class="bi bi-bank2"></i>
                    <span>Capture los datos bancarios de la nueva cuenta.</span>
                </div>

                <div class="split-layout-revisar">
                    <form method="POST" action="<?= BASE_URL ?>solicitudes/aprobar/<?= $solicitudId ?>"
                        id="formAprobarBancario" class="form-revisar">
                        <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">
                        <input type="hidden" name="es_cambio_bancario" value="1">

                        <h4 class="section-title-revisar">Captura de Datos</h4>

                        <div class="form-group-revisar">
                            <label class="form-label-revisar">Banco <span class="required-revisar">*</span></label>
                            <select name="BancoId" class="form-select-revisar" required>
                                <option value="">Seleccione...</option>
                                <?php foreach ($bancos as $banco): ?>
                                    <option value="<?= $banco['Id'] ?>">
                                        <?= e($banco['Nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group-revisar">
                            <label class="form-label-revisar">Cuenta</label>
                            <input type="text" name="Cuenta" class="form-input-revisar" maxlength="30">
                        </div>

                        <div class="form-group-revisar">
                            <label class="form-label-revisar">CLABE</label>
                            <input type="text" name="Clabe" class="form-input-revisar mono" maxlength="18">
                        </div>

                        <div class="grid-revisar-2">
                            <div class="form-group-revisar">
                                <label class="form-label-revisar">Sucursal</label>
                                <input type="text" name="Sucursal" class="form-input-revisar" maxlength="100">
                            </div>
                            <div class="form-group-revisar">
                                <label class="form-label-revisar">Plaza</label>
                                <input type="text" name="Plaza" class="form-input-revisar" maxlength="100">
                            </div>
                        </div>

                        <button type="submit" class="btn-submit-revisar success">
                            <i class="bi bi-check-circle-fill"></i>
                            Guardar y Aprobar
                        </button>
                    </form>

                    <?php if (!empty($solicitud['RutaCaratulaNueva'])): ?>
                        <div class="viewer-revisar">
                            <div class="viewer-header-revisar">
                                <span>Carátula Bancaria</span>
                                <?php
                                $extCar = strtolower(pathinfo($solicitud['RutaCaratulaNueva'], PATHINFO_EXTENSION));
                                $isImgCar = in_array($extCar, ['jpg', 'jpeg', 'png']);
                                $urlCar = BASE_URL . "solicitudes/verArchivo/caratula/" . $solicitudId . ($isImgCar ? "?isImage=1" : "");
                                ?>
                                <button onclick="verDocumento('<?= $urlCar ?>', 'Carátula')" class="btn-expand-revisar">
                                    <i class="bi bi-arrows-fullscreen"></i>
                                </button>
                            </div>
                            <div class="viewer-content-revisar">
                                <?php
                                $ext = strtolower(pathinfo($solicitud['RutaCaratulaNueva'], PATHINFO_EXTENSION));
                                $url = BASE_URL . "solicitudes/verArchivo/caratula/" . $solicitudId;
                                if (in_array($ext, ['jpg', 'jpeg', 'png'])): ?>
                                    <img src="<?= $url ?>" class="viewer-image-revisar" alt="Carátula">
                                <?php else: ?>
                                    <iframe src="<?= $url ?>#toolbar=0" class="viewer-iframe-revisar"></iframe>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

            <?php else: ?>

                <div class="description-box-revisar">
                    <p>
                        <?= e($solicitud['descripcion'] ?? $solicitud['Descripcion'] ?? 'Sin descripción') ?>
                    </p>
                </div>

            <?php endif; ?>
        </div>
    </div>

    <!-- Decisión Final -->
    <?php if (strtoupper($solicitud['estatus'] ?? $solicitud['Estatus']) === 'PENDIENTE' && (esAdmin() || tienePermiso('solicitudes.aprobar'))): ?>
        <?php if (!$esCambioBancario && $tipoCambio !== 'Datos Generales' && $tipoCambio !== 'Datos de Contacto'): ?>

            <div class="card-revisar">
                <div class="card-header-revisar">
                    <div class="card-icon-revisar success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <h2>Decisión Final</h2>
                </div>
                <div class="card-body-revisar">
                    <div class="actions-row-revisar">
                        <button type="button" class="btn-action-revisar danger" onclick="mostrarFormularioRechazo()">
                            <i class="bi bi-x-lg"></i>
                            Rechazar Solicitud
                        </button>

                        <form method="POST" action="<?= BASE_URL ?>solicitudes/aprobar/<?= $solicitudId ?>" id="formAprobar">
                            <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">
                            <button type="submit" class="btn-action-revisar success">
                                <i class="bi bi-check-lg"></i>
                                Aprobar y Activar Proveedor
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        <?php endif; ?>
    <?php endif; ?>

    <!-- Modal Rechazo -->
    <div id="formRechazo" class="modal-rechazo-revisar" style="display: none;">
        <div class="modal-content-rechazo">
            <h3 class="modal-title-rechazo">Rechazar Solicitud</h3>
            <form method="POST" action="<?= BASE_URL ?>solicitudes/rechazar/<?= $solicitudId ?>">
                <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">
                <div class="form-group-revisar">
                    <label class="form-label-revisar">Motivo de Rechazo <span class="required-revisar">*</span></label>
                    <textarea id="motivo_rechazo" name="motivo_rechazo" class="form-textarea-revisar" rows="4"
                        required></textarea>
                </div>
                <div class="modal-actions-rechazo">
                    <button type="button" class="btn-modal-revisar secondary"
                        onclick="ocultarFormularioRechazo()">Cancelar</button>
                    <button type="submit" class="btn-modal-revisar danger">Confirmar Rechazo</button>
                </div>
            </form>
        </div>
    </div>

</div>

<style>
    /* ==========================================
   GLASSMORPHISM - REVISAR SOLICITUD
   ========================================== */

    /* Animated Background */
    .animated-background-revisar {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #e0f2fe 0%, #bfdbfe 50%, #dbeafe 100%);
        z-index: -1;
        overflow: hidden;
    }

    body.dark-mode .animated-background-revisar {
        background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #2563eb 100%);
    }

    .gradient-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.4;
        animation: float 20s infinite ease-in-out;
    }

    body.dark-mode .gradient-orb {
        opacity: 0.3;
    }

    .orb-1 {
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.6) 0%, transparent 70%);
        top: -250px;
        left: -250px;
    }

    .orb-2 {
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(37, 99, 235, 0.5) 0%, transparent 70%);
        bottom: -200px;
        right: -200px;
        animation-delay: 7s;
    }

    .orb-3 {
        width: 350px;
        height: 350px;
        background: radial-gradient(circle, rgba(96, 165, 250, 0.5) 0%, transparent 70%);
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        animation-delay: 14s;
    }

    @keyframes float {

        0%,
        100% {
            transform: translate(0, 0) scale(1);
        }

        33% {
            transform: translate(100px, -100px) scale(1.1);
        }

        66% {
            transform: translate(-100px, 100px) scale(0.9);
        }
    }

    /* Layout */
    .revisar-solicitud-page {
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

    /* Hero Header */
    .page-hero-revisar {
        background: var(--glass-bg-card);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        border-radius: var(--glass-radius);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--glass-shadow);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1.5rem;
    }

    .hero-left-revisar {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex: 1;
    }

    .btn-back-revisar {
        width: 44px;
        height: 44px;
        background: var(--glass-bg-input);
        border: 2px solid var(--glass-border);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--glass-text-main);
        text-decoration: none;
        font-size: 1.25rem;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .btn-back-revisar:hover {
        background: var(--glass-primary-light);
        transform: translateX(-5px);
        color: var(--glass-text-main);
    }

    .hero-tag-revisar {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--glass-bg-input);
        border: 2px solid var(--glass-border);
        padding: 0.3rem 0.85rem;
        border-radius: 50px;
        color: var(--glass-text-main);
        font-size: 0.75rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .hero-title-revisar {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--glass-text-main);
        margin: 0 0 0.25rem 0;
    }

    .hero-subtitle-revisar {
        color: var(--glass-text-muted);
        font-size: 0.9rem;
        margin: 0;
    }

    .hero-actions-revisar {
        display: flex;
        gap: 0.75rem;
    }

    .btn-hero-action-revisar {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.65rem 1.1rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-hero-action-revisar.danger {
        background: var(--glass-danger);
        color: white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .btn-hero-action-revisar.danger:hover {
        background: #dc2626;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
    }

    /* Cards */
    .card-revisar {
        background: var(--glass-bg-card);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        border-radius: var(--glass-radius);
        margin-bottom: 1.5rem;
        box-shadow: var(--glass-shadow);
    }

    .card-header-revisar {
        padding: 1.25rem;
        border-bottom: 2px solid var(--glass-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .card-title-section-revisar {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex: 1;
    }

    .card-icon-revisar {
        width: 40px;
        height: 40px;
        background: var(--glass-primary-light);
        border: 2px solid rgba(59, 130, 246, 0.3);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--glass-primary);
        font-size: 1.25rem;
    }

    .card-icon-revisar.primary {
        background: var(--glass-primary-light);
        border-color: rgba(59, 130, 246, 0.3);
        color: var(--glass-primary);
    }

    .card-icon-revisar.success {
        background: rgba(16, 185, 129, 0.15);
        border-color: rgba(16, 185, 129, 0.3);
        color: var(--glass-success);
    }

    .card-header-revisar h2 {
        font-size: 1rem;
        font-weight: 700;
        color: var(--glass-text-main);
        margin: 0;
    }

    .card-body-revisar {
        padding: 1.25rem;
    }

    /* Status Badge */
    .status-badge-revisar {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .status-dot-revisar {
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

    .status-approved {
        background: rgba(16, 185, 129, 0.15);
        color: #047857;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .status-approved .status-dot-revisar {
        background: #047857;
        box-shadow: 0 0 8px rgba(4, 120, 87, 0.6);
    }

    .status-pending {
        background: rgba(245, 158, 11, 0.15);
        color: #d97706;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .status-pending .status-dot-revisar {
        background: #d97706;
        box-shadow: 0 0 8px rgba(217, 119, 6, 0.6);
    }

    .status-rejected {
        background: rgba(239, 68, 68, 0.15);
        color: #b91c1c;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .status-rejected .status-dot-revisar {
        background: #b91c1c;
        box-shadow: 0 0 8px rgba(185, 28, 28, 0.6);
    }

    .status-default {
        background: rgba(100, 116, 139, 0.15);
        color: #64748b;
        border: 1px solid rgba(100, 116, 139, 0.3);
    }

    /* Info Grid */
    .info-grid-revisar {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem;
    }

    .info-item-revisar {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .info-item-revisar label {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--glass-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .info-badge-revisar {
        display: inline-block;
        padding: 0.4rem 0.85rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 700;
        width: fit-content;
    }

    .info-badge-revisar.primary {
        background: rgba(59, 130, 246, 0.15);
        color: var(--glass-primary);
        border: 1px solid rgba(59, 130, 246, 0.3);
    }

    .info-badge-revisar.secondary {
        background: rgba(100, 116, 139, 0.15);
        color: #64748b;
        border: 1px solid rgba(100, 116, 139, 0.3);
    }

    .info-badge-revisar.info {
        background: rgba(6, 182, 212, 0.15);
        color: #0891b2;
        border: 1px solid rgba(6, 182, 212, 0.3);
    }

    .info-value-revisar {
        font-size: 0.9rem;
        color: var(--glass-text-main);
    }

    /* Data Items */
    .grid-revisar-3 {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.25rem;
    }

    .data-item-revisar {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .data-item-revisar label {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--glass-text-muted);
        text-transform: uppercase;
    }

    .data-value-revisar {
        font-size: 0.95rem;
        color: var(--glass-text-main);
        font-weight: 500;
    }

    .data-value-revisar.mono {
        font-family: 'Courier New', monospace;
        font-weight: 700;
    }

    .data-value-revisar.primary {
        color: var(--glass-primary);
        font-weight: 700;
    }

    /* Alert */
    .alert-revisar {
        background: var(--glass-bg-input);
        backdrop-filter: var(--glass-blur);
        border-radius: 10px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        border: 2px solid;
    }

    .alert-revisar i {
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .alert-revisar.info {
        border-color: rgba(6, 182, 212, 0.3);
        background: rgba(6, 182, 212, 0.08);
        color: var(--glass-text-main);
    }

    .alert-revisar.info i {
        color: var(--glass-info);
    }

    .alert-revisar.success {
        border-color: rgba(16, 185, 129, 0.3);
        background: rgba(16, 185, 129, 0.08);
        color: var(--glass-text-main);
    }

    .alert-revisar.success i {
        color: var(--glass-success);
    }

    .alert-revisar.warning {
        border-color: rgba(245, 158, 11, 0.3);
        background: rgba(245, 158, 11, 0.08);
        color: var(--glass-text-main);
    }

    .alert-revisar.warning i {
        color: var(--glass-warning);
    }

    /* Forms */
    .form-revisar {
        background: var(--glass-bg-input);
        backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        border-radius: 12px;
        padding: 1.5rem;
    }

    .section-title-revisar {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--glass-primary);
        margin: 0 0 1.25rem 0;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--glass-border);
    }

    .grid-revisar-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .form-group-revisar {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .form-label-revisar {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--glass-text-main);
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .required-revisar {
        color: var(--glass-danger);
    }

    .form-input-revisar,
    .form-select-revisar,
    .form-textarea-revisar {
        padding: 0.75rem 1rem;
        background: white;
        border: 2px solid var(--glass-border);
        border-radius: 10px;
        font-size: 0.875rem;
        color: var(--glass-text-main);
        transition: all 0.3s ease;
    }

    body.dark-mode .form-input-revisar,
    body.dark-mode .form-select-revisar,
    body.dark-mode .form-textarea-revisar {
        background: rgba(15, 23, 42, 0.7);
    }

    .form-input-revisar:focus,
    .form-select-revisar:focus,
    .form-textarea-revisar:focus {
        outline: none;
        border-color: var(--glass-primary);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
    }

    .form-input-revisar.mono {
        font-family: 'Courier New', monospace;
        font-weight: 700;
    }

    .form-hint-revisar {
        font-size: 0.75rem;
        color: var(--glass-text-muted);
        font-style: italic;
    }

    .input-group-revisar {
        display: flex;
        align-items: stretch;
    }

    .input-prefix-revisar {
        background: var(--glass-bg-card);
        border: 2px solid var(--glass-border);
        border-right: none;
        border-radius: 10px 0 0 10px;
        padding: 0.75rem 1rem;
        font-weight: 700;
        color: var(--glass-text-main);
        display: flex;
        align-items: center;
    }

    .input-group-revisar .form-input-revisar {
        border-radius: 0 10px 10px 0;
    }

    /* Buttons */
    .btn-submit-revisar {
        width: 100%;
        padding: 0.875rem 1.5rem;
        border: none;
        border-radius: 10px;
        font-size: 0.95rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 1.5rem;
    }

    .btn-submit-revisar.success {
        background: var(--glass-success);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-submit-revisar.success:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
    }

    /* Tables */
    .comparison-table-revisar {
        overflow-x: auto;
    }

    .table-revisar {
        width: 100%;
        border-collapse: collapse;
    }

    .table-revisar thead {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.08) 0%, rgba(139, 92, 246, 0.08) 100%);
    }

    .table-revisar th {
        padding: 0.875rem 1rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--glass-text-main);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        border-bottom: 2px solid var(--glass-border);
    }

    .table-revisar td {
        padding: 0.875rem 1rem;
        font-size: 0.875rem;
        border-bottom: 1px solid var(--glass-border);
    }

    .text-muted-revisar {
        color: var(--glass-text-muted);
    }

    .text-primary-revisar {
        color: var(--glass-primary);
        font-weight: 600;
    }

    /* Companies List */
    .companies-list-revisar {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .company-badge-revisar {
        padding: 0.5rem 1rem;
        background: var(--glass-primary-light);
        color: var(--glass-primary);
        border: 2px solid rgba(59, 130, 246, 0.3);
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 700;
    }

    /* Data Highlight */
    .data-highlight-revisar {
        background: var(--glass-bg-input);
        border: 2px solid var(--glass-border);
        border-radius: 10px;
        padding: 1rem 1.25rem;
    }

    .data-highlight-revisar label {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--glass-text-muted);
        text-transform: uppercase;
        display: block;
        margin-bottom: 0.5rem;
    }

    /* Split Layout */
    .split-layout-revisar {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        align-items: start;
    }

    /* Viewer */
    .viewer-revisar {
        background: var(--glass-bg-card);
        backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        border-radius: 12px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 600px;
    }

    .viewer-header-revisar {
        padding: 0.875rem 1.25rem;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.08) 0%, rgba(139, 92, 246, 0.08) 100%);
        border-bottom: 2px solid var(--glass-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .viewer-header-revisar span {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--glass-text-main);
    }

    .btn-expand-revisar {
        width: 32px;
        height: 32px;
        background: var(--glass-bg-input);
        border: 2px solid var(--glass-border);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--glass-text-main);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-expand-revisar:hover {
        background: var(--glass-primary-light);
        color: var(--glass-primary);
        border-color: var(--glass-primary);
    }

    .viewer-content-revisar {
        flex: 1;
        background: #525659;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .viewer-iframe-revisar {
        width: 100%;
        height: 100%;
        border: none;
    }

    .viewer-image-revisar {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    /* Description Box */
    .description-box-revisar {
        background: var(--glass-bg-input);
        border-left: 4px solid var(--glass-primary);
        border-radius: 8px;
        padding: 1.25rem;
    }

    .description-box-revisar p {
        margin: 0;
        color: var(--glass-text-main);
        line-height: 1.6;
    }

    /* Actions Row */
    .actions-row-revisar {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }

    .btn-action-revisar {
        padding: 0.875rem 1.75rem;
        border: none;
        border-radius: 10px;
        font-size: 0.95rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-action-revisar.danger {
        background: var(--glass-danger);
        color: white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .btn-action-revisar.danger:hover {
        background: #dc2626;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
    }

    .btn-action-revisar.success {
        background: var(--glass-success);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-action-revisar.success:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
    }

    /* Modal Rechazo */
    .modal-rechazo-revisar {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: var(--glass-bg-card);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        padding: 2rem;
        border-top: 4px solid var(--glass-danger);
        box-shadow: 0 -8px 32px rgba(0, 0, 0, 0.3);
        z-index: 1000;
    }

    .modal-content-rechazo {
        max-width: 800px;
        margin: 0 auto;
    }

    .modal-title-rechazo {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--glass-danger);
        margin: 0 0 1.5rem 0;
    }

    .modal-actions-rechazo {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }

    .btn-modal-revisar {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-modal-revisar.secondary {
        background: var(--glass-bg-input);
        color: var(--glass-text-main);
        border: 2px solid var(--glass-border);
    }

    .btn-modal-revisar.secondary:hover {
        background: var(--glass-bg-card);
        border-color: var(--glass-primary);
    }

    .btn-modal-revisar.danger {
        background: var(--glass-danger);
        color: white;
    }

    .btn-modal-revisar.danger:hover {
        background: #dc2626;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .split-layout-revisar {
            grid-template-columns: 1fr;
        }

        .viewer-revisar {
            height: 500px;
        }
    }

    @media (max-width: 768px) {
        .revisar-solicitud-page {
            padding: 1rem;
        }

        .page-hero-revisar {
            flex-direction: column;
            align-items: flex-start;
        }

        .hero-left-revisar {
            width: 100%;
        }

        .hero-actions-revisar {
            width: 100%;
        }

        .btn-hero-action-revisar {
            flex: 1;
            justify-content: center;
        }

        .grid-revisar-2,
        .grid-revisar-3 {
            grid-template-columns: 1fr;
        }

        .info-grid-revisar {
            grid-template-columns: 1fr;
        }

        .actions-row-revisar {
            flex-direction: column;
        }

        .btn-action-revisar {
            width: 100%;
            justify-content: center;
        }
    }

    .swal2-backdrop-show {
        backdrop-filter: blur(5px);
    }
</style>

<script>
    // APROBAR DATOS DE CONTACTO
    function aprobarDatosContacto() {
        const resp = document.getElementById('inputResponsable').value;
        const emailP = document.getElementById('inputEmailPagos').value;
        const emailProv = document.getElementById('inputEmailProv').value;
        const limite = document.getElementById('inputLimite').value;

        if (!resp || !emailP || !emailProv) return alertError('Campos incompletos');

        Swal.fire({
            title: '¿Guardar y Aprobar Contacto?',
            text: 'Se actualizarán los datos del proveedor en el sistema.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, aprobar'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?= BASE_URL ?>solicitudes/aprobar/<?= $solicitudId ?>';

                const inputs = {
                    csrf_token: '<?= generarToken() ?>',
                    es_datos_contacto: 1,
                    Responsable: resp,
                    NombreComercial: document.getElementById('inputNombreComercial').value,
                    CorreoPagosInterno: emailP,
                    CorreoProveedor: emailProv,
                    LimiteCredito: limite
                };

                for (const key in inputs) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = inputs[key];
                    form.appendChild(input);
                }
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // APROBAR DATOS GENERALES
    function aprobarDatosGenerales() {
        const rfc = document.getElementById('inputRFC').value;
        const tipo = document.getElementById('tipoPersonaHidden').value;
        const regimen = document.getElementById('inputRegimen').value;

        const datos = {
            csrf_token: '<?= generarToken() ?>',
            es_datos_generales: 1,
            RFC: rfc,
            RegimenFiscalId: regimen
        };

        if (tipo === 'MORAL') {
            const razon = document.getElementById('inputRazonSocial').value;
            if (!razon) return alertError('Falta Razón Social');
            datos.RazonSocial = razon;
        } else {
            const nombre = document.getElementById('inputNombre').value;
            const paterno = document.getElementById('inputPaterno').value;
            const materno = document.getElementById('inputMaterno').value;
            if (!nombre || !paterno) return alertError('Falta Nombre o Apellido Paterno');
            datos.Nombre = nombre;
            datos.ApellidoPaterno = paterno;
            datos.ApellidoMaterno = materno;
        }

        Swal.fire({
            title: '¿Guardar y Aprobar?',
            text: 'Se actualizará la información fiscal del proveedor.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, aprobar'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?= BASE_URL ?>solicitudes/aprobar/<?= $solicitudId ?>';

                for (const key in datos) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = datos[key];
                    form.appendChild(input);
                }
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function mostrarFormularioRechazo() {
        document.getElementById('formRechazo').style.display = 'block';
        document.getElementById('motivo_rechazo').focus();
    }

    function ocultarFormularioRechazo() {
        document.getElementById('formRechazo').style.display = 'none';
    }

    function verDocumento(url, titulo) {
        if (typeof window.verDocumento === 'function') {
            window.verDocumento(url, titulo);
        } else {
            window.open(url, '_blank');
        }
    }

    async function eliminarSolicitudActual() {
        const confirmed = await confirmDialog(
            '¿Eliminar Solicitud?',
            'Esta acción no se puede deshacer y borrará permanentemente esta solicitud.',
            'Sí, eliminar',
            'Cancelar'
        );

        if (confirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= BASE_URL ?>solicitudes/eliminar/<?= $solicitudId ?>';

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = 'csrf_token';
            csrfInput.value = '<?= generarToken() ?>';

            form.appendChild(csrfInput);
            document.body.appendChild(form);
            form.submit();
        }
    }

    document.getElementById('formAprobarBancario')?.addEventListener('submit', async function (e) {
        e.preventDefault();
        if (await confirmDialog('¿Confirmar?', 'Se actualizarán las cuentas bancarias.')) this.submit();
    });
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>