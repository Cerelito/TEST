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