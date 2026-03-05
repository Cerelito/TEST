<?php
$pagina_actual = 'proveedores';
$titulo = 'Nueva Cuenta Bancaria';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<div class="d-flex justify-between align-center mb-4">
    <div>
        <h1 class="section-title">
            <i class="bi bi-plus-circle"></i> Nueva Cuenta Bancaria
        </h1>
        <p class="section-subtitle">
            <?= e($proveedor['RazonSocial']) ?> - RFC: <?= e($proveedor['RFC']) ?>
        </p>
    </div>
    <a href="<?= BASE_URL ?>datos-bancarios/index/<?= $proveedor['Id'] ?>" class="btn btn-glass">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<?php if (!esAdmin()): ?>
    <div class="alert alert-info mb-4">
        <i class="bi bi-info-circle"></i>
        <div>
            <strong>Nota:</strong> La cuenta bancaria que registres quedará <strong>PENDIENTE de aprobación</strong> por el
            Administrador.
            Podrás adjuntar la carátula bancaria en PDF o imagen (JPG/PNG) y el Admin la revisará antes de activarla.
        </div>
    </div>
<?php endif; ?>

<form method="POST" action="<?= BASE_URL ?>datos-bancarios/guardar/<?= $proveedor['Id'] ?>"
    enctype="multipart/form-data" id="formCuentaBancaria">
    <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">

    <div class="glass-panel mb-4">
        <h2 class="card-title">
            <i class="bi bi-building-gear"></i> Compañía
        </h2>

        <div class="form-group">
            <label class="form-label mb-3">Seleccione las compañías a las que pertenece esta cuenta: <span
                    class="text-danger">*</span></label>

            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="selectAllCias">
                    <label class="form-check-label fw-bold" for="selectAllCias">
                        Seleccionar todas
                    </label>
                </div>
            </div>

            <small class="form-text mt-2">La cuenta bancaria se creará individualmente para cada compañía
                seleccionada.</small>
        </div>

        

        <div class="cia-grid">
            <?php foreach ($cias as $cia):
                $estaAsignadaAlProv = in_array($cia['Id'], $ciasProveedorIds);
                $collision = $colisiones[$cia['Id']] ?? null;
                ?>
                <div class="cia-card <?= $estaAsignadaAlProv ? 'assigned' : '' ?> <?= $collision ? 'has-collision' : '' ?>"
                    onclick="toggleCiaCard(this, 'cia_<?= $cia['Id'] ?>')">

                    <div class="badges-wrapper-crear">
                        <?php if ($estaAsignadaAlProv): ?>
                            <span class="already-assigned-badge">En Perfil</span>
                        <?php endif; ?>

                        <?php if ($collision): ?>
                            <span class="collision-badge-crear"
                                title="Esta empresa ya tiene la cuenta <?= e($collision['Banco']) ?> (..<?= $collision['Clabe'] ?>)">
                                <i class="bi bi-exclamation-triangle"></i> Otra Cuenta
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input cia-checkbox" type="checkbox" name="CiaId[]"
                            value="<?= $cia['Id'] ?>" id="cia_<?= $cia['Id'] ?>" <?= $estaAsignadaAlProv ? 'checked' : '' ?>>
                        <label class="form-check-label" for="cia_<?= $cia['Id'] ?>">
                            <?= e($cia['Nombre']) ?>
                        </label>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <small class="form-text mt-3 text-muted d-block">
            <i class="bi bi-info-circle"></i> Las compañías marcadas como <strong>"En Perfil"</strong> son con las que
            el proveedor ya trabaja.
            Si selecciona una nueva, se vinculará automáticamente al perfil del proveedor al guardar.
        </small>

        <div class="glass-panel mb-4">
            <h2 class="card-title">
                <i class="bi bi-file-earmark-image"></i> Carátula Bancaria
            </h2>

            <div class="alert alert-info mb-4">
                <i class="bi bi-lightbulb"></i>
                <strong>Sistema Inteligente:</strong> Sube el PDF de la carátula bancaria y el sistema vinculará
                automáticamente
                este documento con la cuenta. Podrás agregar más documentos (estados de cuenta, contratos, etc.)
                después.
            </div>

            <div class="form-group">
                <label for="ArchivoCaratula" class="form-label">Carátula Bancaria (PDF o Imagen) <span
                        class="text-red">*</span></label>
                <input type="file" id="ArchivoCaratula" name="ArchivoCaratula" class="form-control"
                    accept=".pdf,.jpg,.jpeg,.png" required>
                <small class="form-text">
                    Archivos PDF o imágenes JPG/PNG (máx. 5MB). Este archivo quedará vinculado a esta cuenta bancaria.
                </small>

                <?php if (esAdmin()): ?>
                    <div class="mt-3 py-2 px-3 bg-light rounded border">
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input" type="checkbox" id="SinCaratula" name="SinCaratula" value="1"
                                onchange="toggleRequeridoDoc(this, 'ArchivoCaratula')">
                            <label class="form-check-label fw-bold text-muted small" for="SinCaratula">Sin Carátula Bancaria
                                (Solo Admin)</label>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div id="previewCaratula" style="display: none; margin-top: 1rem;">
                <div class="alert alert-success mb-0">
                    <i class="bi bi-check-circle"></i>
                    <strong>Archivo seleccionado:</strong> <span id="nombreArchivo"></span>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 justify-end mb-5">
            <a href="<?= BASE_URL ?>datos-bancarios/index/<?= $proveedor['Id'] ?>" class="btn btn-glass">
                <i class="bi bi-x-lg"></i> Cancelar
            </a>
            <button type="submit" class="btn btn-primary" id="btnGuardar">
                <i class="bi bi-check-lg"></i> <?= esAdmin() ? 'Crear y Aprobar' : 'Crear Cuenta (Pendiente)' ?>
            </button>
        </div>
