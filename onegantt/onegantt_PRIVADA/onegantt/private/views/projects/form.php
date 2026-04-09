<?php
require_once ROOT_PATH . 'views/layouts/icons.php';
$editing = !empty($project);
ob_start();
?>
<div class="og-page og-page--narrow">
  <div class="og-page__header">
    <a href="<?= Router::url('projects') ?>" class="og-back">&larr; Proyectos</a>
    <h1 class="og-page__title"><?= $editing ? 'Editar proyecto' : 'Nuevo proyecto' ?></h1>
  </div>

  <?php if (!empty($error)): ?>
    <div class="og-alert og-alert--error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <div class="og-card">
    <form method="POST" class="og-form">
      <?= $csrfField ?>

      <div class="og-form__group">
        <label for="nombre">Nombre del proyecto <span class="og-req">*</span></label>
        <input type="text" id="nombre" name="nombre" required maxlength="150"
               value="<?= htmlspecialchars($project['nombre'] ?? $_POST['nombre'] ?? '') ?>">
      </div>

      <div class="og-form__group">
        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" name="descripcion" rows="3"><?= htmlspecialchars($project['descripcion'] ?? $_POST['descripcion'] ?? '') ?></textarea>
      </div>

      <div class="og-form__row">
        <div class="og-form__group">
          <label for="color">Color del proyecto</label>
          <div class="og-color-picker">
            <input type="color" id="color" name="color"
                   value="<?= htmlspecialchars($project['color'] ?? '#5563DE') ?>">
            <span id="color-label"><?= htmlspecialchars($project['color'] ?? '#5563DE') ?></span>
          </div>
        </div>

        <?php if ($editing): ?>
        <div class="og-form__group">
          <label>Estado</label>
          <label class="og-toggle">
            <input type="checkbox" name="activo" value="1" <?= ($project['activo'] ?? 1) ? 'checked' : '' ?>>
            <span class="og-toggle__slider"></span>
            Activo
          </label>
        </div>
        <?php endif; ?>
      </div>

      <div class="og-form__actions">
        <a href="<?= Router::url('projects') ?>" class="og-btn og-btn--ghost">Cancelar</a>
        <button type="submit" class="og-btn">
          <?= ogIcon('check-sq', 16) ?>
          <?= $editing ? 'Guardar cambios' : 'Crear proyecto' ?>
        </button>
      </div>
    </form>
  </div>
</div>
<script>
document.getElementById('color').addEventListener('input', function(){
  document.getElementById('color-label').textContent = this.value;
});
</script>
<?php
$content   = ob_get_clean();
$pageTitle = $editing ? 'Editar proyecto' : 'Nuevo proyecto';
include ROOT_PATH . 'views/layouts/main.php';
