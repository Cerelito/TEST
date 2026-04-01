<?php
// app/helpers/logs.php - Sistema de logs de seguridad

/**
 * Registrar evento de seguridad
 */
function logSeguridad($evento, $descripcion = null, $usuario_id = null, $nivel = 'info') {
    try {
        $database = new Database();
        $db = $database->getConnection();

        $stmt = $db->prepare("
            INSERT INTO logs_seguridad (evento, descripcion, usuario_id, ip, user_agent, url, metodo, nivel)
            VALUES (:evento, :descripcion, :usuario_id, :ip, :user_agent, :url, :metodo, :nivel)
        ");

        $stmt->execute([
            ':evento' => $evento,
            ':descripcion' => $descripcion,
            ':usuario_id' => $usuario_id ?? usuarioId(),
            ':ip' => getClientIP(),
            ':user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500),
            ':url' => substr($_SERVER['REQUEST_URI'] ?? '', 0, 500),
            ':metodo' => $_SERVER['REQUEST_METHOD'] ?? '',
            ':nivel' => $nivel
        ]);

    } catch (Exception $e) {
        error_log("Error al guardar log de seguridad: " . $e->getMessage());
    }
}

/**
 * Obtener logs de seguridad
 */
function obtenerLogs($filtros = []) {
    try {
        $database = new Database();
        $db = $database->getConnection();

        $sql = "
            SELECT l.*, u.username, u.nombre as usuario_nombre
            FROM logs_seguridad l
            LEFT JOIN usuarios u ON l.usuario_id = u.id
            WHERE 1=1
        ";

        $params = [];

        if (isset($filtros['evento'])) {
            $sql .= " AND l.evento = :evento";
            $params[':evento'] = $filtros['evento'];
        }

        if (isset($filtros['usuario_id'])) {
            $sql .= " AND l.usuario_id = :usuario_id";
            $params[':usuario_id'] = $filtros['usuario_id'];
        }

        if (isset($filtros['nivel'])) {
            $sql .= " AND l.nivel = :nivel";
            $params[':nivel'] = $filtros['nivel'];
        }

        if (isset($filtros['fecha_desde'])) {
            $sql .= " AND l.created_at >= :fecha_desde";
            $params[':fecha_desde'] = $filtros['fecha_desde'];
        }

        if (isset($filtros['fecha_hasta'])) {
            $sql .= " AND l.created_at <= :fecha_hasta";
            $params[':fecha_hasta'] = $filtros['fecha_hasta'];
        }

        $sql .= " ORDER BY l.created_at DESC";

        if (isset($filtros['limit'])) {
            $sql .= " LIMIT " . (int)$filtros['limit'];
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();

    } catch (Exception $e) {
        error_log("Error al obtener logs: " . $e->getMessage());
        return [];
    }
}

/**
 * Limpiar logs antiguos
 */
function limpiarLogsAntiguos($dias = 90) {
    try {
        $database = new Database();
        $db = $database->getConnection();

        $stmt = $db->prepare("DELETE FROM logs_seguridad WHERE created_at < DATE_SUB(NOW(), INTERVAL :dias DAY)");
        $stmt->execute([':dias' => $dias]);

        return $stmt->rowCount();

    } catch (Exception $e) {
        error_log("Error al limpiar logs: " . $e->getMessage());
        return 0;
    }
}
