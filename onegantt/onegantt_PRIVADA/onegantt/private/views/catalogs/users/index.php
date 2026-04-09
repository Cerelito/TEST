<?php
require_once ROOT_PATH . 'views/layouts/icons.php';
ob_start();
?>
<div class="og-page">
  <div class="og-page__header">
    <div>
      <h1 class="og-page__title"><?= ogIcon('users', 20) ?> Cat&aacute;logo de Usuarios</h1>
      <p class="og-page__sub">Administra los usuarios y roles del sistema</p>
    </div>
    <a href="<?= Router::url('catalogs/users/create') ?>" class="og-btn">
      <?= ogIcon('plus', 14) ?> Nuevo usuario
    </a>
  </div>

  <!-- Resumen -->
  <div class="og-metrics" style="grid-template-columns:repeat(3,1fr);max-width:600px">
    <?php
      $total   = count($usuarios);
      $activos = count(array_filter($usuarios, fn($u) => $u['activo']));
      $inactivos = $total - $activos;
    ?>
    <div class="og-metric og-metric--default" style="padding:14px 18px">
      <span class="og-metric__label">Total</span>
      <span class="og-metric__val" style="font-size:1.6rem"><?= $total ?></span>
    </div>
    <div class="og-metric og-metric--info" style="padding:14px 18px">
      <span class="og-metric__label">Activos</span>
      <span class="og-metric__val" style="font-size:1.6rem"><?= $activos ?></span>
    </div>
    <div class="og-metric og-metric--danger" style="padding:14px 18px">
      <span class="og-metric__label">Inactivos</span>
      <span class="og-metric__val" style="font-size:1.6rem"><?= $inactivos ?></span>
    </div>
  </div>

  <div class="og-card">
    <?php if (empty($usuarios)): ?>
      <p class="og-empty">No hay usuarios registrados.</p>
    <?php else: ?>
    <div class="og-table-wrap" style="border:none;background:transparent;backdrop-filter:none">
      <table class="og-table">
        <thead>
          <tr>
            <th>Usuario</th>
            <th>Email</th>
            <th>Rol</th>
            <th>&Uacute;ltimo login</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($usuarios as $u): ?>
          <tr>
            <td>
              <div style="display:flex;align-items:center;gap:10px">
                <div style="
                  width:32px;height:32px;border-radius:50%;flex-shrink:0;
                  background:linear-gradient(135deg,var(--accent),var(--violet));
                  display:flex;align-items:center;justify-content:center;
                  color:#fff;font-weight:700;font-size:13px;
                  box-shadow:0 0 10px var(--accent-glow)">
                  <?= strtoupper(mb_substr($u['nombre'], 0, 1)) ?>
                </div>
                <span style="font-weight:600;color:var(--text)"><?= htmlspecialchars($u['nombre']) ?></span>
              </div>
            </td>
            <td class="og-muted"><?= htmlspecialchars($u['email']) ?></td>
            <td>
              <?php
                $rolColors = ['Administrador'=>'#6366f1','Gestor'=>'#0ea5e9','Usuario'=>'#94a3b8'];
                $rc = $rolColors[$u['rol']] ?? '#94a3b8';
              ?>
              <span class="og-badge" style="background:<?= $rc ?>22;color:<?= $rc ?>;border:1px solid <?= $rc ?>44">
                <?= htmlspecialchars($u['rol']) ?>
              </span>
            </td>
            <td class="og-muted" style="font-size:12px">
              <?= $u['ultimo_login'] ? date('d/m/Y H:i', strtotime($u['ultimo_login'])) : '—' ?>
            </td>
            <td>
              <?php if ($u['activo']): ?>
                <span class="og-badge og-badge--green">Activo</span>
              <?php else: ?>
                <span class="og-badge og-badge--gray">Inactivo</span>
              <?php endif; ?>
            </td>
            <td class="og-actions">
              <a href="<?= Router::url('catalogs/users/edit', $u['id']) ?>" class="og-icon-btn" title="Editar">
                <?= ogIcon('edit', 15) ?>
              </a>
              <form method="POST" action="<?= Router::url('catalogs/users/toggle', $u['id']) ?>"
                    style="display:inline">
                <?= $csrfField ?>
                <button type="submit" class="og-icon-btn <?= $u['activo'] ? 'og-icon-btn--danger' : '' ?>"
                        title="<?= $u['activo'] ? 'Desactivar' : 'Activar' ?>"
                        onclick="return confirm('¿<?= $u['activo'] ? 'Desactivar' : 'Activar' ?> al usuario <?= htmlspecialchars($u['nombre']) ?>?')">
                  <?= $u['activo'] ? ogIcon('alert', 15) : ogIcon('check-sq', 15) ?>
                </button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
</div>
<?php
$content   = ob_get_clean();
$pageTitle = 'Catálogo · Usuarios';
include ROOT_PATH . 'views/layouts/main.php';
