<?php
/**
 * Auth Layout - EK Accesos
 * Full-page layout for login/recovery pages
 */
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0a0e1a">
    <title><?php echo htmlspecialchars($title ?? 'Acceso'); ?> | <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/glass.css">
    <style>
        :root {
            --bg-primary: #0a0e1a;
            --glass-bg: rgba(255,255,255,0.06);
            --glass-border: rgba(255,255,255,0.12);
            --accent: #6366f1;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --font: -apple-system, 'SF Pro Display', 'Helvetica Neue', Arial, sans-serif;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body {
            min-height: 100%;
            font-family: var(--font);
            -webkit-font-smoothing: antialiased;
            background: var(--bg-primary);
            color: var(--text-primary);
        }
        .auth-bg {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        .auth-bg::before {
            content: '';
            position: fixed;
            inset: 0;
            background: radial-gradient(ellipse at 20% 20%, rgba(99,102,241,0.18) 0%, transparent 50%),
                        radial-gradient(ellipse at 80% 80%, rgba(139,92,246,0.15) 0%, transparent 50%),
                        radial-gradient(ellipse at 50% 50%, rgba(6,182,212,0.08) 0%, transparent 70%);
            animation: bgPulse 8s ease-in-out infinite alternate;
            pointer-events: none;
        }
        @keyframes bgPulse {
            from { opacity: 0.7; transform: scale(1); }
            to   { opacity: 1;   transform: scale(1.05); }
        }
        /* Grid lines decorative */
        .auth-bg::after {
            content: '';
            position: fixed;
            inset: 0;
            background-image: linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none;
        }
        .auth-content { position: relative; z-index: 1; width: 100%; }
    </style>
</head>
<body>
<div class="auth-bg">
    <div class="auth-content">
        <?php if (!empty($flash)): ?>
        <?php foreach ($flash as $f): ?>
            <?php $ftype = $f['type'] ?? 'info'; $fmsg = $f['message'] ?? ''; ?>
            <div id="flashAlert" style="
                max-width: 420px; margin: 0 auto 16px;
                padding: 12px 16px; border-radius: 12px;
                background: <?php echo $ftype === 'error' ? 'rgba(239,68,68,0.12)' : 'rgba(16,185,129,0.12)'; ?>;
                border: 1px solid <?php echo $ftype === 'error' ? 'rgba(239,68,68,0.3)' : 'rgba(16,185,129,0.3)'; ?>;
                color: <?php echo $ftype === 'error' ? '#fca5a5' : '#6ee7b7'; ?>;
                font-size: 13px; text-align: center;
            "><?php echo htmlspecialchars($fmsg); ?></div>
        <?php endforeach; ?>
        <?php endif; ?>
        <?php echo $content ?? ''; ?>
    </div>
</div>
<script>
    setTimeout(function() {
        var f = document.getElementById('flashAlert');
        if (f) f.style.transition = 'opacity 0.5s', f.style.opacity = '0', setTimeout(function(){ f.remove(); }, 500);
    }, 4000);
</script>
</body>
</html>
