<?php

class CentroCosto extends BaseModel
{
    protected $table = 'centros_costo';
    protected $primaryKey = 'id_cc';

    public function getAllWithEmpresa(array $filters = []): array
    {
        $sql = "SELECT cc.*, emp.nombre AS empresa_nombre
                FROM centros_costo cc
                JOIN empresas emp ON emp.id_empresa = cc.id_empresa
                WHERE 1=1";
        $params = [];

        if (!empty($filters['id_empresa'])) {
            $sql .= " AND cc.id_empresa = ?";
            $params[] = $filters['id_empresa'];
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
            "SELECT * FROM centros_costo WHERE id_empresa = ? AND activo = 1 ORDER BY codigo",
            [$empresaId]
        );
    }

    public function getEmpresas(): array
    {
        return $this->query("SELECT * FROM empresas WHERE activo = 1 ORDER BY nombre");
    }
}
