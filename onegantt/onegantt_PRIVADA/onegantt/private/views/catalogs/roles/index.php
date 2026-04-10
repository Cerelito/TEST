<?php
require_once ROOT_PATH . 'views/layouts/icons.php';

// Capability matrix por slug (definida por el sistema, no editable)
$caps = [
    'admin' => [
        'Dashboard global (todos los usuarios)' => true,
        'Ver tareas de todos los colaboradores' => true,
        'Crear y editar tareas'                 => true,
        'Eliminar tareas'                       => true,
        'Gestionar proyectos'                   => true,
        'Catálogos del sistema'                 => true,
        'Reportes'                              => true,
    ],
    'director' => [
        'Dashboard global (todos los usuarios)' => true,
        'Ver tareas de todos los colaboradores' => true,
        'Crear y editar tareas'                 => true,
        'Eliminar tareas'                       => true,
        'Gestionar proyectos'                   => true,
        'Catálogos del sistema'                 => false,
        'Reportes'                              => true,
    ],
    'colaborador' => [
        'Dashboard global (todos los usuarios)' => false,
        'Ver tareas de todos los colaboradores' => false,
        'Crear y editar tareas'                 => true,
        'Eliminar tareas'                       => true,
        'Gestionar proyectos'                   => true,
        'Catálogos del sistema'                 => false,
        'Reportes'                              => false,
    ],
];

$rolColors = ['admin' => '#6366f1', 'director' => '#0ea5e9', 'colaborador' => '#10b981'];

ob_start();
?>
<div class="og-page">
  <div class="og-page__header">
    <div>
      <h1 class="og-page__title"><?= ogIcon('shield', 20) ?> Perfiles &amp; Permisos</h1>
      <p class="og-page__sub">Gestiona los perfiles de acceso del sistema</p>
    </div>
  </div>

  <div style="display:grid;gap:20px">
    <?php foreach ($roles as $r):
      $slug  = $r['slug'];
      $color = $rolColors[$slug] ?? '#94a3b8';
      $perms = $caps[$slug] ?? [];
      $total = count($perms);
      $activos = count(array_filter($perms));
    ?>
    <div class="og-card" style="border-color:<?= $color ?>22">
      <!-- Header del perfil -->
      <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:20px;flex-wrap:wrap">
        <div style="display:flex;align-items:center;gap:14px">
          <div style="
            width:44px;height:44px;border-radius:12px;flex-shrink:0;
            background:<?= $color ?>18;border:1px solid <?= $color ?>33;
            display:flex;align-items:center;justify-content:center;color:<?= $color ?>">
            <?= ogIcon('shield', 20) ?>
          </div>
          <div>
            <div style="display:flex;align-items:center;gap:10px">
              <h2 style="font-size:16px;font-weight:700;color:var(--text)"><?= htmlspecialchars($r['nombre']) ?></h2>
              <span class="og-badge" style="background:<?= $color ?>18;color:<?= $color ?>;border:1px solid <?= $color ?>33;font-size:10px;font-family:monospace">
                <?= htmlspecialchars($slug) ?>
              </span>
            </div>
            <p style="font-size:12px;color:var(--text-m);margin-top:3px"><?= htmlspecialchars($r['descripcion'] ?? '—') ?></p>
          </div>
        </div>
        <div style="display:flex;align-items:center;gap:10px;flex-shrink:0">
          <span style="font-size:11px;color:var(--text-m)"><?= $activos ?>/<?= $total ?> permisos activos</span>
          <a href="<?= Router::url('catalogs/roles/edit', $r['id']) ?>" class="og-btn og-btn--ghost og-btn--sm">
            <?= ogIcon('edit', 13) ?> Editar
          </a>
        </div>
      </div>

      <!-- Capability grid -->
      <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:8px">
        <?php foreach ($perms as $label => $allowed): ?>
        <div style="
          display:flex;align-items:center;gap:10px;
          padding:9px 12px;border-radius:8px;
          background:<?= $allowed ? $color.'12' : 'rgba(255,255,255,.03)' ?>;
          border:1px solid <?= $allowed ? $color.'25' : 'rgba(255,255,255,.07)' ?>;
        ">
          <span style="
            width:20px;height:20px;border-radius:6px;flex-shrink:0;
            display:flex;align-items:center;justify-content:center;
            background:<?= $allowed ? $color.'25' : 'rgba(255,255,255,.05)' ?>;
            color:<?= $allowed ? $color : 'var(--text-d)' ?>;
          ">
            <?= $allowed ? ogIcon('check', 11) : ogIcon('x', 11) ?>
          </span>
          <span style="font-size:12px;color:<?= $allowed ? 'var(--text)' : 'var(--text-d)' ?>"><?= htmlspecialchars($label) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <p style="font-size:11px;color:var(--text-d);margin-top:16px;text-align:center">
    <?= ogIcon('lock', 12) ?> Los permisos son definidos por el sistema. Puedes editar el nombre y descripción de cada perfil.
  </p>
</div>
<?php
$content   = ob_get_clean();
$pageTitle = 'Catálogo · Perfiles';
include ROOT_PATH . 'views/layouts/main.php';
