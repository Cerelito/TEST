<?php
// app/models/Solicitud.php - Modelo de Solicitudes de Cambios

class Solicitud
{
    private $conn;
    private $table = 'Solicitudes_Cambios';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Obtener todas las solicitudes
     * MEJORA: Procesa el JSON para extraer el Tipo de Cambio real
     */
    public function getAll($filtros = [], $limit = null, $offset = 0)
    {
        $params = [];
        $where = " WHERE 1=1";

        if (!empty($filtros['estatus'])) {
            $where .= " AND s.Estatus = :estatus";
            $params[':estatus'] = $filtros['estatus'];
        }

        if (!empty($filtros['solicitante_id'])) {
            $where .= " AND s.SolicitanteId = :solicitante_id";
            $params[':solicitante_id'] = $filtros['solicitante_id'];
        }

        if (!empty($filtros['busqueda'])) {
            $where .= " AND (p.RFC LIKE :busqueda OR p.Nombre LIKE :busqueda OR p.RazonSocial LIKE :busqueda OR u.nombre LIKE :busqueda)";
            $params[':busqueda'] = '%' . $filtros['busqueda'] . '%';
        }

        $sql = "
            SELECT s.*,
                   p.RFC, p.RazonSocial, p.Nombre as proveedor_nombre,
                   u.nombre as solicitante_nombre, u.username as solicitante_username,
                   r.nombre as revisor_nombre,
                   c.Nombre as cia_nombre
            FROM {$this->table} s
            INNER JOIN Proveedores p ON s.ProveedorId = p.Id
            INNER JOIN usuarios u ON s.SolicitanteId = u.id
            LEFT JOIN usuarios r ON s.RevisadoPor = r.id
            LEFT JOIN Cat_Cias c ON s.CiaObjetivo = c.Id
            {$where}
            ORDER BY s.FechaSolicitud DESC
        ";

        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
            $params[':limit'] = (int) $limit;
            $params[':offset'] = (int) $offset;
        }

        $stmt = $this->conn->prepare($sql);

        // Bind params manually for limit/offset if necessary, but PDO bindValue handles it well
        foreach ($params as $key => $val) {
            $type = is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($key, $val, $type);
        }

        $stmt->execute();
        $resultados = $stmt->fetchAll();

        // --- LÓGICA DE EXTRACCIÓN DE DATOS JSON ---
        foreach ($resultados as &$row) {
            if (!empty($row['DatosJson'])) {
                $datos = json_decode($row['DatosJson'], true);
                $row['TipoCambio'] = $datos['TipoCambio'] ?? 'GENERAL';
            } else {
                $row['TipoCambio'] = 'GENERAL';
            }
        }

