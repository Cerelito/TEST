<?php
/**
 * Script de prueba de envío de correo SMTP
 */
$root = __DIR__ . '/';
define('ROOT_PATH', $root);

// Simular BASE_URL para el Mailer
define('BASE_URL', 'http://localhost');

require_once $root . 'core/Mailer.php';

$mailer = new Mailer();
$testEmail = 'ecruz@urbanopark.com';

echo "Intentando enviar correo de prueba a {$testEmail}...\n";

try {
    $result = $mailer->send(
        $testEmail,
        "Prueba de SMTP - OneGantt",
        "<h1>¡Funciona!</h1><p>Este es un correo de prueba enviado desde el sistema OneGantt usando SMTP de Google Workspace.</p>",
        "¡Funciona! Este es un correo de prueba enviado desde el sistema OneGantt usando SMTP de Google Workspace."
    );

    if ($result) {
        echo "SUCCESS: Correo enviado correctamente.\n";
    } else {
        echo "ERROR: No se pudo enviar el correo. Revisa logs/app.log o error_log.\n";
    }
} catch (Exception $e) {
    echo "EXCEPCIÓN: " . $e->getMessage() . "\n";
}
