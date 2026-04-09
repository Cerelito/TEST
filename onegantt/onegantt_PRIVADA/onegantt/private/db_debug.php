<?php
/**
 * Herramienta de diagnóstico de base de datos
 */
$root = __DIR__ . '/';
define('ROOT_PATH', $root);

$cfg = require $root . 'config/database.php';

echo "<h1>Diagnóstico de Base de Datos</h1>";
echo "Intentando conectar con:<br>";
echo "Host: " . $cfg['host'] . "<br>";
echo "Base de datos: " . $cfg['dbname'] . "<br>";
echo "Usuario: " . $cfg['user'] . "<br>";
echo "Contraseña: " . substr($cfg['pass'], 0, 3) . "..." . "<br><br>";

try {
    $dsn = "mysql:host={$cfg['host']};dbname={$cfg['dbname']};charset={$cfg['charset']}";
    $pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "<b style='color:green;'>✔️ ¡CONEXIÓN EXITOSA!</b> El sistema puede comunicarse con la base de datos.";
} catch (PDOException $e) {
    echo "<b style='color:red;'>❌ ERROR DE CONEXIÓN:</b><br>";
    echo "<pre>" . $e->getMessage() . "</pre>";
    
    if (strpos($e->getMessage(), 'Access denied') !== false) {
        echo "<p><b>Posible solución:</b> Revisa que el usuario tenga privilegios sobre la base de datos en cPanel o que la contraseña sea correcta.</p>";
    }
    if (strpos($e->getMessage(), 'Unknown database') !== false) {
        echo "<p><b>Posible solución:</b> El nombre de la base de datos es incorrecto o no ha sido creada.</p>";
    }
}
