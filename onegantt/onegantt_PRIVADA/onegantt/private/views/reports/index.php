<?php
require_once ROOT_PATH . 'views/layouts/icons.php';
ob_start();
?>
<div class="og-page og-page--narrow">
  <div class="og-page__header">
    <h1 class="og-page__title">Reportes e importación</h1>
  </div>

  <!-- Exportar -->
  <div class="og-card">
    <h2 class="og-card__title"><?= ogIcon('download', 16) ?> Exportar tareas a CSV</h2>
    <p class="og-muted" style="margin:.5rem 0 1rem">Descarga todas las tareas o filtra por proyecto. Compatible con Excel.</p>
    <form method="GET" action="<?= Router::url('reports/export') ?>" class="og-form og-form--inline">
      <input type="hidden" name="route" value="reports/export">
      <select name="proyecto_id">
        <option value="">Todos los proyectos</option>
        <?php foreach ($proyectos as $p): ?>
        <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="og-btn">
        <?= ogIcon('download', 15) ?> Descargar CSV
      </button>
    </form>
  </div>

  <!-- Importar -->
  <?php if ($auth->isGestor()): ?>
  <div class="og-card">
    <h2 class="og-card__title"><?= ogIcon('upload', 16) ?> Importar tareas desde CSV</h2>
    <p class="og-muted" style="margin:.5rem 0 .75rem">
      Sube un CSV con columnas: <code>ID, Proyecto, Título, Descripción, Estatus, Asignado, Inicio, Fin, Prioridad, Progreso, Padre ID, Creado</code>
    </p>
    <p class="og-muted" style="margin-bottom:1rem">
      El nombre del <strong>Proyecto</strong> debe coincidir exactamente con uno existente.
      Usa el CSV exportado como plantilla.
    </p>

    <?php if (!empty($flash)): ?>
      <div class="og-alert og-alert--<?= $flash['type'] === 'error' ? 'error' : 'success' ?>" style="margin-bottom:1rem">
        <?= htmlspecialchars($flash['msg']) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="<?= Router::url('reports/import') ?>" enctype="multipart/form-data" class="og-form">
      <?= $auth->csrfField() ?>
      <div class="og-form__group">
        <label for="archivo">Archivo CSV</label>
        <input type="file" id="archivo" name="archivo" accept=".csv" required>
      </div>
      <div class="og-form__actions">
        <button type="submit" class="og-btn">
          <?= ogIcon('upload', 15) ?> Importar
        </button>
      </div>
    </form>
  </div>
  <?php endif; ?>

</div>
<?php
$content   = ob_get_clean();
$pageTitle = 'Reportes';
include ROOT_PATH . 'views/layouts/main.php';
