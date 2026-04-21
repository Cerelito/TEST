<?php
$title = 'Centros de Costo';
ob_start();
?>
<div class="d-flex gap-2 align-center sticky-bar" style="margin-bottom:16px;">
  <select id="filtroEmpresa" class="form-control" style="max-width:280px;" onchange="location.href='<?= BASE_URL ?>/centros-costo?empresa_id='+this.value">
    <option value="">Todas las empresas</option>
    <?php foreach($empresas??[] as $e): ?>
    <option value="<?= $e['id'] ?>" <?= ($_GET['empresa_id']??'')==$e['id']?'selected':'' ?>><?= htmlspecialchars($e['nombre']) ?></option>
    <?php endforeach; ?>
  </select>
  <div class="search-wrap">
    <span class="search-icon">🔍</span>
    <input type="text" class="form-control" placeholder="Buscar CC..." data-search-table="tbl-cc" style="padding-left:34px;width:200px;">
  </div>
  <button class="btn btn-primary" style="margin-left:auto;" data-modal-open="modal-cc">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Nuevo Centro
  </button>
</div>

<!-- KPIs rápidos -->
<div class="kpi-grid mb-4">
  <div class="glass kpi-card">
    <div class="kpi-label">Total CCs</div>
    <div class="kpi-value kpi-accent-blue"><?= count($centros??[]) ?></div>
  </div>
  <div class="glass kpi-card">
    <div class="kpi-label">Empresas</div>
    <div class="kpi-value kpi-accent-teal"><?= count($empresas??[]) ?></div>
  </div>
</div>

<!-- Tabla -->
<div class="glass table-container">
  <div class="table-header">
    <div class="table-title">Centros de Costo registrados</div>
  </div>
  <div class="table-scroll">
    <table id="tbl-cc">
      <thead>
        <tr>
          <th>Empresa</th>
          <th>Código</th>
          <th>Descripción</th>
          <th>Empleados</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($centros??[] as $cc): ?>
        <tr>
          <td class="td-muted"><?= htmlspecialchars($cc['empresa_nombre']??'—') ?></td>
          <td><span class="badge badge-blue"><?= htmlspecialchars($cc['codigo']) ?></span></td>
          <td><?= htmlspecialchars($cc['descripcion']) ?></td>
          <td>
            <?php if($cc['total_empleados']>0): ?>
            <span class="badge badge-teal"><?= $cc['total_empleados'] ?> emp.</span>
            <?php else: ?><span class="td-muted">0</span><?php endif; ?>
          </td>
          <td>
            <?= $cc['activo'] ? '<span class="badge badge-teal">● Activo</span>' : '<span class="badge badge-red">● Inactivo</span>' ?>
          </td>
          <td>
            <button class="btn btn-glass btn-xs" onclick="editCC(<?= htmlspecialchars(json_encode($cc)) ?>)">Editar</button>
            <form method="POST" action="<?= BASE_URL ?>/centros-costo/eliminar/<?= $cc['id'] ?>" style="display:inline;">
              <?= csrfField() ?>
              <button type="submit" class="btn btn-danger btn-xs" data-confirm="¿Eliminar este centro de costo?">Eliminar</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if(empty($centros)): ?>
        <tr><td colspan="6"><div class="empty-state"><div class="empty-icon">🏢</div><div class="empty-title">Sin centros de costo</div></div></td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal Crear/Editar CC -->
<div class="modal-backdrop" id="modal-cc">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title" id="modal-cc-title">Nuevo Centro de Costo</div>
      <button class="modal-close" data-modal-close>✕</button>
    </div>
    <form method="POST" action="<?= BASE_URL ?>/centros-costo/guardar" id="form-cc">
      <?= csrfField() ?>
      <input type="hidden" name="id" id="cc-id">
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Empresa *</label>
          <select name="empresa_id" id="cc-empresa" class="form-control" required>
            <option value="">— Seleccionar —</option>
            <?php foreach($empresas??[] as $e): ?>
            <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['nombre']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-row form-row-2">
          <div class="form-group">
            <label class="form-label">Código *</label>
            <input type="text" name="codigo" id="cc-codigo" class="form-control" required maxlength="50" placeholder="ej: P04">
          </div>
          <div class="form-group">
            <label class="form-label">Estado</label>
            <select name="activo" id="cc-activo" class="form-control">
              <option value="1">Activo</option>
              <option value="0">Inactivo</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Descripción *</label>
          <input type="text" name="descripcion" id="cc-descripcion" class="form-control" required placeholder="ej: CONSTRUCCION-PERIFERICO">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-glass" data-modal-close>Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>

<script>
function editCC(cc) {
  document.getElementById('modal-cc-title').textContent = 'Editar Centro de Costo';
  document.getElementById('cc-id').value = cc.id;
  document.getElementById('cc-empresa').value = cc.empresa_id;
  document.getElementById('cc-codigo').value = cc.codigo;
  document.getElementById('cc-descripcion').value = cc.descripcion;
  document.getElementById('cc-activo').value = cc.activo;
  openModal('modal-cc');
}
</script>
<?php
$content = ob_get_clean();
include VIEWS_PATH . 'layouts/app.php';
?>
