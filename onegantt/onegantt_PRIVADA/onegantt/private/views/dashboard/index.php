<?php
require_once ROOT_PATH . 'views/layouts/icons.php';
ob_start();
?>
<div class="og-page">
  <div class="og-page__header">
    <div>
      <h1 class="og-page__title">Dashboard</h1>
      <p class="og-page__sub">
        Bienvenido, <?= htmlspecialchars($auth->userName()) ?> &mdash;
        <span style="
          display:inline-flex;align-items:center;gap:4px;
          font-size:11px;font-weight:700;padding:2px 8px;border-radius:20px;
          <?php if ($auth->isAdmin()): ?>
            background:rgba(99,102,241,.15);color:var(--accent-2);border:1px solid rgba(99,102,241,.25)
          <?php elseif ($auth->isDirector()): ?>
            background:rgba(14,165,233,.15);color:var(--teal);border:1px solid rgba(14,165,233,.25)
          <?php else: ?>
            background:rgba(16,185,129,.15);color:var(--emerald);border:1px solid rgba(16,185,129,.25)
          <?php endif; ?>
        ">
          <?php if ($auth->isAdmin()): ?>
            <?= ogIcon('settings', 11) ?> Administrador
          <?php elseif ($auth->isDirector()): ?>
            <?= ogIcon('users', 11) ?> Director
          <?php else: ?>
            <?= ogIcon('user', 11) ?> Colaborador
          <?php endif; ?>
        </span>
      </p>
    </div>
    <?php if ($auth->canManage()): ?>
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

  <!-- ── KPI Cards ── -->
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

  <!-- ════════════════════════════════════════════════════════
       VISTA DIRECTOR: Pendientes por usuario
       ════════════════════════════════════════════════════════ -->
  <?php if ($auth->isDirector()): ?>

  <div class="og-two-col">

    <!-- Panel izquierdo: tareas por colaborador -->
    <div>
      <div class="og-card">
        <div class="og-card__head">
          <h2 class="og-card__title"><?= ogIcon('users', 15) ?> Pendientes por colaborador</h2>
          <a href="<?= Router::url('tasks') ?>" class="og-link">Ver todas &rarr;</a>
        </div>

        <?php if (empty($tareasPorUsuario)): ?>
          <p class="og-empty">Sin tareas pendientes en el equipo. &check;</p>
        <?php else: ?>
          <?php foreach ($tareasPorUsuario as $uid => $userData): ?>
          <div style="margin-bottom:18px">
            <!-- Cabecera del usuario -->
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;padding-bottom:8px;border-bottom:1px solid var(--border)">
              <div style="
                width:28px;height:28px;border-radius:50%;flex-shrink:0;
                background:linear-gradient(135deg,var(--accent),var(--violet));
                display:flex;align-items:center;justify-content:center;
                color:#fff;font-weight:700;font-size:11px;
                box-shadow:0 0 8px var(--accent-glow)">
                <?= strtoupper(mb_substr($userData['nombre'], 0, 1)) ?>
              </div>
              <span style="font-weight:600;color:var(--text);font-size:13px"><?= htmlspecialchars($userData['nombre']) ?></span>
              <span class="og-badge og-badge--blue" style="font-size:10px;margin-left:auto">
                <?= count($userData['tareas']) ?> pendiente<?= count($userData['tareas']) !== 1 ? 's' : '' ?>
              </span>
            </div>

            <!-- Tareas del usuario -->
            <ul class="og-tasklist" style="padding-left:36px">
              <?php foreach (array_slice($userData['tareas'], 0, 5) as $t): ?>
              <li class="og-tasklist__item">
                <span class="og-badge" style="
                  background:<?= htmlspecialchars($t['estatus_color']) ?>22;
                  color:<?= htmlspecialchars($t['estatus_color']) ?>;
                  border:1px solid <?= htmlspecialchars($t['estatus_color']) ?>44;
                  white-space:nowrap;flex-shrink:0;font-size:10px">
                  <span style="width:5px;height:5px;border-radius:50%;background:<?= htmlspecialchars($t['estatus_color']) ?>;display:inline-block"></span>
                  <?= htmlspecialchars($t['estatus']) ?>
                </span>
                <div class="og-tasklist__info">
                  <a href="<?= Router::url('tasks/edit', $t['id']) ?>" class="og-tasklist__name" style="font-size:12px">
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
                <div style="flex-shrink:0">
                  <div class="og-progress" style="width:50px">
                    <div class="og-progress__bar" style="width:<?= $t['progreso'] ?>%"></div>
                  </div>
                </div>
              </li>
              <?php endforeach; ?>
              <?php if (count($userData['tareas']) > 5): ?>
              <li style="padding:6px 0">
                <a href="<?= Router::url('tasks') ?>?asignado_a=<?= $uid ?>" class="og-link" style="font-size:11px">
                  +<?= count($userData['tareas']) - 5 ?> m&aacute;s &rarr;
                </a>
              </li>
              <?php endif; ?>
            </ul>
          </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <!-- Panel derecho: proyectos -->
    <div>
      <div class="og-card">
        <div class="og-card__head">
          <h2 class="og-card__title"><?= ogIcon('folder', 15) ?> Proyectos</h2>
          <a href="<?= Router::url('projects/create') ?>" class="og-btn og-btn--sm">
            <?= ogIcon('plus', 12) ?> Nuevo
          </a>
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
              <div class="og-progress" style="width:55px;flex-shrink:0">
                <div class="og-progress__bar" style="width:<?= $p['avance'] ?? 0 ?>%;background:<?= htmlspecialchars($p['color']) ?>"></div>
              </div>
            </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>

      <!-- Accesos rápidos director -->
      <div class="og-card og-card--flat">
        <div class="og-card__head">
          <h2 class="og-card__title"><?= ogIcon('grid', 14) ?> Accesos r&aacute;pidos</h2>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
          <a href="<?= Router::url('tasks/gantt') ?>" class="og-btn og-btn--ghost og-btn--sm"><?= ogIcon('gantt', 13) ?> Gantt</a>
          <a href="<?= Router::url('reports') ?>"     class="og-btn og-btn--ghost og-btn--sm"><?= ogIcon('file', 13) ?> Reportes</a>
        </div>
      </div>
    </div>

  </div>

  <!-- ════════════════════════════════════════════════════════
       VISTA ADMIN / COLABORADOR: Solo mis tareas
       ════════════════════════════════════════════════════════ -->
  <?php else: ?>

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
            <span class="og-badge" style="
              background:<?= htmlspecialchars($t['estatus_color']) ?>22;
              color:<?= htmlspecialchars($t['estatus_color']) ?>;
              border:1px solid <?= htmlspecialchars($t['estatus_color']) ?>44;
              white-space:nowrap;flex-shrink:0">
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
        <?php if ($auth->canManage()): ?>
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

  <!-- Accesos rápidos (admin/colaborador) -->
  <div class="og-card og-card--flat">
    <div class="og-card__head">
      <h2 class="og-card__title"><?= ogIcon('grid', 15) ?> Accesos r&aacute;pidos</h2>
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

  <?php endif; ?>

</div>
<?php
$content   = ob_get_clean();
$pageTitle = 'Dashboard';
include ROOT_PATH . 'views/layouts/main.php';
