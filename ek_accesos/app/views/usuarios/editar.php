<?php
$title = 'Editar Usuario';
ob_start();
$u        = $usuario   ?? [];
$programas = $programas ?? [];
$esAdmin  = isRole(['admin', 'superadmin']);
$pnActual = (int)($u['programa_nivel_id'] ?? 0);
?>
<style>
    .edit-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
    .edit-title { font-size:24px; font-weight:800; color:#f1f5f9; letter-spacing:-0.4px; }
    .form-card { background:rgba(255,255,255,0.06); backdrop-filter:blur(20px); -webkit-backdrop-filter:blur(20px); border:1px solid rgba(255,255,255,0.12); border-radius:20px; padding:28px; margin-bottom:20px; }
    .section-label { font-size:11px; font-weight:700; letter-spacing:1.2px; color:#6366f1; text-transform:uppercase; margin-bottom:16px; display:flex; align-items:center; gap:8px; }
    .section-label::after { content:''; flex:1; height:1px; background:linear-gradient(90deg,rgba(99,102,241,0.3),transparent); }
    .section-label-warn { color:#f59e0b; }
    .section-label-warn::after { background:linear-gradient(90deg,rgba(245,158,11,0.3),transparent); }
    .form-actions { display:flex; gap:12px; justify-content:flex-end; margin-top:8px; }
    .user-meta { font-size:12px; color:#64748b; display:flex; align-items:center; gap:12px; flex-wrap:wrap; }
    .user-meta span { display:inline-flex; align-items:center; gap:5px; background:rgba(99,102,241,0.08); border:1px solid rgba(99,102,241,0.18); border-radius:8px; padding:4px 10px; }

    /* Profile cards for programa nivel */
    .pn-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(180px,1fr)); gap:10px; margin-top:4px; }
    .pn-option { position:relative; }
    .pn-option input[type="radio"] { position:absolute; opacity:0; width:0; height:0; }
    .pn-card { border:2px solid rgba(255,255,255,0.1); border-radius:14px; padding:14px 16px; cursor:pointer; transition:all 0.2s; background:rgba(255,255,255,0.03); display:flex; flex-direction:column; gap:4px; }
    .pn-card:hover { border-color:rgba(99,102,241,0.35); background:rgba(99,102,241,0.07); }
    .pn-option input:checked + .pn-card { border-color:#6366f1; background:rgba(99,102,241,0.12); box-shadow:0 0 0 3px rgba(99,102,241,0.15); }
    .pn-card-level { font-size:10px; font-weight:700; letter-spacing:0.8px; text-transform:uppercase; color:#64748b; }
    .pn-card-name { font-size:13px; font-weight:600; color:#f1f5f9; line-height:1.3; }
    .pn-card-desc { font-size:11px; color:#64748b; margin-top:2px; }
    .pn-none { border:2px dashed rgba(255,255,255,0.1); border-radius:14px; padding:14px 16px; cursor:pointer; transition:all 0.2s; background:transparent; display:flex; flex-direction:column; gap:4px; }
    .pn-none:hover { border-color:rgba(255,255,255,0.25); }
    .pn-option input:checked + .pn-none { border-color:rgba(100,116,139,0.5); background:rgba(100,116,139,0.08); }

    @media (max-width:640px) {
        .pn-grid { grid-template-columns:1fr 1fr; }
    }
    @media (max-width:400px) {
        .pn-grid { grid-template-columns:1fr; }
    }
</style>

<div class="edit-header">
    <div>
        <h2 class="edit-title">Editar Usuario</h2>
        <div class="user-meta">
            <span>
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                ID #<?= (int)($u['id'] ?? 0) ?>
            </span>
            <span>
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Desde <?= htmlspecialchars(substr($u['created_at'] ?? '', 0, 10)) ?>
            </span>
        </div>
    </div>
    <a href="<?= BASE_URL ?>/usuarios" class="btn btn-glass">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
        Volver
    </a>
</div>

<form method="POST" action="<?= BASE_URL ?>/usuarios/actualizar/<?= (int)($u['id'] ?? 0) ?>">
    <?= csrfField() ?>

    <!-- Personal Info -->
    <div class="form-card">
        <div class="section-label">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Información Personal
        </div>
        <div class="form-row form-row-2">
            <div class="form-group">
                <label class="form-label">Nombre(s) <span style="color:#f87171;">*</span></label>
                <input type="text" name="nombre" class="form-control" required
                    value="<?= htmlspecialchars($u['nombre'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Apellidos</label>
                <input type="text" name="apellido" class="form-control"
                    value="<?= htmlspecialchars($u['apellido'] ?? '') ?>">
            </div>
        </div>
        <div class="form-row form-row-2">
            <div class="form-group">
                <label class="form-label">Puesto</label>
                <input type="text" name="puesto" class="form-control" placeholder="ej: Jefe de Compras"
                    value="<?= htmlspecialchars($u['puesto'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Correo electrónico <span style="color:#f87171;">*</span></label>
                <input type="email" name="email" class="form-control" required
                    value="<?= htmlspecialchars($u['email'] ?? '') ?>">
            </div>
        </div>
    </div>

    <!-- Sistema Access -->
    <div class="form-card">
        <div class="section-label">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            Acceso al Sistema
        </div>
        <div class="form-row form-row-2">
            <div class="form-group">
                <label class="form-label">Usuario (login) <span style="color:#f87171;">*</span></label>
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
        <div class="form-row form-row-2">
            <div class="form-group">
                <label class="form-label">Nueva contraseña <span style="font-size:11px; color:#64748b;">(vacío = no cambiar)</span></label>
                <input type="password" name="nueva_password" class="form-control" minlength="6">
            </div>
            <div class="form-group">
                <label class="form-label">Confirmar contraseña</label>
                <input type="password" name="confirmar_password" class="form-control" minlength="6">
            </div>
        </div>
        <div class="form-group" style="margin-bottom:0;">
            <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                <input type="checkbox" name="activo" value="1"
                    <?= !empty($u['activo']) ? 'checked' : '' ?>
                    style="width:16px; height:16px; accent-color:#6366f1;">
                <span class="form-label" style="margin:0;">Usuario activo</span>
            </label>
        </div>
    </div>

    <!-- Programa Nivel (Profile) -->
    <div class="form-card">
        <div class="section-label">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            Programa Nivel (Perfil de Permisos)
        </div>
        <p style="font-size:13px; color:#64748b; margin-bottom:16px;">Selecciona el perfil que determina a qué módulos del sistema tiene acceso este usuario.</p>
        <div class="pn-grid">
            <!-- No profile option -->
            <div class="pn-option">
                <input type="radio" name="programa_nivel_id" value="" id="pn_none" <?= $pnActual === 0 ? 'checked' : '' ?>>
                <label for="pn_none" class="pn-none">
                    <span style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.8px; color:#475569;">Sin Perfil</span>
                    <span style="font-size:12px; color:#374151; margin-top:2px;">Acceso básico solamente</span>
                </label>
            </div>
            <?php foreach ($programas as $p): ?>
            <div class="pn-option">
                <input type="radio" name="programa_nivel_id" value="<?= (int)$p['id'] ?>"
                    id="pn_<?= (int)$p['id'] ?>"
                    <?= $pnActual === (int)$p['id'] ? 'checked' : '' ?>>
                <label for="pn_<?= (int)$p['id'] ?>" class="pn-card">
                    <span class="pn-card-level">Nivel <?= (int)$p['nivel'] ?></span>
                    <span class="pn-card-name"><?= htmlspecialchars($p['nombre']) ?></span>
                    <?php if (!empty($p['descripcion'])): ?>
                    <span class="pn-card-desc"><?= htmlspecialchars(mb_substr($p['descripcion'], 0, 60)) ?><?= mb_strlen($p['descripcion']) > 60 ? '…' : '' ?></span>
                    <?php endif; ?>
                </label>
            </div>
            <?php endforeach; ?>
        </div>
        <?php if (empty($programas)): ?>
        <div style="padding:20px; text-align:center; color:#475569; font-size:13px;">
            No hay programas nivel creados aún.
            <a href="<?= BASE_URL ?>/programa-nivel/crear" style="color:#818cf8; margin-left:4px;">Crear uno →</a>
        </div>
        <?php endif; ?>
    </div>

    <!-- ERP Credentials (admin only) -->
    <?php if ($esAdmin): ?>
    <div class="form-card" style="border-color:rgba(245,158,11,0.2); background:rgba(245,158,11,0.04);">
        <div class="section-label section-label-warn">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            Credenciales ERP
            <span style="font-size:10px; color:#64748b; background:rgba(245,158,11,0.1); border:1px solid rgba(245,158,11,0.2); border-radius:20px; padding:1px 8px; letter-spacing:0; font-weight:600; text-transform:none;">Solo administradores</span>
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px;" class="erp-grid">
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">N° Usuario EK</label>
                <input type="text" name="num_usuario_ek" class="form-control" maxlength="20"
                    placeholder="ej: 42"
                    value="<?= htmlspecialchars($u['num_usuario_ek'] ?? '') ?>">
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Contraseña ERP <span style="font-size:10px; color:#64748b;">(vacío = no cambiar)</span></label>
                <input type="text" name="password_ek" class="form-control"
                    maxlength="10" autocomplete="off" placeholder="10 caracteres exactos"
                    value="<?= htmlspecialchars($u['password_ek_plain'] ?? '') ?>">
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">PIN ERP <span style="font-size:10px; color:#64748b;">(vacío = no cambiar)</span></label>
                <input type="text" name="pin_ek" class="form-control"
                    maxlength="4" autocomplete="off" placeholder="4 dígitos"
                    value="<?= htmlspecialchars($u['pin_ek_plain'] ?? '') ?>">
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="form-actions">
        <a href="<?= BASE_URL ?>/usuarios" class="btn btn-glass">Cancelar</a>
        <button type="submit" class="btn btn-primary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            Guardar Cambios
        </button>
    </div>
</form>

<style>
@media (max-width:640px) {
    .erp-grid { grid-template-columns:1fr !important; }
}
</style>
<?php
$content = ob_get_clean();
include VIEWS_PATH . 'layouts/app.php';
?>
