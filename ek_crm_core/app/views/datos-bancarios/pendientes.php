<?php
$pagina_actual = 'proveedores';
$titulo = 'Cuentas Bancarias Pendientes';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<div class="d-flex justify-between align-center mb-4">
    <div>
        <h1 class="section-title">
            <i class="bi bi-clock-history"></i> Cuentas Pendientes de Aprobación
        </h1>
        <p class="section-subtitle">
            Cuentas bancarias que requieren tu aprobación
        </p>
    </div>
    <a href="<?= BASE_URL ?>proveedores" class="btn btn-glass">
        <i class="bi bi-arrow-left"></i> Volver a Proveedores
    </a>
</div>

<?php if (empty($cuentasPendientes)): ?>
    <div class="glass-panel text-center p-5">
        <i class="bi bi-check-circle text-success icon-xxl"></i>
        <p class="text-muted mt-3 fs-5">
            ¡Excelente! No hay cuentas pendientes de aprobación.
        </p>
    </div>
<?php else: ?>
    <div class="alert alert-warning mb-4">
        <i class="bi bi-exclamation-triangle"></i>
        <span><strong><?= count($cuentasPendientes) ?> cuenta(s)</strong> pendiente(s) de aprobación</span>
    </div>

    <div class="grid-container grid-1col">
        <?php foreach ($cuentasPendientes as $cuenta): ?>
            <div class="glass-panel pendientes-card">
                <div class="d-flex flex-column flex-md-row gap-4 align-items-start">

                    <div class="pendientes-info">
                        <div class="d-flex align-center gap-2 mb-3">
                            <h3 class="mb-0 fw-bold fs-5 text-primary">
                                <?= e($cuenta['RazonSocial']) ?>
                            </h3>
                            <span class="badge badge-warning">
                                <i class="bi bi-clock"></i> Pendiente
                            </span>
                        </div>

                        <div class="mb-3 text-muted small">
                            <strong>RFC:</strong> <?= e($cuenta['RFC']) ?> <span class="mx-2">|</span>
                            <strong>Compañía:</strong> <?= e($cuenta['CiaNombre']) ?>
                        </div>

                        <div class="grid-3 mb-3">
                            <div>
                                <label class="form-label mb-1 text-muted small text-uppercase">Banco</label>
                                <div class="fw-bold"><?= e($cuenta['BancoNombre'] ?? 'No especificado') ?></div>
                            </div>

                            <?php if ($cuenta['Cuenta']): ?>
                                <div>
                                    <label class="form-label mb-1 text-muted small text-uppercase">Cuenta</label>
                                    <div class="font-monospace"><?= e($cuenta['Cuenta']) ?></div>
                                </div>
                            <?php endif; ?>

                            <?php if ($cuenta['Clabe']): ?>
                                <div>
                                    <label class="form-label mb-1 text-muted small text-uppercase">CLABE</label>
                                    <div class="font-monospace"><?= e($cuenta['Clabe']) ?></div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="bg-light p-3 rounded border">
                            <div class="d-flex gap-3 text-muted small">
                                <div><i class="bi bi-person"></i> Creada por:
                                    <strong><?= e($cuenta['CreadoPorNombre'] ?? 'Desconocido') ?></strong></div>
                                <div><i class="bi bi-calendar"></i> Fecha:
                                    <strong><?= formatoFechaHora($cuenta['FechaCreacion']) ?></strong></div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-2 pendientes-actions">
                        <a href="<?= BASE_URL ?>datos-bancarios/editar/<?= $cuenta['Id'] ?>?aprobar=1"
                            class="btn btn-primary w-100">
                            <i class="bi bi-pencil-square"></i> Revisar y Capturar
                        </a>

                        <?php if ($cuenta['RutaCaratula']): ?>
                            <a href="<?= BASE_URL ?>datos-bancarios/verArchivo/caratula/<?= $cuenta['Id'] ?>" target="_blank"
                                class="btn btn-glass w-100">
                                <i class="bi bi-file-pdf"></i> Ver Carátula
                            </a>
                        <?php endif; ?>

                        <div class="border-top my-2"></div>

                        <form method="POST" action="<?= BASE_URL ?>datos-bancarios/aprobar/<?= $cuenta['Id'] ?>"
                            id="formAprobar<?= $cuenta['Id'] ?>">
                            <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">
                            <input type="hidden" name="redirect_to" value="pendientes">
                            <button type="button" class="btn btn-success w-100"
                                onclick="confirmarAprobacion(<?= $cuenta['Id'] ?>)">
                                <i class="bi bi-check-circle"></i> Aprobar Directo
                            </button>
                        </form>

                        <button type="button" class="btn btn-danger w-100"
                            onclick="mostrarModalRechazo(<?= $cuenta['Id'] ?>, '<?= e($cuenta['RazonSocial']) ?>')">
                            <i class="bi bi-x-circle"></i> Rechazar
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<script>
    // Confirmar aprobación directa
    async function confirmarAprobacion(cuentaId) {
        const confirmed = await confirmDialog(
            '¿Aprobar esta cuenta SIN capturar datos?',
            'Si necesitas capturar datos bancarios, usa el botón "Revisar y Capturar"',
            'Sí, aprobar',
            'No'
        );

        if (confirmed) {
            document.getElementById('formAprobar' + cuentaId).submit();
        }
    }

    // Mostrar modal de rechazo con SweetAlert2
    async function mostrarModalRechazo(cuentaId, razonSocial) {
        const { value: motivo } = await Swal.fire({
            title: 'Rechazar Cuenta Bancaria',
            html: `
            <p class="mb-3 text-muted">
                <strong>Proveedor:</strong> ${razonSocial}
            </p>
        `,
            input: 'textarea',
            inputLabel: 'Motivo del rechazo',
            inputPlaceholder: 'Explique claramente por qué se rechaza esta cuenta...',
            inputAttributes: {
                'aria-label': 'Motivo del rechazo',
                rows: 4
            },
            showCancelButton: true,
            confirmButtonText: 'Rechazar Cuenta',
            confirmButtonColor: '#dc3545',
            cancelButtonText: 'Cancelar',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-glass'
            },
            buttonsStyling: false,
            inputValidator: (value) => {
                if (!value) {
                    return 'Debe proporcionar un motivo del rechazo';
                }
            }
        });

        if (motivo) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= BASE_URL ?>datos-bancarios/rechazar/' + cuentaId;

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = 'csrf_token';
            csrfInput.value = '<?= generarToken() ?>';

            const motivoInput = document.createElement('input');
            motivoInput.type = 'hidden';
            motivoInput.name = 'motivo';
            motivoInput.value = motivo;

            const redirectInput = document.createElement('input');
            redirectInput.type = 'hidden';
            redirectInput.name = 'redirect_to';
            redirectInput.value = 'pendientes';

            form.appendChild(csrfInput);
            form.appendChild(motivoInput);
            form.appendChild(redirectInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>