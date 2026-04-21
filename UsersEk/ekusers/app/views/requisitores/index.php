<?php
$title = 'Requisitores';
ob_start();

$requisitores = $requisitores ?? [];
$empleados_disponibles = $empleados_disponibles ?? [];
?>
<style>
    .page-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
    .page-header-left { display:flex; flex-direction:column; gap:4px; }
    .page-header-title { font-size:26px; font-weight:800; color:#f1f5f9; letter-spacing:-0.5px; }
    .page-header-sub { font-size:13px; color:#64748b; }
    .info-banner { display:flex; align-items:flex-start; gap:12px; padding:14px 18px; background:rgba(99,102,241,0.08); border:1px solid rgba(99,102,241,0.2); border-radius:14px; margin-bottom:20px; }
    .info-banner-icon { color:#818cf8; flex-shrink:0; margin-top:1px; }
    .info-banner-text { font-size:13px; color:#94a3b8; line-height:1.6; }
    .info-banner-text strong { color:#f1f5f9; }
    .filters-bar { display:flex; gap:12px; margin-bottom:20px; flex-wrap:wrap; align-items:center; }
    .search-wrapper { flex:1; min-width:240px; position:relative; }
    .search-icon-pos { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#475569; pointer-events:none; }
    .search-input { width:100%; padding:10px 16px 10px 42px; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:12px; color:#f1f5f9; font-size:14px; font-family:var(--font); outline:none; transition:all 0.2s; }
    .search-input::placeholder { color:#475569; }
    .search-input:focus { border-color:rgba(99,102,241,0.5); background:rgba(99,102,241,0.05); }
    .emp-cell { display:flex; align-items:center; gap:12px; }
    .emp-avatar { width:36px; height:36px; border-radius:10px; background:linear-gradient(135deg,#10b981,#06b6d4); display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; color:white; flex-shrink:0; }
    .emp-name { font-size:14px; font-weight:600; color:#f1f5f9; }
    .emp-sub  { font-size:12px; color:#64748b; }
    .actions-cell { display:flex; gap:6px; }
    .icon-btn { display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid; cursor:pointer; transition:all 0.2s; text-decoration:none; background:none; font-family:var(--font); }
    .icon-btn-view { border-color:rgba(99,102,241,0.25); color:#818cf8; }
    .icon-btn-view:hover { background:rgba(99,102,241,0.15); }
    .icon-btn-del { border-color:rgba(239,68,68,0.25); color:#f87171; }
    .icon-btn-del:hover { background:rgba(239,68,68,0.12); }
    .empty-state { padding:60px 20px; text-align:center; }
    .empty-state svg { margin:0 auto 16px; display:block; opacity:0.3; }
    .empty-state p { font-size:15px; color:#64748b; }
    .empty-state small { font-size:13px; color:#475569; }
    /* Modal */
    .modal-select { width:100%; background:rgba(255,255,255,0.07); border:1px solid rgba(255,255,255,0.15); border-radius:10px; color:#f1f5f9; font-size:14px; padding:10px 14px; outline:none; font-family:var(--font); }
    .modal-select:focus { border-color:rgba(99,102,241,0.5); }
</style>

<div class="page-header">
    <div class="page-header-left">
        <h2 class="page-header-title">Requisitores</h2>
        <span class="page-header-sub"><?php echo count($requisitores); ?> requisitores registrados</span>
    </div>
    <button type="button" class="btn btn-primary" onclick="document.getElementById('modalAsignar').classList.add('open')">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Asignar Requisitor
    </button>
</div>

<div class="info-banner">
    <span class="info-banner-icon">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    </span>
    <div class="info-banner-text">
        <strong>¿Qué son los Requisitores?</strong><br>
        Son empleados que elaboran, dan visto bueno o autorizan <strong>REQUISICIONES</strong> en sus centros de costo asignados. Un empleado puede ser requisitor en uno o más centros de costo según su rol (Elaborador / VoBo / Autorizador).
    </div>
</div>

<div class="filters-bar">
    <div class="search-wrapper">
        <span class="search-icon-pos">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </span>
        <input type="text" class="search-input" placeholder="Buscar por nombre o empresa..." data-search-table="tbl-req">
    </div>
</div>

<div class="table-container glass">
    <div class="table-scroll">
        <table id="tbl-req">
            <thead>
                <tr>
                    <th>Empleado</th>
                    <th>Empresa</th>
                    <th>Centros de Costo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($requisitores)): ?>
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            <p>No hay requisitores asignados</p>
                            <small>Asigna empleados como requisitores usando el botón "Asignar Requisitor"</small>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($requisitores as $req): ?>
                <?php
                    $nombre = htmlspecialchars($req['nombre'] ?? '');
                    $ini    = strtoupper(substr($req['nombre'] ?? 'R', 0, 1) . substr(strstr($req['nombre'] ?? ' ', ' ') ?: '', 1, 1));
                ?>
                <tr>
                    <td>
                        <div class="emp-cell">
                            <div class="emp-avatar"><?php echo $ini; ?></div>
                            <div>
                                <div class="emp-name"><?php echo $nombre; ?></div>
                                <div class="emp-sub"><?php echo htmlspecialchars($req['puesto'] ?? ''); ?></div>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge badge-blue"><?php echo htmlspecialchars($req['empresa_nombre'] ?? '—'); ?></span></td>
                    <td>
                        <span class="badge badge-purple">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="3" width="20" height="14" rx="2"/></svg>
                            <?php echo (int)($req['centros_count'] ?? 0); ?> CC
                        </span>
                    </td>
                    <td>
                        <?php if (!empty($req['activo'])): ?>
                            <span class="badge badge-teal"><span class="dot dot-green"></span> Activo</span>
                        <?php else: ?>
                            <span class="badge badge-red"><span class="dot dot-red"></span> Inactivo</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="actions-cell">
                            <a href="<?php echo BASE_URL; ?>/empleados/<?php echo (int)($req['empleado_id'] ?? $req['id']); ?>" class="icon-btn icon-btn-view" title="Ver permisos del empleado">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </a>
                            <form method="POST" action="<?php echo BASE_URL; ?>/requisitores/<?php echo (int)($req['empleado_id'] ?? $req['id']); ?>/quitar" style="display:inline;"
                                onsubmit="return confirm('¿Quitar a <?php echo htmlspecialchars(addslashes($req['nombre'] ?? '')); ?> como requisitor?')">
                                <?php echo csrfField(); ?>
                                <button type="submit" class="icon-btn icon-btn-del" title="Quitar como requisitor">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Asignar Requisitor -->
<div class="modal-backdrop" id="modalAsignar" onclick="if(event.target===this)this.classList.remove('open')">
    <div class="modal">
        <div class="modal-header">
            <span class="modal-title">Asignar Requisitor</span>
            <button type="button" class="modal-close" onclick="document.getElementById('modalAsignar').classList.remove('open')">&times;</button>
        </div>
        <form method="POST" action="<?php echo BASE_URL; ?>/requisitores/asignar">
            <?php echo csrfField(); ?>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="empleado_id_modal">Seleccionar Empleado</label>
                    <select id="empleado_id_modal" name="empleado_id" class="modal-select" required>
                        <option value="">— Buscar empleado —</option>
                        <?php foreach ($empleados_disponibles as $ed): ?>
                        <option value="<?php echo (int)$ed['id']; ?>">
                            <?php echo htmlspecialchars($ed['nombre'] ?? ''); ?>
                            <?php if (!empty($ed['empresa_nombre'])): ?> — <?php echo htmlspecialchars($ed['empresa_nombre']); ?><?php endif; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <p style="font-size:11px; color:#64748b; margin-top:6px;">Solo se listan empleados con centros de costo tipo REQ o AMBOS asignados.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-glass" onclick="document.getElementById('modalAsignar').classList.remove('open')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Asignar</button>
            </div>
        </form>
    </div>
</div>

<script>
(function() {
    var input = document.querySelector('[data-search-table="tbl-req"]');
    if (!input) return;
    input.addEventListener('input', function() {
        var q = this.value.toLowerCase();
        document.querySelectorAll('#tbl-req tbody tr').forEach(function(row) {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
})();
</script>
<?php
$content = ob_get_clean();
include VIEWS_PATH . 'layouts/app.php';
?>
