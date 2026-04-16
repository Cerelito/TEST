<?php
$title = 'Nuevo Usuario';
ob_start();

$programas       = $programas       ?? [];
$empleados       = $empleados       ?? [];
$usuarios_con_pn = $usuarios_con_pn ?? [];

$esAdmin = isRole(['admin', 'superadmin']);

$pnMapJson = json_encode(
    array_column($usuarios_con_pn, 'pn_id', 'id'),
    JSON_HEX_TAG
);
?>
<style>
    .usr-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
    .usr-header-title { font-size:26px; font-weight:800; color:#f1f5f9; letter-spacing:-0.5px; }
    .form-card { background:rgba(255,255,255,0.06); backdrop-filter:blur(20px); -webkit-backdrop-filter:blur(20px); border:1px solid rgba(255,255,255,0.12); border-radius:20px; padding:28px; margin-bottom:20px; }
    .form-card-title { font-size:14px; font-weight:700; color:#f1f5f9; margin-bottom:18px; display:flex; align-items:center; gap:8px; text-transform:uppercase; letter-spacing:.8px; font-size:11px; color:#6366f1; }

    /* Profile cards */
    .pn-cards { display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:10px; margin-top:4px; }
    .pn-card-label { position:relative; cursor:pointer; }
    .pn-card-label input[type="radio"] { position:absolute; opacity:0; width:0; height:0; }
    .pn-card { border:1px solid rgba(255,255,255,0.09); border-radius:14px; padding:14px 16px; transition:all 0.2s; background:rgba(255,255,255,0.03); }
    .pn-card:hover { background:rgba(255,255,255,0.06); border-color:rgba(99,102,241,0.3); }
    .pn-card-label input:checked ~ .pn-card { background:rgba(99,102,241,0.12); border-color:rgba(99,102,241,0.5); box-shadow:0 0 0 1px rgba(99,102,241,0.3); }
    .pn-card-level { font-size:10px; font-weight:700; letter-spacing:.8px; text-transform:uppercase; color:#818cf8; margin-bottom:5px; }
    .pn-card-name { font-size:13px; font-weight:700; color:#f1f5f9; margin-bottom:4px; }
    .pn-card-desc { font-size:11px; color:#64748b; line-height:1.4; overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; }
    .pn-card-check { width:16px; height:16px; border-radius:50%; border:2px solid rgba(99,102,241,0.4); background:transparent; display:inline-flex; align-items:center; justify-content:center; margin-bottom:8px; transition:all 0.2s; }
    .pn-card-label input:checked ~ .pn-card .pn-card-check { background:#6366f1; border-color:#6366f1; }
    .pn-card-check::after { content:''; width:6px; height:6px; border-radius:50%; background:#fff; opacity:0; transition:opacity 0.15s; }
    .pn-card-label input:checked ~ .pn-card .pn-card-check::after { opacity:1; }
    .pn-none-card .pn-card { border-style:dashed; border-color:rgba(255,255,255,0.08); }
    .pn-none-card input:checked ~ .pn-card { border-style:dashed; border-color:rgba(99,102,241,0.4); background:rgba(99,102,241,0.06); }

    /* Section divider */
    .section-label { font-size:11px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#6366f1; margin-bottom:14px; padding-bottom:8px; border-bottom:1px solid rgba(99,102,241,0.15); }
    .section-label-amber { color:#f59e0b; border-color:rgba(245,158,11,0.15); }
    .form-actions { display:flex; gap:12px; justify-content:flex-end; margin-top:4px; }
    .notice-info { padding:12px 16px; background:rgba(79,142,247,0.08); border:1px solid rgba(79,142,247,0.2); border-radius:12px; font-size:13px; color:#94a3b8; }
    .erp-section { border:1px solid rgba(245,158,11,0.15); border-radius:16px; padding:20px; margin-top:4px; background:rgba(245,158,11,0.03); }
</style>

<div class="usr-header">
    <div>
        <h2 class="usr-header-title">Registrar Usuario</h2>
        <span style="font-size:13px; color:#64748b;">
            <?php echo $esAdmin
                ? 'Como administrador puedes activar el usuario de inmediato.'
                : 'El usuario recibirá acceso una vez aprobado por el administrador.'; ?>
        </span>
    </div>
    <a href="<?php echo BASE_URL; ?>/usuarios" class="btn btn-glass">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
        Volver
    </a>
</div>

<form method="POST" action="<?php echo BASE_URL; ?>/usuarios/guardar" style="max-width:860px;">
    <?php echo csrfField(); ?>

    <!-- Personal Info -->
    <div class="form-card">
        <div class="section-label">Información Personal</div>
        <div class="form-row form-row-2">
            <div class="form-group">
                <label class="form-label">Nombre(s) <span style="color:#f87171;">*</span></label>
                <input type="text" name="nombre" class="form-control" required
                    value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Apellidos</label>
                <input type="text" name="apellido" class="form-control"
                    value="<?php echo htmlspecialchars($_POST['apellido'] ?? ''); ?>">
            </div>
        </div>
        <div class="form-row form-row-2">
            <div class="form-group">
                <label class="form-label">Puesto</label>
                <input type="text" name="puesto" class="form-control" placeholder="ej: Jefe de Compras"
                    value="<?php echo htmlspecialchars($_POST['puesto'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Correo electrónico <span style="color:#f87171;">*</span></label>
                <input type="email" name="email" id="email" class="form-control" required
                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
        </div>
    </div>

    <!-- System Access -->
    <div class="form-card">
        <div class="section-label">Acceso al Sistema</div>
        <div class="form-row form-row-2">
            <div class="form-group">
                <label class="form-label">Usuario (login) <span style="color:#f87171;">*</span></label>
                <input type="text" name="username" id="username" class="form-control" required
                    placeholder="ej: jgonzalez"
                    value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Rol del sistema</label>
                <select name="rol" class="form-control">
                    <option value="usuario"    <?php echo ($_POST['rol'] ?? '') === 'usuario'    ? 'selected' : ''; ?>>Usuario</option>
                    <option value="capturista" <?php echo ($_POST['rol'] ?? '') === 'capturista' ? 'selected' : ''; ?>>Capturista</option>
                    <?php if ($esAdmin): ?>
                    <option value="admin"      <?php echo ($_POST['rol'] ?? '') === 'admin'      ? 'selected' : ''; ?>>Administrador</option>
                    <?php endif; ?>
                </select>
            </div>
        </div>
        <div class="form-row form-row-2">
            <div class="form-group">
                <label class="form-label">Contraseña <span style="color:#f87171;">*</span></label>
                <input type="password" name="password" class="form-control" required minlength="6">
            </div>
            <div class="form-group">
                <label class="form-label">Confirmar contraseña <span style="color:#f87171;">*</span></label>
                <input type="password" name="confirm_password" class="form-control" required minlength="6">
            </div>
        </div>

        <?php if (!empty($empleados)): ?>
        <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Empleado relacionado <span style="color:#64748b; font-weight:400;">(opcional)</span></label>
            <select name="empleado_id" class="form-control">
                <option value="">— Sin vincular —</option>
                <?php foreach ($empleados as $emp): ?>
                <option value="<?php echo (int)$emp['id']; ?>"
                    <?php echo ((int)($_POST['empleado_id'] ?? 0) === (int)$emp['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($emp['nombre']); ?>
                    <?php if (!empty($emp['empresa_nombre'])): ?>
                     — <?php echo htmlspecialchars($emp['empresa_nombre']); ?>
                    <?php endif; ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>
    </div>

    <!-- Programa Nivel (profile cards) -->
    <div class="form-card">
        <div class="section-label">Programa Nivel</div>

        <?php if (!empty($usuarios_con_pn)): ?>
        <div class="form-group" style="margin-bottom:20px;">
            <label class="form-label">Copiar Programa Nivel de otro usuario</label>
            <select id="copiar_pn_de" class="form-control" style="max-width:360px;">
                <option value="">— Seleccionar usuario —</option>
                <?php foreach ($usuarios_con_pn as $u): ?>
                <option value="<?php echo (int)$u['id']; ?>" data-pn="<?php echo (int)$u['pn_id']; ?>">
                    <?php echo htmlspecialchars(trim($u['nombre'] . ' ' . ($u['apellido'] ?? ''))); ?>
                    (N<?php echo (int)$u['nivel']; ?> – <?php echo htmlspecialchars($u['pn_nombre']); ?>)
                </option>
                <?php endforeach; ?>
            </select>
            <p style="font-size:11px; color:#64748b; margin-top:5px;">Al seleccionar un usuario se marca automáticamente su Programa Nivel.</p>
        </div>
        <?php endif; ?>

        <div class="pn-cards" id="pnCards">
            <!-- None option -->
            <label class="pn-card-label pn-none-card">
                <input type="radio" name="programa_nivel_id" value="" id="pn_none"
                    <?php echo empty($_POST['programa_nivel_id']) ? 'checked' : ''; ?>>
                <div class="pn-card">
                    <div class="pn-card-check"></div>
                    <div class="pn-card-level">Sin perfil</div>
                    <div class="pn-card-name">Sin asignar</div>
                    <div class="pn-card-desc">El usuario no tendrá módulos ERP asignados.</div>
                </div>
            </label>
            <?php foreach ($programas as $p): ?>
            <label class="pn-card-label">
                <input type="radio" name="programa_nivel_id" value="<?php echo (int)$p['id']; ?>"
                    id="pn_<?php echo (int)$p['id']; ?>"
                    <?php echo ((int)($_POST['programa_nivel_id'] ?? 0) === (int)$p['id']) ? 'checked' : ''; ?>>
                <div class="pn-card">
                    <div class="pn-card-check"></div>
                    <div class="pn-card-level">Nivel <?php echo (int)$p['nivel']; ?></div>
                    <div class="pn-card-name"><?php echo htmlspecialchars($p['nombre']); ?></div>
                    <?php if (!empty($p['descripcion'])): ?>
                    <div class="pn-card-desc"><?php echo htmlspecialchars($p['descripcion']); ?></div>
                    <?php endif; ?>
                </div>
            </label>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if (!$esAdmin): ?>
    <div class="notice-info" style="margin-bottom:20px;">
        ℹ El usuario quedará <strong style="color:#f1f5f9;">pendiente de aprobación</strong>.
        Se notificará al administrador (<strong style="color:#f1f5f9;"><?php echo ADMIN_EMAIL; ?></strong>).
    </div>
    <?php endif; ?>

    <!-- ERP Credentials (admin only) -->
    <?php if ($esAdmin): ?>
    <div class="form-card">
        <div class="section-label section-label-amber">Credenciales ERP <span style="color:#64748b;font-weight:400;text-transform:none;letter-spacing:0;font-size:11px;margin-left:6px;">Solo administradores</span></div>
        <div class="erp-section">
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px;">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">N° Usuario EK</label>
                    <input type="text" name="num_usuario_ek" class="form-control" maxlength="20"
                        placeholder="ej: 42"
                        value="<?php echo htmlspecialchars($_POST['num_usuario_ek'] ?? ''); ?>">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Contraseña ERP <span style="font-size:10px; color:#64748b;">(10 chars)</span></label>
                    <input type="text" name="password_ek" class="form-control"
                        maxlength="10" autocomplete="off" placeholder="10 caracteres exactos"
                        value="<?php echo htmlspecialchars($_POST['password_ek'] ?? ''); ?>">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">PIN ERP <span style="font-size:10px; color:#64748b;">(4 chars)</span></label>
                    <input type="text" name="pin_ek" class="form-control"
                        maxlength="4" autocomplete="off" placeholder="4 caracteres exactos"
                        value="<?php echo htmlspecialchars($_POST['pin_ek'] ?? ''); ?>">
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="form-actions">
        <a href="<?php echo BASE_URL; ?>/usuarios" class="btn btn-glass">Cancelar</a>
        <button type="submit" class="btn btn-primary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            Crear Usuario
        </button>
    </div>
</form>

<script>
// Auto-fill username from email prefix
document.getElementById('email').addEventListener('blur', function () {
    var u = document.getElementById('username');
    if (!u.value) {
        u.value = (this.value.split('@')[0] || '').toLowerCase().replace(/[^a-z0-9._]/g, '');
    }
});

// Copy programa nivel when selecting source user
var copiarSel = document.getElementById('copiar_pn_de');
if (copiarSel) {
    copiarSel.addEventListener('change', function () {
        var opt = this.options[this.selectedIndex];
        var pnId = opt ? opt.getAttribute('data-pn') : '';
        var targetId = pnId ? 'pn_' + pnId : 'pn_none';
        var radio = document.getElementById(targetId);
        if (radio) radio.checked = true;
    });
}
</script>
<?php
$content = ob_get_clean();
include VIEWS_PATH . 'layouts/app.php';
?>
