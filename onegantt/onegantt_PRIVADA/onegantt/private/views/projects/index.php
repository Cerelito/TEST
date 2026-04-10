<?php
require_once ROOT_PATH . 'views/layouts/icons.php';
ob_start();
?>
<div class="og-page">
  <div class="og-page__header">
    <h1 class="og-page__title">Proyectos</h1>
    <?php if ($auth->isGestor()): ?>
    <a href="<?= Router::url('projects/create') ?>" class="og-btn">
      <?= ogIcon('plus', 16) ?> Nuevo proyecto
    </a>
    <?php endif; ?>
  </div>

  <?php if (empty($projects)): ?>
    <p class="og-empty">No hay proyectos registrados.</p>
  <?php else: ?>
  <div class="og-table-wrap">
    <table class="og-table">
      <thead>
        <tr>
          <th>Color</th>
          <th>Nombre</th>
          <th>Descripción</th>
          <th>Creado por</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($projects as $p): ?>
        <tr>
          <td><span class="og-color-dot" style="background:<?= htmlspecialchars($p['color']) ?>"></span></td>
          <td><strong><?= htmlspecialchars($p['nombre']) ?></strong></td>
          <td class="og-muted"><?= htmlspecialchars($p['descripcion'] ?? '—') ?></td>
          <td><?= htmlspecialchars($p['creador']) ?></td>
          <td>
            <span class="og-badge <?= $p['activo'] ? 'og-badge--green' : 'og-badge--gray' ?>">
              <?= $p['activo'] ? 'Activo' : 'Inactivo' ?>
            </span>
          </td>
          <td class="og-actions">
            <a href="<?= Router::url('tasks') ?>?proyecto_id=<?= $p['id'] ?>" class="og-icon-btn" title="Ver tareas">
              <?= ogIcon('check-sq', 15) ?>
            </a>
            <a href="<?= Router::url('tasks/gantt') ?>?proyecto_id=<?= $p['id'] ?>" class="og-icon-btn" title="Ver Gantt">
              <?= ogIcon('gantt', 15) ?>
            </a>
            <?php if ($auth->isGestor()): ?>
            <a href="<?= Router::url('projects/edit', $p['id']) ?>" class="og-icon-btn" title="Editar">
              <?= ogIcon('edit', 15) ?>
            </a>
            <?php endif; ?>
            <?php if ($auth->isAdmin()): ?>
            <form method="POST" action="<?= Router::url('projects/delete', $p['id']) ?>"
                  data-confirm="¿Desactivar el proyecto «<?= htmlspecialchars($p['nombre']) ?>»?"
                  style="display:inline">
              <?= $auth->csrfField() ?>
              <button type="submit" class="og-icon-btn og-icon-btn--danger" title="Desactivar">
                <?= ogIcon('trash', 15) ?>
              </button>
            </form>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>
<?php
$content   = ob_get_clean();
$pageTitle = 'Proyectos';
include ROOT_PATH . 'views/layouts/main.php';
