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
                            <div class="badges-section-glass bank-badges-row">
                                <?php if ($cuenta['EsPrincipal']): ?>
                                    <span class="badge-principal">
                                        <i class="bi bi-star-fill"></i> Principal
                                    </span>
                                <?php endif; ?>
                                <span class="badge-estatus-outline">
                                    <?= e($cuenta['Estatus']) ?>
                                </span>
                            </div>
                        </div>

                        <div class="d-flex align-center">
                            <div class="card-chip"></div>
                            <div class="contactless-icon contactless-muted">
                                <i class="bi bi-wifi"></i>
                            </div>
                        </div>

                        <div class="card-data-column">
                            <?php if (!empty($cuenta['Cuenta'])): ?>
                                <div class="card-data-item">
                                    <span class="label-mini">No. Cuenta</span>
                                    <div class="card-number-display card-number-compact" title="Haz doble clic para seleccionar">
                                        <?= e($cuenta['Cuenta']) ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="card-data-item">
                                <span class="label-mini">CLABE Interbancaria</span>
                                <div class="card-number-display card-number-no-margin" title="Haz doble clic para seleccionar">
                                    <?php
                                    $num = !empty($cuenta['Clabe']) ? $cuenta['Clabe'] : '0000000000000000';
                                    echo e($num);
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="card-bottom-row">
                            <div class="card-holder card-holder-narrow">
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
                        <span class="label-mini label-mini-full">
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
                                <a href="<?= BASE_URL ?>datos-bancarios/editar/<?= $cuenta['Id'] ?>?aprobar=1" class="btn-dock btn-dock-approve">
                                    <i class="bi bi-check-lg"></i> Aprobar
                                </a>
                                <button onclick="mostrarModalRechazo(<?= $cuenta['Id'] ?>)" class="btn-dock btn-dock-danger btn-dock-reject">
                                    <i class="bi bi-x-lg"></i> Rechazar
                                </button>
                            <?php endif; ?>

                            <a href="<?= BASE_URL ?>datos-bancarios/editar/<?= $cuenta['Id'] ?>" class="btn-dock">
                                <i class="bi bi-pencil"></i> Editar
                            </a>

                            <?php if (esAdmin() && $cuenta['Estatus'] === 'APROBADO' && !$cuenta['EsPrincipal']): ?>
                                <button onclick="confirmarPrincipal(<?= $cuenta['Id'] ?>)" class="btn-dock btn-dock-principal">
                                    <i class="bi bi-star-fill"></i> Principal
                                </button>
                            <?php endif; ?>

                            <?php if (esAdmin()): ?>
                                <button onclick="mostrarModalDesactivar(<?= $cuenta['Id'] ?>)" class="btn-dock btn-dock-danger btn-dock-delete">
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
        action="<?= BASE_URL ?>datos-bancarios/establecerPrincipal/<?= $c['Id'] ?>" class="form-inline-hidden">
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