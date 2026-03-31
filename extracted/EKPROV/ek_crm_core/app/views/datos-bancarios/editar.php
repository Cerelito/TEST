<?php
$pagina_actual = 'proveedores';
$titulo = 'Gestionar Cuenta Bancaria';
require_once VIEWS_PATH . 'layouts/header.php';

$es_aprobacion = isset($_GET['aprobar']) && $_GET['aprobar'] === '1' && esAdmin();

// --- LÓGICA DE DETECCIÓN INTELIGENTE ---
$cuentasHermanas = [];
$dbModel = new DatosBancarios();
$todas = $dbModel->getTodasListas($cuenta['ProveedorId']); // Obtenemos todas sin importar estatus

foreach ($todas as $c) {
    if ($c['Id'] == $cuenta['Id'])
        continue;

    $coincideArchivo = (!empty($cuenta['RutaCaratula']) && !empty($c['RutaCaratula']) && $c['RutaCaratula'] === $cuenta['RutaCaratula']);
    $coincideCuenta = (!empty($cuenta['Cuenta']) && !empty($c['Cuenta']) && $c['Cuenta'] === $cuenta['Cuenta'] && $c['BancoId'] == $cuenta['BancoId']);
    $coincideClabe = (!empty($cuenta['Clabe']) && !empty($c['Clabe']) && $c['Clabe'] === $cuenta['Clabe']);

    if ($coincideArchivo || $coincideCuenta || $coincideClabe) {
        $cuentasHermanas[] = [
            'Cia' => $c['CiaNombre'],
            'Estatus' => $c['Estatus']
        ];
    }
}
?>

<style>
    .swal2-backdrop-show {
        backdrop-filter: blur(5px);
    }

    /* Layout principal para que ocupe toda la altura disponible */
    .layout-wrapper {
        display: flex;
        gap: 1.5rem;
        height: calc(100vh - 140px);
        overflow: hidden;
        align-items: stretch;
    }

    /* Estilos del Visor PDF Adaptable */
    .pdf-container-wrapper {
        flex: 1;
        display: flex;
        flex-direction: column;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--border-color);
        background-color: var(--bg-secondary);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .pdf-header-bar {
        padding: 0.75rem 1rem;
        background-color: var(--bg-secondary);
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: var(--text-primary);
        flex-shrink: 0;
    }

    /* Fondo del PDF */
    .pdf-content-area {
        flex-grow: 1;
        position: relative;
        background-color: #525659;
        /* Gris oscuro estándar de visores */
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
    }

    /* El iframe ocupa todo y muestra sus propios controles */
    .pdf-iframe {
        width: 100%;
        height: 100%;
        border: none;
        display: block;
    }
</style>

