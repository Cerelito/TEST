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

                        <div class="form-group-revisar col-full">
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