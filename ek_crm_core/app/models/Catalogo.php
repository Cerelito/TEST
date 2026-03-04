<?php
// app/models/Catalogo.php - Modelo de Catálogos

class Catalogo
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // ===========================================
    // VALIDACIONES (NUEVO)
    // ===========================================

    /**
     * Verifica si existe un banco con la misma clave o nombre
     */
    public function existeBanco($clabe, $nombre, $exclude_id = null)
    {
        $sql = "SELECT Id FROM Cat_Bancos WHERE (CLABE = :clabe OR Nombre = :nombre)";

        if ($exclude_id) {
            $sql .= " AND Id != :exclude_id";
        }

        $stmt = $this->conn->prepare($sql);
        $params = [':clabe' => $clabe, ':nombre' => $nombre];

        if ($exclude_id) {
            $params[':exclude_id'] = $exclude_id;
        }

        $stmt->execute($params);
        return $stmt->rowCount() > 0;
    }

    /**
     * Verifica si existe una compañía con el mismo código o nombre
     */
    public function existeCia($codigo, $nombre, $exclude_id = null)
    {
        $sql = "SELECT Id FROM Cat_Cias WHERE (Codigo = :codigo OR Nombre = :nombre)";

        if ($exclude_id) {
            $sql .= " AND Id != :exclude_id";
        }

        $stmt = $this->conn->prepare($sql);
        $params = [':codigo' => $codigo, ':nombre' => $nombre];

        if ($exclude_id) {
            $params[':exclude_id'] = $exclude_id;
        }

        $stmt->execute($params);
        return $stmt->rowCount() > 0;
    }

    /**
     * Verifica si existe un régimen con la misma clave
     */
    public function existeRegimen($clave, $exclude_id = null)
    {
        $sql = "SELECT Id FROM Cat_Regimenes WHERE Clave = :clave";

        if ($exclude_id) {
            $sql .= " AND Id != :exclude_id";
        }

        $stmt = $this->conn->prepare($sql);
        $params = [':clave' => $clave];

        if ($exclude_id) {
            $params[':exclude_id'] = $exclude_id;
        }

        $stmt->execute($params);
        return $stmt->rowCount() > 0;
    }

    // ===========================================
    // BANCOS - LECTURA
    // ===========================================

    public function getBancos($activo = null)
    {
        $sql = "SELECT * FROM Cat_Bancos WHERE 1=1";

        if ($activo !== null) {
            $sql .= " AND Activo = :activo";
        }

        $sql .= " ORDER BY Nombre ASC";

        $stmt = $this->conn->prepare($sql);

        if ($activo !== null) {
            $stmt->execute([':activo' => $activo ? 1 : 0]);
        } else {
            $stmt->execute();
        }

        return $stmt->fetchAll();
    }

    // ===========================================
    // COMPAÑÍAS (CÍAs) - LECTURA
    // ===========================================

    public function getCias($activo = null)
    {
        $sql = "SELECT * FROM Cat_Cias WHERE 1=1";

        if ($activo !== null) {
            $sql .= " AND Activo = :activo";
        }

        $sql .= " ORDER BY Codigo ASC";

        $stmt = $this->conn->prepare($sql);

        if ($activo !== null) {
            $stmt->execute([':activo' => $activo ? 1 : 0]);
        } else {
            $stmt->execute();
        }

        return $stmt->fetchAll();
    }

    // ===========================================
    // REGÍMENES FISCALES - LECTURA
    // ===========================================

    public function getRegimenes($activo = null)
    {
        $sql = "SELECT * FROM Cat_Regimenes WHERE 1=1";

        if ($activo !== null) {
            $sql .= " AND Activo = :activo";
        }

        $sql .= " ORDER BY Descripcion ASC";

        $stmt = $this->conn->prepare($sql);

        if ($activo !== null) {
            $stmt->execute([':activo' => $activo ? 1 : 0]);
        } else {
            $stmt->execute();
        }

        return $stmt->fetchAll();
    }

    // ===========================================
    // ESTADOS Y MUNICIPIOS
    // ===========================================

    public function getEstados()
    {
        $stmt = $this->conn->query("SELECT * FROM Cat_Estados ORDER BY Nombre ASC");
        return $stmt->fetchAll();
    }

    public function getMunicipios($estado_id = null)
    {
        if ($estado_id) {
            $stmt = $this->conn->prepare("
                SELECT * FROM Cat_Municipios
                WHERE EstadoId = :estado_id
                ORDER BY Nombre ASC
            ");
            $stmt->execute([':estado_id' => $estado_id]);
        } else {
            $stmt = $this->conn->query("SELECT * FROM Cat_Municipios ORDER BY Nombre ASC");
        }

        return $stmt->fetchAll();
    }

    // ===========================================
    // BANCOS - CRUD COMPLETO
    // ===========================================

    public function getBancoById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Cat_Bancos WHERE Id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function crearBanco($datos)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO Cat_Bancos (CLABE, Nombre, Activo)
            VALUES (:clabe, :nombre, :activo)
        ");

        return $stmt->execute([
            ':clabe' => $datos['CLABE'] ?? $datos['Clave'] ?? null,
            ':nombre' => $datos['Nombre'],
            ':activo' => $datos['Activo'] ?? 1
        ]);
    }

    public function actualizarBanco($id, $datos)
    {
        $stmt = $this->conn->prepare("
            UPDATE Cat_Bancos
            SET CLABE = :clabe,
                Nombre = :nombre,
                Activo = :activo
            WHERE Id = :id
        ");

        return $stmt->execute([
            ':id' => $id,
            ':clabe' => $datos['CLABE'] ?? $datos['Clave'] ?? null,
            ':nombre' => $datos['Nombre'],
            ':activo' => $datos['Activo'] ?? 1
        ]);
    }

    public function eliminarBanco($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Cat_Bancos WHERE Id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // ===========================================
    // COMPAÑÍAS - CRUD COMPLETO
    // ===========================================

    public function getCiaById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Cat_Cias WHERE Id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function crearCia($datos)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO Cat_Cias (Codigo, Nombre, Descripcion, Activo)
            VALUES (:codigo, :nombre, :descripcion, :activo)
        ");

        return $stmt->execute([
            ':codigo' => $datos['Codigo'],
            ':nombre' => $datos['Nombre'],
            ':descripcion' => $datos['Descripcion'] ?? null,
            ':activo' => $datos['Activo'] ?? 1
        ]);
    }

    public function actualizarCia($id, $datos)
    {
        $stmt = $this->conn->prepare("
            UPDATE Cat_Cias
            SET Codigo = :codigo,
                Nombre = :nombre,
                Descripcion = :descripcion,
                Activo = :activo
            WHERE Id = :id
        ");

        return $stmt->execute([
            ':id' => $id,
            ':codigo' => $datos['Codigo'],
            ':nombre' => $datos['Nombre'],
            ':descripcion' => $datos['Descripcion'] ?? null,
            ':activo' => $datos['Activo'] ?? 1
        ]);
    }

    public function eliminarCia($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Cat_Cias WHERE Id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // ===========================================
    // REGÍMENES FISCALES - CRUD COMPLETO
    // ===========================================

    public function getRegimenById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Cat_Regimenes WHERE Id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function crearRegimen($datos)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO Cat_Regimenes (Clave, Descripcion, TipoPersona, Activo)
            VALUES (:clave, :descripcion, :tipo_persona, :activo)
        ");

        return $stmt->execute([
            ':clave' => $datos['Clave'],
            ':descripcion' => $datos['Descripcion'],
            ':tipo_persona' => $datos['TipoPersona'] ?? 'Ambas',
            ':activo' => $datos['Activo'] ?? 1
        ]);
    }

    public function actualizarRegimen($id, $datos)
    {
        $stmt = $this->conn->prepare("
            UPDATE Cat_Regimenes
            SET Clave = :clave,
                Descripcion = :descripcion,
                TipoPersona = :tipo_persona,
                Activo = :activo
            WHERE Id = :id
        ");

        return $stmt->execute([
            ':id' => $id,
            ':clave' => $datos['Clave'],
            ':descripcion' => $datos['Descripcion'],
            ':tipo_persona' => $datos['TipoPersona'] ?? 'Ambas',
            ':activo' => $datos['Activo'] ?? 1
        ]);
    }

    public function eliminarRegimen($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Cat_Regimenes WHERE Id = :id");
        return $stmt->execute([':id' => $id]);
    }
}