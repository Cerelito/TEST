<?php
// assets.php - Proxy para servir imágenes privadas

// Verificar autenticación antes de servir cualquier archivo privado
session_start();
if (empty($_SESSION['usuario']['id'])) {
    http_response_code(401);
    exit('Unauthorized');
}

// Buscar la carpeta privada
$posibles_nombres = ['EkuoraWebSite', 'CARPETA_PRIVADA'];
$private_path = null;

foreach ($posibles_nombres as $nombre) {
    // 1. Un nivel arriba de __DIR__ (Estructura estándar)
    $test_path = realpath(dirname(__DIR__) . '/' . $nombre) . DIRECTORY_SEPARATOR;
    if ($test_path && is_dir($test_path . 'uploads')) {
        $private_path = $test_path;
        break;
    }

    // 2. Misma carpeta que __DIR__ (Estructura plana)
    $test_path = realpath(__DIR__ . '/' . $nombre) . DIRECTORY_SEPARATOR;
    if ($test_path && is_dir($test_path . 'uploads')) {
        $private_path = $test_path;
        break;
    }
}

if (!$private_path) {
    header("HTTP/1.0 500 Internal Server Error");
    exit('Private directory not found');
}

define('PRIVATE_PATH', $private_path);
define('UPLOADS_BASE', PRIVATE_PATH . 'uploads/');

$file = $_GET['file'] ?? '';

// 1. Validación básica
if (empty($file) || strpos($file, '..') !== false) {
    header("HTTP/1.0 400 Bad Request");
    exit('Bad Request');
}

// 2. Ruta absoluta y saneamiento
// 2. Ruta absoluta y saneamiento
$realBase = realpath(UPLOADS_BASE);
$filePath = realpath(UPLOADS_BASE . $file);

// Si realBase es false, la carpeta uploads no existe
if ($realBase === false) {
    header("HTTP/1.0 500 Internal Server Error");
    exit('Uploads dir missing');
}

// 3. Verificar sandboxing (windows-safe)
if ($filePath === false || !file_exists($filePath) || stripos($filePath, $realBase) !== 0) {
    header("HTTP/1.0 404 Not Found");
    // Debug info (remove in prod)
    // echo "File: $file (Path: $filePath) | Base: $realBase"; 
    exit('Not Found');
}

// 4. Determinar MIME Type
$ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
$mimeTypes = [
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif',
    'webp' => 'image/webp',
    'svg' => 'image/svg+xml',
    'pdf' => 'application/pdf'
];

$contentType = $mimeTypes[$ext] ?? 'application/octet-stream';

// 5. Servir archivo
if (ob_get_level())
    ob_end_clean(); // Limpiar buffers previos
header('Content-Type: ' . $contentType);
header('Content-Length: ' . filesize($filePath));
header('Cache-Control: public, max-age=86400'); // Cache por 1 día
header('Access-Control-Allow-Origin: *'); // Allow cross-subdomain access

readfile($filePath);
