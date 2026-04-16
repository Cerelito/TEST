<?php
$title   = 'Centros de Costo';
$centros = $centros  ?? [];
$empresas = $empresas ?? [];
$filters  = $filters  ?? [];
ob_start();

$totalActivos = count(array_filter($centros, fn($c) => $c['activo']));
$totalEmp     = array_sum(array_column($centros, 'total_empleados'));
$totalEmpresas = count(array_unique(array_column($centros, 'empresa_id')));
?>
<style>
    .ph{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;}
    .ph-title{font-size:26px;font-weight:800;color:#f1f5f9;letter-spacing:-.5px;}
    .kpi-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:14px;margin-bottom:24px;}
    .kc{background:rgba(255,255,255,.06);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,.12);border-radius:16px;padding:18px;position:relative;overflow:hidden;transition:all .25s;}
    .kc:hover{transform:translateY(-2px);box-shadow:0 10px 28px rgba(0,0,0,.25);}
    .kc::before{content:'';position:absolute;top:0;right:0;width:55px;height:55px;border-radius:50%;background:var(--kc,rgba(99,102,241,.1));transform:translate(18px,-18px);}
    .kc-ic{width:34px;height:34px;border-radius:9px;background:var(--ki,rgba(99,102,241,.15));border:1px solid var(--kib,rgba(99,102,241,.25));display:flex;align-items:center;justify-content:center;color:var(--kicl,#818cf8);margin-bottom:10px;}
    .kc-val{font-size:26px;font-weight:800;color:#f1f5f9;line-height:1;letter-spacing:-1px;}
    .kc-lbl{font-size:11px;color:#64748b;font-weight:500;margin-top:3px;}
    .toolbar{display:flex;align-items:center;gap:10px;margin-bottom:18px;flex-wrap:wrap;}
    .srch-wrap{flex:1;min-width:180px;position:relative;}
    .srch-icon{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#475569;pointer-events:none;}
    .srch-inp{width:100%;padding:9px 14px 9px 36px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:10px;color:#f1f5f9;font-size:13px;font-family:var(--font);outline:none;transition:all .2s;}
    .srch-inp:focus{border-color:rgba(99,102,241,.5);box-shadow:0 0 0 3px rgba(99,102,241,.1);}
    .srch-inp::placeholder{color:#475569;}
    .fsel{padding:9px 28px 9px 12px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:10px;color:#94a3b8;font-size:13px;font-family:var(--font);outline:none;appearance:none;-webkit-appearance:none;background-image:url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1L5 5L9 1' stroke='%2364748b' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 8px center;cursor:pointer;max-width:200px;}
    .glass-card{background:rgba(255,255,255,.06);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,.12);border-radius:20px;overflow:hidden;}
    .tbl-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch;}
    .cc-tbl{width:100%;border-collapse:collapse;min-width:580px;}
    .cc-tbl th{padding:12px 16px;font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.8px;text-align:left;border-bottom:1px solid rgba(255,255,255,.06);background:rgba(255,255,255,.02);white-space:nowrap;}
    .cc-tbl td{padding:13px 16px;font-size:13px;color:#cbd5e1;border-bottom:1px solid rgba(255,255,255,.04);vertical-align:middle;}
    .cc-tbl tr:last-child td{border-bottom:none;}
    .cc-tbl tr:hover td{background:rgba(255,255,255,.025);}
    .badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;}
    .badge-blue{background:rgba(59,130,246,.12);color:#60a5fa;border:1px solid rgba(59,130,246,.25);}
    .badge-green{background:rgba(16,185,129,.12);color:#34d399;border:1px solid rgba(16,185,129,.25);}
    .badge-gray{background:rgba(100,116,139,.15);color:#94a3b8;border:1px solid rgba(100,116,139,.25);}
    .badge-red{background:rgba(239,68,68,.12);color:#f87171;border:1px solid rgba(239,68,68,.25);}
    .badge-teal{background:rgba(20,184,166,.12);color:#2dd4bf;border:1px solid rgba(20,184,166,.25);}
    .actions{display:flex;gap:5px;}
    .ibtn{display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:8px;border:1px solid;cursor:pointer;transition:all .2s;text-decoration:none;background:none;font-family:var(--font);}
    .ibtn-edit{border-color:rgba(245,158,11,.25);color:#fbbf24;}
    .ibtn-edit:hover{background:rgba(245,158,11,.12);}
    .ibtn-del{border-color:rgba(239,68,68,.25);color:#f87171;}
    .ibtn-del:hover{background:rgba(239,68,68,.12);}
    .empty-st{padding:60px 20px;text-align:center;color:#64748b;}
</style>

<div class="ph">
    <div>
        <h2 class="ph-title">Centros de Costo</h2>
        <span style="font-size:13px;color:#64748b;"><?= count($centros) ?> centros registrados</span>
    </div>
    <button type="button" class="btn btn-primary" onclick="abrirModal()">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Nuevo Centro
    </button>
</div>

<!-- KPIs -->
<div class="kpi-row">
    <div class="kc" style="--kc:rgba(99,102,241,.1);--ki:rgba(99,102,241,.15);--kib:rgba(99,102,241,.25);--kicl:#818cf8;">
        <div class="kc-ic"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg></div>
        <div class="kc-val"><?= count($centros) ?></div>
        <div class="kc-lbl">Total CCs</div>
    </div>
    <div class="kc" style="--kc:rgba(16,185,129,.1);--ki:rgba(16,185,129,.12);--kib:rgba(16,185,129,.25);--kicl:#34d399;">
        <div class="kc-ic"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></div>
        <div class="kc-val"><?= $totalActivos ?></div>
        <div class="kc-lbl">Activos</div>
    </div>
    <div class="kc" style="--kc:rgba(245,158,11,.1);--ki:rgba(245,158,11,.12);--kib:rgba(245,158,11,.25);--kicl:#fbbf24;">
        <div class="kc-ic"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg></div>
        <div class="kc-val"><?= $totalEmpresas ?></div>
        <div class="kc-lbl">Empresas</div>
    </div>
    <div class="kc" style="--kc:rgba(6,182,212,.1);--ki:rgba(6,182,212,.12);--kib:rgba(6,182,212,.25);--kicl:#22d3ee;">
        <div class="kc-ic"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div>
        <div class="kc-val"><?= $totalEmp ?></div>
        <div class="kc-lbl">Asignaciones</div>
    </div>
</div>

<!-- Toolbar -->
<form method="GET" id="filterForm">
<div class="toolbar">
    <div class="srch-wrap">
        <span class="srch-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></span>
        <input type="text" name="buscar" class="srch-inp" placeholder="Buscar código o descripción..." value="<?= htmlspecialchars($filters['buscar'] ?? '') ?>" oninput="debounce()">
    </div>
    <select name="empresa_id" class="fsel" onchange="this.form.submit()">
        <option value="">Todas las empresas</option>
        <?php foreach ($empresas as $e): ?>
        <option value="<?= $e['id'] ?>" <?= ($filters['empresa_id'] ?? '') == $e['id'] ? 'selected' : '' ?>><?= htmlspecialchars($e['nombre']) ?></option>
        <?php endforeach; ?>
    </select>
    <select name="activo" class="fsel" onchange="this.form.submit()">
        <option value="">Todos</option>
        <option value="1" <?= ($filters['activo'] ?? '') === '1' ? 'selected' : '' ?>>Activos</option>
        <option value="0" <?= ($filters['activo'] ?? '') === '0' ? 'selected' : '' ?>>Inactivos</option>
    </select>
</div>
</form>

<!-- Table -->
<div class="glass-card">
    <div class="tbl-wrap">
        <table class="cc-tbl">
            <thead>
                <tr>
                    <th>Empresa</th>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th>Empleados</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($centros)): ?>
                <tr><td colspan="6">
                    <div class="empty-st">
                        <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin:0 auto 14px;display:block;opacity:.25;"><rect x="2" y="3" width="20" height="14" rx="2"/></svg>
                        <div style="font-size:14px;font-weight:600;margin-bottom:4px;">Sin centros de costo</div>
                        <div style="font-size:13px;color:#475569;">Agrega un nuevo centro de costo.</div>
                    </div>
                </td></tr>
                <?php else: foreach ($centros as $cc): $activo = (int)$cc['activo']; ?>
                <tr>
                    <td><span class="badge badge-blue"><?= htmlspecialchars($cc['empresa_nombre'] ?? '—') ?></span></td>
                    <td><code style="font-size:12px;color:#94a3b8;background:rgba(255,255,255,.06);padding:2px 8px;border-radius:5px;"><?= htmlspecialchars($cc['codigo']) ?></code></td>
                    <td style="max-width:280px;"><?= htmlspecialchars($cc['descripcion']) ?></td>
                    <td>
                        <?php if (!empty($cc['total_empleados']) && $cc['total_empleados'] > 0): ?>
                        <span class="badge badge-teal"><?= (int)$cc['total_empleados'] ?> emp.</span>
                        <?php else: ?><span style="color:#475569;font-size:12px;">0</span><?php endif; ?>
                    </td>
                    <td>
                        <?= $activo
                            ? '<span class="badge badge-green">● Activo</span>'
                            : '<span class="badge badge-gray">● Inactivo</span>' ?>
                    </td>
                    <td>
                        <div class="actions">
                            <button type="button" class="ibtn ibtn-edit" title="Editar" onclick='editarCC(<?= json_encode($cc, JSON_HEX_TAG) ?>)'>
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <?php if (isRole(['admin','superadmin'])): ?>
                            <form method="POST" action="<?= BASE_URL ?>/centros-costo/eliminar/<?= $cc['id'] ?>" style="display:inline;"
                                  onsubmit="return confirm('¿Desactivar el CC <?= htmlspecialchars(addslashes($cc['codigo'])) ?>?')">
                                <?= csrfField() ?>
                                <button type="submit" class="ibtn ibtn-del" title="Desactivar">
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

<!-- Modal -->
<div class="modal-backdrop" id="modalCC" onclick="if(event.target===this)cerrarModal()">
    <div class="modal">
        <div class="modal-header">
            <span class="modal-title" id="modalTitulo">Nuevo Centro de Costo</span>
            <button type="button" class="modal-close" onclick="cerrarModal()">&times;</button>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/centros-costo/guardar" id="formCC">
            <?= csrfField() ?>
            <input type="hidden" name="id" id="cc_id" value="">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Empresa <span style="color:#f87171;">*</span></label>
                    <select name="empresa_id" id="cc_empresa" class="form-control" required>
                        <option value="">— Seleccionar empresa —</option>
                        <?php foreach ($empresas as $e): ?>
                        <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-row form-row-2">
                    <div class="form-group">
                        <label class="form-label">Código <span style="color:#f87171;">*</span></label>
                        <input type="text" name="codigo" id="cc_codigo" class="form-control" required maxlength="50" placeholder="ej: P04" style="font-family:monospace;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Estado</label>
                        <select name="activo" id="cc_activo" class="form-control">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Descripción <span style="color:#f87171;">*</span></label>
                    <input type="text" name="descripcion" id="cc_descripcion" class="form-control" required maxlength="300" placeholder="ej: CONSTRUCCION-PERIFERICO">
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
    document.getElementById('modalTitulo').textContent = 'Nuevo Centro de Costo';
    document.getElementById('formCC').reset();
    document.getElementById('cc_id').value = '';
    document.getElementById('btnGuardar').textContent = 'Crear Centro';
    document.getElementById('modalCC').classList.add('open');
    setTimeout(function(){ document.getElementById('cc_empresa').focus(); }, 150);
}
function cerrarModal() { document.getElementById('modalCC').classList.remove('open'); }
function editarCC(cc) {
    document.getElementById('modalTitulo').textContent = 'Editar Centro de Costo';
    document.getElementById('cc_id').value          = cc.id;
    document.getElementById('cc_empresa').value     = cc.empresa_id;
    document.getElementById('cc_codigo').value      = cc.codigo;
    document.getElementById('cc_descripcion').value = cc.descripcion;
    document.getElementById('cc_activo').value      = cc.activo;
    document.getElementById('btnGuardar').textContent = 'Guardar Cambios';
    document.getElementById('modalCC').classList.add('open');
}
var debTimer;
function debounce() { clearTimeout(debTimer); debTimer = setTimeout(function(){ document.getElementById('filterForm').submit(); }, 400); }
</script>
<?php
$content = ob_get_clean();
include VIEWS_PATH . 'layouts/app.php';
?>
