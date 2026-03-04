<!DOCTYPE html>
<html lang="es" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? APP_NAME ?></title>

    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/themes.css?v=2.0.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/app.css?v=2.0.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/responsive.css?v=2.0.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= BASE_URL ?>public/js/alerts.js"></script>
</head>

<body>
    <script>
        // Init tema inmediato para evitar FOUC
        (function () {
            const saved = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', saved);
            if (saved === 'dark') document.body.classList.add('dark-mode');
        })();
    </script>

    <?php
    $badges = function_exists('obtenerContadoresMenu')
        ? obtenerContadoresMenu()
        : ['solicitudes' => 0, 'proveedores' => 0];
    ?>

    <div class="app-layout">
        <!-- Sidebar Glass -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h1 class="sidebar-title">
                    <i class="bi bi-building-check"></i>
                    <span><?= str_replace(['"', "'"], '', APP_NAME) ?></span>
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
                        <?php if (!empty($badges['proveedores'])): ?>
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
                        <?php if (!empty($badges['solicitudes'])): ?>
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
                    <button class="btn btn-glass" id="toggleSidebar">
                        <i class="bi bi-list"></i>
                    </button>
                </div>

                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <!-- Theme Toggle -->
                    <button class="theme-toggle" aria-label="Cambiar tema">
                        <i class="bi bi-sun-fill theme-toggle-icon sun"></i>
                        <i class="bi bi-moon-fill theme-toggle-icon moon"></i>
                    </button>

                    <!-- Divider -->
                    <div style="width: 1px; height: 24px; background: var(--glass-border);"></div>

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
                        <span><?= e($flash) ?></span>
                        <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
                    </div>
                <?php endif; ?>

                <?php if ($flash = getFlash('error')): ?>
                    <div class="alert alert-danger" data-auto-close>
                        <i class="bi bi-exclamation-circle"></i>
                        <span><?= e($flash) ?></span>
                        <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
                    </div>
                <?php endif; ?>

                <?php if ($flash = getFlash('warning')): ?>
                    <div class="alert alert-warning" data-auto-close>
                        <i class="bi bi-exclamation-triangle"></i>
                        <span><?= e($flash) ?></span>
                        <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
                    </div>
                <?php endif; ?>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const toggleBtn = document.getElementById('toggleSidebar');
                        const sidebar   = document.getElementById('sidebar');

                        toggleBtn?.addEventListener('click', function () {
                            sidebar.classList.toggle('active');
                        });

                        document.addEventListener('click', function (e) {
                            if (window.innerWidth <= 992) {
                                if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                                    sidebar.classList.remove('active');
                                }
                            }
                        });

                        // Auto-cerrar alertas
                        document.querySelectorAll('[data-auto-close]').forEach(alert => {
                            setTimeout(() => alert.remove(), 5000);
                        });
                    });
                </script>
