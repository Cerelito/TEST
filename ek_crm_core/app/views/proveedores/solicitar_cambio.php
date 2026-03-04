<?php
$pagina_actual = 'proveedores';
$titulo = 'Solicitar Cambio';
require_once VIEWS_PATH . 'layouts/header.php';

// Helper para evitar errores de índices indefinidos
$getVal = function ($row, $key) {
    if (!is_array($row))
        return null;
    return $row[$key] ?? $row[strtolower($key)] ?? $row[ucfirst($key)] ?? null;
};
?>



<div class="solicitar-cambio-page">

    <!-- Hero Header -->
    <div class="page-hero-solicitar">
        <div class="hero-left-solicitar">
            <a href="<?= BASE_URL ?>proveedores" class="btn-back-solicitar">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div class="hero-info-solicitar">
                <div class="hero-tag-solicitar">
                    <i class="bi bi-pencil-square"></i>
                    <span>Solicitud de Cambio</span>
                </div>
                <h1 class="hero-title-solicitar">Solicitar Cambio</h1>
                <p class="hero-subtitle-solicitar">
                    <strong><?= e($getVal($proveedor, 'RazonSocial') ?: $getVal($proveedor, 'Nombre') . ' ' . $getVal($proveedor, 'ApellidoPaterno')) ?></strong>
                </p>
            </div>
        </div>
    </div>

    <!-- Datos Actuales -->
    <div class="card-solicitar">
        <div class="card-header-solicitar">
            <div class="card-icon-solicitar">
                <i class="bi bi-info-circle"></i>
            </div>
            <h2>Datos Actuales</h2>
        </div>
        <div class="card-body-solicitar">
            <div class="grid-solicitar-3">
                <div class="data-item-solicitar">
                    <label>RFC</label>
                    <div class="data-value-solicitar mono primary"><?= e($getVal($proveedor, 'RFC')) ?></div>
                </div>
                <div class="data-item-solicitar">
                    <label>Razón Social</label>
                    <div class="data-value-solicitar">
                        <?= e($getVal($proveedor, 'RazonSocial') ?: $getVal($proveedor, 'Nombre')) ?></div>
                </div>
                <div class="data-item-solicitar">
                    <label>Estatus</label>
                    <?php $st = strtoupper($getVal($proveedor, 'Estatus') ?? 'PENDIENTE'); ?>
                    <span class="status-badge-solicitar <?= $st === 'APROBADO' ? 'approved' : 'pending' ?>">
                        <span class="status-dot-solicitar"></span>
                        <?= e($st) ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="<?= BASE_URL ?>proveedores/guardarSolicitud" id="formSolicitud"
        enctype="multipart/form-data" class="form-solicitar">
        <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">
        <input type="hidden" name="IdProveedor" value="<?= $getVal($proveedor, 'Id') ?>">

        <!-- Detalles de Solicitud -->
        <div class="card-solicitar">
            <div class="card-header-solicitar">
                <div class="card-icon-solicitar primary">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <h2>Detalles de la Solicitud</h2>
            </div>
            <div class="card-body-solicitar">
                <div class="grid-solicitar-2">
                    <div class="form-group-solicitar">
                        <label for="tipo_cambio" class="form-label-solicitar">
                            ¿Qué desea modificar? <span class="required-solicitar">*</span>
                        </label>
                        <select id="tipo_cambio" name="tipo_cambio" class="form-select-solicitar" required>
                            <option value="">Seleccione una opción...</option>
                            <option value="Datos Generales">Datos Generales (Razón Social, RFC, Régimen)</option>
                            <option value="Datos de Contacto">Datos de Contacto (Correos, Límite)</option>
                            <option value="Cuentas Bancarias">Cuentas Bancarias / Asignar Compañías</option>
                        </select>
                    </div>

                    <div class="form-group-solicitar">
                        <label for="urgencia" class="form-label-solicitar">
                            Nivel de Urgencia <span class="required-solicitar">*</span>
                        </label>
                        <select id="urgencia" name="urgencia" class="form-select-solicitar" required>
                            <option value="Media" selected>Media</option>
                            <option value="Alta">Alta</option>
                            <option value="Crítica">Crítica (Afecta pagos)</option>
                        </select>
                    </div>
                </div>

                <!-- Sección: Datos Generales -->
                <div id="sec_DatosGenerales" class="seccion-dinamica-solicitar" style="display: none;">
                    <div class="alert-solicitar info">
                        <i class="bi bi-file-earmark-pdf"></i>
                        <div>
                            <strong>Cambio Fiscal:</strong> Para modificar el RFC o Razón Social,
                            es obligatorio subir la nueva <strong>Constancia de Situación Fiscal (CSF)</strong>.
                        </div>
                    </div>
                    <div class="grid-solicitar-2">
                        <div class="form-group-solicitar">
                            <label class="form-label-solicitar">
                                Nueva CSF (PDF o Imagen) <span class="required-solicitar">*</span>
                            </label>
                            <div class="upload-zone-solicitar">
                                <div class="upload-icon-solicitar">
                                    <i class="bi bi-cloud-arrow-up"></i>
                                </div>
                                <div class="upload-text-solicitar">
                                    <span>Seleccione archivo</span>
                                    <small>PDF o Imagen (Máx 5MB)</small>
                                </div>
                                <label for="fileConstancia" class="btn-upload-solicitar">
                                    Examinar
                                </label>
                                <input type="file" id="fileConstancia" name="fileConstancia" class="file-input-hidden"
                                    accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                        </div>

                        <div class="form-group-solicitar">
                            <label for="RegimenFiscalId" class="form-label-solicitar">Nuevo Régimen Fiscal
                                (Opcional)</label>
                            <select name="RegimenFiscalId" id="RegimenFiscalId" class="form-select-solicitar">
                                <option value="">Seleccione el nuevo régimen...</option>
                                <?php foreach ($regimenes as $reg): ?>
                                    <option value="<?= $reg['Id'] ?>" <?= $getVal($proveedor, 'RegimenFiscalId') == $reg['Id'] ? 'selected' : '' ?>>
                                        <?= e($reg['Descripcion']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="form-hint-solicitar">Indique el nuevo régimen si cambió.</small>
                        </div>
                    </div>
                </div>

                <!-- Sección: Datos Contacto -->
                <div id="sec_DatosContacto" class="seccion-dinamica-solicitar" style="display: none;">
                    <div class="alert-solicitar light">
                        <i class="bi bi-pencil"></i>
                        <span>Edite únicamente los campos que desea actualizar.</span>
                    </div>
                    <div class="grid-solicitar-2">
                        <div class="form-group-solicitar">
                            <label class="form-label-solicitar">Responsable</label>
                            <input type="text" name="Responsable" class="form-input-solicitar"
                                value="<?= e($getVal($proveedor, 'Responsable')) ?>">
                        </div>

                        <div class="form-group-solicitar">
                            <label class="form-label-solicitar">Nombre Comercial</label>
                            <input type="text" name="NombreComercial" class="form-input-solicitar"
                                value="<?= e($getVal($proveedor, 'NombreComercial')) ?>">
                        </div>

                        <div class="form-group-solicitar">
                            <label class="form-label-solicitar primary-label">Email Interno</label>
                            <div class="input-group-solicitar">
                                <span class="input-prefix-solicitar"><i class="bi bi-building"></i></span>
                                <input type="email" name="CorreoPagosInterno" class="form-input-solicitar"
                                    value="<?= e($getVal($proveedor, 'CorreoPagosInterno')) ?>">
                            </div>
                        </div>

                        <div class="form-group-solicitar">
                            <label class="form-label-solicitar">Email Proveedor</label>
                            <div class="input-group-solicitar">
                                <span class="input-prefix-solicitar"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="CorreoProveedor" class="form-input-solicitar"
                                    value="<?= e($getVal($proveedor, 'CorreoProveedor')) ?>">
                            </div>
                        </div>

                        <div class="form-group-solicitar" style="grid-column: 1 / -1;">
                            <label class="form-label-solicitar">Límite de Crédito</label>
                            <div class="input-group-solicitar">
                                <span class="input-prefix-solicitar">$</span>
                                <input type="number" name="LimiteCredito" class="form-input-solicitar" step="0.01"
                                    value="<?= e($getVal($proveedor, 'LimiteCredito') ?? 0) ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección: Cuentas Bancarias -->
                <div id="sec_CuentasBancarias" class="seccion-dinamica-solicitar" style="display: none;">

                    <div class="alert-solicitar info">
                        <i class="bi bi-bank"></i>
                        <div>
                            <strong>Gestión de Cuentas:</strong>
                            Seleccione las compañías. Para las <strong>Nuevas</strong>, debe adjuntar documento.
                            Para las <strong>Existentes</strong>, puede actualizar el documento si cambió la cuenta.
                        </div>
                    </div>

                    <!-- Master File Upload -->
                    <div class="master-upload-solicitar">
                        <div class="master-icon-solicitar">
                            <i class="bi bi-layers-half"></i>
                        </div>
                        <div class="master-content-solicitar">
                            <h5>Carátula General (Maestra)</h5>
                            <p>Suba un archivo aquí para aplicarlo a todas las nuevas asignaciones.</p>
                            <label for="archivo_maestro_solicitud" class="btn-master-solicitar">
                                <i class="bi bi-cloud-arrow-up"></i>
                                Seleccionar Archivo
                            </label>
                            <input type="file" id="archivo_maestro_solicitud" name="archivo_maestro"
                                class="file-input-hidden" accept=".pdf,.jpg,.jpeg,.png"
                                onchange="aplicarVisualMaestro()">
                        </div>
                    </div>

                    <!-- Companies Header -->
                    <div class="companies-header-solicitar">
                        <label class="companies-title-solicitar">Selección de Compañías:</label>
                        <div class="select-all-wrapper-solicitar">
                            <input type="checkbox" id="selectAllCias" class="checkbox-solicitar">
                            <label for="selectAllCias">Seleccionar Todas</label>
                        </div>
                    </div>

                    <!-- Companies Grid -->
                    <div class="companies-grid-solicitar">
                        <?php
                        $idsAsignados = array_column($cias_asignadas ?? [], 'Id');
                        ?>

                        <?php foreach ($todas_cias as $cia): ?>
                            <?php $esExistente = in_array($cia['Id'], $idsAsignados); ?>

                            <div class="company-card-solicitar" id="card_<?= $cia['Id'] ?>">

                                <div class="company-header-solicitar">
                                    <div class="company-checkbox-solicitar">
                                        <input type="checkbox" id="cia_target_<?= $cia['Id'] ?>" name="CiasObjetivo[]"
                                            value="<?= $cia['Id'] ?>" class="checkbox-solicitar chk-cia-solicitud"
                                            <?= $esExistente ? 'checked disabled' : '' ?>
                                            onchange="toggleCiaSolicitud(<?= $cia['Id'] ?>)">

                                        <label for="cia_target_<?= $cia['Id'] ?>">
                                            <?= e($cia['Nombre']) ?>
                                        </label>

                                        <?php if ($esExistente): ?>
                                            <input type="hidden" name="CiasObjetivo[]" value="<?= $cia['Id'] ?>">
                                        <?php endif; ?>
                                    </div>

                                    <?php if ($esExistente): ?>
                                        <span class="company-badge-solicitar assigned">ASIGNADA</span>
                                    <?php else: ?>
                                        <span class="company-badge-solicitar available">DISPONIBLE</span>
                                    <?php endif; ?>
                                </div>

                                <div id="zona_archivo_<?= $cia['Id'] ?>" class="file-zone-solicitar"
                                    style="display: <?= $esExistente ? 'block' : 'none' ?>;">

                                    <?php if ($esExistente): ?>
                                        <div class="existing-account-solicitar">
                                            <div class="account-status-solicitar">
                                                <i class="bi bi-check-circle-fill"></i>
                                                <span>Cuenta Activa</span>
                                            </div>
                                            <button type="button" class="btn-change-solicitar"
                                                onclick="mostrarInputUpdate(<?= $cia['Id'] ?>)"
                                                id="btn_update_<?= $cia['Id'] ?>">
                                                <i class="bi bi-pencil-square"></i> Cambiar
                                            </button>
                                        </div>

                                        <div id="input_wrapper_<?= $cia['Id'] ?>" class="update-wrapper-solicitar"
                                            style="display: none;">
                                            <label class="update-label-solicitar">Nueva Carátula:</label>
                                            <div class="update-input-group-solicitar">
                                                <input type="file" name="ArchivosCias[<?= $cia['Id'] ?>]"
                                                    class="form-input-solicitar small" accept=".pdf,.jpg,.jpeg,.png">
                                                <button type="button" class="btn-cancel-solicitar"
                                                    onclick="ocultarInputUpdate(<?= $cia['Id'] ?>)">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </div>
                                        </div>

                                    <?php else: ?>
                                        <div id="file_area_<?= $cia['Id'] ?>" class="new-file-area-solicitar"
                                            style="display: none;">
                                            <div class="file-header-solicitar">
                                                <label>Archivo Individual</label>
                                                <small>(Opcional si hay Maestro)</small>
                                            </div>
                                            <input type="file" name="ArchivosCias[<?= $cia['Id'] ?>]"
                                                class="form-input-solicitar small input-individual-solicitar input-new-file"
                                                accept=".pdf,.jpg,.jpeg,.png" onchange="validarIndividual(this)">
                                        </div>
                                    <?php endif; ?>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>
        </div>

        <!-- Action Buttons -->
        <div class="actions-row-solicitar">
            <a href="<?= BASE_URL ?>proveedores" class="btn-action-solicitar secondary">
                <i class="bi bi-x-lg"></i>
                Cancelar
            </a>
            <button type="submit" class="btn-action-solicitar primary" id="btnEnviar">
                <i class="bi bi-send"></i>
                Enviar Solicitud
            </button>
        </div>

    </form>

</div>

<style>
    /* ==========================================
   GLASSMORPHISM - SOLICITAR CAMBIO
   ========================================== */

    /* Layout */
    .solicitar-cambio-page {
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
    .page-hero-solicitar {
        background: var(--glass-bg-card);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        border-radius: var(--glass-radius);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--glass-shadow);
    }

    .hero-left-solicitar {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .btn-back-solicitar {
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

    .btn-back-solicitar:hover {
        background: var(--glass-primary-light);
        transform: translateX(-5px);
        color: var(--glass-text-main);
    }

    .hero-tag-solicitar {
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

    .hero-title-solicitar {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--glass-text-main);
        margin: 0 0 0.25rem 0;
    }

    .hero-subtitle-solicitar {
        color: var(--glass-text-muted);
        font-size: 0.9rem;
        margin: 0;
    }

    /* Cards */
    .card-solicitar {
        background: var(--glass-bg-card);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        border-radius: var(--glass-radius);
        margin-bottom: 1.5rem;
        box-shadow: var(--glass-shadow);
    }

    .card-header-solicitar {
        padding: 1.25rem;
        border-bottom: 2px solid var(--glass-border);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .card-icon-solicitar {
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

    .card-icon-solicitar.primary {
        background: var(--glass-primary-light);
        color: var(--glass-primary);
    }

    .card-header-solicitar h2 {
        font-size: 1rem;
        font-weight: 700;
        color: var(--glass-text-main);
        margin: 0;
    }

    .card-body-solicitar {
        padding: 1.25rem;
    }

    /* Data Items */
    .grid-solicitar-3 {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.25rem;
    }

    .data-item-solicitar {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .data-item-solicitar label {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--glass-text-muted);
        text-transform: uppercase;
    }

    .data-value-solicitar {
        font-size: 0.95rem;
        color: var(--glass-text-main);
        font-weight: 500;
    }

    .data-value-solicitar.mono {
        font-family: 'Courier New', monospace;
        font-weight: 700;
    }

    .data-value-solicitar.primary {
        color: var(--glass-primary);
    }

    .status-badge-solicitar {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        width: fit-content;
    }

    .status-dot-solicitar {
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

    .status-badge-solicitar.approved {
        background: rgba(16, 185, 129, 0.15);
        color: #047857;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .status-badge-solicitar.approved .status-dot-solicitar {
        background: #047857;
        box-shadow: 0 0 8px rgba(4, 120, 87, 0.6);
    }

    .status-badge-solicitar.pending {
        background: rgba(245, 158, 11, 0.15);
        color: #d97706;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .status-badge-solicitar.pending .status-dot-solicitar {
        background: #d97706;
        box-shadow: 0 0 8px rgba(217, 119, 6, 0.6);
    }

    /* Forms */
    .grid-solicitar-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .form-group-solicitar {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .form-label-solicitar {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--glass-text-main);
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .form-label-solicitar.primary-label {
        color: var(--glass-primary);
    }

    .required-solicitar {
        color: var(--glass-danger);
    }

    .form-input-solicitar,
    .form-select-solicitar {
        padding: 0.75rem 1rem;
        background: white;
        border: 2px solid var(--glass-border);
        border-radius: 10px;
        font-size: 0.875rem;
        color: var(--glass-text-main);
        transition: all 0.3s ease;
    }

    body.dark-mode .form-input-solicitar,
    body.dark-mode .form-select-solicitar {
        background: rgba(15, 23, 42, 0.7);
    }

    .form-input-solicitar:focus,
    .form-select-solicitar:focus {
        outline: none;
        border-color: var(--glass-primary);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
    }

    .form-input-solicitar.small {
        padding: 0.5rem 0.75rem;
        font-size: 0.8rem;
    }

    .form-hint-solicitar {
        font-size: 0.75rem;
        color: var(--glass-text-muted);
        font-style: italic;
    }

    .input-group-solicitar {
        display: flex;
        align-items: stretch;
    }

    .input-prefix-solicitar {
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

    .input-group-solicitar .form-input-solicitar {
        border-radius: 0 10px 10px 0;
    }

    /* Secciones Dinámicas */
    .seccion-dinamica-solicitar {
        margin-top: 1.5rem;
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Alerts */
    .alert-solicitar {
        background: var(--glass-bg-input);
        backdrop-filter: var(--glass-blur);
        border-radius: 10px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: start;
        gap: 0.75rem;
        border: 2px solid;
    }

    .alert-solicitar i {
        font-size: 1.25rem;
        flex-shrink: 0;
        margin-top: 0.125rem;
    }

    .alert-solicitar.info {
        border-color: rgba(6, 182, 212, 0.3);
        background: rgba(6, 182, 212, 0.08);
        color: var(--glass-text-main);
    }

    .alert-solicitar.info i {
        color: var(--glass-info);
    }

    .alert-solicitar.light {
        border-color: var(--glass-border);
        background: var(--glass-bg-input);
        color: var(--glass-text-muted);
    }

    /* Upload Zone */
    .upload-zone-solicitar {
        background: var(--glass-bg-input);
        backdrop-filter: var(--glass-blur);
        border: 2px dashed var(--glass-border);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .upload-zone-solicitar:hover {
        border-color: var(--glass-primary);
        background: rgba(59, 130, 246, 0.05);
    }

    .upload-icon-solicitar {
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

    .upload-text-solicitar {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .upload-text-solicitar span {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--glass-text-main);
    }

    .upload-text-solicitar small {
        font-size: 0.75rem;
        color: var(--glass-text-muted);
    }

    .btn-upload-solicitar {
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

    .btn-upload-solicitar:hover {
        background: var(--glass-primary-dark);
        transform: translateY(-2px);
    }

    .file-input-hidden {
        display: none;
    }

    /* Master Upload */
    .master-upload-solicitar {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.08) 0%, rgba(139, 92, 246, 0.08) 100%);
        backdrop-filter: var(--glass-blur);
        border: 2px dashed var(--glass-border);
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .master-icon-solicitar {
        width: 60px;
        height: 60px;
        background: var(--glass-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .master-content-solicitar {
        flex: 1;
        text-align: center;
    }

    .master-content-solicitar h5 {
        font-size: 1rem;
        font-weight: 700;
        color: var(--glass-primary);
        margin: 0 0 0.25rem 0;
    }

    .master-content-solicitar p {
        font-size: 0.85rem;
        color: var(--glass-text-muted);
        margin: 0 0 1rem 0;
    }

    .btn-master-solicitar {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.65rem 1.25rem;
        background: var(--glass-primary);
        color: white;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-master-solicitar:hover {
        background: var(--glass-primary-dark);
        transform: translateY(-2px);
    }

    /* Companies Header */
    .companies-header-solicitar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .companies-title-solicitar {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--glass-text-main);
        margin: 0;
    }

    .select-all-wrapper-solicitar {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--glass-primary);
    }

    .checkbox-solicitar {
        width: 18px;
        height: 18px;
        accent-color: var(--glass-primary);
        cursor: pointer;
    }

    /* Companies Grid */
    .companies-grid-solicitar {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1rem;
    }

    .company-card-solicitar {
        background: white;
        border: 2px solid var(--glass-border);
        border-radius: 12px;
        padding: 1rem;
        transition: all 0.3s ease;
    }

    body.dark-mode .company-card-solicitar {
        background: rgba(15, 23, 42, 0.7);
    }

    .company-card-solicitar:hover {
        border-color: var(--glass-primary);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }

    .company-header-solicitar {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 0.75rem;
    }

    .company-checkbox-solicitar {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        flex: 1;
    }

    .company-checkbox-solicitar label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--glass-text-main);
        cursor: pointer;
        margin: 0;
    }

    .company-badge-solicitar {
        padding: 0.25rem 0.65rem;
        border-radius: 50px;
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        flex-shrink: 0;
    }

    .company-badge-solicitar.assigned {
        background: rgba(100, 116, 139, 0.15);
        color: #64748b;
        border: 1px solid rgba(100, 116, 139, 0.3);
    }

    .company-badge-solicitar.available {
        background: rgba(16, 185, 129, 0.15);
        color: #047857;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    /* File Zone */
    .file-zone-solicitar {
        border-top: 1px solid var(--glass-border);
        padding-top: 0.75rem;
        margin-top: 0.75rem;
    }

    .existing-account-solicitar {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .account-status-solicitar {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        color: var(--glass-success);
    }

    .btn-change-solicitar {
        padding: 0.35rem 0.75rem;
        background: var(--glass-bg-input);
        color: var(--glass-primary);
        border: 2px solid var(--glass-border);
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-change-solicitar:hover {
        background: var(--glass-primary-light);
        border-color: var(--glass-primary);
    }

    .update-wrapper-solicitar {
        background: var(--glass-bg-input);
        border-radius: 8px;
        padding: 0.75rem;
        margin-top: 0.75rem;
    }

    .update-label-solicitar {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--glass-primary);
        margin-bottom: 0.5rem;
        display: block;
    }

    .update-input-group-solicitar {
        display: flex;
        gap: 0.5rem;
    }

    .btn-cancel-solicitar {
        width: 32px;
        height: 32px;
        background: var(--glass-danger);
        color: white;
        border: none;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .btn-cancel-solicitar:hover {
        background: #dc2626;
    }

    .new-file-area-solicitar {
        margin-top: 0.75rem;
    }

    .file-header-solicitar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .file-header-solicitar label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--glass-text-muted);
        margin: 0;
    }

    .file-header-solicitar small {
        font-size: 0.7rem;
        color: var(--glass-text-light);
    }

    /* Action Buttons */
    .actions-row-solicitar {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .btn-action-solicitar {
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
        text-decoration: none;
    }

    .btn-action-solicitar.secondary {
        background: var(--glass-bg-card);
        color: var(--glass-text-main);
        border: 2px solid var(--glass-border);
    }

    .btn-action-solicitar.secondary:hover {
        background: var(--glass-bg-input);
        border-color: var(--glass-primary);
        transform: translateY(-2px);
        color: var(--glass-text-main);
    }

    .btn-action-solicitar.primary {
        background: var(--glass-warning);
        color: white;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .btn-action-solicitar.primary:hover {
        background: #d97706;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(245, 158, 11, 0.4);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .solicitar-cambio-page {
            padding: 1rem;
        }

        .hero-title-solicitar {
            font-size: 1.25rem;
        }

        .grid-solicitar-2,
        .grid-solicitar-3 {
            grid-template-columns: 1fr;
        }

        .companies-grid-solicitar {
            grid-template-columns: 1fr;
        }

        .master-upload-solicitar {
            flex-direction: column;
            text-align: center;
        }

        .actions-row-solicitar {
            flex-direction: column;
        }

        .btn-action-solicitar {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<script>
    const selectTipo = document.getElementById('tipo_cambio');

    const secciones = {
        'Datos Generales': 'sec_DatosGenerales',
        'Datos de Contacto': 'sec_DatosContacto',
        'Cuentas Bancarias': 'sec_CuentasBancarias'
    };

    selectTipo.addEventListener('change', function () {
        document.querySelectorAll('.seccion-dinamica-solicitar').forEach(el => el.style.display = 'none');
        document.getElementById('fileConstancia').required = false;

        const idSeccion = secciones[this.value];
        if (idSeccion) {
            const seccion = document.getElementById(idSeccion);
            if (seccion) {
                seccion.style.display = 'block';
            }

            if (this.value === 'Datos Generales') {
                document.getElementById('fileConstancia').required = true;
            }
        }
    });

    // Select All Logic
    const selectAllCias = document.getElementById('selectAllCias');
    if (selectAllCias) {
        selectAllCias.addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('.chk-cia-solicitud:not(:disabled)');
            checkboxes.forEach(cb => {
                if (cb.checked !== this.checked) {
                    cb.checked = this.checked;
                    toggleCiaSolicitud(cb.value);
                }
            });
        });
    }

    function toggleCiaSolicitud(id) {
        const checkbox = document.getElementById('cia_target_' + id);
        const zona = document.getElementById('zona_archivo_' + id);
        const fileArea = document.getElementById('file_area_' + id);
        const card = document.getElementById('card_' + id);

        if (checkbox.checked) {
            zona.style.display = 'block';
            if (fileArea) fileArea.style.display = 'block';

            card.style.borderColor = 'var(--glass-primary)';
            card.style.backgroundColor = 'rgba(59, 130, 246, 0.05)';
            aplicarVisualMaestro();
        } else {
            zona.style.display = 'none';
            if (fileArea) fileArea.style.display = 'none';

            card.style.borderColor = 'var(--glass-border)';
            card.style.backgroundColor = '';
            const input = zona.querySelector('input');
            if (input) input.value = '';
        }
    }

    function mostrarInputUpdate(id) {
        document.getElementById('btn_update_' + id).style.display = 'none';
        document.getElementById('input_wrapper_' + id).style.display = 'block';
    }

    function ocultarInputUpdate(id) {
        const wrapper = document.getElementById('input_wrapper_' + id);
        wrapper.style.display = 'none';
        wrapper.querySelector('input').value = '';
        document.getElementById('btn_update_' + id).style.display = 'inline-block';
    }

    function aplicarVisualMaestro() {
        const tieneMaestro = document.getElementById('archivo_maestro_solicitud').files.length > 0;
        const inputs = document.querySelectorAll('.input-new-file');

        inputs.forEach(input => {
            const card = input.closest('.company-card-solicitar');
            if (input.offsetParent !== null) {
                if (tieneMaestro && input.value === '') {
                    card.style.borderLeft = '4px solid var(--glass-success)';
                    input.placeholder = "Cubierto por Maestra";
                    input.title = "Cubierto por archivo maestro";
                } else {
                    card.style.borderLeft = '';
                    input.placeholder = "";
                }
            }
        });
    }

    function validarIndividual(input) {
        aplicarVisualMaestro();
    }

    // Validación Final
    document.getElementById('formSolicitud').addEventListener('submit', async function (e) {
        e.preventDefault();

        const tipo = selectTipo.value;

        if (tipo === '') {
            alertWarning('Tipo Requerido', 'Seleccione qué desea modificar.');
            return false;
        }

        if (tipo === 'Cuentas Bancarias') {
            const nuevasCheck = document.querySelectorAll('.chk-cia-solicitud:not(:disabled):checked');
            const totalSeleccionados = document.querySelectorAll('.chk-cia-solicitud:checked').length;

            if (totalSeleccionados === 0) {
                alertWarning('Selección Requerida', 'Debe haber al menos una compañía seleccionada.');
                return false;
            }

            const tieneMaestro = document.getElementById('archivo_maestro_solicitud').files.length > 0;
            let error = false;

            nuevasCheck.forEach(chk => {
                const id = chk.value;
                const fileInput = document.querySelector(`input[name="ArchivosCias[${id}]"]`);
                const fileVal = fileInput ? fileInput.value : '';

                if (!tieneMaestro && !fileVal) {
                    error = true;
                    const card = document.getElementById('card_' + id);
                    card.style.border = '2px solid var(--glass-danger)';
                    card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });

            if (error) {
                alertError('Faltan Documentos', 'Para nuevas asignaciones, suba la Carátula General o la individual por compañía.');
                return false;
            }
        }

        const confirmed = await confirmDialog(
            '¿Enviar Solicitud?',
            'El administrador revisará los cambios solicitados.',
            'Sí, enviar',
            'Cancelar'
        );

        if (confirmed) {
            const btnEnviar = document.getElementById('btnEnviar');
            btnEnviar.disabled = true;
            btnEnviar.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Enviando...';
            this.submit();
        }
    });
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>