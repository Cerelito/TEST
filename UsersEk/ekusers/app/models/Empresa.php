<?php

class Empresa extends BaseModel
{
    protected $table      = 'empresas';
    protected $primaryKey = 'id';

    public function getAll(array $filters = []): array
    {
        $sql = "SELECT e.*,
                       COUNT(DISTINCT cc.id)   AS total_cc,
                       COUNT(DISTINCT emp.id)  AS total_empleados
                FROM empresas e
                LEFT JOIN centros_costo cc  ON cc.empresa_id = e.id  AND cc.activo = 1
                LEFT JOIN empleados     emp ON emp.empresa_id = e.id AND emp.deleted_at IS NULL AND emp.activo = 1
                WHERE 1=1";
        $params = [];

        if (!empty($filters['buscar'])) {
            $sql     .= " AND (e.nombre LIKE ? OR e.codigo LIKE ?)";
            $q        = '%' . $filters['buscar'] . '%';
            $params[] = $q;
            $params[] = $q;
        }
        if (isset($filters['activo']) && $filters['activo'] !== '') {
            $sql     .= " AND e.activo = ?";
            $params[] = (int)$filters['activo'];
        }

        $sql .= " GROUP BY e.id ORDER BY e.nombre ASC";
        return $this->query($sql, $params);
    }

    public function existeNombre(string $nombre, int $excludeId = 0): bool
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) FROM empresas WHERE nombre = ? AND id != ?"
        );
        $stmt->execute([$nombre, $excludeId]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function tieneDependencias(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) FROM centros_costo WHERE empresa_id = ? LIMIT 1"
        );
        $stmt->execute([$id]);
        return (int)$stmt->fetchColumn() > 0;
    }
}
