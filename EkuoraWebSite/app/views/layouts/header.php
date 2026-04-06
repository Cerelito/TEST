<!DOCTYPE html>
<html lang="es" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? APP_NAME ?></title>
    <link rel="icon" type="image/png" href="<?= e(asset('favicon.png')) ?>">
    <link rel="shortcut icon" href="<?= e(asset('favicon.png')) ?>" type="image/png">
    <link rel="apple-touch-icon" href="<?= e(asset('favicon.png')) ?>">

    <link rel="stylesheet" href="<?= BASE_URL ?>css/themes.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/app.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/ultra-glass.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/admin_custom.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/responsive.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Global Font Override */
        * {
            font-family: 'Montserrat', sans-serif !important;
        }

        .app-layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .sidebar-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary);
            margin: 0;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.2s;
        }

        .sidebar-link:hover {
            background: var(--sidebar-hover);
            color: var(--text-primary);
        }

        .sidebar-link.active {
            background: var(--sidebar-active);
            color: var(--primary);
            font-weight: 600;
        }

        .sidebar-link i {
            font-size: 1.125rem;
        }

        .sidebar-divider {
            height: 1px;
            background: var(--border-color);
            margin: 0.5rem 1.5rem;
        }

        .main-wrapper {
            flex: 1;
            margin-left: 260px;
        }

        .topbar {
            height: 64px;
            background: var(--bg-primary);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .main-content {
            padding: 2rem;
        }

        #toggleSidebar {
            display: none;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

    <div class="app-layout">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h1 class="sidebar-title">
                    <i class="bi bi-shop"></i> Ekuora Admin
                </h1>
            </div>

            <nav class="sidebar-nav">
                <a href="<?= BASE_URL ?>dashboard"
                    class="sidebar-link <?= ($pagina_actual ?? '') === 'dashboard' ? 'active' : '' ?>">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>

                <a href="<?= BASE_URL ?>productos/admin"
                    class="sidebar-link <?= ($pagina_actual ?? '') === 'productos_admin' ? 'active' : '' ?>">
                    <i class="bi bi-box-seam"></i>
                    <span>Productos</span>
                </a>

                <a href="<?= BASE_URL ?>productos/categorias"
                    class="sidebar-link <?= ($pagina_actual ?? '') === 'categorias_admin' ? 'active' : '' ?>">
                    <i class="bi bi-grid-3x3-gap"></i>
                    <span>Categorías</span>
                </a>

                <a href="<?= BASE_URL ?>productos/familias"
                    class="sidebar-link <?= ($pagina_actual ?? '') === 'familias_admin' ? 'active' : '' ?>">
                    <i class="bi bi-grid-1x2"></i>
                    <span>Familias</span>
                </a>

                <a href="<?= BASE_URL ?>colecciones/admin"
                    class="sidebar-link <?= ($pagina_actual ?? '') === 'colecciones_admin' ? 'active' : '' ?>">
                    <i class="bi bi-collection"></i>
                    <span>Colecciones</span>
                </a>

                <a href="<?= BASE_URL ?>banners/admin"
                    class="sidebar-link <?= ($pagina_actual ?? '') === 'banners_admin' ? 'active' : '' ?>">
                    <i class="bi bi-images"></i>
                    <span>Banners de Inicio</span>
                </a>

                <a href="<?= BASE_URL ?>ajustes"
                    class="sidebar-link <?= ($pagina_actual ?? '') === 'ajustes' ? 'active' : '' ?>">
                    <i class="bi bi-gear"></i>
                    <span>Ajustes del Sitio</span>
                </a>

                <div class="sidebar-divider"></div>

                <a href="<?= BASE_URL ?>usuarios"
                    class="sidebar-link <?= ($pagina_actual ?? '') === 'usuarios' ? 'active' : '' ?>">
                    <i class="bi bi-people"></i>
                    <span>Usuarios</span>
                </a>

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

        <!-- Main Content -->
        <div class="main-wrapper">
            <!-- Top Bar -->
            <div class="topbar">
                <div>
                    <button class="btn btn-glass" id="toggleSidebar">
                        <i class="bi bi-list"></i>
                    </button>
                </div>

                <div class="topbar-user">
                    <div class="avatar"
                        style="width: 40px; height: 40px; background: var(--primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                        <?= strtoupper(substr(usuarioActual()['nombre'] ?? 'U', 0, 1)) ?>
                    </div>
                    <div>
                        <div style="font-weight: 600; font-size: 0.9375rem; color: var(--text-primary);">
                            <?= e(usuarioActual()['nombre'] ?? 'Usuario') ?>
                        </div>
                        <div style="font-size: 0.8125rem; color: var(--text-muted);">
                            <?= e(usuarioActual()['perfil_nombre'] ?? usuarioActual()['rol'] ?? '') ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <main class="main-content">
                <?php
                // Capturar mensajes flash para el sistema de notificaciones JS
                $flashSuccess = getFlash('success');
                $flashError = getFlash('error');
                $flashWarning = getFlash('warning');
                $flashInfo = getFlash('info');
                ?>

                <?php if ($flashSuccess || $flashError || $flashWarning || $flashInfo): ?>
                    <script>
                        window.flashMessages = {
                            success: <?= json_encode($flashSuccess) ?>,
                            error: <?= json_encode($flashError) ?>,
                            warning: <?= json_encode($flashWarning) ?>,
                            info: <?= json_encode($flashInfo) ?>
                        };
                    </script>
                <?php endif; ?>