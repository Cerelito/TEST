<?php
$title = 'Editar Usuario';
ob_start();
$u = $usuario ?? [];
?>
<div class="mb-4">
  <a href="<?= BASE_URL ?>/usuarios" class="btn btn-glass btn-sm">← Volver</a>
</div>

<div style="max-width:640px;">
  <div class="glass" style="padding:28px 32px;border-radius:var(--radius-lg);">
    <h2 style="font-size:18px;font-weight:700;margin-bottom:6px;">Editar Usuario</h2>
    <p class="text-muted text-sm mb-4">ID: <strong><?= (int)($u['id']??0) ?></strong> — Creado: <?= htmlspecialchars($u['created_at']??'') ?></p>

    <form method="POST" action="<?= BASE_URL ?>/usuarios/actualizar/<?= (int)($u['id']??0) ?>">
      <?= csrfField() ?>

      <div class="form-row form-row-2">
        <div class="form-group">
          <label class="form-label">Nombre *</label>
          <input type="text" name="nombre" class="form-control" required value="<?= htmlspecialchars($u['nombre']??'') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Apellido</label>
          <input type="text" name="apellido" class="form-control" value="<?= htmlspecialchars($u['apellido']??'') ?>">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Correo electrónico *</label>
        <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($u['email']??'') ?>">
      </div>

      <div class="form-group">
        <label class="form-label">Usuario (login) *</label>
        <input type="text" name="username" class="form-control" required value="<?= htmlspecialchars($u['username']??'') ?>">
      </div>

      <div class="form-group">
        <label class="form-label">Rol del sistema</label>
        <select name="rol" class="form-control">
          <option value="usuario"    <?= ($u['rol']??'')==='usuario'   ?'selected':'' ?>>Usuario</option>
          <option value="capturista" <?= ($u['rol']??'')==='capturista'?'selected':'' ?>>Capturista</option>
          <option value="admin"      <?= ($u['rol']??'')==='admin'     ?'selected':'' ?>>Administrador</option>
          <option value="superadmin" <?= ($u['rol']??'')==='superadmin'?'selected':'' ?>>Super Admin</option>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label">Nueva contraseña (dejar vacío para no cambiar)</label>
        <input type="password" name="nueva_password" class="form-control" minlength="6">
      </div>
      <div class="form-group">
        <label class="form-label">Confirmar nueva contraseña</label>
        <input type="password" name="confirmar_password" class="form-control" minlength="6">
      </div>

      <div class="form-group">
        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
          <input type="checkbox" name="activo" value="1" <?= !empty($u['activo'])?'checked':'' ?> style="width:16px;height:16px;">
          <span class="form-label" style="margin:0;">Usuario activo</span>
        </label>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="<?= BASE_URL ?>/usuarios" class="btn btn-glass">Cancelar</a>
      </div>
    </form>
  </div>
</div>
<?php
$content = ob_get_clean();
include VIEWS_PATH . 'layouts/app.php';
?>
