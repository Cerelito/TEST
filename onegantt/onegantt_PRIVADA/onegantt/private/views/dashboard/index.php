<?php
require_once ROOT_PATH . 'views/layouts/icons.php';
ob_start();
?>
<div class="og-page">
  <div class="og-page__header">
    <div>
      <h1 class="og-page__title">Dashboard</h1>
      <p class="og-page__sub">Bienvenido, <?= htmlspecialchars($auth->userName()) ?></p>
    </div>
    <?php if ($auth->isGestor()): ?>
    <div style="display:flex;gap:8px">
      <a href="<?= Router::url('tasks/create') ?>" class="og-btn og-btn--sm">
        <?= ogIcon('plus', 14) ?> Nueva tarea
      </a>
      <a href="<?= Router::url('projects/create') ?>" class="og-btn og-btn--sm og-btn--ghost">
        <?= ogIcon('folder', 14) ?> Proyecto
      </a>
    </div>
    <?php endif; ?>
  </div>

  <!-- ── Métricas KPI ── -->
  <div class="og-metrics">
    <div class="og-metric og-metric--default">
      <div class="og-metric__icon"><?= ogIcon('folder', 18) ?></div>
      <span class="og-metric__label">Proyectos activos</span>
      <span class="og-metric__val"><?= $stats['proyectos'] ?></span>
    </div>
    <div class="og-metric og-metric--info">
      <div class="og-metric__icon"><?= ogIcon('check-sq', 18) ?></div>
      <span class="og-metric__label">Mis tareas activas</span>
      <span class="og-metric__val"><?= $stats['mis_tareas'] ?></span>
    </div>
    <div class="og-metric og-metric--warn">
      <div class="og-metric__icon"><?= ogIcon('clock', 18) ?></div>
      <span class="og-metric__label">Vencen hoy</span>
      <span class="og-metric__val"><?= $stats['tareas_hoy'] ?></span>
    </div>
    <div class="og-metric og-metric--danger">
      <div class="og-metric__icon"><?= ogIcon('alert', 18) ?></div>
      <span class="og-metric__label">Vencidas</span>
      <span class="og-metric__val"><?= $stats['vencidas'] ?></span>
    </div>
  </div>

  <!-- ── Cuerpo ── -->
  <div class="og-two-col">

    <!-- Mis tareas -->
    <div class="og-card">
      <div class="og-card__head">
        <h2 class="og-card__title"><?= ogIcon('check-sq', 15) ?> Mis tareas pendientes</h2>
        <a href="<?= Router::url('tasks') ?>" class="og-link">Ver todas &rarr;</a>
      </div>
      <?php if (empty($misTareas)): ?>
        <p class="og-empty">Sin tareas pendientes. &check; Todo al d&iacute;a.</p>
      <?php else: ?>
        <ul class="og-tasklist">
          <?php foreach ($misTareas as $t): ?>
          <li class="og-tasklist__item">
            <span class="og-badge" style="background:<?= htmlspecialchars($t['estatus_color']) ?>22;color:<?= htmlspecialchars($t['estatus_color']) ?>;border:1px solid <?= htmlspecialchars($t['estatus_color']) ?>44;white-space:nowrap;flex-shrink:0">
              <span style="width:6px;height:6px;border-radius:50%;background:<?= htmlspecialchars($t['estatus_color']) ?>;display:inline-block;flex-shrink:0"></span>
              <?= htmlspecialchars($t['estatus']) ?>
            </span>
            <div class="og-tasklist__info">
              <a href="<?= Router::url('tasks/edit', $t['id']) ?>" class="og-tasklist__name">
                <?= htmlspecialchars($t['titulo']) ?>
              </a>
              <span class="og-tasklist__meta">
                <?= htmlspecialchars($t['proyecto']) ?>
                <?php if (!empty($t['fecha_fin'])): ?>
                  &middot;
                  <?php $left = DateHelper::daysLeft($t['fecha_fin']); ?>
                  <span class="<?= $left < 0 ? 'og-overdue' : ($left <= 3 ? 'og-soon' : '') ?>">
                    <?= $left < 0 ? abs($left).'d vencida' : "Vence en {$left}d" ?>
                  </span>
                <?php endif; ?>
              </span>
            </div>
            <div style="text-align:right;flex-shrink:0">
              <div class="og-progress" style="width:70px">
                <div class="og-progress__bar" style="width:<?= $t['progreso'] ?>%"></div>
              </div>
              <small class="og-muted" style="font-size:10px"><?= $t['progreso'] ?>%</small>
            </div>
          </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>

    <!-- Proyectos -->
    <div class="og-card">
      <div class="og-card__head">
        <h2 class="og-card__title"><?= ogIcon('folder', 15) ?> Proyectos</h2>
        <?php if ($auth->isGestor()): ?>
        <a href="<?= Router::url('projects/create') ?>" class="og-btn og-btn--sm">
          <?= ogIcon('plus', 12) ?> Nuevo
        </a>
        <?php endif; ?>
      </div>
      <?php if (empty($proyectos)): ?>
        <p class="og-empty">Sin proyectos a&uacute;n.</p>
      <?php else: ?>
        <ul class="og-projlist">
          <?php foreach ($proyectos as $p): ?>
          <li class="og-projlist__item">
            <span class="og-projlist__dot" style="background:<?= htmlspecialchars($p['color']) ?>"></span>
            <div class="og-projlist__info">
              <a href="<?= Router::url('tasks') ?>?proyecto_id=<?= $p['id'] ?>" class="og-projlist__name">
                <?= htmlspecialchars($p['nombre']) ?>
              </a>
              <span class="og-projlist__meta"><?= $p['total_tareas'] ?> tareas &middot; <?= $p['avance'] ?? 0 ?>% avance</span>
            </div>
            <div class="og-progress" style="width:60px;flex-shrink:0">
              <div class="og-progress__bar" style="width:<?= $p['avance'] ?? 0 ?>%;background:<?= htmlspecialchars($p['color']) ?>"></div>
            </div>
          </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>

  </div>

  <!-- ── Accesos r&#225;pidos ── -->
  <div class="og-card og-card--flat">
    <div class="og-card__head">
      <h2 class="og-card__title"><?= ogIcon('grid', 15) ?> Accesos r&#225;pidos</h2>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap">
      <a href="<?= Router::url('tasks/gantt') ?>" class="og-btn og-btn--ghost og-btn--sm"><?= ogIcon('gantt', 14) ?> Gantt</a>
      <a href="<?= Router::url('reports') ?>"     class="og-btn og-btn--ghost og-btn--sm"><?= ogIcon('file', 14) ?> Reportes</a>
      <?php if ($auth->isAdmin()): ?>
      <a href="<?= Router::url('catalogs/statuses') ?>" class="og-btn og-btn--ghost og-btn--sm"><?= ogIcon('tag', 14) ?> Cat. Estatus</a>
      <a href="<?= Router::url('catalogs/users') ?>"    class="og-btn og-btn--ghost og-btn--sm"><?= ogIcon('users', 14) ?> Cat. Usuarios</a>
      <?php endif; ?>
    </div>
  </div>

</div>
<?php
$content   = ob_get_clean();
$pageTitle = 'Dashboard';
include ROOT_PATH . 'views/layouts/main.php';
