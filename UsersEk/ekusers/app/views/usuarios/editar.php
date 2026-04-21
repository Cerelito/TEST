<?php
$title = 'Editar Usuario';
ob_start();
$u       = $usuario  ?? [];
$programas = $programas ?? [];
$esAdmin = isRole(['admin', 'superadmin']);
?>
<div class="mb-4">
  <a href="<?= BASE_URL ?>/usuarios" class="btn btn-glass btn-sm">← Volver</a>
</div>

<div style="max-width:720px;">
  <div class="glass" style="padding:28px 32px;border-radius:var(--radius-lg);">
    <h2 style="font-size:18px;font-weight:700;margin-bottom:4px;">Editar Usuario</h2>
    <p class="text-muted text-sm mb-5">
      ID: <strong><?= (int)($u['id'] ?? 0) ?></strong> — Creado: <?= htmlspecialchars($u['created_at'] ?? '') ?>
    </p>

    <form method="POST" action="<?= BASE_URL ?>/usuarios/actualizar/<?= (int)($u['id'] ?? 0) ?>">
      <?= csrfField() ?>

      <!-- ── Información Personal ──────────────────────────── -->
      <div style="font-size:11px;font-weight:700;letter-spacing:1px;color:#6366f1;text-transform:uppercase;margin-bottom:14px;">
        Información Personal
      </div>

      <div class="form-row form-row-2">
        <div class="form-group">
          <label class="form-label">Nombre(s) *</label>
          <input type="text" name="nombre" class="form-control" required
                 value="<?= htmlspecialchars($u['nombre'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Apellidos</label>
          <input type="text" name="apellido" class="form-control"
                 value="<?= htmlspecialchars($u['apellido'] ?? '') ?>">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Puesto</label>
        <input type="text" name="puesto" class="form-control" placeholder="ej: Jefe de Compras"
               value="<?= htmlspecialchars($u['puesto'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label class="form-label">Correo electrónico *</label>
        <input type="email" name="email" class="form-control" required
               value="<?= htmlspecialchars($u['email'] ?? '') ?>">
      </div>

      <!-- ── Acceso al Sistema ──────────────────────────────── -->
      <div style="font-size:11px;font-weight:700;letter-spacing:1px;color:#6366f1;text-transform:uppercase;margin:20px 0 14px;">
        Acceso al Sistema
      </div>

      <div class="form-row form-row-2">
        <div class="form-group">
          <label class="form-label">Usuario (login) *</label>
          <input type="text" name="username" class="form-control" required
                 value="<?= htmlspecialchars($u['username'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Rol del sistema</label>
          <select name="rol" class="form-control">
            <option value="usuario"    <?= ($u['rol'] ?? '') === 'usuario'    ? 'selected' : '' ?>>Usuario</option>
            <option value="capturista" <?= ($u['rol'] ?? '') === 'capturista' ? 'selected' : '' ?>>Capturista</option>
            <option value="admin"      <?= ($u['rol'] ?? '') === 'admin'      ? 'selected' : '' ?>>Administrador</option>
            <option value="superadmin" <?= ($u['rol'] ?? '') === 'superadmin' ? 'selected' : '' ?>>Super Admin</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Programa Nivel</label>
        <select name="programa_nivel_id" class="form-control">
          <option value="">— Sin asignar —</option>
          <?php foreach ($programas as $p): ?>
          <option value="<?= (int)$p['id'] ?>"
            <?= ((int)($u['programa_nivel_id'] ?? 0) === (int)$p['id']) ? 'selected' : '' ?>>
            Nivel <?= (int)$p['nivel'] ?> – <?= htmlspecialchars($p['nombre']) ?>
          </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-row form-row-2">
        <div class="form-group">
          <label class="form-label">Nueva contraseña <span style="font-size:11px;color:#64748b;">(dejar vacío para no cambiar)</span></label>
          <input type="password" name="nueva_password" class="form-control" minlength="6">
        </div>
        <div class="form-group">
          <label class="form-label">Confirmar nueva contraseña</label>
          <input type="password" name="confirmar_password" class="form-control" minlength="6">
        </div>
      </div>

      <div class="form-group">
        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
          <input type="checkbox" name="activo" value="1"
                 <?= !empty($u['activo']) ? 'checked' : '' ?>
                 style="width:16px;height:16px;">
          <span class="form-label" style="margin:0;">Usuario activo</span>
        </label>
      </div>

      <!-- ── Credenciales ERP ────────────────────────────────── -->
      <div style="margin:20px 0 14px;">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
          <div style="font-size:11px;font-weight:700;letter-spacing:1px;color:#f59e0b;text-transform:uppercase;">
            Credenciales ERP
          </div>
          <span style="font-size:11px;color:#64748b;background:rgba(245,158,11,0.1);padding:2px 8px;border-radius:20px;border:1px solid rgba(245,158,11,0.2);">
            Solo administradores
          </span>
        </div>
        <div style="height:1px;background:linear-gradient(90deg,rgba(245,158,11,0.3),transparent);margin-bottom:16px;"></div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:20px;">
        <div class="form-group" style="margin-bottom:0;">
          <label class="form-label">N° Usuario EK</label>
          <input type="text" name="num_usuario_ek" class="form-control" maxlength="20"
                 placeholder="ej: 42"
                 value="<?= htmlspecialchars($u['num_usuario_ek'] ?? '') ?>">
        </div>
        <div class="form-group" style="margin-bottom:0;">
          <label class="form-label">Contraseña ERP <span style="font-size:10px;color:#64748b;">(10 chars, dejar vacío = no cambiar)</span></label>
          <input type="text" name="password_ek" class="form-control"
                 maxlength="10" autocomplete="off" placeholder="10 caracteres exactos"
                 value="<?= htmlspecialchars($u['password_ek_plain'] ?? '') ?>">
        </div>
        <div class="form-group" style="margin-bottom:0;">
          <label class="form-label">
            PIN ERP <span style="font-size:10px;color:#64748b;">(4 chars<?= $esAdmin ? ', solo visible admin' : '' ?>)</span>
          </label>
          <?php if ($esAdmin): ?>
          <input type="text" name="pin_ek" class="form-control"
                 maxlength="4" autocomplete="off" placeholder="4 caracteres exactos"
                 value="<?= htmlspecialchars($u['pin_ek_plain'] ?? '') ?>">
          <?php else: ?>
          <input type="password" class="form-control" disabled value="••••"
                 title="Solo el administrador puede ver y modificar el PIN">
          <input type="hidden" name="pin_ek" value="">
          <?php endif; ?>
        </div>
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
