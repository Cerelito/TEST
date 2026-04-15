<?php
$title             = 'Editar Empleado';
$empleado          = $empleado          ?? [];
$empresas          = $empresas          ?? [];
$programas         = $programas         ?? [];
$centros_asignados = $centros_asignados ?? [];
$esAdmin           = $esAdmin           ?? false;
ob_start();

$TIPOS_INSUMO = [
    0=>'Todos los tipos',1=>'MATERIALES',2=>'MANO DE OBRA',3=>'HERRAMIENTA Y EQUIPO',
    4=>'SUBCONTRATOS',5=>'INDIRECTOS',6=>'ADMINISTRATIVOS',7=>'TRAMITES Y PROYECTOS',8=>'BASICOS',9=>'COMERCIAL',
];
$empId    = (int)($empleado['id'] ?? 0);
$aprobado = (int)($empleado['aprobado'] ?? 1);
$nombre   = htmlspecialchars(trim(($empleado['nombre']??'').' '.($empleado['apellido_paterno']??'').' '.($empleado['apellido_materno']??'')));
?>
<style>
.pf-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;flex-wrap:wrap;gap:12px;}
.pf-title{font-size:26px;font-weight:800;color:#f1f5f9;letter-spacing:-.5px;}
.pf-sub{font-size:13px;color:#64748b;margin-top:2px;}
.meta-pill{font-size:11px;color:#64748b;background:rgba(99,102,241,.08);border:1px solid rgba(99,102,241,.2);border-radius:8px;padding:5px 11px;display:inline-flex;align-items:center;gap:6px;}
.pending-pill{font-size:11px;font-weight:700;background:rgba(245,158,11,.12);color:#fbbf24;border:1px solid rgba(245,158,11,.3);border-radius:8px;padding:5px 11px;}
.fc{background:rgba(255,255,255,.06);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,.12);border-radius:20px;padding:26px;margin-bottom:20px;}
.fc-title{font-size:14px;font-weight:700;color:#f1f5f9;margin-bottom:18px;display:flex;align-items:center;gap:8px;padding-bottom:14px;border-bottom:1px solid rgba(255,255,255,.07);}
.fc-title svg{color:#818cf8;}
.fc-admin{border-color:rgba(245,158,11,.3);background:rgba(245,158,11,.03);}
.fc-admin .fc-title{color:#fbbf24;}
.fc-admin .fc-title svg{color:#fbbf24;}
.admin-pill{display:inline-flex;align-items:center;gap:5px;font-size:10px;font-weight:700;letter-spacing:.5px;text-transform:uppercase;color:#fbbf24;background:rgba(245,158,11,.12);border:1px solid rgba(245,158,11,.25);border-radius:20px;padding:2px 9px;margin-left:8px;}
.form-row-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;}
.form-row-2{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
@media(max-width:900px){.form-row-3{grid-template-columns:1fr 1fr;}}
@media(max-width:560px){.form-row-3,.form-row-2{grid-template-columns:1fr;}}
.fg{display:flex;flex-direction:column;gap:6px;margin-bottom:16px;}
.fg:last-child{margin-bottom:0;}
.form-row-3 .fg,.form-row-2 .fg{margin-bottom:0;}
.form-label{font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.6px;}
.form-control{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);border-radius:10px;color:#f1f5f9;font-size:14px;padding:10px 14px;outline:none;width:100%;font-family:var(--font);transition:border-color .2s,box-shadow .2s;}
.form-control:focus{border-color:rgba(99,102,241,.5);box-shadow:0 0 0 3px rgba(99,102,241,.1);}
.form-control::placeholder{color:#475569;}
select.form-control{appearance:none;-webkit-appearance:none;background-image:url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none'%3E%3Cpath d='M1 1L6 7L11 1' stroke='%2364748b' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;padding-right:34px;}
.req{color:#f87171;}
.hint{font-size:11px;color:#475569;}
/* toggle */
.tog-row{display:flex;align-items:center;gap:12px;}
.tog{position:relative;display:inline-flex;align-items:center;cursor:pointer;}
.tog input{position:absolute;opacity:0;width:0;height:0;}
.tog-track{width:40px;height:22px;background:rgba(255,255,255,.1);border-radius:11px;border:1px solid rgba(255,255,255,.15);transition:background .2s;}
.tog input:checked~.tog-track{background:rgba(99,102,241,.5);border-color:rgba(99,102,241,.6);}
.tog-thumb{position:absolute;left:3px;top:50%;transform:translateY(-50%);width:16px;height:16px;background:#94a3b8;border-radius:50%;transition:left .2s,background .2s;}
.tog input:checked~.tog-thumb{left:21px;background:#fff;}
/* CC */
.cc-wrap{overflow-x:auto;border-radius:12px;border:1px solid rgba(255,255,255,.08);}
.cc-table{width:100%;border-collapse:collapse;font-size:13px;min-width:680px;}
.cc-table th{padding:10px 12px;font-size:10px;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.8px;text-align:left;border-bottom:1px solid rgba(255,255,255,.07);background:rgba(255,255,255,.02);}
.cc-table td{padding:8px 10px;vertical-align:middle;border-bottom:1px solid rgba(255,255,255,.04);}
.cc-table tr:last-child td{border-bottom:none;}
.cc-table select,.cc-table input[type=number]{background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);border-radius:7px;color:#f1f5f9;font-size:12px;padding:6px 8px;outline:none;width:100%;}
.cc-table input[type=number]{width:90px;}
.rg{display:flex;gap:4px;flex-wrap:wrap;}
.rg label{display:flex;align-items:center;gap:4px;font-size:11px;color:#94a3b8;cursor:pointer;padding:3px 7px;border-radius:6px;border:1px solid rgba(255,255,255,.08);transition:all .15s;}
.rg label:has(input:checked){background:rgba(99,102,241,.15);border-color:rgba(99,102,241,.3);color:#818cf8;}
.del-btn{display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;border-radius:7px;border:1px solid rgba(239,68,68,.3);background:rgba(239,68,68,.08);color:#f87171;cursor:pointer;transition:all .2s;}
.del-btn:hover{background:rgba(239,68,68,.2);}
.add-cc{display:inline-flex;align-items:center;gap:7px;margin-top:14px;padding:9px 18px;border-radius:10px;font-size:13px;font-weight:600;border:1px dashed rgba(99,102,241,.4);background:rgba(99,102,241,.06);color:#818cf8;cursor:pointer;transition:all .2s;font-family:var(--font);}
.add-cc:hover{background:rgba(99,102,241,.12);border-color:rgba(99,102,241,.7);}
.form-actions{display:flex;gap:12px;justify-content:space-between;padding-top:8px;flex-wrap:wrap;}
.fa-right{display:flex;gap:12px;flex-wrap:wrap;}
.btn{display:inline-flex;align-items:center;gap:8px;padding:11px 22px;border-radius:12px;font-size:14px;font-weight:600;text-decoration:none;cursor:pointer;transition:all .2s;border:1px solid;font-family:var(--font);}
.btn-primary{background:linear-gradient(135deg,#6366f1,#8b5cf6);border-color:transparent;color:#fff;box-shadow:0 4px 15px rgba(99,102,241,.35);}
.btn-primary:hover{transform:translateY(-1px);box-shadow:0 8px 25px rgba(99,102,241,.5);}
.btn-glass{background:rgba(255,255,255,.06);border-color:rgba(255,255,255,.12);color:#94a3b8;}
.btn-glass:hover{background:rgba(255,255,255,.1);color:#f1f5f9;}
.btn-danger{background:rgba(239,68,68,.1);border-color:rgba(239,68,68,.3);color:#f87171;}
.btn-danger:hover{background:rgba(239,68,68,.2);}
.cb-c{text-align:center;}
.cb-c input[type=checkbox]{width:16px;height:16px;cursor:pointer;accent-color:#6366f1;}
</style>

<div class="pf-header">
    <div>
        <h2 class="pf-title">Editar Empleado</h2>
        <p class="pf-sub"><?= $nombre ?></p>
    </div>
    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
        <?php if ($empId): ?>
        <span class="meta-pill">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            ID #<?= $empId ?>
            <?php if (!empty($empleado['user_id'])): ?> · ERP: <?= htmlspecialchars($empleado['user_id']) ?><?php endif; ?>
        </span>
        <?php endif; ?>
        <?php if ($aprobado === 0): ?>
        <span class="pending-pill">⚠ Pendiente de aprobación</span>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>/empleados" class="btn btn-glass">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            Volver
        </a>
    </div>
</div>

<form method="POST" action="<?= BASE_URL ?>/empleados/actualizar/<?= $empId ?>" id="formEdit" autocomplete="off">
    <?= csrfField() ?>

    <!-- Datos Personales -->
    <div class="fc">
        <div class="fc-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Datos Personales
        </div>
        <div class="form-row-3">
            <div class="fg">
                <label class="form-label">Nombre(s) <span class="req">*</span></label>
                <input type="text" name="nombre" class="form-control" required maxlength="100"
                    value="<?= htmlspecialchars($_POST['nombre'] ?? $empleado['nombre'] ?? '') ?>">
            </div>
            <div class="fg">
                <label class="form-label">Apellido Paterno</label>
                <input type="text" name="apellido_paterno" class="form-control" maxlength="100"
                    value="<?= htmlspecialchars($_POST['apellido_paterno'] ?? $empleado['apellido_paterno'] ?? '') ?>">
            </div>
            <div class="fg">
                <label class="form-label">Apellido Materno</label>
                <input type="text" name="apellido_materno" class="form-control" maxlength="100"
                    value="<?= htmlspecialchars($_POST['apellido_materno'] ?? $empleado['apellido_materno'] ?? '') ?>">
            </div>
        </div>
        <div class="form-row-3" style="margin-top:16px;">
            <div class="fg">
                <label class="form-label">Puesto</label>
                <input type="text" name="puesto" class="form-control" maxlength="150"
                    value="<?= htmlspecialchars($_POST['puesto'] ?? $empleado['puesto'] ?? '') ?>">
            </div>
            <div class="fg">
                <label class="form-label">Correo Operativo</label>
                <input type="email" name="email" class="form-control"
                    value="<?= htmlspecialchars($_POST['email'] ?? $empleado['email'] ?? '') ?>">
            </div>
            <div class="fg">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control" maxlength="20"
                    value="<?= htmlspecialchars($_POST['telefono'] ?? $empleado['telefono'] ?? '') ?>">
            </div>
        </div>
    </div>

    <!-- Organización -->
    <div class="fc">
        <div class="fc-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="8" y="2" width="8" height="4" rx="1"/><rect x="2" y="14" width="6" height="4" rx="1"/><rect x="9" y="14" width="6" height="4" rx="1"/><rect x="16" y="14" width="6" height="4" rx="1"/><line x1="12" y1="6" x2="12" y2="10"/><line x1="5" y1="16" x2="5" y2="10"/><line x1="12" y1="16" x2="12" y2="10"/><line x1="19" y1="16" x2="19" y2="10"/><line x1="5" y1="10" x2="19" y2="10"/></svg>
            Asignación Organizacional
        </div>
        <div class="form-row-3">
            <div class="fg">
                <label class="form-label">Empresa <span class="req">*</span></label>
                <select name="empresa_id" class="form-control">
                    <option value="">— Seleccionar —</option>
                    <?php foreach ($empresas as $e):
                        $sel = ($_POST['empresa_id'] ?? $empleado['empresa_id'] ?? '') == $e['id']; ?>
                    <option value="<?= (int)$e['id'] ?>" <?= $sel?'selected':'' ?>><?= htmlspecialchars($e['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="fg">
                <label class="form-label">Programa Nivel / Perfil de Permisos</label>
                <select name="programa_nivel_id" class="form-control">
                    <option value="">— Sin asignar —</option>
                    <?php foreach ($programas as $p):
                        $sel = ($_POST['programa_nivel_id'] ?? $empleado['programa_nivel_id'] ?? '') == $p['id']; ?>
                    <option value="<?= (int)$p['id'] ?>" <?= $sel?'selected':'' ?>>
                        Nivel <?= (int)$p['nivel'] ?> — <?= htmlspecialchars($p['nombre']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="fg">
                <label class="form-label">Jefe Directo</label>
                <div style="position:relative;">
                    <input type="text" id="jefe_buscar" class="form-control"
                        placeholder="Buscar por nombre..." autocomplete="off"
                        value="<?= htmlspecialchars($empleado['jefe_nombre'] ?? '') ?>">
                    <input type="hidden" id="jefe_id" name="jefe_id"
                        value="<?= htmlspecialchars($_POST['jefe_id'] ?? $empleado['jefe_id'] ?? '') ?>">
                    <div id="jefeSugg" style="display:none;position:absolute;top:100%;left:0;right:0;background:rgba(10,14,26,.98);border:1px solid rgba(255,255,255,.15);border-radius:10px;z-index:50;max-height:200px;overflow-y:auto;margin-top:4px;box-shadow:0 8px 30px rgba(0,0,0,.5);"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Credenciales ERP — solo admin -->
    <?php if ($esAdmin): ?>
    <div class="fc fc-admin">
        <div class="fc-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            Credenciales ERP
            <span class="admin-pill">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Solo Admin
            </span>
        </div>
        <div class="form-row-3" style="margin-bottom:20px;">
            <div class="fg">
                <label class="form-label">ID ERP</label>
                <input type="text" name="user_id" class="form-control" maxlength="20" placeholder="ej: 42"
                    value="<?= htmlspecialchars($_POST['user_id'] ?? $empleado['user_id'] ?? '') ?>">
            </div>
            <div class="fg">
                <label class="form-label">Contraseña ERP <span style="font-weight:400;color:#475569;">(dejar vacío = no cambiar)</span></label>
                <input type="text" name="password_ek" class="form-control" maxlength="10" autocomplete="new-password" placeholder="••••••••••">
            </div>
            <div class="fg">
                <label class="form-label">NIP <span style="font-weight:400;color:#475569;">(dejar vacío = no cambiar)</span></label>
                <input type="text" name="pin_ek" class="form-control" maxlength="4" autocomplete="new-password" placeholder="••••">
            </div>
        </div>
        <?php if ($aprobado === 0): ?>
        <div style="padding:14px 18px;background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.2);border-radius:12px;display:flex;align-items:center;gap:14px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="2" style="flex-shrink:0;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            <div style="flex:1;">
                <p style="font-size:13px;font-weight:600;color:#fbbf24;margin-bottom:3px;">Registro pendiente de aprobación</p>
                <p style="font-size:12px;color:#64748b;">Completa el ID ERP, contraseña y NIP, luego marca como aprobado para enviar el correo de bienvenida al empleado y notificar al capturista.</p>
            </div>
            <label class="tog" title="Marcar como aprobado">
                <input type="checkbox" name="aprobado" value="1" <?= $aprobado?'checked':'' ?> id="togAprobado">
                <span class="tog-track"></span>
                <span class="tog-thumb"></span>
            </label>
            <span style="font-size:12px;color:#94a3b8;" id="aprobadoLabel"><?= $aprobado?'Aprobado':'Pendiente' ?></span>
        </div>
        <?php else: ?>
        <input type="hidden" name="aprobado" value="1">
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Estado general -->
    <?php if ($esAdmin): ?>
    <div class="fc" style="padding:18px 26px;">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <div>
                <p style="font-size:13px;font-weight:600;color:#f1f5f9;">Estado del empleado</p>
                <p style="font-size:12px;color:#64748b;margin-top:2px;">Activo / Inactivo en el sistema</p>
            </div>
            <label class="tog">
                <input type="checkbox" name="activo" value="1" <?= !empty($empleado['activo'])?'checked':'' ?> id="togActivo">
                <span class="tog-track"></span>
                <span class="tog-thumb"></span>
            </label>
            <span style="font-size:13px;color:#94a3b8;" id="activoLabel"><?= !empty($empleado['activo'])?'Activo':'Inactivo' ?></span>
        </div>
    </div>
    <?php endif; ?>

    <!-- Centros de Costo -->
    <div class="fc">
        <div class="fc-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
            Centros de Costo
        </div>
        <p style="font-size:12px;color:#64748b;margin-bottom:14px;">Indica el tipo de documento, tipo de insumo y permisos por cada centro de costo.</p>
        <div class="cc-wrap">
            <table class="cc-table">
                <thead><tr>
                    <th>Empresa</th><th style="min-width:160px;">Centro de Costo</th>
                    <th>Tipo Doc.</th><th style="min-width:140px;">Tipo Insumo</th>
                    <th title="Elaboración">Elab</th><th title="Visto Bueno">VoBo</th><th title="Autorización">Aut</th>
                    <th>Monto Máx.</th><th></th>
                </tr></thead>
                <tbody id="ccBody">
                <?php foreach ($centros_asignados as $ci => $cc):
                    $tipo = $cc['tipo'] ?? 'AMBOS'; ?>
                <tr id="r<?= $ci ?>">
                    <td>
                        <select name="centros[<?= $ci ?>][empresa_id]" onchange="lCC(this,<?= $ci ?>)">
                            <option value="">— Empresa —</option>
                            <?php foreach ($empresas as $e): ?>
                            <option value="<?= (int)$e['id'] ?>" <?= ($cc['empresa_id']==$e['id'])?'selected':'' ?>><?= htmlspecialchars($e['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <select name="centros[<?= $ci ?>][cc_id]" id="cs<?= $ci ?>">
                            <option value="">— CC —</option>
                            <?php if (!empty($cc['cc_id'])): ?>
                            <option value="<?= (int)$cc['cc_id'] ?>" selected><?= htmlspecialchars(($cc['codigo']??'').' – '.($cc['descripcion']??'')) ?></option>
                            <?php endif; ?>
                        </select>
                    </td>
                    <td>
                        <div class="rg">
                            <?php foreach (['REQ','OC','AMBOS'] as $v): ?>
                            <label><input type="radio" name="centros[<?= $ci ?>][tipo]" value="<?= $v ?>" <?= $tipo===$v?'checked':'' ?>><?= $v ?></label>
                            <?php endforeach; ?>
                        </div>
                    </td>
                    <td>
                        <select name="centros[<?= $ci ?>][tipo_insumo]">
                            <?php foreach ($TIPOS_INSUMO as $k=>$v): ?>
                            <option value="<?= $k ?>" <?= ((int)($cc['tipo_insumo']??0)===$k)?'selected':'' ?>><?= htmlspecialchars($v) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td class="cb-c"><input type="checkbox" name="centros[<?= $ci ?>][elab]" value="1" <?= !empty($cc['elab'])?'checked':'' ?> style="width:16px;height:16px;accent-color:#6366f1;"></td>
                    <td class="cb-c"><input type="checkbox" name="centros[<?= $ci ?>][vobo]" value="1" <?= !empty($cc['vobo'])?'checked':'' ?> style="width:16px;height:16px;accent-color:#6366f1;"></td>
                    <td class="cb-c"><input type="checkbox" name="centros[<?= $ci ?>][aut]"  value="1" <?= !empty($cc['aut'])?'checked':'' ?> style="width:16px;height:16px;accent-color:#6366f1;"></td>
                    <td><input type="number" name="centros[<?= $ci ?>][monto]" value="<?= htmlspecialchars($cc['monto']??'') ?>" placeholder="0.00" min="0" step="0.01"></td>
                    <td><button type="button" class="del-btn" onclick="document.getElementById('r<?= $ci ?>').remove()">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <button type="button" class="add-cc" id="addCC">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Agregar Centro de Costo
        </button>
    </div>

    <div class="form-actions">
        <?php if ($esAdmin): ?>
        <form method="POST" action="<?= BASE_URL ?>/empleados/<?= $empId ?>/eliminar" style="display:inline;"
              onsubmit="return confirm('¿Eliminar a <?= htmlspecialchars(addslashes($nombre)) ?>? Esta acción no se puede deshacer.')">
            <?= csrfField() ?>
            <button type="submit" class="btn btn-danger">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                Eliminar
            </button>
        </form>
        <?php else: ?>
        <span></span>
        <?php endif; ?>
        <div class="fa-right">
            <a href="<?= BASE_URL ?>/empleados" class="btn btn-glass">Cancelar</a>
            <button type="submit" class="btn btn-primary">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                Guardar Cambios
            </button>
        </div>
    </div>
</form>

<script>
(function(){
var BASE='<?= BASE_URL ?>';
var TI=<?= json_encode($TIPOS_INSUMO) ?>;
var EMP=<?= json_encode(array_map(fn($e)=>['id'=>(string)$e['id'],'n'=>$e['nombre']],$empresas)) ?>;
var idx=<?= max(count($centros_asignados),0) ?>;

function esc(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/"/g,'&quot;');}
function insOpts(sel){return Object.entries(TI).map(([k,v])=>'<option value="'+k+'"'+(k===(String(sel||'0'))?' selected':'')+'>'+esc(v)+'</option>').join('');}
function empOpts(sel){var o='<option value="">— Empresa —</option>';EMP.forEach(e=>{o+='<option value="'+esc(e.id)+'"'+(e.id===String(sel||'')?' selected':'')+'>'+esc(e.n)+'</option>';});return o;}
function row(i,d){d=d||{};var t=d.tipo||'AMBOS';
return '<tr id="r'+i+'">'+
'<td><select name="centros['+i+'][empresa_id]" onchange="lCC(this,'+i+')">'+empOpts(d.empresa_id)+'</select></td>'+
'<td><select name="centros['+i+'][cc_id]" id="cs'+i+'"><option value="">— CC —</option></select></td>'+
'<td><div class="rg">'+['REQ','OC','AMBOS'].map(v=>'<label><input type="radio" name="centros['+i+'][tipo]" value="'+v+'"'+(t===v?' checked':'')+'>'+v+'</label>').join('')+'</div></td>'+
'<td><select name="centros['+i+'][tipo_insumo]">'+insOpts(0)+'</select></td>'+
'<td class="cb-c"><input type="checkbox" name="centros['+i+'][elab]" value="1" style="width:16px;height:16px;accent-color:#6366f1;"></td>'+
'<td class="cb-c"><input type="checkbox" name="centros['+i+'][vobo]" value="1" style="width:16px;height:16px;accent-color:#6366f1;"></td>'+
'<td class="cb-c"><input type="checkbox" name="centros['+i+'][aut]"  value="1" style="width:16px;height:16px;accent-color:#6366f1;"></td>'+
'<td><input type="number" name="centros['+i+'][monto]" value="" placeholder="0.00" min="0" step="0.01"></td>'+
'<td><button type="button" class="del-btn" onclick="document.getElementById(\'r'+i+'\').remove()">'+
'<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>'+
'</button></td></tr>';}

document.getElementById('addCC').addEventListener('click',()=>{document.getElementById('ccBody').insertAdjacentHTML('beforeend',row(idx++));});

window.lCC=function(sel,i){
    var eId=sel.value;var cs=document.getElementById('cs'+i);
    if(!cs)return;
    if(!eId){cs.innerHTML='<option value="">— CC —</option>';return;}
    fetch(BASE+'/centros-costo/por-empresa?empresa_id='+eId)
        .then(r=>r.json()).catch(()=>({data:[]}))
        .then(resp=>{var list=resp.data||resp||[];
            cs.innerHTML='<option value="">— CC —</option>'+list.map(cc=>'<option value="'+esc(cc.id)+'">'+esc(cc.codigo)+' – '+esc((cc.descripcion||'').substring(0,50))+'</option>').join('');
        });
};

// Jefe
var ji=document.getElementById('jefe_buscar'),jh=document.getElementById('jefe_id'),js=document.getElementById('jefeSugg'),jt;
if(ji){
    ji.addEventListener('input',function(){
        clearTimeout(jt);var q=this.value.trim();
        if(q.length<2){js.style.display='none';return;}
        jt=setTimeout(()=>{
            fetch(BASE+'/empleados/buscar?q='+encodeURIComponent(q))
                .then(r=>r.json()).catch(()=>[])
                .then(data=>{
                    if(!data.length){js.style.display='none';return;}
                    js.innerHTML=data.map(e=>'<div style="padding:10px 14px;cursor:pointer;font-size:13px;color:#f1f5f9;border-bottom:1px solid rgba(255,255,255,.05);display:flex;justify-content:space-between;" onmousedown="sJ('+e.id+',\''+String(e.nombre||'').replace(/\'/g,"\\'")+'\')"><span>'+esc(e.nombre)+'</span>'+(e.empresa?'<span style="color:#475569;font-size:11px;">'+esc(e.empresa)+'</span>':'')+' </div>').join('');
                    js.style.display='block';
                });
        },300);
    });
    ji.addEventListener('blur',()=>setTimeout(()=>{js.style.display='none';},200));
}
window.sJ=function(id,n){jh.value=id;ji.value=n;js.style.display='none';};

// Toggle labels
var ta=document.getElementById('togAprobado'),tl=document.getElementById('aprobadoLabel');
if(ta&&tl) ta.addEventListener('change',()=>{tl.textContent=ta.checked?'Aprobado':'Pendiente';});
var tv=document.getElementById('togActivo'),al=document.getElementById('activoLabel');
if(tv&&al) tv.addEventListener('change',()=>{al.textContent=tv.checked?'Activo':'Inactivo';});
})();
</script>
<?php
$content = ob_get_clean();
include VIEWS_PATH . 'layouts/app.php';
?>
