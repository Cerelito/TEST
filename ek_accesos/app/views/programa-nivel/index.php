<?php
$title = 'Programa Nivel';
ob_start();

$programas = $programas ?? [];
?>
<style>
    .page-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
    .page-header-left { display:flex; flex-direction:column; gap:4px; }
    .page-header-title { font-size:26px; font-weight:800; color:#f1f5f9; letter-spacing:-0.5px; }
    .page-header-sub { font-size:13px; color:#64748b; }
    .filters-bar { display:flex; gap:12px; margin-bottom:20px; flex-wrap:wrap; align-items:center; }
    .search-wrapper { flex:1; min-width:240px; position:relative; }
    .search-icon-pos { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#475569; pointer-events:none; }
    .search-input { width:100%; padding:10px 16px 10px 42px; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:12px; color:#f1f5f9; font-size:14px; font-family:var(--font); outline:none; transition:all 0.2s; }
    .search-input::placeholder { color:#475569; }
    .search-input:focus { border-color:rgba(99,102,241,0.5); background:rgba(99,102,241,0.05); box-shadow:0 0 0 3px rgba(99,102,241,0.1); }
    .empty-state { padding:60px 20px; text-align:center; }
    .empty-state svg { margin:0 auto 16px; display:block; opacity:0.3; }
    .empty-state p { font-size:15px; color:#64748b; }
    .empty-state small { font-size:13px; color:#475569; }
    .nivel-num { font-size:20px; font-weight:800; color:#818cf8; }
    .actions-cell { display:flex; gap:6px; }
    .icon-btn { display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid; cursor:pointer; transition:all 0.2s; text-decoration:none; background:none; font-family:var(--font); }
    .icon-btn-edit { border-color:rgba(245,158,11,0.25); color:#fbbf24; }
    .icon-btn-edit:hover { background:rgba(245,158,11,0.12); }
    .icon-btn-del  { border-color:rgba(239,68,68,0.25); color:#f87171; }
    .icon-btn-del:hover  { background:rgba(239,68,68,0.12); }
</style>

<div class="page-header">
    <div class="page-header-left">
        <h2 class="page-header-title">Programa Nivel</h2>
        <span class="page-header-sub"><?php echo count($programas); ?> programas configurados</span>
    </div>
    <a href="<?php echo BASE_URL; ?>/programa-nivel/crear" class="btn btn-primary">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Nuevo Programa Nivel
    </a>
</div>

<div class="filters-bar">
    <div class="search-wrapper">
        <span class="search-icon-pos">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </span>
        <input type="text" class="search-input" placeholder="Buscar por nombre o nivel..." data-search-table="tbl-pn">
    </div>
</div>

<div class="table-container glass">
    <div class="table-scroll">
        <table id="tbl-pn">
            <thead>
                <tr>
                    <th>Nivel #</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Empleados</th>
                    <th>Módulos activos</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($programas)): ?>
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <p>No hay programas configurados</p>
                            <small>Crea el primer programa nivel para asignarlo a empleados</small>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($programas as $prog): ?>
                <tr>
                    <td><span class="nivel-num"><?php echo (int)($prog['nivel'] ?? 0); ?></span></td>
                    <td>
                        <div style="font-weight:600; color:#f1f5f9;"><?php echo htmlspecialchars($prog['nombre'] ?? ''); ?></div>
                    </td>
                    <td class="td-muted" style="max-width:260px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        <?php echo htmlspecialchars($prog['descripcion'] ?? '—'); ?>
                    </td>
                    <td>
                        <span style="font-weight:700; color:#f1f5f9;"><?php echo (int)($prog['total_empleados'] ?? 0); ?></span>
                        <span class="td-muted"> emp.</span>
                    </td>
                    <td>
                        <span class="badge badge-blue"><?php echo (int)($prog['total_permisos'] ?? 0); ?> módulos</span>
                    </td>
                    <td>
                        <?php if (!empty($prog['activo'])): ?>
                            <span class="badge badge-teal">
                                <span class="dot dot-green"></span> Activo
                            </span>
                        <?php else: ?>
                            <span class="badge badge-red">
                                <span class="dot dot-red"></span> Inactivo
                            </span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="actions-cell">
                            <a href="<?php echo BASE_URL; ?>/programa-nivel/<?php echo (int)$prog['id']; ?>/editar" class="icon-btn icon-btn-edit btn-sm" title="Editar">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                            <form method="POST" action="<?php echo BASE_URL; ?>/programa-nivel/<?php echo (int)$prog['id']; ?>/eliminar" style="display:inline;" onsubmit="return confirm('¿Eliminar el programa «<?php echo htmlspecialchars(addslashes($prog['nombre'] ?? '')); ?>»? Esta acción no se puede deshacer.')">
                                <?php echo csrfField(); ?>
                                <button type="submit" class="icon-btn icon-btn-del btn-sm" title="Eliminar">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
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

<script>
// Live search
(function() {
    var input = document.querySelector('[data-search-table="tbl-pn"]');
    if (!input) return;
    input.addEventListener('input', function() {
        var q = this.value.toLowerCase();
        var rows = document.querySelectorAll('#tbl-pn tbody tr');
        rows.forEach(function(row) {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
})();
</script>
<?php
$content = ob_get_clean();
include VIEWS_PATH . 'layouts/app.php';
?>
