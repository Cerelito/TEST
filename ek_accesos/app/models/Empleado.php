<?php

class Empleado extends BaseModel
{
    protected $table = 'empleados';

    // ─────────────────────────────────────────────────────────────────────────
    // Listing / search
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Return all employees with empresa name, programa nivel, and CC count.
     * Supports filters: empresa_id, buscar, activo, tipo.
     */
    public function getAll(array $filters = []): array
    {
        $sql = "SELECT e.*,
                       emp.nombre  AS empresa_nombre,
                       pn.nombre   AS programa_nivel_nombre,
                       pn.id       AS programa_nivel_id,
                       CASE WHEN r.id IS NOT NULL THEN 1 ELSE 0 END AS es_requisitor,
                       CASE WHEN c.id IS NOT NULL THEN 1 ELSE 0 END AS es_comprador,
                       (SELECT COUNT(*) FROM empleado_cc ecc2
                        WHERE ecc2.empleado_id = e.id AND ecc2.activo = 1) AS total_cc
                FROM empleados e
                LEFT JOIN empresas emp ON emp.id = e.empresa_id
                LEFT JOIN empleado_programa_nivel epn
                       ON epn.empleado_id = e.id
                LEFT JOIN programa_nivel pn ON pn.id = epn.programa_nivel_id
                LEFT JOIN requisitores r ON r.empleado_id = e.id AND r.activo = 1
                LEFT JOIN compradores  c ON c.empleado_id = e.id AND c.activo = 1
                WHERE e.deleted_at IS NULL";

        $params = [];

        if (!empty($filters['empresa_id'])) {
            $sql    .= " AND e.empresa_id = ?";
            $params[] = $filters['empresa_id'];
        }
        if (!empty($filters['buscar'])) {
            $sql    .= " AND (e.nombre LIKE ? OR e.apellido_paterno LIKE ? OR e.apellido_materno LIKE ? OR e.email LIKE ? OR e.puesto LIKE ? OR e.user_id LIKE ?)";
            $q       = '%' . $filters['buscar'] . '%';
            $params[] = $q; $params[] = $q; $params[] = $q;
            $params[] = $q; $params[] = $q; $params[] = $q;
        }
        if (isset($filters['aprobado']) && $filters['aprobado'] !== '') {
            $sql    .= " AND e.aprobado = ?";
            $params[] = (int)$filters['aprobado'];
        }
        if (isset($filters['activo']) && $filters['activo'] !== '') {
            $sql    .= " AND e.activo = ?";
            $params[] = (int)$filters['activo'];
        }

        $sql .= " ORDER BY e.nombre ASC";
        return $this->query($sql, $params);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Single record
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Single employee with empresa, programa nivel, and CC assignments.
     */
    public function getById(int $id): ?array
    {
        $emp = $this->queryOne("
            SELECT e.*,
                   emp.nombre AS empresa_nombre,
                   pn.nombre  AS programa_nivel_nombre,
                   pn.id      AS programa_nivel_id,
                   CASE WHEN r.id IS NOT NULL THEN 1 ELSE 0 END AS es_requisitor,
                   CASE WHEN c.id IS NOT NULL THEN 1 ELSE 0 END AS es_comprador
            FROM empleados e
            LEFT JOIN empresas emp ON emp.id = e.empresa_id
            LEFT JOIN empleado_programa_nivel epn ON epn.empleado_id = e.id
            LEFT JOIN programa_nivel pn ON pn.id = epn.programa_nivel_id
            LEFT JOIN requisitores r ON r.empleado_id = e.id AND r.activo = 1
            LEFT JOIN compradores  c ON c.empleado_id = e.id AND c.activo = 1
            WHERE e.id = ? AND e.deleted_at IS NULL
            LIMIT 1
        ", [$id]);

        if ($emp) {
            $emp['centros_costo'] = $this->getCentrosCosto($id);
        }
        return $emp;
    }

    /**
     * Alias kept for backward-compat with older controller references.
     */
    public function getWithDetails(int $id): ?array
    {
        return $this->getById($id);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Create
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Insert into `empleados`, optionally assign programa_nivel_id.
     * Returns the new employee ID.
     */
    public function create(array $data): int
    {
        // Extract programa_nivel_id if bundled in $data
        $programaNivelId = null;
        if (isset($data['programa_nivel_id'])) {
            $programaNivelId = (int)$data['programa_nivel_id'];
            unset($data['programa_nivel_id']);
        }

        $cols         = implode(', ', array_map(fn($c) => "`$c`", array_keys($data)));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $stmt = $this->pdo->prepare(
            "INSERT INTO `{$this->table}` ($cols) VALUES ($placeholders)"
        );
        $stmt->execute(array_values($data));
        $newId = (int)$this->pdo->lastInsertId();

        if ($programaNivelId) {
            $this->asignarProgramaNivel($newId, $programaNivelId, currentUserId() ?? 0);
        }

        return $newId;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Update
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Update empleado row; if programa_nivel_id is in $data, reassign it too.
     */
    public function update($id, array $data): bool
    {
        $programaNivelId = null;
        if (isset($data['programa_nivel_id'])) {
            $programaNivelId = (int)$data['programa_nivel_id'];
            unset($data['programa_nivel_id']);
        }

        $set  = implode(', ', array_map(fn($c) => "`$c` = ?", array_keys($data)));
        $stmt = $this->pdo->prepare(
            "UPDATE `{$this->table}` SET $set WHERE `{$this->primaryKey}` = ?"
        );
        $result = $stmt->execute([...array_values($data), $id]);

        if ($programaNivelId) {
            $this->asignarProgramaNivel((int)$id, $programaNivelId, currentUserId() ?? 0);
        }

        return $result;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Organigrama (hierarchical)
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Return a recursive hierarchical array with subordinados[] for org-chart
     * rendering, filtered by empresa_id.
     */
    public function getForOrganigrama(int $empresaId): array
    {
        return $this->query("
            SELECT e.id, e.nombre, e.puesto, e.email, e.jefe_id, e.activo,
                   emp.nombre AS empresa_nombre,
                   pn.nombre  AS programa_nivel,
                   u.id       AS user_id,
                   jefe.nombre AS jefe_nombre,
                   IF(r.empleado_id IS NOT NULL, 1, 0) AS es_requisitor,
                   IF(c.empleado_id IS NOT NULL, 1, 0) AS es_comprador
            FROM empleados e
            LEFT JOIN empresas emp ON emp.id = e.empresa_id
            LEFT JOIN empleado_programa_nivel epn ON epn.empleado_id = e.id
            LEFT JOIN programa_nivel pn ON pn.id = epn.programa_nivel_id
            LEFT JOIN usuarios u ON u.empleado_id = e.id AND u.deleted_at IS NULL
            LEFT JOIN empleados jefe ON jefe.id = e.jefe_id AND jefe.deleted_at IS NULL
            LEFT JOIN requisitores r ON r.empleado_id = e.id AND r.activo = 1
            LEFT JOIN compradores c ON c.empleado_id = e.id AND c.activo = 1
            WHERE e.empresa_id = ? AND e.deleted_at IS NULL AND e.activo = 1
            ORDER BY e.nombre ASC
        ", [$empresaId]);
    }

    /**
     * Build recursive hierarchy from a flat list.
     * Each node gets a `subordinados` key.
     */
    private function buildHierarchy(array $rows, ?int $parentId = null): array
    {
        $branch = [];
        foreach ($rows as $row) {
            $rowJefe = $row['jefe_id'] ? (int)$row['jefe_id'] : null;
            if ($rowJefe === $parentId) {
                $row['subordinados'] = $this->buildHierarchy($rows, (int)$row['id']);
                $branch[]            = $row;
            }
        }
        return $branch;
    }

    /**
     * Legacy flat list of organigrama nodes (kept for backward-compat).
     */
    public function getOrganigramaNodos(): array
    {
        return $this->query("
            SELECT e.id, e.nombre, e.puesto, e.jefe_id,
                   emp.nombre AS empresa_nombre
            FROM empleados e
            LEFT JOIN empresas emp ON emp.id = e.empresa_id
            WHERE e.deleted_at IS NULL AND e.activo = 1
            ORDER BY e.nombre ASC
        ");
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Centros de Costo
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Return empleado_cc join centros_costo rows for this employee.
     */
    public function getCentrosCosto(int $empleadoId): array
    {
        return $this->query("
            SELECT ecc.*,
                   cc.codigo, cc.descripcion,
                   emp.nombre AS empresa_nombre,
                   emp.id     AS empresa_id
            FROM empleado_cc ecc
            JOIN centros_costo cc  ON cc.id  = ecc.cc_id
            JOIN empresas      emp ON emp.id = cc.empresa_id
            WHERE ecc.empleado_id = ? AND ecc.activo = 1
            ORDER BY emp.nombre, cc.codigo, ecc.tipo, ecc.tipo_insumo
        ", [$empleadoId]);
    }

    /**
     * Replace all CC assignments for an employee.
     * $ccs is an array of arrays, each with keys:
     *   cc_id, tipo (REQ/OC/AMBOS), tipo_insumo (0-9), elab, vobo, aut, monto
     */
    public function saveCentrosCosto(int $empleadoId, array $ccs): void
    {
        // Soft-delete existing
        $this->execute(
            "UPDATE empleado_cc SET activo = 0 WHERE empleado_id = ?",
            [$empleadoId]
        );

        if (empty($ccs)) {
            return;
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO empleado_cc
                (empleado_id, cc_id, tipo, tipo_insumo, elab, vobo, aut, monto, activo)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)
            ON DUPLICATE KEY UPDATE
                elab   = VALUES(elab),
                vobo   = VALUES(vobo),
                aut    = VALUES(aut),
                monto  = VALUES(monto),
                activo = 1
        ");

        foreach ($ccs as $cc) {
            $ccId = (int)($cc['cc_id'] ?? 0);
            if (!$ccId) continue;

            $tipo = in_array($cc['tipo'] ?? '', ['REQ', 'OC', 'AMBOS'])
                ? $cc['tipo']
                : 'AMBOS';

            $tipoInsumo = (int)($cc['tipo_insumo'] ?? 0);
            if ($tipoInsumo < 0 || $tipoInsumo > 9) $tipoInsumo = 0;

            $stmt->execute([
                $empleadoId,
                $ccId,
                $tipo,
                $tipoInsumo,
                isset($cc['elab']) ? (int)$cc['elab'] : 0,
                isset($cc['vobo']) ? (int)$cc['vobo'] : 0,
                isset($cc['aut'])  ? (int)$cc['aut']  : 0,
                (float)($cc['monto'] ?? 0),
            ]);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Programa Nivel
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Assign (or replace) the programa nivel for an employee.
     */
    public function asignarProgramaNivel(int $empleadoId, int $programaNivelId, int $asignadoPor): void
    {
        // Remove any existing assignment
        $this->execute(
            "DELETE FROM empleado_programa_nivel WHERE empleado_id = ?",
            [$empleadoId]
        );
        $this->execute(
            "INSERT INTO empleado_programa_nivel
                 (empleado_id, programa_nivel_id, asignado_por)
             VALUES (?, ?, ?)",
            [$empleadoId, $programaNivelId, $asignadoPor]
        );
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Dropdowns
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Simple list of empresas for <select> dropdowns.
     */
    public function getEmpresasList(): array
    {
        return $this->query(
            "SELECT id, nombre, codigo FROM empresas WHERE activo = 1 ORDER BY nombre ASC"
        );
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Requisitores / Compradores
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Employees in the requisitores table with their CC data.
     */
    public function getRequisitores(): array
    {
        return $this->query("
            SELECT e.*,
                   emp.nombre AS empresa_nombre,
                   r.id       AS requisitor_id,
                   r.activo   AS req_activo,
                   COUNT(DISTINCT ecc.id) AS total_cc
            FROM requisitores r
            JOIN empleados e   ON e.id  = r.empleado_id
            LEFT JOIN empresas emp ON emp.id = e.empresa_id
            LEFT JOIN empleado_cc ecc
                   ON ecc.empleado_id = e.id
                  AND ecc.tipo IN ('REQ','AMBOS')
                  AND ecc.activo = 1
            WHERE e.deleted_at IS NULL AND r.activo = 1
            GROUP BY r.id
            ORDER BY e.nombre ASC
        ");
    }

    /**
     * Employees in the compradores table with their CC data.
     */
    public function getCompradores(): array
    {
        return $this->query("
            SELECT e.*,
                   emp.nombre AS empresa_nombre,
                   c.id       AS comprador_id,
                   c.activo   AS comp_activo,
                   COUNT(DISTINCT ecc.id) AS total_cc
            FROM compradores c
            JOIN empleados e   ON e.id  = c.empleado_id
            LEFT JOIN empresas emp ON emp.id = e.empresa_id
            LEFT JOIN empleado_cc ecc
                   ON ecc.empleado_id = e.id
                  AND ecc.tipo IN ('OC','AMBOS')
                  AND ecc.activo = 1
            WHERE e.deleted_at IS NULL AND c.activo = 1
            GROUP BY c.id
            ORDER BY e.nombre ASC
        ");
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Toggle / soft-delete
    // ─────────────────────────────────────────────────────────────────────────

    public function toggleActivo(int $id): bool
    {
        return $this->execute(
            "UPDATE empleados SET activo = IF(activo = 1, 0, 1) WHERE id = ?",
            [$id]
        );
    }
}
