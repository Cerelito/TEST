<!DOCTYPE html>
<html lang="es" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesion |
        <?= APP_NAME ?>
    </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ============================================
           EKUORA LOGIN - ULTRA GLASS PANTONE
        ============================================ */
        :root {
            --ek-navy: #002B49;
            --ek-orange: #ED8B00;
            --ek-sky: #7A99AC;
            --ek-slate: #425563;
            --ek-navy-light: #003d66;
            --ek-orange-light: #ff9d1a;
            --ek-sky-light: #9bb5c4;
            --ek-sky-pale: #e8eff3;
            --ek-green: #22c55e;

            --glass-bg: rgba(255, 255, 255, 0.9);
            --glass-border: rgba(122, 153, 172, 0.3);
            --glass-shadow: 0 8px 32px rgba(0, 43, 73, 0.15);
            --glass-blur: blur(20px);

            --radius-sm: 12px;
            --radius-md: 16px;
            --radius-lg: 24px;
            --radius-xl: 32px;
            --radius-full: 9999px;

            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: linear-gradient(135deg, var(--ek-navy) 0%, var(--ek-navy-light) 50%, var(--ek-slate) 100%);
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 80%;
            height: 200%;
            background: radial-gradient(circle, rgba(237, 139, 0, 0.15) 0%, transparent 50%);
            animation: floatBg 10s ease-in-out infinite;
        }

        body::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -20%;
            width: 60%;
            height: 150%;
            background: radial-gradient(circle, rgba(122, 153, 172, 0.1) 0%, transparent 50%);
            animation: floatBg 12s ease-in-out infinite reverse;
        }

        @keyframes floatBg {

            0%,
            100% {
                transform: translate(0, 0) rotate(0deg);
            }

            50% {
                transform: translate(30px, -30px) rotate(5deg);
            }
        }

        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
        }

        .login-card {
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius-xl);
            padding: 2.5rem;
            box-shadow: var(--glass-shadow), 0 20px 60px rgba(0, 43, 73, 0.2);
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--ek-orange), var(--ek-orange-light), var(--ek-sky));
        }

        /* Header */
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--ek-navy), var(--ek-navy-light));
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            box-shadow: 0 10px 30px rgba(0, 43, 73, 0.3);
        }

        .login-logo i {
            font-size: 2.25rem;
            color: var(--ek-orange);
        }

        .login-header h1 {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--ek-navy);
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: var(--ek-slate);
            font-size: 0.95rem;
        }

        /* Alertas */
        .ek-alert {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 1rem 1.25rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .ek-alert i {
            font-size: 1.25rem;
        }

        .ek-alert.success {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: var(--ek-green);
        }

        .ek-alert.danger {
            background: rgba(244, 63, 94, 0.1);
            border: 1px solid rgba(244, 63, 94, 0.3);
            color: #f43f5e;
        }

        /* Formulario */
        .ek-form-group {
            margin-bottom: 1.5rem;
        }

        .ek-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--ek-navy);
            margin-bottom: 0.5rem;
        }

        .ek-label i {
            color: var(--ek-sky);
        }

        .ek-input {
            width: 100%;
            padding: 0.9rem 1rem;
            border: 1px solid var(--glass-border);
            border-radius: var(--radius-md);
            background: white;
            color: var(--ek-navy);
            font-size: 1rem;
            font-family: inherit;
            transition: var(--transition);
        }

        .ek-input::placeholder {
            color: var(--ek-sky);
        }

        .ek-input:focus {
            outline: none;
            border-color: var(--ek-orange);
            box-shadow: 0 0 0 3px rgba(237, 139, 0, 0.15);
        }

        /* Boton */
        .ek-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 1rem 2rem;
            border: none;
            border-radius: var(--radius-full);
            font-family: inherit;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .ek-btn-primary {
            background: linear-gradient(135deg, var(--ek-orange), var(--ek-orange-light));
            color: white;
            box-shadow: 0 8px 25px rgba(237, 139, 0, 0.35);
        }

        .ek-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(237, 139, 0, 0.45);
        }

        .ek-btn-primary:active {
            transform: translateY(0);
        }

        /* Enlaces */
        .login-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: var(--ek-navy);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: var(--transition);
        }

        .login-link:hover {
            color: var(--ek-orange);
        }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--glass-border);
        }

        .login-footer small {
            color: var(--ek-slate);
            font-size: 0.85rem;
        }

        .login-footer a {
            color: var(--ek-orange);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .login-footer a:hover {
            color: var(--ek-orange-light);
        }

        /* Theme Toggle */
        .theme-toggle {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            width: 48px;
            height: 48px;
            border: none;
            border-radius: var(--radius-md);
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            transition: var(--transition);
            z-index: 100;
        }

        .theme-toggle:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: scale(1.05);
        }

        .theme-toggle .moon {
            display: none;
        }

        [data-theme="dark"] .theme-toggle .sun {
            display: none;
        }

        [data-theme="dark"] .theme-toggle .moon {
            display: block;
        }

        /* Dark Mode */
        [data-theme="dark"] body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
        }

        [data-theme="dark"] .login-card {
            background: rgba(30, 41, 59, 0.9);
            border-color: rgba(71, 85, 105, 0.5);
        }

        [data-theme="dark"] .login-header h1 {
            color: white;
        }

        [data-theme="dark"] .login-header p {
            color: var(--ek-sky-light);
        }

        [data-theme="dark"] .ek-label {
            color: white;
        }

        [data-theme="dark"] .ek-input {
            background: rgba(51, 65, 85, 0.5);
            border-color: rgba(71, 85, 105, 0.5);
            color: white;
        }

        [data-theme="dark"] .login-link {
            color: var(--ek-sky-light);
        }

        [data-theme="dark"] .login-footer small {
            color: var(--ek-sky-light);
        }

        /* Animacion */
        .ek-fade-up {
            animation: fadeUp 0.6s ease forwards;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 480px) {
            body {
                padding: 1rem;
            }

            .login-card {
                padding: 2rem 1.5rem;
            }

            .login-header h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <!-- Toggle Tema -->
    <button class="theme-toggle" id="themeToggle" aria-label="Cambiar tema">
        <i class="bi bi-sun-fill sun"></i>
        <i class="bi bi-moon-fill moon"></i>
    </button>

    <div class="login-container ek-fade-up">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
                <h1>
                    <?= APP_NAME ?>
                </h1>
                <p>Gestion de Productos</p>
            </div>

            <?php if ($flash = getFlash('success')): ?>
                <div class="ek-alert success">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>
                        <?= e($flash) ?>
                    </span>
                </div>
            <?php endif; ?>

            <?php if ($flash = getFlash('error')): ?>
                <div class="ek-alert danger">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <span>
                        <?= e($flash) ?>
                    </span>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>auth/procesar" method="POST">
                <input type="hidden" name="csrf_token" value="<?= generarToken() ?>">

                <div class="ek-form-group">
                    <label class="ek-label" for="username">
                        <i class="bi bi-person"></i> Usuario o Email
                    </label>
                    <input type="text" id="username" name="username" class="ek-input" placeholder="Ingresa tu usuario"
                        required autocomplete="username">
                </div>

                <div class="ek-form-group">
                    <label class="ek-label" for="password">
                        <i class="bi bi-lock"></i> Contrasena
                    </label>
                    <input type="password" id="password" name="password" class="ek-input"
                        placeholder="Ingresa tu contrasena" required autocomplete="current-password">
                </div>

                <button type="submit" class="ek-btn ek-btn-primary">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Iniciar Sesion
                </button>
            </form>

            <a href="<?= BASE_URL ?>solicitar-recuperacion" class="login-link">
                ¿Olvidaste tu contrasena?
            </a>

            <div class="login-footer">
                <small>
                    Desarrollado por
                    <a href="https://www.apotemaone.com" target="_blank">Apotema One</a>
                </small>
            </div>
        </div>
    </div>

    <script>
        const themeToggle = document.getElementById('themeToggle');
        const html = document.documentElement;

        // Cargar tema guardado
        const savedTheme = localStorage.getItem('theme') || 'light';
        html.setAttribute('data-theme', savedTheme);

        themeToggle.addEventListener('click', () => {
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        });
    </script>
</body>

</html>