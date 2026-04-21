<?php

class Requisitor extends BaseModel
{
    protected $table = 'requisitores';

    public function getAll(array $filters = []): array
    {
        $sql = "SELECT r.*,
                       e.nombre, e.email AS emp_email, e.puesto,
                       emp.nombre AS empresa_nombre,
                       COUNT(DISTINCT ecc.id) AS total_cc
                FROM requisitores r
                JOIN empleados e ON e.id = r.empleado_id
                LEFT JOIN empresas emp ON emp.id = e.empresa_id
                LEFT JOIN empleado_cc ecc ON ecc.empleado_id = e.id
                    AND ecc.tipo IN ('REQ','AMBOS') AND ecc.activo = 1
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
            $sql .= " AND r.activo = ?";
            $params[] = $filters['activo'];
        }

        $sql .= " GROUP BY r.id ORDER BY e.nombre ASC";
        return $this->query($sql, $params);
    }

    public function getWithCentrosCosto(int $requisitorId): ?array
    {
        $req = $this->queryOne(
            "SELECT r.*, e.nombre, e.email AS emp_email, e.puesto, e.empresa_id,
                    emp.nombre AS empresa_nombre
             FROM requisitores r
             JOIN empleados e ON e.id = r.empleado_id
             LEFT JOIN empresas emp ON emp.id = e.empresa_id
             WHERE r.id = ? LIMIT 1",
            [$requisitorId]
        );
        if ($req) {
            $req['centros_costo'] = $this->query("
                SELECT ecc.*, cc.codigo, cc.descripcion, emp2.nombre AS empresa_nombre
                FROM empleado_cc ecc
                JOIN centros_costo cc ON cc.id = ecc.cc_id
                JOIN empresas emp2 ON emp2.id = cc.empresa_id
                WHERE ecc.empleado_id = ? AND ecc.tipo IN ('REQ','AMBOS') AND ecc.activo = 1
                ORDER BY emp2.nombre, cc.codigo
            ", [$req['empleado_id']]);
        }
        return $req;
    }

    public function asignar(int $empleadoId, array $data): int
    {
        $existing = $this->queryOne(
            "SELECT id FROM requisitores WHERE empleado_id = ?",
            [$empleadoId]
        );
        if ($existing) {
            $this->execute(
                "UPDATE requisitores SET activo = ? WHERE id = ?",
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
            "UPDATE requisitores SET activo = 0 WHERE empleado_id = ?",
            [$empleadoId]
        );
    }
}
