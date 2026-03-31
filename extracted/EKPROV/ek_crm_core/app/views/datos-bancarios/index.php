<?php
$pagina_actual = 'proveedores';
$titulo = 'Cuentas Bancarias - ' . e($proveedor['RazonSocial']);
require_once VIEWS_PATH . 'layouts/header.php';

// Validar que $cuentas sea un array
$cuentas = is_array($cuentas) ? $cuentas : [];

// Agrupar cuentas por CLABE
$cuentasAgrupadas = [];
foreach ($cuentas as $cuenta) {
    $claveUnica = !empty($cuenta['Clabe']) ? $cuenta['Clabe'] : (!empty($cuenta['Cuenta']) ? $cuenta['Cuenta'] : 'uniq_' . $cuenta['Id']);
    $claveUnica = preg_replace('/[^a-zA-Z0-9]/', '', $claveUnica);

    if (!isset($cuentasAgrupadas[$claveUnica])) {
        $cuentasAgrupadas[$claveUnica] = $cuenta;
        $cuentasAgrupadas[$claveUnica]['CiasAsignadas'] = [];
        $cuentasAgrupadas[$claveUnica]['Ids'] = [];
    }

    if (!empty($cuenta['CiaNombre'])) {
        $cuentasAgrupadas[$claveUnica]['CiasAsignadas'][] = $cuenta['CiaNombre'];
    }
    $cuentasAgrupadas[$claveUnica]['Ids'][] = $cuenta['Id'];
}

// Lógica de nombre para el header
$tipoPersona = strtoupper($proveedor['TipoPersona'] ?? '');
$nombreCompleto = trim(($proveedor['Nombre'] ?? '') . ' ' . ($proveedor['ApellidoPaterno'] ?? '') . ' ' . ($proveedor['ApellidoMaterno'] ?? ''));
$displayName = ($tipoPersona === 'FISICA' && !empty($nombreCompleto)) ? $nombreCompleto : $proveedor['RazonSocial'];
if (empty($displayName))
    $displayName = $nombreCompleto ?: $proveedor['RazonSocial'] ?: 'SIN NOMBRE';
?>

<!-- Animated Background -->
<div class="animated-background">
    <div class="gradient-orb orb-1"></div>
    <div class="gradient-orb orb-2"></div>
    <div class="gradient-orb orb-3"></div>
</div>

