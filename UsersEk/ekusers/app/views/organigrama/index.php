<?php
$title = 'Organigrama';
ob_start();
?>
<!-- Selector de empresa -->
<div class="glass d-flex gap-3 align-center sticky-bar" style="padding:16px 20px;border-radius:12px;margin-bottom:16px;flex-wrap:wrap;">
  <label class="form-label" style="margin:0;white-space:nowrap;">Empresa:</label>
  <select id="empresaSelect" class="form-control" style="max-width:320px;" onchange="loadOrganigrama(this.value)">
    <option value="">— Selecciona empresa —</option>
    <?php foreach($empresas ?? [] as $e): ?>
    <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['nombre']) ?></option>
    <?php endforeach; ?>
  </select>
  <div class="d-flex gap-2" style="margin-left:auto;">
    <button class="btn btn-glass btn-sm" onclick="window.print()">🖨 Imprimir</button>
    <button class="btn btn-glass btn-sm" id="zoomIn">+</button>
    <button class="btn btn-glass btn-sm" id="zoomOut">–</button>
  </div>
</div>

<!-- Org Chart container -->
<div class="glass" style="padding:0;overflow:hidden;border-radius:var(--radius-lg);min-height:500px;">
  <div class="table-header">
    <div class="table-title">Organigrama de Empleados</div>
    <div id="org-empresa-name" class="text-muted text-sm"></div>
  </div>
  <div id="org-container" style="overflow:auto;padding:32px;min-height:400px;display:flex;align-items:flex-start;justify-content:center;">
    <div class="empty-state">
      <div class="empty-icon">🌿</div>
      <div class="empty-title">Selecciona una empresa</div>
      <p class="empty-text">Para visualizar el organigrama de empleados</p>
    </div>
  </div>
</div>

<!-- Lista plana de empleados -->
<div class="glass table-container mt-4">
  <div class="table-header">
    <div class="table-title">Relación de Empleados</div>
    <div class="search-wrap">
      <span class="search-icon">🔍</span>
      <input type="text" class="form-control" placeholder="Buscar..." data-search-table="tbl-emp-org" style="padding-left:34px;width:200px;">
    </div>
  </div>
  <div class="table-scroll">
    <table id="tbl-emp-org">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Puesto</th>
          <th>Empresa</th>
          <th>Programa Nivel</th>
          <th>Jefe Directo</th>
          <th>Rol CC</th>
        </tr>
      </thead>
      <tbody id="emp-org-tbody">
        <tr><td colspan="7"><div class="empty-state" style="padding:30px;"><p class="text-muted">Selecciona una empresa para ver el listado.</p></div></td></tr>
      </tbody>
    </table>
  </div>
</div>

<script>
var orgScale = 1;
document.getElementById('zoomIn').onclick = function() {
  orgScale = Math.min(2, orgScale + 0.1);
  document.getElementById('org-container').querySelector('.org-root') &&
    (document.getElementById('org-container').querySelector('.org-root').style.transform = 'scale('+orgScale+')');
};
document.getElementById('zoomOut').onclick = function() {
  orgScale = Math.max(0.3, orgScale - 0.1);
  document.getElementById('org-container').querySelector('.org-root') &&
    (document.getElementById('org-container').querySelector('.org-root').style.transform = 'scale('+orgScale+')');
};

function loadOrganigrama(empresaId) {
  if (!empresaId) return;
  var container = document.getElementById('org-container');
  var tbody = document.getElementById('emp-org-tbody');
  container.innerHTML = '<div class="empty-state"><div class="spinner"></div><p class="text-muted mt-2">Cargando...</p></div>';
  tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:20px;color:var(--text-secondary);">Cargando...</td></tr>';

  fetch('<?= BASE_URL ?>/organigrama/data?empresa_id=' + empresaId)
    .then(function(r){ return r.json(); })
    .then(function(data) {
      if (!data.ok) {
        container.innerHTML = '<div class="empty-state"><div class="empty-icon">⚠</div><p>'+data.error+'</p></div>';
        return;
      }
      // Set empresa name
      document.getElementById('org-empresa-name').textContent = data.empresa || '';
      // Render tree
      if (data.empleados && data.empleados.length) {
        renderOrgTree(container, data.empleados);
        renderOrgTable(tbody, data.empleados);
      } else {
        container.innerHTML = '<div class="empty-state"><div class="empty-icon">👤</div><div class="empty-title">Sin empleados</div><p class="empty-text">Esta empresa no tiene empleados registrados.</p></div>';
        tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:20px;color:var(--text-secondary);">Sin datos</td></tr>';
      }
    })
    .catch(function(e) {
      container.innerHTML = '<div class="empty-state"><div class="empty-icon">⚠</div><p class="text-muted">Error al cargar datos.</p></div>';
    });
}

