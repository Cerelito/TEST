<?php
$title = 'Empleados';
ob_start();

$empleados = $empleados ?? [];
$empresas  = $empresas  ?? [];
$filters   = $filters   ?? [];
$pagina    = $filters['pagina'] ?? 1;
$total     = $total     ?? count($empleados);
$perPage   = 20;
$totalPag  = ceil($total / $perPage);
?>
<style>
    .page-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
    .page-header-left { display:flex; flex-direction:column; gap:4px; }
    .page-header-title { font-size:26px; font-weight:800; color:#f1f5f9; letter-spacing:-0.5px; }
    .page-header-sub { font-size:13px; color:#64748b; }
    .filters-bar { display:flex; gap:12px; margin-bottom:20px; flex-wrap:wrap; }
    .search-wrapper { flex:1; min-width:240px; position:relative; }
    .search-icon { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#475569; pointer-events:none; }
    .search-input { width:100%; padding:11px 16px 11px 44px; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:12px; color:#f1f5f9; font-size:14px; font-family:var(--font); outline:none; transition:all 0.2s; }
    .search-input::placeholder { color:#475569; }
    .search-input:focus { border-color:rgba(99,102,241,0.5); background:rgba(99,102,241,0.05); box-shadow:0 0 0 3px rgba(99,102,241,0.1); }
    .filter-select { padding:11px 36px 11px 14px; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:12px; color:#94a3b8; font-size:14px; font-family:var(--font); outline:none; cursor:pointer; transition:all 0.2s; appearance:none; -webkit-appearance:none; background-image:url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1L6 7L11 1' stroke='%2364748b' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 12px center; min-width:160px; }
    .filter-select:focus { border-color:rgba(99,102,241,0.5); }
    .glass-card { background:rgba(255,255,255,0.06); backdrop-filter:blur(20px); -webkit-backdrop-filter:blur(20px); border:1px solid rgba(255,255,255,0.12); border-radius:20px; overflow:hidden; }
    .table-wrapper { overflow-x:auto; -webkit-overflow-scrolling:touch; }
    .glass-table { width:100%; border-collapse:collapse; min-width:700px; }
    .glass-table th { padding:13px 16px; font-size:11px; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:0.8px; text-align:left; border-bottom:1px solid rgba(255,255,255,0.06); background:rgba(255,255,255,0.02); white-space:nowrap; }
    .glass-table td { padding:14px 16px; font-size:14px; color:#cbd5e1; border-bottom:1px solid rgba(255,255,255,0.04); vertical-align:middle; }
    .glass-table tr:last-child td { border-bottom:none; }
    .glass-table tr:hover td { background:rgba(255,255,255,0.03); }
    .emp-cell { display:flex; align-items:center; gap:12px; }
    .emp-avatar { width:38px; height:38px; border-radius:11px; display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:700; color:white; flex-shrink:0; }
    .emp-name { font-size:14px; font-weight:600; color:#f1f5f9; line-height:1.3; }
    .emp-email { font-size:12px; color:#64748b; }
    .badge { display:inline-flex; align-items:center; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600; }
    .badge-indigo { background:rgba(99,102,241,0.15); color:#818cf8; border:1px solid rgba(99,102,241,0.25); }
    .badge-green { background:rgba(16,185,129,0.12); color:#34d399; border:1px solid rgba(16,185,129,0.25); }
    .badge-yellow { background:rgba(245,158,11,0.12); color:#fbbf24; border:1px solid rgba(245,158,11,0.25); }
    .badge-blue { background:rgba(59,130,246,0.12); color:#60a5fa; border:1px solid rgba(59,130,246,0.25); }
    .badge-gray { background:rgba(100,116,139,0.15); color:#94a3b8; border:1px solid rgba(100,116,139,0.25); }
    .badge-red { background:rgba(239,68,68,0.12); color:#f87171; border:1px solid rgba(239,68,68,0.25); }
    .badge-orange { background:rgba(249,115,22,0.12); color:#fb923c; border:1px solid rgba(249,115,22,0.25); }
    .role-flags { display:flex; gap:4px; flex-wrap:wrap; }
    .role-dot { width:24px; height:24px; border-radius:6px; display:inline-flex; align-items:center; justify-content:center; font-size:10px; font-weight:700; }
    .role-req { background:rgba(99,102,241,0.2); color:#818cf8; border:1px solid rgba(99,102,241,0.3); }
    .role-comp { background:rgba(6,182,212,0.15); color:#22d3ee; border:1px solid rgba(6,182,212,0.3); }
    .actions-cell { display:flex; gap:6px; white-space:nowrap; }
    .icon-btn { display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid; cursor:pointer; transition:all 0.2s; text-decoration:none; background:none; font-family:var(--font); }
    .icon-btn-edit { border-color:rgba(245,158,11,0.25); color:#fbbf24; }
    .icon-btn-edit:hover { background:rgba(245,158,11,0.12); }
    .icon-btn-del { border-color:rgba(239,68,68,0.25); color:#f87171; }
    .icon-btn-del:hover { background:rgba(239,68,68,0.12); }
    .table-footer { display:flex; align-items:center; justify-content:space-between; padding:16px 20px; border-top:1px solid rgba(255,255,255,0.06); flex-wrap:wrap; gap:10px; }
    .table-count { font-size:13px; color:#64748b; }
    .pagination { display:flex; gap:6px; align-items:center; }
    .page-btn { display:inline-flex; align-items:center; justify-content:center; min-width:34px; height:34px; padding:0 6px; border-radius:9px; font-size:13px; font-weight:600; text-decoration:none; border:1px solid rgba(255,255,255,0.1); color:#94a3b8; background:rgba(255,255,255,0.04); transition:all 0.2s; }
    .page-btn:hover { background:rgba(255,255,255,0.09); color:#f1f5f9; }
    .page-btn.active { background:rgba(99,102,241,0.2); border-color:rgba(99,102,241,0.4); color:#818cf8; }
    .empty-state { padding:60px 20px; text-align:center; color:#475569; }
    .empty-state svg { margin:0 auto 16px; display:block; opacity:0.3; }
    .empty-state p { font-size:15px; color:#64748b; }
    .empty-state small { font-size:13px; color:#475569; }
    .status-cell { display:flex; flex-direction:column; gap:4px; }
    @media (max-width:640px) {
        .page-header-title { font-size:20px; }
        .filters-bar { flex-direction:column; }
        .filter-select { min-width:0; width:100%; }
    }
</style>

<div class="page-header">
    <div class="page-header-left">
        <h2 class="page-header-title">Empleados</h2>
        <span class="page-header-sub"><?php echo number_format($total); ?> empleados registrados</span>
    </div>
    <a href="<?php echo BASE_URL; ?>/empleados/crear" class="btn btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Nuevo Empleado
    </a>
</div>

<!-- Filters -->
<form method="GET" action="<?php echo BASE_URL; ?>/empleados" id="filterForm">
    <div class="filters-bar">
        <div class="search-wrapper">
            <span class="search-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </span>
            <input type="text" name="q" class="search-input"
                placeholder="Buscar por nombre, email, puesto..."
                value="<?php echo htmlspecialchars($filters['q'] ?? ''); ?>"
                oninput="debounceSubmit()">
        </div>
        <select name="empresa" class="filter-select" onchange="this.form.submit()">
            <option value="">Todas las empresas</option>
            <?php foreach ($empresas as $emp): ?>
            <option value="<?php echo htmlspecialchars($emp['id']); ?>" <?php echo ($filters['empresa'] ?? '') == $emp['id'] ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($emp['nombre']); ?>
            </option>
            <?php endforeach; ?>
        </select>
        <select name="activo" class="filter-select" onchange="this.form.submit()">
            <option value="">Estado: Todos</option>
            <option value="1" <?php echo ($filters['activo'] ?? '') === '1' ? 'selected' : ''; ?>>Activos</option>
            <option value="0" <?php echo ($filters['activo'] ?? '') === '0' ? 'selected' : ''; ?>>Inactivos</option>
        </select>
        <select name="aprobado" class="filter-select" onchange="this.form.submit()">
            <option value="">Aprobación: Todos</option>
            <option value="1" <?php echo ($filters['aprobado'] ?? '') === '1' ? 'selected' : ''; ?>>Aprobados</option>
            <option value="0" <?php echo ($filters['aprobado'] ?? '') === '0' ? 'selected' : ''; ?>>Pendientes</option>
        </select>
    </div>
</form>

<!-- Table -->
<div class="glass-card">
    <div class="table-wrapper">
        <table class="glass-table">
            <thead>
                <tr>
                    <th>Empleado</th>
                    <th>Empresa</th>
                    <th>Puesto</th>
                    <th>Programa Nivel</th>
                    <th>Roles</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($empleados)): ?>
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                            <p>No se encontraron empleados</p>
                            <small>Prueba con otros filtros o agrega un nuevo empleado</small>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($empleados as $emp): ?>
                <?php
                    $nombre = trim(($emp['nombre'] ?? '') . ' ' . ($emp['apellido_paterno'] ?? '') . ' ' . ($emp['apellido_materno'] ?? ''));
                    $ini = strtoupper(substr($emp['nombre'] ?? 'U', 0, 1) . substr($emp['apellido_paterno'] ?? '', 0, 1));
                    $gradients = ['135deg,#6366f1,#8b5cf6','135deg,#06b6d4,#6366f1','135deg,#10b981,#06b6d4','135deg,#f59e0b,#ef4444'];
                    $grad = $gradients[crc32((string)($emp['id'] ?? 0)) % 4];
                    $aprobado = (int)($emp['aprobado'] ?? 1);
                    $activo   = (int)($emp['activo'] ?? 1);
                ?>
                <tr>
                    <td>
                        <div class="emp-cell">
                            <div class="emp-avatar" style="background:linear-gradient(<?php echo $grad; ?>);"><?php echo htmlspecialchars($ini); ?></div>
                            <div>
                                <div class="emp-name"><?php echo htmlspecialchars($nombre); ?></div>
                                <div class="emp-email"><?php echo htmlspecialchars($emp['email'] ?? ''); ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <?php if (!empty($emp['empresa_nombre'])): ?>
                        <span class="badge badge-blue"><?php echo htmlspecialchars($emp['empresa_nombre']); ?></span>
                        <?php else: ?><span style="color:#475569; font-size:12px;">—</span><?php endif; ?>
                    </td>
                    <td style="color:#94a3b8; font-size:13px; max-width:180px;">
                        <?php echo htmlspecialchars($emp['puesto'] ?? '—'); ?>
                    </td>
                    <td>
                        <?php if (!empty($emp['programa_nivel_nombre'])): ?>
                        <span class="badge badge-indigo"><?php echo htmlspecialchars($emp['programa_nivel_nombre']); ?></span>
                        <?php else: ?><span style="color:#475569; font-size:12px;">—</span><?php endif; ?>
                    </td>
                    <td>
                        <div class="role-flags">
                            <?php if (!empty($emp['es_requisitor'])): ?>
                            <span class="role-dot role-req" title="Requisitor">R</span>
                            <?php endif; ?>
                            <?php if (!empty($emp['es_comprador'])): ?>
                            <span class="role-dot role-comp" title="Comprador">C</span>
                            <?php endif; ?>
                            <?php if (empty($emp['es_requisitor']) && empty($emp['es_comprador'])): ?>
                            <span style="color:#475569; font-size:12px;">—</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <div class="status-cell">
                            <?php if (!$aprobado): ?>
                            <span class="badge badge-yellow">⏳ Pendiente</span>
                            <?php elseif ($activo): ?>
                            <span class="badge badge-green">● Activo</span>
                            <?php else: ?>
                            <span class="badge badge-gray">● Inactivo</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <div class="actions-cell">
                            <a href="<?php echo BASE_URL; ?>/empleados/<?php echo (int)$emp['id']; ?>/editar" class="icon-btn icon-btn-edit" title="Editar">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                            <?php if (isRole(['admin','superadmin'])): ?>
                            <form method="POST" action="<?php echo BASE_URL; ?>/empleados/<?php echo (int)$emp['id']; ?>/eliminar" style="display:inline;" onsubmit="return confirm('¿Eliminar a <?php echo htmlspecialchars(addslashes($nombre)); ?>?')">
                                <?php echo csrfField(); ?>
                                <button type="submit" class="icon-btn icon-btn-del" title="Eliminar">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if (!empty($empleados)): ?>
    <div class="table-footer">
        <span class="table-count">
            Mostrando <?php echo count($empleados); ?> de <?php echo number_format($total); ?> empleados
        </span>
        <?php if ($totalPag > 1): ?>
        <div class="pagination">
            <?php if ($pagina > 1): ?>
            <a href="?<?php echo http_build_query(array_merge($filters, ['pagina' => $pagina - 1])); ?>" class="page-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            </a>
            <?php endif; ?>
            <?php for ($p = max(1, $pagina - 2); $p <= min($totalPag, $pagina + 2); $p++): ?>
            <a href="?<?php echo http_build_query(array_merge($filters, ['pagina' => $p])); ?>" class="page-btn <?php echo $p == $pagina ? 'active' : ''; ?>"><?php echo $p; ?></a>
            <?php endfor; ?>
            <?php if ($pagina < $totalPag): ?>
            <a href="?<?php echo http_build_query(array_merge($filters, ['pagina' => $pagina + 1])); ?>" class="page-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<script>
var debounceTimer;
function debounceSubmit() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(function() { document.getElementById('filterForm').submit(); }, 400);
}
</script>
<?php
$content = ob_get_clean();
include VIEWS_PATH . 'layouts/app.php';
?>
