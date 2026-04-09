<?php
class UserModel
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function all(): array
    {
        return $this->db->fetchAll(
            'SELECT u.id, u.nombre, u.email, u.activo, u.ultimo_login, r.nombre AS rol
             FROM users u JOIN roles r ON r.id = u.rol_id ORDER BY u.nombre'
        );
    }

    public function find(int $id): array|false
    {
        return $this->db->fetchOne(
            'SELECT u.*, r.slug AS rol_slug, r.nombre AS rol_nombre
             FROM users u JOIN roles r ON r.id = u.rol_id WHERE u.id = :id',
            [':id' => $id]
        );
    }

    public function create(array $data): string
    {
        return $this->db->insert(
            'INSERT INTO users (rol_id, nombre, email, password) VALUES (:rol, :nombre, :email, :pass)',
            [
                ':rol'    => $data['rol_id'],
                ':nombre' => $data['nombre'],
                ':email'  => $data['email'],
                ':pass'   => password_hash($data['password'], PASSWORD_BCRYPT),
            ]
        );
    }

    public function update(int $id, array $data): int
    {
        $fields = [];
        $params = [':id' => $id];
        foreach (['nombre', 'email', 'rol_id', 'activo'] as $f) {
            if (isset($data[$f])) {
                $fields[] = "{$f} = :{$f}";
                $params[":{$f}"] = $data[$f];
            }
        }
        if (!empty($data['password'])) {
            $fields[] = 'password = :pass';
            $params[':pass'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        if (empty($fields)) return 0;
        return $this->db->execute('UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = :id', $params);
    }

    public function roles(): array
    {
        return $this->db->fetchAll('SELECT * FROM roles ORDER BY id');
    }

    public function listForSelect(): array
    {
        return $this->db->fetchAll('SELECT id, nombre FROM users WHERE activo = 1 ORDER BY nombre');
    }
}
