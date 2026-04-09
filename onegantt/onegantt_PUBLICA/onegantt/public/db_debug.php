<?php
/**
 * Herramienta de diagnóstico de base de datos (Versión Pública)
 */

// Intentar detectar la ruta raíz del servidor
$root = '/home1/erickedu/Controladores-Apotema/onegantt/';

if (!file_exists($root . 'config/database.php')) {
    die("<h1>❌ Error Crítico</h1> No se encuentra el archivo de configuración en: <b>$root</b><br>Verifica que la ruta sea correcta.");
}

$cfg = require $root . 'config/database.php';

echo "<h1>Diagnóstico de Base de Datos</h1>";
echo "Intentando conectar con:<br>";
echo "Base de datos: " . $cfg['dbname'] . "<br>";
echo "Usuario: " . $cfg['user'] . "<br>";

try {
    $dsn = "mysql:host={$cfg['host']};dbname={$cfg['dbname']};charset={$cfg['charset']}";
    $pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "<h2 style='color:green;'>✔️ ¡CONEXIÓN EXITOSA!</h2> El sistema puede comunicarse con la base de datos sin problemas.";
} catch (PDOException $e) {
    echo "<h2 style='color:red;'>❌ ERROR DE CONEXIÓN:</h2>";
    echo "<pre style='background:#f4f4f4;padding:15px;border-radius:5px;border:1px solid #ccc;'>" . $e->getMessage() . "</pre>";
    
    if (strpos($e->getMessage(), 'Access denied') !== false) {
        echo "<p style='font-size:1.1rem;'><b>Posible solución:</b><br> 
        1. Ve a tu cPanel -> <b>Bases de Datos MySQL</b>.<br>
        2. Baja hasta <b>'Añadir usuario a la base de datos'</b>.<br>
        3. Selecciona el usuario <b>" . $cfg['user'] . "</b> y la base de datos <b>" . $cfg['dbname'] . "</b>.<br>
        4. Haz clic en <b>Añadir</b> y marca <b>TODOS LOS PRIVILEGIOS</b>.<br>
        5. Si ya lo hiciste, verifica que la contraseña sea exactamente la que configuramos.</p>";
    }
}
