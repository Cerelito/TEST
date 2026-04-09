<?php
class ProjectModel
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function all(bool $soloActivos = false): array
    {
        $where = $soloActivos ? 'WHERE p.activo = 1' : '';
        return $this->db->fetchAll(
            "SELECT p.*, u.nombre AS creador
             FROM projects p JOIN users u ON u.id = p.created_by
             {$where} ORDER BY p.nombre"
        );
    }

    public function find(int $id): array|false
    {
        return $this->db->fetchOne(
            'SELECT p.*, u.nombre AS creador FROM projects p JOIN users u ON u.id = p.created_by WHERE p.id = :id',
            [':id' => $id]
        );
    }

    public function create(array $data, int $userId): string
    {
        return $this->db->insert(
            'INSERT INTO projects (nombre, descripcion, color, created_by) VALUES (:n, :d, :c, :u)',
            [':n' => $data['nombre'], ':d' => $data['descripcion'] ?? null, ':c' => $data['color'] ?? '#5563DE', ':u' => $userId]
        );
    }

    public function update(int $id, array $data): int
    {
        return $this->db->execute(
            'UPDATE projects SET nombre = :n, descripcion = :d, color = :c, activo = :a WHERE id = :id',
            [':n' => $data['nombre'], ':d' => $data['descripcion'] ?? null, ':c' => $data['color'], ':a' => $data['activo'] ?? 1, ':id' => $id]
        );
    }

    public function delete(int $id): int
    {
        return $this->db->execute('UPDATE projects SET activo = 0 WHERE id = :id', [':id' => $id]);
    }

    public function listForSelect(): array
    {
        return $this->db->fetchAll('SELECT id, nombre, color FROM projects WHERE activo = 1 ORDER BY nombre');
    }

    public function stats(int $id): array
    {
        return $this->db->fetchOne(
            'SELECT
               COUNT(*) AS total,
               SUM(estatus_id = 4) AS completadas,
               SUM(estatus_id = 5) AS canceladas,
               ROUND(AVG(progreso), 1) AS avance_pct
             FROM tasks WHERE proyecto_id = :id AND padre_id IS NULL',
            [':id' => $id]
        ) ?: [];
    }
}