function renderOrgTree(container, empleados) {
  // Build map
  var map = {};
  empleados.forEach(function(e){ map[e.id] = Object.assign({}, e, {subordinados:[]}); });
  var roots = [];
  empleados.forEach(function(e) {
    if (e.jefe_id && map[e.jefe_id]) {
      map[e.jefe_id].subordinados.push(map[e.id]);
    } else {
      roots.push(map[e.id]);
    }
  });
  var html = '<div class="org-root" style="transform-origin:top center;transition:transform 0.2s;">';
  roots.forEach(function(r){ html += buildNodeHTML(r); });
  html += '</div>';
  container.innerHTML = html;
}

function buildNodeHTML(node) {
  var initials = (node.nombre||'?').split(' ').slice(0,2).map(function(w){return w[0]||'?';}).join('').toUpperCase();
  var childrenHtml = '';
  if (node.subordinados && node.subordinados.length) {
    childrenHtml = '<div style="display:flex;gap:20px;justify-content:center;margin-top:20px;position:relative;">';
    node.subordinados.forEach(function(s){ childrenHtml += buildNodeHTML(s); });
    childrenHtml += '</div>';
  }
  return '<div style="display:flex;flex-direction:column;align-items:center;">' +
    '<div class="org-connector" style="height:20px;width:1px;background:var(--glass-border);"></div>' +
    '<div class="glass org-card" style="min-width:150px;text-align:center;padding:14px 16px;">' +
      '<div class="org-avatar" style="margin:0 auto 8px;">' + initials + '</div>' +
      '<div class="org-name" style="font-size:11.5px;font-weight:700;line-height:1.3;">' + (node.nombre||'') + '</div>' +
      '<div class="org-role" style="font-size:10px;color:var(--text-secondary);margin-top:3px;">' + (node.puesto||'') + '</div>' +
      (node.programa_nivel ? '<div class="badge badge-blue mt-1" style="font-size:9px;">' + node.programa_nivel + '</div>' : '') +
    '</div>' +
    (childrenHtml ? '<div style="height:20px;width:1px;background:var(--glass-border);"></div>' + childrenHtml : '') +
  '</div>';
}

function renderOrgTable(tbody, empleados) {
  var html = '';
  empleados.forEach(function(e) {
    var rolBadge = '';
    if (e.es_requisitor && e.es_comprador) rolBadge = '<span class="chip-both">REQ + OC</span>';
    else if (e.es_requisitor) rolBadge = '<span class="chip-req">Requisitor</span>';
    else if (e.es_comprador) rolBadge = '<span class="chip-oc">Comprador</span>';
    html += '<tr>' +
      '<td class="td-muted">' + (e.user_id||'—') + '</td>' +
      '<td><strong>' + (e.nombre||'') + '</strong></td>' +
      '<td class="td-muted">' + (e.puesto||'—') + '</td>' +
      '<td class="td-muted">' + (e.empresa_nombre||'—') + '</td>' +
      '<td>' + (e.programa_nivel ? '<span class="badge badge-blue">'+e.programa_nivel+'</span>' : '<span class="td-muted">—</span>') + '</td>' +
      '<td class="td-muted">' + (e.jefe_nombre||'—') + '</td>' +
      '<td>' + (rolBadge||'<span class="td-muted">—</span>') + '</td>' +
    '</tr>';
  });
  tbody.innerHTML = html || '<tr><td colspan="7" style="text-align:center;padding:20px;color:var(--text-secondary);">Sin datos</td></tr>';
}
</script>
<?php
$content = ob_get_clean();
include VIEWS_PATH . 'layouts/app.php';
?>
