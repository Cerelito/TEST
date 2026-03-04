<?php
$pagina_actual = 'dashboard';
$titulo = 'Dashboard';
require_once VIEWS_PATH . 'layouts/header.php';

// Lógica de Notificaciones
$pendientesProveedores = $stats['proveedores']['pendientes'] ?? 0;
$pendientesSolicitudes = $stats['solicitudes']['pendientes'] ?? 0;
$totalPendientes = $pendientesProveedores + $pendientesSolicitudes;
?>

<div class="dashboard-pro">
    <!-- Hero Header -->
    <div class="hero-header">
        <div class="hero-content">
            <div class="hero-left">
                <div class="welcome-tag">
                    <i class="bi bi-stars"></i>
                    <span>Panel de Control</span>
                </div>
                <h1 class="hero-title">
                    Bienvenido, <span class="gradient-text">
                        <?= $_SESSION['nombre'] ?? 'Usuario' ?>
                    </span>
                </h1>
                <p class="hero-subtitle">
                    Aquí está el resumen de tu sistema hoy
                </p>
            </div>
            <div class="hero-right">
                <?php if ($totalPendientes > 0): ?>
                    <div class="alert-card">
                        <div class="alert-icon">
                            <i class="bi bi-bell-fill"></i>
                            <span class="alert-badge">
                                <?= $totalPendientes ?>
                            </span>
                        </div>
                        <div class="alert-text">
                            <strong>
                                <?= $totalPendientes ?> Tareas Pendientes
                            </strong>
                            <span>Requieren tu atención</span>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="success-card">
                        <div class="success-icon">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div class="success-text">
                            <strong>Todo al Día</strong>
                            <span>Sin pendientes</span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <?php if (isset($stats['proveedores'])):
            $totalProv = $stats['proveedores']['total'] > 0 ? $stats['proveedores']['total'] : 1;
            $porcentajeProv = round(($stats['proveedores']['aprobados'] / $totalProv) * 100);
            $pendientesProv = $stats['proveedores']['pendientes'] ?? 0;
            ?>
            <div class="stat-card stat-blue">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                    <div class="stat-trend up">
                        <i class="bi bi-arrow-up"></i>
                        <span>+12%</span>
                    </div>
                </div>
                <div class="stat-body">
                    <h3 class="stat-title">Proveedores</h3>
                    <div class="stat-number">
                        <span class="counter" data-target="<?= $stats['proveedores']['total'] ?? 0 ?>">0</span>
                    </div>
                    <div class="stat-footer">
                        <div class="stat-item">
                            <span class="dot green"></span>
                            <span>
                                <?= $stats['proveedores']['aprobados'] ?? 0 ?> Activos
                            </span>
                        </div>
                        <div class="stat-item">
                            <span class="dot orange"></span>
                            <span>
                                <?= $pendientesProv ?> Pendientes
                            </span>
                        </div>
                    </div>
                </div>
                <div class="stat-progress">
                    <div class="progress-bar">
                        <div class="progress-fill blue" style="width: <?= $porcentajeProv ?>%"></div>
                    </div>
                    <span class="progress-label">
                        <?= $porcentajeProv ?>% Aprobados
                    </span>
                </div>
            </div>
        <?php endif; ?>

        <?php if (isset($stats['usuarios'])):
            $totalUser = $stats['usuarios']['total'] > 0 ? $stats['usuarios']['total'] : 1;
            $porcentajeUser = round(($stats['usuarios']['activos'] / $totalUser) * 100);
            ?>
            <div class="stat-card stat-purple">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="stat-trend up">
                        <i class="bi bi-arrow-up"></i>
                        <span>+8%</span>
                    </div>
                </div>
                <div class="stat-body">
                    <h3 class="stat-title">Usuarios</h3>
                    <div class="stat-number">
                        <span class="counter" data-target="<?= $stats['usuarios']['total'] ?? 0 ?>">0</span>
                    </div>
                    <div class="stat-footer">
                        <div class="stat-item">
                            <span class="dot green"></span>
                            <span>
                                <?= $stats['usuarios']['activos'] ?? 0 ?> Activos
                            </span>
                        </div>
                        <div class="stat-item">
                            <span class="dot red"></span>
                            <span>
                                <?= $stats['usuarios']['inactivos'] ?? 0 ?> Inactivos
                            </span>
                        </div>
                    </div>
                </div>
                <div class="stat-progress">
                    <div class="progress-bar">
                        <div class="progress-fill purple" style="width: <?= $porcentajeUser ?>%"></div>
                    </div>
                    <span class="progress-label">
                        <?= $porcentajeUser ?>% Activos
                    </span>
                </div>
            </div>
        <?php endif; ?>

        <!-- System Status Card -->
        <div class="stat-card stat-gradient">
            <div class="status-overlay">
                <div class="status-header">
                    <div class="status-badge">
                        <span class="pulse-ring"></span>
                        <span class="pulse-dot"></span>
                        <span>ONLINE</span>
                    </div>
                    <div class="status-time" id="currentTime">00:00:00</div>
                </div>
                <div class="status-body">
                    <h3 class="status-title">Sistema Operativo</h3>
                    <div class="status-date" id="currentDate"></div>
                </div>
                <div class="status-metrics">
                    <div class="metric">
                        <i class="bi bi-hdd-network-fill"></i>
                        <div>
                            <small>Base de Datos</small>
                            <strong>Conectada</strong>
                        </div>
                    </div>
                    <div class="metric">
                        <i class="bi bi-speedometer2"></i>
                        <div>
                            <small>Rendimiento</small>
                            <strong>Óptimo</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="stat-card stat-actions">
            <div class="actions-header">
                <h3>
                    <i class="bi bi-lightning-charge-fill"></i>
                    Acciones Rápidas
                </h3>
            </div>
            <div class="actions-grid">
                <?php if (puedeCrear('proveedores')): ?>
                    <a href="<?= BASE_URL ?>proveedores/crear" class="action-btn blue">
                        <i class="bi bi-person-plus-fill"></i>
                        <span>Nuevo Proveedor</span>
                    </a>
                <?php endif; ?>

                <?php if (puedeCrear('usuarios')): ?>
                    <a href="<?= BASE_URL ?>usuarios/crear" class="action-btn purple">
                        <i class="bi bi-shield-fill-plus"></i>
                        <span>Nuevo Usuario</span>
                    </a>
                <?php endif; ?>

                <a href="<?= BASE_URL ?>proveedores" class="action-btn green">
                    <i class="bi bi-list-ul"></i>
                    <span>Ver Proveedores</span>
                </a>

                <a href="<?= BASE_URL ?>usuarios" class="action-btn orange">
                    <i class="bi bi-people"></i>
                    <span>Ver Usuarios</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="chart-section">
        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <h3 class="chart-title">
                        <i class="bi bi-graph-up-arrow"></i>
                        Actividad de la Semana
                    </h3>
                    <p class="chart-subtitle">Nuevas altas vs. Aprobaciones</p>
                </div>
                <div class="chart-legend">
                    <div class="legend-item">
                        <span class="legend-dot blue"></span>
                        <span>Nuevas Altas</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-dot green"></span>
                        <span>Aprobados</span>
                    </div>
                </div>
            </div>
            <div class="chart-body">
                <canvas id="activityChart"></canvas>
            </div>
        </div>
    </div>