<div class="bank-accounts-page">
    <!-- Hero Header Glass -->
    <div class="page-hero-glass">
        <div class="hero-content-glass">
            <div class="hero-left-glass">
                <a href="<?= BASE_URL ?>proveedores/ver/<?= $proveedor['Id'] ?>" class="btn-back-glass">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="hero-info-glass">
                    <div class="hero-tag-glass">
                        <i class="bi bi-bank2"></i>
                        <span>Gestión Bancaria</span>
                    </div>
                    <h1 class="hero-title-glass">Cuentas Bancarias</h1>
                    <p class="hero-subtitle-glass">
                        <i class="bi bi-building"></i>
                        <span class="fw-bold">
                            <?= e($displayName) ?>
                        </span>
                        <?php if (!empty($proveedor['IdManual'])): ?>
                            <span class="hero-id-tag-glass">
                                <i class="bi bi-hash"></i>
                                <?= e($proveedor['IdManual']) ?>
                            </span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            <div class="hero-right-glass">
                <?php if (puedeCrear('proveedores')): ?>
                    <a href="<?= BASE_URL ?>datos-bancarios/crear/<?= $proveedor['Id'] ?>" class="btn-hero-glass">
                        <i class="bi bi-plus-circle-fill"></i>
                        <span>Nueva Cuenta</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if (empty($cuentasAgrupadas)): ?>
        <!-- Estado Vacío Glass -->
        <div class="empty-state-glass">
            <div class="empty-icon-glass">
                <i class="bi bi-bank"></i>
            </div>
            <h3 class="empty-title-glass">No hay cuentas bancarias</h3>
            <p class="empty-text-glass">Este proveedor aún no tiene información bancaria registrada.</p>
            <?php if (puedeCrear('proveedores')): ?>
                <a href="<?= BASE_URL ?>datos-bancarios/crear/<?= $proveedor['Id'] ?>" class="btn-primary-glass-lg">
                    <i class="bi bi-plus-lg"></i>
                    Registrar Primera Cuenta
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <!-- Grid de Tarjetas Bancarias Premium -->
        <div class="bank-grid-glass">
            <?php foreach ($cuentasAgrupadas as $clave => $cuenta): ?>
                <?php
                $estatus = strtoupper($cuenta['Estatus']);
                $cardClass = match ($estatus) {
                    'APROBADO' => 'approved',
                    'PENDIENTE' => 'pending',
                    'RECHAZADO' => 'rejected',
                    default => ''
                };
                if ($cuenta['EsPrincipal'])
                    $cardClass .= ' is-principal';
                ?>
                <div class="bank-card-container">
                    <!-- Tarjeta Visual estilo Crédito (Minimalist Blueprint) -->
                    <div class="credit-card-elegant <?= $cardClass ?>">
                        <div class="card-top-row">
                            <div class="bank-brand">
                                <span class="label-mini">Institución Bancaria</span>
                                <span class="bank-brand-name"><?= e($cuenta['BancoNombre'] ?? 'Banco Nacional') ?></span>
                            </div>
                            <div class="badges-section-glass"
                                style="margin: 0; display: flex; gap: 0.5rem; align-items: center;">
                                <?php if ($cuenta['EsPrincipal']): ?>
                                    <span
                                        style="background: #ef4444; color: white; padding: 4px 10px; border-radius: 6px; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; display: flex; align-items: center; gap: 4px; box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2);">
                                        <i class="bi bi-star-fill"></i> Principal
                                    </span>
                                <?php endif; ?>
                                <span
                                    style="background: #f8fafc; color: #ef4444; border: 1.5px solid #ef4444; padding: 3px 10px; border-radius: 6px; font-size: 0.65rem; font-weight: 800; text-transform: uppercase;">
                                    <?= e($cuenta['Estatus']) ?>
                                </span>
                            </div>
                        </div>

                        <div class="d-flex align-center">
                            <div class="card-chip"></div>
                            <div class="contactless-icon" style="color: #94a3b8;">
                                <i class="bi bi-wifi"></i>
                            </div>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 0.5rem; margin: 1rem 0;">
                            <?php if (!empty($cuenta['Cuenta'])): ?>
                                <div style="display: flex; flex-direction: column; gap: 2px;">
                                    <span class="label-mini">No. Cuenta</span>
                                    <div class="card-number-display" style="font-size: 1.1rem; padding: 0.4rem 0.6rem; margin: 0;" title="Haz doble clic para seleccionar">
                                        <?= e($cuenta['Cuenta']) ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div style="display: flex; flex-direction: column; gap: 2px;">
                                <span class="label-mini">CLABE Interbancaria</span>
                                <div class="card-number-display" style="margin: 0;" title="Haz doble clic para seleccionar">
                                    <?php
                                    $num = !empty($cuenta['Clabe']) ? $cuenta['Clabe'] : '0000000000000000';
                                    echo e($num);
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="card-bottom-row">
                            <div class="card-holder" style="max-width: 60%;">
                                <span class="label-mini">Titular / Proveedor</span>
                                <span class="holder-name d-block text-truncate"><?= e($displayName) ?></span>
                            </div>
                            <div class="card-meta">
                                <div class="meta-item">
                                    <span class="label-mini">Sucursal</span>
                                    <span class="holder-name"><?= e($cuenta['Sucursal'] ?: '000') ?></span>
                                </div>
                                <div class="meta-item">
                                    <span class="label-mini">Plaza</span>
                                    <span class="holder-name"><?= e($cuenta['Plaza'] ?: '000') ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Overlay de Compañías -->
                    <div class="card-companies-overlay">
                        <span class="label-mini" style="width: 100%; color: var(--glass-text-light); margin-bottom: 5px;">
                            <i class="bi bi-building"></i> Compañías Vinculadas
                        </span>
                        <?php
                        $ciasUnicas = array_unique($cuenta['CiasAsignadas']);
                        foreach ($ciasUnicas as $cia): ?>
                            <span class="company-tag-glass"><?= e($cia) ?></span>
                        <?php endforeach; ?>
                    </div>

                    <!-- Dock de Gestión -->
                    <div class="management-dock">
                        <?php if ($cuenta['RutaCaratula']): ?>
                            <button
                                onclick="verDocumento('<?= BASE_URL ?>datos-bancarios/verArchivo/caratula/<?= $cuenta['Id'] ?>', 'Carátula')"
                                class="btn-dock">
                                <i class="bi bi-file-earmark-pdf"></i> Ver PDF
                            </button>
                        <?php endif; ?>

                        <a href="<?= BASE_URL ?>datos-bancarios/historial/<?= $cuenta['Id'] ?>" class="btn-dock">
                            <i class="bi bi-clock-history"></i> Log
                        </a>

                        <?php if (esAdmin() || puedeEditar('proveedores')): ?>
                            <?php if ($cuenta['Estatus'] === 'PENDIENTE' && esAdmin()): ?>
                                <a href="<?= BASE_URL ?>datos-bancarios/editar/<?= $cuenta['Id'] ?>?aprobar=1" class="btn-dock"
                                    style="background: var(--glass-success); color: white; border: none;">
                                    <i class="bi bi-check-lg"></i> Aprobar
                                </a>
                                <button onclick="mostrarModalRechazo(<?= $cuenta['Id'] ?>)" class="btn-dock btn-dock-danger"
                                    style="background: var(--glass-danger); color: white; border: none;">
                                    <i class="bi bi-x-lg"></i> Rechazar
                                </button>
                            <?php endif; ?>

                            <a href="<?= BASE_URL ?>datos-bancarios/editar/<?= $cuenta['Id'] ?>" class="btn-dock">
                                <i class="bi bi-pencil"></i> Editar
                            </a>

                            <?php if (esAdmin() && $cuenta['Estatus'] === 'APROBADO' && !$cuenta['EsPrincipal']): ?>
                                <button onclick="confirmarPrincipal(<?= $cuenta['Id'] ?>)" class="btn-dock" style="color: #b8860b;">
                                    <i class="bi bi-star-fill"></i> Principal
                                </button>
                            <?php endif; ?>

                            <?php if (esAdmin()): ?>
                                <button onclick="mostrarModalDesactivar(<?= $cuenta['Id'] ?>)" class="btn-dock btn-dock-danger"
                                    style="color: var(--glass-danger);">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Formularios Ocultos -->
