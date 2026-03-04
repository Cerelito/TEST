<?php
$pagina_actual = 'proveedores';
$titulo = 'Nuevo Proveedor';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<form method="POST" action="<?= BASE_URL ?>proveedores/guardar" enctype="multipart/form-data" id="formProveedor">
    <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">
    <input type="hidden" name="TipoPersona" id="TipoPersona" value="">

    <div class="dashboard-container">



        <!-- HEADER INTERNO (Visible en modo expandido) -->
        <div class="dashboard-header form-hidden">
            <div class="header-left">
                <a href="<?= BASE_URL ?>proveedores" class="btn-back-glass">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h1 class="glass-title">Nuevo Proveedor</h1>
                    <p class="glass-subtitle">complete la información requerida</p>
                </div>
            </div>
            <?php if (!esAdmin()): ?>
                <div class="header-alert-glass">
                    <i class="bi bi-info-circle"></i> Ingrese el RFC para validar
                </div>
            <?php endif; ?>
        </div>

        <!-- CONTENIDO LAYOUT (Hero y Grid) -->
        <div class="grid-wrapper">

            <!-- TEXTO HERO (Visible solo en inicio) -->
            <div class="hero-welcome-text form-hidden">
                <div class="hero-badge-glass">
                    <i class="bi bi-stars"></i> Gestión de Proveedores
                </div>
                <h1 class="hero-title-glass">Bienvenido al Portal de Alta</h1>
                <p class="hero-description-glass">Ingrese el RFC para validar la existencia del proveedor y comenzar el
                    registro.</p>
                <div class="hero-stats-glass">
                    <div class="stat-item-glass">
                        <span class="stat-val">+1200</span>
                        <span class="stat-label">Proveedores</span>
                    </div>
                    <div class="stat-item-glass">
                        <span class="stat-val">24/7</span>
                        <span class="stat-label">Disponibilidad</span>
                    </div>
                </div>
            </div>

            <div class="layout-column">

                <div class="card-glass">
                    <div class="card-header-glass">
                        <div class="icon-glass">
                            <i class="bi bi-person-vcard"></i>
                        </div>
                        <h3>Identificación Fiscal</h3>
                    </div>

                    <div class="card-body-glass">
                        <div class="form-group mb-0">
                            <label for="RFC" class="label-glass">RFC <span class="text-danger-glass">*</span></label>
                            <div class="input-group-glass">
                                <input type="text" id="RFC" name="RFC" class="input-glass rfc-font" required
                                    pattern="[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}" minlength="12" maxlength="13"
                                    placeholder="XAXX010101000">
                                <div id="rfcLoader" class="input-loader-glass">
                                    <div class="spinner-glass"></div>
                                </div>
                            </div>
                            <div id="rfcFeedback" class="field-feedback-glass"></div>

                            <?php if (esAdmin()): ?>
                                <div class="mt-3 switch-container-glass">
                                    <input class="switch-glass" type="checkbox" id="EsGenerico" name="EsGenerico" value="1">
                                    <label class="switch-label-glass" for="EsGenerico">RFC Genérico (Público en
                                        General)</label>
                                </div>

                                <div id="tipoPersonaGenerico" class="mt-3 form-hidden">
                                    <label class="label-glass">Tipo de Persona para RFC Genérico:</label>
                                    <select name="TipoPersona" id="TipoPersonaManual" class="select-glass">
                                        <option value="FISICA">Física</option>
                                        <option value="MORAL">Moral</option>
                                    </select>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if (esAdmin()): ?>
                            <div class="form-group mt-3">
                                <label for="IdManual" class="label-glass">Código Interno <span
                                        class="text-danger-glass">*</span></label>
                                <input type="text" id="IdManual" name="IdManual" class="input-glass" required maxlength="20"
                                    placeholder="Ej: EK-001">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div id="formularioCompleto" class="form-hidden">
                    <div class="card-glass mt-3">
                        <div class="card-header-glass">
                            <div class="icon-glass">
                                <i class="bi bi-building"></i>
                            </div>
                            <h3>Datos Generales</h3>
                        </div>
                        <div class="card-body-glass">
                            <div class="form-group">
                                <label class="label-glass">Tipo Proveedor <span
                                        class="text-danger-glass">*</span></label>
                                <select id="TipoProveedor" name="TipoProveedor" class="select-glass" required>
                                    <option value="">Seleccione...</option>
                                    <option value="1- PROVEEDOR DE BIENES Y SERVICIOS">1- PROVEEDOR DE BIENES Y
                                        SERVICIOS</option>
                                    <option value="2- CONTRATISTA">2- CONTRATISTA</option>
                                    <option value="3- ACREEDOR DIVERSO">3- ACREEDOR DIVERSO</option>
                                    <option value="4- HONORARIOS">4- HONORARIOS</option>
                                    <option value="5- ARRENDAMIENTO">5- ARRENDAMIENTO</option>
                                </select>
                            </div>

                            <div id="camposMoral" class="campos-tipo mt-3">
                                <div class="form-group">
                                    <label class="label-glass">Razón Social <span
                                            class="text-danger-glass">*</span></label>
                                    <input type="text" id="RazonSocial" name="RazonSocial" class="input-glass">
                                </div>
                            </div>

                            <div id="camposFisica" class="campos-tipo mt-3">
                                <div class="grid-glass-2">
                                    <div class="form-group">
                                        <label class="label-glass">Nombre <span
                                                class="text-danger-glass">*</span></label>
                                        <input type="text" id="Nombre" name="Nombre" class="input-glass">
                                    </div>
                                    <div class="form-group">
                                        <label class="label-glass">Ap. Paterno <span
                                                class="text-danger-glass">*</span></label>
                                        <input type="text" id="ApellidoPaterno" name="ApellidoPaterno"
                                            class="input-glass">
                                    </div>
                                    <div class="form-group">
                                        <label class="label-glass">Ap. Materno</label>
                                        <input type="text" id="ApellidoMaterno" name="ApellidoMaterno"
                                            class="input-glass">
                                    </div>
                                </div>
                            </div>

                            <div class="grid-glass-2 mt-3">
                                <div class="form-group full-width-glass">
                                    <label class="label-glass">Régimen Fiscal <span
                                            class="text-danger-glass">*</span></label>
                                    <select id="RegimenFiscalId" name="RegimenFiscalId" class="select-glass" required>
                                        <option value="">Seleccione...</option>
                                    </select>
                                </div>
                                <?php if (esAdmin()): ?>
                                    <div class="form-group">
                                        <label class="label-glass">C.P. <span class="text-danger-glass">*</span></label>
                                        <input type="text" id="CP" name="CP" class="input-glass" required pattern="[0-9]{5}"
                                            maxlength="5">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="card-glass mt-3">
                        <div class="card-header-glass">
                            <div class="icon-glass">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <h3>Contacto</h3>
                        </div>
                        <div class="card-body-glass">
                            <div class="form-group">
                                <label class="label-glass">Responsable <span class="text-danger-glass">*</span></label>
                                <input type="text" name="Responsable" class="input-glass" required
                                    placeholder="Nombre completo">
                            </div>
                            <div class="grid-glass-2 mt-3">
                                <div class="form-group">
                                    <label class="label-glass">Email Interno <span
                                            class="text-danger-glass">*</span></label>
                                    <input type="email" name="CorreoPagosInterno" class="input-glass" required
                                        placeholder="@miempresa.com">
                                </div>
                                <div class="form-group">
                                    <label class="label-glass">Email Prov. <span
                                            class="text-danger-glass">*</span></label>
                                    <input type="email" name="CorreoProveedor" class="input-glass" required
                                        placeholder="@proveedor.com">
                                </div>
                                <div class="form-group">
                                    <label class="label-glass">Límite de Crédito</label>
                                    <div class="input-group-glass">
                                        <span class="input-group-text-glass">$</span>
                                        <input type="text" name="LimiteCredito" id="LimiteCredito" class="input-glass"
                                            placeholder="0.00" onkeyup="formatCurrency(this)">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="layout-column form-hidden" id="columnaDerecha">

                <div class="card-glass">
                    <div class="card-header-glass">
                        <div class="icon-glass">
                            <i class="bi bi-file-earmark-pdf"></i>
                        </div>
                        <h3>Documentación Fiscal</h3>
                    </div>
                    <div class="card-body-glass">
                        <div class="upload-zone-glass">
                            <div class="upload-icon-glass">
                                <i class="bi bi-cloud-arrow-up"></i>
                            </div>
                            <div class="upload-text-glass">
                                <span class="upload-title-glass">Constancia de Situación Fiscal <span
                                        class="text-danger-glass">*</span></span>
                                <span class="upload-subtitle-glass" id="constanciaFileName">PDF o Imagen (Máx
                                    5MB)</span>
                            </div>
                            <label for="fileConstancia" class="btn-upload-glass">
                                Examinar
                            </label>
                            <input type="file" id="fileConstancia" name="fileConstancia" class="file-input-hidden"
                                accept=".pdf,.jpg,.jpeg,.png" required
                                onchange="updateFileNameCompact(this, 'constanciaFileName')">
                        </div>

                        <?php if (esAdmin()): ?>
                            <div class="mt-3 switch-container-glass">
                                <input class="switch-glass" type="checkbox" id="SinCSF" name="SinCSF" value="1"
                                    onchange="toggleRequeridoDoc(this, 'fileConstancia')">
                                <label class="switch-label-glass" for="SinCSF">Sin CSF (Solo Admin)</label>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card-glass mt-3 companies-wrapper-glass">
                    <div class="companies-header-glass" style="border-bottom: 2px solid #ef4444;">
                        <div class="header-text-glass">
                            <h3><i class="bi bi-building-gear" style="color: #ef4444;"></i> Asignación de Compañías</h3>
                            <p>Configure documentación bancaria por empresa</p>
                        </div>
                        <div class="select-all-glass" style="color: #ef4444;">
                            <input type="checkbox" id="selectAllCias" class="checkbox-glass chk-cia"
                                style="accent-color: #ef4444;">
                            <label for="selectAllCias" class="fw-bold cursor-pointer">Seleccionar Todas</label>
                        </div>
                    </div>

                    <div class="master-upload-glass">
                        <div class="master-info-glass">
                            <i class="bi bi-file-earmark-spreadsheet-fill"></i>
                            <div>
                                <strong>Carátula Bancaria General</strong>
                                <small>Se aplicará a todas las seleccionadas</small>
                            </div>
                        </div>
                        <label for="archivo_maestro" class="btn-master-upload-glass">
                            <i class="bi bi-upload"></i> <span id="masterFileName">Elegir archivo</span>
                        </label>
                        <input type="file" id="archivo_maestro" name="archivo_maestro" class="file-input-hidden"
                            accept=".pdf,.jpg,.jpeg,.png" onchange="aplicarArchivoMaestro()">
                    </div>

                    <?php if (esAdmin()): ?>
                        <div class="mb-3 switch-container-glass">
                            <input class="switch-glass" type="checkbox" id="SinCaratula" name="SinCaratula" value="1">
                            <label class="switch-label-glass" for="SinCaratula">Sin Carátula Bancaria (Solo Admin)</label>
                        </div>
                    <?php endif; ?>

                    <div class="companies-grid-glass">
                        <?php foreach ($cias as $cia): ?>
                            <div class="cia-card-glass" data-id="<?= $cia['Id'] ?>"
                                onclick="toggleCiaCard(<?= $cia['Id'] ?>)">

                                <div class="cia-main-content-glass">
                                    <input type="checkbox" id="cia_<?= $cia['Id'] ?>"
                                        name="Cias[<?= $cia['Id'] ?>][selected]" value="1" class="checkbox-glass chk-cia"
                                        onclick="event.stopPropagation()" onchange="toggleCiaCard(<?= $cia['Id'] ?>)">
                                    <label for="cia_<?= $cia['Id'] ?>" onclick="event.stopPropagation()">
                                        <?= e($cia['Nombre']) ?>
                                    </label>
                                </div>

                                <div class="cia-status-glass" id="status_cia_<?= $cia['Id'] ?>"
                                    onclick="event.stopPropagation()">
                                    <span class="status-text-glass">Sin archivo</span>
                                    <label class="btn-change-glass" title="Subir archivo específico">
                                        <i class="bi bi-arrow-repeat"></i>
                                        <input type="file" name="Cias[<?= $cia['Id'] ?>][archivo_propio]"
                                            class="file-input-hidden" accept=".pdf,.jpg,.jpeg,.png"
                                            onchange="actualizarArchivoIndividual(this, <?= $cia['Id'] ?>)">
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="actions-panel-glass mt-3">
                    <button type="button" class="btn-cancel-glass"
                        onclick="window.location.href='<?= BASE_URL ?>proveedores'">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-submit-glass" id="btnGuardar">
                        <?= esAdmin() ? 'Guardar' : 'Enviar' ?> <i class="bi bi-arrow-right"></i>
                    </button>
                </div>

            </div>
        </div>
    </div>
