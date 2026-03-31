<!DOCTYPE html>
<html lang="es" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $titulo ?? APP_NAME ?>
    </title>

    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/themes.css?v=1.0.1">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/app.css?v=1.0.1">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/responsive.css?v=1.0.1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= BASE_URL ?>public/js/alerts.js"></script>

    <style>
        /* ==========================================
           GLASSMORPHISM LAYOUT
           ========================================== */

        :root {
            --glass-primary: #3b82f6;
            --glass-primary-dark: #2563eb;
            --glass-primary-light: rgba(59, 130, 246, 0.15);
            --glass-success: #10b981;
            --glass-warning: #f59e0b;
            --glass-danger: #ef4444;

            --glass-bg: rgba(255, 255, 255, 0.4);
            --glass-bg-card: rgba(255, 255, 255, 0.6);
            --glass-bg-sidebar: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(59, 130, 246, 0.25);

            --glass-text-main: #1e293b;
            --glass-text-muted: #64748b;
            --glass-text-light: #94a3b8;

            --glass-blur: blur(30px);
            --glass-shadow: 0 8px 32px 0 rgba(59, 130, 246, 0.2);
            --glass-radius: 16px;
        }

        [data-theme="dark"] body,
        body.dark-mode {
            --glass-bg: rgba(30, 41, 59, 0.5);
            --glass-bg-card: rgba(30, 41, 59, 0.65);
            --glass-bg-sidebar: rgba(15, 23, 42, 0.85);
            --glass-border: rgba(59, 130, 246, 0.35);

            --glass-text-main: rgba(255, 255, 255, 0.95);
            --glass-text-muted: rgba(255, 255, 255, 0.65);
            --glass-text-light: rgba(255, 255, 255, 0.45);

            --glass-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.5);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--bg-secondary);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        [data-theme="dark"] body,
        body.dark-mode {
            background: var(--bg-secondary);
        }

        /* ==========================================
           APP LAYOUT
           ========================================== */
        .app-layout {
            display: flex;
            min-height: 100vh;
            position: relative;
            z-index: 1;
        }

        /* ==========================================
           SIDEBAR GLASS
           ========================================== */
        #sidebar {
            width: 260px;
            background: var(--glass-bg-sidebar);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border-right: 2px solid var(--glass-border);
            box-shadow: var(--glass-shadow);
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 1000;
            overflow-y: auto;
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 2px solid var(--glass-border);
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.08) 0%, rgba(139, 92, 246, 0.08) 100%);
        }

        .sidebar-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.125rem;
            font-weight: 800;
            color: var(--glass-text-main);
            margin: 0;
        }

        .sidebar-title i {
            color: var(--glass-primary);
            font-size: 1.5rem;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            padding: 0.875rem 1rem;
            margin-bottom: 0.5rem;
            color: var(--glass-text-main);
            text-decoration: none;
            border-radius: 10px;
            font-size: 0.9375rem;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            background: transparent;
            border: 2px solid transparent;
        }

        .sidebar-link i {
            font-size: 1.125rem;
            color: var(--glass-text-muted);
            transition: all 0.3s ease;
        }

        .sidebar-link:hover {
            background: var(--glass-bg-card);
            border-color: rgba(59, 130, 246, 0.2);
            transform: translateX(4px);
        }

        .sidebar-link:hover i {
            color: var(--glass-primary);
        }

        .sidebar-link.active {
            background: var(--glass-primary-light);
            border-color: var(--glass-primary);
            color: var(--glass-primary);
            font-weight: 600;
        }

        .sidebar-link.active i {
            color: var(--glass-primary);
        }

        .sidebar-divider {
            height: 1px;
            background: var(--glass-border);
            margin: 1rem 0;
        }

        /* Sidebar Badge */
        .sidebar-badge {
            background: var(--glass-danger);
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 3px 7px;
            border-radius: 50px;
            margin-left: auto;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
            min-width: 20px;
            text-align: center;
            animation: pulseBadge 2s infinite ease-in-out;
        }

        @keyframes pulseBadge {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        /* ==========================================
           MAIN WRAPPER
           ========================================== */
        .main-wrapper {
            flex: 1;
            margin-left: 260px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ==========================================
           TOPBAR GLASS
           ========================================== */
        .topbar {
            background: var(--glass-bg-card);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border-bottom: 2px solid var(--glass-border);
            box-shadow: var(--glass-shadow);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 0.875rem;
        }

        .avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--glass-primary) 0%, var(--glass-primary-dark) 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.875rem;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        /* Theme Toggle */
        .theme-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            padding: 0;
            margin-right: 15px;
            border: 2px solid var(--glass-border);
            background: var(--glass-bg-card);
            cursor: pointer;
            color: var(--glass-text-main);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .theme-toggle:hover {
            background: var(--glass-primary-light);
            border-color: var(--glass-primary);
            transform: scale(1.1);
        }

        .theme-toggle-icon {
            position: absolute;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .theme-toggle-icon.sun {
            opacity: 1;
            transform: rotate(0deg) scale(1);
        }

        .theme-toggle-icon.moon {
            opacity: 0;
            transform: rotate(180deg) scale(0);
        }

        body.dark-mode .theme-toggle-icon.sun {
            opacity: 0;
            transform: rotate(-180deg) scale(0);
        }

        body.dark-mode .theme-toggle-icon.moon {
            opacity: 1;
            transform: rotate(0deg) scale(1);
        }

        /* Toggle Sidebar Button */
        #toggleSidebar {
            padding: 0.5rem 1rem;
            background: var(--glass-bg-card);
            border: 2px solid var(--glass-border);
            border-radius: 10px;
            color: var(--glass-text-main);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        #toggleSidebar:hover {
            background: var(--glass-primary-light);
            border-color: var(--glass-primary);
        }

        /* ==========================================
           MAIN CONTENT
           ========================================== */
        .main-content {
            flex: 1;
            padding: 2rem;
        }

        /* ==========================================
           ALERTS GLASS
           ========================================== */
        .alert {
            background: var(--glass-bg-card);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.875rem;
            box-shadow: var(--glass-shadow);
            border: 2px solid;
            animation: slideDown 0.4s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert i {
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .alert span {
            flex: 1;
            font-size: 0.9375rem;
            font-weight: 500;
        }

        .alert-close {
            background: transparent;
            border: none;
            font-size: 1.5rem;
            color: inherit;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.3s ease;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .alert-close:hover {
            opacity: 1;
        }

        .alert-success {
            border-color: rgba(16, 185, 129, 0.3);
            background: rgba(16, 185, 129, 0.08);
            color: #047857;
        }

        .alert-success i {
            color: var(--glass-success);
        }

        .alert-danger {
            border-color: rgba(239, 68, 68, 0.3);
            background: rgba(239, 68, 68, 0.08);
            color: #b91c1c;
        }

        .alert-danger i {
            color: var(--glass-danger);
        }

        .alert-warning {
            border-color: rgba(245, 158, 11, 0.3);
            background: rgba(245, 158, 11, 0.08);
            color: #d97706;
        }

        .alert-warning i {
            color: var(--glass-warning);
        }

        /* Auto-close animation */
        .alert[data-auto-close] {
            animation: slideDown 0.4s ease-out, slideUp 0.4s ease-in 4.6s forwards;
        }

        @keyframes slideUp {
            from {
                opacity: 1;
                transform: translateY(0);
            }

            to {
                opacity: 0;
                transform: translateY(-20px);
            }
        }

        /* ==========================================
           RESPONSIVE
           ========================================== */
        @media (max-width: 992px) {
            #sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            #sidebar.active {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-left: 0;
            }

            #toggleSidebar {
                display: flex !important;
            }

            .topbar {
                padding: 1rem;
            }

            .main-content {
                padding: 1rem;
            }
        }

        @media (max-width: 640px) {
            .sidebar {
                width: 100%;
                max-width: 280px;
            }

            .topbar-user>div {
                display: none;
            }
        }

        /* ==========================================
           SCROLLBAR STYLING
           ========================================== */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--glass-border);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--glass-primary);
        }
    </style>

