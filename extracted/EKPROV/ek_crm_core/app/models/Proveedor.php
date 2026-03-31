<?php
// app/models/Proveedor.php - Modelo de Proveedor

class Proveedor
{
    private $conn;
    private $table = 'Proveedores';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Obtener todos los proveedores
     */
    public function getAll($filtros = [])
    {
        $sql = "
            SELECT p.*,
                   r.Descripcion as regimen_nombre,
                   GROUP_CONCAT(DISTINCT c.Nombre ORDER BY c.Codigo SEPARATOR ', ') as cias_nombres
            FROM {$this->table} p
            LEFT JOIN Cat_Regimenes r ON p.RegimenFiscalId = r.Id
            LEFT JOIN Proveedor_Cias pc ON p.Id = pc.ProveedorId
            LEFT JOIN Cat_Cias c ON pc.CiaId = c.Id
            WHERE 1=1
        ";

        $params = [];

        if (!empty($filtros['buscar'])) {
            $sql .= " AND (p.RFC LIKE :buscar1 OR p.RazonSocial LIKE :buscar2 OR p.Nombre LIKE :buscar3 OR p.IdManual LIKE :buscar4)";
            $term = '%' . $filtros['buscar'] . '%';
            $params[':buscar1'] = $term;
            $params[':buscar2'] = $term;
            $params[':buscar3'] = $term;
            $params[':buscar4'] = $term;
        }

        if (!empty($filtros['estatus'])) {
            $sql .= " AND p.Estatus = :estatus";
            $params[':estatus'] = $filtros['estatus'];
        }

        // Filtro para ocultar estatus específicos
        if (!empty($filtros['excluir_estatus'])) {
            $sql .= " AND p.Estatus != :excluir_estatus";
            $params[':excluir_estatus'] = $filtros['excluir_estatus'];
        }

        $sql .= " GROUP BY p.Id ORDER BY p.FechaRegistro DESC";

        if (isset($filtros['limit'])) {
            $sql .= " LIMIT " . (int) $filtros['limit'];
            if (isset($filtros['offset'])) {
                $sql .= " OFFSET " . (int) $filtros['offset'];
            }
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * Contar total de proveedores (para paginación)
     */
    public function contarTodos($filtros = [])
    {
        $sql = "
            SELECT COUNT(DISTINCT p.Id) as total
            FROM {$this->table} p
            WHERE 1=1
        ";

        $params = [];

        if (!empty($filtros['buscar'])) {
            $sql .= " AND (p.RFC LIKE :buscar1 OR p.RazonSocial LIKE :buscar2 OR p.Nombre LIKE :buscar3 OR p.IdManual LIKE :buscar4)";
            $term = '%' . $filtros['buscar'] . '%';
            $params[':buscar1'] = $term;
            $params[':buscar2'] = $term;
            $params[':buscar3'] = $term;
            $params[':buscar4'] = $term;
        }

        if (!empty($filtros['estatus'])) {
            $sql .= " AND p.Estatus = :estatus";
            $params[':estatus'] = $filtros['estatus'];
        }

        if (!empty($filtros['excluir_estatus'])) {
            $sql .= " AND p.Estatus != :excluir_estatus";
            $params[':excluir_estatus'] = $filtros['excluir_estatus'];
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();

        return (int) $result['total'];
    }

    /**
     * Buscar proveedores
     */
    public function buscar($termino)
    {
        return $this->getAll(['buscar' => $termino]);
    }

    /**
     * Obtener proveedor por ID
     */
    public function getById($id)
    {
        $stmt = $this->conn->prepare("
            SELECT p.*, r.Descripcion as regimen_nombre
            FROM {$this->table} p
            LEFT JOIN Cat_Regimenes r ON p.RegimenFiscalId = r.Id
            WHERE p.Id = :id
            LIMIT 1
        ");

        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Obtener proveedor por RFC
     */
    public function getByRFC($rfc)
    {
        $stmt = $this->conn->prepare("
            SELECT p.*, r.Descripcion as regimen_nombre
            FROM {$this->table} p
            LEFT JOIN Cat_Regimenes r ON p.RegimenFiscalId = r.Id
            WHERE p.RFC = :rfc
            LIMIT 1
        ");

        $stmt->execute([':rfc' => strtoupper(trim($rfc))]);
        return $stmt->fetch();
    }

    /**
     * Obtener CÍAs asignadas a un proveedor
     */
    public function getCias($proveedor_id)
    {
        $stmt = $this->conn->prepare("
            SELECT c.*
            FROM Cat_Cias c
            INNER JOIN Proveedor_Cias pc ON c.Id = pc.CiaId
            WHERE pc.ProveedorId = :proveedor_id
            ORDER BY c.Codigo
        ");

        $stmt->execute([':proveedor_id' => $proveedor_id]);
        return $stmt->fetchAll();
    }

    /**
     * Obtener cuentas bancarias de un proveedor
     */
    public function getCuentas($proveedor_id)
    {
        $stmt = $this->conn->prepare("
            SELECT pc.*, c.Nombre as cia_nombre, c.Codigo as cia_codigo, b.Nombre as banco_nombre
            FROM Proveedor_Cuentas pc
            INNER JOIN Cat_Cias c ON pc.CiaId = c.Id
            LEFT JOIN Cat_Bancos b ON pc.BancoId = b.Id
            WHERE pc.ProveedorId = :proveedor_id
            ORDER BY c.Codigo
        ");

        $stmt->execute([':proveedor_id' => $proveedor_id]);
        return $stmt->fetchAll();
    }

    /**
     * Verificar si existe RFC
     */
    public function existeRFC($rfc, $exclude_id = null)
    {
        $sql = "SELECT Id FROM {$this->table} WHERE RFC = :rfc";

        if ($exclude_id) {
            $sql .= " AND Id != :exclude_id";
        }

        $stmt = $this->conn->prepare($sql);
        $params = [':rfc' => $rfc];

        if ($exclude_id) {
            $params[':exclude_id'] = $exclude_id;
        }

        $stmt->execute($params);
        return $stmt->rowCount() > 0;
    }

    /**
     * Verificar si existe ID Manual
     */
    public function existeIdManual($id_manual, $exclude_id = null)
    {
        if (empty($id_manual)) {
            return false;
        }

        $sql = "SELECT Id FROM {$this->table} WHERE IdManual = :id_manual";

        if ($exclude_id) {
            $sql .= " AND Id != :exclude_id";
        }

        $stmt = $this->conn->prepare($sql);
        $params = [':id_manual' => $id_manual];

        if ($exclude_id) {
            $params[':exclude_id'] = $exclude_id;
        }

        $stmt->execute($params);
        return $stmt->rowCount() > 0;
    }

    /**
     * Alias de existeIdManual()
     */
    public function existeCodigoProveedor($codigo, $exclude_id = null)
    {
        return $this->existeIdManual($codigo, $exclude_id);
    }

    /**
     * Obtener el último consecutivo usado en RFCs genéricos
     * Busca RFCs que empiecen con XAXX010101 o XEXX010101
     * y extrae el mayor número del consecutivo (últimos 3 dígitos)
     */
    public function getUltimoConsecutivoRFCGenerico()
    {
        $stmt = $this->conn->prepare("
            SELECT RFC 
            FROM {$this->table} 
            WHERE RFC LIKE 'XAXX010101%' OR RFC LIKE 'XEXX010101%'
            ORDER BY RFC DESC
        ");

        $stmt->execute();
        $rfcs = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $maxConsecutivo = 0;

        foreach ($rfcs as $rfc) {
            // Extraer los últimos 3 dígitos (posiciones 10-12)
            if (strlen($rfc) >= 13) {
                $consecutivo = (int) substr($rfc, 10, 3);
                if ($consecutivo > $maxConsecutivo) {
                    $maxConsecutivo = $consecutivo;
                }
            }
        }

        return $maxConsecutivo;
    }

    /**
     * Crear proveedor
     */
    public function create($datos)
    {
        try {
            $this->conn->beginTransaction();

            $sql = "
                INSERT INTO {$this->table}
                (IdManual, RFC, TipoPersona, TipoProveedor, RazonSocial, NombreComercial, Nombre, ApellidoPaterno, ApellidoMaterno,
                 RegimenFiscalId, CorreoPagosInterno, CorreoProveedor, Responsable, LimiteCredito,
                 Calle, NumeroExterior, NumeroInterior, Colonia, CP, Estado, Municipio,
                 RutaConstancia, RutaCaratula, Estatus, UsuarioCreadorId)
                VALUES
                (:id_manual, :rfc, :tipo, :tipo_prov, :razon, :nombre_comercial, :nombre, :paterno, :materno,
                 :regimen, :correo_int, :correo_prov, :resp, :limite,
                 :calle, :num_ext, :num_int, :colonia, :cp, :estado, :municipio,
                 :ruta_cons, :ruta_car, :estatus, :usuario_id)
            ";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                ':id_manual' => $datos['IdManual'],
                ':rfc' => $datos['RFC'],
                ':tipo' => $datos['TipoPersona'],
                ':tipo_prov' => $datos['TipoProveedor'],
                ':razon' => $datos['RazonSocial'] ?? null,
                ':nombre_comercial' => $datos['NombreComercial'] ?? null,
                ':nombre' => $datos['Nombre'] ?? null,
                ':paterno' => $datos['ApellidoPaterno'] ?? null,
                ':materno' => $datos['ApellidoMaterno'] ?? null,
                ':regimen' => $datos['RegimenFiscalId'] ?? null,
                ':correo_int' => $datos['CorreoPagosInterno'] ?? null,
                ':correo_prov' => $datos['CorreoProveedor'] ?? null,
                ':resp' => $datos['Responsable'] ?? null,
                ':limite' => $datos['LimiteCredito'] ?? 0,
                ':calle' => $datos['Calle'] ?? null,
                ':num_ext' => $datos['NumeroExterior'] ?? null,
                ':num_int' => $datos['NumeroInterior'] ?? null,
                ':colonia' => $datos['Colonia'] ?? null,
                ':cp' => $datos['CP'] ?? null,
                ':estado' => $datos['Estado'] ?? null,
                ':municipio' => $datos['Municipio'] ?? null,
                ':ruta_cons' => $datos['RutaConstancia'] ?? null,
                ':ruta_car' => $datos['RutaCaratula'] ?? null,
                ':estatus' => $datos['Estatus'] ?? 'PENDIENTE',
                ':usuario_id' => $datos['UsuarioCreadorId'] ?? usuarioId()
            ]);

            $proveedor_id = $this->conn->lastInsertId();

            if (!empty($datos['Cias'])) {
                foreach ($datos['Cias'] as $cia_id) {
                    $this->asignarCia($proveedor_id, $cia_id);
                }
            }

            $this->conn->commit();
            logSeguridad('proveedor_creado', "Proveedor {$datos['RFC']} creado", null, 'info');

            return $proveedor_id;

        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al crear proveedor: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar proveedor
     * MEJORADO: Ahora incluye correctamente la columna LimiteCredito en el SQL
     */
    public function update($id, $datos)
    {
        try {
            $this->conn->beginTransaction();

            $sql = "
                UPDATE {$this->table}
                SET IdManual = :id_manual,
                    RFC = :rfc,
                    TipoPersona = :tipo,
                    TipoProveedor = :tipo_prov,
                    RazonSocial = :razon,
                    NombreComercial = :nombre_comercial,
                    Nombre = :nombre,
                    ApellidoPaterno = :paterno,
                    ApellidoMaterno = :materno,
                    RegimenFiscalId = :regimen,
                    CorreoPagosInterno = :correo_int,
                    CorreoProveedor = :correo_prov,
                    Responsable = :resp,
                    LimiteCredito = :limite,
                    Calle = :calle,
                    NumeroExterior = :num_ext,
                    NumeroInterior = :num_int,
                    Colonia = :colonia,
                    CP = :cp,
                    Estado = :estado,
                    Municipio = :municipio,
                    Estatus = :estatus,
                    FechaModificacion = NOW()
                WHERE Id = :id
            ";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                ':id_manual' => $datos['IdManual'] ?? null,
                ':rfc' => $datos['RFC'],
                ':tipo' => $datos['TipoPersona'],
                ':tipo_prov' => $datos['TipoProveedor'] ?? null,
                ':razon' => $datos['RazonSocial'] ?? null,
                ':nombre_comercial' => $datos['NombreComercial'] ?? null,
                ':nombre' => $datos['Nombre'] ?? null,
                ':paterno' => $datos['ApellidoPaterno'] ?? null,
                ':materno' => $datos['ApellidoMaterno'] ?? null,
                ':regimen' => !empty($datos['RegimenFiscalId']) ? $datos['RegimenFiscalId'] : null,
                ':correo_int' => $datos['CorreoPagosInterno'],
                ':correo_prov' => $datos['CorreoProveedor'],
                ':resp' => $datos['Responsable'],
                ':limite' => $datos['LimiteCredito'] ?? 0,
                ':calle' => $datos['Calle'] ?? null,
                ':num_ext' => $datos['NumeroExterior'] ?? null,
                ':num_int' => $datos['NumeroInterior'] ?? null,
                ':colonia' => $datos['Colonia'] ?? null,
                ':cp' => $datos['CP'] ?? null,
                ':estado' => $datos['Estado'] ?? null,
                ':municipio' => $datos['Municipio'] ?? null,
                ':estatus' => $datos['Estatus'] ?? 'PENDIENTE',
                ':id' => $id
            ]);

            if (isset($datos['Cias'])) {
                $this->limpiarCias($id);
                foreach ($datos['Cias'] as $cia_id) {
                    $this->asignarCia($id, $cia_id);
                }
            }

            $this->conn->commit();
            logSeguridad('proveedor_actualizado', "Proveedor ID: $id actualizado", null, 'info');

            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("CRITICAL UPDATE ERROR [PROV_ID: $id]: " . $e->getMessage());
            error_log("SQL Trace: " . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Actualizar archivos
     */
    public function updateArchivos($id, $rutas)
    {
        $sql = "UPDATE {$this->table} SET ";
        $fields = [];
        $params = [':id' => $id];

        if (isset($rutas['RutaConstancia'])) {
            $fields[] = "RutaConstancia = :ruta_cons";
            $params[':ruta_cons'] = $rutas['RutaConstancia'];
        }

        if (isset($rutas['RutaCaratula'])) {
            $fields[] = "RutaCaratula = :ruta_car";
            $params[':ruta_car'] = $rutas['RutaCaratula'];
        }

        $sql .= implode(', ', $fields) . ", FechaModificacion = NOW() WHERE Id = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Asignar CIA
     */
    public function asignarCia($proveedor_id, $cia_id)
    {
        $stmt = $this->conn->prepare("
            INSERT IGNORE INTO Proveedor_Cias (ProveedorId, CiaId)
            VALUES (:proveedor_id, :cia_id)
        ");

        return $stmt->execute([
            ':proveedor_id' => $proveedor_id,
            ':cia_id' => $cia_id
        ]);
    }

    /**
     * Limpiar CÍAs
     */
    private function limpiarCias($proveedor_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Proveedor_Cias WHERE ProveedorId = :proveedor_id");
        return $stmt->execute([':proveedor_id' => $proveedor_id]);
    }

    /**
     * Guardar cuenta bancaria
     */
    public function guardarCuenta($datos)
    {
        try {
            $stmt = $this->conn->prepare("
                SELECT Id FROM Proveedor_Cuentas
                WHERE ProveedorId = :proveedor_id AND CiaId = :cia_id
                LIMIT 1
            ");

            $stmt->execute([
                ':proveedor_id' => $datos['ProveedorId'],
                ':cia_id' => $datos['CiaId']
            ]);

            $existe = $stmt->fetch();

            if ($existe) {
                $stmt = $this->conn->prepare("
                    UPDATE Proveedor_Cuentas
                    SET BancoId = :banco_id,
                        Cuenta = :cuenta,
                        Clabe = :clabe,
                        Sucursal = :sucursal,
                        Plaza = :plaza
                    WHERE Id = :id
                ");

                return $stmt->execute([
                    ':id' => $existe['Id'],
                    ':banco_id' => !empty($datos['BancoId']) ? $datos['BancoId'] : null,
                    ':cuenta' => !empty($datos['Cuenta']) ? $datos['Cuenta'] : null,
                    ':clabe' => !empty($datos['Clabe']) ? $datos['Clabe'] : null,
                    ':sucursal' => !empty($datos['Sucursal']) ? $datos['Sucursal'] : null,
                    ':plaza' => !empty($datos['Plaza']) ? $datos['Plaza'] : null
                ]);

            } else {
                $stmt = $this->conn->prepare("
                    INSERT INTO Proveedor_Cuentas
                    (ProveedorId, CiaId, BancoId, Cuenta, Clabe, Sucursal, Plaza)
                    VALUES
                    (:proveedor_id, :cia_id, :banco_id, :cuenta, :clabe, :sucursal, :plaza)
                ");

                return $stmt->execute([
                    ':proveedor_id' => $datos['ProveedorId'],
                    ':cia_id' => $datos['CiaId'],
                    ':banco_id' => !empty($datos['BancoId']) ? $datos['BancoId'] : null,
                    ':cuenta' => !empty($datos['Cuenta']) ? $datos['Cuenta'] : null,
                    ':clabe' => !empty($datos['Clabe']) ? $datos['Clabe'] : null,
                    ':sucursal' => !empty($datos['Sucursal']) ? $datos['Sucursal'] : null,
                    ':plaza' => !empty($datos['Plaza']) ? $datos['Plaza'] : null
                ]);
            }

        } catch (Exception $e) {
            error_log("Error al guardar cuenta: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener historial de bitácora bancaria
     */
    public function getHistorialBancario($proveedor_id, $cia_id)
    {
        $stmt = $this->conn->prepare("
            SELECT bb.*, b.Nombre as banco_antiguo_nombre, u.nombre as usuario_nombre
            FROM Bitacora_Bancaria bb
            LEFT JOIN Cat_Bancos b ON bb.BancoIdAntiguo = b.Id
            LEFT JOIN usuarios u ON bb.UsuarioResponsable = u.id
            WHERE bb.ProveedorId = :proveedor_id AND bb.CiaId = :cia_id
            ORDER BY bb.FechaCambio DESC
        ");

        $stmt->execute([
            ':proveedor_id' => $proveedor_id,
            ':cia_id' => $cia_id
        ]);

        return $stmt->fetchAll();
    }

    /**
     * Registrar cambio en bitácora
     */
    public function registrarCambioBitacora($proveedor_id, $cia_id, $datos_antiguos)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO Bitacora_Bancaria
            (ProveedorId, CiaId, BancoIdAntiguo, CuentaAntigua, ClabeAntigua, SucursalAntigua, PlazaAntigua, UsuarioResponsable)
            VALUES
            (:proveedor_id, :cia_id, :banco_id, :cuenta, :clabe, :sucursal, :plaza, :usuario_id)
        ");

        return $stmt->execute([
            ':proveedor_id' => $proveedor_id,
            ':cia_id' => $cia_id,
            ':banco_id' => $datos_antiguos['BancoId'] ?? null,
            ':cuenta' => $datos_antiguos['Cuenta'] ?? null,
            ':clabe' => $datos_antiguos['Clabe'] ?? null,
            ':sucursal' => $datos_antiguos['Sucursal'] ?? null,
            ':plaza' => $datos_antiguos['Plaza'] ?? null,
            ':usuario_id' => usuarioId()
        ]);
    }

    /**
     * Eliminar proveedor
     */
    public function delete($id)
    {
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("DELETE FROM Proveedor_Cuentas WHERE ProveedorId = :id");
            $stmt->execute([':id' => $id]);

            $this->limpiarCias($id);

            $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE Id = :id");
            $stmt->execute([':id' => $id]);

            $this->conn->commit();
            logSeguridad('proveedor_eliminado', "Proveedor ID: $id eliminado", null, 'warning');

            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al eliminar proveedor: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener estadísticas para Dashboard
     */
    public function getEstadisticas()
    {
        $stmt = $this->conn->query("
            SELECT
                COUNT(*) as total,
                SUM(LOWER(Estatus) = 'pendiente') as pendientes,
                SUM(LOWER(Estatus) = 'aprobado' OR LOWER(Estatus) = 'activo') as aprobados,
                SUM(LOWER(Estatus) = 'rechazado' OR LOWER(Estatus) = 'inactivo') as rechazados
            FROM {$this->table}
        ");

        return $stmt->fetch();
    }
}