</form>

<style>
    /* ==========================================
   GLASSMORPHISM THEME - CRISTAL LÍQUIDO
   ========================================== */

    /* ==========================================
   DASHBOARD CONTAINER
   ========================================== */
    .dashboard-container {
        position: relative;
        width: 100%;
        min-height: 100vh;
        padding: 2rem;
        overflow: visible;
    }

    /* MODO HERO */
    .dashboard-container.mode-hero {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 600px;
    }

    .dashboard-container.mode-hero .grid-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4rem;
        width: 100%;
        max-width: 1200px;
    }

    /* MODO EXPANDIDO */
    .dashboard-container.mode-expanded {
        display: block;
    }

    .dashboard-container.mode-expanded .grid-wrapper {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        max-width: 1400px;
        margin: 0 auto;
        padding-top: 2rem;
    }

    /* ==========================================
   HEADER GLASS
   ========================================== */
    .dashboard-header {
        background: var(--glass-bg-card);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        border-radius: var(--glass-radius);
        padding: 2rem;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: var(--glass-shadow), inset 0 1px 0 0 rgba(255, 255, 255, 0.3);
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .btn-back-glass {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: var(--glass-bg-input);
        backdrop-filter: var(--glass-blur);
        border: 1px solid var(--glass-border);
        color: var(--glass-text-main);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 1.25rem;
    }

    .btn-back-glass:hover {
        background: var(--glass-primary-light);
        transform: translateX(-5px);
        color: var(--glass-text-main);
    }

    .glass-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--glass-text-main);
        margin: 0;
    }

    .glass-subtitle {
        font-size: 0.9rem;
        color: var(--glass-text-muted);
        margin: 0;
    }

    .header-alert-glass {
        background: var(--glass-bg-input);
        backdrop-filter: var(--glass-blur);
        border: 1px solid var(--glass-border);
        color: var(--glass-text-main);
        padding: 0.75rem 1.25rem;
        border-radius: 50px;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* ==========================================
   HERO SECTION GLASS
   ========================================== */
    .hero-welcome-text {
        color: var(--glass-text-main);
        animation: fadeInUp 0.8s ease-out;
    }

    .hero-badge-glass {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--glass-bg-card);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        padding: 0.5rem 1.25rem;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 2rem;
        box-shadow: var(--glass-shadow), inset 0 1px 0 0 rgba(255, 255, 255, 0.3);
    }

    .hero-title-glass {
        font-size: 2.75rem;
        font-weight: 800;
        margin-bottom: 1rem;
        line-height: 1.1;
        text-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .hero-description-glass {
        font-size: 1.125rem;
        color: var(--glass-text-muted);
        margin-bottom: 2.5rem;
        max-width: 90%;
        line-height: 1.6;
    }

    .hero-stats-glass {
        display: flex;
        gap: 3rem;
    }

    .stat-item-glass {
        display: flex;
        flex-direction: column;
        background: var(--glass-bg-card);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        padding: 1rem 1.5rem;
        border-radius: 12px;
        box-shadow: var(--glass-shadow), inset 0 1px 0 0 rgba(255, 255, 255, 0.3);
    }

    .stat-item-glass .stat-val {
        font-size: 1.75rem;
        font-weight: 700;
    }

    .stat-item-glass .stat-label {
        font-size: 0.8rem;
        color: var(--glass-text-muted);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* ==========================================
   CARDS GLASS
   ========================================== */
    .card-glass {
        background: var(--glass-bg-card);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        border-radius: var(--glass-radius);
        box-shadow: var(--glass-shadow), inset 0 1px 0 0 rgba(255, 255, 255, 0.3);
        overflow: hidden;
        transition: all 0.3s ease;
        position: relative;
    }

    .card-glass::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
    }

    body.dark-mode .card-glass::before {
        background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.3), transparent);
    }

    .card-glass:hover {
        transform: translateY(-5px);
        box-shadow: var(--glass-shadow-hover);
    }

    .card-header-glass {
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border-bottom: 1px solid var(--glass-border);
    }

    .icon-glass {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: var(--glass-primary-light);
        backdrop-filter: var(--glass-blur);
        border: 1px solid rgba(59, 130, 246, 0.3);
        color: var(--glass-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .card-header-glass h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--glass-text-main);
        margin: 0;
    }

    .card-body-glass {
        padding: 2rem;
    }

    /* ==========================================
   FORM ELEMENTS GLASS
   ========================================== */
    .label-glass {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--glass-text-main);
        margin-bottom: 0.5rem;
    }

    .input-glass,
    .select-glass {
        width: 100%;
        padding: 0.875rem 1rem;
        font-size: 0.95rem;
        background: var(--glass-bg-input);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        border-radius: 12px;
        color: var(--glass-text-main);
        transition: all 0.3s ease;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .input-glass::placeholder {
        color: var(--glass-text-muted);
    }

    .input-glass:focus,
    .select-glass:focus {
        outline: none;
        border-color: var(--glass-primary);
        background: rgba(255, 255, 255, 0.8);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15), inset 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    body.dark-mode .input-glass:focus,
    body.dark-mode .select-glass:focus {
        background: rgba(15, 23, 42, 0.7);
    }

    .select-glass {
        cursor: pointer;
    }

    .rfc-font {
        font-family: 'Roboto Mono', monospace;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
    }

    .input-group-glass {
        display: flex;
        align-items: stretch;
    }

    .input-group-text-glass {
        display: flex;
        align-items: center;
        padding: 0 1rem;
        background: var(--glass-bg-input);
        border: 2px solid var(--glass-border);
        border-right: none;
        border-radius: 12px 0 0 12px;
        color: var(--glass-text-muted);
        font-weight: 700;
        font-size: 1.1rem;
    }

    .input-group-text-glass+.input-glass {
        border-radius: 0 12px 12px 0;
    }

    .input-loader-glass {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        display: none;
    }

    .spinner-glass {
        width: 20px;
        height: 20px;
        border: 3px solid var(--glass-border);
        border-top-color: var(--glass-primary);
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    .grid-glass-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .full-width-glass {
        grid-column: 1 / -1;
    }

    .text-danger-glass {
        color: var(--glass-danger);
    }

    .field-feedback-glass {
        font-size: 0.8rem;
        margin-top: 0.5rem;
        font-weight: 600;
    }

    /* ==========================================
   SWITCH GLASS
   ========================================== */
    .switch-container-glass {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: var(--glass-bg-input);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        padding: 0.875rem 1.25rem;
        border-radius: 12px;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .switch-glass {
        width: 48px;
        height: 24px;
        appearance: none;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        position: relative;
        cursor: pointer;
        transition: background 0.3s;
        border: 1px solid var(--glass-border);
    }

    .switch-glass:checked {
        background: var(--glass-primary);
    }

    .switch-glass::before {
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

    .switch-glass:checked::before {
        left: 26px;
    }

    .switch-label-glass {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--glass-text-main);
        margin: 0;
        cursor: pointer;
    }

    /* ==========================================
   UPLOAD ZONE GLASS
   ========================================== */
    .upload-zone-glass {
        background: var(--glass-bg-input);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px dashed var(--glass-border);
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .upload-zone-glass:hover {
        border-color: var(--glass-primary);
        background: rgba(59, 130, 246, 0.08);
        box-shadow: inset 0 2px 8px rgba(59, 130, 246, 0.1);
    }

    .upload-icon-glass {
        width: 60px;
        height: 60px;
        margin: 0 auto 1rem;
        border-radius: 50%;
        background: var(--glass-primary-light);
        backdrop-filter: var(--glass-blur);
        border: 1px solid rgba(59, 130, 246, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--glass-primary);
        font-size: 1.75rem;
    }

    .upload-text-glass {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        margin-bottom: 1rem;
    }

    .upload-title-glass {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--glass-text-main);
    }

    .upload-subtitle-glass {
        font-size: 0.8rem;
        color: var(--glass-text-muted);
    }

    .btn-upload-glass {
        display: inline-block;
        padding: 0.625rem 1.5rem;
        background: var(--glass-primary);
        color: white;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-upload-glass:hover {
        background: var(--glass-primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
    }

    /* ==========================================
   COMPANIES SECTION GLASS
   ========================================== */
    .companies-wrapper-glass {
        overflow: hidden;
    }

    .companies-header-glass {
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--glass-border);
    }

    .header-text-glass h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--glass-text-main);
        margin: 0;
    }

    .header-text-glass p {
        font-size: 0.8rem;
        color: var(--glass-text-muted);
        margin: 0;
    }

    .select-all-glass {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--glass-text-main);
    }

    .checkbox-glass {
        width: 20px;
        height: 20px;
        accent-color: var(--glass-primary);
        cursor: pointer;
    }

    .master-upload-glass {
        background: var(--glass-success-light);
        backdrop-filter: var(--glass-blur);
        border-bottom: 1px solid var(--glass-border);
        padding: 1.25rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }

    .master-info-glass {
        display: flex;
        align-items: center;
        gap: 1rem;
        color: var(--glass-text-main);
    }

    .master-info-glass i {
        font-size: 1.75rem;
        color: var(--glass-success);
    }

    .master-info-glass strong {
        font-size: 0.95rem;
        display: block;
    }

    .master-info-glass small {
        font-size: 0.8rem;
        color: var(--glass-text-muted);
    }

    .btn-master-upload-glass {
        background: var(--glass-success);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-master-upload-glass:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
    }

    .companies-grid-glass {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1.25rem;
        padding: 1.5rem;
        max-height: 450px;
        overflow-y: auto;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 16px;
    }

    .cia-card-glass {
        background: #ffffff;
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.25rem;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        position: relative;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .cia-card-glass:hover {
        border-color: #cbd5e1;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .cia-card-glass.active {
        border-color: #ef4444;
        background-color: #ffffff;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.1);
    }

    .cia-card-glass.active::after {
        content: '\F26E';
        /* Bootstrap bi-check-circle-fill */
        font-family: 'bootstrap-icons';
        position: absolute;
        top: -10px;
        right: -10px;
        background: #ef4444;
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        border: 2px solid white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        z-index: 10;
    }

    .cia-main-content-glass {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .cia-main-content-glass .checkbox-glass {
        width: 1.2rem;
        height: 1.2rem;
        accent-color: #ef4444;
    }

    .cia-main-content-glass label {
        font-size: 0.9rem;
        font-weight: 700;
        color: #334155;
        cursor: pointer;
        flex: 1;
        letter-spacing: -0.2px;
    }

    .cia-status-glass {
        display: none;
        justify-content: space-between;
        align-items: center;
        padding-top: 0.75rem;
        border-top: 1px solid #e2e8f0;
    }

    .cia-card-glass.active .cia-status-glass {
        display: flex;
    }

    .status-text-glass {
        font-size: 0.7rem;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
    }

    .btn-change-glass {
        color: #ef4444;
        cursor: pointer;
        background: #fef2f2;
        padding: 4px;
        border-radius: 4px;
        transition: all 0.2s;
    }

    .btn-change-glass:hover {
        background: #fee2e2;
    }

    padding: 4px 8px;
    border-radius: 6px;
    background: rgba(255, 255, 255, 0.05);
    transition: all 0.2s;
    }

    .btn-change-glass:hover {
        color: var(--glass-primary);
        background: var(--glass-primary-light);
    }

    /* ==========================================
   ACTION BUTTONS GLASS
   ========================================== */
    .actions-panel-glass {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }

    .btn-submit-glass {
        background: var(--glass-primary);
        color: white;
        border: none;
        padding: 0.875rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-submit-glass:hover {
        background: var(--glass-primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(59, 130, 246, 0.5);
    }

    .btn-submit-glass:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .btn-cancel-glass {
        background: var(--glass-bg-input);
        backdrop-filter: var(--glass-blur);
        color: var(--glass-text-main);
        border: 1px solid var(--glass-border);
        padding: 0.875rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-cancel-glass:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateY(-2px);
    }

    /* ==========================================
   UTILITIES
   ========================================== */
    .form-hidden {
        display: none;
    }

    .file-input-hidden {
        display: none;
    }

    .mt-3 {
        margin-top: 1rem;
    }

    .mb-0 {
        margin-bottom: 0;
    }

    /* ==========================================
   ANIMATIONS
   ========================================== */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ==========================================
   RESPONSIVE
   ========================================== */
    @media (max-width: 992px) {
        .dashboard-container.mode-expanded .grid-wrapper {
            grid-template-columns: 1fr;
        }

        .dashboard-container.mode-hero .grid-wrapper {
            flex-direction: column;
            text-align: center;
        }

        .hero-stats-glass {
            justify-content: center;
        }

        .grid-glass-2 {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .hero-title-glass {
            font-size: 2rem;
        }

        .companies-grid-glass {
            grid-template-columns: 1fr;
        }

        .dashboard-header {
            flex-direction: column;
            gap: 1rem;
        }
    }

    /* ==========================================
   SCROLLBAR GLASS
   ========================================== */
    .companies-grid-glass::-webkit-scrollbar {
        width: 8px;
    }

    .companies-grid-glass::-webkit-scrollbar-track {
        background: var(--glass-bg-input);
        border-radius: 4px;
    }

    .companies-grid-glass::-webkit-scrollbar-thumb {
        background: var(--glass-border);
        border-radius: 4px;
    }

    .companies-grid-glass::-webkit-scrollbar-thumb:hover {
        background: var(--glass-primary);
    }
</style>

<script>
    // Se mantiene toda la lógica JavaScript original
    const regimenesFiscales = <?= json_encode($regimenes) ?>;
    let rfcValidationTimeout = null;
    let rfcDuplicado = false;

    function checkRFCLogic(isValid) {
        const grid = document.querySelector('.dashboard-container');
        const colDerecha = document.getElementById('columnaDerecha');
        const formCompleto = document.getElementById('formularioCompleto');
        const itemsHero = grid.querySelector('.hero-welcome-text');
        const pageHeader = document.querySelector('.dashboard-header');

        if (isValid) {
            grid.classList.remove('mode-hero');
            grid.classList.add('mode-expanded');

            if (itemsHero) itemsHero.classList.add('form-hidden');
            if (pageHeader) pageHeader.classList.remove('form-hidden');

            colDerecha.classList.remove('form-hidden');
            formCompleto.classList.remove('form-hidden');

            colDerecha.style.opacity = 0;
            colDerecha.style.transform = 'translateY(20px)';
            setTimeout(() => {
                colDerecha.style.transition = 'all 0.5s ease';
                colDerecha.style.opacity = 1;
                colDerecha.style.transform = 'translateY(0)';
            }, 100);

        } else {
            grid.classList.remove('mode-expanded');
            grid.classList.add('mode-hero');

            if (itemsHero) itemsHero.classList.remove('form-hidden');
            if (pageHeader) pageHeader.classList.add('form-hidden');

            colDerecha.classList.add('form-hidden');
            formCompleto.classList.add('form-hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const grid = document.querySelector('.dashboard-container');
        if (grid) {
            grid.classList.add('mode-hero');
        }
    });

    const selectAllCias = document.getElementById('selectAllCias');
    if (selectAllCias) {
        selectAllCias.addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('.chk-cia');
            checkboxes.forEach(cb => {
                toggleCiaCard(cb.id.replace('cia_', ''), this.checked);
            });
        });
    }

    function toggleCiaCard(id, forceValue = null) {
        const checkbox = document.getElementById('cia_' + id);

        if (forceValue !== null) {
            checkbox.checked = forceValue;
        } else if (typeof event !== 'undefined' && event.target !== checkbox) {
            checkbox.checked = !checkbox.checked;
        }

        const card = document.querySelector(`.cia-card-glass[data-id="${id}"]`);

        if (checkbox.checked) {
            card.classList.add('active');
            aplicarMaestroAUno(id);
        } else {
            card.classList.remove('active');
        }
    }

    function aplicarArchivoMaestro() {
        const inputMaestro = document.getElementById('archivo_maestro');
        const masterFileName = document.getElementById('masterFileName');
        const tieneArchivo = inputMaestro.files.length > 0;

        if (tieneArchivo) {
            masterFileName.textContent = inputMaestro.files[0].name;
            masterFileName.style.fontWeight = 'bold';
        } else {
            masterFileName.textContent = 'Elegir archivo';
        }

        document.querySelectorAll('.chk-cia:checked').forEach(chk => {
            const id = chk.id.replace('cia_', '');
            actualizarVisual(id, tieneArchivo, tieneArchivo ? 'Usando General' : 'Esperando archivo...');
        });
    }

    function aplicarMaestroAUno(id) {
        const inputMaestro = document.getElementById('archivo_maestro');
        const tieneArchivo = inputMaestro.files.length > 0;
        const inputPropio = document.querySelector(`input[name="Cias[${id}][archivo_propio]"]`);
        if (!inputPropio.files.length) {
            actualizarVisual(id, tieneArchivo, tieneArchivo ? 'Usando General' : 'Esperando archivo...');
        }
    }

    function actualizarArchivoIndividual(input, id) {
        if (input.files.length > 0) {
            actualizarVisual(id, true, input.files[0].name);
        } else {
            aplicarMaestroAUno(id);
        }
    }

    function actualizarVisual(id, activo, texto) {
        const box = document.getElementById('status_cia_' + id);
        const label = box.querySelector('.status-text-glass');

        label.textContent = texto;
        if (activo) {
            label.style.color = 'var(--glass-success)';
            label.style.fontWeight = '600';
        } else {
            label.style.color = 'var(--glass-text-muted)';
            label.style.fontWeight = 'normal';
        }
    }

    function updateFileNameCompact(input, spanId) {
        const span = document.getElementById(spanId);
        if (input.files.length > 0) {
            span.textContent = input.files[0].name;
            span.style.color = 'var(--glass-primary)';
            span.style.fontWeight = '600';
        }
    }

    document.getElementById('RFC').addEventListener('input', function () {
        this.value = this.value.toUpperCase().replace(/[^A-Z0-9Ñ&]/g, '');
        const rfc = this.value;
        const feedback = document.getElementById('rfcFeedback');
        const loader = document.getElementById('rfcLoader');

        if (rfcValidationTimeout) clearTimeout(rfcValidationTimeout);
        rfcDuplicado = false;
        feedback.innerHTML = '';
        loader.style.display = 'none';

        if (rfc.length < 12) {
            if (!document.getElementById('EsGenerico')?.checked) {
                checkRFCLogic(false);
            }
            return;
        }

        loader.style.display = 'block';

        rfcValidationTimeout = setTimeout(() => {
            fetch(`<?= BASE_URL ?>api/validarRfc/${encodeURIComponent(rfc)}`)
                .then(response => response.json())
                .then(data => {
                    loader.style.display = 'none';
                    if (data.existe) {
                        rfcDuplicado = true;
                        checkRFCLogic(false);
                        feedback.innerHTML = `<span style="color:var(--glass-danger)">RFC Registrado: ${data.codigo}</span>`;
                    } else {
                        feedback.innerHTML = `<span style="color:var(--glass-success)">RFC Disponible</span>`;
                        checkRFCLogic(true);
                        configurarTipoPersona(rfc.length);
                    }
                })
                .catch(err => {
                    console.error(err);
                    loader.style.display = 'none';
                });
        }, 800);
    });

    function configurarTipoPersona(length) {
        const tipoInput = document.getElementById('TipoPersona');
        const moral = document.getElementById('camposMoral');
        const fisica = document.getElementById('camposFisica');

        if (length === 12) {
            tipoInput.value = 'MORAL';
            moral.style.display = 'block';
            fisica.style.display = 'none';
            toggleRequired('camposMoral', true);
            toggleRequired('camposFisica', false);
            filtrarRegimenes('MORAL');
        } else {
            tipoInput.value = 'FISICA';
            moral.style.display = 'none';
            fisica.style.display = 'block';
            toggleRequired('camposMoral', false);
            toggleRequired('camposFisica', true);
            filtrarRegimenes('FISICA');
        }
    }

    function toggleRequired(containerId, isRequired) {
        const container = document.getElementById(containerId);
        container.querySelectorAll('input').forEach(input => {
            const label = input.closest('.form-group').querySelector('label');
            if (label && label.innerHTML.includes('*')) input.required = isRequired;
        });
    }

    function filtrarRegimenes(tipoPersona) {
        const select = document.getElementById('RegimenFiscalId');
        select.innerHTML = '<option value="">Seleccione...</option>';
        const codigosFisica = ['605', '606', '608', '611', '612', '614', '615', '621', '625', '629', '630'];

        regimenesFiscales.forEach(reg => {
            const codigo = String(reg.clave || reg.Clave || reg.codigosat || reg.CodigoSAT);
            const descripcion = reg.descripcion || reg.Descripcion;
            const id = reg.id || reg.Id;
            const tipoRegimenBD = reg.tipopersona || reg.TipoPersona;
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
                    if (!codigosFisica.includes(codigo)) mostrar = true;
                    if (codigo === '601') mostrar = true;
                }
            }

            if (mostrar) {
                const option = document.createElement('option');
                option.value = id;
                option.textContent = `${codigo} - ${descripcion}`;
                select.appendChild(option);
            }
        });
    }

    document.getElementById('formProveedor').addEventListener('submit', function (e) {
        const btn = document.getElementById('btnGuardar');
        btn.disabled = true;
        btn.innerHTML = 'Procesando...';
    });

    const esGenericoCheck = document.getElementById('EsGenerico');
    if (esGenericoCheck) {
        esGenericoCheck.addEventListener('change', function () {
            const rfcInput = document.getElementById('RFC');
            const feedback = document.getElementById('rfcFeedback');
            const tipoManual = document.getElementById('tipoPersonaGenerico');
            const tipoSelect = document.getElementById('TipoPersonaManual');

            if (this.checked) {
                rfcInput.value = 'GENERICO-AUTO';
                rfcInput.readOnly = true;
                rfcInput.required = false;
                feedback.innerHTML = '<span style="color:var(--glass-primary); font-weight: 600;">Se generará un RFC automático al guardar</span>';
                tipoManual.classList.remove('form-hidden');

                tipoSelect.dispatchEvent(new Event('change'));
                checkRFCLogic(true);
            } else {
                rfcInput.value = '';
                rfcInput.readOnly = false;
                rfcInput.required = true;
                feedback.innerHTML = '';
                tipoManual.classList.add('form-hidden');
                checkRFCLogic(false);
            }
        });

        document.getElementById('TipoPersonaManual').addEventListener('change', function () {
            if (esGenericoCheck.checked) {
                const isMoral = this.value === 'MORAL';
                const camposMoral = document.getElementById('camposMoral');
                const camposFisica = document.getElementById('camposFisica');

                if (isMoral) {
                    camposMoral.style.display = 'block';
                    camposFisica.style.display = 'none';
                } else {
                    camposMoral.style.display = 'none';
                    camposFisica.style.display = 'block';
                }
            }
        });
    }

    function toggleRequeridoDoc(check, inputId) {
        const input = document.getElementById(inputId);
        if (check.checked) {
            input.required = false;
        } else {
            input.required = true;
        }
    }
    /**
     * Formatear input como moneda
     */
    function formatCurrency(input) {
        let value = input.value.replace(/[^\d.]/g, '');
        if (value) {
            let parts = value.split('.');
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            if (parts.length > 2) parts.pop();
            input.value = parts.join('.');
        }
    }
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>