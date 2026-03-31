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
    $fullUrl = BASE_URL . ltrim($url, '/');
    if (!headers_sent()) {
        header("Location: " . $fullUrl);
    } else {
        echo '<script type="text/javascript">';
        echo 'window.location.href="' . $fullUrl . '";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url=' . $fullUrl . '" />';
        echo '</noscript>';
    }
    exit();
}

/**
 * Obtener la IP del cliente
 */
function getClientIP()
{
    $ip = '0.0.0.0';

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

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
    return filter_var(trim($string), FILTER_SANITIZE_STRING);
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
 * Obtener contadores para el menú lateral (Sidebar)
 * Retorna array con total de pendientes
 */
function obtenerContadoresMenu()
{
    $conteos = ['solicitudes' => 0, 'proveedores' => 0];

    // Si no ha iniciado sesión, retornar ceros
    if (!estaAutenticado())
        return $conteos;

    // 1. Contar Solicitudes Pendientes
    if (tienePermiso('solicitudes.ver')) {
        try {
            // Instanciamos el modelo directamente aquí para evitar dependencias circulares
            $solicitudModel = new Solicitud();
            $stats = $solicitudModel->getEstadisticas();
            $conteos['solicitudes'] = $stats['pendientes'] ?? 0;
        } catch (Exception $e) { /* Ignorar errores en menú si falla la carga */
        }
    }

    // 2. Contar Proveedores Pendientes (Nuevos registros)
    if (tienePermiso('proveedores.ver')) {
        try {
            $proveedorModel = new Proveedor();
            $stats = $proveedorModel->getEstadisticas();
            $conteos['proveedores'] = $stats['pendientes'] ?? 0;
        } catch (Exception $e) { /* Ignorar errores en menú */
        }
    }

    return $conteos;
}