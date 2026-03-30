<?php
$title = 'Nuevo Usuario';
ob_start();
?>
<div class="mb-4">
  <a href="<?= BASE_URL ?>/usuarios" class="btn btn-glass btn-sm">← Volver</a>
</div>

<div style="max-width:640px;">
  <div class="glass" style="padding:28px 32px;border-radius:var(--radius-lg);">
    <h2 style="font-size:18px;font-weight:700;margin-bottom:6px;">Registrar Usuario</h2>
    <p class="text-muted text-sm mb-4">El usuario recibirá acceso al sistema una vez que sea aprobado por el administrador.</p>

    <form method="POST" action="<?= BASE_URL ?>/usuarios/guardar">
      <?= csrfField() ?>

      <div class="form-row form-row-2">
        <div class="form-group">
          <label class="form-label">Nombre *</label>
          <input type="text" name="nombre" class="form-control" required value="<?= htmlspecialchars($_POST['nombre']??'') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Apellido</label>
          <input type="text" name="apellido" class="form-control" value="<?= htmlspecialchars($_POST['apellido']??'') ?>">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Correo electrónico *</label>
        <input type="email" name="email" id="email" class="form-control" required value="<?= htmlspecialchars($_POST['email']??'') ?>">
      </div>

      <div class="form-group">
        <label class="form-label">Usuario (login) *</label>
        <input type="text" name="username" id="username" class="form-control" required value="<?= htmlspecialchars($_POST['username']??'') ?>" placeholder="ej: jgonzalez">
      </div>

      <div class="form-row form-row-2">
        <div class="form-group">
          <label class="form-label">Contraseña *</label>
          <input type="password" name="password" class="form-control" required minlength="8">
        </div>
        <div class="form-group">
          <label class="form-label">Confirmar contraseña *</label>
          <input type="password" name="confirm_password" class="form-control" required minlength="8">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Rol del sistema</label>
        <select name="rol" class="form-control">
          <option value="usuario" <?= ($_POST['rol']??'')==='usuario'?'selected':'' ?>>Usuario</option>
          <option value="capturista" <?= ($_POST['rol']??'')==='capturista'?'selected':'' ?>>Capturista</option>
          <?php if(isRole(['admin','superadmin'])): ?>
          <option value="admin" <?= ($_POST['rol']??'')==='admin'?'selected':'' ?>>Administrador</option>
          <?php endif; ?>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label">Programa Nivel (opcional)</label>
        <select name="programa_nivel_id" class="form-control">
          <option value="">— Sin asignar —</option>
          <?php foreach($programas ?? [] as $p): ?>
          <option value="<?= $p['id'] ?>" <?= ($_POST['programa_nivel_id']??'')==$p['id']?'selected':'' ?>>
            Nivel <?= $p['nivel'] ?> – <?= htmlspecialchars($p['nombre']) ?>
          </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="glass-strong" style="padding:16px;border-radius:10px;margin-bottom:20px;background:rgba(79,142,247,.08);border:1px solid rgba(79,142,247,.20);">
        <div style="font-size:13px;color:var(--blue-light);">
          ℹ El usuario quedará <strong>pendiente de aprobación</strong>. Se enviará un correo de notificación al administrador (<strong><?= ADMIN_EMAIL ?></strong>).
        </div>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Crear Usuario</button>
        <a href="<?= BASE_URL ?>/usuarios" class="btn btn-glass">Cancelar</a>
      </div>
    </form>
  </div>
</div>

<script>
// Auto-fill username from email prefix
document.getElementById('email').addEventListener('blur', function() {
  var usernameField = document.getElementById('username');
  if (!usernameField.value) {
    var email = this.value;
    var prefix = email.split('@')[0] || '';
    usernameField.value = prefix.toLowerCase().replace(/[^a-z0-9._]/g,'');
  }
});
</script>
<?php
$content = ob_get_clean();
include VIEWS_PATH . 'layouts/app.php';
?>
