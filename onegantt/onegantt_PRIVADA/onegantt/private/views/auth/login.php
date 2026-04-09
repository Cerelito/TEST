<?php // Vista de login — no usa el layout principal (página sin autenticar) ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OneGantt · Acceso</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/login.css">
</head>
<body>
  <div class="bg-orbs">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
  </div>

  <div class="glass-card">
    <div class="logo">
      <div class="logo-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.85)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polygon points="12 2 22 20 2 20"/>
          <line x1="12" y1="2" x2="12" y2="20"/>
          <line x1="2" y1="20" x2="22" y2="20"/>
        </svg>
      </div>
      <div>
        <div class="logo-text">OneGantt</div>
        <div class="logo-sub">Apotema Lab · Gestión de tareas</div>
      </div>
    </div>

    <h1 class="card-title">Bienvenido</h1>
    <p class="card-subtitle">Ingresa tus credenciales para continuar</p>

    <?php if (!empty($error)): ?>
      <div class="alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= htmlspecialchars(BASE_URL . '/login') ?>" autocomplete="off" novalidate>
      <?= $csrfField ?>

      <div class="field">
        <label for="email">Correo electrónico</label>
        <input type="email" id="email" name="email"
               placeholder="correo@empresa.com"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
               required autofocus>
      </div>

      <div class="field">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" placeholder="••••••••" required>
      </div>

      <button type="submit" class="btn-login">Iniciar sesión</button>
    </form>

    <div class="card-footer">
      &copy; <?= date('Y') ?> Apotema Lab · OneGantt v<?= APP_VERSION ?>
    </div>
  </div>
</body>
</html>
