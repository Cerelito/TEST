<?php
$title = 'Nuevo Usuario';
ob_start();

$programas       = $programas       ?? [];
$empleados       = $empleados       ?? [];
$usuarios_con_pn = $usuarios_con_pn ?? [];

$esAdmin = isRole(['admin', 'superadmin']);

// Build data-pn attribute map for the JS "copy pn" feature
$pnMapJson = json_encode(
    array_column($usuarios_con_pn, 'pn_id', 'id'),
    JSON_HEX_TAG
);
?>
<div class="mb-4">
  <a href="<?= BASE_URL ?>/usuarios" class="btn btn-glass btn-sm">← Volver</a>
</div>

<div style="max-width:720px;">
  <div class="glass" style="padding:28px 32px;border-radius:var(--radius-lg);">
    <h2 style="font-size:18px;font-weight:700;margin-bottom:4px;">Registrar Usuario</h2>
    <p class="text-muted text-sm mb-5">
      <?= $esAdmin
          ? 'Completa todos los campos. Como administrador puedes activar el usuario de inmediato.'
          : 'El usuario recibirá acceso al sistema una vez que sea aprobado por el administrador.' ?>
    </p>

    <form method="POST" action="<?= BASE_URL ?>/usuarios/guardar">
      <?= csrfField() ?>

      <!-- ── Información Personal ─────────────────────────────── -->
      <div style="font-size:11px;font-weight:700;letter-spacing:1px;color:#6366f1;text-transform:uppercase;margin-bottom:14px;">
        Información Personal
      </div>

      <div class="form-row form-row-2">
        <div class="form-group">
          <label class="form-label">Nombre(s) *</label>
          <input type="text" name="nombre" class="form-control" required
                 value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Apellidos</label>
          <input type="text" name="apellido" class="form-control"
                 value="<?= htmlspecialchars($_POST['apellido'] ?? '') ?>">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Puesto</label>
        <input type="text" name="puesto" class="form-control" placeholder="ej: Jefe de Compras"
               value="<?= htmlspecialchars($_POST['puesto'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label class="form-label">Correo electrónico *</label>
        <input type="email" name="email" id="email" class="form-control" required
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      </div>

      <!-- ── Acceso al Sistema ─────────────────────────────────── -->
      <div style="font-size:11px;font-weight:700;letter-spacing:1px;color:#6366f1;text-transform:uppercase;margin:20px 0 14px;">
        Acceso al Sistema
      </div>

      <div class="form-row form-row-2">
        <div class="form-group">
          <label class="form-label">Usuario (login) *</label>
          <input type="text" name="username" id="username" class="form-control" required
                 placeholder="ej: jgonzalez"
                 value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Rol del sistema</label>
          <select name="rol" class="form-control">
            <option value="usuario"    <?= ($_POST['rol'] ?? '') === 'usuario'    ? 'selected' : '' ?>>Usuario</option>
            <option value="capturista" <?= ($_POST['rol'] ?? '') === 'capturista' ? 'selected' : '' ?>>Capturista</option>
            <?php if ($esAdmin): ?>
            <option value="admin"      <?= ($_POST['rol'] ?? '') === 'admin'      ? 'selected' : '' ?>>Administrador</option>
            <?php endif; ?>
          </select>
        </div>
      </div>

      <div class="form-row form-row-2">
        <div class="form-group">
          <label class="form-label">Contraseña *</label>
          <input type="password" name="password" class="form-control" required minlength="6">
        </div>
        <div class="form-group">
          <label class="form-label">Confirmar contraseña *</label>
          <input type="password" name="confirm_password" class="form-control" required minlength="6">
        </div>
      </div>

      <!-- Programa Nivel + Copiar de -->
      <div class="form-row form-row-2">
        <div class="form-group">
          <label class="form-label">Programa Nivel</label>
          <select name="programa_nivel_id" id="sel_pn" class="form-control">
            <option value="">— Sin asignar —</option>
            <?php foreach ($programas as $p): ?>
            <option value="<?= (int)$p['id'] ?>"
              <?= ((int)($_POST['programa_nivel_id'] ?? 0) === (int)$p['id']) ? 'selected' : '' ?>>
              Nivel <?= (int)$p['nivel'] ?> – <?= htmlspecialchars($p['nombre']) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>
        <?php if (!empty($usuarios_con_pn)): ?>
        <div class="form-group">
          <label class="form-label">Copiar Programa Nivel de</label>
          <select id="copiar_pn_de" name="copiar_pn_de" class="form-control">
            <option value="">— Seleccionar usuario —</option>
            <?php foreach ($usuarios_con_pn as $u): ?>
            <option value="<?= (int)$u['id'] ?>" data-pn="<?= (int)$u['pn_id'] ?>">
              <?= htmlspecialchars(trim($u['nombre'] . ' ' . ($u['apellido'] ?? ''))) ?>
              (N<?= (int)$u['nivel'] ?> – <?= htmlspecialchars($u['pn_nombre']) ?>)
            </option>
            <?php endforeach; ?>
          </select>
          <p style="font-size:11px;color:#64748b;margin-top:5px;">
            Al seleccionar un usuario se copia su Programa Nivel automáticamente.
          </p>
        </div>
        <?php endif; ?>
      </div>

      <!-- Empleado relacionado -->
      <?php if (!empty($empleados)): ?>
      <div class="form-group">
        <label class="form-label">Empleado relacionado (opcional)</label>
        <select name="empleado_id" class="form-control">
          <option value="">— Sin vincular —</option>
          <?php foreach ($empleados as $emp): ?>
          <option value="<?= (int)$emp['id'] ?>"
            <?= ((int)($_POST['empleado_id'] ?? 0) === (int)$emp['id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($emp['nombre']) ?>
            <?php if (!empty($emp['empresa_nombre'])): ?>
             — <?= htmlspecialchars($emp['empresa_nombre']) ?>
            <?php endif; ?>
          </option>
          <?php endforeach; ?>
        </select>
      </div>
      <?php endif; ?>

      <?php if (!$esAdmin): ?>
      <div style="padding:14px 18px;background:rgba(79,142,247,0.08);border:1px solid rgba(79,142,247,0.2);
                  border-radius:12px;margin-bottom:20px;font-size:13px;color:#94a3b8;">
        ℹ El usuario quedará <strong style="color:#f1f5f9;">pendiente de aprobación</strong>.
        Se notificará al administrador (<strong style="color:#f1f5f9;"><?= ADMIN_EMAIL ?></strong>).
      </div>
      <?php endif; ?>

      <!-- ── Credenciales ERP (solo admin) ──────────────────────── -->
      <?php if ($esAdmin): ?>
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
                 value="<?= htmlspecialchars($_POST['num_usuario_ek'] ?? '') ?>">
        </div>
        <div class="form-group" style="margin-bottom:0;">
          <label class="form-label">Contraseña ERP <span style="font-size:10px;color:#64748b;">(10 chars)</span></label>
          <input type="text" name="password_ek" class="form-control"
                 maxlength="10" autocomplete="off" placeholder="10 caracteres exactos"
                 value="<?= htmlspecialchars($_POST['password_ek'] ?? '') ?>">
        </div>
        <div class="form-group" style="margin-bottom:0;">
          <label class="form-label">PIN ERP <span style="font-size:10px;color:#64748b;">(4 chars)</span></label>
          <input type="text" name="pin_ek" class="form-control"
                 maxlength="4" autocomplete="off" placeholder="4 caracteres exactos"
                 value="<?= htmlspecialchars($_POST['pin_ek'] ?? '') ?>">
        </div>
      </div>
      <?php endif; ?>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Crear Usuario</button>
        <a href="<?= BASE_URL ?>/usuarios" class="btn btn-glass">Cancelar</a>
      </div>
    </form>
  </div>
</div>

<script>
// Auto-fill username from email prefix
document.getElementById('email').addEventListener('blur', function () {
    var u = document.getElementById('username');
    if (!u.value) {
        u.value = (this.value.split('@')[0] || '').toLowerCase().replace(/[^a-z0-9._]/g, '');
    }
});

// Copy programa nivel when selecting a source user
var copiarSel = document.getElementById('copiar_pn_de');
if (copiarSel) {
    copiarSel.addEventListener('change', function () {
        var opt = this.options[this.selectedIndex];
        var pnId = opt ? opt.getAttribute('data-pn') : '';
        if (pnId) {
            document.getElementById('sel_pn').value = pnId;
        }
    });
}
</script>
<?php
$content = ob_get_clean();
include VIEWS_PATH . 'layouts/app.php';
?>