</form>

<script>
    // Select All functionality
    document.getElementById('selectAllCias').addEventListener('change', function () {
        const checkboxes = document.querySelectorAll('.cia-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = this.checked;
            const card = cb.closest('.cia-card');
            if (this.checked) card.classList.add('assigned');
            else card.classList.remove('assigned');
        });
    });

    function toggleCiaCard(card, checkboxId) {
        const cb = document.getElementById(checkboxId);
        cb.checked = !cb.checked;
        if (cb.checked) {
            card.classList.add('assigned');
        } else {
            card.classList.remove('assigned');
        }
    }

    function toggleRequeridoDoc(check, inputId) {
        const input = document.getElementById(inputId);
        if (check.checked) {
            input.required = false;
        } else {
            input.required = true;
        }
    }

    // Preview del archivo seleccionado
    document.getElementById('ArchivoCaratula').addEventListener('change', function (e) {
        const preview = document.getElementById('previewCaratula');
        const nombreArchivo = document.getElementById('nombreArchivo');

        if (this.files && this.files[0]) {
            const file = this.files[0];

            // Validar que sea PDF o Imagen
            const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
            if (!allowedTypes.includes(file.type)) {
                alertError('Archivo Inválido', 'Solo se permiten archivos PDF o imágenes JPG/PNG');
                this.value = '';
                preview.style.display = 'none';
                return;
            }

            // Validar tamaño (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alertError('Archivo Demasiado Grande', 'El archivo no debe exceder 5MB');
                this.value = '';
                preview.style.display = 'none';
                return;
            }

            nombreArchivo.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    });

    // Validar CLABE en tiempo real
    const clabeInput = document.getElementById('Clabe');
    if (clabeInput) {
        clabeInput.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '').substring(0, 18);
        });
    }

    // Validar formulario al enviar
    document.getElementById('formCuentaBancaria').addEventListener('submit', function (e) {
        // Validar que al menos una compañía esté seleccionada
        const checkedCias = document.querySelectorAll('.cia-checkbox:checked');

        if (checkedCias.length === 0) {
            e.preventDefault();
            alertWarning('Compañía Requerida', 'Debe seleccionar al menos una compañía');
            return false;
        }

        // Validar CLABE si se proporcionó
        const clabe = document.getElementById('Clabe');
        if (clabe && clabe.value && clabe.value.length !== 18) {
            e.preventDefault();
            alertError('CLABE Inválida', 'La CLABE debe tener exactamente 18 dígitos');
            return false;
        }

        // Mostrar loading
        const btnGuardar = document.getElementById('btnGuardar');
        btnGuardar.disabled = true;
        btnGuardar.innerHTML = '<i class="bi bi-hourglass-split"></i> Guardando...';
    });
    function toggleRequeridoDoc(check, inputId) {
        const input = document.getElementById(inputId);
        if (check.checked) {
            input.required = false;
        } else {
            input.required = true;
        }
    }
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>