</head>

<body>
    <script>
        // Init Theme Immediately to prevent FOUC
        (function () {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            if (savedTheme === 'dark') {
                document.body.classList.add('dark-mode');
            }
        })();
    </script>
    <?php
    // Obtenemos los contadores globales para las alertas del menú
    $badges = function_exists('obtenerContadoresMenu') ? obtenerContadoresMenu() : ['solicitudes' => 0, 'proveedores' => 0];
    ?>

    <div class="app-layout">
        <!-- Sidebar Glass -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h1 class="sidebar-title">
                    <i class="bi bi-building-check"></i>
                    <span>
                        <?= str_replace(['"', "'"], '', APP_NAME) ?>
                    </span>
                </h1>
            </div>

            <nav class="sidebar-nav">
                <a href="<?= BASE_URL ?>dashboard"
                    class="sidebar-link <?= ($pagina_actual ?? '') === 'dashboard' ? 'active' : '' ?>">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>

                <?php if (puedeVer('proveedores')): ?>
                    <a href="<?= BASE_URL ?>proveedores"
                        class="sidebar-link <?= ($pagina_actual ?? '') === 'proveedores' ? 'active' : '' ?>">
                        <i class="bi bi-building"></i>
                        <span>Proveedores</span>
                        <?php if (isset($badges['proveedores']) && $badges['proveedores'] > 0): ?>
                            <span class="sidebar-badge" title="<?= $badges['proveedores'] ?> pendientes">
                                <?= $badges['proveedores'] ?>
                            </span>
                        <?php endif; ?>
                    </a>
                <?php endif; ?>

                <?php if (puedeVer('solicitudes')): ?>
                    <a href="<?= BASE_URL ?>solicitudes"
                        class="sidebar-link <?= ($pagina_actual ?? '') === 'solicitudes' ? 'active' : '' ?>">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>Solicitudes</span>
                        <?php if (isset($badges['solicitudes']) && $badges['solicitudes'] > 0): ?>
                            <span class="sidebar-badge" title="<?= $badges['solicitudes'] ?> nuevas">
                                <?= $badges['solicitudes'] ?>
                            </span>
                        <?php endif; ?>
                    </a>
                <?php endif; ?>

                <div class="sidebar-divider"></div>

                <?php if (puedeVer('usuarios')): ?>
                    <a href="<?= BASE_URL ?>usuarios"
                        class="sidebar-link <?= ($pagina_actual ?? '') === 'usuarios' ? 'active' : '' ?>">
                        <i class="bi bi-people"></i>
                        <span>Usuarios</span>
                    </a>
                <?php endif; ?>

                <?php if (puedeVer('perfiles')): ?>
                    <a href="<?= BASE_URL ?>perfiles"
                        class="sidebar-link <?= ($pagina_actual ?? '') === 'perfiles' ? 'active' : '' ?>">
                        <i class="bi bi-shield-check"></i>
                        <span>Perfiles</span>
                    </a>
                <?php endif; ?>

                <?php if (puedeVer('catalogos')): ?>
                    <a href="<?= BASE_URL ?>catalogos"
                        class="sidebar-link <?= ($pagina_actual ?? '') === 'catalogos' ? 'active' : '' ?>">
                        <i class="bi bi-list-ul"></i>
                        <span>Catálogos</span>
                    </a>
                <?php endif; ?>

                <div class="sidebar-divider"></div>

                <a href="<?= BASE_URL ?>cambiar-password" class="sidebar-link">
                    <i class="bi bi-key"></i>
                    <span>Cambiar Contraseña</span>
                </a>

                <a href="<?= BASE_URL ?>logout" class="sidebar-link">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Cerrar Sesión</span>
                </a>
            </nav>
        </aside>

        <!-- Main Wrapper -->
        <div class="main-wrapper">
            <!-- Topbar Glass -->
            <div class="topbar">
                <div>
                    <button class="btn btn-glass" id="toggleSidebar" style="display: none;">
                        <i class="bi bi-list"></i>
                    </button>
                </div>

                <div style="display: flex; align-items: center;">
                    <!-- Theme Toggle -->
                    <button class="theme-toggle" aria-label="Cambiar tema">
                        <i class="bi bi-sun-fill theme-toggle-icon sun"></i>
                        <i class="bi bi-moon-fill theme-toggle-icon moon"></i>
                    </button>

                    <!-- Divider -->
                    <div style="width: 1px; height: 24px; background: var(--glass-border); margin-right: 15px;"></div>

                    <!-- User Info -->
                    <div class="topbar-user">
                        <div class="avatar">
                            <?= strtoupper(substr(usuarioActual()['nombre'] ?? 'U', 0, 1)) ?>
                        </div>
                        <div>
                            <div style="font-weight: 600; font-size: 0.9375rem; color: var(--glass-text-main);">
                                <?= e(usuarioActual()['nombre'] ?? 'Usuario') ?>
                            </div>
                            <div style="font-size: 0.8125rem; color: var(--glass-text-muted);">
                                <?= e(usuarioActual()['perfil_nombre'] ?? usuarioActual()['rol'] ?? '') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <main class="main-content">
                <!-- Flash Messages -->
                <?php if ($flash = getFlash('success')): ?>
                    <div class="alert alert-success" data-auto-close>
                        <i class="bi bi-check-circle"></i>
                        <span>
                            <?= e($flash) ?>
                        </span>
                        <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
                    </div>
                <?php endif; ?>

                <?php if ($flash = getFlash('error')): ?>
                    <div class="alert alert-danger" data-auto-close>
                        <i class="bi bi-exclamation-circle"></i>
                        <span>
                            <?= e($flash) ?>
                        </span>
                        <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
                    </div>
                <?php endif; ?>

                <?php if ($flash = getFlash('warning')): ?>
                    <div class="alert alert-warning" data-auto-close>
                        <i class="bi bi-exclamation-triangle"></i>
                        <span>
                            <?= e($flash) ?>
                        </span>
                        <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
                    </div>
                <?php endif; ?>

                <script>
                    // Sidebar toggle for mobile
                    document.addEventListener('DOMContentLoaded', function () {
                        const toggleBtn = document.getElementById('toggleSidebar');
                        const sidebar = document.getElementById('sidebar');

                        toggleBtn?.addEventListener('click', function () {
                            sidebar.classList.toggle('active');
                        });

                        // Close sidebar when clicking outside on mobile
                        document.addEventListener('click', function (e) {
                            if (window.innerWidth <= 992) {
                                if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                                    sidebar.classList.remove('active');
                                }
                            }
                        });

                        // Auto-close alerts
                        document.querySelectorAll('[data-auto-close]').forEach(alert => {
                            setTimeout(() => {
                                alert.remove();
                            }, 5000);
                        });
                    });
                </script>