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
    .page-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 24px; flex-wrap: wrap; gap: 12px;
    }
    .page-header-left { display: flex; flex-direction: column; gap: 4px; }
    .page-header-title { font-size: 26px; font-weight: 800; color: var(--text-primary); letter-spacing: -0.5px; }
    .page-header-sub { font-size: 13px; color: var(--text-secondary); }
    .filters-bar { display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }
    .search-wrapper { flex: 1; min-width: 240px; position: relative; }
    .search-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-tertiary); pointer-events: none; }
    .search-input {
        width: 100%; padding: 11px 16px 11px 44px;
        background: rgba(255,255,255,0.05);
        border: 1px solid var(--glass-border);
        border-radius: 12px;
        color: var(--text-primary);
        font-size: 13.5px;
        font-family: inherit;
        outline: none;
        transition: all var(--dur);
    }
    .search-input::placeholder { color: var(--text-tertiary); }
    .search-input:focus { border-color: rgba(79,142,247,0.5); background: rgba(79,142,247,0.05); box-shadow: 0 0 0 3px rgba(79,142,247,0.1); }
    .filter-select {
        padding: 11px 36px 11px 14px;
        background: rgba(255,255,255,0.05);
        border: 1px solid var(--glass-border);
        border-radius: 12px;
        color: var(--text-secondary);
        font-size: 13.5px;
        font-family: inherit;
        outline: none;
        cursor: pointer;
        transition: all var(--dur);
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1L6 7L11 1' stroke='%2364748b' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        min-width: 160px;
    }
    .filter-select option { background: #1a1d27; }
    .filter-select:focus { border-color: rgba(79,142,247,0.5); }
    .emp-cell { display: flex; align-items: center; gap: 12px; }
    .emp-avatar { width: 38px; height: 38px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; color: #fff; flex-shrink: 0; }
    .emp-name { font-size: 14px; font-weight: 600; color: var(--text-primary); line-height: 1.3; }
    .emp-email { font-size: 12px; color: var(--text-secondary); }
    .role-badges { display: flex; gap: 4px; flex-wrap: wrap; }
    .role-dot { width: 22px; height: 22px; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 700; }
    .role-elab { background: rgba(79,142,247,0.2); color: var(--blue-light); border: 1px solid rgba(79,142,247,0.3); }
    .role-vobo { background: rgba(62,207,142,0.15); color: var(--teal); border: 1px solid rgba(62,207,142,0.3); }
    .role-aut  { background: rgba(245,200,66,0.15); color: var(--yellow); border: 1px solid rgba(245,200,66,0.3); }
    .actions-cell { display: flex; gap: 6px; white-space: nowrap; }
    .icon-btn { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; border: 1px solid; cursor: pointer; transition: all var(--dur); text-decoration: none; background: none; font-family: inherit; }
    .icon-btn-view { border-color: rgba(79,142,247,0.25); color: var(--blue-light); }
    .icon-btn-view:hover { background: rgba(79,142,247,0.15); }
    .icon-btn-edit { border-color: rgba(245,200,66,0.25); color: var(--yellow); }
    .icon-btn-edit:hover { background: rgba(245,200,66,0.12); }
    .icon-btn-del { border-color: rgba(255,107,107,0.25); color: var(--red); }
    .icon-btn-del:hover { background: rgba(255,107,107,0.12); }
    .table-footer { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; border-top: 1px solid var(--glass-border); flex-wrap: wrap; gap: 10px; }
    .table-count { font-size: 13px; color: var(--text-secondary); }
    .pagination { display: flex; gap: 6px; align-items: center; }
    .page-btn { display: inline-flex; align-items: center; justify-content: center; min-width: 34px; height: 34px; padding: 0 6px; border-radius: 9px; font-size: 13px; font-weight: 600; text-decoration: none; border: 1px solid var(--glass-border); color: var(--text-secondary); background: rgba(255,255,255,0.04); transition: all var(--dur); }
    .page-btn:hover { background: rgba(255,255,255,0.09); color: var(--text-primary); }
    .page-btn.active { background: rgba(79,142,247,0.2); border-color: rgba(79,142,247,0.4); color: var(--blue-light); }
    .page-btn:disabled, .page-btn[disabled] { opacity: 0.4; pointer-events: none; }
    .empty-state svg { margin: 0 auto 16px; display: block; opacity: 0.3; }
    .empty-state p { font-size: 14px; color: var(--text-secondary); }
    .empty-state small { font-size: 12px; color: var(--text-tertiary); }
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
            <input
                type="text"
                name="q"
                class="search-input"
                placeholder="Buscar por nombre, email, empresa..."
                value="<?php echo htmlspecialchars($filters['q'] ?? ''); ?>"
                oninput="debounceSubmit()"
            >
        </div>
        <select name="empresa" class="filter-select" onchange="this.form.submit()">
            <option value="">Todas las empresas</option>
            <?php foreach ($empresas as $emp): ?>
            <option value="<?php echo htmlspecialchars($emp['id']); ?>" <?php echo ($filters['empresa'] ?? '') == $emp['id'] ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($emp['nombre']); ?>
            </option>
            <?php endforeach; ?>
        </select>
        <select name="tipo" class="filter-select" onchange="this.form.submit()">
            <option value="">Todos los tipos</option>
            <option value="interno"  <?php echo ($filters['tipo'] ?? '') === 'interno'  ? 'selected' : ''; ?>>Interno</option>
            <option value="externo"  <?php echo ($filters['tipo'] ?? '') === 'externo'  ? 'selected' : ''; ?>>Externo</option>
        </select>
    </div>
</form>

<!-- Table -->
<div class="glass table-container">
    <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th>Empleado</th>
                    <th>Empresa</th>
                    <th>Puesto</th>
                    <th>Programa Nivel</th>
                    <th>Tipo</th>
                    <th>Elab / VoBo / Aut</th>
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
                    $gradients = ['135deg, #4f8ef7, #7c5cbf', '135deg, #06b6d4, #4f8ef7', '135deg, #3ecf8e, #06b6d4', '135deg, #f58642, #ff6b6b'];
                    $grad = $gradients[crc32($emp['id'] ?? 0) % 4];
                ?>
                <tr>
                    <td>
                        <div class="emp-cell">
                            <div class="emp-avatar" style="background: linear-gradient(<?php echo $grad; ?>);"><?php echo $ini; ?></div>
                            <div>
                                <div class="emp-name"><?php echo htmlspecialchars($nombre); ?></div>
                                <div class="emp-email"><?php echo htmlspecialchars($emp['email'] ?? ''); ?></div>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge badge-blue"><?php echo htmlspecialchars($emp['empresa'] ?? '-'); ?></span></td>
                    <td class="td-muted"><?php echo htmlspecialchars($emp['puesto'] ?? '-'); ?></td>
                    <td>
                        <?php if (!empty($emp['programa_nivel'])): ?>
                        <span class="badge badge-purple"><?php echo htmlspecialchars($emp['programa_nivel']); ?></span>
                        <?php else: ?>
                        <span class="td-muted">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge <?php echo ($emp['tipo'] ?? '') === 'interno' ? 'badge-teal' : 'badge-yellow'; ?>">
                            <?php echo ucfirst(htmlspecialchars($emp['tipo'] ?? '-')); ?>
                        </span>
                    </td>
                    <td>
                        <div class="role-badges">
                            <?php if (!empty($emp['es_elaborador'])): ?><span class="role-dot role-elab" title="Elaborador">E</span><?php endif; ?>
                            <?php if (!empty($emp['es_vobo'])): ?><span class="role-dot role-vobo" title="VoBo">V</span><?php endif; ?>
                            <?php if (!empty($emp['es_autorizador'])): ?><span class="role-dot role-aut" title="Autorizador">A</span><?php endif; ?>
                            <?php if (empty($emp['es_elaborador']) && empty($emp['es_vobo']) && empty($emp['es_autorizador'])): ?>
                            <span class="td-muted">—</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <div class="actions-cell">
                            <a href="<?php echo BASE_URL; ?>/empleados/<?php echo $emp['id']; ?>" class="icon-btn icon-btn-view" title="Ver detalle">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </a>
                            <a href="<?php echo BASE_URL; ?>/empleados/<?php echo $emp['id']; ?>/editar" class="icon-btn icon-btn-edit" title="Editar">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                            <form method="POST" action="<?php echo BASE_URL; ?>/empleados/<?php echo $emp['id']; ?>/eliminar" style="display:inline;" onsubmit="return confirm('¿Eliminar a <?php echo htmlspecialchars(addslashes($nombre)); ?>?')">
                                <?php echo csrfField(); ?>
                                <button type="submit" class="icon-btn icon-btn-del" title="Eliminar">
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
            <a href="?<?php echo http_build_query(array_merge($filters, ['pagina' => $p])); ?>" class="page-btn <?php echo $p == $pagina ? 'active' : ''; ?>">
                <?php echo $p; ?>
            </a>
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
    debounceTimer = setTimeout(function() {
        document.getElementById('filterForm').submit();
    }, 400);
}
</script>
<?php
$content = ob_get_clean();
include VIEWS_PATH . 'layouts/app.php';
?>
