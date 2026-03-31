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
                    <th style="width: 100px;">CLABE</th>
                    <th>Nombre</th>
                    <th style="width: 120px;">Estado</th>
                    <?php if (puedeHacer('catalogos.editar')): ?>
                        <th style="width: 140px; text-align: right;">Acciones</th>
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
                            <td style="text-align: right;">
                                <div class="d-flex gap-1 justify-end">
                                    <a href="<?= BASE_URL ?>catalogos/editarBanco/<?= $banco['Id'] ?? $banco['id'] ?? '' ?>"
                                        class="btn btn-sm btn-glass" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST"
                                        action="<?= BASE_URL ?>catalogos/eliminarBanco/<?= $banco['Id'] ?? $banco['id'] ?? '' ?>"
                                        style="display: inline;"
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

    <div class="scrollable-table" style="border: none; max-height: 500px;">
        <div class="grid-container"
            style="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)) !important; padding: 0.5rem;">
            <?php foreach ($cias as $cia): ?>
                <div class="glass-panel"
                    style="background: var(--bg-secondary); padding: 1.25rem; margin-bottom: 0 !important; border: 1px solid var(--border-color);">
                    <div class="d-flex justify-between align-center mb-2">
                        <h3 style="font-size: 1rem; font-weight: 700; color: var(--text-primary); margin: 0;">
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
                        <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0 0 1rem 0; line-height: 1.4;">
                            <?= e($cia['Descripcion'] ?? $cia['descripcion'] ?? '') ?>
                        </p>
                    <?php else: ?>
                        <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0 0 1rem 0; font-style: italic;">Sin
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
                                style="width: 100%;" id="formEliminarCia<?= $cia['Id'] ?? $cia['id'] ?? '' ?>">
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
                    <th style="width: 80px;">Clave</th>
                    <th>Descripción</th>
                    <th style="width: 150px;">Tipo Persona</th>
                    <th style="width: 100px;">Estado</th>
                    <?php if (puedeHacer('catalogos.editar')): ?>
                        <th style="width: 140px; text-align: right;">Acciones</th>
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
                            <td style="text-align: right;">
                                <div class="d-flex gap-1 justify-end">
                                    <a href="<?= BASE_URL ?>catalogos/editarRegimen/<?= $reg['Id'] ?? $reg['id'] ?? '' ?>"
                                        class="btn btn-sm btn-glass" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST"
                                        action="<?= BASE_URL ?>catalogos/eliminarRegimen/<?= $reg['Id'] ?? $reg['id'] ?? '' ?>"
                                        style="display: inline;" id="formEliminarRegimen<?= $reg['Id'] ?? $reg['id'] ?? '' ?>">
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

<div class="glass-panel" style="background: var(--bg-secondary);">
    <h3 class="card-title" style="font-size: 1rem; color: var(--text-secondary);">
        <i class="bi bi-info-circle"></i> Información sobre Catálogos
    </h3>

    <div style="font-size: 0.9rem; line-height: 1.6; color: var(--text-muted);">
        <p class="mb-2">
            <strong>Catálogos Maestros:</strong> Estos catálogos son utilizados en todo el sistema para mantener
            la consistencia de los datos. Solo los administradores pueden modificarlos.
        </p>

        <ul style="margin: 0 0 1rem 1.5rem; list-style-type: disc;">
            <li><strong>Bancos:</strong> Catálogo oficial de instituciones bancarias en México</li>
            <li><strong>Compañías:</strong> Empresas del grupo que pueden tener proveedores asignados</li>
            <li><strong>Regímenes Fiscales:</strong> Catálogo del SAT para la clasificación fiscal</li>
        </ul>

        <div class="alert alert-info mb-0" style="padding: 0.5rem 1rem; font-size: 0.85rem;">
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