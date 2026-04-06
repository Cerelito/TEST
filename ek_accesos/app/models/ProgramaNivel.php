<?php

class ProgramaNivel extends BaseModel
{
    protected $table = 'programa_nivel';

    public function getAll(array $filters = []): array
    {
        $sql = "SELECT pn.*,
                       COUNT(DISTINCT epn.empleado_id) AS total_empleados,
                       COUNT(DISTINCT pnp.id) AS total_permisos
                FROM programa_nivel pn
                LEFT JOIN empleado_programa_nivel epn ON epn.programa_nivel_id = pn.id
                LEFT JOIN programa_nivel_permisos pnp ON pnp.programa_nivel_id = pn.id AND pnp.activo = 1
                WHERE pn.deleted_at IS NULL";
        $params = [];

        if (!empty($filters['buscar'])) {
            $sql .= " AND (pn.nombre LIKE ? OR pn.descripcion LIKE ?)";
            $q = '%' . $filters['buscar'] . '%';
            $params[] = $q; $params[] = $q;
        }
        if (isset($filters['activo'])) {
            $sql .= " AND pn.activo = ?";
            $params[] = $filters['activo'];
        }

        $sql .= " GROUP BY pn.id ORDER BY pn.nivel ASC";
        return $this->query($sql, $params);
    }

    /** Build module tree with permission status for this programa_nivel_id */
    public function getArbolModulos(?int $programaNivelId = null): array
    {
        $modulos = $this->query(
            "SELECT m.* FROM modulos_erp m WHERE m.activo = 1 ORDER BY m.parent_id ASC, m.orden ASC"
        );

        // Get granted permissions for this nivel
        $granted = [];
        if ($programaNivelId) {
            $perms = $this->query(
                "SELECT modulo_erp_id FROM programa_nivel_permisos WHERE programa_nivel_id = ? AND activo = 1",
                [$programaNivelId]
            );
            foreach ($perms as $p) {
                $granted[$p['modulo_erp_id']] = true;
            }
        }

        // Index modules and add 'granted' flag
        $indexed = [];
        foreach ($modulos as $m) {
            $m['children'] = [];
            $m['granted']  = isset($granted[$m['id']]);
            $indexed[$m['id']] = $m;
        }

        // Build tree
        $tree = [];
        foreach ($indexed as $id => &$m) {
            if ($m['parent_id']) {
                if (isset($indexed[$m['parent_id']])) {
                    $indexed[$m['parent_id']]['children'][] = &$m;
                }
            } else {
                $tree[] = &$m;
            }
        }
        unset($m);
        return $tree;
    }

    public function savePermisos(int $programaNivelId, array $moduloIds): void
    {
        // Delete existing
        $this->execute(
            "DELETE FROM programa_nivel_permisos WHERE programa_nivel_id = ?",
            [$programaNivelId]
        );
        // Insert new
        if (!empty($moduloIds)) {
            $stmt = $this->pdo->prepare(
                "INSERT INTO programa_nivel_permisos (programa_nivel_id, modulo_erp_id, activo) VALUES (?, ?, 1)"
            );
            foreach ($moduloIds as $mid) {
                $mid = (int)$mid;
                if ($mid > 0) $stmt->execute([$programaNivelId, $mid]);
            }
        }
    }

    public function getPermisosFlat(int $programaNivelId): array
    {
        $rows = $this->query(
            "SELECT modulo_erp_id FROM programa_nivel_permisos WHERE programa_nivel_id = ? AND activo = 1",
            [$programaNivelId]
        );
        return array_column($rows, 'modulo_erp_id');
    }

    public function getSelectList(): array
    {
        return $this->query(
            "SELECT id, nivel, nombre FROM programa_nivel WHERE deleted_at IS NULL AND activo = 1 ORDER BY nivel ASC"
        );
    }
}
