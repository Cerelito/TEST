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

<style>
/* ==========================================
   GLASSMORPHISM THEME - EDITAR PROVEEDOR
   ========================================== */

/* Layout */
.editar-proveedor-layout {
    display: flex;
    gap: 1.5rem;
    height: calc(100vh - 100px);
    padding: 1.5rem;
    max-width: 2000px;
    margin: 0 auto;
    overflow: hidden;
}

/* Form Panel */
.form-panel-editar {
    flex: 1;
    overflow-y: auto;
    padding-right: 0.5rem;
}

.form-panel-editar::-webkit-scrollbar {
    width: 6px;
}

.form-panel-editar::-webkit-scrollbar-track {
    background: transparent;
}

.form-panel-editar::-webkit-scrollbar-thumb {
    background: var(--glass-border);
    border-radius: 3px;
}

.form-panel-editar::-webkit-scrollbar-thumb:hover {
    background: var(--glass-primary);
}

/* Hero Header */
.page-hero-editar {
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

.hero-left-editar {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex: 1;
}

.btn-back-editar {
    width: 44px;
    height: 44px;
    background: var(--glass-bg-input);
    backdrop-filter: var(--glass-blur);
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

.btn-back-editar:hover {
    background: var(--glass-primary-light);
    transform: translateX(-5px);
    color: var(--glass-text-main);
}

.hero-tag-editar {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--glass-bg-input);
    backdrop-filter: var(--glass-blur);
    border: 2px solid var(--glass-border);
    padding: 0.3rem 0.85rem;
    border-radius: 50px;
    color: var(--glass-text-main);
    font-size: 0.75rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.hero-title-editar {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--glass-text-main);
    margin: 0 0 0.25rem 0;
}

.hero-subtitle-editar {
    color: var(--glass-text-muted);
    font-size: 0.9rem;
    margin: 0;
}

.hero-actions-editar {
    display: flex;
    gap: 0.75rem;
}

.btn-hero-action-editar {
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

.btn-hero-action-editar.warning {
    background: var(--glass-warning);
    color: white;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

.btn-hero-action-editar.warning:hover {
    background: #d97706;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(245, 158, 11, 0.4);
    color: white;
}

/* Alert */
.alert-editar {
    background: var(--glass-bg-card);
    backdrop-filter: var(--glass-blur);
    border: 2px solid rgba(245, 158, 11, 0.3);
    border-left: 4px solid var(--glass-warning);
    border-radius: 12px;
    padding: 1.25rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: var(--glass-shadow);
}

.alert-icon-editar {
    width: 44px;
    height: 44px;
    background: rgba(245, 158, 11, 0.15);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: var(--glass-warning);
    font-size: 1.25rem;
}

.alert-content-editar {
    flex: 1;
}

.alert-title-editar {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--glass-text-main);
    margin: 0 0 0.25rem 0;
}

.alert-text-editar {
    font-size: 0.8rem;
    color: var(--glass-text-muted);
    margin: 0;
}

.btn-alert-editar {
    padding: 0.5rem 1rem;
    background: var(--glass-warning);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 700;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.btn-alert-editar:hover {
    background: #d97706;
    transform: translateY(-2px);
    color: white;
}

/* Cards */
.card-editar {
    background: var(--glass-bg-card);
    backdrop-filter: var(--glass-blur);
    -webkit-backdrop-filter: var(--glass-blur);
    border: 2px solid var(--glass-border);
    border-radius: var(--glass-radius);
    margin-bottom: 1.25rem;
    box-shadow: var(--glass-shadow);
}

.card-header-editar {
    padding: 1.25rem;
    border-bottom: 2px solid var(--glass-border);
    display: flex;
    align-items: center;
    gap: 1rem;
}

.card-icon-editar {
    width: 40px;
    height: 40px;
    background: var(--glass-primary-light);
    backdrop-filter: var(--glass-blur);
    border: 2px solid rgba(59, 130, 246, 0.3);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--glass-primary);
    font-size: 1.25rem;
}

.card-header-editar h2 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--glass-text-main);
    margin: 0;
    flex: 1;
}

.select-all-wrapper-editar {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--glass-text-main);
}

.card-body-editar {
    padding: 1.25rem;
}

