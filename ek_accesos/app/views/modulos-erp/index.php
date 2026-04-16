<?php
$title    = 'Módulos ERP';
$tree     = $tree     ?? [];
$flatList = $flatList ?? [];
ob_start();

// Recursive render function
function renderModuloTree(array $nodes, int $depth = 0): void
{
    foreach ($nodes as $node):
        $id       = (int)$node['id'];
        $nombre   = htmlspecialchars($node['nombre']);
        $clave    = htmlspecialchars($node['clave']);
        $hijos    = (int)($node['hijos_count']   ?? 0);
        $permisos = (int)($node['permisos_count'] ?? 0);
        $activo   = !empty($node['activo']);
        $esSep    = !empty($node['es_separador']);
        $hasKids  = !empty($node['children']);
        $nodeId   = 'node-' . $id;
        $indent   = $depth * 24;
        ?>
        <div class="mod-row <?= $activo ? '' : 'mod-inactive' ?>" id="<?= $nodeId ?>-row">
            <div class="mod-cell mod-name" style="padding-left:<?= 16 + $indent ?>px;">
                <?php if ($hasKids): ?>
                <button class="toggle-btn" onclick="toggleNode('<?= $nodeId ?>')" title="Expandir/Colapsar">
                    <svg class="toggle-icon" id="<?= $nodeId ?>-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <?php else: ?>
                <span style="display:inline-block;width:22px;"></span>
                <?php endif; ?>

                <?php if ($esSep): ?>
                <span style="color:#475569;font-style:italic;">— <?= $nombre ?> —</span>
                <?php else: ?>
                <span class="mod-nombre"><?= $nombre ?></span>
                <?php endif; ?>
            </div>

            <div class="mod-cell mod-clave">
                <code><?= $clave ?></code>
            </div>

            <div class="mod-cell mod-stats">
                <?php if ($hijos > 0): ?>
                <span class="badge badge-blue"><?= $hijos ?> hijo<?= $hijos !== 1 ? 's' : '' ?></span>
                <?php endif; ?>
                <?php if ($permisos > 0): ?>
                <span class="badge badge-purple"><?= $permisos ?> permiso<?= $permisos !== 1 ? 's' : '' ?></span>
                <?php endif; ?>
                <?php if (!$activo): ?>
                <span class="badge badge-red">Inactivo</span>
                <?php endif; ?>
            </div>

            <div class="mod-cell mod-actions">
                <!-- Agregar hijo -->
                <button type="button" class="icon-btn icon-btn-add"
                        title="Agregar submódulo"
                        onclick="openCrear(<?= $id ?>, '<?= addslashes($clave) ?>', '<?= addslashes($nombre) ?>')">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                </button>

                <!-- Toggle activo -->
                <form method="POST" action="<?= BASE_URL ?>/modulos-erp/toggle-activo/<?= $id ?>" style="display:inline;">
                    <?= csrfField() ?>
                    <button type="submit" class="icon-btn <?= $activo ? 'icon-btn-toggle-on' : 'icon-btn-toggle-off' ?>"
                            title="<?= $activo ? 'Desactivar' : 'Activar' ?>">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <?php if ($activo): ?>
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                            <?php else: ?>
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>
                            <?php endif; ?>
                        </svg>
                    </button>
                </form>

                <!-- Editar -->
                <a href="<?= BASE_URL ?>/modulos-erp/editar/<?= $id ?>" class="icon-btn icon-btn-view" title="Editar">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </a>

                <!-- Eliminar -->
                <?php if ($hijos === 0): ?>
                <form method="POST" action="<?= BASE_URL ?>/modulos-erp/eliminar/<?= $id ?>" style="display:inline;"
                      onsubmit="return confirm('¿Eliminar el módulo «<?= htmlspecialchars(addslashes($nombre)) ?>»?\nSe borrarán también sus permisos asignados.')">
                    <?= csrfField() ?>
                    <button type="submit" class="icon-btn icon-btn-del" title="Eliminar módulo">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                    </button>
                </form>
                <?php else: ?>
                <span class="icon-btn icon-btn-disabled" title="Tiene submódulos — no se puede eliminar">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" opacity=".3"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                </span>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($hasKids): ?>
        <div class="mod-children" id="<?= $nodeId ?>-children">
            <?php renderModuloTree($node['children'], $depth + 1); ?>
        </div>
        <?php endif; ?>
    <?php endforeach;
}
?>
<style>
.page-hdr { display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; flex-wrap:wrap; gap:12px; }
.page-hdr h2 { font-size:26px; font-weight:800; color:#f1f5f9; letter-spacing:-0.5px; }
.mod-tree-wrap { overflow-x:auto; border-radius:var(--radius-lg); }
.mod-tree { border-radius:var(--radius-lg); overflow:hidden; min-width:560px; }
.mod-tree-header {
    display:grid; grid-template-columns:1fr 180px 160px 116px;
    padding:10px 16px; background:rgba(255,255,255,0.04);
    border-bottom:1px solid rgba(255,255,255,0.07);
    font-size:11px; font-weight:700; letter-spacing:.8px; text-transform:uppercase; color:#475569;
}
.mod-row {
    display:grid; grid-template-columns:1fr 180px 160px 116px;
    border-bottom:1px solid rgba(255,255,255,0.04);
    min-height:42px; align-items:center;
    transition:background .15s;
}
.mod-row:hover { background:rgba(255,255,255,0.03); }
.mod-inactive { opacity:.5; }
.mod-cell { padding:8px 12px; font-size:13px; }
.mod-name { display:flex; align-items:center; gap:6px; }
.mod-nombre { font-weight:500; color:#f1f5f9; }
.mod-clave code { font-size:11px; color:#64748b; background:rgba(255,255,255,0.05); padding:2px 6px; border-radius:4px; }
.mod-stats { display:flex; gap:5px; flex-wrap:wrap; }
.mod-actions { display:flex; gap:5px; align-items:center; }
.toggle-btn {
    background:none; border:none; cursor:pointer; color:#475569;
    display:flex; align-items:center; padding:2px; border-radius:4px;
    transition:color .15s;
}
.toggle-btn:hover { color:#94a3b8; }
.toggle-icon { transition:transform .2s; }
.toggle-icon.collapsed { transform:rotate(-90deg); }
.mod-children { border-left:1px solid rgba(99,102,241,0.15); margin-left:20px; }
.icon-btn {
    display:inline-flex; align-items:center; justify-content:center;
    width:28px; height:28px; border-radius:7px; border:1px solid;
    cursor:pointer; transition:all .15s; text-decoration:none; background:none; font-family:var(--font);
}
.icon-btn-add         { border-color:rgba(16,185,129,.3); color:#10b981; }
.icon-btn-add:hover   { background:rgba(16,185,129,.12); }
.icon-btn-toggle-on   { border-color:rgba(99,102,241,.3); color:#818cf8; }
.icon-btn-toggle-on:hover { background:rgba(99,102,241,.12); }
.icon-btn-toggle-off  { border-color:rgba(245,158,11,.3); color:#f59e0b; }
.icon-btn-toggle-off:hover { background:rgba(245,158,11,.12); }
.icon-btn-view        { border-color:rgba(99,102,241,.25); color:#818cf8; }
.icon-btn-view:hover  { background:rgba(99,102,241,.12); }
.icon-btn-del         { border-color:rgba(239,68,68,.25); color:#f87171; }
.icon-btn-del:hover   { background:rgba(239,68,68,.12); }
.icon-btn-disabled    { border-color:rgba(255,255,255,.06); color:#334155; cursor:default; }
/* Modal */
.modal-backdrop { display:none; position:fixed; inset:0; background:rgba(0,0,0,.6); backdrop-filter:blur(4px); z-index:300; align-items:center; justify-content:center; }
.modal-backdrop.open { display:flex; }
.modal { background:#111827; border:1px solid rgba(255,255,255,.12); border-radius:18px; width:100%; max-width:500px; overflow:hidden; }
.modal-header { padding:20px 24px 0; display:flex; align-items:center; justify-content:space-between; }
.modal-title { font-size:16px; font-weight:700; color:#f1f5f9; }
.modal-close { background:none; border:none; color:#64748b; cursor:pointer; font-size:20px; line-height:1; padding:4px; }
.modal-close:hover { color:#f1f5f9; }
.modal-body { padding:20px 24px; }
.modal-footer { padding:0 24px 20px; display:flex; justify-content:flex-end; gap:10px; }
.clave-preview { font-family:monospace; font-size:12px; color:#6366f1; background:rgba(99,102,241,.08); padding:6px 10px; border-radius:6px; margin-top:4px; word-break:break-all; }
/* Import panel */
.import-panel { margin-bottom:20px; border:1px solid rgba(255,255,255,0.1); border-radius:16px; overflow:hidden; background:rgba(255,255,255,0.03); }
.import-toggle { width:100%; display:flex; align-items:center; gap:10px; padding:14px 20px; background:none; border:none; color:#94a3b8; font-size:14px; font-weight:600; font-family:var(--font); cursor:pointer; text-align:left; transition:color .2s; }
.import-toggle:hover { color:#f1f5f9; }
.import-toggle svg:first-child { color:#818cf8; }
.import-chevron { margin-left:auto; transition:transform .25s; color:#475569; }
.import-chevron.open { transform:rotate(180deg); }
.import-body { padding:0 20px 20px; }
.import-info { display:flex; gap:12px; padding:14px 16px; background:rgba(79,142,247,0.07); border:1px solid rgba(79,142,247,0.18); border-radius:12px; font-size:12.5px; color:#94a3b8; line-height:1.7; }
.import-info strong { color:#f1f5f9; }
.import-info code { background:rgba(255,255,255,0.08); padding:1px 5px; border-radius:4px; font-size:11px; color:#a5b4fc; }
.drop-zone { border:2px dashed rgba(255,255,255,0.12); border-radius:12px; padding:24px 20px; text-align:center; cursor:pointer; transition:all .2s; background:rgba(255,255,255,0.02); }
.drop-zone:hover, .drop-zone.drag-over { border-color:rgba(99,102,241,0.4); background:rgba(99,102,241,0.05); }
.drop-zone.has-file { border-color:rgba(16,185,129,0.4); background:rgba(16,185,129,0.05); }
</style>

<div class="page-hdr">
    <div>
        <h2>Módulos ERP</h2>
        <p style="font-size:13px;color:#64748b;margin-top:2px;">
            Administra el árbol de módulos y sus submódulos. Los cambios aquí afectan los checkboxes de todos los Programa Nivel.
        </p>
    </div>
    <button type="button" class="btn btn-primary" onclick="openCrear(0,'','Raíz')">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Nuevo Módulo Raíz
    </button>
</div>

<!-- ── Import CSV panel ──────────────────────────────────────────────────── -->
<div class="import-panel" id="importPanel">
    <button type="button" class="import-toggle" id="importToggle" onclick="toggleImport()">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
        Importar desde CSV / Excel
        <svg class="import-chevron" id="importChevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
    </button>

    <div class="import-body" id="importBody" style="display:none;">
        <div class="import-info">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px;color:#6fa8ff;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <div>
                <strong>¿Cómo funciona?</strong><br>
                1. Descarga la plantilla CSV → llénala en Excel → guarda como <em>CSV UTF-8</em> → súbela aquí.<br>
                Columnas: <code>nombre</code>, <code>clave</code> (opcional), <code>parent_clave</code> (opcional), <code>orden</code>, <code>es_separador</code>.<br>
                El sistema resuelve la jerarquía automáticamente por <code>parent_clave</code>. Los módulos existentes no se duplican.
            </div>
        </div>

        <div style="display:flex; gap:16px; align-items:flex-start; flex-wrap:wrap; margin-top:16px;">
            <a href="<?= BASE_URL ?>/modulos-erp/plantilla" class="btn btn-glass" style="flex-shrink:0;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Descargar plantilla
            </a>

            <form method="POST" action="<?= BASE_URL ?>/modulos-erp/importar"
                  enctype="multipart/form-data" id="importForm" style="flex:1; min-width:260px;">
                <?= csrfField() ?>
                <div class="drop-zone" id="dropZone"
                     ondragover="event.preventDefault();this.classList.add('drag-over')"
                     ondragleave="this.classList.remove('drag-over')"
                     ondrop="handleDrop(event)">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color:#475569;margin-bottom:8px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    <div id="dropLabel" style="font-size:13px;color:#64748b;">Arrastra tu CSV aquí o <label for="csvFile" style="color:#818cf8;cursor:pointer;text-decoration:underline;">selecciona archivo</label></div>
                    <div id="fileName" style="font-size:12px;color:#818cf8;margin-top:6px;display:none;"></div>
                    <input type="file" name="archivo" id="csvFile" accept=".csv" style="display:none;" onchange="showFileName(this)">
                </div>
                <button type="submit" class="btn btn-primary" id="importBtn" style="margin-top:12px;width:100%;" disabled>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    Importar módulos
                </button>
            </form>
        </div>
    </div>
</div>

<?php if (empty($tree)): ?>
<div class="glass" style="padding:60px 20px;text-align:center;border-radius:var(--radius-lg);">
    <p style="color:#64748b;font-size:15px;">No hay módulos registrados.</p>
    <p style="color:#475569;font-size:13px;margin-top:6px;">Crea el primer módulo raíz usando el botón de arriba.</p>
</div>
<?php else: ?>
<div class="glass mod-tree-wrap">
    <div class="mod-tree">
        <div class="mod-tree-header">
            <div>Módulo</div>
            <div>Clave</div>
            <div>Info</div>
            <div>Acciones</div>
        </div>
        <?php renderModuloTree($tree); ?>
    </div>
</div>
<?php endif; ?>

<!-- ── Modal: Crear Módulo ─────────────────────────────────────────────── -->
<div class="modal-backdrop" id="modalCrear" onclick="if(event.target===this)this.classList.remove('open')">
  <div class="modal">
    <div class="modal-header">
      <span class="modal-title" id="modalCrearTitle">Nuevo Módulo</span>
      <button type="button" class="modal-close" onclick="document.getElementById('modalCrear').classList.remove('open')">&times;</button>
    </div>
    <form method="POST" action="<?= BASE_URL ?>/modulos-erp/guardar">
      <?= csrfField() ?>
      <div class="modal-body">

        <input type="hidden" name="parent_id" id="mc_parent_id" value="0">

        <div id="mc_parent_info" style="display:none;margin-bottom:16px;padding:10px 14px;background:rgba(99,102,241,.08);border:1px solid rgba(99,102,241,.2);border-radius:10px;font-size:13px;color:#94a3b8;">
          Submódulo de: <strong id="mc_parent_label" style="color:#818cf8;"></strong>
        </div>

        <div class="form-group">
          <label class="form-label">Nombre *</label>
          <input type="text" name="nombre" id="mc_nombre" class="form-control" required
                 placeholder="ej: Recursos Humanos" oninput="updateClavePreview()">
        </div>

        <div class="form-group">
          <label class="form-label" style="display:flex;align-items:center;justify-content:space-between;">
            Clave
            <span style="font-size:11px;color:#475569;font-weight:400;">Se genera automáticamente — puedes editarla</span>
          </label>
          <input type="text" name="clave" id="mc_clave" class="form-control"
                 placeholder="Se generará del nombre"
                 style="font-family:monospace;font-size:13px;">
          <div class="clave-preview" id="mc_clave_preview" style="display:none;"></div>
        </div>

        <div class="form-row form-row-2">
          <div class="form-group">
            <label class="form-label">Orden</label>
            <input type="number" name="orden" id="mc_orden" class="form-control" value="0" min="0">
          </div>
          <div class="form-group" style="display:flex;align-items:flex-end;padding-bottom:4px;">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;margin-top:8px;">
              <input type="checkbox" name="es_separador" value="1" style="width:15px;height:15px;">
              <span class="form-label" style="margin:0;">Es separador visual</span>
            </label>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-glass" onclick="document.getElementById('modalCrear').classList.remove('open')">Cancelar</button>
        <button type="submit" class="btn btn-primary">Crear Módulo</button>
      </div>
    </form>
  </div>
</div>

<script>
// Flat list of modules for clave generation
var flatModules = <?= json_encode(array_map(fn($m) => ['id' => $m['id'], 'clave' => $m['clave'], 'nombre' => $m['nombre']], $flatList), JSON_HEX_TAG) ?>;

function toSlug(str) {
    var map = {'á':'a','é':'e','í':'i','ó':'o','ú':'u','ü':'u','ñ':'n','à':'a','è':'e','ì':'i','ò':'o','ù':'u'};
    return str.toLowerCase().replace(/[áéíóúüñàèìòù]/g, function(c){ return map[c]||c; }).replace(/[^a-z0-9]+/g,'_').replace(/^_|_$/g,'');
}

function openCrear(parentId, parentClave, parentNombre) {
    document.getElementById('mc_parent_id').value = parentId;
    document.getElementById('mc_nombre').value    = '';
    document.getElementById('mc_clave').value     = '';
    document.getElementById('mc_clave_preview').style.display = 'none';

    var info = document.getElementById('mc_parent_info');
    if (parentId > 0) {
        document.getElementById('mc_parent_label').textContent = parentNombre + ' (' + parentClave + ')';
        info.style.display = 'block';
        document.getElementById('modalCrearTitle').textContent = 'Nuevo Submódulo';
    } else {
        info.style.display = 'none';
        document.getElementById('modalCrearTitle').textContent = 'Nuevo Módulo Raíz';
    }

    document.getElementById('modalCrear').classList.add('open');
    setTimeout(function(){ document.getElementById('mc_nombre').focus(); }, 150);

    // Store parent clave for preview
    window._mcParentClave = parentClave;
}

function updateClavePreview() {
    var nombre = document.getElementById('mc_nombre').value;
    var slug   = toSlug(nombre);
    if (!slug) { document.getElementById('mc_clave_preview').style.display='none'; return; }

    var parentClave = window._mcParentClave || '';
    var full = parentClave ? parentClave + '.' + slug : slug;

    var preview = document.getElementById('mc_clave_preview');
    preview.textContent = full;
    preview.style.display = 'block';

    // Auto-fill clave field only if user hasn't typed there
    var claveField = document.getElementById('mc_clave');
    if (!claveField.dataset.userEdited) {
        claveField.value = full;
    }
}

document.getElementById('mc_clave').addEventListener('input', function() {
    this.dataset.userEdited = '1';
});
document.getElementById('mc_nombre').addEventListener('focus', function() {
    delete document.getElementById('mc_clave').dataset.userEdited;
});

// Import panel toggle
function toggleImport() {
    var body    = document.getElementById('importBody');
    var chevron = document.getElementById('importChevron');
    var open    = body.style.display !== 'none';
    body.style.display = open ? 'none' : 'block';
    chevron.classList.toggle('open', !open);
}

function showFileName(input) {
    var zone  = document.getElementById('dropZone');
    var label = document.getElementById('fileName');
    var btn   = document.getElementById('importBtn');
    if (input.files && input.files[0]) {
        label.textContent = '✓ ' + input.files[0].name;
        label.style.display = 'block';
        zone.classList.add('has-file');
        btn.disabled = false;
    }
}

function handleDrop(e) {
    e.preventDefault();
    var zone = document.getElementById('dropZone');
    zone.classList.remove('drag-over');
    var file = e.dataTransfer.files[0];
    if (!file) return;
    if (!file.name.endsWith('.csv')) {
        alert('Solo se aceptan archivos .csv');
        return;
    }
    var input = document.getElementById('csvFile');
    var dt = new DataTransfer();
    dt.items.add(file);
    input.files = dt.files;
    showFileName(input);
}

// Toggle expand/collapse tree nodes
function toggleNode(nodeId) {
    var children = document.getElementById(nodeId + '-children');
    var icon     = document.getElementById(nodeId + '-icon');
    if (!children) return;
    if (children.style.display === 'none') {
        children.style.display = '';
        icon.classList.remove('collapsed');
    } else {
        children.style.display = 'none';
        icon.classList.add('collapsed');
    }
}
</script>
<?php
$content = ob_get_clean();
include VIEWS_PATH . 'layouts/app.php';
?>
