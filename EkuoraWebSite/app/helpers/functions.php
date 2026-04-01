<?php
// app/helpers/functions.php - Funciones auxiliares generales

/**
 * Escapar HTML para prevenir XSS
 */
function e($string)
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Redirigir a una URL
 */
function redirect($url)
{
    header("Location: " . BASE_URL . ltrim($url, '/'));
    exit();
}

/**
 * Obtener la IP del cliente
 */
function getClientIP()
{
    // HTTP_CLIENT_IP y HTTP_X_FORWARDED_FOR pueden ser falsificados por cualquier cliente.
    // Usar siempre REMOTE_ADDR para rate-limiting y seguridad.
    // Si tu servidor está detrás de un proxy de confianza configura la IP del proxy aquí.
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
}

/**
 * Validar email
 */
function validarEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Sanitizar string
 */
function sanitizarString($string)
{
    // FILTER_SANITIZE_STRING fue eliminado en PHP 8.1+; usar htmlspecialchars
    return htmlspecialchars(trim($string ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Formatear moneda
 */
function formatoMoneda($cantidad)
{
    return '$' . number_format($cantidad, 2, '.', ',');
}

/**
 * Formatear fecha
 */
function formatoFecha($fecha, $formato = 'd/m/Y')
{
    if (!$fecha)
        return '-';
    $timestamp = is_numeric($fecha) ? $fecha : strtotime($fecha);
    return date($formato, $timestamp);
}

/**
 * Formatear fecha y hora
 */
function formatoFechaHora($fecha)
{
    return formatoFecha($fecha, 'd/m/Y H:i');
}

/**
 * Generar slug
 */
function generarSlug($texto)
{
    $texto = mb_strtolower($texto, 'UTF-8');
    $texto = preg_replace('/[^a-z0-9\s-]/', '', $texto);
    $texto = preg_replace('/[\s-]+/', '-', $texto);
    return trim($texto, '-');
}

/**
 * Validar RFC (México)
 */
function validarRFC($rfc)
{
    $rfc = strtoupper($rfc);

    // RFC Persona Moral (12 caracteres)
    $regexMoral = '/^[A-ZÑ&]{3}[0-9]{6}[A-Z0-9]{3}$/';
    // RFC Persona Física (13 caracteres)
    $regexFisica = '/^[A-ZÑ&]{4}[0-9]{6}[A-Z0-9]{3}$/';

    return preg_match($regexMoral, $rfc) || preg_match($regexFisica, $rfc);
}

/**
 * Validar CLABE (México)
 */
function validarCLABE($clabe)
{
    return preg_match('/^[0-9]{18}$/', $clabe);
}

/**
 * Generar token aleatorio
 */
function generarTokenAleatorio($length = 32)
{
    return bin2hex(random_bytes($length));
}

/**
 * Debug (solo en desarrollo)
 */
function dd(...$vars)
{
    if (APP_DEBUG) {
        echo '<pre>';
        foreach ($vars as $var) {
            var_dump($var);
        }
        echo '</pre>';
        die();
    }
}

/**
 * Obtener extensión de archivo
 */
function obtenerExtension($filename)
{
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

/**
 * Validar tipo de archivo
 */
function validarTipoArchivo($filename, $tiposPermitidos = ['pdf', 'jpg', 'jpeg', 'png'])
{
    $ext = obtenerExtension($filename);
    return in_array($ext, $tiposPermitidos);
}

/**
 * Obtener tamaño de archivo legible
 */
function tamanoArchivoLegible($bytes)
{
    $units = ['B', 'KB', 'MB', 'GB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, 2) . ' ' . $units[$pow];
}

/**
 * Obtener configuración
 */
function config($key, $default = null)
{
    global $db;

    static $config_cache = [];

    if (isset($config_cache[$key])) {
        return $config_cache[$key];
    }

    if (!isset($db)) {
        $database = new Database();
        $db = $database->getConnection();
    }

    try {
        $stmt = $db->prepare("SELECT valor, tipo FROM configuraciones WHERE clave = :key LIMIT 1");
        $stmt->execute([':key' => $key]);
        $row = $stmt->fetch();

        if ($row) {
            $valor = $row['valor'];

            switch ($row['tipo']) {
                case 'int':
                    $valor = (int) $valor;
                    break;
                case 'bool':
                    $valor = filter_var($valor, FILTER_VALIDATE_BOOLEAN);
                    break;
                case 'json':
                    $valor = json_decode($valor, true);
                    break;
            }

            $config_cache[$key] = $valor;
            return $valor;
        }

    } catch (Exception $e) {
        // Log error
    }

    return $default;
}
/**
 * Obtener URL de un recurso (imagen, etc)
 * Maneja tanto recursos públicos como privados (uploads)
 */
function asset($path)
{
    if (empty($path)) {
        return BASE_URL . 'img/placeholder.png';
    }

    // Si ya es una URL completa, no hacer nada
    if (strpos($path, 'http') === 0) {
        return $path;
    }

    // Si la ruta comienza con 'uploads_privados/', redirigir a assets.php
    if (strpos($path, 'uploads_privados/') === 0) {
        $cleanPath = str_replace('uploads_privados/', '', $path);
        return BASE_URL . 'assets.php?file=' . $cleanPath;
    }

    // Fallback: Si no empieza con uploads_privados pero es una ruta relativa,
    // asumimos que es un recurso público en la carpeta img/ o similar.
    return BASE_URL . ltrim($path, '/');
}
