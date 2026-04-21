<?php
$title = 'Usuarios del Sistema';
ob_start();
?>
<div class="d-flex justify-between align-center mb-4">
  <div></div>
  <div class="d-flex gap-2">
    <a href="<?= BASE_URL ?>/usuarios/crear" class="btn btn-primary">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Nuevo Usuario
    </a>
  </div>
</div>

<!-- KPIs -->
<div class="kpi-grid">
  <div class="glass kpi-card">
    <div class="kpi-label">Total Usuarios</div>
    <div class="kpi-value kpi-accent-blue"><?= $stats['total'] ?? 0 ?></div>
  </div>
  <div class="glass kpi-card">
    <div class="kpi-label">Activos</div>
    <div class="kpi-value kpi-accent-teal"><?= $stats['activos'] ?? 0 ?></div>
  </div>
  <div class="glass kpi-card">
    <div class="kpi-label">Pendientes Aprobación</div>
    <div class="kpi-value" style="color:#f5c842;"><?= $stats['pendientes'] ?? 0 ?></div>
    <?php if(($stats['pendientes']??0) > 0): ?><div class="kpi-sub">⚠ Requieren revisión</div><?php endif; ?>
  </div>
  <div class="glass kpi-card">
    <div class="kpi-label">Administradores</div>
    <div class="kpi-value kpi-accent-purple"><?= $stats['admins'] ?? 0 ?></div>
  </div>
</div>

<!-- Filtros -->
<div class="glass d-flex gap-2 align-center mb-3" style="padding:12px 16px;border-radius:10px;">
  <a href="<?= BASE_URL ?>/usuarios" class="btn btn-sm <?= empty($_GET['filter']) ? 'btn-primary' : 'btn-glass' ?>">Todos</a>
  <a href="<?= BASE_URL ?>/usuarios?filter=pendiente" class="btn btn-sm <?= ($_GET['filter']??'')==='pendiente' ? 'btn-primary' : 'btn-glass' ?>">
    Pendientes <?php if(($stats['pendientes']??0) > 0): ?><span class="nav-badge"><?= $stats['pendientes'] ?></span><?php endif; ?>
  </a>
  <a href="<?= BASE_URL ?>/usuarios?filter=activo" class="btn btn-sm <?= ($_GET['filter']??'')==='activo' ? 'btn-primary' : 'btn-glass' ?>">Activos</a>
  <div class="search-wrap" style="margin-left:auto;">
    <span class="search-icon">🔍</span>
    <input type="text" class="form-control" placeholder="Buscar..." data-search-table="tbl-usuarios" style="padding-left:34px;width:200px;">
  </div>
</div>

<!-- Tabla -->
<div class="glass table-container">
  <div class="table-header">
    <div class="table-title">Usuarios registrados</div>
  </div>
  <div class="table-scroll">
    <table id="tbl-usuarios">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Usuario / Email</th>
          <th>Rol</th>
          <th>Programa Nivel</th>
          <th>Estado</th>
          <th>Registro</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($usuarios ?? [] as $u): ?>
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:10px;">
              <div class="user-avatar" style="width:32px;height:32px;font-size:11px;flex-shrink:0;">
                <?= strtoupper(substr($u['nombre'],0,1).substr($u['apellido']??'',0,1)) ?>
              </div>
              <div>
                <div class="fw-bold"><?= htmlspecialchars($u['nombre'].' '.($u['apellido']??'')) ?></div>
              </div>
            </div>
          </td>
          <td>
            <div><?= htmlspecialchars($u['username']) ?></div>
            <div class="td-muted"><?= htmlspecialchars($u['email']) ?></div>
          </td>
          <td>
            <?php $rolColors = ['superadmin'=>'badge-purple','admin'=>'badge-blue','capturista'=>'badge-teal','usuario'=>'badge-gray']; ?>
            <span class="badge <?= $rolColors[$u['rol']] ?? 'badge-gray' ?>"><?= ucfirst($u['rol']) ?></span>
          </td>
          <td>
            <?php if(!empty($u['programa_nivel_nombre'])): ?>
            <span class="badge badge-blue"><?= htmlspecialchars($u['programa_nivel_nombre']) ?></span>
            <?php else: ?>
            <span class="td-muted">—</span>
            <?php endif; ?>
          </td>
          <td>
            <?php if(!$u['aprobado']): ?>
              <span class="badge badge-yellow">⏳ Pendiente</span>
            <?php elseif($u['activo']): ?>
              <span class="badge badge-teal">● Activo</span>
            <?php else: ?>
              <span class="badge badge-red">● Inactivo</span>
            <?php endif; ?>
          </td>
          <td class="td-muted"><?= htmlspecialchars(substr($u['created_at']??'',0,10)) ?></td>
          <td>
            <div class="d-flex gap-2">
              <?php if(!$u['aprobado'] && isRole(['admin','superadmin'])): ?>
              <form method="POST" action="<?= BASE_URL ?>/usuarios/aprobar/<?= $u['id'] ?>" style="display:inline;">
                <?= csrfField() ?>
                <button type="submit" class="btn btn-success btn-xs" data-confirm="¿Aprobar acceso de <?= htmlspecialchars($u['nombre']) ?>?">✓ Aprobar</button>
              </form>
              <?php endif; ?>
              <a href="<?= BASE_URL ?>/usuarios/editar/<?= $u['id'] ?>" class="btn btn-glass btn-xs">Editar</a>
              <?php if($u['id'] != currentUserId()): ?>
              <form method="POST" action="<?= BASE_URL ?>/usuarios/toggle/<?= $u['id'] ?>" style="display:inline;">
                <?= csrfField() ?>
                <button type="submit" class="btn btn-danger btn-xs" data-confirm="¿Cambiar estado de este usuario?">
                  <?= $u['activo'] ? 'Desactivar' : 'Activar' ?>
                </button>
              </form>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if(empty($usuarios)): ?>
        <tr><td colspan="7"><div class="empty-state"><div class="empty-icon">👥</div><div class="empty-title">Sin usuarios registrados</div><p class="empty-text">Crea el primer usuario del sistema.</p></div></td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php
$content = ob_get_clean();
include VIEWS_PATH . 'layouts/app.php';
?>
