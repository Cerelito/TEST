<?php

class Requisitor extends BaseModel
{
    protected $table = 'requisitores';

    public function getAll(array $filters = []): array
    {
        $sql = "SELECT r.*,
                       e.nombre_completo, e.email AS emp_email, e.puesto, e.departamento,
                       emp.nombre AS empresa_nombre,
                       COUNT(DISTINCT ecc.id) AS total_cc
                FROM requisitores r
                JOIN empleados e ON e.id = r.empleado_id
                LEFT JOIN empresas emp ON emp.id_empresa = e.empresa_id
                LEFT JOIN empleado_cc_permisos ecc ON ecc.empleado_id = e.id
                    AND ecc.tipo_documento IN ('REQ','AMBOS') AND ecc.activo = 1
                WHERE e.deleted_at IS NULL";
        $params = [];

        if (!empty($filters['empresa_id'])) {
            $sql .= " AND e.empresa_id = ?";
            $params[] = $filters['empresa_id'];
        }
        if (!empty($filters['buscar'])) {
            $sql .= " AND (e.nombre_completo LIKE ? OR e.email LIKE ?)";
            $q = '%' . $filters['buscar'] . '%';
            $params[] = $q; $params[] = $q;
        }
        if (isset($filters['activo'])) {
            $sql .= " AND r.activo = ?";
            $params[] = $filters['activo'];
        }

        $sql .= " GROUP BY r.id ORDER BY e.nombre_completo ASC";
        return $this->query($sql, $params);
    }

    public function getWithCentrosCosto(int $requisitorId): array
    {
        $req = $this->queryOne(
            "SELECT r.*, e.nombre_completo, e.email AS emp_email, e.puesto, e.empresa_id,
                    emp.nombre AS empresa_nombre
             FROM requisitores r
             JOIN empleados e ON e.id = r.empleado_id
             LEFT JOIN empresas emp ON emp.id_empresa = e.empresa_id
             WHERE r.id = ? LIMIT 1",
            [$requisitorId]
        );
        if ($req) {
            $req['centros_costo'] = $this->query("
                SELECT ecc.*, cc.codigo, cc.descripcion, emp.nombre AS empresa_nombre
                FROM empleado_cc_permisos ecc
                JOIN centros_costo cc ON cc.id_cc = ecc.id_cc
                JOIN empresas emp ON emp.id_empresa = ecc.id_empresa
                WHERE ecc.empleado_id = ? AND ecc.tipo_documento IN ('REQ','AMBOS') AND ecc.activo = 1
                ORDER BY emp.nombre, cc.codigo
            ", [$req['empleado_id']]);
        }
        return $req ?? [];
    }

    public function asignar(int $empleadoId, array $data): int
    {
        $existing = $this->queryOne(
            "SELECT id FROM requisitores WHERE empleado_id = ?",
            [$empleadoId]
        );
        if ($existing) {
            $this->execute(
                "UPDATE requisitores SET activo=?, puede_elab=?, puede_vobo=?, puede_aut=? WHERE id=?",
                [$data['activo']??1, $data['puede_elab']??1, $data['puede_vobo']??0, $data['puede_aut']??0, $existing['id']]
            );
            return $existing['id'];
        }
        return $this->create([
            'empleado_id' => $empleadoId,
            'activo'      => $data['activo'] ?? 1,
            'puede_elab'  => $data['puede_elab'] ?? 1,
            'puede_vobo'  => $data['puede_vobo'] ?? 0,
            'puede_aut'   => $data['puede_aut'] ?? 0,
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
