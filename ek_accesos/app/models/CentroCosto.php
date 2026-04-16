<?php

class CentroCosto extends BaseModel
{
    protected $table      = 'centros_costo';
    protected $primaryKey = 'id';

    public function getAllWithEmpresa(array $filters = []): array
    {
        $sql = "SELECT cc.*, emp.nombre AS empresa_nombre,
                       (SELECT COUNT(*) FROM empleado_cc ecc WHERE ecc.centro_costo_id = cc.id) AS total_empleados
                FROM centros_costo cc
                JOIN empresas emp ON emp.id = cc.empresa_id
                WHERE 1=1";
        $params = [];

        if (!empty($filters['empresa_id'])) {
            $sql .= " AND cc.empresa_id = ?";
            $params[] = $filters['empresa_id'];
        }
        if (!empty($filters['buscar'])) {
            $sql .= " AND (cc.codigo LIKE ? OR cc.descripcion LIKE ?)";
            $q = '%' . $filters['buscar'] . '%';
            $params[] = $q; $params[] = $q;
        }
        if (isset($filters['activo'])) {
            $sql .= " AND cc.activo = ?";
            $params[] = $filters['activo'];
        }

        $sql .= " ORDER BY emp.nombre, cc.codigo";
        return $this->query($sql, $params);
    }

    public function getByEmpresa(int $empresaId): array
    {
        return $this->query(
            "SELECT * FROM centros_costo WHERE empresa_id = ? AND activo = 1 ORDER BY codigo",
            [$empresaId]
        );
    }

    public function getEmpresas(): array
    {
        return $this->query("SELECT id, nombre, codigo FROM empresas WHERE activo = 1 ORDER BY nombre");
    }
}