        return $resultados;
    }

    /**
     * Contar total de solicitudes con filtros
     */
    public function countAll($filtros = [])
    {
        $params = [];
        $where = " WHERE 1=1";

        if (!empty($filtros['estatus'])) {
            $where .= " AND s.Estatus = :estatus";
            $params[':estatus'] = $filtros['estatus'];
        }

        if (!empty($filtros['solicitante_id'])) {
            $where .= " AND s.SolicitanteId = :solicitante_id";
            $params[':solicitante_id'] = $filtros['solicitante_id'];
        }

        if (!empty($filtros['busqueda'])) {
            $where .= " AND (p.RFC LIKE :busqueda OR p.Nombre LIKE :busqueda OR p.RazonSocial LIKE :busqueda OR u.nombre LIKE :busqueda)";
            $params[':busqueda'] = '%' . $filtros['busqueda'] . '%';
        }

        $sql = "
            SELECT COUNT(*) 
            FROM {$this->table} s
            INNER JOIN Proveedores p ON s.ProveedorId = p.Id
            INNER JOIN usuarios u ON s.SolicitanteId = u.id
            {$where}
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    /**
     * Obtener solicitud por ID
     */
    public function getById($id)
    {
        $stmt = $this->conn->prepare("
            SELECT 
                p.*, 
                s.*, 
                s.Id as Id, 
                s.Estatus as Estatus, 
                p.Estatus as proveedor_estatus,
                p.Id as proveedor_id,
                u.nombre as solicitante_nombre,
                c.Nombre as cia_nombre
            FROM {$this->table} s
            INNER JOIN Proveedores p ON s.ProveedorId = p.Id
            INNER JOIN usuarios u ON s.SolicitanteId = u.id
            LEFT JOIN Cat_Cias c ON s.CiaObjetivo = c.Id
            WHERE s.Id = :id
            LIMIT 1
        ");

        $stmt->execute([':id' => $id]);
        $solicitud = $stmt->fetch();

        // Decodificar el JSON de datos solicitados para que sea utilizable
        if ($solicitud && !empty($solicitud['DatosJson'])) {
            $solicitud['DatosSolicitados'] = json_decode($solicitud['DatosJson'], true);
        }

        return $solicitud;
    }

    /**
     * Crear solicitud
     */
    public function create($datos)
    {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO {$this->table}
                (ProveedorId, SolicitanteId, DatosJson, RutaConstanciaNueva, RutaCaratulaNueva, CiaObjetivo, Estatus)
                VALUES
                (:proveedor_id, :solicitante_id, :datos_json, :ruta_cons, :ruta_car, :cia_objetivo, 'PENDIENTE')
            ");

            $result = $stmt->execute([
                ':proveedor_id' => $datos['ProveedorId'],
                ':solicitante_id' => usuarioId(),
                ':datos_json' => json_encode($datos['DatosJson'] ?? []),
                ':ruta_cons' => $datos['RutaConstanciaNueva'] ?? null,
                ':ruta_car' => $datos['RutaCaratulaNueva'] ?? null,
                ':cia_objetivo' => $datos['CiaObjetivo'] ?? 0
            ]);

            if ($result) {
                $solicitud_id = $this->conn->lastInsertId();
                if (function_exists('logSeguridad')) {
                    logSeguridad('solicitud_creada', "Solicitud ID: $solicitud_id creada", null, 'info');
                }
                return $solicitud_id;
            }

            return false;

        } catch (Exception $e) {
            error_log("Error al crear solicitud: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Aprobar solicitud
     */
    public function aprobar($id)
    {
        $stmt = $this->conn->prepare("
            UPDATE {$this->table}
            SET Estatus = 'APROBADO',
                FechaRevision = NOW(),
                RevisadoPor = :revisor_id
            WHERE Id = :id
        ");

        $result = $stmt->execute([
            ':id' => $id,
            ':revisor_id' => usuarioId()
        ]);

        if ($result && function_exists('logSeguridad')) {
            logSeguridad('solicitud_aprobada', "Solicitud ID: $id aprobada", null, 'info');
        }

        return $result;
    }

    /**
     * Rechazar solicitud
     */
    public function rechazar($id, $motivo = null)
    {
        $stmt = $this->conn->prepare("
            UPDATE {$this->table}
            SET Estatus = 'RECHAZADO',
                FechaRevision = NOW(),
                RevisadoPor = :revisor_id,
                MotivoRechazo = :motivo
            WHERE Id = :id
        ");

        $result = $stmt->execute([
            ':id' => $id,
            ':revisor_id' => usuarioId(),
            ':motivo' => $motivo
        ]);

        if ($result && function_exists('logSeguridad')) {
            logSeguridad('solicitud_rechazada', "Solicitud ID: $id rechazada", null, 'warning');
        }

        return $result;
    }

    /**
     * Obtener estadísticas
     */
    public function getEstadisticas()
    {
        $stmt = $this->conn->query("
            SELECT
                COUNT(*) as total,
                SUM(LOWER(Estatus) = 'pendiente') as pendientes,
                SUM(LOWER(Estatus) = 'aprobado') as aprobadas,
                SUM(LOWER(Estatus) = 'rechazado') as rechazadas
            FROM {$this->table}
        ");

        return $stmt->fetch();
    }

    /**
     * Buscar si existe una solicitud pendiente para un proveedor
     * (MEJORA: Usado para mostrar alerta en el perfil del proveedor)
     */
    public function getPendientePorProveedor($proveedorId)
    {
        // FIX: Se eliminó TipoCambio de la consulta SQL porque no existe como columna.
        // Se obtiene desde DatosJson.
        $stmt = $this->conn->prepare("
            SELECT Id, DatosJson, FechaSolicitud 
            FROM {$this->table} 
            WHERE ProveedorId = :pid AND Estatus = 'PENDIENTE' 
            LIMIT 1
        ");
        $stmt->execute([':pid' => $proveedorId]);
        $row = $stmt->fetch();

        if ($row) {
            $datos = json_decode($row['DatosJson'] ?? '{}', true);
            $row['TipoCambio'] = $datos['TipoCambio'] ?? 'SOLICITUD DE CAMBIO';
        }

        return $row;
    }

    /**
     * Eliminar solicitud
     */
    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE Id = :id");
        return $stmt->execute([':id' => $id]);
    }
}