<?php foreach ($cuentas as $c): ?>
    <form id="formPrincipal<?= $c['Id'] ?>" method="POST"
        action="<?= BASE_URL ?>datos-bancarios/establecerPrincipal/<?= $c['Id'] ?>" style="display:none;">
        <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">
    </form>
<?php endforeach; ?>

<!-- Modal Rechazo Glass -->
<div id="modalRechazo" class="modal-overlay-glass">
    <div class="modal-content-glass modal-danger-glass">
        <div class="modal-header-glass">
            <i class="bi bi-x-circle-fill"></i>
            <h3>Rechazar Cuenta</h3>
        </div>
        <form method="POST" id="formRechazo">
            <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">
            <div class="modal-body-glass">
                <div class="form-group-glass">
                    <label class="form-label-glass">Motivo del rechazo <span class="required-glass">*</span></label>
                    <textarea name="motivo" class="form-control-glass" rows="4" required
                        placeholder="Explique la razón del rechazo..."></textarea>
                </div>
            </div>
            <div class="modal-footer-glass">
                <button type="button" class="btn-secondary-glass"
                    onclick="cerrarModal('modalRechazo')">Cancelar</button>
                <button type="submit" class="btn-danger-glass">Confirmar Rechazo</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Desactivar Glass -->
<div id="modalDesactivar" class="modal-overlay-glass">
    <div class="modal-content-glass modal-warning-glass">
        <div class="modal-header-glass">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <h3>Desactivar Cuenta</h3>
        </div>
        <form method="POST" id="formDesactivar">
            <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">
            <div class="modal-body-glass">
                <div class="form-group-glass">
                    <label class="form-label-glass">Motivo (Opcional)</label>
                    <textarea name="motivo" class="form-control-glass" rows="4"
                        placeholder="Razón de la desactivación..."></textarea>
                </div>
            </div>
            <div class="modal-footer-glass">
                <button type="button" class="btn-secondary-glass"
                    onclick="cerrarModal('modalDesactivar')">Cancelar</button>
                <button type="submit" class="btn-danger-glass">Desactivar</button>
            </div>
        </form>
    </div>
