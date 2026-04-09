<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$root = '/home1/erickedu/Controladores-Apotema/onegantt/';
$cfg = require $root . 'config/database.php';

echo "<h1>Diagnóstico Quirúrgico de MySQL</h1>";
echo "<b>Configuración cargada:</b><br>";
echo "Host: " . $cfg['host'] . "<br>";
echo "DB: [" . $cfg['dbname'] . "] (Longitud: " . strlen($cfg['dbname']) . ")<br>";
echo "User: [" . $cfg['user'] . "] (Longitud: " . strlen($cfg['user']) . ")<br><br>";

try {
    $pdo = new PDO("mysql:host=" . $cfg['host'] . ";charset=utf8mb4", $cfg['user'], $cfg['pass']);
    echo "<b style='color:green;'>✔️ Login exitoso.</b><br><br>";
    
    // 1. Quién soy?
    $stmt = $pdo->query("SELECT USER(), CURRENT_USER()");
    $users = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<b>MySQL te identifica como:</b> " . $users['USER()'] . "<br>";
    echo "<b>Permisos aplicados para:</b> " . $users['CURRENT_USER()'] . "<br><br>";

    // 2. Qué bases de datos puedo ver?
    echo "<b>Bases de datos que puedes ver:</b><ul>";
    $stmt = $pdo->query("SHOW DATABASES");
    $found = false;
    while ($row = $stmt->fetchColumn()) {
        $style = ($row == $cfg['dbname']) ? "style='color:green; font-weight:bold;'" : "";
        echo "<li $style>$row</li>";
        if ($row == $cfg['dbname']) $found = true;
    }
    echo "</ul>";

    if (!$found) {
        echo "<b style='color:red;'>⚠️ ATENCIÓN: La base de datos '".$cfg['dbname']."' NO aparece en la lista de arriba.</b><br>";
        echo "Esto confirma que MySQL no te está dando acceso a ella aunque cPanel diga que sí.<br>";
    } else {
        echo "<b style='color:green;'>✔️ La base de datos sí aparece en la lista. Intentando entrar...</b><br>";
        try {
            $pdo->exec("USE `" . $cfg['dbname'] . "`");
            echo "<b style='color:green;'>✔️ ¡CONEXIÓN TOTAL EXITOSA!</b> El sistema ya debería funcionar.<br>";
        } catch (Exception $e) {
            echo "<b style='color:red;'>❌ Error final al entrar (USE):</b> " . $e->getMessage() . "<br>";
        }
    }

} catch (PDOException $e) {
    echo "<b style='color:red;'>❌ Error de Conexión:</b> " . $e->getMessage();
}
