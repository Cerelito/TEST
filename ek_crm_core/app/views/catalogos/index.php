<?php
$pagina_actual = 'catalogos';
$titulo = 'Catálogos del Sistema';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<div class="d-flex justify-between align-center mb-4">
    <div>
        <h1 class="section-title">
            <i class="bi bi-list-ul"></i> Catálogos del Sistema
        </h1>
        <p class="section-subtitle">
            Consulta y gestión de catálogos maestros
        </p>
    </div>
</div>

<div class="glass-panel mb-4">
    <div class="d-flex justify-between align-center mb-3 pb-2 border-bottom">
        <div class="d-flex align-center gap-3">
            <h2 class="card-title mb-0">
                <i class="bi bi-bank"></i> Catálogo de Bancos
            </h2>
            <span class="badge badge-primary"><?= count($bancos) ?> bancos</span>
        </div>
        <?php if (puedeHacer('catalogos.editar')): ?>
            <a href="<?= BASE_URL ?>catalogos/crearBanco" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle"></i> Agregar Banco
            </a>
        <?php endif; ?>
    </div>

    <div class="scrollable-table">
        <table class="glass-table">
            <thead>
                <tr>
                    <th class="col-w-100">CLABE</th>
                    <th>Nombre</th>
                    <th class="col-w-120">Estado</th>
                    <?php if (puedeHacer('catalogos.editar')): ?>
                        <th class="col-w-140-right">Acciones</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bancos as $banco): ?>
                    <tr>
                        <td><strong><?= e($banco['CLABE'] ?? $banco['clabe'] ?? '') ?></strong></td>
                        <td><?= e($banco['Nombre'] ?? $banco['nombre'] ?? '') ?></td>
                        <td>
                            <?php if ($banco['Activo'] ?? $banco['activo'] ?? 0): ?>
                                <span class="badge badge-success">Activo</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <?php if (puedeHacer('catalogos.editar')): ?>
                            <td class="text-right">
                                <div class="d-flex gap-1 justify-end">
                                    <a href="<?= BASE_URL ?>catalogos/editarBanco/<?= $banco['Id'] ?? $banco['id'] ?? '' ?>"
                                        class="btn btn-sm btn-glass" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST"
                                        action="<?= BASE_URL ?>catalogos/eliminarBanco/<?= $banco['Id'] ?? $banco['id'] ?? '' ?>"
                                        class="d-inline"
                                        id="formEliminarBanco<?= $banco['Id'] ?? $banco['id'] ?? '' ?>">
                                        <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">
                                        <button type="button" class="btn btn-sm btn-danger" title="Eliminar"
                                            onclick="confirmarEliminacionBanco(<?= $banco['Id'] ?? $banco['id'] ?? '' ?>, '<?= e($banco['Nombre'] ?? $banco['nombre'] ?? '') ?>')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="glass-panel mb-4">
    <div class="d-flex justify-between align-center mb-3 pb-2 border-bottom">
        <div class="d-flex align-center gap-3">
            <h2 class="card-title mb-0">
                <i class="bi bi-building-gear"></i> Catálogo de Compañías
            </h2>
            <span class="badge badge-primary"><?= count($cias) ?> compañías</span>
        </div>
        <?php if (puedeHacer('catalogos.editar')): ?>
            <a href="<?= BASE_URL ?>catalogos/crearCia" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle"></i> Agregar Compañía
            </a>
        <?php endif; ?>
    </div>

    <div class="scrollable-table scrollable-no-border">
        <div class="grid-container grid-auto-fill-280 grid-p-half">
            <?php foreach ($cias as $cia): ?>
                <div class="glass-panel catalog-cia-card">
                    <div class="d-flex justify-between align-center mb-2">
                        <h3 class="catalog-cia-title">
                            <span class="text-muted mr-1">#<?= e($cia['Codigo'] ?? $cia['codigo'] ?? '') ?></span>
                            <?= e($cia['Nombre'] ?? $cia['nombre'] ?? '') ?>
                        </h3>
                        <?php if ($cia['Activo'] ?? $cia['activo'] ?? 0): ?>
                            <span class="badge badge-success">Activo</span>
                        <?php else: ?>
                            <span class="badge badge-secondary">Inactivo</span>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($cia['Descripcion'] ?? $cia['descripcion'] ?? '')): ?>
                        <p class="catalog-cia-desc">
                            <?= e($cia['Descripcion'] ?? $cia['descripcion'] ?? '') ?>
                        </p>
                    <?php else: ?>
                        <p class="catalog-cia-desc-empty">Sin
                            descripción</p>
                    <?php endif; ?>

                    <?php if (puedeHacer('catalogos.editar')): ?>
                        <div class="d-flex gap-2 mt-auto pt-2 border-top">
                            <a href="<?= BASE_URL ?>catalogos/editarCia/<?= $cia['Id'] ?? $cia['id'] ?? '' ?>"
                                class="btn btn-sm btn-glass w-100" title="Editar">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                            <form method="POST"
                                action="<?= BASE_URL ?>catalogos/eliminarCia/<?= $cia['Id'] ?? $cia['id'] ?? '' ?>"
                                class="w-100" id="formEliminarCia<?= $cia['Id'] ?? $cia['id'] ?? '' ?>">
                                <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">
                                <button type="button" class="btn btn-sm btn-danger w-100" title="Eliminar"
                                    onclick="confirmarEliminacionCia(<?= $cia['Id'] ?? $cia['id'] ?? '' ?>, '<?= e($cia['Nombre'] ?? $cia['nombre'] ?? '') ?>')">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="glass-panel mb-4">
    <div class="d-flex justify-between align-center mb-3 pb-2 border-bottom">
        <div class="d-flex align-center gap-3">
            <h2 class="card-title mb-0">
                <i class="bi bi-file-earmark-ruled"></i> Catálogo de Regímenes Fiscales (SAT)
            </h2>
            <span class="badge badge-primary"><?= count($regimenes) ?> regímenes</span>
        </div>
        <?php if (puedeHacer('catalogos.editar')): ?>
            <a href="<?= BASE_URL ?>catalogos/crearRegimen" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle"></i> Agregar Régimen
            </a>
        <?php endif; ?>
    </div>

    <div class="scrollable-table">
        <table class="glass-table">
            <thead>
                <tr>
                    <th class="col-w-80">Clave</th>
                    <th>Descripción</th>
                    <th class="col-w-150">Tipo Persona</th>
                    <th class="col-w-100">Estado</th>
                    <?php if (puedeHacer('catalogos.editar')): ?>
                        <th class="col-w-140-right">Acciones</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($regimenes as $reg): ?>
                    <tr>
                        <td><strong><?= e($reg['Clave'] ?? $reg['clave'] ?? '') ?></strong></td>
                        <td><?= e($reg['Descripcion'] ?? $reg['descripcion'] ?? '') ?></td>
                        <td>
                            <?php
                            $tipo = $reg['TipoPersona'] ?? $reg['tipopersona'] ?? '';
                            if (in_array($tipo, ['Física', 'Fisica'])): ?>
                                <span class="badge badge-info">Física</span>
                            <?php elseif ($tipo === 'Moral'): ?>
                                <span class="badge badge-warning">Moral</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Ambas</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($reg['Activo'] ?? $reg['activo'] ?? 0): ?>
                                <span class="badge badge-success">Activo</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <?php if (puedeHacer('catalogos.editar')): ?>
                            <td class="text-right">
                                <div class="d-flex gap-1 justify-end">
                                    <a href="<?= BASE_URL ?>catalogos/editarRegimen/<?= $reg['Id'] ?? $reg['id'] ?? '' ?>"
                                        class="btn btn-sm btn-glass" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST"
                                        action="<?= BASE_URL ?>catalogos/eliminarRegimen/<?= $reg['Id'] ?? $reg['id'] ?? '' ?>"
                                        class="d-inline" id="formEliminarRegimen<?= $reg['Id'] ?? $reg['id'] ?? '' ?>">
                                        <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">
                                        <button type="button" class="btn btn-sm btn-danger" title="Eliminar"
                                            onclick="confirmarEliminacionRegimen(<?= $reg['Id'] ?? $reg['id'] ?? '' ?>, '<?= e($reg['Descripcion'] ?? $reg['descripcion'] ?? '') ?>')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="glass-panel catalog-info-panel">
    <h3 class="card-title catalog-info-title">
        <i class="bi bi-info-circle"></i> Información sobre Catálogos
    </h3>

    <div class="catalog-info-text">
        <p class="mb-2">
            <strong>Catálogos Maestros:</strong> Estos catálogos son utilizados en todo el sistema para mantener
            la consistencia de los datos. Solo los administradores pueden modificarlos.
        </p>

        <ul class="catalog-info-list">
            <li><strong>Bancos:</strong> Catálogo oficial de instituciones bancarias en México</li>
            <li><strong>Compañías:</strong> Empresas del grupo que pueden tener proveedores asignados</li>
            <li><strong>Regímenes Fiscales:</strong> Catálogo del SAT para la clasificación fiscal</li>
        </ul>

        <div class="alert alert-info mb-0 catalog-info-alert">
            <i class="bi bi-exclamation-circle"></i>
            <strong>Nota:</strong> Los catálogos se actualizan automáticamente según las publicaciones oficiales del
            SAT.
        </div>
    </div>
</div>

<script>
    // Confirmar eliminación de banco
    async function confirmarEliminacionBanco(id, nombre) {
        const confirmed = await confirmDelete(`Banco: ${nombre}`, 'Esta acción no se puede deshacer');
        if (confirmed) {
            document.getElementById('formEliminarBanco' + id).submit();
        }
    }

    // Confirmar eliminación de compañía
    async function confirmarEliminacionCia(id, nombre) {
        const confirmed = await confirmDelete(`Compañía: ${nombre}`, 'Esta acción no se puede deshacer');
        if (confirmed) {
            document.getElementById('formEliminarCia' + id).submit();
        }
    }

    // Confirmar eliminación de régimen fiscal
    async function confirmarEliminacionRegimen(id, descripcion) {
        const confirmed = await confirmDelete(`Régimen Fiscal: ${descripcion}`, 'Esta acción no se puede deshacer');
        if (confirmed) {
            document.getElementById('formEliminarRegimen' + id).submit();
        }
    }
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>