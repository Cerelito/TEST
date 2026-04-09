<?php
require_once ROOT_PATH . 'views/layouts/icons.php';
ob_start();
?>
<div class="og-page">
  <div class="og-page__header">
    <h1 class="og-page__title">Tareas</h1>
    <?php if ($auth->isGestor()): ?>
    <a href="<?= Router::url('tasks/create') ?>" class="og-btn">
      <?= ogIcon('plus', 16) ?> Nueva tarea
    </a>
    <?php endif; ?>
  </div>

  <!-- Filtros -->
  <form method="GET" action="<?= Router::url('tasks') ?>" class="og-filters">
    <input type="hidden" name="route" value="tasks">
    <select name="proyecto_id" onchange="this.form.submit()">
      <option value="">Todos los proyectos</option>
      <?php foreach ($proyectos as $p): ?>
      <option value="<?= $p['id'] ?>" <?= ($_GET['proyecto_id'] ?? '') == $p['id'] ? 'selected' : '' ?>>
        <?= htmlspecialchars($p['nombre']) ?>
      </option>
      <?php endforeach; ?>
    </select>
    <select name="estatus_id" onchange="this.form.submit()">
      <option value="">Todos los estatus</option>
      <?php foreach ($statuses as $s): ?>
      <option value="<?= $s['id'] ?>" <?= ($_GET['estatus_id'] ?? '') == $s['id'] ? 'selected' : '' ?>>
        <?= htmlspecialchars($s['nombre']) ?>
      </option>
      <?php endforeach; ?>
    </select>
    <input type="text" name="busqueda" placeholder="Buscar tarea..."
           value="<?= htmlspecialchars($_GET['busqueda'] ?? '') ?>">
    <button type="submit" class="og-btn og-btn--sm">Filtrar</button>
    <a href="<?= Router::url('tasks') ?>" class="og-btn og-btn--ghost og-btn--sm">Limpiar</a>
  </form>

  <?php if (empty($taskList)): ?>
    <p class="og-empty">No se encontraron tareas con esos filtros.</p>
  <?php else: ?>
  <div class="og-table-wrap">
    <table class="og-table">
      <thead>
        <tr>
          <th>Estatus</th>
          <th>Título</th>
          <th>Proyecto</th>
          <th>Asignado a</th>
          <th>Vence</th>
          <th>Progreso</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($taskList as $t): ?>
        <?php
          $overdue = !empty($t['fecha_fin']) && DateHelper::isOverdue($t['fecha_fin']) && !in_array($t['estatus_id'] ?? 0, [4,5]);
          $soon    = !$overdue && !empty($t['fecha_fin']) && DateHelper::isDueSoon($t['fecha_fin']);
        ?>
        <tr class="<?= $overdue ? 'og-row--danger' : ($soon ? 'og-row--warn' : '') ?>">
          <td>
            <span class="og-badge" style="background:<?= htmlspecialchars($t['estatus_color']) ?>22;color:<?= htmlspecialchars($t['estatus_color']) ?>">
              <?= htmlspecialchars($t['estatus_nombre']) ?>
            </span>
          </td>
          <td>
            <a href="<?= Router::url('tasks/edit', $t['id']) ?>" class="og-link">
              <?= htmlspecialchars($t['titulo']) ?>
            </a>
            <?php if (!empty($t['padre_id'])): ?>
              <span class="og-tag">subtarea</span>
            <?php endif; ?>
          </td>
          <td>
            <span class="og-projdot" style="background:<?= htmlspecialchars($t['proyecto_color'] ?? '#888') ?>"></span>
            <?= htmlspecialchars($t['proyecto_nombre']) ?>
          </td>
          <td class="og-muted"><?= htmlspecialchars($t['asignado_nombre'] ?? '—') ?></td>
          <td>
            <?php if (!empty($t['fecha_fin'])): ?>
              <?php $left = DateHelper::daysLeft($t['fecha_fin']); ?>
              <span class="<?= $overdue ? 'og-overdue' : ($soon ? 'og-soon' : '') ?>">
                <?= DateHelper::format($t['fecha_fin']) ?>
              </span>
              <small class="og-muted">(<?= $left < 0 ? abs($left).'d atrasada' : $left.'d' ?>)</small>
            <?php else: ?>—<?php endif; ?>
          </td>
          <td>
            <div class="og-progress">
              <div class="og-progress__bar" style="width:<?= $t['progreso'] ?>%"></div>
            </div>
            <small><?= $t['progreso'] ?>%</small>
          </td>
          <td class="og-actions">
            <a href="<?= Router::url('tasks/edit', $t['id']) ?>" class="og-icon-btn" title="Editar">
              <?= ogIcon('edit', 15) ?>
            </a>
            <?php if ($auth->isGestor()): ?>
            <form method="POST" action="<?= Router::url('tasks/delete', $t['id']) ?>"
                  onsubmit="return confirm('¿Eliminar esta tarea?')" style="display:inline">
              <?= $auth->csrfField() ?>
              <button type="submit" class="og-icon-btn og-icon-btn--danger" title="Eliminar">
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
$pageTitle = 'Tareas';
include ROOT_PATH . 'views/layouts/main.php';
