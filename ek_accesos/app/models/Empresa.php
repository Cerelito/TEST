<?php

class Empresa extends BaseModel
{
    protected $table      = 'empresas';
    protected $primaryKey = 'id';

    public function getAll(array $filters = []): array
    {
        $sql    = "SELECT e.*,
                       (SELECT COUNT(*) FROM centros_costo cc WHERE cc.empresa_id = e.id AND cc.activo = 1) AS total_cc,
                       (SELECT COUNT(*) FROM empleados em WHERE em.empresa_id = e.id AND em.deleted_at IS NULL) AS total_empleados
                   FROM empresas e WHERE 1=1";
        $params = [];

        if (!empty($filters['buscar'])) {
            $sql    .= " AND (e.nombre LIKE ? OR e.codigo LIKE ?)";
            $q       = '%' . $filters['buscar'] . '%';
            $params[] = $q; $params[] = $q;
        }
        if (isset($filters['activo']) && $filters['activo'] !== '') {
            $sql    .= " AND e.activo = ?";
            $params[] = (int)$filters['activo'];
        }

        $sql .= " ORDER BY e.nombre ASC";
        return $this->query($sql, $params);
    }

    public function existeNombre(string $nombre, int $excludeId = 0): bool
    {
        $row = $this->queryOne(
            "SELECT id FROM empresas WHERE nombre = ? AND id != ?",
            [$nombre, $excludeId]
        );
        return (bool)$row;
    }

    public function tieneDependencias(int $id): bool
    {
        $cc = $this->queryOne("SELECT id FROM centros_costo WHERE empresa_id = ? LIMIT 1", [$id]);
        $em = $this->queryOne("SELECT id FROM empleados WHERE empresa_id = ? AND deleted_at IS NULL LIMIT 1", [$id]);
        return (bool)$cc || (bool)$em;
    }

    public function toggleActivo(int $id): bool
    {
        return $this->execute(
            "UPDATE empresas SET activo = IF(activo=1,0,1) WHERE id = ?",
            [$id]
        );
    }
}
