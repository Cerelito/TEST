<?php
require_once ROOT_PATH . 'views/layouts/icons.php';
$editing = !empty($status);
ob_start();
?>
<div class="og-page og-page--narrow">
  <div class="og-page__header">
    <div>
      <a href="<?= Router::url('catalogs/statuses') ?>" class="og-back">&larr; Volver a Estatus</a>
      <h1 class="og-page__title">
        <?= ogIcon('tag', 20) ?>
        <?= $editing ? 'Editar estatus' : 'Nuevo estatus' ?>
      </h1>
    </div>
  </div>

  <?php if (!empty($error)): ?>
    <div class="og-alert og-alert--error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <div class="og-card">
    <form method="POST" class="og-form">
      <?= $csrfField ?>

      <div class="og-form__group">
        <label for="nombre">Nombre del estatus <span class="og-req">*</span></label>
        <input type="text" id="nombre" name="nombre" required maxlength="80"
               placeholder="Ej: En revisión, Bloqueado..."
               value="<?= htmlspecialchars($status['nombre'] ?? $_POST['nombre'] ?? '') ?>">
      </div>

      <div class="og-form__row">
        <div class="og-form__group">
          <label for="color">Color del estatus</label>
          <div class="og-color-picker">
            <input type="color" id="color" name="color"
                   value="<?= htmlspecialchars($status['color'] ?? $_POST['color'] ?? '#6366f1') ?>"
                   oninput="updatePreview(this.value)">
            <input type="text" id="color-hex" maxlength="7" style="width:100px"
                   placeholder="#6366f1"
                   value="<?= htmlspecialchars($status['color'] ?? $_POST['color'] ?? '#6366f1') ?>"
                   oninput="syncColor(this.value)">
          </div>
        </div>

        <div class="og-form__group">
          <label for="orden">Orden de aparici&oacute;n</label>
          <input type="number" id="orden" name="orden" min="0" max="99"
                 value="<?= (int)($status['orden'] ?? $_POST['orden'] ?? 0) ?>">
        </div>
      </div>

      <!-- Vista previa dinámica -->
      <div class="og-form__group">
        <label>Vista previa</label>
        <div id="preview-container" style="display:flex;align-items:center;gap:12px;margin-top:4px">
          <span id="preview-badge" class="og-badge" style="
            background:<?= htmlspecialchars($status['color'] ?? '#6366f1') ?>22;
            color:<?= htmlspecialchars($status['color'] ?? '#6366f1') ?>;
            border:1px solid <?= htmlspecialchars($status['color'] ?? '#6366f1') ?>44">
            <span id="preview-dot" style="width:7px;height:7px;border-radius:50%;background:<?= htmlspecialchars($status['color'] ?? '#6366f1') ?>;display:inline-block;flex-shrink:0"></span>
            <span id="preview-text"><?= htmlspecialchars($status['nombre'] ?? 'Nuevo estatus') ?></span>
          </span>
          <span class="og-muted" style="font-size:12px">As&iacute; se ver&aacute; en la tabla de tareas</span>
        </div>
      </div>

      <div class="og-form__actions">
        <a href="<?= Router::url('catalogs/statuses') ?>" class="og-btn og-btn--ghost">Cancelar</a>
        <button type="submit" class="og-btn">
          <?= $editing ? 'Guardar cambios' : 'Crear estatus' ?>
        </button>
      </div>
    </form>
  </div>
</div>

<script>
function hexToRgba(hex, alpha) {
  const r = parseInt(hex.slice(1,3),16);
  const g = parseInt(hex.slice(3,5),16);
  const b = parseInt(hex.slice(5,7),16);
  return `rgba(${r},${g},${b},${alpha})`;
}
function updatePreview(color) {
  if (!/^#[0-9A-Fa-f]{6}$/.test(color)) return;
  document.getElementById('preview-dot').style.background = color;
  document.getElementById('preview-badge').style.color = color;
  document.getElementById('preview-badge').style.background = hexToRgba(color, 0.13);
  document.getElementById('preview-badge').style.borderColor = hexToRgba(color, 0.30);
  document.getElementById('color-hex').value = color;
}
function syncColor(val) {
  if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
    document.getElementById('color').value = val;
    updatePreview(val);
  }
}
document.getElementById('nombre').addEventListener('input', function(){
  const t = this.value || 'Nuevo estatus';
  document.getElementById('preview-text').textContent = t;
});
</script>
<?php
$content   = ob_get_clean();
$pageTitle = $editing ? 'Editar Estatus' : 'Nuevo Estatus';
include ROOT_PATH . 'views/layouts/main.php';
