<?php
$pagina_actual = 'proveedores';
$titulo = 'Editar Proveedor';
require_once VIEWS_PATH . 'layouts/header.php';

// Helper local para evitar errores de mayúsculas/minúsculas en claves del array
$getVal = function ($row, $key) {
    if (!is_array($row))
        return null;
    return $row[$key] ?? $row[strtolower($key)] ?? $row[ucfirst($key)] ?? null;
};

// Variables seguras
$id = $getVal($proveedor, 'Id');
$razonSocial = $getVal($proveedor, 'RazonSocial');
$rfc = $getVal($proveedor, 'RFC');
$idManual = $getVal($proveedor, 'IdManual');
$tipoPersona = $getVal($proveedor, 'TipoPersona') ?? 'MORAL';
?>

<div class="editar-proveedor-layout">
    
    <!-- Left Panel: Form -->
    <div class="form-panel-editar">
        
        <!-- Hero Header Glass -->
        <div class="page-hero-editar">
            <div class="hero-left-editar">
                <a href="<?= BASE_URL ?>proveedores" class="btn-back-editar">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="hero-info-editar">
                    <div class="hero-tag-editar">
                        <i class="bi bi-pencil-square"></i>
                        <span>Edición</span>
                    </div>
                    <h1 class="hero-title-editar">Editar Proveedor</h1>
                    <p class="hero-subtitle-editar">
                        <?php
                        $nom = $getVal($proveedor, 'Nombre');
                        $pat = $getVal($proveedor, 'ApellidoPaterno');
                        $mat = $getVal($proveedor, 'ApellidoMaterno');
                        $nombreCompleto = trim(($nom ?? '') . ' ' . ($pat ?? '') . ' ' . ($mat ?? ''));
                        echo e($razonSocial ?: ($nombreCompleto ?: 'SIN NOMBRE'));
                        ?>
                    </p>
                </div>
            </div>
            <div class="hero-actions-editar">
                <a href="<?= BASE_URL ?>datos-bancarios/index/<?= $id ?>" class="btn-hero-action-editar warning">
                    <i class="bi bi-bank"></i>
                    <span>Cuentas</span>
                </a>
            </div>
        </div>

        <!-- Alert de Solicitud Pendiente -->
        <?php if (!empty($solicitudPendiente)): ?>
            <div class="alert-editar warning">
                <div class="alert-icon-editar">
                    <i class="bi bi-exclamation-circle-fill"></i>
                </div>
                <div class="alert-content-editar">
                    <h4 class="alert-title-editar">¡Atención! Hay una solicitud pendiente</h4>
                    <p class="alert-text-editar">
                        Tipo: <strong><?= e($solicitudPendiente['TipoCambio']) ?></strong>
                        • <?= formatoFecha($solicitudPendiente['FechaSolicitud']) ?>
                    </p>
                </div>
                <?php if (esAdmin() || tienePermiso('solicitudes.aprobar')): ?>
                    <a href="<?= BASE_URL ?>solicitudes/revisar/<?= $solicitudPendiente['Id'] ?>"
                        class="btn-alert-editar">
                        <i class="bi bi-eye-fill"></i> Revisar
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form method="POST" action="<?= BASE_URL ?>proveedores/actualizar/<?= $id ?>" enctype="multipart/form-data"
            id="formProveedor" class="form-editar">
            <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">
            <input type="hidden" name="TipoPersona" id="TipoPersona" value="<?= e($tipoPersona) ?>">

            <!-- Identificación -->
            <div class="card-editar">
                <div class="card-header-editar">
                    <div class="card-icon-editar">
                        <i class="bi bi-person-vcard"></i>
                    </div>
                    <h2>Identificación</h2>
                </div>
                <div class="card-body-editar">
                    <div class="grid-editar-2">
                        <div class="form-group-editar">
                            <label for="RFC" class="form-label-editar">RFC <span class="required-editar">*</span></label>
                            <input type="text" id="RFC" name="RFC" class="form-input-editar" required
                                pattern="[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}" maxlength="13" value="<?= e($rfc) ?>">
                        </div>

                        <?php if (esAdmin()): ?>
                            <div class="form-group-editar">
                                <label for="IdManual" class="form-label-editar">Código Interno</label>
                                <input type="text" id="IdManual" name="IdManual" class="form-input-editar" maxlength="20"
                                    value="<?= e($idManual) ?>">
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group-editar">
                        <label for="TipoProveedor" class="form-label-editar">Tipo de Proveedor <span class="required-editar">*</span></label>
                        <?php $tipoProv = $getVal($proveedor, 'TipoProveedor'); ?>
                        <select id="TipoProveedor" name="TipoProveedor" class="form-select-editar" required>
                            <option value="">Seleccione...</option>
                            <?php
                            $opcionesTipo = [
                                '1- PROVEEDOR DE BIENES Y SERVICIOS',
                                '2- CONTRATISTA',
                                '3- ACREEDOR DIVERSO',
                                '4- HONORARIOS',
                                '5- ARRENDAMIENTO'
                            ];
                            foreach ($opcionesTipo as $opcion):
                                $selected = (strtoupper(trim($tipoProv)) === strtoupper(trim($opcion))) ? 'selected' : '';
                                ?>
                                <option value="<?= $opcion ?>" <?= $selected ?>><?= $opcion ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div id="camposMoral" style="display: none;">
                        <div class="form-group-editar">
                            <label for="RazonSocial" class="form-label-editar">Razón Social <span class="required-editar">*</span></label>
                            <input type="text" id="RazonSocial" name="RazonSocial" class="form-input-editar" maxlength="255"
                                value="<?= e($razonSocial) ?>">
                        </div>
                    </div>

                    <div id="camposFisica" style="display: none;">
                        <div class="grid-editar-3">
                            <div class="form-group-editar">
                                <label class="form-label-editar">Nombre</label>
                                <input type="text" name="Nombre" class="form-input-editar"
                                    value="<?= e($getVal($proveedor, 'Nombre')) ?>">
                            </div>
                            <div class="form-group-editar">
                                <label class="form-label-editar">Apellido Paterno</label>
                                <input type="text" name="ApellidoPaterno" class="form-input-editar"
                                    value="<?= e($getVal($proveedor, 'ApellidoPaterno')) ?>">
                            </div>
                            <div class="form-group-editar">
                                <label class="form-label-editar">Apellido Materno</label>
                                <input type="text" name="ApellidoMaterno" class="form-input-editar"
                                    value="<?= e($getVal($proveedor, 'ApellidoMaterno')) ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-group-editar">
                        <label for="NombreComercial" class="form-label-editar">Nombre Comercial</label>
                        <input type="text" id="NombreComercial" name="NombreComercial" class="form-input-editar" maxlength="255"
                            value="<?= e($getVal($proveedor, 'NombreComercial')) ?>" placeholder="Nombre de fantasía o negocio">
                    </div>

                    <div class="form-group-editar">
                        <label for="RegimenFiscal" class="form-label-editar">Régimen Fiscal <span class="required-editar">*</span></label>
                        <select id="RegimenFiscal" name="RegimenFiscalId" class="form-select-editar" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($regimenes as $reg): ?>
                                <option value="<?= e($getVal($reg, 'Id')) ?>" <?= $getVal($proveedor, 'RegimenFiscalId') == $getVal($reg, 'Id') ? 'selected' : '' ?>>
                                    <?= e($getVal($reg, 'Clave')) ?> - <?= e($getVal($reg, 'Descripcion')) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Dirección -->
            <div class="card-editar">
                <div class="card-header-editar">
                    <div class="card-icon-editar">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <h2>Dirección</h2>
                </div>
                <div class="card-body-editar">
                    <div class="form-group-editar">
                        <label class="form-label-editar">Calle</label>
                        <input type="text" name="Calle" class="form-input-editar" value="<?= e($getVal($proveedor, 'Calle')) ?>" required>
                    </div>
                    <div class="grid-editar-3">
                        <div class="form-group-editar">
                            <label class="form-label-editar">Ext</label>
                            <input type="text" name="NumeroExterior" class="form-input-editar" value="<?= e($getVal($proveedor, 'NumeroExterior')) ?>">
                        </div>
                        <div class="form-group-editar">
                            <label class="form-label-editar">Int</label>
                            <input type="text" name="NumeroInterior" class="form-input-editar" value="<?= e($getVal($proveedor, 'NumeroInterior')) ?>">
                        </div>
                        <div class="form-group-editar">
                            <label class="form-label-editar">Límite de Crédito</label>
                            <div class="input-with-icon-editar">
                                <i class="bi bi-cash-stack"></i>
                                <input type="text" name="LimiteCredito" class="form-input-editar" 
                                       value="<?= number_format((float)($getVal($proveedor, 'LimiteCredito') ?: 0), 2, '.', ',') ?>" 
                                       onkeyup="formatCurrency(this)" required>
                            </div>
                        </div>
                    </div>
                    <div class="grid-editar-2">
                        <div class="form-group-editar">
                            <label class="form-label-editar">CP</label>
                            <input type="text" name="CP" class="form-input-editar" value="<?= e($getVal($proveedor, 'CP')) ?>" required>
                        </div>
                        <div class="form-group-editar">
                            <label class="form-label-editar">Colonia</label>
                            <input type="text" name="Colonia" class="form-input-editar" value="<?= e($getVal($proveedor, 'Colonia')) ?>" required>
                        </div>
                        <div class="form-group-editar">
                            <label class="form-label-editar">Estado</label>
                            <select name="Estado" id="EstadoSelect" class="form-select-editar" required>
                                <option value="">Seleccione...</option>
                                <?php foreach ($estados as $edo): ?>
                                    <option value="<?= e($edo['Nombre']) ?>" data-id="<?= $edo['Id'] ?>" <?= $getVal($proveedor, 'Estado') == $edo['Nombre'] ? 'selected' : '' ?>>
                                        <?= e($edo['Nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" name="EstadoId" id="EstadoId" value="<?= e($getVal($proveedor, 'EstadoId')) ?>">
                        </div>
                    </div>
                    <div class="form-group-editar">
                        <label class="form-label-editar">Municipio</label>
                        <input type="text" name="Municipio" class="form-input-editar" value="<?= e($getVal($proveedor, 'Municipio')) ?>" required>
                        <input type="hidden" name="MunicipioId" id="MunicipioId" value="<?= e($getVal($proveedor, 'MunicipioId')) ?>">
                    </div>
                </div>
            </div>

            <!-- Contacto -->
            <div class="card-editar">
                <div class="card-header-editar">
                    <div class="card-icon-editar">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <h2>Contacto</h2>
                </div>
                <div class="card-body-editar">
                    <div class="grid-editar-2">
                        <div class="form-group-editar">
                            <label class="form-label-editar">Email Interno</label>
                            <input type="email" name="CorreoPagosInterno" class="form-input-editar"
                                value="<?= e($getVal($proveedor, 'CorreoPagosInterno')) ?>" required>
                        </div>
                        <div class="form-group-editar">
                            <label class="form-label-editar">Email Proveedor</label>
                            <input type="email" name="CorreoProveedor" class="form-input-editar"
                                value="<?= e($getVal($proveedor, 'CorreoProveedor')) ?>" required>
                        </div>
                    </div>
                    <div class="form-group-editar">
                        <label class="form-label-editar">Responsable</label>
                        <input type="text" name="Responsable" class="form-input-editar"
                            value="<?= e($getVal($proveedor, 'Responsable')) ?>" required>
                    </div>
                    <div class="form-group-editar">
                        <label class="form-label-editar">Límite de Crédito</label>
                        <input type="number" name="LimiteCredito" class="form-input-editar" step="0.01"
                            value="<?= e($getVal($proveedor, 'LimiteCredito') ?? 0) ?>">
                    </div>
                </div>
            </div>

            <!-- Compañías -->
            <div class="card-editar">
                <div class="card-header-editar">
                    <div class="card-icon-editar">
                        <i class="bi bi-building-gear"></i>
                    </div>
                    <h2>Asignación de Compañías</h2>
                    <div class="select-all-wrapper-editar">
                        <input type="checkbox" id="selectAllCias" class="checkbox-editar">
                        <label for="selectAllCias">Todas</label>
                    </div>
                </div>
                <div class="card-body-editar">
                    <div class="info-box-editar">
                        <i class="bi bi-info-circle"></i>
                        <p>Seleccione las compañías con las que trabaja este proveedor. Para cuentas bancarias específicas, use <strong>Gestionar Cuentas</strong>.</p>
                    </div>

                    <div class="companies-grid-editar">
                        <?php
                        $ciasIds = $ciasIds ?? [];
                        foreach ($cias as $cia):
                            $estaAsignada = in_array($getVal($cia, 'Id'), $ciasIds);
                            ?>
                            <div class="company-card-editar <?= $estaAsignada ? 'selected' : '' ?>" onclick="toggleCiaCard(this)">
                                <input type="checkbox" id="cia_<?= $getVal($cia, 'Id') ?>" name="Cias[]"
                                    value="<?= $getVal($cia, 'Id') ?>" class="checkbox-editar chk-cia"
                                    onclick="event.stopPropagation()" onchange="toggleCiaCard(this.closest('.company-card-editar'))"
                                    <?= $estaAsignada ? 'checked' : '' ?>>
                                <label for="cia_<?= $getVal($cia, 'Id') ?>">
                                    <?= e($getVal($cia, 'Nombre')) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- CSF -->
            <div class="card-editar">
                <div class="card-header-editar">
                    <div class="card-icon-editar">
                        <i class="bi bi-file-earmark-pdf"></i>
                    </div>
                    <h2>Actualizar CSF</h2>
                </div>
                <div class="card-body-editar">
                    <div class="upload-zone-editar">
                        <div class="upload-icon-editar">
                            <i class="bi bi-cloud-arrow-up"></i>
                        </div>
                        <div class="upload-text-editar">
                            <span>Seleccione un archivo nuevo</span>
                            <small>PDF o Imagen (Máx 5MB)</small>
                        </div>
                        <label for="ArchivoCSF" class="btn-upload-editar">
                            Examinar
                        </label>
                        <input type="file" name="ArchivoCSF" id="ArchivoCSF" class="file-input-hidden" accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    <?php if (esAdmin()): ?>
                        <div class="switch-container-editar">
                            <input type="checkbox" id="SinCSF" name="SinCSF" value="1" class="switch-editar">
                            <label for="SinCSF">Sin CSF (Solo Admin)</label>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Estatus -->
            <div class="card-editar">
                <div class="card-header-editar">
                    <div class="card-icon-editar">
                        <i class="bi bi-toggle-on"></i>
                    </div>
                    <h2>Estatus y Notificación</h2>
                </div>
                <div class="card-body-editar">
                    <div class="grid-editar-2">
                        <div class="form-group-editar">
                            <label class="form-label-editar">Estatus del Proveedor</label>
                            <select name="Estatus" class="form-select-editar">
                                <?php $estatus = $getVal($proveedor, 'Estatus'); ?>
                                <option value="PENDIENTE" <?= ($estatus == 'PENDIENTE') ? 'selected' : '' ?>>PENDIENTE</option>
                                <option value="APROBADO" <?= ($estatus == 'APROBADO') ? 'selected' : '' ?>>APROBADO</option>
                                <option value="RECHAZADO" <?= ($estatus == 'RECHAZADO') ? 'selected' : '' ?>>RECHAZADO</option>
                            </select>
                        </div>
                    </div>

                    <div class="notification-box-editar">
                        <div class="notification-checkbox-editar">
                            <input type="checkbox" name="enviar_ficha" id="enviar_ficha" value="1" class="checkbox-editar"
                                onchange="toggleEmailAdicional(this.checked)">
                            <label for="enviar_ficha">
                                <i class="bi bi-envelope-paper"></i>
                                Enviar Ficha Técnica Completa
                            </label>
                        </div>
                        <p class="notification-description-editar">
                            Marque para enviar email con todos los datos y cuentas bancarias actuales.
                        </p>
                        <div id="wrapperEmailAdicional" class="email-adicional-wrapper-editar" style="display: none;">
                            <div class="email-adicional-input-editar">
                                <i class="bi bi-envelope-plus"></i>
                                <input type="email" name="email_adicional" class="form-input-editar"
                                    placeholder="Enviar copia a (opcional)...">
                            </div>
                            <small>Si se deja vacío, solo se envía al correo interno.</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="submit-wrapper-editar">
                <button type="submit" class="btn-submit-editar">
                    <i class="bi bi-check-lg"></i>
                    Guardar Cambios
                </button>
            </div>

        </form>
    </div>

    <!-- Right Panel: Document Viewer -->
    <div class="viewer-panel-editar">
        <div class="viewer-header-editar">
            <div class="viewer-title-editar">
                <i class="bi bi-file-earmark-pdf"></i>
                <span>Constancia Fiscal Actual</span>
            </div>
            <?php if ($ruta = $getVal($proveedor, 'RutaConstancia')):
                $ext = strtolower(pathinfo($ruta, PATHINFO_EXTENSION));
                $isImg = in_array($ext, ['jpg', 'jpeg', 'png']);
                $url = BASE_URL . "proveedores/verArchivo/csf/" . $id . ($isImg ? "?isImage=1" : "");
                ?>
                <button onclick="verDocumento('<?= $url ?>', 'Constancia de Situación Fiscal')"
                    class="btn-expand-editar" title="Pantalla Completa">
                    <i class="bi bi-arrows-fullscreen"></i>
                </button>
            <?php endif; ?>
        </div>

        <div class="viewer-content-editar">
            <?php if ($getVal($proveedor, 'RutaConstancia')): ?>
                <?php
                $ext = strtolower(pathinfo($getVal($proveedor, 'RutaConstancia'), PATHINFO_EXTENSION));
                $url = BASE_URL . "proveedores/verArchivo/csf/" . $id;
                if (in_array($ext, ['jpg', 'jpeg', 'png'])): ?>
                    <img src="<?= $url ?>" class="viewer-image-editar" alt="CSF">
                <?php else: ?>
                    <iframe src="<?= $url ?>#toolbar=1&view=FitH" class="viewer-iframe-editar"></iframe>
                <?php endif; ?>
            <?php else: ?>
                <div class="viewer-empty-editar">
                    <i class="bi bi-file-earmark-x"></i>
                    <p>No hay constancia cargada</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
const regimenesFiscales = <?= json_encode($regimenes) ?>;

// Lógica para detectar Persona Fisica/Moral por RFC
document.getElementById('RFC').addEventListener('input', function () {
    const rfc = this.value.toUpperCase();
    this.value = rfc;

    const moral = document.getElementById('camposMoral');
    const fisica = document.getElementById('camposFisica');
    const tipoInput = document.getElementById('TipoPersona');

    if (rfc.length === 12) {
        tipoInput.value = 'MORAL';
        moral.style.display = 'block';
        fisica.style.display = 'none';
        document.getElementById('RazonSocial').required = true;
        document.querySelector('input[name="Nombre"]').required = false;
        document.querySelector('input[name="ApellidoPaterno"]').required = false;
        filtrarRegimenes('MORAL');
    } else {
        tipoInput.value = 'FISICA';
        moral.style.display = 'none';
        fisica.style.display = 'block';
        document.getElementById('RazonSocial').required = false;
        document.querySelector('input[name="Nombre"]').required = true;
        document.querySelector('input[name="ApellidoPaterno"]').required = true;
        filtrarRegimenes('FISICA');
    }
});

function filtrarRegimenes(tipoPersona) {
    const select = document.getElementById('RegimenFiscal');
    const currentVal = select.value;
    select.innerHTML = '<option value="">Seleccione...</option>';
    const codigosFisica = ['605', '606', '608', '611', '612', '614', '615', '621', '625', '629', '630'];

    regimenesFiscales.forEach(reg => {
        const row = reg;
        const codigo = String(row.clave ?? row.Clave ?? row.codigosat ?? row.CodigoSAT);
        const descripcion = row.descripcion ?? row.Descripcion;
        const id = row.id ?? row.Id;
        const tipoRegimenBD = row.tipopersona ?? row.TipoPersona;
        let mostrar = false;

        if (tipoPersona === 'FISICA') {
            if (tipoRegimenBD) {
                if (['Física', 'Fisica', 'Ambas'].includes(tipoRegimenBD)) mostrar = true;
            } else {
                if (codigosFisica.includes(codigo)) mostrar = true;
            }
        } else {
            if (tipoRegimenBD) {
                if (!['Física', 'Fisica'].includes(tipoRegimenBD)) mostrar = true;
            } else {
                if (!codigosFisica.includes(codigo) || codigo === '601') mostrar = true;
            }
        }

        if (mostrar) {
            const option = document.createElement('option');
            option.value = id;
            option.textContent = `${codigo} - ${descripcion}`;
            if (id == currentVal) option.selected = true;
            select.appendChild(option);
        }
    });
}

// Ejecutar al cargar
document.getElementById('RFC').dispatchEvent(new Event('input'));

document.getElementById('formProveedor').addEventListener('submit', function () {
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Guardando...';
});

function toggleEmailAdicional(checked) {
    const wrapper = document.getElementById('wrapperEmailAdicional');
    if (checked) {
        wrapper.style.display = 'block';
    } else {
        wrapper.style.display = 'none';
    }
}

function verDocumento(url, titulo) {
    if (typeof window.verDocumento === 'function') {
        window.verDocumento(url, titulo);
    } else {
        window.open(url, '_blank');
    }
}

// Lógica para compañías
const selectAllCias = document.getElementById('selectAllCias');
if (selectAllCias) {
    selectAllCias.addEventListener('change', function () {
        const checkboxes = document.querySelectorAll('.chk-cia');
        checkboxes.forEach(cb => {
            const card = cb.closest('.company-card-editar');
            if (card) {
                toggleCiaCard(card, this.checked);
            }
        });
    });
}

function toggleCiaCard(card, forceValue = null) {
    const checkbox = card.querySelector('.chk-cia');
    if (!checkbox) return;

    if (forceValue !== null) {
        checkbox.checked = forceValue;
    } else if (typeof event !== 'undefined' && event.target !== checkbox) {
        checkbox.checked = !checkbox.checked;
    }

    if (checkbox.checked) {
        card.classList.add('selected');
    } else {
        card.classList.remove('selected');
    }
}
// Función para formatear moneda (quitar todo lo que no sea número o punto, agregar comas)
function formatCurrency(input) {
    let value = input.value.replace(/[^\d.]/g, '');
    if (value) {
        let parts = value.split('.');
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        if (parts.length > 2) parts.pop();
        input.value = parts.join('.');
    }
}

// Carga dinámica de Municipios
const edoSelect = document.getElementById('EstadoSelect');
if (edoSelect) {
    edoSelect.addEventListener('change', function() {
        const estadoId = this.options[this.selectedIndex].getAttribute('data-id');
        document.getElementById('EstadoId').value = estadoId || '';
        
        // El municipio ahora es un input de texto, así que no necesitamos cargar opciones,
        // pero podemos resetear el MunicipioId si se desea, o simplemente dejar que el usuario escriba.
        document.getElementById('MunicipioId').value = '';
    });
}
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>