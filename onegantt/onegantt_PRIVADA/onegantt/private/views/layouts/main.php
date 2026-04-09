<?php
// Layout principal — envuelve todas las vistas autenticadas
// Variables esperadas: $pageTitle (string), $auth (Auth)
$flash = Router::flash();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle ?? 'OneGantt') ?> · OneGantt</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/app.css">
</head>
<body>
  <!-- Header móvil -->
  <header class="og-mobile-header">
    <button class="og-icon-btn" id="sb-toggle" title="Abrir menú">
      <?= ogIcon('menu', 20) ?>
    </button>
    <div class="og-mobile-header__logo">OneGantt</div>
    <div style="width:32px"></div>
  </header>

<div class="og-layout">
  <div class="og-overlay" id="overlay"></div>

  <!-- Sidebar -->
  <aside class="og-sidebar" id="sidebar">
    <div class="og-sidebar__logo">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 22 20 2 20"/><line x1="12" y1="2" x2="12" y2="20"/><line x1="2" y1="20" x2="22" y2="20"/></svg>
      <span>OneGantt</span>
    </div>

    <nav class="og-nav">
      <?php
        $cur = trim($_GET['route'] ?? '', '/');

        $navMain = [
          ['icon' => 'grid',     'label' => 'Dashboard', 'route' => 'dashboard'],
          ['icon' => 'folder',   'label' => 'Proyectos', 'route' => 'projects'],
          ['icon' => 'check-sq', 'label' => 'Tareas',    'route' => 'tasks'],
          ['icon' => 'gantt',    'label' => 'Gantt',     'route' => 'tasks/gantt'],
          ['icon' => 'file',     'label' => 'Reportes',  'route' => 'reports'],
        ];
        foreach ($navMain as $item):
          $active = str_starts_with($cur, $item['route']) ? ' og-nav__item--active' : '';
      ?>
      <a href="<?= Router::url($item['route']) ?>" class="og-nav__item<?= $active ?>">
        <?= ogIcon($item['icon']) ?>
        <span><?= $item['label'] ?></span>
      </a>
      <?php endforeach; ?>

      <?php if ($auth->isAdmin()): ?>
      <div class="og-nav__section">Cat&aacute;logos</div>
      <?php
        $navCat = [
          ['icon' => 'tag',   'label' => 'Estatus',  'route' => 'catalogs/statuses'],
          ['icon' => 'users', 'label' => 'Usuarios', 'route' => 'catalogs/users'],
        ];
        foreach ($navCat as $item):
          $active = str_starts_with($cur, $item['route']) ? ' og-nav__item--active' : '';
      ?>
      <a href="<?= Router::url($item['route']) ?>" class="og-nav__item og-nav__item--sub<?= $active ?>">
        <?= ogIcon($item['icon'], 16) ?>
        <span><?= $item['label'] ?></span>
      </a>
      <?php endforeach; ?>
      <?php endif; ?>
    </nav>

    <div class="og-sidebar__footer">
      <div class="og-user">
        <div class="og-user__avatar"><?= strtoupper(mb_substr($auth->userName(), 0, 1)) ?></div>
        <div class="og-user__info">
          <span class="og-user__name"><?= htmlspecialchars($auth->userName()) ?></span>
          <span class="og-user__rol"><?= htmlspecialchars($auth->rol()) ?></span>
        </div>
      </div>
      <a href="<?= Router::url('logout') ?>" class="og-logout" title="Cerrar sesi&oacute;n">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      </a>
    </div>
  </aside>

  <!-- Contenido principal -->
  <main class="og-main">
    <?= $content ?? '' ?>
  </main>
</div>

<!-- Toast container (rendered by JS) -->
<div class="og-toasts" id="og-toasts"></div>

<?php if ($flash): ?>
<script>window.__ogFlash = <?= json_encode(['msg' => $flash['msg'], 'type' => $flash['type']]) ?>;</script>
<?php endif; ?>

<script src="<?= BASE_URL ?>/assets/js/app.js"></script>
</body>
</html>
