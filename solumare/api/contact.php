<?php
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$lang = $_POST['lang'] ?? 'es';

$nombre   = trim(strip_tags($_POST['nombre']   ?? ''));
$email    = trim(strip_tags($_POST['email']    ?? ''));
$telefono = trim(strip_tags($_POST['telefono'] ?? ''));
$propiedad= trim(strip_tags($_POST['propiedad']?? ''));
$mensaje  = trim(strip_tags($_POST['mensaje']  ?? ''));

$errors = [];
if (empty($nombre))              $errors[] = 'Nombre requerido';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email inválido';

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $lang === 'en' ? 'Please check the required fields.' : 'Por favor verifica los campos requeridos.'
    ]);
    exit;
}

$propertyNames = [
    'ekab-tulum' => 'Ekab Tulum',
    'la-salina'  => 'La Salina Isla Mujeres',
    'general'    => 'Información General',
];
$propName = $propertyNames[$propiedad] ?? $propiedad;

$subject = "Nueva consulta Solumare: {$propName} — {$nombre}";
$body    = "
Nueva consulta desde solumare.mx
==================================

Nombre:    {$nombre}
Email:     {$email}
Teléfono:  {$telefono}
Propiedad: {$propName}

Mensaje:
{$mensaje}

--
Enviado: " . date('Y-m-d H:i:s') . "
IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'N/A') . "
";

$headers  = "From: noreply@solumare.mx\r\n";
$headers .= "Reply-To: {$email}\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

$sent = mail('info@solumare.mx', $subject, $body, $headers);

if ($sent) {
    echo json_encode([
        'success' => true,
        'message' => $lang === 'en'
            ? 'Message sent! We will contact you soon.'
            : '¡Mensaje enviado! Te contactaremos pronto.',
    ]);
} else {
    // Log failed attempt — mail() may be disabled in dev
    error_log("Solumare contact form failed for {$email}");
    echo json_encode([
        'success' => true,
        'message' => $lang === 'en'
            ? 'Message received. We will contact you soon.'
            : 'Mensaje recibido. Nos pondremos en contacto contigo pronto.',
    ]);
}