/* Form Elements */
.form-editar {
    animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.grid-editar-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.grid-editar-3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}

.form-group-editar {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-label-editar {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--glass-text-main);
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.required-editar {
    color: var(--glass-danger);
}

.form-input-editar,
.form-select-editar {
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

.form-input-editar:focus,
.form-select-editar:focus {
    outline: none;
    border-color: var(--glass-primary);
    background: rgba(255, 255, 255, 0.8);
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
}

body.dark-mode .form-input-editar:focus,
body.dark-mode .form-select-editar:focus {
    background: rgba(15, 23, 42, 0.7);
}

/* Info Box */
.info-box-editar {
    display: flex;
    align-items: start;
    gap: 0.75rem;
    background: rgba(59, 130, 246, 0.08);
    padding: 1rem;
    border-radius: 10px;
    border: 2px solid rgba(59, 130, 246, 0.2);
    margin-bottom: 1.25rem;
}

.info-box-editar i {
    color: var(--glass-primary);
    font-size: 1.25rem;
    flex-shrink: 0;
    margin-top: 0.125rem;
}

.info-box-editar p {
    font-size: 0.85rem;
    color: var(--glass-text-main);
    margin: 0;
    line-height: 1.5;
}

/* Companies Grid */
.companies-grid-editar {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 0.875rem;
    max-height: 350px;
    overflow-y: auto;
    padding: 0.5rem;
}

.companies-grid-editar::-webkit-scrollbar {
    width: 6px;
}

.companies-grid-editar::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.05);
    border-radius: 3px;
}

.companies-grid-editar::-webkit-scrollbar-thumb {
    background: var(--glass-border);
    border-radius: 3px;
}

.company-card-editar {
    background: var(--glass-bg-input);
    backdrop-filter: var(--glass-blur);
    border: 2px solid var(--glass-border);
    border-radius: 10px;
    padding: 0.875rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.65rem;
    position: relative;
}

.company-card-editar:hover {
    border-color: var(--glass-primary);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
}

.company-card-editar.selected {
    border-color: var(--glass-danger);
    background: rgba(239, 68, 68, 0.05);
    box-shadow: 0 0 0 1px var(--glass-danger);
}

.company-card-editar.selected::after {
    content: '\F26E';
    font-family: 'bootstrap-icons';
    position: absolute;
    top: -8px;
    right: -8px;
    background: var(--glass-danger);
    color: white;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    border: 2px solid white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.company-card-editar label {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--glass-text-main);
    margin: 0;
    cursor: pointer;
    flex: 1;
}

.checkbox-editar {
    width: 18px;
    height: 18px;
    accent-color: var(--glass-danger);
    cursor: pointer;
}

/* Upload Zone */
.upload-zone-editar {
    background: var(--glass-bg-input);
    backdrop-filter: var(--glass-blur);
    border: 2px dashed var(--glass-border);
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}

.upload-zone-editar:hover {
    border-color: var(--glass-primary);
    background: rgba(59, 130, 246, 0.05);
}

.upload-icon-editar {
    width: 50px;
    height: 50px;
    background: var(--glass-primary-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--glass-primary);
    font-size: 1.5rem;
}

.upload-text-editar {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.upload-text-editar span {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--glass-text-main);
}

.upload-text-editar small {
    font-size: 0.75rem;
    color: var(--glass-text-muted);
}

.btn-upload-editar {
    padding: 0.5rem 1.25rem;
    background: var(--glass-primary);
    color: white;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
}

.btn-upload-editar:hover {
    background: var(--glass-primary-dark);
    transform: translateY(-2px);
}

.file-input-hidden {
    display: none;
}

/* Switch Container */
.switch-container-editar {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: var(--glass-bg-input);
    backdrop-filter: var(--glass-blur);
    border: 2px solid var(--glass-border);
    padding: 0.875rem 1.25rem;
    border-radius: 10px;
    margin-top: 1rem;
}

.switch-editar {
    width: 44px;
    height: 24px;
    appearance: none;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 24px;
    position: relative;
    cursor: pointer;
    transition: background 0.3s;
    border: 1px solid var(--glass-border);
}

.switch-editar:checked {
    background: var(--glass-primary);
}

