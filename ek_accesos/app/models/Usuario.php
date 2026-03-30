<?php

class Usuario extends BaseModel
{
    protected $table = 'usuarios';

    public function findByEmail(string $email): ?array
    {
        return $this->queryOne(
            "SELECT * FROM usuarios WHERE email = ? AND deleted_at IS NULL LIMIT 1",
            [$email]
        );
    }

    public function findByUsername(string $username): ?array
    {
        return $this->queryOne(
            "SELECT * FROM usuarios WHERE username = ? AND deleted_at IS NULL LIMIT 1",
            [$username]
        );
    }

    public function findByToken(string $token): ?array
    {
        return $this->queryOne(
            "SELECT * FROM usuarios WHERE token_sesion = ? AND deleted_at IS NULL LIMIT 1",
            [$token]
        );
    }

    public function getAll(array $filters = []): array
    {
        $sql = "SELECT u.*,
                       e.nombre_completo AS empleado_nombre,
                       pn.nombre AS programa_nivel_nombre
                FROM usuarios u
                LEFT JOIN empleado_usuario eu ON eu.usuario_id = u.id
                LEFT JOIN empleados e ON e.id = eu.empleado_id
                LEFT JOIN empleado_programa_nivel epn ON epn.empleado_id = e.id AND epn.activo = 1
                LEFT JOIN programa_nivel pn ON pn.id = epn.programa_nivel_id
                WHERE u.deleted_at IS NULL";
        $params = [];

        if (!empty($filters['tipo'])) {
            $sql .= " AND u.tipo_usuario = ?";
            $params[] = $filters['tipo'];
        }
        if (!empty($filters['buscar'])) {
            $sql .= " AND (u.nombre LIKE ? OR u.email LIKE ? OR u.username LIKE ?)";
            $q = '%' . $filters['buscar'] . '%';
            $params[] = $q; $params[] = $q; $params[] = $q;
        }
        if (isset($filters['activo'])) {
            $sql .= " AND u.activo = ?";
            $params[] = $filters['activo'];
        }
        if (isset($filters['aprobado'])) {
            $sql .= " AND u.aprobado = ?";
            $params[] = $filters['aprobado'];
        }

        $sql .= " ORDER BY u.created_at DESC";
        return $this->query($sql, $params);
    }

    public function createUser(array $data): int
    {
        $data['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        unset($data['password']);
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->create($data);
    }

    public function verifyPassword(string $plain, string $hash): bool
    {
        return password_verify($plain, $hash);
    }

    public function incrementLoginAttempts(int $id): void
    {
        $this->execute(
            "UPDATE usuarios SET intentos_fallidos = intentos_fallidos + 1 WHERE id = ?",
            [$id]
        );
        $user = $this->find($id);
        if ($user && $user['intentos_fallidos'] >= 5) {
            $this->execute(
                "UPDATE usuarios SET bloqueado_hasta = DATE_ADD(NOW(), INTERVAL 30 MINUTE) WHERE id = ?",
                [$id]
            );
        }
    }

    public function resetLoginAttempts(int $id): void
    {
        $this->execute(
            "UPDATE usuarios SET intentos_fallidos = 0, bloqueado_hasta = NULL, ultimo_acceso = NOW() WHERE id = ?",
            [$id]
        );
    }

    public function setToken(int $id): string
    {
        $token = bin2hex(random_bytes(32));
        $this->execute("UPDATE usuarios SET token_sesion = ? WHERE id = ?", [$token, $id]);
        return $token;
    }

    public function isBlocked(array $user): bool
    {
        if (!$user['bloqueado_hasta']) return false;
        return strtotime($user['bloqueado_hasta']) > time();
    }

    public function existeEmail(string $email, int $excludeId = 0): bool
    {
        $sql = "SELECT COUNT(*) FROM usuarios WHERE email = ? AND deleted_at IS NULL";
        $params = [$email];
        if ($excludeId) { $sql .= " AND id != ?"; $params[] = $excludeId; }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function existeUsername(string $username, int $excludeId = 0): bool
    {
        $sql = "SELECT COUNT(*) FROM usuarios WHERE username = ? AND deleted_at IS NULL";
        $params = [$username];
        if ($excludeId) { $sql .= " AND id != ?"; $params[] = $excludeId; }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function aprobar(int $id, int $aprobadoPor): bool
    {
        return $this->execute(
            "UPDATE usuarios SET aprobado = 1, activo = 1, aprobado_por = ?, fecha_aprobacion = NOW() WHERE id = ?",
            [$aprobadoPor, $id]
        );
    }
}
