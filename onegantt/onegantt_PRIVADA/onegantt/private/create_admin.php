<?php
/**
 * Script para crear el usuario administrador inicial.
 * Ejecutar desde la línea de comandos o vía web una sola vez.
 */

// Configurar ruta raíz
$root = __DIR__ . '/';
define('ROOT_PATH', $root);

// Cargar núcleo
require_once $root . 'core/Database.php';

$email = 'ecruz@urbanopark.com';
$nombre = 'Erick Cruz';
$password = 'Syndulla25@';
$rol_id = 1; // Administrador

try {
    $db = Database::getInstance();
    
    // Verificar si ya existe
    $existing = $db->fetchOne("SELECT id FROM users WHERE email = :email", [':email' => $email]);
    
    if ($existing) {
        die("ERROR: El usuario {$email} ya existe en la base de datos.\n");
    }
    
    $hash = password_hash($password, PASSWORD_BCRYPT);
    
    $db->insert(
        "INSERT INTO users (rol_id, nombre, email, password) VALUES (:rol, :nombre, :email, :pass)",
        [
            ':rol'    => $rol_id,
            ':nombre' => $nombre,
            ':email'  => $email,
            ':pass'   => $hash
        ]
    );
    
    echo "SUCCESS: Usuario administrador {$email} creado correctamente.\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