</div>

<style>
    /* ==========================================
   ANIMACIONES
   ========================================== */
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }
    }

    @keyframes ripple {
        0% {
            transform: scale(0.8);
            opacity: 1;
        }

        100% {
            transform: scale(2.5);
            opacity: 0;
        }
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    @keyframes gradient {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    /* ==========================================
   LAYOUT PRINCIPAL
   ========================================== */
    .dashboard-pro {
        padding: 2rem 0;
        max-width: 100%;
        animation: fadeIn 0.5s ease-out;
    }

    /* ==========================================
   HERO HEADER
   ========================================== */
    .hero-header {
        background: linear-gradient(135deg, var(--blue) 0%, var(--purple) 100%);
        border-radius: var(--radius-lg);
        padding: 2.5rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        animation: slideUp 0.6s ease-out;
    }

    body.dark-mode .hero-header {
        background: linear-gradient(135deg, #1e40af 0%, #6d28d9 100%);
        box-shadow: 0 0 40px rgba(139, 92, 246, 0.3);
    }

    .hero-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: float 6s ease-in-out infinite;
    }

    .hero-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 2rem;
        position: relative;
        z-index: 1;
    }

    .hero-left {
        flex: 1;
    }

    .welcome-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 0.5rem 1rem;
        border-radius: 50px;
        color: white;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .hero-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        margin: 0 0 0.5rem 0;
        line-height: 1.2;
    }

    .gradient-text {
        background: linear-gradient(to right, #fff, rgba(255, 255, 255, 0.8));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-subtitle {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.1rem;
        margin: 0;
    }

    .alert-card,
    .success-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        padding: 1.5rem;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        gap: 1rem;
        min-width: 280px;
        box-shadow: var(--shadow-lg);
    }

    /* MODO OSCURO PARA LAS TARJETAS DE ALERTA - ARREGLADO */
    body.dark-mode .alert-card,
    body.dark-mode .success-card {
        background: rgba(30, 41, 59, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .alert-icon,
    .success-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        position: relative;
    }

    .alert-icon {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: var(--red);
    }

    .success-icon {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: var(--green);
    }

    body.dark-mode .alert-icon {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.25) 0%, rgba(239, 68, 68, 0.35) 100%);
        color: #fca5a5;
    }

    body.dark-mode .success-icon {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.25) 0%, rgba(16, 185, 129, 0.35) 100%);
        color: #6ee7b7;
    }

    .alert-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: var(--red);
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        animation: pulse 2s ease-in-out infinite;
    }

    .alert-text,
    .success-text {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    /* COLORES DE TEXTO ARREGLADOS */
    .alert-text strong,
    .success-text strong {
        color: #1e293b;
        font-size: 1rem;
        font-weight: 700;
    }

    .alert-text span,
    .success-text span {
        color: #64748b;
        font-size: 0.875rem;
    }

    body.dark-mode .alert-text strong,
    body.dark-mode .success-text strong {
        color: #f1f5f9;
    }

    body.dark-mode .alert-text span,
    body.dark-mode .success-text span {
        color: #cbd5e1;
    }

    /* ==========================================
   STATS GRID
   ========================================== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--bg-secondary);
        border-radius: var(--radius-lg);
        padding: 1.75rem;
        box-shadow: var(--shadow-md);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        animation: slideUp 0.6s ease-out;
        animation-fill-mode: both;
    }

    .stat-card:nth-child(1) {
        animation-delay: 0.1s;
    }

    .stat-card:nth-child(2) {
        animation-delay: 0.2s;
    }

    .stat-card:nth-child(3) {
        animation-delay: 0.3s;
    }

    .stat-card:nth-child(4) {
        animation-delay: 0.4s;
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-xl);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        border-radius: var(--radius-lg) var(--radius-lg) 0 0;
    }

    .stat-blue::before {
        background: linear-gradient(90deg, var(--blue), var(--blue-light));
    }

    .stat-purple::before {
        background: linear-gradient(90deg, var(--purple), var(--purple-light));
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        transition: transform 0.3s ease;
    }

    .stat-card:hover .stat-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .stat-blue .stat-icon {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(96, 165, 250, 0.2) 100%);
        color: var(--blue);
    }

    .stat-purple .stat-icon {
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.1) 0%, rgba(167, 139, 250, 0.2) 100%);
        color: var(--purple);
    }

    body.dark-mode .stat-blue .stat-icon {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.2) 0%, rgba(96, 165, 250, 0.3) 100%);
    }

    body.dark-mode .stat-purple .stat-icon {
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.2) 0%, rgba(167, 139, 250, 0.3) 100%);
    }

    .stat-trend {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 700;
    }

    .stat-trend.up {
        background: rgba(16, 185, 129, 0.1);
        color: var(--green);
    }

    body.dark-mode .stat-trend.up {
        background: rgba(16, 185, 129, 0.2);
    }

    .stat-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin: 0 0 0.75rem 0;
    }

    .stat-number {
        font-size: 3rem;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1;
        margin-bottom: 1rem;
    }

    .stat-footer {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 1.25rem;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .dot.green {
        background: var(--green);
    }

    .dot.orange {
        background: var(--orange);
    }

    .dot.red {
        background: var(--red);
    }

    .stat-progress {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .progress-bar {
        height: 8px;
        background: rgba(0, 0, 0, 0.05);
        border-radius: 50px;
        overflow: hidden;
    }

    body.dark-mode .progress-bar {
        background: rgba(255, 255, 255, 0.1);
    }

    .progress-fill {
        height: 100%;
        border-radius: 50px;
        transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .progress-fill::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% {
            transform: translateX(-100%);
        }

        100% {
            transform: translateX(100%);
        }
    }

    .progress-fill.blue {
        background: linear-gradient(90deg, var(--blue-dark), var(--blue-light));
    }

    .progress-fill.purple {
        background: linear-gradient(90deg, var(--purple-dark), var(--purple-light));
    }

    .progress-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-tertiary);
    }

    /* ==========================================
   GRADIENT STATUS CARD
   ========================================== */
    .stat-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        background-size: 200% 200%;
        animation: gradient 8s ease infinite;
        color: white;
        min-height: 280px;
    }

    body.dark-mode .stat-gradient {
        background: linear-gradient(135deg, #4c63d2 0%, #5a3a7f 50%, #c76dce 100%);
        background-size: 200% 200%;
    }

    .stat-gradient::before {
        display: none;
    }

    .status-overlay {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .status-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .status-badge {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        position: relative;
    }

    .pulse-ring {
        position: absolute;
        width: 100%;
        height: 100%;
        border: 2px solid white;
        border-radius: 50px;
        animation: ripple 2s ease-out infinite;
    }

    .pulse-dot {
        width: 8px;
        height: 8px;
        background: white;
        border-radius: 50%;
        animation: pulse 2s ease-in-out infinite;
    }

    .status-time {
        font-size: 1.5rem;
        font-weight: 700;
        font-family: 'SF Mono', monospace;
    }

    .status-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
    }

    .status-date {
        font-size: 0.95rem;
        opacity: 0.9;
    }

    .status-metrics {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }

    .metric {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .metric i {
        font-size: 1.5rem;
        opacity: 0.9;
    }

    .metric small {
        display: block;
        font-size: 0.75rem;
        opacity: 0.8;
    }

    .metric strong {
        display: block;
        font-size: 0.95rem;
        font-weight: 600;
    }

    /* ==========================================
   ACTIONS CARD
   ========================================== */
    .stat-actions {
        background: var(--bg-secondary);
    }

    .stat-actions::before {
        background: linear-gradient(90deg, var(--green), #34d399);
    }

    .actions-header {
        margin-bottom: 1.5rem;
    }

    .actions-header h3 {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .actions-header i {
        color: var(--green);
    }

    .actions-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }

    .action-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem;
        border-radius: var(--radius-sm);
        text-decoration: none;
        transition: all 0.3s ease;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .action-btn i {
        font-size: 1.5rem;
    }

    .action-btn.blue {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(96, 165, 250, 0.15) 100%);
        color: var(--blue);
    }

    .action-btn.purple {
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.1) 0%, rgba(167, 139, 250, 0.15) 100%);
        color: var(--purple);
    }

    .action-btn.green {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(52, 211, 153, 0.15) 100%);
        color: var(--green);
    }

    .action-btn.orange {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(251, 191, 36, 0.15) 100%);
        color: var(--orange);
    }

    body.dark-mode .action-btn.blue {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(96, 165, 250, 0.2) 100%);
    }

    body.dark-mode .action-btn.purple {
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.15) 0%, rgba(167, 139, 250, 0.2) 100%);
    }

    body.dark-mode .action-btn.green {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(52, 211, 153, 0.2) 100%);
    }

    body.dark-mode .action-btn.orange {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(251, 191, 36, 0.2) 100%);
    }

    .action-btn:hover {
        transform: translateY(-4px) scale(1.05);
        box-shadow: var(--shadow-lg);
    }

    /* ==========================================
   CHART SECTION
   ========================================== */
    .chart-section {
        margin-bottom: 2rem;
        animation: slideUp 0.6s ease-out 0.5s both;
    }

    .chart-card {
        background: var(--bg-secondary);
        border-radius: var(--radius-lg);
        padding: 2rem;
        box-shadow: var(--shadow-md);
        transition: all 0.3s ease;
    }

    .chart-card:hover {
        box-shadow: var(--shadow-lg);
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .chart-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
    }

    .chart-title i {
        color: var(--blue);
    }

    .chart-subtitle {
        color: var(--text-secondary);
        font-size: 0.95rem;
        margin: 0;
    }

    .chart-legend {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .legend-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .legend-dot.blue {
        background: var(--blue);
    }

    .legend-dot.green {
        background: var(--green);
    }

    .chart-body {
        position: relative;
        height: 350px;
    }

    .chart-body canvas {
        width: 100% !important;
        height: 100% !important;
    }

    /* ==========================================
   RESPONSIVE
   ========================================== */
    @media (max-width: 768px) {
        .dashboard-pro {
            padding: 1rem 0;
        }

        .hero-header {
            padding: 2rem 1.5rem;
            margin-bottom: 1.5rem;
        }

        .hero-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .hero-title {
            font-size: 2rem;
        }

        .alert-card,
        .success-card {
            min-width: 100%;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .actions-grid {
            grid-template-columns: 1fr;
        }

        .chart-card {
            padding: 1.5rem;
        }

        .chart-header {
            flex-direction: column;
        }

        .chart-body {
            height: 300px;
        }

        .stat-number {
            font-size: 2.5rem;
        }
    }

    @media (max-width: 480px) {
        .hero-title {
            font-size: 1.75rem;
        }

        .stat-number {
            font-size: 2rem;
        }

        .stat-footer {
            flex-direction: column;
            gap: 0.5rem;
        }
    }

    /* ==========================================
   SCROLLBAR PERSONALIZADO
   ========================================== */
    ::-webkit-scrollbar {
        width: 10px;
    }

    ::-webkit-scrollbar-track {
        background: var(--bg-primary);
    }

    ::-webkit-scrollbar-thumb {
        background: var(--text-tertiary);
        border-radius: 5px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: var(--text-secondary);
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // ==========================================
    // RELOJ Y FECHA
    // ==========================================
    function updateDateTime() {
        const now = new Date();

        const timeString = now.toLocaleTimeString('es-MX', {
            hour12: false,
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });

        const dateString = now.toLocaleDateString('es-MX', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        const timeEl = document.getElementById('currentTime');
        const dateEl = document.getElementById('currentDate');

        if (timeEl) timeEl.textContent = timeString;
        if (dateEl) dateEl.textContent = dateString.charAt(0).toUpperCase() + dateString.slice(1);
    }

    setInterval(updateDateTime, 1000);
    updateDateTime();

    // ==========================================
    // ANIMACIÓN DE CONTADORES
    // ==========================================
    function animateCounter(element, target, duration = 2000) {
        const start = 0;
        const increment = target / (duration / 16);
        let current = start;

        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                element.textContent = Math.round(target).toLocaleString();
                clearInterval(timer);
            } else {
                element.textContent = Math.round(current).toLocaleString();
            }
        }, 16);
    }

    // ==========================================
    // GRÁFICO DE ACTIVIDAD
    // ==========================================
    document.addEventListener('DOMContentLoaded', function () {
        // Animar contadores
        document.querySelectorAll('.counter').forEach(el => {
            const target = parseInt(el.getAttribute('data-target')) || 0;
            setTimeout(() => animateCounter(el, target), 500);
        });

        // Crear gráfico
        const canvas = document.getElementById('activityChart');
        if (canvas) {
            const ctx = canvas.getContext('2d');

            const isDark = document.body.classList.contains('dark-mode');
            const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)';
            const textColor = isDark ? '#cbd5e1' : '#64748b';

            const gradient1 = ctx.createLinearGradient(0, 0, 0, 350);
            gradient1.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
            gradient1.addColorStop(1, 'rgba(59, 130, 246, 0)');

            const gradient2 = ctx.createLinearGradient(0, 0, 0, 350);
            gradient2.addColorStop(0, 'rgba(16, 185, 129, 0.3)');
            gradient2.addColorStop(1, 'rgba(16, 185, 129, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                    datasets: [
                        {
                            label: 'Nuevas Altas',
                            data: [12, 19, 8, 15, 22, 10, 5],
                            borderColor: '#3b82f6',
                            backgroundColor: gradient1,
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#3b82f6',
                            pointBorderWidth: 3,
                            pointHoverBackgroundColor: '#3b82f6',
                            pointHoverBorderColor: '#fff',
                            pointHoverBorderWidth: 3,
                        },
                        {
                            label: 'Aprobados',
                            data: [8, 15, 5, 12, 20, 8, 4],
                            borderColor: '#10b981',
                            backgroundColor: gradient2,
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#10b981',
                            pointBorderWidth: 3,
                            pointHoverBackgroundColor: '#10b981',
                            pointHoverBorderColor: '#fff',
                            pointHoverBorderWidth: 3,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    animation: {
                        duration: 2000,
                        easing: 'easeInOutQuart'
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: isDark ? 'rgba(30, 41, 59, 0.95)' : 'rgba(255, 255, 255, 0.95)',
                            titleColor: isDark ? '#f1f5f9' : '#1e293b',
                            bodyColor: isDark ? '#cbd5e1' : '#64748b',
                            borderColor: isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)',
                            borderWidth: 1,
                            padding: 16,
                            displayColors: true,
                            boxWidth: 12,
                            boxHeight: 12,
                            boxPadding: 6,
                            usePointStyle: true,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: textColor,
                                font: {
                                    size: 13,
                                    weight: '500'
                                }
                            },
                            border: {
                                display: false
                            }
                        },
                        y: {
                            grid: {
                                color: gridColor,
                                drawBorder: false
                            },
                            ticks: {
                                color: textColor,
                                font: {
                                    size: 13,
                                    weight: '500'
                                },
                                padding: 12,
                                stepSize: 5
                            },
                            border: {
                                display: false
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    });
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>