<?php
// app/models/User.php - Modelo de Usuario

class User
{
    private $conn;
    private $table = 'usuarios';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Obtener todos los usuarios activos (excluye soft-deleted)
     */
    public function getAll($filtros = [])
    {
        $sql = "
            SELECT u.*, p.nombre as perfil_nombre
            FROM {$this->table} u
            LEFT JOIN perfiles p ON u.perfil_id = p.id
            WHERE u.deleted_at IS NULL
        ";

        $params = [];

        if (!empty($filtros['perfil_id'])) {
            $sql .= " AND u.perfil_id = :perfil_id";
            $params[':perfil_id'] = $filtros['perfil_id'];
        }

        if (isset($filtros['activo']) && $filtros['activo'] !== '') {
            $sql .= " AND u.activo = :activo";
            $params[':activo'] = $filtros['activo'];
        }

        if (!empty($filtros['buscar'])) {
            $sql .= " AND (u.nombre LIKE :buscar OR u.username LIKE :buscar OR u.email LIKE :buscar)";
            $params[':buscar'] = '%' . $filtros['buscar'] . '%';
        }

        $sql .= " ORDER BY u.created_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * Obtener usuario por ID (excluye soft-deleted)
     */
    public function getById($id)
    {
        $stmt = $this->conn->prepare("
            SELECT u.*, p.nombre as perfil_nombre
            FROM {$this->table} u
            LEFT JOIN perfiles p ON u.perfil_id = p.id
            WHERE u.id = :id AND u.deleted_at IS NULL
            LIMIT 1
        ");

        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Obtener usuario por username o email (para login; excluye soft-deleted)
     */
    public function getByUsernameOrEmail($username)
    {
        $stmt = $this->conn->prepare("
            SELECT u.*, p.nombre as perfil_nombre
            FROM {$this->table} u
            LEFT JOIN perfiles p ON u.perfil_id = p.id
            WHERE (u.username = :username OR u.email = :email)
            AND u.deleted_at IS NULL
            LIMIT 1
        ");

        $stmt->execute([':username' => $username, ':email' => $username]);
        return $stmt->fetch();
    }

    /**
     * Obtener permisos de un usuario via su perfil
     */
    public function getPermisos($usuario_id)
    {
        $stmt = $this->conn->prepare("
            SELECT p.clave
            FROM permisos p
            INNER JOIN perfil_permisos pp ON p.id = pp.permiso_id
            INNER JOIN usuarios u ON pp.perfil_id = u.perfil_id
            WHERE u.id = :usuario_id
            AND u.deleted_at IS NULL
        ");

        $stmt->execute([':usuario_id' => $usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Crear usuario
     */
    public function create($datos)
    {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO {$this->table}
                    (perfil_id, username, email, password_hash, nombre, apellido,
                     telefono, rol, activo, debe_cambiar_password)
                VALUES
                    (:perfil_id, :username, :email, :password_hash, :nombre, :apellido,
                     :telefono, :rol, :activo, :debe_cambiar_password)
            ");

            $result = $stmt->execute([
                ':perfil_id'             => $datos['perfil_id'] ?? null,
                ':username'              => $datos['username'],
                ':email'                 => $datos['email'],
                ':password_hash'         => password_hash($datos['password'], PASSWORD_BCRYPT, ['cost' => BCRYPT_COST]),
                ':nombre'                => $datos['nombre'],
                ':apellido'              => $datos['apellido'] ?? null,
                ':telefono'              => $datos['telefono'] ?? null,
                ':rol'                   => $datos['rol'] ?? 'usuario',
                ':activo'                => $datos['activo'] ?? 1,
                ':debe_cambiar_password' => $datos['debe_cambiar_password'] ?? 0,
            ]);

            if (!$result) return false;

            $usuario_id = $this->conn->lastInsertId();
            logSeguridad('usuario_creado', "Usuario {$datos['username']} creado", null, 'info');

            return $usuario_id;

        } catch (Exception $e) {
            error_log("Error al crear usuario: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar usuario
     * BUG FIX: Incluye el campo `activo` que el controller envía pero antes se ignoraba.
     */
    public function update($id, $datos)
    {
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("
                UPDATE {$this->table}
                SET perfil_id  = :perfil_id,
                    username   = :username,
                    email      = :email,
                    nombre     = :nombre,
                    apellido   = :apellido,
                    telefono   = :telefono,
                    rol        = :rol,
                    activo     = :activo,
                    updated_at = NOW()
                WHERE id = :id
                AND deleted_at IS NULL
            ");

            $stmt->execute([
                ':id'        => $id,
                ':perfil_id' => $datos['perfil_id'] ?? null,
                ':username'  => $datos['username'],
                ':email'     => $datos['email'],
                ':nombre'    => $datos['nombre'],
                ':apellido'  => $datos['apellido'] ?? null,
                ':telefono'  => $datos['telefono'] ?? null,
                ':rol'       => $datos['rol'] ?? 'usuario',
                ':activo'    => isset($datos['activo']) ? (int)$datos['activo'] : 1,
            ]);

            // Cambio de contraseña opcional
            if (!empty($datos['password'])) {
                $this->updatePassword($id, $datos['password'], false);
            }

            $this->conn->commit();
            logSeguridad('usuario_actualizado', "Usuario ID: $id actualizado", $id, 'info');

            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al actualizar usuario: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar contraseña
     */
    public function updatePassword($id, $nueva_password, $log = true)
    {
        $stmt = $this->conn->prepare("
            UPDATE {$this->table}
            SET password_hash        = :password_hash,
                debe_cambiar_password = 0,
                updated_at           = NOW()
            WHERE id = :id
            AND deleted_at IS NULL
        ");

        $result = $stmt->execute([
            ':id'            => $id,
            ':password_hash' => password_hash($nueva_password, PASSWORD_BCRYPT, ['cost' => BCRYPT_COST]),
        ]);

        if ($result && $log) {
            logSeguridad('password_cambiado', "Contraseña cambiada para usuario ID: $id", $id, 'info');
        }

        return $result;
    }

    /**
     * Toggle estado activo/inactivo
     */
    public function toggleEstado($id)
    {
        $stmt = $this->conn->prepare("
            UPDATE {$this->table}
            SET activo = NOT activo, updated_at = NOW()
            WHERE id = :id AND deleted_at IS NULL
        ");

        $result = $stmt->execute([':id' => $id]);

        if ($result) {
            logSeguridad('usuario_estado_cambiado', "Estado cambiado para usuario ID: $id", $id, 'info');
        }

        return $result;
    }

    /**
     * Eliminar usuario (soft-delete: pone deleted_at, NO borra el row)
     * Los métodos existeUsername/existeEmail filtran deleted_at IS NULL,
     * por lo que el username/email queda libre para reutilizarse.
     */
    public function delete($id)
    {
        $stmt = $this->conn->prepare("
            UPDATE {$this->table}
            SET deleted_at = NOW()
            WHERE id = :id
            AND deleted_at IS NULL
        ");

        $result = $stmt->execute([':id' => $id]);

        if ($result) {
            logSeguridad('usuario_eliminado', "Usuario ID: $id eliminado (soft-delete)", $id, 'warning');
        }

        return $result;
    }

    /**
     * ¿Existe username?
     * BUG FIX: Filtra `deleted_at IS NULL` para que un usuario eliminado
     * no bloquee la creación de uno nuevo con el mismo username.
     */
    public function existeUsername($username, $exclude_id = null)
    {
        $sql = "SELECT id FROM {$this->table}
                WHERE username = :username
                AND deleted_at IS NULL";

        if ($exclude_id) {
            $sql .= " AND id != :exclude_id";
        }

        $params = [':username' => $username];
        if ($exclude_id) {
            $params[':exclude_id'] = $exclude_id;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }

    /**
     * ¿Existe email?
     * BUG FIX: Filtra `deleted_at IS NULL` para que un usuario eliminado
     * no bloquee la creación de uno nuevo con el mismo email.
     */
    public function existeEmail($email, $exclude_id = null)
    {
        $sql = "SELECT id FROM {$this->table}
                WHERE email = :email
                AND deleted_at IS NULL";

        if ($exclude_id) {
            $sql .= " AND id != :exclude_id";
        }

        $params = [':email' => $email];
        if ($exclude_id) {
            $params[':exclude_id'] = $exclude_id;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }

    /**
     * Registrar intento fallido de login
     */
    public function registrarIntentoFallido($username)
    {
        $stmt = $this->conn->prepare("
            UPDATE {$this->table}
            SET intentos_fallidos = intentos_fallidos + 1,
                bloqueado_hasta   = IF(intentos_fallidos >= 4, DATE_ADD(NOW(), INTERVAL 30 MINUTE), bloqueado_hasta)
            WHERE (username = :username OR email = :email)
            AND deleted_at IS NULL
        ");

        $stmt->execute([':username' => $username, ':email' => $username]);
    }

    /**
     * ¿Está bloqueado el usuario?
     */
    public function estaBloqueado($username)
    {
        $stmt = $this->conn->prepare("
            SELECT bloqueado_hasta
            FROM {$this->table}
            WHERE (username = :username OR email = :email)
            AND deleted_at IS NULL
            LIMIT 1
        ");

        $stmt->execute([':username' => $username, ':email' => $username]);
        $row = $stmt->fetch();

        if ($row && $row['bloqueado_hasta']) {
            return strtotime($row['bloqueado_hasta']) > time();
        }

        return false;
    }

    /**
     * Limpiar intentos fallidos tras login exitoso
     */
    public function limpiarIntentos($id)
    {
        $stmt = $this->conn->prepare("
            UPDATE {$this->table}
            SET intentos_fallidos = 0, bloqueado_hasta = NULL
            WHERE id = :id
        ");

        return $stmt->execute([':id' => $id]);
    }

    /**
     * Crear token de recuperación de contraseña
     */
    public function crearTokenRecuperacion($email)
    {
        $token = generarTokenAleatorio(32);

        $stmt = $this->conn->prepare("
            UPDATE {$this->table}
            SET token_recuperacion = :token,
                token_expira       = DATE_ADD(NOW(), INTERVAL 1 HOUR)
            WHERE email = :email
            AND deleted_at IS NULL
        ");

        $result = $stmt->execute([':token' => $token, ':email' => $email]);

        return $result ? $token : false;
    }

    /**
     * Verificar token de recuperación
     */
    public function verificarTokenRecuperacion($token)
    {
        $stmt = $this->conn->prepare("
            SELECT id, nombre, email
            FROM {$this->table}
            WHERE token_recuperacion = :token
            AND token_expira > NOW()
            AND deleted_at IS NULL
            LIMIT 1
        ");

        $stmt->execute([':token' => $token]);
        return $stmt->fetch();
    }

    /**
     * Limpiar token tras resetear contraseña
     */
    public function limpiarTokenRecuperacion($id)
    {
        $stmt = $this->conn->prepare("
            UPDATE {$this->table}
            SET token_recuperacion = NULL, token_expira = NULL
            WHERE id = :id
        ");

        return $stmt->execute([':id' => $id]);
    }

    /**
     * Estadísticas del panel
     */
    public function getEstadisticas()
    {
        $stmt = $this->conn->query("
            SELECT
                COUNT(*)                          AS total,
                SUM(activo = 1)                   AS activos,
                SUM(activo = 0)                   AS inactivos,
                SUM(debe_cambiar_password = 1)    AS deben_cambiar_password
            FROM {$this->table}
            WHERE deleted_at IS NULL
        ");

        return $stmt->fetch();
    }
}
