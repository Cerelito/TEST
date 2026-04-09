<?php
/**
 * STATUS.PHP — Diagnóstico completo del sistema OneGantt
 * BORRAR después de resolver los problemas.
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ── 1. Cargar configuración ──────────────────────────────────────────
$rootPath = getenv('ROOT_PATH') ?: '/home1/erickedu/Controladores-Apotema/onegantt/';
define('ROOT_PATH', rtrim($rootPath, '/') . '/');
require_once ROOT_PATH . 'config/app.php';

// ── 2. Iniciar sesión ────────────────────────────────────────────────
session_name(SESSION_NAME);
session_start();

// ── Estilos mínimos ──────────────────────────────────────────────────
echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Status OneGantt</title>
<style>
  body{font-family:monospace;padding:20px;background:#111;color:#eee}
  h2{color:#60a5fa;margin-top:30px}
  .ok{color:#4ade80} .err{color:#f87171} .warn{color:#fbbf24}
  table{border-collapse:collapse;width:100%;margin-top:10px}
  td,th{border:1px solid #333;padding:6px 10px;text-align:left}
  th{background:#1e3a5f;color:#93c5fd}
  code{background:#1f2937;padding:2px 6px;border-radius:4px;color:#a5f3fc}
  pre{background:#1f2937;padding:10px;border-radius:6px;overflow:auto;font-size:12px}
  .box{background:#1f2937;padding:15px;border-radius:8px;margin:10px 0}
</style></head><body>';

echo '<h1>🔍 Diagnóstico OneGantt</h1>';

// ── SECCIÓN 1: VARIABLES DE ENTORNO ──────────────────────────────────
echo '<h2>1. Configuración del Servidor</h2><div class="box"><table>';
$rows = [
    'ROOT_PATH (PHP)'       => ROOT_PATH,
    'ROOT_PATH (env)'       => getenv('ROOT_PATH') ?: '❌ No está en env, usando valor por defecto',
    'BASE_URL'              => BASE_URL,
    'SCRIPT_FILENAME'       => $_SERVER['SCRIPT_FILENAME'] ?? '?',
    'SCRIPT_NAME'           => $_SERVER['SCRIPT_NAME'] ?? '?',
    'REQUEST_URI'           => $_SERVER['REQUEST_URI'] ?? '?',
    'HTTP_HOST'             => $_SERVER['HTTP_HOST'] ?? '?',
    'HTTPS'                 => $_SERVER['HTTPS'] ?? '(no está — posiblemente http)',
    'PHP Version'           => PHP_VERSION,
    'Document Root'         => $_SERVER['DOCUMENT_ROOT'] ?? '?',
];
echo '<tr><th>Variable</th><th>Valor</th></tr>';
foreach ($rows as $k => $v) {
    echo "<tr><td>$k</td><td><code>" . htmlspecialchars($v) . "</code></td></tr>";
}
echo '</table></div>';

// ── SECCIÓN 2: ARCHIVOS CRÍTICOS ─────────────────────────────────────
echo '<h2>2. Archivos Críticos en el Servidor</h2><div class="box"><table>';
echo '<tr><th>Archivo</th><th>Ruta Esperada</th><th>Existe?</th><th>Permisos</th></tr>';
$criticalFiles = [
    'config/app.php'              => ROOT_PATH . 'config/app.php',
    'config/database.php'         => ROOT_PATH . 'config/database.php',
    'core/Auth.php'               => ROOT_PATH . 'core/Auth.php',
    'core/Database.php'           => ROOT_PATH . 'core/Database.php',
    'core/Router.php'             => ROOT_PATH . 'core/Router.php',
    'views/layouts/main.php'      => ROOT_PATH . 'views/layouts/main.php',
    'views/layouts/icons.php'     => ROOT_PATH . 'views/layouts/icons.php',
    'views/dashboard/index.php'   => ROOT_PATH . 'views/dashboard/index.php',
    'controllers/DashboardController.php' => ROOT_PATH . 'controllers/DashboardController.php',
    'helpers/DateHelper.php'      => ROOT_PATH . 'helpers/DateHelper.php',
];
foreach ($criticalFiles as $name => $path) {
    $exists = file_exists($path);
    $perms = $exists ? substr(sprintf('%o', fileperms($path)), -4) : '-';
    $status = $exists ? '<span class="ok">✅ Sí</span>' : '<span class="err">❌ NO EXISTE</span>';
    echo "<tr><td>$name</td><td><code>" . htmlspecialchars($path) . "</code></td><td>$status</td><td>$perms</td></tr>";
}

// Verificar CSS físico en public/
$cssPhysicalPath = $_SERVER['DOCUMENT_ROOT'] . '/urbano/onegantt/public/assets/css/app.css';
$cssExists = file_exists($cssPhysicalPath);
$cssStatus = $cssExists ? '<span class="ok">✅ Sí</span>' : '<span class="err">❌ NO EXISTE</span>';
echo "<tr><td>CSS (public/assets/css/app.css)</td><td><code>" . htmlspecialchars($cssPhysicalPath) . "</code></td><td>$cssStatus</td><td>-</td></tr>";

echo '</table></div>';

// ── SECCIÓN 3: URL DEL CSS ────────────────────────────────────────────
$cssUrl = BASE_URL . '/assets/css/app.css';
echo '<h2>3. URL del CSS</h2><div class="box">';
echo "URL generada: <code>$cssUrl</code><br><br>";
echo "Prueba directa: <a href='$cssUrl' target='_blank' style='color:#60a5fa'>Haz clic aquí para abrir el CSS</a><br>";
echo "<small class='warn'>Si al hacer clic ves código CSS en el navegador → el CSS carga bien. Si ves una página de error → el problema es el .htaccess.</small>";
echo '</div>';

// ── SECCIÓN 4: SESIÓN ─────────────────────────────────────────────────
echo '<h2>4. Datos de Sesión (SESSION)</h2><div class="box">';
if (empty($_SESSION)) {
    echo '<span class="warn">⚠️ La sesión está VACÍA. El usuario no está logueado o la sesión no persiste.</span>';
} else {
    echo '<span class="ok">✅ Sesión activa.</span><pre>' . htmlspecialchars(print_r($_SESSION, true)) . '</pre>';
}
echo "<br>Session Name: <code>" . session_name() . "</code>";
echo "<br>Session ID: <code>" . session_id() . "</code>";
echo "</div>";

// ── SECCIÓN 5: COOKIES ────────────────────────────────────────────────
echo '<h2>5. Cookies del Navegador</h2><div class="box">';
if (empty($_COOKIE)) {
    echo '<span class="warn">⚠️ No hay cookies. La cookie de sesión no se está enviando.</span>';
} else {
    echo '<pre>' . htmlspecialchars(print_r($_COOKIE, true)) . '</pre>';
}
echo '</div>';

// ── SECCIÓN 6: PRUEBA DE RENDERIZADO HTML ────────────────────────────
echo '<h2>6. Prueba de Carga de CSS</h2><div class="box">';
echo "Si el texto de abajo tiene fondo azul y color blanco, el CSS cargó correctamente:<br><br>";
echo '<link rel="stylesheet" href="' . $cssUrl . '">';
echo '<div style="padding:10px;border:1px dashed #666;margin-top:10px">';
echo '<div class="og-btn">Botón de prueba (debe verse azul/morado con CSS)</div>';
echo '<p style="margin-top:10px">Texto normal (sin clase)</p>';
echo '</div>';
echo '</div>';

echo '</body></html>';