<div class="d-flex justify-between align-center mb-4">
    <div>
        <h1 class="section-title">
            <i class="bi bi-pencil-square"></i> <?= $es_aprobacion ? 'Revisar y Aprobar' : 'Editar Cuenta' ?>
        </h1>
        <p class="section-subtitle">
            <?= e($cuenta['RazonSocial']) ?>
        </p>
    </div>
    <a href="<?= BASE_URL ?>datos-bancarios/index/<?= $cuenta['ProveedorId'] ?>" class="btn btn-glass">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="layout-wrapper">

    <div style="flex: 1; padding-right: 5px; overflow-y: auto;" class="hide-scrollbar">

        <form method="POST" action="<?= BASE_URL ?>datos-bancarios/actualizar/<?= $cuenta['Id'] ?>"
            enctype="multipart/form-data" id="formCuenta">
            <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">

            <?php if ($es_aprobacion): ?>
                <input type="hidden" name="aprobar_despues" value="1">
            <?php endif; ?>

            <!-- Grid de Selección de Compañías -->
            <div class="glass-panel mb-4">
                <div class="d-flex justify-between align-center mb-3">
                    <h2 class="card-title mb-0"><i class="bi bi-building-gear"></i> Asignación de Compañías</h2>
                </div>

                <div class="form-group">
                    <label class="form-label mb-3">Esta cuenta bancaria está asignada a las marcadas. Puede agregarla a nuevas marcándolas:</label>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAllCias">
                            <label class="form-check-label fw-bold" for="selectAllCias">
                                Seleccionar restantes
                            </label>
                        </div>
                    </div>

                    <div class="cia-grid">
                        <?php foreach ($cias as $cia): 
                            $isAssigned = in_array($cia['Id'], $cuentasHermanasIds); 
                            $estaEnPerfil = in_array($cia['Id'], $ciasProvIds);
                            $collision = $colisiones[$cia['Id']] ?? null;
                        ?>
                            <div class="cia-card <?= $isAssigned ? 'assigned disabled' : '' ?> <?= $estaEnPerfil ? 'in-profile' : '' ?> <?= $collision ? 'has-collision' : '' ?>" 
                                 onclick="<?= $isAssigned ? '' : "toggleCiaCard(this, 'cia_" . $cia['Id'] . "')" ?>">
                                
                                <div class="badges-wrapper">
                                    <?php if($isAssigned): ?>
                                        <span class="badge-item assigned-badge">Asignada</span>
                                    <?php elseif($estaEnPerfil): ?>
                                        <span class="badge-item profile-badge">En Perfil</span>
                                    <?php endif; ?>

                                    <?php if($collision): ?>
                                        <span class="badge-item collision-badge" title="Esta empresa ya tiene la cuenta <?= e($collision['Banco']) ?> (..<?= $collision['Clabe'] ?>)">
                                            <i class="bi bi-exclamation-triangle"></i> Otra Cuenta
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input cia-checkbox" type="checkbox" name="CiaId[]"
                                        value="<?= $cia['Id'] ?>" id="cia_<?= $cia['Id'] ?>"
                                        <?= $isAssigned ? 'checked disabled' : '' ?>>
                                    <label class="form-check-label" for="cia_<?= $cia['Id'] ?>">
                                        <?= e($cia['Nombre']) ?>
                                    </label>
                                    <?php if($isAssigned): ?>
                                        <!-- Input oculto para enviar las ya asignadas al controlador -->
                                        <input type="hidden" name="CiaId[]" value="<?= $cia['Id'] ?>">
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <style>
                .cia-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
                    gap: 1.25rem;
                    max-height: 450px;
                    overflow-y: auto;
                    padding: 1.5rem;
                    background: #f8fafc;
                    border: 2px solid #e2e8f0;
                    border-radius: 16px;
                    box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
                }

                .cia-card {
                    background: #ffffff;
                    border: 2px solid #e2e8f0;
                    padding: 1.25rem;
                    border-radius: 14px;
                    cursor: pointer;
                    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
                    display: flex;
                    align-items: center;
                    gap: 1rem;
                    position: relative;
                    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
                }

                .cia-card:hover:not(.disabled) {
                    border-color: #cbd5e1;
                    transform: translateY(-2px);
                    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
                }

                /* Ya asignada (Greenish for status quo or keeping it neutral) */
                .cia-card.assigned {
                    background-color: #f1f5f9;
                    border-color: #cbd5e1;
                    cursor: default;
                    opacity: 0.8;
                }

                /* Seleccionada ahora (Red accent) */
                .cia-card.selected:not(.assigned) {
                    border-color: #ef4444;
                    background-color: #ffffff;
                    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.1);
                }

                .cia-card.selected::after {
                    content: '\F26E'; /* Bootstrap bi-check-circle-fill */
                    font-family: inherit;
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
                    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                }

                .cia-card .form-check {
                    margin: 0;
                    pointer-events: none;
                }

                .cia-card .form-check-input {
                    width: 1.1rem;
                    height: 1.1rem;
                    margin-top: 0;
                    border: 2px solid #cbd5e1;
                }

                .cia-card.selected .form-check-input, 
                .cia-card.assigned .form-check-input {
                    background-color: #ef4444;
                    border-color: #ef4444;
                }

                .cia-card label {
                    cursor: pointer;
                    font-weight: 700;
                    color: #334155;
                    margin: 0;
                    font-size: 0.85rem;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }

                .badges-wrapper {
                    position: absolute;
                    bottom: -8px;
                    left: 50%;
                    transform: translateX(-50%);
                    display: flex;
                    gap: 4px;
                    z-index: 2;
                }

                .badge-item {
                    font-size: 0.55rem;
                    padding: 1px 8px;
                    border-radius: 20px;
                    font-weight: 800;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                    white-space: nowrap;
                }

                .assigned-badge { background: #10b981; color: white; }
                .profile-badge { background: #475569; color: white; }
                .collision-badge { background: #f59e0b; color: white; }

                .cia-card.has-collision {
                    border-style: dashed;
                }

                .cia-card.disabled {
                    opacity: 0.7;
                    filter: grayscale(0.5);
                }
            </style>

            <div class="glass-panel mb-4">
                <div class="d-flex justify-between align-center mb-3">
                    <h2 class="card-title mb-0"><i class="bi bi-bank"></i> Datos de la Cuenta</h2>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Banco <?= esAdmin() ? '<span class="text-danger">*</span>' : '' ?></label>
                    <select name="BancoId" class="form-control" <?= esAdmin() ? 'required' : 'disabled' ?>>
                        <option value="">Seleccione...</option>
                        <?php foreach ($bancos as $banco): ?>
                            <option value="<?= $banco['Id'] ?>" <?= $cuenta['BancoId'] == $banco['Id'] ? 'selected' : '' ?>>
                                <?= e($banco['Nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Número de Cuenta</label>
                    <input type="text" name="Cuenta" class="form-control" value="<?= e($cuenta['Cuenta'] ?? '') ?>"
                        maxlength="30" placeholder="Ej: 0123456789" <?= !esAdmin() && $cuenta['Estatus'] === 'APROBADO' ? 'readonly' : '' ?>>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">CLABE</label>
                    <input type="text" name="Clabe" id="Clabe" class="form-control"
                        value="<?= e($cuenta['Clabe'] ?? '') ?>" maxlength="18" pattern="[0-9]{18}"
                        placeholder="18 dígitos" <?= !esAdmin() && $cuenta['Estatus'] === 'APROBADO' ? 'readonly' : '' ?>>
                </div>

                <div class="grid-2">
                    <div class="form-group mb-0">
                        <label class="form-label">Sucursal</label>
                        <input type="text" name="Sucursal" class="form-control"
                            value="<?= e($cuenta['Sucursal'] ?? '') ?>" <?= !esAdmin() && $cuenta['Estatus'] === 'APROBADO' ? 'readonly' : '' ?>>
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Plaza</label>
                        <input type="text" name="Plaza" class="form-control" value="<?= e($cuenta['Plaza'] ?? '') ?>"
                            <?= !esAdmin() && $cuenta['Estatus'] === 'APROBADO' ? 'readonly' : '' ?>>
                    </div>
                </div>
            </div>

            <div class="glass-panel mb-4">
                <h2 class="card-title fs-6 text-muted mb-3"><i class="bi bi-paperclip"></i> Archivo Adjunto</h2>
                <?php if (esAdmin() || $cuenta['Estatus'] === 'PENDIENTE'): ?>
                    <div class="form-group mb-0">
                        <label class="form-label">Reemplazar Carátula (Si es incorrecta)</label>
                        <input type="file" name="ArchivoCaratula" class="form-control" accept=".pdf,.jpg,.jpeg,.png">

                        <?php if (esAdmin()): ?>
                            <div class="mt-3 py-2 px-3 bg-light rounded border">
                                <div class="form-check form-switch m-0">
                                    <input class="form-check-input" type="checkbox" id="SinCaratula" name="SinCaratula" value="1">
                                    <label class="form-check-label fw-bold text-muted small" for="SinCaratula">Sin Carátula Bancaria (Solo Admin)</label>
                                </div>
                            </div>
                        <?php endif; ?>

                        <small class="form-text text-warning mt-2 d-block">
                            <i class="bi bi-exclamation-triangle"></i> Nota: Si sube un nuevo archivo, esta cuenta se
                            separará del grupo.
                        </small>
                    </div>
                <?php else: ?>
                    <div class="alert alert-light border mb-0 py-2">
                        El archivo no se puede cambiar en este estado.
                    </div>
                <?php endif; ?>
            </div>

            <div class="d-flex gap-2 justify-end pb-5">
                <a href="<?= BASE_URL ?>datos-bancarios/index/<?= $cuenta['ProveedorId'] ?>" class="btn btn-glass">
                    Cancelar
                </a>

                <?php if ($es_aprobacion): ?>
                    <button type="submit" class="btn btn-success py-2 px-4 fw-bold shadow-sm">
                        <i class="bi bi-check-circle-fill"></i> GUARDAR Y APROBAR
                    </button>
                <?php elseif (esAdmin() || $cuenta['Estatus'] === 'PENDIENTE'): ?>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save"></i> Guardar Cambios
                    </button>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="pdf-container-wrapper">
        <div class="pdf-header-bar">
            <span class="small fw-bold d-flex align-center gap-2">
                <i class="bi bi-file-earmark-pdf text-danger fs-5"></i>
                Carátula Bancaria
            </span>

            <?php if (!empty($cuenta['RutaCaratula'])):
                $extExp = strtolower(pathinfo($cuenta['RutaCaratula'], PATHINFO_EXTENSION));
                $isImgExp = in_array($extExp, ['jpg', 'jpeg', 'png']);
                $urlExp = BASE_URL . "datos-bancarios/verArchivo/caratula/" . $cuenta['Id'] . ($isImgExp ? "?isImage=1" : "");
                ?>
                <button onclick="verDocumento('<?= $urlExp ?>', 'Carátula Bancaria')" class="btn btn-sm btn-glass py-0"
                    title="Pantalla Completa">
                    <i class="bi bi-arrows-fullscreen"></i> Expandir
                </button>
            <?php endif; ?>
        </div>

        <div class="pdf-content-area">
            <?php if (!empty($cuenta['RutaCaratula'])): ?>
                <?php
                $ext = strtolower(pathinfo($cuenta['RutaCaratula'], PATHINFO_EXTENSION));
                $url = BASE_URL . "datos-bancarios/verArchivo/caratula/" . $cuenta['Id'];
                if (in_array($ext, ['jpg', 'jpeg', 'png'])): ?>
                    <img src="<?= $url ?>" class="img-fluid" style="max-height: 100%; object-fit: contain;" alt="Carátula">
                <?php else: ?>
                    <iframe src="<?= $url ?>#toolbar=1&view=FitH" class="pdf-iframe"></iframe>
                <?php endif; ?>
            <?php else: ?>
                <div class="d-flex align-center justify-center h-100 flex-column" style="opacity: 0.5; color: #fff;">
                    <i class="bi bi-file-earmark-x" style="font-size: 3rem;"></i>
                    <p class="mt-2 small fw-bold">Sin documento adjunto</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<script>
    // Select All functionality (only for enabled checkboxes)
    const selectAll = document.getElementById('selectAllCias');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.cia-checkbox:not(:disabled)');
            checkboxes.forEach(cb => {
                cb.checked = this.checked;
                const card = cb.closest('.cia-card');
                if (this.checked) card.classList.add('selected');
                else card.classList.remove('selected');
            });
        });
    }

    function toggleCiaCard(card, checkboxId) {
        const cb = document.getElementById(checkboxId);
        if (cb.disabled) return;
        
        cb.checked = !cb.checked;
        if (cb.checked) {
            card.classList.add('selected');
        } else {
            card.classList.remove('selected');
        }
    }

    // Validación CLABE
    const clabeInput = document.getElementById('Clabe');
    if (clabeInput) {
        clabeInput.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '').substring(0, 18);
        });
    }

    // Submit Loading
    document.getElementById('formCuenta').addEventListener('submit', function (e) {
        if (clabeInput && clabeInput.value && clabeInput.value.length !== 18) {
            e.preventDefault();
            alertError('CLABE Inválida', 'Debe tener 18 dígitos.');
            return false;
        }

        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Procesando...';
    });

    // Lightbox Global
    function verDocumento(url, titulo) {
        window.verDocumento(url, titulo);
    }
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>