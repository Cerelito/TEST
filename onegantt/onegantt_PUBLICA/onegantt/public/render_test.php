<?php
/**
 * RENDER_TEST.PHP — Verifica si main.php renderiza correctamente
 * BORRAR después de resolver el problema.
 */
$rootPath = getenv('ROOT_PATH') ?: '/home1/erickedu/Controladores-Apotema/onegantt/';
define('ROOT_PATH', rtrim($rootPath, '/') . '/');
require_once ROOT_PATH . 'config/app.php';

// Cargar clases necesarias
foreach (['core/Router.php', 'views/layouts/icons.php'] as $file) {
    $full = ROOT_PATH . $file;
    echo "<!-- Loading: $full (" . (file_exists($full) ? 'EXISTS' : 'MISSING') . ") -->\n";
    if (file_exists($full)) require_once $full;
}

// Simular un usuario logueado sin sesión
class MockAuth {
    public function userName(): string { return 'Administrador (TEST)'; }
    public function rol(): string { return 'admin'; }
    public function isGestor(): bool { return true; }
    public function isAdmin(): bool { return true; }
}
$auth = new MockAuth();

// Simular sesión para Router::flash()
session_name('og_test_session');
session_start();

// Capturar el contenido de prueba
ob_start();
?>
<div class="og-page">
  <div class="og-page__header">
    <div>
      <h1 class="og-page__title">Dashboard — TEST RENDERIZADO</h1>
      <p class="og-page__sub">Esta es una prueba de que main.php funciona.</p>
    </div>
  </div>
  <div class="og-metrics">
    <div class="og-metric"><span class="og-metric__label">Proyectos activos</span><span class="og-metric__val">5</span></div>
    <div class="og-metric og-metric--warn"><span class="og-metric__label">Vencen hoy</span><span class="og-metric__val">2</span></div>
  </div>
</div>
<?php
$content  = ob_get_clean();
$pageTitle = 'Dashboard TEST';

// Intentar incluir main.php
$mainPath = ROOT_PATH . 'views/layouts/main.php';
echo "<!-- main.php path: $mainPath (" . (file_exists($mainPath) ? 'EXISTS' : 'MISSING') . ") -->\n";

if (file_exists($mainPath)) {
    include $mainPath;
} else {
    echo "<h1 style='color:red'>ERROR: No se encuentra main.php en: $mainPath</h1>";
    echo "<p>BASE_URL = " . BASE_URL . "</p>";
    echo "<p>ROOT_PATH = " . ROOT_PATH . "</p>";
}
