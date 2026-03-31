<?php
/**
 * Main Application Layout - EK Accesos
 * Uses $title, $content variables set by views
 */
$user = currentUser();
$initials = '';
if ($user) {
    $parts = explode(' ', $user['nombre']);
    $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
}
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0a0e1a">
    <title><?php echo htmlspecialchars($title ?? 'EK Accesos'); ?> | EK Accesos</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/glass.css">
    <style>
        :root {
            --bg-primary: #0a0e1a;
            --bg-secondary: #0f1422;
            --glass-bg: rgba(255,255,255,0.06);
            --glass-border: rgba(255,255,255,0.12);
            --glass-blur: blur(20px);
            --accent: #6366f1;
            --accent-hover: #4f46e5;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --sidebar-width: 260px;
            --topbar-height: 64px;
            --font: -apple-system, 'SF Pro Display', 'Helvetica Neue', Arial, sans-serif;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body {
            height: 100%;
            background: var(--bg-primary);
            color: var(--text-primary);
            font-family: var(--font);
            -webkit-font-smoothing: antialiased;
        }
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: rgba(10,14,26,0.95);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border-right: 1px solid var(--glass-border);
            display: flex;
            flex-direction: column;
            z-index: 100;
            overflow-y: auto;
        }
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 2px; }
        .sidebar-logo {
            padding: 24px 20px 20px;
            border-bottom: 1px solid var(--glass-border);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .logo-mark {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6, #06b6d4);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 800;
            color: white;
            letter-spacing: -1px;
            box-shadow: 0 4px 20px rgba(99,102,241,0.4);
        }
        .logo-text { display: flex; flex-direction: column; }
        .logo-text span:first-child {
            font-size: 16px; font-weight: 700;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.2;
        }
        .logo-text span:last-child {
            font-size: 11px; color: var(--text-muted);
            text-transform: uppercase; letter-spacing: 1px;
        }
        .nav-section { padding: 12px 0; }
        .nav-section-label {
            padding: 8px 20px 4px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--text-muted);
        }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 20px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            border-radius: 10px;
            margin: 2px 10px;
            transition: all 0.2s ease;
            position: relative;
        }
        .nav-link:hover {
            background: rgba(255,255,255,0.06);
            color: var(--text-primary);
        }
        .nav-link.active {
            background: rgba(99,102,241,0.15);
            color: #818cf8;
            border: 1px solid rgba(99,102,241,0.25);
        }
        .nav-link.active::before {
            content: '';
            position: absolute;
            left: -10px; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 20px;
            background: var(--accent);
            border-radius: 0 2px 2px 0;
        }
        .nav-icon {
            width: 20px; height: 20px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .nav-icon svg { width: 18px; height: 18px; }
        .sidebar-footer {
            margin-top: auto;
            padding: 16px;
            border-top: 1px solid var(--glass-border);
        }
        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-radius: 12px;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--glass-border);
            margin-bottom: 10px;
        }
        .user-avatar {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
        }
        .user-info { flex: 1; min-width: 0; }
        .user-name {
            font-size: 13px; font-weight: 600;
            color: var(--text-primary);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .user-role {
            font-size: 11px; color: var(--text-muted);
            text-transform: capitalize;
        }
        .logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 9px;
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.2);
            border-radius: 10px;
            color: #ef4444;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
            font-family: var(--font);
        }
        .logout-btn:hover { background: rgba(239,68,68,0.2); }
        /* Topbar */
        .topbar {
            position: fixed;
            top: 0; left: var(--sidebar-width); right: 0;
            height: var(--topbar-height);
            background: rgba(10,14,26,0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
            display: flex;
            align-items: center;
            padding: 0 24px;
            z-index: 99;
            gap: 16px;
        }
        .hamburger {
            display: none;
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
        }
        .hamburger:hover { background: rgba(255,255,255,0.06); }
        .topbar-title {
            flex: 1;
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
        }
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .notification-badge {
            position: relative;
            display: inline-flex;
        }
        .notif-btn {
            width: 38px; height: 38px;
            border-radius: 10px;
            background: rgba(255,255,255,0.06);
            border: 1px solid var(--glass-border);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.2s;
        }
        .notif-btn:hover { background: rgba(255,255,255,0.1); color: var(--text-primary); }
        .badge-count {
            position: absolute;
            top: -6px; right: -6px;
            background: var(--danger);
            color: white;
            font-size: 10px; font-weight: 700;
            min-width: 18px; height: 18px;
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            padding: 0 4px;
            border: 2px solid var(--bg-primary);
        }
        .topbar-user {
            display: flex; align-items: center; gap: 8px;
            padding: 6px 10px;
            border-radius: 10px;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--glass-border);
        }
        .topbar-avatar {
            width: 30px; height: 30px;
            border-radius: 8px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 700; color: white;
        }
        .topbar-user-name { font-size: 13px; font-weight: 500; color: var(--text-primary); }
        /* Main content */
        .page-wrapper {
            margin-left: var(--sidebar-width);
            padding-top: var(--topbar-height);
            min-height: 100vh;
        }
        .main-content {
            padding: 28px 28px;
            max-width: 1400px;
        }
        /* Flash messages */
        .flash-container {
            position: fixed;
            top: calc(var(--topbar-height) + 16px);
            right: 20px;
            z-index: 200;
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-width: 380px;
        }
        .flash-message {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 14px;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid;
            animation: slideInRight 0.3s ease, fadeOut 0.4s ease 4.6s forwards;
            cursor: pointer;
        }
        .flash-success { background: rgba(16,185,129,0.12); border-color: rgba(16,185,129,0.3); }
        .flash-error   { background: rgba(239,68,68,0.12); border-color: rgba(239,68,68,0.3); }
        .flash-warning { background: rgba(245,158,11,0.12); border-color: rgba(245,158,11,0.3); }
        .flash-info    { background: rgba(99,102,241,0.12); border-color: rgba(99,102,241,0.3); }
        .flash-icon { font-size: 18px; flex-shrink: 0; line-height: 1.4; }
        .flash-text { flex: 1; }
        .flash-title { font-size: 13px; font-weight: 600; color: var(--text-primary); }
        .flash-body  { font-size: 12px; color: var(--text-secondary); margin-top: 2px; }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(30px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes fadeOut {
            from { opacity: 1; }
            to   { opacity: 0; pointer-events: none; }
        }
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
            .sidebar.open { transform: translateX(0); }
            .page-wrapper { margin-left: 0; }
            .topbar { left: 0; }
            .hamburger { display: flex; }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<nav class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="logo-mark">EK</div>
        <div class="logo-text">
            <span>EK Accesos</span>
            <span>Sistema de Gestión</span>
        </div>
    </div>

    <div class="nav-section">
        <a href="<?php echo BASE_URL; ?>/dashboard" class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/dashboard') !== false) ? 'active' : ''; ?>">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            </span>
            Dashboard
        </a>
    </div>

    <div class="nav-section">
        <div class="nav-section-label">Empleados</div>
        <a href="<?php echo BASE_URL; ?>/empleados" class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/empleados') !== false) ? 'active' : ''; ?>">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </span>
            Empleados
        </a>
        <a href="<?php echo BASE_URL; ?>/requisitores" class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/requisitores') !== false) ? 'active' : ''; ?>">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            </span>
            Requisitores
        </a>
        <a href="<?php echo BASE_URL; ?>/compradores" class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/compradores') !== false) ? 'active' : ''; ?>">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            </span>
            Compradores
        </a>
    </div>

    <div class="nav-section">
        <div class="nav-section-label">Configuración</div>
        <a href="<?php echo BASE_URL; ?>/programa-nivel" class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/programa-nivel') !== false) ? 'active' : ''; ?>">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            </span>
            Programa Nivel
        </a>
        <a href="<?php echo BASE_URL; ?>/centros-costo" class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/centros-costo') !== false) ? 'active' : ''; ?>">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
            </span>
            Centros de Costo
        </a>
    </div>

    <div class="nav-section">
        <div class="nav-section-label">Sistema</div>
        <?php if (isRole('admin') || isRole('superadmin')): ?>
        <a href="<?php echo BASE_URL; ?>/usuarios" class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/usuarios') !== false) ? 'active' : ''; ?>">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </span>
            Usuarios
        </a>
        <?php endif; ?>
        <a href="<?php echo BASE_URL; ?>/organigrama" class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/organigrama') !== false) ? 'active' : ''; ?>">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="8" y="2" width="8" height="4" rx="1"/><rect x="2" y="18" width="6" height="4" rx="1"/><rect x="9" y="18" width="6" height="4" rx="1"/><rect x="16" y="18" width="6" height="4" rx="1"/><line x1="12" y1="6" x2="12" y2="14"/><line x1="5" y1="20" x2="5" y2="14"/><line x1="12" y1="20" x2="12" y2="14"/><line x1="19" y1="20" x2="19" y2="14"/><line x1="5" y1="14" x2="19" y2="14"/></svg>
            </span>
            Organigrama
        </a>
    </div>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar"><?php echo htmlspecialchars($initials); ?></div>
            <div class="user-info">
                <div class="user-name"><?php echo htmlspecialchars($user['nombre'] ?? 'Usuario'); ?></div>
                <div class="user-role"><?php echo htmlspecialchars($user['rol'] ?? ''); ?></div>
            </div>
        </div>
        <a href="<?php echo BASE_URL; ?>/logout" class="logout-btn">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Cerrar Sesión
        </a>
    </div>
</nav>

<!-- Topbar -->
<header class="topbar">
    <button class="hamburger" id="hamburgerBtn" aria-label="Toggle menu">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
    </button>
    <h1 class="topbar-title"><?php echo htmlspecialchars($title ?? 'Dashboard'); ?></h1>
    <div class="topbar-right">
        <?php
        $pendingCount = 0;
        if (isset($stats['usuarios_pendientes'])) $pendingCount = (int)$stats['usuarios_pendientes'];
        ?>
        <div class="notification-badge">
            <a href="<?php echo BASE_URL; ?>/usuarios?filter=pendiente" class="notif-btn" title="Aprobaciones pendientes">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            </a>
            <?php if ($pendingCount > 0): ?>
            <span class="badge-count"><?php echo $pendingCount; ?></span>
            <?php endif; ?>
        </div>
        <div class="topbar-user">
            <div class="topbar-avatar"><?php echo htmlspecialchars($initials); ?></div>
            <span class="topbar-user-name"><?php echo htmlspecialchars($user['nombre'] ?? ''); ?></span>
        </div>
    </div>
</header>

<!-- Flash Messages -->
<?php if (!empty($flash)): ?>
<div class="flash-container" id="flashContainer">
    <?php foreach ($flash as $f): ?>
        <?php $ftype = $f['type'] ?? 'info'; $fmsg = $f['message'] ?? ''; ?>
        <div class="flash-message flash-<?php echo htmlspecialchars($ftype); ?>" onclick="this.remove()">
            <div class="flash-icon">
                <?php $icons = ['success'=>'✅','error'=>'❌','warning'=>'⚠️','info'=>'ℹ️']; echo $icons[$ftype] ?? 'ℹ️'; ?>
            </div>
            <div class="flash-text">
                <div class="flash-title"><?php echo ucfirst($ftype); ?></div>
                <div class="flash-body"><?php echo $fmsg; ?></div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Main Content -->
<div class="page-wrapper">
    <main class="main-content">
        <?php echo $content ?? ''; ?>
    </main>
</div>

<script src="<?php echo BASE_URL; ?>/public/js/app.js"></script>
<script>
(function() {
    // Hamburger menu
    var ham = document.getElementById('hamburgerBtn');
    var sidebar = document.getElementById('sidebar');
    if (ham && sidebar) {
        ham.addEventListener('click', function() {
            sidebar.classList.toggle('open');
        });
        document.addEventListener('click', function(e) {
            if (!sidebar.contains(e.target) && !ham.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });
    }
    // Auto-dismiss flash messages
    var flashes = document.querySelectorAll('.flash-message');
    flashes.forEach(function(el) {
        setTimeout(function() { el.remove(); }, 5000);
    });
})();
</script>
</body>
</html>
