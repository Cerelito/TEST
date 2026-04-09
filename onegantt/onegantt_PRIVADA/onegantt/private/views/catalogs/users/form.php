<?php
require_once ROOT_PATH . 'views/layouts/icons.php';
$editing = !empty($usuario);
ob_start();
?>
<div class="og-page og-page--narrow">
  <div class="og-page__header">
    <div>
      <a href="<?= Router::url('catalogs/users') ?>" class="og-back">&larr; Volver a Usuarios</a>
      <h1 class="og-page__title">
        <?= ogIcon('user', 20) ?>
        <?= $editing ? 'Editar usuario' : 'Nuevo usuario' ?>
      </h1>
    </div>
  </div>

  <?php if (!empty($error)): ?>
    <div class="og-alert og-alert--error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <div class="og-card">
    <form method="POST" class="og-form">
      <?= $csrfField ?>

      <div class="og-form__row">
        <div class="og-form__group">
          <label for="nombre">Nombre completo <span class="og-req">*</span></label>
          <input type="text" id="nombre" name="nombre" required maxlength="100"
                 placeholder="Ej: Erick García"
                 value="<?= htmlspecialchars($usuario['nombre'] ?? $_POST['nombre'] ?? '') ?>">
        </div>

        <div class="og-form__group">
          <label for="email">Email <span class="og-req">*</span></label>
          <input type="email" id="email" name="email" required maxlength="150"
                 placeholder="usuario@empresa.com"
                 value="<?= htmlspecialchars($usuario['email'] ?? $_POST['email'] ?? '') ?>">
        </div>
      </div>

      <div class="og-form__row">
        <div class="og-form__group">
          <label for="password">
            Contrase&ntilde;a <?= $editing ? '(dejar en blanco para no cambiar)' : '<span class="og-req">*</span>' ?>
          </label>
          <input type="password" id="password" name="password"
                 minlength="8" maxlength="100"
                 placeholder="M&iacute;nimo 8 caracteres"
                 <?= $editing ? '' : 'required' ?>>
        </div>

        <div class="og-form__group">
          <label for="rol_id">Rol <span class="og-req">*</span></label>
          <select id="rol_id" name="rol_id" required>
            <?php foreach ($roles as $r): ?>
            <option value="<?= $r['id'] ?>"
              <?= ($usuario['rol_id'] ?? $_POST['rol_id'] ?? 3) == $r['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($r['nombre']) ?> — <?= htmlspecialchars($r['descripcion'] ?? '') ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <?php if ($editing): ?>
      <div class="og-form__group">
        <label class="og-toggle">
          <input type="checkbox" name="activo" <?= ($usuario['activo'] ?? 1) ? 'checked' : '' ?>>
          <span class="og-toggle__slider"></span>
          Usuario activo
        </label>
      </div>
      <?php endif; ?>

      <!-- Info roles -->
      <div class="og-alert og-alert--info" style="font-size:12px;margin-top:4px">
        <strong>Roles:</strong>
        <strong>Administrador</strong> — acceso total, incluye cat&aacute;logos &bull;
        <strong>Gestor</strong> — crea y asigna tareas y proyectos &bull;
        <strong>Usuario</strong> — ve y actualiza sus propias tareas
      </div>

      <div class="og-form__actions">
        <a href="<?= Router::url('catalogs/users') ?>" class="og-btn og-btn--ghost">Cancelar</a>
        <button type="submit" class="og-btn">
          <?= $editing ? 'Guardar cambios' : 'Crear usuario' ?>
        </button>
      </div>
    </form>
  </div>
</div>
<?php
$content   = ob_get_clean();
$pageTitle = $editing ? 'Editar Usuario' : 'Nuevo Usuario';
include ROOT_PATH . 'views/layouts/main.php';
