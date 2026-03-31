<?php

class Comprador extends BaseModel
{
    protected $table = 'compradores';

    public function getAll(array $filters = []): array
    {
        $sql = "SELECT c.*,
                       e.nombre, e.email AS emp_email, e.puesto,
                       emp.nombre AS empresa_nombre,
                       COUNT(DISTINCT ecc.id) AS total_cc
                FROM compradores c
                JOIN empleados e ON e.id = c.empleado_id
                LEFT JOIN empresas emp ON emp.id = e.empresa_id
                LEFT JOIN empleado_cc ecc ON ecc.empleado_id = e.id
                    AND ecc.tipo IN ('OC','AMBOS') AND ecc.activo = 1
                WHERE e.deleted_at IS NULL";
        $params = [];

        if (!empty($filters['empresa_id'])) {
            $sql .= " AND e.empresa_id = ?";
            $params[] = $filters['empresa_id'];
        }
        if (!empty($filters['buscar'])) {
            $sql .= " AND (e.nombre LIKE ? OR e.email LIKE ?)";
            $q = '%' . $filters['buscar'] . '%';
            $params[] = $q; $params[] = $q;
        }
        if (isset($filters['activo'])) {
            $sql .= " AND c.activo = ?";
            $params[] = $filters['activo'];
        }

        $sql .= " GROUP BY c.id ORDER BY e.nombre ASC";
        return $this->query($sql, $params);
    }

    public function getWithCentrosCosto(int $compradorId): ?array
    {
        $comp = $this->queryOne(
            "SELECT c.*, e.nombre, e.email AS emp_email, e.puesto, e.empresa_id,
                    emp.nombre AS empresa_nombre
             FROM compradores c
             JOIN empleados e ON e.id = c.empleado_id
             LEFT JOIN empresas emp ON emp.id = e.empresa_id
             WHERE c.id = ? LIMIT 1",
            [$compradorId]
        );
        if ($comp) {
            $comp['centros_costo'] = $this->query("
                SELECT ecc.*, cc.codigo, cc.descripcion, emp2.nombre AS empresa_nombre
                FROM empleado_cc ecc
                JOIN centros_costo cc ON cc.id = ecc.cc_id
                JOIN empresas emp2 ON emp2.id = cc.empresa_id
                WHERE ecc.empleado_id = ? AND ecc.tipo IN ('OC','AMBOS') AND ecc.activo = 1
                ORDER BY emp2.nombre, cc.codigo
            ", [$comp['empleado_id']]);
        }
        return $comp;
    }

    public function asignar(int $empleadoId, array $data): int
    {
        $existing = $this->queryOne(
            "SELECT id FROM compradores WHERE empleado_id = ?",
            [$empleadoId]
        );
        if ($existing) {
            $this->execute(
                "UPDATE compradores SET activo = ? WHERE id = ?",
                [$data['activo'] ?? 1, $existing['id']]
            );
            return $existing['id'];
        }
        return $this->create([
            'empleado_id' => $empleadoId,
            'activo'      => $data['activo'] ?? 1,
        ]);
    }

    public function eliminar(int $empleadoId): bool
    {
        return $this->execute(
            "UPDATE compradores SET activo = 0 WHERE empleado_id = ?",
            [$empleadoId]
        );
    }
}
