<?php
$title = 'Editar Programa Nivel';
ob_start();

$programa = $programa ?? [];
$arbol    = $arbol    ?? [];

/**
 * Renders a permission tree node recursively (editar mode - respects $node['granted']).
 */
function renderTreeNodeEditar(array $node): void {
    $hasChildren = !empty($node['children']);
    $granted     = !empty($node['granted']);
    $nodeId      = 'node_' . (int)$node['id'];
    ?>
    <div class="tree-item">
        <div class="tree-label <?php echo $hasChildren ? 'has-children' : ''; ?>">
            <?php if ($hasChildren): ?>
            <button type="button" class="tree-toggle open" data-target="<?php echo $nodeId; ?>_children" aria-label="Toggle">&#9658;</button>
            <?php else: ?>
            <span style="width:16px; flex-shrink:0;"></span>
            <?php endif; ?>
            <input
                type="checkbox"
                class="tree-check"
                name="permisos[]"
                value="<?php echo (int)$node['id']; ?>"
                id="perm_<?php echo (int)$node['id']; ?>"
                <?php echo $granted ? 'checked' : ''; ?>
                data-parent="<?php echo $nodeId; ?>"
            >
            <span style="color:#f1f5f9; font-size:13px; user-select:none;" onclick="document.getElementById('perm_<?php echo (int)$node['id']; ?>').click()">
                <?php echo htmlspecialchars($node['nombre'] ?? ($node['clave'] ?? '')); ?>
            </span>
            <?php if (!empty($node['clave'])): ?>
            <span class="badge badge-gray" style="font-size:10px; margin-left:4px;"><?php echo htmlspecialchars($node['clave']); ?></span>
            <?php endif; ?>
        </div>
        <?php if ($hasChildren): ?>
        <div class="tree-children" id="<?php echo $nodeId; ?>_children">
            <?php foreach ($node['children'] as $child): ?>
                <?php renderTreeNodeEditar($child); ?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    <?php
}
?>
<style>
    .form-card { background:rgba(255,255,255,0.06); backdrop-filter:blur(20px); -webkit-backdrop-filter:blur(20px); border:1px solid rgba(255,255,255,0.12); border-radius:20px; padding:28px; margin-bottom:24px; }
    .form-card-title { font-size:16px; font-weight:700; color:#f1f5f9; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
    .form-card-title svg { color:#818cf8; }
    .page-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
    .page-header-title { font-size:26px; font-weight:800; color:#f1f5f9; letter-spacing:-0.5px; }
    .tree-root-block { background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.08); border-radius:14px; padding:14px; margin-bottom:12px; }
    .tree-root-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:10px; padding-bottom:10px; border-bottom:1px solid rgba(255,255,255,0.07); }
    .tree-root-title { font-size:14px; font-weight:700; color:#f1f5f9; display:flex; align-items:center; gap:8px; }
    .tree-root-actions { display:flex; gap:6px; }
    .btn-tree-sel { padding:4px 10px; font-size:11px; border-radius:7px; border:1px solid rgba(255,255,255,0.15); background:rgba(255,255,255,0.06); color:#94a3b8; cursor:pointer; transition:all 0.2s; font-family:var(--font); }
    .btn-tree-sel:hover { background:rgba(255,255,255,0.12); color:#f1f5f9; }
    .form-actions { display:flex; gap:12px; justify-content:flex-end; margin-top:8px; }
    .edit-meta { font-size:12px; color:#64748b; background:rgba(99,102,241,0.08); border:1px solid rgba(99,102,241,0.2); border-radius:8px; padding:6px 12px; display:inline-flex; align-items:center; gap:6px; }
</style>

<div class="page-header">
    <div>
        <h2 class="page-header-title">Editar Programa Nivel</h2>
        <span style="font-size:13px; color:#64748b;">Modifica la configuración y permisos del programa</span>
    </div>
    <div style="display:flex; gap:10px; align-items:center;">
        <?php if (!empty($programa['id'])): ?>
        <span class="edit-meta">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            ID #<?php echo (int)$programa['id']; ?>
        </span>
        <?php endif; ?>
        <a href="<?php echo BASE_URL; ?>/programa-nivel" class="btn btn-glass">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            Volver
        </a>
    </div>
</div>

<form method="POST" action="<?php echo BASE_URL; ?>/programa-nivel/actualizar/<?php echo (int)($programa['id'] ?? 0); ?>" id="formPNEditar">
    <?php echo csrfField(); ?>

    <!-- Basic Info -->
    <div class="form-card">
        <div class="form-card-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            Información General
        </div>
        <div class="form-row form-row-2">
            <div class="form-group">
                <label class="form-label" for="nombre">Nombre del Programa <span style="color:#f87171;">*</span></label>
                <input type="text" id="nombre" name="nombre" class="form-control"
                    placeholder="Ej. Operativo Básico" required maxlength="100"
                    value="<?php echo htmlspecialchars($_POST['nombre'] ?? $programa['nombre'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label class="form-label" for="nivel">Número de Nivel <span style="color:#f87171;">*</span></label>
                <input type="number" id="nivel" name="nivel" class="form-control"
                    placeholder="1, 2, 3..." min="1" max="99" required
                    value="<?php echo htmlspecialchars($_POST['nivel'] ?? $programa['nivel'] ?? ''); ?>">
            </div>
        </div>
        <div class="form-row form-row-2" style="margin-top:0;">
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label" for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" class="form-control" rows="3"
                    placeholder="Descripción breve de este nivel de programa..."><?php echo htmlspecialchars($_POST['descripcion'] ?? $programa['descripcion'] ?? ''); ?></textarea>
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Estado</label>
                <label style="display:flex; align-items:center; gap:10px; cursor:pointer; margin-top:4px;">
                    <span class="toggle">
                        <input type="hidden" name="activo" value="0">
                        <input type="checkbox" name="activo" value="1" id="activoToggle"
                            <?php echo !empty($programa['activo']) ? 'checked' : ''; ?>>
                        <span class="toggle-track"></span>
                        <span class="toggle-thumb"></span>
                    </span>
                    <span style="font-size:13px; color:#94a3b8;" id="activoLabel">
                        <?php echo !empty($programa['activo']) ? 'Activo' : 'Inactivo'; ?>
                    </span>
                </label>
            </div>
        </div>
    </div>

    <!-- Module Permission Tree -->
    <div class="form-card">
        <div class="form-card-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            Permisos de Módulos
        </div>
        <p style="font-size:13px; color:#64748b; margin-bottom:16px;">Los módulos marcados (✓) ya están otorgados. Modifica la selección según sea necesario.</p>

        <?php if (empty($arbol)): ?>
        <div style="padding:40px; text-align:center; color:#475569;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin:0 auto 12px; display:block; opacity:0.3;"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            <div>No hay módulos configurados en el sistema</div>
        </div>
        <?php else: ?>
        <?php foreach ($arbol as $root): ?>
        <div class="tree-root-block">
            <div class="tree-root-header">
                <span class="tree-root-title">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#818cf8" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    <?php echo htmlspecialchars($root['nombre'] ?? ($root['clave'] ?? '')); ?>
                </span>
                <div class="tree-root-actions">
                    <button type="button" class="btn-tree-sel" data-action="select-all"   data-root="root_<?php echo (int)$root['id']; ?>">Seleccionar todo</button>
                    <button type="button" class="btn-tree-sel" data-action="deselect-all" data-root="root_<?php echo (int)$root['id']; ?>">Deseleccionar todo</button>
                </div>
            </div>
            <div class="tree" id="root_<?php echo (int)$root['id']; ?>">
                <?php renderTreeNodeEditar($root); ?>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="form-actions">
        <a href="<?php echo BASE_URL; ?>/programa-nivel" class="btn btn-glass">Cancelar</a>
        <button type="submit" class="btn btn-primary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            Guardar Cambios
        </button>
    </div>
</form>

<script>
(function () {
    // Toggle label for activo
    var activoToggle = document.getElementById('activoToggle');
    var activoLabel  = document.getElementById('activoLabel');
    if (activoToggle && activoLabel) {
        activoToggle.addEventListener('change', function () {
            activoLabel.textContent = this.checked ? 'Activo' : 'Inactivo';
        });
    }

    // Toggle tree children visibility
    document.querySelectorAll('.tree-toggle').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var targetId = this.getAttribute('data-target');
            var children = document.getElementById(targetId);
            if (!children) return;
            children.classList.toggle('collapsed');
            this.classList.toggle('open');
        });
    });

    // Cascade parent checkbox to children
    document.querySelectorAll('.tree-check').forEach(function (chk) {
        chk.addEventListener('change', function () {
            var item = this.closest('.tree-item');
            if (!item) return;
            var childChecks = item.querySelectorAll('.tree-children .tree-check');
            childChecks.forEach(function (c) { c.checked = chk.checked; });
            updateParentState(item.parentElement);
        });
    });

    function updateParentState(container) {
        if (!container) return;
        var parentItem = container.closest('.tree-item');
        if (!parentItem) return;
        var parentChk = parentItem.querySelector(':scope > .tree-label > .tree-check');
        if (!parentChk) return;
        var childChecks = Array.from(container.querySelectorAll(':scope > .tree-item > .tree-label > .tree-check'));
        var allChecked  = childChecks.every(function (c) { return c.checked; });
        var someChecked = childChecks.some(function (c)  { return c.checked; });
        parentChk.indeterminate = someChecked && !allChecked;
        parentChk.checked = allChecked;
        updateParentState(parentItem.parentElement);
    }

    // Select / deselect all per root
    document.querySelectorAll('[data-action="select-all"], [data-action="deselect-all"]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var rootId = this.getAttribute('data-root');
            var root   = document.getElementById(rootId);
            if (!root) return;
            var state  = this.getAttribute('data-action') === 'select-all';
            root.querySelectorAll('.tree-check').forEach(function (c) { c.checked = state; c.indeterminate = false; });
        });
    });

    // Initialize indeterminate states on load
    document.querySelectorAll('.tree-item').forEach(function (item) {
        var children = item.querySelector('.tree-children');
        if (!children) return;
        updateParentState(children);
    });
})();
</script>
<?php
$content = ob_get_clean();
include VIEWS_PATH . 'layouts/app.php';
?>