</div>

<style>
    /* ==========================================
   GLASSMORPHISM THEME - CUENTAS BANCARIAS
   ========================================== */

    :root {
        /* Colores Base Azules */
        --glass-primary: #3b82f6;
        --glass-primary-dark: #2563eb;
        --glass-primary-light: rgba(59, 130, 246, 0.15);
        --glass-success: #10b981;
        --glass-warning: #f59e0b;
        --glass-danger: #ef4444;
        --glass-purple: #8b5cf6;
        --glass-yellow: #fbbf24;

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
        --glass-radius: 20px;
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
        --glass-blur: blur(30px);
        --glass-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.5);
        --glass-shadow-hover: 0 12px 40px 0 rgba(0, 0, 0, 0.6);
    }

    /* ==========================================
   ANIMATED BACKGROUND
   ========================================== */
    .animated-background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #e0f2fe 0%, #bfdbfe 50%, #dbeafe 100%);
        z-index: -1;
        overflow: hidden;
    }

    body.dark-mode .animated-background {
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
        animation-delay: 0s;
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

    /* ==========================================
   LAYOUT
   ========================================== */
    .bank-accounts-page {
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

    /* ==========================================
   HERO HEADER GLASS
   ========================================== */
    .page-hero-glass {
        background: var(--glass-bg-card);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        border-radius: var(--glass-radius);
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--glass-shadow), inset 0 1px 0 0 rgba(255, 255, 255, 0.3);
        animation: slideUp 0.6s ease-out;
        position: relative;
        overflow: hidden;
    }

    .page-hero-glass::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
    }

    body.dark-mode .page-hero-glass::before {
        background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.3), transparent);
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

    .hero-content-glass {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 2rem;
    }

    .hero-left-glass {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex: 1;
    }

    .btn-back-glass {
        width: 48px;
        height: 48px;
        background: var(--glass-bg-input);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
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

    .btn-back-glass:hover {
        background: var(--glass-primary-light);
        transform: translateX(-5px);
        color: var(--glass-text-main);
    }

    .hero-info-glass {
        flex: 1;
    }

    .hero-tag-glass {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--glass-bg-card);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        padding: 0.5rem 1.25rem;
        border-radius: 50px;
        color: var(--glass-text-main);
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
        box-shadow: var(--glass-shadow), inset 0 1px 0 0 rgba(255, 255, 255, 0.3);
    }

    .hero-title-glass {
        font-size: 2rem;
        font-weight: 800;
        color: var(--glass-text-main);
        margin: 0 0 0.5rem 0;
        line-height: 1.2;
    }

    .hero-subtitle-glass {
        color: var(--glass-text-muted);
        font-size: 1.1rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .hero-id-tag-glass {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: var(--glass-bg-input);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--glass-text-main);
        margin-left: 0.5rem;
    }

    .btn-hero-glass {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: var(--glass-primary);
        color: white;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
        transition: all 0.3s ease;
        border: none;
    }

    .btn-hero-glass:hover {
        background: var(--glass-primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(59, 130, 246, 0.5);
        color: white;
    }

    /* ==========================================
   EMPTY STATE GLASS
   ========================================== */
    .empty-state-glass {
        background: var(--glass-bg-card);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        border-radius: var(--glass-radius);
        padding: 4rem 2rem;
        text-align: center;
        box-shadow: var(--glass-shadow), inset 0 1px 0 0 rgba(255, 255, 255, 0.3);
        animation: slideUp 0.6s ease-out 0.2s both;
    }

    .empty-icon-glass {
        width: 120px;
        height: 120px;
        background: var(--glass-primary-light);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid rgba(59, 130, 246, 0.3);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 3.5rem;
        color: var(--glass-primary);
    }

    .empty-title-glass {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--glass-text-main);
        margin: 0 0 0.5rem 0;
    }

    .empty-text-glass {
        color: var(--glass-text-muted);
        font-size: 1rem;
        margin: 0 0 2rem 0;
    }

    .btn-primary-glass-lg {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 2rem;
        background: var(--glass-primary);
        color: white;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
        transition: all 0.3s ease;
        border: none;
    }

    .btn-primary-glass-lg:hover {
        background: var(--glass-primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(59, 130, 246, 0.5);
        color: white;
    }

    /* ==========================================
   BANK GRID & MINIMALIST BLUEPRINT CARDS
   ========================================== */
    .bank-grid-glass {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(420px, 1fr));
        gap: 2.5rem;
        padding: 1rem 0;
    }

    /* Estilo Base de la Tarjeta */
    .bank-card-container {
        perspective: 1000px;
    }

    /* Estilo "Dibujo" / Minimalist Blueprint */
    .credit-card-elegant {
        position: relative;
        width: 100%;
        min-height: 250px;
        border-radius: 16px;
        padding: 1.75rem;
        background: #ffffff;
        color: #334155;
        border: 2px solid #e2e8f0;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    body.dark-mode .credit-card-elegant {
        background: #1e293b;
        color: #f8fafc;
        border-color: #334155;
    }

    .credit-card-elegant:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border-color: var(--glass-primary);
    }

    /* Variantes de Borde según Estatus */
    .credit-card-elegant.is-principal {
        border-color: #ef4444;
        border-width: 2px;
    }

    .credit-card-elegant.approved {
        border-top: 4px solid #ef4444;
    }

    .credit-card-elegant.pending {
        border-top: 4px solid #f97316;
    }

    .credit-card-elegant.rejected {
        border-top: 4px solid #b91c1c;
    }

    /* Elementos Visuales */
    .card-top-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        z-index: 2;
    }

    .bank-brand {
        display: flex;
        flex-direction: column;
    }

    .bank-brand-name {
        font-size: 1.25rem;
        font-weight: 800;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #1e293b;
    }

    body.dark-mode .bank-brand-name {
        color: #f8fafc;
    }

    /* Chip Estilo "Dibujo" Lineal */
    .card-chip {
        width: 45px;
        height: 34px;
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        position: relative;
        margin-top: 0.75rem;
        overflow: hidden;
    }

    body.dark-mode .card-chip {
        background: #334155;
        border-color: #475569;
    }

    .card-chip::before,
    .card-chip::after {
        content: '';
        position: absolute;
        background: #cbd5e1;
    }

    body.dark-mode .card-chip::before,
    body.dark-mode .card-chip::after {
        background: #475569;
    }

    .card-chip::before {
        width: 100%;
        height: 1px;
        top: 50%;
    }

    .card-chip::after {
        width: 1px;
        height: 100%;
        left: 50%;
    }

    .contactless-icon {
        font-size: 1.5rem;
        opacity: 0.8;
        transform: rotate(90deg);
        margin-top: 1.2rem;
        margin-left: 0.5rem;
    }

    .card-number-display {
        margin: 1.25rem 0;
        font-family: 'Courier New', Courier, monospace;
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: 1px;
        color: #475569;
        background: #f8fafc;
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        display: inline-block;
        border: 1px dashed #cbd5e1;
    }

    body.dark-mode .card-number-display {
        color: #cbd5e1;
        background: #0f172a;
        border-color: #334155;
    }

    .card-bottom-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        z-index: 2;
    }

    .card-holder {
        display: flex;
        flex-direction: column;
    }

    .label-mini {
        font-size: 0.6rem;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .holder-name {
        font-size: 0.95rem;
        font-weight: 600;
        color: #334155;
    }

    body.dark-mode .holder-name {
        color: #cbd5e1;
    }

    .card-meta {
        display: flex;
        gap: 1.5rem;
    }

    .meta-item {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }

    /* Sección de Compañías (Fuera de la parte "visual" pero parte del bloque) */
    .card-companies-overlay {
        margin-top: -10px;
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-top: none;
        border-radius: 0 0 16px 16px;
        padding: 1.5rem 1.25rem 0.75rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        z-index: 1;
    }

    body.dark-mode .card-companies-overlay {
        background: #0f172a;
        border-color: #334155;
    }

    /* Botones de Gestión */
    .management-dock {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-dock {
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-size: 0.75rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        text-transform: uppercase;
        transition: all 0.2s;
        border: 1px solid #e2e8f0;
        background: white;
        color: #475569;
        cursor: pointer;
        text-decoration: none;
    }

    body.dark-mode .btn-dock {
        background: #1e293b;
        border-color: #334155;
        color: #cbd5e1;
    }

    .btn-dock:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
        transform: translateY(-2px);
    }

    body.dark-mode .btn-dock:hover {
        background: #334155;
    }

    .btn-edit {
        background: var(--glass-primary);
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
    }

    .btn-edit:hover {
        background: var(--glass-primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }

    .btn-star {
        background: linear-gradient(135deg, var(--glass-yellow) 0%, var(--glass-warning) 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(251, 191, 36, 0.3);
    }

    .btn-star:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(251, 191, 36, 0.4);
    }

    .btn-danger {
        background: transparent;
        color: var(--glass-danger);
        border: 2px solid var(--glass-border);
        flex: 0 0 auto;
        padding: 0.5rem 0.75rem;
    }

    .btn-danger:hover {
        background: var(--glass-danger);
        color: white;
        border-color: var(--glass-danger);
        transform: translateY(-2px);
    }

    /* ==========================================
   MODALES GLASS
   ========================================== */
    .modal-overlay-glass {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        z-index: 2000;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        animation: fadeIn 0.3s ease-out;
    }

    .modal-overlay-glass.active {
        display: flex;
    }

    .modal-content-glass {
        background: var(--glass-bg-card);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        border-radius: var(--glass-radius);
        box-shadow: var(--glass-shadow);
        max-width: 480px;
        width: 100%;
        overflow: hidden;
        animation: slideUp 0.3s ease-out;
    }

    .modal-header-glass {
        padding: 1.5rem 2rem;
        border-bottom: 2px solid var(--glass-border);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .modal-danger-glass .modal-header-glass {
        background: rgba(239, 68, 68, 0.1);
    }

    .modal-warning-glass .modal-header-glass {
        background: rgba(245, 158, 11, 0.1);
    }

    .modal-header-glass i {
        font-size: 1.75rem;
    }

    .modal-danger-glass .modal-header-glass i {
        color: var(--glass-danger);
    }

    .modal-warning-glass .modal-header-glass i {
        color: var(--glass-warning);
    }

    .modal-header-glass h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--glass-text-main);
        margin: 0;
    }

    .modal-body-glass {
        padding: 2rem;
    }

    .form-group-glass {
        margin-bottom: 0;
    }

    .form-label-glass {
        display: block;
        font-size: 0.875rem;
        font-weight: 700;
        color: var(--glass-text-main);
        margin-bottom: 0.5rem;
    }

    .required-glass {
        color: var(--glass-danger);
    }

    .form-control-glass {
        width: 100%;
        padding: 0.75rem 1rem;
        background: var(--glass-bg-input);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        border-radius: 12px;
        font-size: 0.875rem;
        color: var(--glass-text-main);
        transition: all 0.3s ease;
        resize: vertical;
    }

    .form-control-glass:focus {
        outline: none;
        border-color: var(--glass-primary);
        background: rgba(255, 255, 255, 0.8);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
    }

    body.dark-mode .form-control-glass:focus {
        background: rgba(15, 23, 42, 0.7);
    }

    .modal-footer-glass {
        padding: 1.5rem 2rem;
        background: var(--glass-bg-input);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border-top: 2px solid var(--glass-border);
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    .btn-secondary-glass {
        padding: 0.75rem 1.5rem;
        background: var(--glass-bg-card);
        color: var(--glass-text-muted);
        border: 2px solid var(--glass-border);
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-secondary-glass:hover {
        background: var(--glass-bg);
        color: var(--glass-text-main);
        border-color: var(--glass-primary);
    }

    .btn-danger-glass {
        padding: 0.75rem 1.5rem;
        background: var(--glass-danger);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    }

    .btn-danger-glass:hover {
        background: #dc2626;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    }

    /* ==========================================
   RESPONSIVE
   ========================================== */
    @media (max-width: 768px) {
        .bank-accounts-page {
            padding: 1rem;
        }

        .page-hero-glass {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .hero-content-glass {
            flex-direction: column;
            align-items: flex-start;
        }

        .hero-left-glass {
            width: 100%;
        }

        .hero-title-glass {
            font-size: 1.5rem;
        }

        .hero-right-glass {
            width: 100%;
        }

        .btn-hero-glass {
            width: 100%;
            justify-content: center;
        }

        .bank-grid-glass {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .data-grid-glass {
            grid-template-columns: 1fr;
        }

        .action-row-glass {
            flex-direction: column;
        }

        .btn-action-glass {
            width: 100%;
        }

        .empty-state-glass {
            padding: 3rem 1.5rem;
        }

        .empty-icon-glass {
            width: 100px;
            height: 100px;
            font-size: 3rem;
        }
    }
</style>

<script>
    function verDocumento(url, titulo) {
        if (typeof window.verDocumento === 'function') {
            window.verDocumento(url, titulo);
        } else {
            window.open(url, '_blank');
        }
    }

    function mostrarModalRechazo(id) {
        document.getElementById('formRechazo').action = '<?= BASE_URL ?>datos-bancarios/rechazar/' + id;
        document.getElementById('modalRechazo').classList.add('active');
    }

    function mostrarModalDesactivar(id) {
        document.getElementById('formDesactivar').action = '<?= BASE_URL ?>datos-bancarios/desactivar/' + id;
        document.getElementById('modalDesactivar').classList.add('active');
    }

    function cerrarModal(modalId) {
        document.getElementById(modalId).classList.remove('active');
    }

    document.querySelectorAll('.modal-overlay-glass').forEach(modal => {
        modal.addEventListener('click', function (e) {
            if (e.target === this) {
                this.classList.remove('active');
            }
        });
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay-glass').forEach(modal => {
                modal.classList.remove('active');
            });
        }
    });

    async function confirmarPrincipal(id) {
        const result = await Swal.fire({
            title: '¿Hacer Principal?',
            text: 'Esta será la cuenta predeterminada para esta compañía.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, confirmar',
            cancelButtonText: 'Cancelar',
            customClass: {
                popup: 'swal-custom',
                confirmButton: 'btn-primary-glass-lg',
                cancelButton: 'btn-secondary-glass'
            },
            buttonsStyling: false
        });

        if (result.isConfirmed) {
            document.getElementById('formPrincipal' + id).submit();
        }
    }
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>