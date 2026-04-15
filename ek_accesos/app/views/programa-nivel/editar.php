<?php
$title = 'Editar Programa Nivel';
ob_start();

$programa = $programa ?? [];
$arbol    = $arbol    ?? [];

// Count total and granted modules
function countModules(array $nodes): array {
    $total = 0; $granted = 0;
    foreach ($nodes as $n) {
        if (empty($n['es_separador'])) { $total++; if (!empty($n['granted'])) $granted++; }
        if (!empty($n['children'])) {
            $c = countModules($n['children']); $total += $c[0]; $granted += $c[1];
        }
    }
    return [$total, $granted];
}
[$totalMods, $grantedMods] = countModules($arbol);

/**
 * Renders a permission tree node recursively (editar mode).
 */
function renderTreeNodeEditar(array $node, int $depth = 0): void {
    $isSep       = !empty($node['es_separador']);
    $hasChildren = !empty($node['children']);
    $granted     = !empty($node['granted']);
    $nodeId      = 'node_' . (int)$node['id'];
    $label       = htmlspecialchars($node['nombre'] ?? ($node['clave'] ?? ''));
    $clave       = htmlspecialchars($node['clave'] ?? '');

    if ($isSep) { ?>
    <div class="pn-sep"><?php echo $label ?: '—'; ?></div>
    <?php return; } ?>
    <div class="pn-row" data-label="<?php echo strtolower(strip_tags($label)); ?>" style="padding-left:<?php echo 8 + $depth * 20; ?>px;">
        <div class="pn-row-inner">
            <?php if ($hasChildren): ?>
            <button type="button" class="pn-expand open" data-target="<?php echo $nodeId; ?>_ch" aria-label="Toggle">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
            <?php else: ?>
            <span class="pn-expand-ph"></span>
            <?php endif; ?>
            <label class="pn-label" for="perm_<?php echo (int)$node['id']; ?>">
                <input type="checkbox" class="pn-check" name="permisos[]"
                    value="<?php echo (int)$node['id']; ?>"
                    id="perm_<?php echo (int)$node['id']; ?>"
                    <?php echo $granted ? 'checked' : ''; ?>>
                <span class="pn-name"><?php echo $label; ?></span>
                <?php if ($clave): ?><span class="pn-clave"><?php echo $clave; ?></span><?php endif; ?>
            </label>
            <?php if ($hasChildren): ?>
            <div class="pn-child-actions">
                <button type="button" class="pn-mini-btn" data-block-sel="1" data-root="<?php echo $nodeId; ?>_ch">+todos</button>
                <button type="button" class="pn-mini-btn" data-block-sel="0" data-root="<?php echo $nodeId; ?>_ch">-todos</button>
            </div>
            <?php endif; ?>
        </div>
        <?php if ($hasChildren): ?>
        <div class="pn-children" id="<?php echo $nodeId; ?>_ch">
            <?php foreach ($node['children'] as $child): renderTreeNodeEditar($child, $depth + 1); endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    <?php
}
?>
<style>
    .pn-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
    .pn-header-title { font-size:26px; font-weight:800; color:#f1f5f9; letter-spacing:-0.5px; }
    .form-card { background:rgba(255,255,255,0.06); backdrop-filter:blur(20px); -webkit-backdrop-filter:blur(20px); border:1px solid rgba(255,255,255,0.12); border-radius:20px; padding:28px; margin-bottom:24px; }
    .form-card-title { font-size:15px; font-weight:700; color:#f1f5f9; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
    .form-card-title svg { color:#818cf8; }

    /* Search + counter bar */
    .pn-toolbar { display:flex; align-items:center; gap:12px; margin-bottom:16px; flex-wrap:wrap; }
    .pn-search-wrap { flex:1; min-width:200px; position:relative; }
    .pn-search-icon { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#475569; pointer-events:none; }
    .pn-search { width:100%; padding:9px 14px 9px 38px; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:10px; color:#f1f5f9; font-size:13px; font-family:var(--font); outline:none; transition:all 0.2s; }
    .pn-search:focus { border-color:rgba(99,102,241,0.5); background:rgba(99,102,241,0.06); box-shadow:0 0 0 3px rgba(99,102,241,0.1); }
    .pn-search::placeholder { color:#475569; }
    .pn-counter { font-size:12px; font-weight:600; color:#94a3b8; background:rgba(99,102,241,0.1); border:1px solid rgba(99,102,241,0.2); border-radius:8px; padding:6px 12px; white-space:nowrap; }
    .pn-counter span { color:#818cf8; }
    .pn-global-btns { display:flex; gap:6px; }
    .btn-pn-global { padding:7px 14px; font-size:12px; border-radius:9px; border:1px solid rgba(255,255,255,0.13); background:rgba(255,255,255,0.05); color:#94a3b8; cursor:pointer; transition:all 0.2s; font-family:var(--font); font-weight:600; }
    .btn-pn-global:hover { background:rgba(255,255,255,0.1); color:#f1f5f9; }
    .btn-pn-global.sel { background:rgba(99,102,241,0.15); border-color:rgba(99,102,241,0.3); color:#818cf8; }

    /* Root blocks */
    .pn-root-block { background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.07); border-radius:14px; margin-bottom:10px; overflow:hidden; }
    .pn-root-header { display:flex; align-items:center; justify-content:space-between; padding:12px 16px; border-bottom:1px solid rgba(255,255,255,0.06); background:rgba(255,255,255,0.02); }
    .pn-root-title { font-size:13px; font-weight:700; color:#f1f5f9; display:flex; align-items:center; gap:8px; }
    .pn-root-title svg { color:#818cf8; }
    .pn-root-actions { display:flex; gap:5px; }
    .btn-tree-sel { padding:4px 10px; font-size:11px; border-radius:7px; border:1px solid rgba(255,255,255,0.12); background:rgba(255,255,255,0.05); color:#94a3b8; cursor:pointer; transition:all 0.2s; font-family:var(--font); }
    .btn-tree-sel:hover { background:rgba(255,255,255,0.1); color:#f1f5f9; }

    /* Tree rows */
    .pn-row { border-bottom:1px solid rgba(255,255,255,0.04); }
    .pn-row:last-child { border-bottom:none; }
    .pn-row-inner { display:flex; align-items:center; gap:8px; padding:8px 12px; position:relative; }
    .pn-row-inner:hover { background:rgba(255,255,255,0.03); }
    .pn-row-inner:hover .pn-child-actions { opacity:1; }
    .pn-expand { width:20px; height:20px; display:flex; align-items:center; justify-content:center; background:none; border:none; cursor:pointer; color:#475569; border-radius:5px; flex-shrink:0; transition:all 0.2s; }
    .pn-expand:hover { background:rgba(255,255,255,0.08); color:#94a3b8; }
    .pn-expand svg { transition:transform 0.2s; }
    .pn-expand.open svg { transform:rotate(90deg); }
    .pn-expand-ph { width:20px; height:20px; flex-shrink:0; }
    .pn-label { display:flex; align-items:center; gap:8px; cursor:pointer; flex:1; }
    .pn-check { width:15px; height:15px; accent-color:#6366f1; cursor:pointer; flex-shrink:0; }
    .pn-name { font-size:13px; color:#cbd5e1; user-select:none; }
    .pn-clave { font-size:10px; color:#475569; background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.08); border-radius:4px; padding:1px 6px; font-family:monospace; user-select:none; }
    .pn-child-actions { display:flex; gap:4px; opacity:0; transition:opacity 0.15s; margin-left:auto; flex-shrink:0; }
    .pn-mini-btn { padding:2px 7px; font-size:10px; border-radius:5px; border:1px solid rgba(255,255,255,0.1); background:rgba(255,255,255,0.05); color:#64748b; cursor:pointer; transition:all 0.15s; font-family:var(--font); }
    .pn-mini-btn:hover { background:rgba(99,102,241,0.15); color:#818cf8; border-color:rgba(99,102,241,0.25); }
    .pn-children { }
    .pn-children.hidden { display:none; }
    .pn-sep { font-size:10px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#475569; padding:8px 16px 4px; background:rgba(255,255,255,0.01); border-bottom:1px solid rgba(255,255,255,0.04); }
    .pn-no-results { padding:24px; text-align:center; font-size:13px; color:#475569; }
    .pn-row.pn-hidden { display:none; }

    .form-actions { display:flex; gap:12px; justify-content:flex-end; margin-top:4px; }
    .edit-meta-tag { font-size:12px; color:#64748b; background:rgba(99,102,241,0.08); border:1px solid rgba(99,102,241,0.2); border-radius:8px; padding:5px 12px; display:inline-flex; align-items:center; gap:6px; }
</style>

<div class="pn-header">
    <div>
        <h2 class="pn-header-title">Editar Programa Nivel</h2>
        <span style="font-size:13px; color:#64748b;">Configura los permisos de módulos del programa</span>
    </div>
    <div style="display:flex; gap:10px; align-items:center;">
        <?php if (!empty($programa['id'])): ?>
        <span class="edit-meta-tag">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            ID #<?php echo (int)$programa['id']; ?>
        </span>
        <?php endif; ?>
        <a href="<?php echo BASE_URL; ?>/programa-nivel" class="btn btn-glass">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
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
                <label style="display:flex; align-items:center; gap:10px; cursor:pointer; margin-top:6px;">
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

        <?php if (empty($arbol)): ?>
        <div style="padding:48px; text-align:center; color:#475569;">
            <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin:0 auto 14px; display:block; opacity:0.25;"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            <div style="font-size:14px;">No hay módulos configurados</div>
            <div style="font-size:12px; margin-top:4px; color:#374151;">
                <a href="<?php echo BASE_URL; ?>/modulos-erp" style="color:#818cf8;">Ir a Módulos ERP</a> para crearlos.
            </div>
        </div>
        <?php else: ?>

        <!-- Toolbar: search + counter + global actions -->
        <div class="pn-toolbar">
            <div class="pn-search-wrap">
                <span class="pn-search-icon">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </span>
                <input type="text" id="pnSearch" class="pn-search" placeholder="Buscar módulo...">
            </div>
            <div class="pn-counter" id="pnCounter">
                <span id="pnGranted"><?php echo $grantedMods; ?></span> / <span><?php echo $totalMods; ?></span> módulos seleccionados
            </div>
            <div class="pn-global-btns">
                <button type="button" class="btn-pn-global sel" id="btnSelAll">Seleccionar todo</button>
                <button type="button" class="btn-pn-global" id="btnDeselAll">Limpiar todo</button>
            </div>
        </div>

        <div id="pnTree">
        <?php foreach ($arbol as $root): ?>
        <div class="pn-root-block" id="rootblock_<?php echo (int)$root['id']; ?>">
            <div class="pn-root-header">
                <span class="pn-root-title">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    <?php echo htmlspecialchars($root['nombre'] ?? ($root['clave'] ?? '')); ?>
                </span>
                <div class="pn-root-actions">
                    <button type="button" class="btn-tree-sel" data-action="select-all" data-root="rootblock_<?php echo (int)$root['id']; ?>">+ Todos</button>
                    <button type="button" class="btn-tree-sel" data-action="deselect-all" data-root="rootblock_<?php echo (int)$root['id']; ?>">– Ninguno</button>
                </div>
            </div>
            <div class="pn-root-body">
                <?php renderTreeNodeEditar($root, 0); ?>
            </div>
        </div>
        <?php endforeach; ?>
        </div>
        <div class="pn-no-results" id="pnNoResults" style="display:none;">Sin módulos que coincidan con la búsqueda.</div>

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
    // ── Toggle activo label ───────────────────────────────────
    var activoToggle = document.getElementById('activoToggle');
    var activoLabel  = document.getElementById('activoLabel');
    if (activoToggle && activoLabel) {
        activoToggle.addEventListener('change', function () {
            activoLabel.textContent = this.checked ? 'Activo' : 'Inactivo';
        });
    }

    // ── Expand/collapse children ─────────────────────────────
    document.querySelectorAll('.pn-expand').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var targetId = this.getAttribute('data-target');
            var children = document.getElementById(targetId);
            if (!children) return;
            children.classList.toggle('hidden');
            this.classList.toggle('open');
        });
    });

    // ── Cascade: parent → children, update counter ───────────
    function updateCounter() {
        var all = document.querySelectorAll('.pn-check');
        var checked = Array.from(all).filter(function(c){ return c.checked; }).length;
        var el = document.getElementById('pnGranted');
        if (el) el.textContent = checked;
    }

    function cascadeDown(item, state) {
        var children = item.querySelector(':scope > .pn-children');
        if (!children) return;
        children.querySelectorAll('.pn-check').forEach(function (c) {
            c.checked = state;
            c.indeterminate = false;
        });
    }

    function updateParentState(container) {
        if (!container) return;
        var parentRow = container.closest('.pn-row');
        if (!parentRow) return;
        var parentChk = parentRow.querySelector(':scope > .pn-row-inner > .pn-label > .pn-check');
        if (!parentChk) return;
        var childChecks = Array.from(container.querySelectorAll(':scope > .pn-row > .pn-row-inner > .pn-label > .pn-check'));
        if (!childChecks.length) return;
        var allChecked  = childChecks.every(function (c) { return c.checked; });
        var someChecked = childChecks.some(function  (c) { return c.checked; });
        parentChk.indeterminate = someChecked && !allChecked;
        parentChk.checked = allChecked;
        updateParentState(parentRow.closest('.pn-children'));
    }

    document.querySelectorAll('.pn-check').forEach(function (chk) {
        chk.addEventListener('change', function () {
            var row = this.closest('.pn-row');
            if (row) cascadeDown(row, this.checked);
            var container = row ? row.parentElement : null;
            if (container && container.classList.contains('pn-children')) {
                updateParentState(container);
            }
            updateCounter();
        });
    });

    // ── Select/deselect all per root block ───────────────────
    document.querySelectorAll('[data-action="select-all"], [data-action="deselect-all"]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var rootId = this.getAttribute('data-root');
            var root   = document.getElementById(rootId);
            if (!root) return;
            var state  = this.getAttribute('data-action') === 'select-all';
            root.querySelectorAll('.pn-check').forEach(function (c) { c.checked = state; c.indeterminate = false; });
            updateCounter();
        });
    });

    // ── Per-node child actions (+todos / -todos on hover) ────
    document.querySelectorAll('.pn-mini-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var rootId = this.getAttribute('data-root');
            var block  = document.getElementById(rootId);
            if (!block) return;
            var state  = this.getAttribute('data-block-sel') === '1';
            block.querySelectorAll('.pn-check').forEach(function (c) { c.checked = state; c.indeterminate = false; });
            updateCounter();
        });
    });

    // ── Global select / deselect ─────────────────────────────
    var btnSelAll   = document.getElementById('btnSelAll');
    var btnDeselAll = document.getElementById('btnDeselAll');
    if (btnSelAll) {
        btnSelAll.addEventListener('click', function () {
            document.querySelectorAll('.pn-check').forEach(function (c) { c.checked = true; c.indeterminate = false; });
            updateCounter();
        });
    }
    if (btnDeselAll) {
        btnDeselAll.addEventListener('click', function () {
            document.querySelectorAll('.pn-check').forEach(function (c) { c.checked = false; c.indeterminate = false; });
            updateCounter();
        });
    }

    // ── Live search ──────────────────────────────────────────
    var searchInput = document.getElementById('pnSearch');
    var noResults   = document.getElementById('pnNoResults');
    var pnTree      = document.getElementById('pnTree');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            var q = this.value.trim().toLowerCase();
            if (!q) {
                // Show everything
                document.querySelectorAll('.pn-row').forEach(function (r) { r.classList.remove('pn-hidden'); });
                document.querySelectorAll('.pn-root-block').forEach(function (b) { b.style.display = ''; });
                document.querySelectorAll('.pn-children').forEach(function (c) { c.classList.remove('hidden'); });
                document.querySelectorAll('.pn-expand').forEach(function (b) { b.classList.add('open'); });
                if (noResults) noResults.style.display = 'none';
                return;
            }
            var anyVisible = false;
            document.querySelectorAll('.pn-root-block').forEach(function (block) {
                var rows = Array.from(block.querySelectorAll('.pn-row'));
                var blockVisible = false;
                // Expand all children when searching
                block.querySelectorAll('.pn-children').forEach(function (c) { c.classList.remove('hidden'); });
                block.querySelectorAll('.pn-expand').forEach(function (b) { b.classList.add('open'); });
                rows.forEach(function (row) {
                    var lbl = (row.getAttribute('data-label') || '').toLowerCase();
                    if (lbl.indexOf(q) !== -1) {
                        row.classList.remove('pn-hidden');
                        blockVisible = true;
                        anyVisible   = true;
                        // Ensure all parent containers are visible
                        var p = row.parentElement;
                        while (p && p !== block) {
                            if (p.classList.contains('pn-children')) p.classList.remove('hidden');
                            if (p.classList.contains('pn-row')) p.classList.remove('pn-hidden');
                            p = p.parentElement;
                        }
                    } else {
                        row.classList.add('pn-hidden');
                    }
                });
                block.style.display = blockVisible ? '' : 'none';
            });
            if (noResults) noResults.style.display = anyVisible ? 'none' : '';
        });
    }

    // ── Initialize indeterminate states on load ───────────────
    document.querySelectorAll('.pn-row').forEach(function (row) {
        var children = row.querySelector('.pn-children');
        if (!children) return;
        updateParentState(children);
    });
    updateCounter();
})();
</script>
<?php
$content = ob_get_clean();
include VIEWS_PATH . 'layouts/app.php';
?>
