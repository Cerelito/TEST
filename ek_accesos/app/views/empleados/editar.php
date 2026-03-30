<?php
$title = 'Editar Empleado';
ob_start();

$empleado         = $empleado         ?? [];
$empresas         = $empresas         ?? [];
$programas        = $programas        ?? [];
$centros_asignados = $centros_asignados ?? [];
?>
<style>
    .page-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
    .page-header-title { font-size:26px; font-weight:800; color:#f1f5f9; letter-spacing:-0.5px; }
    .form-card { background:rgba(255,255,255,0.06); backdrop-filter:blur(20px); -webkit-backdrop-filter:blur(20px); border:1px solid rgba(255,255,255,0.12); border-radius:20px; padding:28px; margin-bottom:24px; }
    .form-card-title { font-size:16px; font-weight:700; color:#f1f5f9; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
    .form-card-title svg { color:#818cf8; }
    .form-actions { display:flex; gap:12px; justify-content:flex-end; margin-top:8px; }
    .edit-meta { font-size:12px; color:#64748b; background:rgba(99,102,241,0.08); border:1px solid rgba(99,102,241,0.2); border-radius:8px; padding:6px 12px; display:inline-flex; align-items:center; gap:6px; }
    /* CC Table */
    .cc-table-wrap { overflow-x:auto; }
    .cc-table { width:100%; border-collapse:collapse; font-size:13px; }
    .cc-table th { padding:9px 10px; font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.6px; text-align:left; border-bottom:1px solid rgba(255,255,255,0.08); background:rgba(255,255,255,0.03); white-space:nowrap; }
    .cc-table td { padding:8px 10px; vertical-align:middle; border-bottom:1px solid rgba(255,255,255,0.05); }
    .cc-table tr:last-child td { border-bottom:none; }
    .cc-table input[type="text"], .cc-table select { width:100%; background:rgba(255,255,255,0.07); border:1px solid rgba(255,255,255,0.12); border-radius:7px; color:#f1f5f9; font-size:12px; padding:6px 9px; outline:none; font-family:var(--font); }
    .cc-table input[type="text"]:focus, .cc-table select:focus { border-color:rgba(79,142,247,0.5); }
    .cc-table input[type="number"] { width:90px; background:rgba(255,255,255,0.07); border:1px solid rgba(255,255,255,0.12); border-radius:7px; color:#f1f5f9; font-size:12px; padding:6px 9px; outline:none; font-family:var(--font); }
    .radio-group { display:flex; gap:6px; }
    .radio-group label { display:flex; align-items:center; gap:4px; font-size:11px; color:#94a3b8; cursor:pointer; white-space:nowrap; }
    .del-row-btn { display:inline-flex; align-items:center; justify-content:center; width:26px; height:26px; border-radius:7px; border:1px solid rgba(239,68,68,0.3); background:rgba(239,68,68,0.08); color:#f87171; cursor:pointer; transition:all 0.2s; }
    .del-row-btn:hover { background:rgba(239,68,68,0.18); }
    .add-cc-btn { display:inline-flex; align-items:center; gap:7px; margin-top:12px; padding:8px 16px; border-radius:10px; font-size:13px; font-weight:600; border:1px dashed rgba(79,142,247,0.4); background:rgba(79,142,247,0.07); color:#6fa8ff; cursor:pointer; transition:all 0.2s; font-family:var(--font); }
    .add-cc-btn:hover { background:rgba(79,142,247,0.14); border-color:rgba(79,142,247,0.6); }
    .section-hint { font-size:12px; color:#64748b; margin-bottom:14px; }
    .existing-badge { display:inline-flex; align-items:center; gap:4px; font-size:10px; font-weight:600; padding:2px 7px; border-radius:99px; background:rgba(62,207,142,0.12); color:#3ecf8e; border:1px solid rgba(62,207,142,0.25); }
</style>

<div class="page-header">
    <div>
        <h2 class="page-header-title">Editar Empleado</h2>
        <span style="font-size:13px; color:#64748b;">
            <?php echo htmlspecialchars(trim(($empleado['nombre'] ?? '') . ' ' . ($empleado['apellido'] ?? ''))); ?>
        </span>
    </div>
    <div style="display:flex; gap:10px; align-items:center;">
        <?php if (!empty($empleado['id'])): ?>
        <span class="edit-meta">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            ID #<?php echo (int)$empleado['id']; ?>
            <?php if (!empty($empleado['user_id'])): ?>
             · ERP: <?php echo htmlspecialchars($empleado['user_id']); ?>
            <?php endif; ?>
        </span>
        <?php endif; ?>
        <a href="<?php echo BASE_URL; ?>/empleados" class="btn btn-glass">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            Volver
        </a>
    </div>
</div>

<form method="POST" action="<?php echo BASE_URL; ?>/empleados/actualizar/<?php echo (int)($empleado['id'] ?? 0); ?>" id="formEmpleadoEditar">
    <?php echo csrfField(); ?>

    <!-- Datos Generales -->
    <div class="form-card">
        <div class="form-card-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Datos del Empleado
        </div>
        <div class="form-row form-row-3">
            <div class="form-group">
                <label class="form-label" for="user_id">ID ERP</label>
                <input type="text" id="user_id" name="user_id" class="form-control"
                    placeholder="Ej. EMP-001"
                    value="<?php echo htmlspecialchars($_POST['user_id'] ?? $empleado['user_id'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label class="form-label" for="nombre">Nombre(s) <span style="color:#f87171;">*</span></label>
                <input type="text" id="nombre" name="nombre" class="form-control"
                    placeholder="Nombre(s)" required maxlength="100"
                    value="<?php echo htmlspecialchars($_POST['nombre'] ?? $empleado['nombre'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label class="form-label" for="apellido">Apellido(s)</label>
                <input type="text" id="apellido" name="apellido" class="form-control"
                    placeholder="Apellido paterno y materno" maxlength="150"
                    value="<?php echo htmlspecialchars($_POST['apellido'] ?? $empleado['apellido'] ?? ''); ?>">
            </div>
        </div>
        <div class="form-row form-row-3">
            <div class="form-group">
                <label class="form-label" for="puesto">Puesto</label>
                <input type="text" id="puesto" name="puesto" class="form-control"
                    placeholder="Ej. Coordinador de Compras" maxlength="150"
                    value="<?php echo htmlspecialchars($_POST['puesto'] ?? $empleado['puesto'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label class="form-label" for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" class="form-control"
                    placeholder="correo@empresa.com"
                    value="<?php echo htmlspecialchars($_POST['email'] ?? $empleado['email'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label class="form-label" for="telefono">Teléfono</label>
                <input type="text" id="telefono" name="telefono" class="form-control"
                    placeholder="55 0000-0000" maxlength="20"
                    value="<?php echo htmlspecialchars($_POST['telefono'] ?? $empleado['telefono'] ?? ''); ?>">
            </div>
        </div>
        <div class="form-row form-row-3">
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label" for="empresa_id">Empresa</label>
                <select id="empresa_id" name="empresa_id" class="form-control">
                    <option value="">— Seleccionar empresa —</option>
                    <?php foreach ($empresas as $emp): ?>
                    <?php $selEmp = ($_POST['empresa_id'] ?? $empleado['empresa_id'] ?? ''); ?>
                    <option value="<?php echo (int)$emp['id']; ?>"
                        <?php echo ($selEmp == $emp['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($emp['nombre']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label" for="programa_nivel_id">Programa Nivel</label>
                <select id="programa_nivel_id" name="programa_nivel_id" class="form-control">
                    <option value="">— Sin programa nivel —</option>
                    <?php foreach ($programas as $prog): ?>
                    <?php $selProg = ($_POST['programa_nivel_id'] ?? $empleado['programa_nivel_id'] ?? ''); ?>
                    <option value="<?php echo (int)$prog['id']; ?>"
                        <?php echo ($selProg == $prog['id']) ? 'selected' : ''; ?>>
                        Nivel <?php echo (int)$prog['nivel']; ?> — <?php echo htmlspecialchars($prog['nombre']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label" for="jefe_buscar">Jefe Directo</label>
                <div style="position:relative;">
                    <input type="text" id="jefe_buscar" class="form-control"
                        placeholder="Buscar por nombre..." autocomplete="off"
                        value="<?php echo htmlspecialchars($empleado['jefe_nombre'] ?? ''); ?>">
                    <input type="hidden" id="jefe_id" name="jefe_id" value="<?php echo htmlspecialchars($_POST['jefe_id'] ?? $empleado['jefe_id'] ?? ''); ?>">
                    <div id="jefeSuggestions" style="display:none; position:absolute; top:100%; left:0; right:0; background:rgba(20,24,40,0.97); border:1px solid rgba(255,255,255,0.15); border-radius:10px; z-index:50; max-height:180px; overflow-y:auto; margin-top:4px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Centros de Costo -->
    <div class="form-card">
        <div class="form-card-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
            Centros de Costo Asignados
        </div>
        <p class="section-hint">Modifica los centros de costo del empleado. Las filas existentes se actualizarán; las nuevas se agregarán.</p>

        <div class="cc-table-wrap">
            <table class="cc-table" id="ccTable">
                <thead>
                    <tr>
                        <th>Empresa</th>
                        <th style="min-width:110px;">Código CC</th>
                        <th style="min-width:180px;">Descripción</th>
                        <th>Tipo Doc.</th>
                        <th title="Elaborador">Elab</th>
                        <th title="Visto Bueno">VoBo</th>
                        <th title="Autorizador">Aut</th>
                        <th style="min-width:90px;">Monto Máx.</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="ccBody">
                    <?php foreach ($centros_asignados as $idx => $cc): ?>
                    <tr id="ccRow_<?php echo $idx; ?>">
                        <td>
                            <select name="cc[<?php echo $idx; ?>][empresa_id]">
                                <option value="">—</option>
                                <?php foreach ($empresas as $emp): ?>
                                <option value="<?php echo (int)$emp['id']; ?>"
                                    <?php echo ($cc['empresa_id'] ?? '') == $emp['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($emp['nombre']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (!empty($cc['id'])): ?>
                            <input type="hidden" name="cc[<?php echo $idx; ?>][id]" value="<?php echo (int)$cc['id']; ?>">
                            <?php endif; ?>
                        </td>
                        <td><input type="text" name="cc[<?php echo $idx; ?>][codigo]" value="<?php echo htmlspecialchars($cc['codigo'] ?? ''); ?>" placeholder="CC-001" maxlength="50"></td>
                        <td><input type="text" name="cc[<?php echo $idx; ?>][descripcion]" value="<?php echo htmlspecialchars($cc['descripcion'] ?? ''); ?>" placeholder="Descripción..." maxlength="200"></td>
                        <td>
                            <div class="radio-group">
                                <label><input type="radio" name="cc[<?php echo $idx; ?>][tipo]" value="REQ" <?php echo ($cc['tipo'] ?? '') === 'REQ' ? 'checked' : ''; ?>> REQ</label>
                                <label><input type="radio" name="cc[<?php echo $idx; ?>][tipo]" value="OC"  <?php echo ($cc['tipo'] ?? '') === 'OC'  ? 'checked' : ''; ?>> OC</label>
                                <label><input type="radio" name="cc[<?php echo $idx; ?>][tipo]" value="AMBOS" <?php echo (($cc['tipo'] ?? 'AMBOS') === 'AMBOS') ? 'checked' : ''; ?>> Ambos</label>
                            </div>
                        </td>
                        <td style="text-align:center;"><input type="checkbox" name="cc[<?php echo $idx; ?>][elab]" value="1" style="width:16px;height:16px;cursor:pointer;" <?php echo !empty($cc['elab']) ? 'checked' : ''; ?>></td>
                        <td style="text-align:center;"><input type="checkbox" name="cc[<?php echo $idx; ?>][vobo]" value="1" style="width:16px;height:16px;cursor:pointer;" <?php echo !empty($cc['vobo']) ? 'checked' : ''; ?>></td>
                        <td style="text-align:center;"><input type="checkbox" name="cc[<?php echo $idx; ?>][aut]"  value="1" style="width:16px;height:16px;cursor:pointer;" <?php echo !empty($cc['aut'])  ? 'checked' : ''; ?>></td>
                        <td><input type="number" name="cc[<?php echo $idx; ?>][monto]" value="<?php echo htmlspecialchars($cc['monto'] ?? ''); ?>" placeholder="0.00" min="0" step="0.01"></td>
                        <td><button type="button" class="del-row-btn" onclick="removeCCRow(<?php echo $idx; ?>)" title="Eliminar fila">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <button type="button" class="add-cc-btn" id="addCCRow">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Agregar Centro de Costo
        </button>
    </div>

    <div class="form-actions">
        <a href="<?php echo BASE_URL; ?>/empleados" class="btn btn-glass">Cancelar</a>
        <button type="submit" class="btn btn-primary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            Guardar Cambios
        </button>
    </div>
</form>

<script>
(function () {
    var empresasData = <?php echo json_encode(array_map(function($e) { return ['id' => $e['id'], 'nombre' => $e['nombre']]; }, $empresas)); ?>;
    var ccRowIdx = <?php echo max(count($centros_asignados), 0); ?>;

    function buildCCRow(idx) {
        var empresaOpts = '<option value="">—</option>' + empresasData.map(function(e) {
            return '<option value="' + e.id + '">' + e.nombre.replace(/</g,'&lt;') + '</option>';
        }).join('');

        return '<tr id="ccRow_' + idx + '">' +
            '<td><select name="cc[' + idx + '][empresa_id]">' + empresaOpts + '</select></td>' +
            '<td><input type="text" name="cc[' + idx + '][codigo]" placeholder="CC-001" maxlength="50"></td>' +
            '<td><input type="text" name="cc[' + idx + '][descripcion]" placeholder="Descripción..." maxlength="200"></td>' +
            '<td><div class="radio-group">' +
                '<label><input type="radio" name="cc[' + idx + '][tipo]" value="REQ"> REQ</label>' +
                '<label><input type="radio" name="cc[' + idx + '][tipo]" value="OC"> OC</label>' +
                '<label><input type="radio" name="cc[' + idx + '][tipo]" value="AMBOS" checked> Ambos</label>' +
            '</div></td>' +
            '<td style="text-align:center;"><input type="checkbox" name="cc[' + idx + '][elab]" value="1" style="width:16px;height:16px;cursor:pointer;"></td>' +
            '<td style="text-align:center;"><input type="checkbox" name="cc[' + idx + '][vobo]" value="1" style="width:16px;height:16px;cursor:pointer;"></td>' +
            '<td style="text-align:center;"><input type="checkbox" name="cc[' + idx + '][aut]"  value="1" style="width:16px;height:16px;cursor:pointer;"></td>' +
            '<td><input type="number" name="cc[' + idx + '][monto]" placeholder="0.00" min="0" step="0.01"></td>' +
            '<td><button type="button" class="del-row-btn" onclick="removeCCRow(' + idx + ')" title="Eliminar fila">' +
                '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>' +
            '</button></td>' +
        '</tr>';
    }

    window.removeCCRow = function(idx) {
        var row = document.getElementById('ccRow_' + idx);
        if (row) row.remove();
    };

    document.getElementById('addCCRow').addEventListener('click', function () {
        document.getElementById('ccBody').insertAdjacentHTML('beforeend', buildCCRow(ccRowIdx++));
    });

    // Jefe search
    var jefeInput  = document.getElementById('jefe_buscar');
    var jefeHidden = document.getElementById('jefe_id');
    var jefeSugg   = document.getElementById('jefeSuggestions');
    var jefeTimer;

    if (jefeInput) {
        jefeInput.addEventListener('input', function () {
            clearTimeout(jefeTimer);
            var q = this.value.trim();
            if (q.length < 2) { jefeSugg.style.display = 'none'; return; }
            jefeTimer = setTimeout(function () {
                fetch('<?php echo BASE_URL; ?>/empleados/buscar?q=' + encodeURIComponent(q))
                    .then(function(r) { return r.json(); })
                    .catch(function() { return []; })
                    .then(function(data) {
                        if (!data.length) { jefeSugg.style.display = 'none'; return; }
                        jefeSugg.innerHTML = data.map(function(e) {
                            return '<div style="padding:9px 14px; cursor:pointer; font-size:13px; color:#f1f5f9; border-bottom:1px solid rgba(255,255,255,0.06);" ' +
                                'onmousedown="selectJefe(' + e.id + ',\'' + (e.nombre||'').replace(/'/g,"\\'") + '\')">' +
                                e.nombre + (e.empresa ? ' <span style=\"color:#64748b;font-size:11px;\">— ' + e.empresa + '</span>' : '') +
                                '</div>';
                        }).join('');
                        jefeSugg.style.display = 'block';
                    });
            }, 300);
        });
        jefeInput.addEventListener('blur', function () {
            setTimeout(function() { jefeSugg.style.display = 'none'; }, 200);
        });
    }

    window.selectJefe = function(id, nombre) {
        jefeHidden.value = id;
        jefeInput.value  = nombre;
        jefeSugg.style.display = 'none';
    };
})();
</script>
<?php
$content = ob_get_clean();
include VIEWS_PATH . 'layouts/app.php';
?>
