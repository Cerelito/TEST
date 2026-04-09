<?php
/**
 * FILE_CHECK.PHP — Verifica las versiones de archivos en el servidor
 * BORRAR después de resolver el problema.
 */
$rootPath = getenv('ROOT_PATH') ?: '/home1/erickedu/Controladores-Apotema/onegantt/';
define('ROOT_PATH', rtrim($rootPath, '/') . '/');

echo "<pre style='font-family:monospace;font-size:12px;background:#111;color:#eee;padding:20px'>";
echo "ROOT_PATH = " . ROOT_PATH . "\n\n";

// ── Leer las primeras líneas de los archivos críticos del servidor ──
$filesToCheck = [
    'views/dashboard/index.php',
    'views/layouts/main.php',
    'controllers/DashboardController.php',
    'core/Auth.php',
    'core/Database.php',
];

foreach ($filesToCheck as $file) {
    $fullPath = ROOT_PATH . $file;
    echo "═══════════════════════════════════════\n";
    echo "ARCHIVO: $file\n";
    echo "RUTA: $fullPath\n";
    
    if (!file_exists($fullPath)) {
        echo "❌ NO EXISTE EN EL SERVIDOR\n";
    } else {
        echo "✅ EXISTE — Primeras 20 líneas:\n";
        $lines = file($fullPath);
        $count = min(20, count($lines));
        for ($i = 0; $i < $count; $i++) {
            echo ($i+1) . ": " . htmlspecialchars($lines[$i]);
        }
        echo "... (Total: " . count($lines) . " líneas)\n";
    }
    echo "\n";
}

// ── Verificar qué tablas existen en la BD ──────────────────────────
echo "═══════════════════════════════════════\n";
echo "TABLAS EN LA BASE DE DATOS:\n";
$cfg = require ROOT_PATH . 'config/database.php';
try {
    $pdo = new PDO("mysql:host={$cfg['host']};dbname={$cfg['dbname']}", $cfg['user'], $cfg['pass']);
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo implode(', ', $tables) . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "</pre>";
