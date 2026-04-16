<?php
$title    = 'Empresas';
$empresas = $empresas ?? [];
$filters  = $filters  ?? [];
ob_start();
$totalActivas = count(array_filter($empresas, fn($e) => $e['activo']));
$totalCC      = array_sum(array_column($empresas, 'total_cc'));
$totalEmp     = array_sum(array_column($empresas, 'total_empleados'));
?>
<style>
    .page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;}
    .page-title-lg{font-size:26px;font-weight:800;color:#f1f5f9;letter-spacing:-.5px;}
    .kpi-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin-bottom:24px;}
    .kpi-c{background:rgba(255,255,255,.06);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,.12);border-radius:16px;padding:18px 20px;position:relative;overflow:hidden;transition:all .25s;}
    .kpi-c:hover{transform:translateY(-2px);box-shadow:0 12px 32px rgba(0,0,0,.25);}
    .kpi-c::before{content:'';position:absolute;top:0;right:0;width:60px;height:60px;border-radius:50%;background:var(--kc,rgba(99,102,241,.1));transform:translate(20px,-20px);}
    .kpi-ic{width:36px;height:36px;border-radius:10px;background:var(--ki,rgba(99,102,241,.15));border:1px solid var(--kib,rgba(99,102,241,.25));display:flex;align-items:center;justify-content:center;color:var(--kicl,#818cf8);margin-bottom:12px;}
    .kpi-val{font-size:28px;font-weight:800;color:#f1f5f9;line-height:1;letter-spacing:-1px;}
    .kpi-lbl{font-size:12px;color:#64748b;font-weight:500;margin-top:4px;}

    .toolbar{display:flex;align-items:center;gap:10px;margin-bottom:18px;flex-wrap:wrap;}
    .srch-wrap{flex:1;min-width:200px;position:relative;}
    .srch-icon{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#475569;pointer-events:none;}
    .srch-inp{width:100%;padding:9px 14px 9px 38px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:10px;color:#f1f5f9;font-size:13px;font-family:var(--font);outline:none;transition:all .2s;}
    .srch-inp:focus{border-color:rgba(99,102,241,.5);box-shadow:0 0 0 3px rgba(99,102,241,.1);}
    .srch-inp::placeholder{color:#475569;}
    .filter-sel{padding:9px 32px 9px 12px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:10px;color:#94a3b8;font-size:13px;font-family:var(--font);outline:none;appearance:none;-webkit-appearance:none;background-image:url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1L5 5L9 1' stroke='%2364748b' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 10px center;cursor:pointer;}

    .glass-card{background:rgba(255,255,255,.06);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,.12);border-radius:20px;overflow:hidden;}
    .tbl-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch;}
    .emp-tbl{width:100%;border-collapse:collapse;min-width:600px;}
    .emp-tbl th{padding:12px 16px;font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.8px;text-align:left;border-bottom:1px solid rgba(255,255,255,.06);background:rgba(255,255,255,.02);white-space:nowrap;}
    .emp-tbl td{padding:14px 16px;font-size:13px;color:#cbd5e1;border-bottom:1px solid rgba(255,255,255,.04);vertical-align:middle;}
    .emp-tbl tr:last-child td{border-bottom:none;}
    .emp-tbl tr:hover td{background:rgba(255,255,255,.025);}
    .emp-name-cell{display:flex;align-items:center;gap:10px;}
    .emp-ico{width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:#fff;flex-shrink:0;}
    .emp-nm{font-size:14px;font-weight:600;color:#f1f5f9;}
    .emp-cd{font-size:11px;color:#64748b;font-family:monospace;}
    .badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;}
    .badge-blue{background:rgba(59,130,246,.12);color:#60a5fa;border:1px solid rgba(59,130,246,.25);}
    .badge-green{background:rgba(16,185,129,.12);color:#34d399;border:1px solid rgba(16,185,129,.25);}
    .badge-gray{background:rgba(100,116,139,.15);color:#94a3b8;border:1px solid rgba(100,116,139,.25);}
    .badge-red{background:rgba(239,68,68,.12);color:#f87171;border:1px solid rgba(239,68,68,.25);}
    .badge-indigo{background:rgba(99,102,241,.12);color:#818cf8;border:1px solid rgba(99,102,241,.25);}
    .stat-pill{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:600;}
    .actions{display:flex;gap:5px;}
    .ibtn{display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:8px;border:1px solid;cursor:pointer;transition:all .2s;text-decoration:none;background:none;font-family:var(--font);}
    .ibtn-edit{border-color:rgba(245,158,11,.25);color:#fbbf24;}
    .ibtn-edit:hover{background:rgba(245,158,11,.12);}
    .ibtn-tog{border-color:rgba(99,102,241,.25);color:#818cf8;}
    .ibtn-tog:hover{background:rgba(99,102,241,.12);}
    .ibtn-del{border-color:rgba(239,68,68,.25);color:#f87171;}
    .ibtn-del:hover{background:rgba(239,68,68,.12);}
    .empty-st{padding:60px 20px;text-align:center;color:#64748b;}
</style>

<div class="page-header">
    <div>
        <h2 class="page-title-lg">Empresas</h2>
        <span style="font-size:13px;color:#64748b;"><?= count($empresas) ?> empresas registradas</span>
    </div>
    <button type="button" class="btn btn-primary" onclick="abrirModal()">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Nueva Empresa
    </button>
</div>

<!-- KPIs -->
<div class="kpi-row">
    <div class="kpi-c" style="--kc:rgba(99,102,241,.1);--ki:rgba(99,102,241,.15);--kib:rgba(99,102,241,.25);--kicl:#818cf8;">
        <div class="kpi-ic"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
        <div class="kpi-val"><?= count($empresas) ?></div>
        <div class="kpi-lbl">Total Empresas</div>
    </div>
    <div class="kpi-c" style="--kc:rgba(16,185,129,.1);--ki:rgba(16,185,129,.12);--kib:rgba(16,185,129,.25);--kicl:#34d399;">
        <div class="kpi-ic"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></div>
        <div class="kpi-val"><?= $totalActivas ?></div>
        <div class="kpi-lbl">Activas</div>
    </div>
    <div class="kpi-c" style="--kc:rgba(245,158,11,.1);--ki:rgba(245,158,11,.12);--kib:rgba(245,158,11,.25);--kicl:#fbbf24;">
        <div class="kpi-ic"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg></div>
        <div class="kpi-val"><?= $totalCC ?></div>
        <div class="kpi-lbl">Centros de Costo</div>
    </div>
    <div class="kpi-c" style="--kc:rgba(6,182,212,.1);--ki:rgba(6,182,212,.12);--kib:rgba(6,182,212,.25);--kicl:#22d3ee;">
        <div class="kpi-ic"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div>
        <div class="kpi-val"><?= $totalEmp ?></div>
        <div class="kpi-lbl">Empleados</div>
    </div>
</div>

<!-- Toolbar -->
<form method="GET" id="filterForm">
<div class="toolbar">
    <div class="srch-wrap">
        <span class="srch-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></span>
        <input type="text" name="buscar" class="srch-inp" placeholder="Buscar empresa..." value="<?= htmlspecialchars($filters['buscar'] ?? '') ?>" oninput="debounce()">
    </div>
    <select name="activo" class="filter-sel" onchange="this.form.submit()">
        <option value="">Todos los estados</option>
        <option value="1" <?= ($filters['activo'] ?? '') === '1' ? 'selected' : '' ?>>Activas</option>
        <option value="0" <?= ($filters['activo'] ?? '') === '0' ? 'selected' : '' ?>>Inactivas</option>
    </select>
</div>
</form>

<!-- Table -->
<div class="glass-card">
    <div class="tbl-wrap">
        <table class="emp-tbl" id="tblEmpresas">
            <thead>
                <tr>
                    <th>Empresa</th>
                    <th>Código</th>
                    <th>Centros de Costo</th>
                    <th>Empleados</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($empresas)): ?>
                <tr><td colspan="6">
                    <div class="empty-st">
                        <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin:0 auto 14px;display:block;opacity:.25;"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                        <div style="font-size:15px;font-weight:600;margin-bottom:4px;">Sin empresas registradas</div>
                        <div style="font-size:13px;color:#475569;">Agrega la primera empresa con el botón de arriba.</div>
                    </div>
                </td></tr>
                <?php else: foreach ($empresas as $e): ?>
                <?php $ini = strtoupper(substr($e['nombre'], 0, 1)); $activo = (int)$e['activo']; ?>
                <tr>
                    <td>
                        <div class="emp-name-cell">
                            <div class="emp-ico"><?= $ini ?></div>
                            <div>
                                <div class="emp-nm"><?= htmlspecialchars($e['nombre']) ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <?php if ($e['codigo']): ?>
                        <code style="font-size:12px;color:#94a3b8;background:rgba(255,255,255,.06);padding:2px 8px;border-radius:5px;"><?= htmlspecialchars($e['codigo']) ?></code>
                        <?php else: ?><span style="color:#475569;">—</span><?php endif; ?>
                    </td>
                    <td>
                        <?php if ($e['total_cc'] > 0): ?>
                        <a href="<?= BASE_URL ?>/centros-costo?empresa_id=<?= $e['id'] ?>" class="badge badge-blue" style="text-decoration:none;">
                            <?= (int)$e['total_cc'] ?> CC
                        </a>
                        <?php else: ?><span style="color:#475569;font-size:12px;">0</span><?php endif; ?>
                    </td>
                    <td>
                        <?php if ($e['total_empleados'] > 0): ?>
                        <a href="<?= BASE_URL ?>/empleados?empresa=<?= $e['id'] ?>" class="badge badge-indigo" style="text-decoration:none;">
                            <?= (int)$e['total_empleados'] ?> emp.
                        </a>
                        <?php else: ?><span style="color:#475569;font-size:12px;">0</span><?php endif; ?>
                    </td>
                    <td>
                        <?= $activo
                            ? '<span class="badge badge-green">● Activa</span>'
                            : '<span class="badge badge-gray">● Inactiva</span>' ?>
                    </td>
                    <td>
                        <div class="actions">
                            <button type="button" class="ibtn ibtn-edit" title="Editar" onclick='editarEmpresa(<?= json_encode($e, JSON_HEX_TAG) ?>)'>
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <form method="POST" action="<?= BASE_URL ?>/empresas/toggle/<?= $e['id'] ?>" style="display:inline;">
                                <?= csrfField() ?>
                                <button type="submit" class="ibtn ibtn-tog" title="<?= $activo ? 'Desactivar' : 'Activar' ?>">
                                    <?php if ($activo): ?>
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <?php else: ?>
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19M1 1l22 22"/></svg>
                                    <?php endif; ?>
                                </button>
                            </form>
                            <?php if ($e['total_cc'] == 0 && $e['total_empleados'] == 0): ?>
                            <form method="POST" action="<?= BASE_URL ?>/empresas/eliminar/<?= $e['id'] ?>" style="display:inline;"
                                  onsubmit="return confirm('¿Eliminar empresa <?= htmlspecialchars(addslashes($e['nombre'])) ?>? Esta acción no se puede deshacer.')">
                                <?= csrfField() ?>
                                <button type="submit" class="ibtn ibtn-del" title="Eliminar">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Crear/Editar -->
<div class="modal-backdrop" id="modalEmpresa" onclick="if(event.target===this)cerrarModal()">
    <div class="modal">
        <div class="modal-header">
            <span class="modal-title" id="modalTitulo">Nueva Empresa</span>
            <button type="button" class="modal-close" onclick="cerrarModal()">&times;</button>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/empresas/guardar" id="formEmpresa">
            <?= csrfField() ?>
            <input type="hidden" name="id" id="emp_id" value="">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nombre de la Empresa <span style="color:#f87171;">*</span></label>
                    <input type="text" name="nombre" id="emp_nombre" class="form-control" required maxlength="200" placeholder="ej: Empresa 1 - CONDOR 31">
                </div>
                <div class="form-row form-row-2">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Código</label>
                        <input type="text" name="codigo" id="emp_codigo" class="form-control" maxlength="20" placeholder="ej: 1">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Estado</label>
                        <select name="activo" id="emp_activo" class="form-control">
                            <option value="1">Activa</option>
                            <option value="0">Inactiva</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-glass" onclick="cerrarModal()">Cancelar</button>
                <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirModal() {
    document.getElementById('modalTitulo').textContent = 'Nueva Empresa';
    document.getElementById('formEmpresa').reset();
    document.getElementById('emp_id').value = '';
    document.getElementById('btnGuardar').textContent = 'Crear Empresa';
    document.getElementById('modalEmpresa').classList.add('open');
    setTimeout(function(){ document.getElementById('emp_nombre').focus(); }, 150);
}
function cerrarModal() { document.getElementById('modalEmpresa').classList.remove('open'); }
function editarEmpresa(e) {
    document.getElementById('modalTitulo').textContent = 'Editar Empresa';
    document.getElementById('emp_id').value     = e.id;
    document.getElementById('emp_nombre').value = e.nombre;
    document.getElementById('emp_codigo').value = e.codigo || '';
    document.getElementById('emp_activo').value = e.activo;
    document.getElementById('btnGuardar').textContent = 'Guardar Cambios';
    document.getElementById('modalEmpresa').classList.add('open');
}
var debTimer;
function debounce() { clearTimeout(debTimer); debTimer = setTimeout(function(){ document.getElementById('filterForm').submit(); }, 400); }
</script>
<?php
$content = ob_get_clean();
include VIEWS_PATH . 'layouts/app.php';
?>
