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
     * Obtener todos los usuarios
     */
    public function getAll($filtros = [])
    {
        $sql = "
            SELECT u.*, p.nombre as perfil_nombre,
                   (SELECT COUNT(*) FROM usuarios WHERE perfil_id = u.perfil_id) as usuarios_en_perfil
            FROM {$this->table} u
            LEFT JOIN perfiles p ON u.perfil_id = p.id
            WHERE u.deleted_at IS NULL
        ";

        $params = [];

        if (!empty($filtros['perfil_id'])) {
            $sql .= " AND u.perfil_id = :perfil_id";
            $params[':perfil_id'] = $filtros['perfil_id'];
        }

        if (isset($filtros['activo'])) {
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
     * Obtener usuario por ID
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
     * Obtener usuario por username o email
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
     * Obtener permisos de un usuario
     */
    public function getPermisos($usuario_id)
    {
        $stmt = $this->conn->prepare("
            SELECT p.clave
            FROM permisos p
            INNER JOIN perfil_permisos pp ON p.id = pp.permiso_id
            INNER JOIN usuarios u ON pp.perfil_id = u.perfil_id
            WHERE u.id = :usuario_id
        ");

        $stmt->execute([':usuario_id' => $usuario_id]);
        $permisos = $stmt->fetchAll(PDO::FETCH_COLUMN);

        return $permisos;
    }

    /**
     * Crear usuario
     */
    public function create($datos)
    {
        try {
            $this->conn->beginTransaction();

            $sql = "
                INSERT INTO {$this->table}
                (perfil_id, username, email, password_hash, nombre, apellido, telefono, rol, debe_cambiar_password)
                VALUES
                (:perfil_id, :username, :email, :password_hash, :nombre, :apellido, :telefono, :rol, :debe_cambiar_password)
            ";

            $stmt = $this->conn->prepare($sql);

            $password_hash = password_hash($datos['password'], PASSWORD_BCRYPT, ['cost' => BCRYPT_COST]);
            $rol = $datos['rol'] ?? 'usuario';

            $result = $stmt->execute([
                ':perfil_id' => $datos['perfil_id'] ?? null,
                ':username' => $datos['username'],
                ':email' => $datos['email'],
                ':password_hash' => $password_hash,
                ':nombre' => $datos['nombre'],
                ':apellido' => $datos['apellido'] ?? null,
                ':telefono' => $datos['telefono'] ?? null,
                ':rol' => $rol,
                ':debe_cambiar_password' => $datos['debe_cambiar_password'] ?? 1
            ]);

            $usuario_id = $this->conn->lastInsertId();

            $this->conn->commit();

            logSeguridad('usuario_creado', "Usuario {$datos['username']} creado", null, 'info');

            return $usuario_id;

        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al crear usuario: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar usuario
     */
    public function update($id, $datos)
    {
        try {
            $this->conn->beginTransaction();

            $sql = "
                UPDATE {$this->table}
                SET perfil_id = :perfil_id,
                    username = :username,
                    email = :email,
                    nombre = :nombre,
                    apellido = :apellido,
                    telefono = :telefono,
                    rol = :rol,
                    updated_at = NOW()
                WHERE id = :id
            ";

            $stmt = $this->conn->prepare($sql);

            $result = $stmt->execute([
                ':id' => $id,
                ':perfil_id' => $datos['perfil_id'] ?? null,
                ':username' => $datos['username'],
                ':email' => $datos['email'],
                ':nombre' => $datos['nombre'],
                ':apellido' => $datos['apellido'] ?? null,
                ':telefono' => $datos['telefono'] ?? null,
                ':rol' => $datos['rol'] ?? 'usuario'
            ]);

            // Si se proporciona nueva contraseña
            $passwordUpdated = false;
            if (!empty($datos['password'])) {
                $this->updatePassword($id, $datos['password'], false); // Silenciamos log interno
                $passwordUpdated = true;
            }

            $this->conn->commit();

            // Los logs se ejecutan después del commit para evitar transacciones anidadas o bloqueos
            logSeguridad('usuario_actualizado', "Usuario ID: $id actualizado", $id, 'info');

            if ($passwordUpdated) {
                logSeguridad('password_cambiado', "Contraseña cambiada para usuario ID: $id", $id, 'info');
            }

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
        $password_hash = password_hash($nueva_password, PASSWORD_BCRYPT, ['cost' => BCRYPT_COST]);

        $stmt = $this->conn->prepare("
            UPDATE {$this->table}
            SET password_hash = :password_hash,
                debe_cambiar_password = 0,
                updated_at = NOW()
            WHERE id = :id
        ");

        $result = $stmt->execute([
            ':id' => $id,
            ':password_hash' => $password_hash
        ]);

        if ($result && $log) {
            logSeguridad('password_cambiado', "Contraseña cambiada para usuario ID: $id", $id, 'info');
        }

        return $result;
    }

    /**
     * Toggle estado activo
     */
    public function toggleEstado($id)
    {
        $stmt = $this->conn->prepare("
            UPDATE {$this->table}
            SET activo = NOT activo,
                updated_at = NOW()
            WHERE id = :id
        ");

        $result = $stmt->execute([':id' => $id]);

        if ($result) {
            logSeguridad('usuario_estado_cambiado', "Estado cambiado para usuario ID: $id", $id, 'info');
        }

        return $result;
    }

    /**
     * Eliminar usuario (soft delete)
     */
    public function delete($id)
    {
        $stmt = $this->conn->prepare("
            UPDATE {$this->table}
            SET deleted_at = NOW()
            WHERE id = :id
        ");

        $result = $stmt->execute([':id' => $id]);

        if ($result) {
            logSeguridad('usuario_eliminado', "Usuario ID: $id eliminado", $id, 'warning');
        }

        return $result;
    }

    /**
     * Verificar si existe username
     */
    public function existeUsername($username, $exclude_id = null)
    {
        $sql = "SELECT id FROM {$this->table} WHERE username = :username AND deleted_at IS NULL";

        if ($exclude_id) {
            $sql .= " AND id != :exclude_id";
        }

        $stmt = $this->conn->prepare($sql);
        $params = [':username' => $username];

        if ($exclude_id) {
            $params[':exclude_id'] = $exclude_id;
        }

        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }

    /**
     * Verificar si existe email
     */
    public function existeEmail($email, $exclude_id = null)
    {
        $sql = "SELECT id FROM {$this->table} WHERE email = :email AND deleted_at IS NULL";

        if ($exclude_id) {
            $sql .= " AND id != :exclude_id";
        }

        $stmt = $this->conn->prepare($sql);
        $params = [':email' => $email];

        if ($exclude_id) {
            $params[':exclude_id'] = $exclude_id;
        }

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
                bloqueado_hasta = IF(intentos_fallidos >= 4, DATE_ADD(NOW(), INTERVAL 30 MINUTE), NULL)
            WHERE (username = :username OR email = :email)
            AND deleted_at IS NULL
        ");

        $stmt->execute([':username' => $username, ':email' => $username]);
    }

    /**
     * Verificar si usuario está bloqueado
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
     * Limpiar intentos fallidos
     */
    public function limpiarIntentos($id)
    {
        $stmt = $this->conn->prepare("
            UPDATE {$this->table}
            SET intentos_fallidos = 0,
                bloqueado_hasta = NULL
            WHERE id = :id
        ");

        return $stmt->execute([':id' => $id]);
    }

    /**
     * Crear token de recuperación
     */
    public function crearTokenRecuperacion($email)
    {
        $token = generarTokenAleatorio(32);

        $stmt = $this->conn->prepare("
            UPDATE {$this->table}
            SET token_recuperacion = :token,
                token_expira = DATE_ADD(NOW(), INTERVAL 1 HOUR)
            WHERE email = :email
            AND deleted_at IS NULL
        ");

        $result = $stmt->execute([
            ':token' => $token,
            ':email' => $email
        ]);

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
     * Limpiar token de recuperación
     */
    public function limpiarTokenRecuperacion($id)
    {
        $stmt = $this->conn->prepare("
            UPDATE {$this->table}
            SET token_recuperacion = NULL,
                token_expira = NULL
            WHERE id = :id
        ");

        return $stmt->execute([':id' => $id]);
    }

    /**
     * Obtener estadísticas de usuarios
     */
    public function getEstadisticas()
    {
        $stmt = $this->conn->query("
            SELECT
                COUNT(*) as total,
                SUM(activo = 1) as activos,
                SUM(activo = 0) as inactivos,
                SUM(debe_cambiar_password = 1) as deben_cambiar_password
            FROM {$this->table}
            WHERE deleted_at IS NULL
        ");

        return $stmt->fetch();
    }
}
