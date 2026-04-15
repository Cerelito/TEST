<?php
$title = 'Usuarios del Sistema';
ob_start();
?>
<style>
    .page-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
    .page-header-title { font-size:26px; font-weight:800; color:#f1f5f9; letter-spacing:-0.5px; }
    .kpi-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:16px; margin-bottom:24px; }
    .kpi-card { background:rgba(255,255,255,0.06); backdrop-filter:blur(20px); -webkit-backdrop-filter:blur(20px); border:1px solid rgba(255,255,255,0.12); border-radius:18px; padding:20px; position:relative; overflow:hidden; transition:all 0.25s; }
    .kpi-card:hover { background:rgba(255,255,255,0.09); transform:translateY(-2px); box-shadow:0 12px 32px rgba(0,0,0,0.25); }
    .kpi-card::before { content:''; position:absolute; top:0; right:0; width:70px; height:70px; border-radius:50%; background:var(--kpi-c,rgba(99,102,241,0.12)); transform:translate(25px,-25px); }
    .kpi-icon { width:38px; height:38px; border-radius:10px; background:var(--kpi-ib,rgba(99,102,241,0.15)); border:1px solid var(--kpi-ibrd,rgba(99,102,241,0.25)); display:flex; align-items:center; justify-content:center; color:var(--kpi-ic,#818cf8); margin-bottom:14px; }
    .kpi-value { font-size:32px; font-weight:800; color:#f1f5f9; line-height:1; margin-bottom:4px; letter-spacing:-1px; }
    .kpi-label { font-size:12px; color:#64748b; font-weight:500; }
    .kpi-warn { display:inline-flex; align-items:center; gap:4px; font-size:10px; font-weight:600; padding:2px 7px; border-radius:20px; margin-top:6px; background:rgba(245,158,11,0.15); color:#fbbf24; border:1px solid rgba(245,158,11,0.25); animation:pulse 2s infinite; }
    @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.6} }

    .filters-row { display:flex; align-items:center; gap:10px; margin-bottom:20px; flex-wrap:wrap; }
    .filter-btn { padding:8px 16px; border-radius:10px; font-size:13px; font-weight:600; cursor:pointer; text-decoration:none; border:1px solid; transition:all 0.2s; display:inline-flex; align-items:center; gap:6px; font-family:var(--font); }
    .filter-btn-active { background:rgba(99,102,241,0.2); border-color:rgba(99,102,241,0.4); color:#818cf8; }
    .filter-btn-glass { background:rgba(255,255,255,0.05); border-color:rgba(255,255,255,0.1); color:#94a3b8; }
    .filter-btn-glass:hover { background:rgba(255,255,255,0.09); color:#f1f5f9; }
    .search-wrap { margin-left:auto; position:relative; }
    .search-icon { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#475569; pointer-events:none; }
    .search-input { padding:9px 14px 9px 38px; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:10px; color:#f1f5f9; font-size:13px; font-family:var(--font); outline:none; width:220px; transition:all 0.2s; }
    .search-input:focus { border-color:rgba(99,102,241,0.5); background:rgba(99,102,241,0.05); }
    .search-input::placeholder { color:#475569; }

    .glass-card { background:rgba(255,255,255,0.06); backdrop-filter:blur(20px); -webkit-backdrop-filter:blur(20px); border:1px solid rgba(255,255,255,0.12); border-radius:20px; overflow:hidden; }
    .table-scroll { overflow-x:auto; -webkit-overflow-scrolling:touch; }
    .usr-table { width:100%; border-collapse:collapse; min-width:680px; }
    .usr-table th { padding:12px 16px; font-size:11px; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:0.8px; text-align:left; border-bottom:1px solid rgba(255,255,255,0.06); background:rgba(255,255,255,0.02); white-space:nowrap; }
    .usr-table td { padding:14px 16px; font-size:14px; color:#cbd5e1; border-bottom:1px solid rgba(255,255,255,0.04); vertical-align:middle; }
    .usr-table tr:last-child td { border-bottom:none; }
    .usr-table tr:hover td { background:rgba(255,255,255,0.025); }

    .user-avatar { width:36px; height:36px; border-radius:10px; background:linear-gradient(135deg,#6366f1,#8b5cf6); display:inline-flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; color:white; flex-shrink:0; }
    .fw-bold { font-weight:600; color:#f1f5f9; font-size:14px; }
    .td-muted { font-size:12px; color:#64748b; }
    .badge { display:inline-flex; align-items:center; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600; }
    .badge-purple { background:rgba(139,92,246,0.15); color:#a78bfa; border:1px solid rgba(139,92,246,0.25); }
    .badge-blue { background:rgba(59,130,246,0.12); color:#60a5fa; border:1px solid rgba(59,130,246,0.25); }
    .badge-teal { background:rgba(16,185,129,0.12); color:#34d399; border:1px solid rgba(16,185,129,0.25); }
    .badge-gray { background:rgba(100,116,139,0.15); color:#94a3b8; border:1px solid rgba(100,116,139,0.25); }
    .badge-yellow { background:rgba(245,158,11,0.12); color:#fbbf24; border:1px solid rgba(245,158,11,0.25); }
    .badge-red { background:rgba(239,68,68,0.12); color:#f87171; border:1px solid rgba(239,68,68,0.25); }
    .badge-indigo { background:rgba(99,102,241,0.15); color:#818cf8; border:1px solid rgba(99,102,241,0.25); }
    .d-flex { display:flex; }
    .gap-2 { gap:8px; }
    .align-center { align-items:center; }
    .btn-xs { padding:5px 12px; font-size:11px; border-radius:8px; font-weight:600; border:1px solid; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:4px; font-family:var(--font); transition:all 0.2s; }
    .btn-glass-xs { background:rgba(255,255,255,0.06); border-color:rgba(255,255,255,0.13); color:#94a3b8; }
    .btn-glass-xs:hover { background:rgba(255,255,255,0.1); color:#f1f5f9; }
    .btn-success-xs { background:rgba(16,185,129,0.15); border-color:rgba(16,185,129,0.3); color:#34d399; }
    .btn-success-xs:hover { background:rgba(16,185,129,0.25); }
    .btn-danger-xs { background:rgba(239,68,68,0.1); border-color:rgba(239,68,68,0.2); color:#f87171; }
    .btn-danger-xs:hover { background:rgba(239,68,68,0.2); }
    .btn-indigo-xs { background:rgba(99,102,241,0.12); border-color:rgba(99,102,241,0.25); color:#818cf8; }
    .btn-indigo-xs:hover { background:rgba(99,102,241,0.2); }
    .empty-state { padding:60px 20px; text-align:center; }
    .empty-icon { font-size:40px; margin-bottom:8px; opacity:0.4; }
    .empty-title { font-size:15px; font-weight:600; color:#64748b; margin-bottom:4px; }
    .empty-text { font-size:13px; color:#475569; }
    .nav-badge { background:rgba(245,158,11,0.25); color:#fbbf24; border-radius:20px; padding:1px 7px; font-size:10px; font-weight:700; }
    @media (max-width:640px) {
        .search-wrap { margin-left:0; width:100%; }
        .search-input { width:100%; }
        .filters-row { flex-direction:column; align-items:flex-start; }
    }
</style>

<div class="page-header">
    <div>
        <h2 class="page-header-title">Usuarios del Sistema</h2>
        <span style="font-size:13px; color:#64748b;"><?php echo (int)($stats['total'] ?? 0); ?> usuarios registrados</span>
    </div>
    <a href="<?php echo BASE_URL ?>/usuarios/crear" class="btn btn-primary">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Nuevo Usuario
    </a>
</div>

<!-- KPIs -->
<div class="kpi-grid">
    <div class="kpi-card" style="--kpi-c:rgba(99,102,241,0.12);--kpi-ib:rgba(99,102,241,0.15);--kpi-ibrd:rgba(99,102,241,0.25);--kpi-ic:#818cf8;">
        <div class="kpi-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
        <div class="kpi-value"><?= (int)($stats['total'] ?? 0) ?></div>
        <div class="kpi-label">Total Usuarios</div>
    </div>
    <div class="kpi-card" style="--kpi-c:rgba(16,185,129,0.1);--kpi-ib:rgba(16,185,129,0.12);--kpi-ibrd:rgba(16,185,129,0.25);--kpi-ic:#34d399;">
        <div class="kpi-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></div>
        <div class="kpi-value"><?= (int)($stats['activos'] ?? 0) ?></div>
        <div class="kpi-label">Activos</div>
    </div>
    <div class="kpi-card" style="--kpi-c:rgba(245,158,11,0.1);--kpi-ib:rgba(245,158,11,0.12);--kpi-ibrd:rgba(245,158,11,0.25);--kpi-ic:#fbbf24;">
        <div class="kpi-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></div>
        <div class="kpi-value"><?= (int)($stats['pendientes'] ?? 0) ?></div>
        <div class="kpi-label">Pendientes Aprobación</div>
        <?php if(($stats['pendientes']??0) > 0): ?>
        <span class="kpi-warn">⚠ Requieren revisión</span>
        <?php endif; ?>
    </div>
    <div class="kpi-card" style="--kpi-c:rgba(139,92,246,0.1);--kpi-ib:rgba(139,92,246,0.12);--kpi-ibrd:rgba(139,92,246,0.25);--kpi-ic:#a78bfa;">
        <div class="kpi-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>
        <div class="kpi-value"><?= (int)($stats['admins'] ?? 0) ?></div>
        <div class="kpi-label">Administradores</div>
    </div>
</div>

<!-- Filters -->
<div class="filters-row">
    <a href="<?= BASE_URL ?>/usuarios" class="filter-btn <?= empty($_GET['filter']) ? 'filter-btn-active' : 'filter-btn-glass' ?>">Todos</a>
    <a href="<?= BASE_URL ?>/usuarios?filter=pendiente" class="filter-btn <?= ($_GET['filter']??'')==='pendiente' ? 'filter-btn-active' : 'filter-btn-glass' ?>">
        Pendientes
        <?php if(($stats['pendientes']??0) > 0): ?><span class="nav-badge"><?= $stats['pendientes'] ?></span><?php endif; ?>
    </a>
    <a href="<?= BASE_URL ?>/usuarios?filter=activo" class="filter-btn <?= ($_GET['filter']??'')==='activo' ? 'filter-btn-active' : 'filter-btn-glass' ?>">Activos</a>
    <div class="search-wrap">
        <span class="search-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></span>
        <input type="text" class="search-input" placeholder="Buscar usuario..." data-search-table="tbl-usuarios">
    </div>
</div>

<!-- Table -->
<div class="glass-card">
    <div class="table-scroll">
        <table id="tbl-usuarios" class="usr-table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Credenciales</th>
                    <th>Rol</th>
                    <th>Programa Nivel</th>
                    <th>Estado</th>
                    <th>Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios ?? [] as $u): ?>
                <tr>
                    <td>
                        <div class="d-flex align-center gap-2">
                            <div class="user-avatar" style="flex-shrink:0;">
                                <?= strtoupper(substr($u['nombre'],0,1).substr($u['apellido']??'',0,1)) ?>
                            </div>
                            <div>
                                <div class="fw-bold"><?= htmlspecialchars($u['nombre'].' '.($u['apellido']??'')) ?></div>
                                <?php if(!empty($u['puesto'])): ?><div class="td-muted"><?= htmlspecialchars($u['puesto']) ?></div><?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="fw-bold" style="font-size:13px;"><?= htmlspecialchars($u['username']) ?></div>
                        <div class="td-muted"><?= htmlspecialchars($u['email']) ?></div>
                    </td>
                    <td>
                        <?php $rolColors = ['superadmin'=>'badge-purple','admin'=>'badge-blue','capturista'=>'badge-teal','usuario'=>'badge-gray']; ?>
                        <span class="badge <?= $rolColors[$u['rol']] ?? 'badge-gray' ?>"><?= ucfirst(htmlspecialchars($u['rol'])) ?></span>
                    </td>
                    <td>
                        <?php if(!empty($u['programa_nivel_nombre'])): ?>
                        <span class="badge badge-indigo">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <?= htmlspecialchars($u['programa_nivel_nombre']) ?>
                        </span>
                        <?php else: ?>
                        <a href="<?= BASE_URL ?>/usuarios/editar/<?= $u['id'] ?>" class="badge badge-gray" style="text-decoration:none;" title="Asignar perfil">
                            + Asignar perfil
                        </a>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if(!$u['aprobado']): ?>
                          <span class="badge badge-yellow">⏳ Pendiente</span>
                        <?php elseif($u['activo']): ?>
                          <span class="badge badge-teal">● Activo</span>
                        <?php else: ?>
                          <span class="badge badge-red">● Inactivo</span>
                        <?php endif; ?>
                    </td>
                    <td class="td-muted"><?= htmlspecialchars(substr($u['created_at']??'',0,10)) ?></td>
                    <td>
                        <div class="d-flex gap-2">
                            <?php if(!$u['aprobado'] && isRole(['admin','superadmin'])): ?>
                            <form method="POST" action="<?= BASE_URL ?>/usuarios/aprobar/<?= $u['id'] ?>" style="display:inline;">
                                <?= csrfField() ?>
                                <button type="submit" class="btn-xs btn-success-xs" data-confirm="¿Aprobar acceso de <?= htmlspecialchars($u['nombre']) ?>?">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                    Aprobar
                                </button>
                            </form>
                            <?php endif; ?>
                            <a href="<?= BASE_URL ?>/usuarios/editar/<?= $u['id'] ?>" class="btn-xs btn-glass-xs">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Editar
                            </a>
                            <?php if($u['id'] != currentUserId()): ?>
                            <form method="POST" action="<?= BASE_URL ?>/usuarios/toggle/<?= $u['id'] ?>" style="display:inline;">
                                <?= csrfField() ?>
                                <button type="submit" class="btn-xs btn-danger-xs" data-confirm="¿Cambiar estado de este usuario?">
                                    <?= $u['activo'] ? 'Desactivar' : 'Activar' ?>
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($usuarios)): ?>
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <div class="empty-icon">👥</div>
                            <div class="empty-title">Sin usuarios registrados</div>
                            <p class="empty-text">Crea el primer usuario del sistema.</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Simple client-side search
(function() {
    var inp = document.querySelector('[data-search-table="tbl-usuarios"]');
    if (!inp) return;
    inp.addEventListener('input', function() {
        var q = this.value.trim().toLowerCase();
        var rows = document.querySelectorAll('#tbl-usuarios tbody tr');
        rows.forEach(function(row) {
            row.style.display = !q || row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
    // Confirm buttons
    document.querySelectorAll('[data-confirm]').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            if (!confirm(this.getAttribute('data-confirm'))) e.preventDefault();
        });
    });
})();
</script>
<?php
$content = ob_get_clean();
include VIEWS_PATH . 'layouts/app.php';
?>
