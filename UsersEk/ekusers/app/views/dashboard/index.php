<?php
$title = 'Dashboard';
ob_start();

$stats = $stats ?? [
    'total_empleados'     => 0,
    'total_requisitores'  => 0,
    'total_compradores'   => 0,
    'centros_costo'       => 0,
    'programas_nivel'     => 0,
    'usuarios_pendientes' => 0,
];
$recientes = $recientes ?? [];
$ccDist    = $ccDist    ?? [];
?>
<style>
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 28px;
    }
    .kpi-card {
        background: rgba(255,255,255,0.06);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 20px;
        padding: 22px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s;
    }
    .kpi-card:hover {
        background: rgba(255,255,255,0.09);
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.3);
    }
    .kpi-card::before {
        content: '';
        position: absolute;
        top: 0; right: 0;
        width: 80px; height: 80px;
        border-radius: 50%;
        background: var(--kpi-color, rgba(99,102,241,0.12));
        transform: translate(30px, -30px);
    }
    .kpi-icon {
        width: 42px; height: 42px;
        border-radius: 12px;
        background: var(--kpi-icon-bg, rgba(99,102,241,0.15));
        border: 1px solid var(--kpi-icon-border, rgba(99,102,241,0.25));
        display: flex; align-items: center; justify-content: center;
        color: var(--kpi-icon-color, #818cf8);
        margin-bottom: 16px;
    }
    .kpi-value {
        font-size: 36px;
        font-weight: 800;
        color: #f1f5f9;
        line-height: 1;
        margin-bottom: 4px;
        letter-spacing: -1px;
    }
    .kpi-label {
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
    }
    .kpi-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 20px;
        margin-top: 8px;
    }
    .kpi-badge.danger {
        background: rgba(239,68,68,0.15);
        color: #fca5a5;
        border: 1px solid rgba(239,68,68,0.25);
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
    }
    .section-row {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-bottom: 28px;
    }
    .glass-card {
        background: rgba(255,255,255,0.06);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 20px;
        overflow: hidden;
    }
    .card-header {
        padding: 20px 24px;
        border-bottom: 1px solid rgba(255,255,255,0.06);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .card-title {
        font-size: 16px;
        font-weight: 700;
        color: #f1f5f9;
    }
    .card-subtitle {
        font-size: 12px;
        color: #64748b;
        margin-top: 2px;
    }
    .card-link {
        font-size: 12px;
        color: #818cf8;
        text-decoration: none;
        font-weight: 500;
    }
    .card-link:hover { color: #a5b4fc; }
    .table-wrapper { overflow-x: auto; }
    .glass-table { width: 100%; border-collapse: collapse; }
    .glass-table th {
        padding: 12px 16px;
        font-size: 11px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        text-align: left;
        border-bottom: 1px solid rgba(255,255,255,0.06);
        background: rgba(255,255,255,0.02);
    }
    .glass-table td {
        padding: 14px 16px;
        font-size: 14px;
        color: #cbd5e1;
        border-bottom: 1px solid rgba(255,255,255,0.04);
        vertical-align: middle;
    }
    .glass-table tr:last-child td { border-bottom: none; }
    .glass-table tr:hover td { background: rgba(255,255,255,0.03); }
    .emp-avatar {
        width: 34px; height: 34px;
        border-radius: 10px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        color: white;
        flex-shrink: 0;
    }
    .emp-info { display: flex; align-items: center; gap: 10px; }
    .emp-name { font-size: 14px; font-weight: 600; color: #f1f5f9; }
    .emp-email { font-size: 12px; color: #64748b; }
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.3px;
    }
    .badge-indigo { background: rgba(99,102,241,0.15); color: #818cf8; border: 1px solid rgba(99,102,241,0.25); }
    .badge-green  { background: rgba(16,185,129,0.12); color: #34d399; border: 1px solid rgba(16,185,129,0.25); }
    .badge-yellow { background: rgba(245,158,11,0.12); color: #fbbf24; border: 1px solid rgba(245,158,11,0.25); }
    .badge-red    { background: rgba(239,68,68,0.12);  color: #f87171; border: 1px solid rgba(239,68,68,0.25); }
    .badge-blue   { background: rgba(59,130,246,0.12); color: #60a5fa; border: 1px solid rgba(59,130,246,0.25); }
    .dist-item { padding: 16px 24px; border-bottom: 1px solid rgba(255,255,255,0.04); }
    .dist-item:last-child { border-bottom: none; }
    .dist-header { display: flex; justify-content: space-between; margin-bottom: 8px; }
    .dist-label { font-size: 13px; color: #94a3b8; font-weight: 500; }
    .dist-count { font-size: 13px; color: #f1f5f9; font-weight: 700; }
    .dist-bar-bg {
        height: 6px;
        background: rgba(255,255,255,0.06);
        border-radius: 3px;
        overflow: hidden;
    }
    .dist-bar { height: 100%; border-radius: 3px; transition: width 1s ease; }
    .quick-actions { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 28px; }
    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 20px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid;
        font-family: -apple-system, 'SF Pro Display', sans-serif;
    }
    .action-primary {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-color: transparent;
        color: white;
        box-shadow: 0 4px 15px rgba(99,102,241,0.35);
    }
    .action-primary:hover { transform: translateY(-1px); box-shadow: 0 8px 25px rgba(99,102,241,0.5); }
    .action-secondary {
        background: rgba(255,255,255,0.05);
        border-color: rgba(255,255,255,0.12);
        color: #94a3b8;
    }
    .action-secondary:hover { background: rgba(255,255,255,0.09); color: #f1f5f9; }
    @media (max-width: 900px) {
        .section-row { grid-template-columns: 1fr; }
    }
</style>

<!-- Quick Actions -->
<div class="quick-actions">
    <a href="<?php echo BASE_URL; ?>/empleados/crear" class="action-btn action-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Nuevo Empleado
    </a>
    <a href="<?php echo BASE_URL; ?>/requisitores" class="action-btn action-secondary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        Requisitores
    </a>
    <a href="<?php echo BASE_URL; ?>/compradores" class="action-btn action-secondary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
        Compradores
    </a>
    <a href="<?php echo BASE_URL; ?>/programa-nivel/crear" class="action-btn action-secondary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        Nuevo Nivel
    </a>
</div>

<!-- KPI Grid -->
<div class="kpi-grid">
    <div class="kpi-card" style="--kpi-color: rgba(99,102,241,0.12); --kpi-icon-bg: rgba(99,102,241,0.15); --kpi-icon-border: rgba(99,102,241,0.25); --kpi-icon-color: #818cf8;">
        <div class="kpi-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <div class="kpi-value"><?php echo number_format($stats['total_empleados']); ?></div>
        <div class="kpi-label">Total Empleados</div>
    </div>

    <div class="kpi-card" style="--kpi-color: rgba(16,185,129,0.1); --kpi-icon-bg: rgba(16,185,129,0.12); --kpi-icon-border: rgba(16,185,129,0.25); --kpi-icon-color: #34d399;">
        <div class="kpi-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/></svg>
        </div>
        <div class="kpi-value"><?php echo number_format($stats['total_requisitores']); ?></div>
        <div class="kpi-label">Requisitores</div>
    </div>

    <div class="kpi-card" style="--kpi-color: rgba(6,182,212,0.1); --kpi-icon-bg: rgba(6,182,212,0.12); --kpi-icon-border: rgba(6,182,212,0.25); --kpi-icon-color: #22d3ee;">
        <div class="kpi-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
        </div>
        <div class="kpi-value"><?php echo number_format($stats['total_compradores']); ?></div>
        <div class="kpi-label">Compradores</div>
    </div>

    <div class="kpi-card" style="--kpi-color: rgba(245,158,11,0.1); --kpi-icon-bg: rgba(245,158,11,0.12); --kpi-icon-border: rgba(245,158,11,0.25); --kpi-icon-color: #fbbf24;">
        <div class="kpi-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
        </div>
        <div class="kpi-value"><?php echo number_format($stats['centros_costo']); ?></div>
        <div class="kpi-label">Centros de Costo</div>
    </div>

    <div class="kpi-card" style="--kpi-color: rgba(139,92,246,0.1); --kpi-icon-bg: rgba(139,92,246,0.12); --kpi-icon-border: rgba(139,92,246,0.25); --kpi-icon-color: #a78bfa;">
        <div class="kpi-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        </div>
        <div class="kpi-value"><?php echo number_format($stats['programas_nivel']); ?></div>
        <div class="kpi-label">Programas Nivel</div>
    </div>

    <div class="kpi-card" style="--kpi-color: rgba(239,68,68,0.1); --kpi-icon-bg: rgba(239,68,68,0.12); --kpi-icon-border: rgba(239,68,68,0.25); --kpi-icon-color: #f87171;">
        <div class="kpi-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </div>
        <div class="kpi-value"><?php echo number_format($stats['usuarios_pendientes']); ?></div>
        <div class="kpi-label">Usuarios Pendientes</div>
        <?php if ($stats['usuarios_pendientes'] > 0): ?>
        <span class="kpi-badge danger">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>
            Requiere atención
        </span>
        <?php endif; ?>
    </div>
</div>

<!-- Main Content Row -->
<div class="section-row">
    <!-- Recent Employees Table -->
    <div class="glass-card">
        <div class="card-header">
            <div>
                <div class="card-title">Empleados Recientes</div>
                <div class="card-subtitle">Últimas altas en el sistema</div>
            </div>
            <a href="<?php echo BASE_URL; ?>/empleados" class="card-link">Ver todos →</a>
        </div>
        <div class="table-wrapper">
            <table class="glass-table">
                <thead>
                    <tr>
                        <th>Empleado</th>
                        <th>Empresa</th>
                        <th>Programa</th>
                        <th>Tipo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recientes)): ?>
                    <tr>
                        <td colspan="4" style="text-align:center; padding: 40px; color: #475569;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 12px; display: block; opacity: 0.4;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                            <div>Sin empleados registrados</div>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($recientes as $emp): ?>
                    <?php
                        $n = ($emp['nombre'] ?? '') . ' ' . ($emp['apellido_paterno'] ?? '');
                        $ini = strtoupper(substr($emp['nombre'] ?? 'U', 0, 1) . substr($emp['apellido_paterno'] ?? '', 0, 1));
                    ?>
                    <tr>
                        <td>
                            <div class="emp-info">
                                <div class="emp-avatar"><?php echo $ini; ?></div>
                                <div>
                                    <div class="emp-name"><?php echo htmlspecialchars($n); ?></div>
                                    <div class="emp-email"><?php echo htmlspecialchars($emp['email'] ?? ''); ?></div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge badge-blue"><?php echo htmlspecialchars($emp['empresa'] ?? '-'); ?></span></td>
                        <td><span class="badge badge-indigo"><?php echo htmlspecialchars($emp['programa_nivel'] ?? '-'); ?></span></td>
                        <td>
                            <span class="badge <?php echo ($emp['tipo'] ?? '') === 'interno' ? 'badge-green' : 'badge-yellow'; ?>">
                                <?php echo htmlspecialchars(ucfirst($emp['tipo'] ?? '-')); ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- CC Distribution -->
    <div class="glass-card">
        <div class="card-header">
            <div>
                <div class="card-title">Distribución CC</div>
                <div class="card-subtitle">Por tipo de documento</div>
            </div>
        </div>
        <?php
        $totalDist = array_sum(array_column($ccDist, 'total'));
        $tipos = [
            'OC'    => ['color' => '#6366f1', 'label' => 'Órdenes de Compra'],
            'REQ'   => ['color' => '#10b981', 'label' => 'Requisiciones'],
            'AMBOS' => ['color' => '#f59e0b', 'label' => 'Ambos (OC + REQ)'],
        ];
        ?>
        <?php if (empty($ccDist)): ?>
        <div style="padding: 40px; text-align: center; color: #475569;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 12px; display: block; opacity: 0.4;"><rect x="2" y="3" width="20" height="14" rx="2"/></svg>
            <div>Sin datos disponibles</div>
        </div>
        <?php else: ?>
        <?php foreach ($ccDist as $item): ?>
        <?php
            $tipo = $item['tipo_documento'] ?? 'OC';
            $cfg = $tipos[$tipo] ?? ['color' => '#6366f1', 'label' => $tipo];
            $pct = $totalDist > 0 ? round(($item['total'] / $totalDist) * 100) : 0;
        ?>
        <div class="dist-item">
            <div class="dist-header">
                <span class="dist-label"><?php echo htmlspecialchars($cfg['label']); ?></span>
                <span class="dist-count"><?php echo $item['total']; ?> (<?php echo $pct; ?>%)</span>
            </div>
            <div class="dist-bar-bg">
                <div class="dist-bar" style="width: <?php echo $pct; ?>%; background: <?php echo $cfg['color']; ?>;"></div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?php
$content = ob_get_clean();
include VIEWS_PATH . 'layouts/app.php';
?>

