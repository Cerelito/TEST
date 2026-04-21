<?php
$title = 'Nueva Contraseña';
ob_start();
?>
<div style="max-width:420px;margin:0 auto;">
  <div class="glass" style="padding:36px 32px;border-radius:20px;text-align:center;">
    <div style="width:54px;height:54px;border-radius:15px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:24px;">🔒</div>
    <h1 style="font-size:22px;font-weight:800;margin-bottom:8px;">Nueva contraseña</h1>
    <p style="font-size:13px;color:var(--text-secondary);margin-bottom:28px;">Ingresa tu nueva contraseña segura.</p>

    <form method="POST" action="<?= BASE_URL ?>/auth/guardar-password" style="text-align:left;">
      <?= csrfField() ?>
      <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">

      <div class="form-group">
        <label class="form-label">Nueva contraseña</label>
        <input type="password" name="password" class="form-control" required minlength="8" placeholder="Mínimo 8 caracteres">
      </div>
      <div class="form-group">
        <label class="form-label">Confirmar contraseña</label>
        <input type="password" name="confirm_password" class="form-control" required minlength="8">
      </div>

      <button type="submit" class="btn btn-primary" style="width:100%;margin-top:8px;">Cambiar Contraseña</button>
      <div style="text-align:center;margin-top:16px;">
        <a href="<?= BASE_URL ?>/auth/login" style="color:var(--text-muted);font-size:13px;text-decoration:none;">← Volver al inicio</a>
      </div>
    </form>
  </div>
</div>
<?php
$content = ob_get_clean();
include VIEWS_PATH . 'layouts/auth-layout.php';
?>
