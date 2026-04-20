<?php
$title    = 'Empresas';
$empresas = $empresas ?? [];
$filters  = $filters  ?? [];
ob_start();
?>
<style>
.empresa-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 16px;
    margin-bottom: 28px;
}
.empresa-card {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 16px;
    padding: 20px;
    transition: all 0.2s;
    position: relative;
}
.empresa-card:hover {
    border-color: rgba(99,102,241,0.35);
    background: rgba(255,255,255,0.06);
    transform: translateY(-2px);
    box-shadow: 0 8px 32px rgba(0,0,0,0.2);
}
.empresa-card.inactive { opacity: 0.55; }
.empresa-header { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; }
.empresa-icon {
    width: 44px; height: 44px;
    border-radius: 12px;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; font-weight: 800; color: #fff;
    flex-shrink: 0;
}
.empresa-nombre {
    font-size: 15px; font-weight: 700; color: #f1f5f9;
    line-height: 1.3;
}
.empresa-codigo {
    font-size: 12px; color: #64748b;
    font-family: monospace;
    margin-top: 2px;
}
.empresa-stats {
    display: flex; gap: 16px;
    margin-bottom: 14px;
}
.estad { text-align: center; }
.estad-val { font-size: 22px; font-weight: 800; color: #f1f5f9; line-height: 1; }
.estad-val.blue { color: #60a5fa; }
.estad-val.teal { color: #34d399; }
.estad-lbl { font-size: 11px; color: #64748b; margin-top: 2px; }
.empresa-actions { display: flex; gap: 8px; }
.status-dot {
    position: absolute; top: 16px; right: 16px;
    width: 8px; height: 8px; border-radius: 50%;
}
.status-dot.on  { background: #10b981; box-shadow: 0 0 6px #10b981; }
.status-dot.off { background: #ef4444; }
.modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,0.6); z-index: 300;
    align-items: center; justify-content: center; padding: 20px;
}
.modal-overlay.open { display: flex; }
.modal-box {
    background: #0f1422; border: 1px solid rgba(255,255,255,0.12);
    border-radius: 20px; padding: 32px; width: 100%; max-width: 480px;
    animation: slideUp 0.25s ease;
}
@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
.modal-title { font-size: 18px; font-weight: 700; color: #f1f5f9; margin-bottom: 20px; }
@media (max-width: 600px) {
    .empresa-grid { grid-template-columns: 1fr; }
    .toolbar { flex-direction: column; gap: 10px; }
}
</style>

<!-- Toolbar -->
<div class="toolbar d-flex gap-3 mb-4 align-center" style="flex-wrap:wrap;">
    <form method="GET" action="<?= BASE_URL ?>/empresas" class="d-flex gap-2 align-center" style="flex:1; min-width:200px;">
        <input type="text" name="buscar" class="form-control" placeholder="Buscar empresa..."
               value="<?= htmlspecialchars($filters['buscar'] ?? '') ?>" style="max-width:260px;">
        <select name="activo" class="form-control" style="max-width:150px;" onchange="this.form.submit()">
            <option value="">Todos</option>
            <option value="1" <?= ($filters['activo'] ?? '') === '1' ? 'selected' : '' ?>>Activas</option>
            <option value="0" <?= ($filters['activo'] ?? '') === '0' ? 'selected' : '' ?>>Inactivas</option>
        </select>
        <button class="btn btn-glass" type="submit">Filtrar</button>
    </form>
    <button class="btn btn-primary" onclick="openModal()">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Nueva Empresa
    </button>
</div>

<!-- KPI -->
<div class="kpi-grid mb-4" style="grid-template-columns: repeat(3, 1fr);">
    <div class="glass kpi-card">
        <div class="kpi-label">Total Empresas</div>
        <div class="kpi-value kpi-accent-blue"><?= count($empresas) ?></div>
    </div>
    <div class="glass kpi-card">
        <div class="kpi-label">Activas</div>
        <div class="kpi-value kpi-accent-teal"><?= count(array_filter($empresas, fn($e) => $e['activo'])) ?></div>
    </div>
    <div class="glass kpi-card">
        <div class="kpi-label">Inactivas</div>
        <div class="kpi-value" style="color:#f87171;"><?= count(array_filter($empresas, fn($e) => !$e['activo'])) ?></div>
    </div>
</div>

<!-- Cards -->
<?php if (empty($empresas)): ?>
<div class="glass empty-state">
    <div class="empty-icon">🏢</div>
    <div class="empty-title">Sin empresas registradas</div>
    <div class="empty-text">Haz clic en "Nueva Empresa" para comenzar.</div>
</div>
<?php else: ?>
<div class="empresa-grid">
    <?php foreach ($empresas as $e): ?>
    <div class="empresa-card <?= $e['activo'] ? '' : 'inactive' ?>">
        <span class="status-dot <?= $e['activo'] ? 'on' : 'off' ?>"></span>
        <div class="empresa-header">
            <div class="empresa-icon"><?= strtoupper(substr($e['nombre'], 0, 2)) ?></div>
            <div>
                <div class="empresa-nombre"><?= htmlspecialchars($e['nombre']) ?></div>
                <?php if ($e['codigo']): ?>
                <div class="empresa-codigo">Código: <?= htmlspecialchars($e['codigo']) ?></div>
                <?php endif; ?>
            </div>
        </div>
        <div class="empresa-stats">
            <div class="estad">
                <div class="estad-val blue"><?= (int)($e['total_cc'] ?? 0) ?></div>
                <div class="estad-lbl">Centros de Costo</div>
            </div>
            <div class="estad">
                <div class="estad-val teal"><?= (int)($e['total_empleados'] ?? 0) ?></div>
                <div class="estad-lbl">Empleados</div>
            </div>
        </div>
        <div class="empresa-actions">
            <button class="btn btn-glass btn-sm" style="flex:1;"
                    onclick="editModal(<?= $e['id'] ?>, '<?= addslashes(htmlspecialchars($e['nombre'])) ?>', '<?= addslashes(htmlspecialchars($e['codigo'] ?? '')) ?>')">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Editar
            </button>
            <form method="POST" action="<?= BASE_URL ?>/empresas/toggle-activo/<?= $e['id'] ?>" style="margin:0;" onsubmit="return confirm('¿Cambiar estado?')">
                <?= csrfField() ?>
                <button class="btn btn-sm <?= $e['activo'] ? 'btn-warning' : 'btn-success' ?>" type="submit">
                    <?= $e['activo'] ? 'Desactivar' : 'Activar' ?>
                </button>
            </form>
            <?php if (!(int)($e['total_cc'] ?? 0)): ?>
            <form method="POST" action="<?= BASE_URL ?>/empresas/eliminar/<?= $e['id'] ?>" style="margin:0;"
                  onsubmit="return confirm('¿Eliminar empresa?')">
                <?= csrfField() ?>
                <button class="btn btn-danger btn-sm" type="submit">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Modal Nueva/Editar Empresa -->
<div class="modal-overlay" id="modalEmpresa">
    <div class="modal-box">
        <div class="modal-title" id="modalTitle">Nueva Empresa</div>
        <form method="POST" action="<?= BASE_URL ?>/empresas/guardar" id="formEmpresa">
            <?= csrfField() ?>
            <input type="hidden" name="id" id="frmId" value="0">
            <div class="form-group">
                <label class="form-label">Nombre <span style="color:#f87171;">*</span></label>
                <input type="text" name="nombre" id="frmNombre" class="form-control" required placeholder="Nombre completo de la empresa">
            </div>
            <div class="form-group">
                <label class="form-label">Código</label>
                <input type="text" name="codigo" id="frmCodigo" class="form-control" placeholder="Código corto (ej: 1, 2, 14...)">
            </div>
            <div class="d-flex gap-2" style="margin-top:24px;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Guardar</button>
                <button type="button" class="btn btn-glass" onclick="closeModal()">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('modalTitle').textContent = 'Nueva Empresa';
    document.getElementById('frmId').value = '0';
    document.getElementById('frmNombre').value = '';
    document.getElementById('frmCodigo').value = '';
    document.getElementById('modalEmpresa').classList.add('open');
}
function editModal(id, nombre, codigo) {
    document.getElementById('modalTitle').textContent = 'Editar Empresa';
    document.getElementById('frmId').value = id;
    document.getElementById('frmNombre').value = nombre;
    document.getElementById('frmCodigo').value = codigo;
    document.getElementById('modalEmpresa').classList.add('open');
}
function closeModal() {
    document.getElementById('modalEmpresa').classList.remove('open');
}
document.getElementById('modalEmpresa').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>

<?php
$content = ob_get_clean();
include VIEWS_PATH . 'layouts/app.php';
