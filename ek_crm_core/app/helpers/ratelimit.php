<?php
// app/helpers/ratelimit.php - Rate Limiting para prevenir ataques

/**
 * Verificar rate limit
 *
 * @param string $accion Nombre de la acción (ej: 'login', 'registro')
 * @param int $max_intentos Número máximo de intentos permitidos
 * @param int $ventana_minutos Ventana de tiempo en minutos
 * @param string $identificador Identificador único (default: IP)
 * @return bool true si está dentro del límite, false si excedió
 */
function verificarRateLimit($accion, $max_intentos = 5, $ventana_minutos = 15, $identificador = null) {
    try {
        $database = new Database();
        $db = $database->getConnection();

        $identificador = $identificador ?? getClientIP();

        // Limpiar registros antiguos
        $stmt = $db->prepare("
            DELETE FROM rate_limits
            WHERE accion = :accion
            AND identificador = :identificador
            AND created_at < DATE_SUB(NOW(), INTERVAL :minutos MINUTE)
        ");
        $stmt->execute([
            ':accion' => $accion,
            ':identificador' => $identificador,
            ':minutos' => $ventana_minutos
        ]);

        // Contar intentos recientes
        $stmt = $db->prepare("
            SELECT COUNT(*) as total
            FROM rate_limits
            WHERE accion = :accion
            AND identificador = :identificador
        ");
        $stmt->execute([
            ':accion' => $accion,
            ':identificador' => $identificador
        ]);

        $row = $stmt->fetch();
        $total_intentos = $row['total'] ?? 0;

        if ($total_intentos >= $max_intentos) {
            logSeguridad('rate_limit_excedido', "Rate limit excedido para acción: $accion", null, 'warning');
            return false;
        }

        // Registrar intento
        registrarIntento($accion, $identificador);

        return true;

    } catch (Exception $e) {
        error_log("Error en rate limit: " . $e->getMessage());
        return true; // En caso de error, permitir la acción
    }
}

/**
 * Registrar intento de acción
 */
function registrarIntento($accion, $identificador = null) {
    try {
        $database = new Database();
        $db = $database->getConnection();

        $identificador = $identificador ?? getClientIP();

        $stmt = $db->prepare("
            INSERT INTO rate_limits (identificador, accion, ip)
            VALUES (:identificador, :accion, :ip)
        ");

        $stmt->execute([
            ':identificador' => $identificador,
            ':accion' => $accion,
            ':ip' => getClientIP()
        ]);

    } catch (Exception $e) {
        error_log("Error al registrar intento: " . $e->getMessage());
    }
}

/**
 * Limpiar rate limits de un identificador
 */
function limpiarRateLimit($accion, $identificador = null) {
    try {
        $database = new Database();
        $db = $database->getConnection();

        $identificador = $identificador ?? getClientIP();

        $stmt = $db->prepare("
            DELETE FROM rate_limits
            WHERE accion = :accion
            AND identificador = :identificador
        ");

        $stmt->execute([
            ':accion' => $accion,
            ':identificador' => $identificador
        ]);

    } catch (Exception $e) {
        error_log("Error al limpiar rate limit: " . $e->getMessage());
    }
}

/**
 * Obtener tiempo restante de bloqueo
 */
function tiempoBloqueoRestante($accion, $ventana_minutos = 15, $identificador = null) {
    try {
        $database = new Database();
        $db = $database->getConnection();

        $identificador = $identificador ?? getClientIP();

        $stmt = $db->prepare("
            SELECT TIMESTAMPDIFF(SECOND, NOW(), DATE_ADD(MIN(created_at), INTERVAL :minutos MINUTE)) as segundos
            FROM rate_limits
            WHERE accion = :accion
            AND identificador = :identificador
        ");

        $stmt->execute([
            ':accion' => $accion,
            ':identificador' => $identificador,
            ':minutos' => $ventana_minutos
        ]);

        $row = $stmt->fetch();
        $segundos = $row['segundos'] ?? 0;

        return max(0, $segundos);

    } catch (Exception $e) {
        error_log("Error al obtener tiempo de bloqueo: " . $e->getMessage());
        return 0;
    }
}
