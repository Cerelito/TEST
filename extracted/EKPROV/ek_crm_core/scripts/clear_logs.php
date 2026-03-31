<?php
/**
 * clear_logs.php - Script para borrar el contenido de la tabla de logs de seguridad.
 * 
 * Uso: php clear_logs.php (desde la terminal)
 * O acceder vía web si está en una carpeta expuesta.
 */

// Definir la ruta raíz si no está definida
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__) . '/');
}

// Cargar configuración y helpers necesarios
require_once ROOT_PATH . 'app/config/config.php';
require_once ROOT_PATH . 'app/config/db.php';
require_once ROOT_PATH . 'app/helpers/functions.php';
require_once ROOT_PATH . 'app/helpers/logs.php';

try {
    // Obtener conexión a la base de datos
    $database = new Database();
    $db = $database->getConnection();

    // Ejecutar TRUNCATE para limpiar la tabla
    $sql = "TRUNCATE TABLE logs_seguridad";
    $stmt = $db->prepare($sql);
    $stmt->execute();

    echo "✅ ÉXITO: La tabla 'logs_seguridad' ha sido vaciada correctamente.\n";

    // Registrar la acción en el log (opcional, pero recomendado si quieres saber cuándo se limpió)
    // logSeguridad('limpieza_logs', 'Se ha vaciado la tabla de logs de seguridad manualmente.');

} catch (Exception $e) {
    echo "❌ ERROR: No se pudo limpiar la tabla de logs: " . $e->getMessage() . "\n";
    error_log("Error al limpiar logs_seguridad: " . $e->getMessage());
}
