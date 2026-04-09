<?php
/**
 * OneGantt — Generador de hash para contraseña admin
 * Ejecutar UNA VEZ en el servidor: php generate_hash.php
 * Luego borrar este archivo.
 */
$password = 'Admin1234!'; // <── Cambia esto antes de ejecutar

$hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

echo "\n=== OneGantt: Hash generado ===\n";
echo "Contraseña : {$password}\n";
echo "Hash       : {$hash}\n\n";
echo "Copia el hash y ejecuta en tu BD:\n";
echo "UPDATE users SET password = '{$hash}' WHERE email = 'erick@apotemaone.com';\n\n";
echo "¡ELIMINA este archivo después de ejecutarlo!\n";