.switch-editar::before {
    content: '';
    position: absolute;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: white;
    top: 2px;
    left: 2px;
    transition: left 0.3s;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.switch-editar:checked::before {
    left: 22px;
}

.switch-container-editar label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--glass-text-muted);
    margin: 0;
    cursor: pointer;
}

/* Notification Box */
.notification-box-editar {
    background: var(--glass-bg-input);
    backdrop-filter: var(--glass-blur);
    border: 2px solid var(--glass-border);
    border-radius: 10px;
    padding: 1rem;
    margin-top: 1rem;
}

.notification-checkbox-editar {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.5rem;
}

.notification-checkbox-editar label {
    font-size: 0.875rem;
    font-weight: 700;
    color: var(--glass-primary);
    margin: 0;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.notification-description-editar {
    font-size: 0.8rem;
    color: var(--glass-text-muted);
    margin: 0 0 0.75rem 0;
    padding-left: 2.25rem;
}

.email-adicional-wrapper-editar {
    padding-left: 2.25rem;
}

.email-adicional-input-editar {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: white;
    border: 2px solid var(--glass-primary);
    border-radius: 8px;
    padding: 0.5rem 1rem;
    margin-bottom: 0.5rem;
}

.email-adicional-input-editar i {
    color: var(--glass-primary);
}

.email-adicional-wrapper-editar small {
    font-size: 0.7rem;
    color: var(--glass-text-muted);
}

/* Submit */
.submit-wrapper-editar {
    display: flex;
    justify-content: flex-end;
    padding: 1.5rem 0 2rem 0;
}

.btn-submit-editar {
    padding: 0.875rem 2rem;
    background: var(--glass-primary);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.btn-submit-editar:hover {
    background: var(--glass-primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
}

/* Viewer Panel */
.viewer-panel-editar {
    flex: 0 0 450px;
    background: var(--glass-bg-card);
    backdrop-filter: var(--glass-blur);
    -webkit-backdrop-filter: var(--glass-blur);
    border: 2px solid var(--glass-border);
    border-radius: var(--glass-radius);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: var(--glass-shadow);
}

.viewer-header-editar {
    padding: 1rem 1.25rem;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.08) 0%, rgba(139, 92, 246, 0.08) 100%);
    backdrop-filter: var(--glass-blur);
    border-bottom: 2px solid var(--glass-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.viewer-title-editar {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--glass-text-main);
}

.viewer-title-editar i {
    color: var(--glass-danger);
    font-size: 1.25rem;
}

.btn-expand-editar {
    width: 36px;
    height: 36px;
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

.btn-expand-editar:hover {
    background: var(--glass-primary-light);
    color: var(--glass-primary);
    border-color: var(--glass-primary);
    transform: scale(1.05);
}

.viewer-content-editar {
    flex: 1;
    background: #525659;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.viewer-iframe-editar {
    width: 100%;
    height: 100%;
    border: none;
}

.viewer-image-editar {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.viewer-empty-editar {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    color: white;
    opacity: 0.5;
}

.viewer-empty-editar i {
    font-size: 4rem;
}

.viewer-empty-editar p {
    font-size: 0.9rem;
    font-weight: 600;
    margin: 0;
}

/* Responsive */
@media (max-width: 1200px) {
    .editar-proveedor-layout {
        flex-direction: column;
        height: auto;
    }

    .form-panel-editar {
        overflow: visible;
    }

    .viewer-panel-editar {
        flex: 0 0 500px;
        width: 100%;
    }

    .grid-editar-2,
    .grid-editar-3 {
        grid-template-columns: 1fr;
    }

    .companies-grid-editar {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    }
}

@media (max-width: 768px) {
    .editar-proveedor-layout {
        padding: 1rem;
        gap: 1rem;
    }

    .hero-title-editar {
        font-size: 1.25rem;
    }

    .page-hero-editar {
        flex-direction: column;
        align-items: flex-start;
    }

    .hero-left-editar {
        width: 100%;
    }

    .hero-actions-editar {
        width: 100%;
    }

    .btn-hero-action-editar {
        flex: 1;
        justify-content: center;
    }

    .viewer-panel-editar {
        flex: 0 0 400px;
    }
}
</style>

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