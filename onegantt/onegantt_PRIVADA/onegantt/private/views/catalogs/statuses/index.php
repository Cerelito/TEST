<?php
require_once ROOT_PATH . 'views/layouts/icons.php';
ob_start();
?>
<div class="og-page">
  <div class="og-page__header">
    <div>
      <h1 class="og-page__title"><?= ogIcon('tag', 20) ?> Cat&aacute;logo de Estatus</h1>
      <p class="og-page__sub">Define los estados del flujo de trabajo para las tareas</p>
    </div>
    <a href="<?= Router::url('catalogs/statuses/create') ?>" class="og-btn">
      <?= ogIcon('plus', 14) ?> Nuevo estatus
    </a>
  </div>

  <div class="og-card">
    <?php if (empty($statuses)): ?>
      <p class="og-empty">No hay estatus definidos. Crea el primero.</p>
    <?php else: ?>
    <div class="og-table-wrap" style="border:none;background:transparent;backdrop-filter:none">
      <table class="og-table">
        <thead>
          <tr>
            <th>Orden</th>
            <th>Estatus</th>
            <th>Color</th>
            <th>Vista previa</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($statuses as $s): ?>
          <tr>
            <td class="og-muted" style="font-size:12px;font-weight:600"><?= (int)$s['orden'] ?></td>
            <td style="font-weight:600;color:var(--text)"><?= htmlspecialchars($s['nombre']) ?></td>
            <td>
              <div style="display:flex;align-items:center;gap:8px">
                <span style="display:inline-block;width:16px;height:16px;border-radius:50%;background:<?= htmlspecialchars($s['color']) ?>;box-shadow:0 0 10px <?= htmlspecialchars($s['color']) ?>88"></span>
                <code style="font-size:12px;color:var(--text-m);font-family:monospace"><?= htmlspecialchars($s['color']) ?></code>
              </div>
            </td>
            <td>
              <span class="og-badge" style="background:<?= htmlspecialchars($s['color']) ?>22;color:<?= htmlspecialchars($s['color']) ?>;border:1px solid <?= htmlspecialchars($s['color']) ?>44">
                <span style="width:6px;height:6px;border-radius:50%;background:<?= htmlspecialchars($s['color']) ?>;display:inline-block"></span>
                <?= htmlspecialchars($s['nombre']) ?>
              </span>
            </td>
            <td class="og-actions">
              <a href="<?= Router::url('catalogs/statuses/edit', $s['id']) ?>" class="og-icon-btn" title="Editar">
                <?= ogIcon('edit', 15) ?>
              </a>
              <form method="POST" action="<?= Router::url('catalogs/statuses/delete', $s['id']) ?>"
                    onsubmit="return confirm('¿Eliminar el estatus «<?= htmlspecialchars($s['nombre']) ?>»? Solo es posible si no tiene tareas asignadas.')"
                    style="display:inline">
                <?= $csrfField ?>
                <button type="submit" class="og-icon-btn og-icon-btn--danger" title="Eliminar">
                  <?= ogIcon('trash', 15) ?>
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

  <!-- Info card -->
  <div class="og-card og-card--flat" style="margin-top:0">
    <p style="font-size:12px;color:var(--text-d);display:flex;align-items:center;gap:6px">
      <?= ogIcon('alert', 14) ?>
      Solo puedes eliminar estatus que no tengan tareas asignadas. El campo <strong style="color:var(--text-m)">Orden</strong> determina el orden de aparici&oacute;n en los selectores.
    </p>
  </div>
</div>
<?php
$content   = ob_get_clean();
$pageTitle = 'Catálogo · Estatus';
include ROOT_PATH . 'views/layouts/main.php';
