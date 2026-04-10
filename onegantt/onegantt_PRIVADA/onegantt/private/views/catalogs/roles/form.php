<?php
require_once ROOT_PATH . 'views/layouts/icons.php';
$rolColors = ['admin' => '#6366f1', 'director' => '#0ea5e9', 'colaborador' => '#10b981'];
$color     = $rolColors[$role['slug']] ?? '#94a3b8';
ob_start();
?>
<div class="og-page" style="max-width:680px">
  <div class="og-page__header">
    <div>
      <h1 class="og-page__title"><?= ogIcon('shield', 20) ?> Editar perfil</h1>
      <p class="og-page__sub">Modifica el nombre y descripción del perfil</p>
    </div>
    <a href="<?= Router::url('catalogs/roles') ?>" class="og-btn og-btn--ghost og-btn--sm">
      &larr; Volver
    </a>
  </div>

  <?php if ($error): ?>
  <div class="og-alert og-alert--error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <div class="og-card">
    <!-- Badge identificador del slug (no editable) -->
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:22px;padding-bottom:18px;border-bottom:1px solid var(--border)">
      <div style="
        width:40px;height:40px;border-radius:10px;
        background:<?= $color ?>18;border:1px solid <?= $color ?>33;
        display:flex;align-items:center;justify-content:center;color:<?= $color ?>">
        <?= ogIcon('shield', 18) ?>
      </div>
      <div>
        <div style="font-size:11px;color:var(--text-d);margin-bottom:2px">Identificador del sistema (no editable)</div>
        <code style="
          font-size:13px;font-weight:700;color:<?= $color ?>;
          background:<?= $color ?>12;padding:2px 10px;border-radius:6px;
          border:1px solid <?= $color ?>25"><?= htmlspecialchars($role['slug']) ?></code>
      </div>
    </div>

    <form method="POST">
      <?= $csrfField ?>

      <div class="og-form-group">
        <label class="og-label">Nombre del perfil <span style="color:var(--rose)">*</span></label>
        <input type="text" name="nombre" class="og-input"
               value="<?= htmlspecialchars($_POST['nombre'] ?? $role['nombre']) ?>"
               placeholder="Ej. Administrador" required maxlength="50">
      </div>

      <div class="og-form-group">
        <label class="og-label">Descripción</label>
        <input type="text" name="descripcion" class="og-input"
               value="<?= htmlspecialchars($_POST['descripcion'] ?? $role['descripcion'] ?? '') ?>"
               placeholder="Describe brevemente las capacidades del perfil" maxlength="255">
      </div>

      <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:24px">
        <a href="<?= Router::url('catalogs/roles') ?>" class="og-btn og-btn--ghost">Cancelar</a>
        <button type="submit" class="og-btn">
          <?= ogIcon('check', 14) ?> Guardar cambios
        </button>
      </div>
    </form>
  </div>
</div>
<?php
$content   = ob_get_clean();
$pageTitle = 'Catálogo · Editar perfil';
include ROOT_PATH . 'views/layouts/main.php';
