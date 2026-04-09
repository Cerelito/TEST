<?php
class TaskModel
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // ── CRUD BASE ─────────────────────────────────────────

    public function find(int $id): array|false
    {
        return $this->db->fetchOne(
            'SELECT t.*, s.nombre AS estatus_nombre, s.color AS estatus_color,
                    u.nombre AS asignado_nombre, p.nombre AS proyecto_nombre,
                    p.color AS proyecto_color
             FROM tasks t
             JOIN statuses s ON s.id = t.estatus_id
             JOIN projects p ON p.id = t.proyecto_id
             LEFT JOIN users u ON u.id = t.asignado_a
             WHERE t.id = :id',
            [':id' => $id]
        );
    }

    public function create(array $data, int $userId): string
    {
        return $this->db->insert(
            'INSERT INTO tasks
               (padre_id, proyecto_id, estatus_id, titulo, descripcion,
                asignado_a, creado_por, fecha_inicio, fecha_fin, prioridad, orden)
             VALUES
               (:padre, :proyecto, :estatus, :titulo, :desc,
                :asignado, :creador, :inicio, :fin, :prioridad, :orden)',
            [
                ':padre'    => $data['padre_id']    ?? null,
                ':proyecto' => $data['proyecto_id'],
                ':estatus'  => $data['estatus_id']  ?? 1,
                ':titulo'   => $data['titulo'],
                ':desc'     => $data['descripcion'] ?? null,
                ':asignado' => $data['asignado_a']  ?? null,
                ':creador'  => $userId,
                ':inicio'   => $data['fecha_inicio'] ?? null,
                ':fin'      => $data['fecha_fin']    ?? null,
                ':prioridad'=> $data['prioridad']    ?? 2,
                ':orden'    => $data['orden']        ?? 0,
            ]
        );
    }

    public function update(int $id, array $data): int
    {
        return $this->db->execute(
            'UPDATE tasks SET
               titulo = :titulo, descripcion = :desc, proyecto_id = :proyecto,
               estatus_id = :estatus, asignado_a = :asignado,
               fecha_inicio = :inicio, fecha_fin = :fin,
               prioridad = :prioridad, progreso = :progreso, padre_id = :padre
             WHERE id = :id',
            [
                ':titulo'   => $data['titulo'],
                ':desc'     => $data['descripcion'] ?? null,
                ':proyecto' => $data['proyecto_id'],
                ':estatus'  => $data['estatus_id'],
                ':asignado' => $data['asignado_a'] ?? null,
                ':inicio'   => $data['fecha_inicio'] ?? null,
                ':fin'      => $data['fecha_fin'] ?? null,
                ':prioridad'=> $data['prioridad'] ?? 2,
                ':progreso' => $data['progreso'] ?? 0,
                ':padre'    => $data['padre_id'] ?? null,
                ':id'       => $id,
            ]
        );
    }

    public function delete(int $id): int
    {
        return $this->db->execute('DELETE FROM tasks WHERE id = :id', [':id' => $id]);
    }

    // ── JERARQUÍA ─────────────────────────────────────────

    /**
     * Obtener tareas madre de un proyecto (padre_id IS NULL)
     */
    public function mothers(int $proyectoId): array
    {
        return $this->db->fetchAll(
            'SELECT t.*, s.nombre AS estatus_nombre, s.color AS estatus_color,
                    u.nombre AS asignado_nombre
             FROM tasks t
             JOIN statuses s ON s.id = t.estatus_id
             LEFT JOIN users u ON u.id = t.asignado_a
             WHERE t.proyecto_id = :p AND t.padre_id IS NULL
             ORDER BY t.orden, t.created_at',
            [':p' => $proyectoId]
        );
    }

    /**
     * Obtener hijos directos de una tarea
     */
    public function children(int $padreId): array
    {
        return $this->db->fetchAll(
            'SELECT t.*, s.nombre AS estatus_nombre, s.color AS estatus_color,
                    u.nombre AS asignado_nombre
             FROM tasks t
             JOIN statuses s ON s.id = t.estatus_id
             LEFT JOIN users u ON u.id = t.asignado_a
             WHERE t.padre_id = :p ORDER BY t.orden, t.created_at',
            [':p' => $padreId]
        );
    }

    /**
     * Árbol completo de un proyecto (recursivo en PHP)
     */
    public function tree(int $proyectoId): array
    {
        $all = $this->db->fetchAll(
            'SELECT t.*, s.nombre AS estatus_nombre, s.color AS estatus_color,
                    u.nombre AS asignado_nombre
             FROM tasks t
             JOIN statuses s ON s.id = t.estatus_id
             LEFT JOIN users u ON u.id = t.asignado_a
             WHERE t.proyecto_id = :p ORDER BY t.orden, t.created_at',
            [':p' => $proyectoId]
        );

        $indexed  = [];
        $children = [];
        foreach ($all as $t) {
            $indexed[$t['id']] = $t;
            $indexed[$t['id']]['hijos'] = [];
            $children[$t['padre_id'] ?? 0][] = $t['id'];
        }

        $build = function (int $padreId) use (&$build, $indexed, $children): array {
            $result = [];
            foreach ($children[$padreId] ?? [] as $id) {
                $node = $indexed[$id];
                $node['hijos'] = $build($id);
                $result[] = $node;
            }
            return $result;
        };

        return $build(0);
    }

    /**
     * Todas las tareas de un proyecto (para Gantt)
     */
    public function forGantt(int $proyectoId): array
    {
        return $this->db->fetchAll(
            'SELECT t.id, t.padre_id, t.titulo, t.fecha_inicio, t.fecha_fin,
                    t.progreso, t.prioridad, s.nombre AS estatus, s.color AS estatus_color,
                    u.nombre AS asignado
             FROM tasks t
             JOIN statuses s ON s.id = t.estatus_id
             LEFT JOIN users u ON u.id = t.asignado_a
             WHERE t.proyecto_id = :p ORDER BY t.orden, t.fecha_inicio',
            [':p' => $proyectoId]
        );
    }

    // ── DEPENDENCIAS ──────────────────────────────────────

    public function addDependency(int $tareaId, int $depId): void
    {
        $this->db->execute(
            'INSERT IGNORE INTO task_dependencies (tarea_id, depende_de_id) VALUES (:t, :d)',
            [':t' => $tareaId, ':d' => $depId]
        );
    }

    public function removeDependency(int $tareaId, int $depId): void
    {
        $this->db->execute(
            'DELETE FROM task_dependencies WHERE tarea_id = :t AND depende_de_id = :d',
            [':t' => $tareaId, ':d' => $depId]
        );
    }

    public function dependencies(int $tareaId): array
    {
        return $this->db->fetchAll(
            'SELECT t.id, t.titulo FROM task_dependencies td
             JOIN tasks t ON t.id = td.depende_de_id
             WHERE td.tarea_id = :t',
            [':t' => $tareaId]
        );
    }

    // ── NOTAS ─────────────────────────────────────────────

    public function addNote(int $tareaId, int $userId, string $nota): string
    {
        return $this->db->insert(
            'INSERT INTO task_notes (tarea_id, usuario_id, nota) VALUES (:t, :u, :n)',
            [':t' => $tareaId, ':u' => $userId, ':n' => $nota]
        );
    }

    public function notes(int $tareaId): array
    {
        return $this->db->fetchAll(
            'SELECT n.*, u.nombre AS autor FROM task_notes n
             JOIN users u ON u.id = n.usuario_id
             WHERE n.tarea_id = :t ORDER BY n.created_at DESC',
            [':t' => $tareaId]
        );
    }

    // ── RECORDATORIOS ─────────────────────────────────────

    public function dueSoon(int $days = 3): array
    {
        return $this->db->fetchAll(
            'SELECT t.id, t.titulo, t.fecha_fin, p.nombre AS proyecto,
                    u.nombre AS asignado_nombre, u.email AS asignado_email
             FROM tasks t
             JOIN projects p ON p.id = t.proyecto_id
             JOIN users u ON u.id = t.asignado_a
             WHERE t.fecha_fin BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL :d DAY)
               AND t.estatus_id NOT IN (4, 5)
               AND t.asignado_a IS NOT NULL',
            [':d' => $days]
        );
    }

    // ── FILTROS Y BÚSQUEDA ────────────────────────────────

    public function filter(array $params): array
    {
        $where  = ['1=1'];
        $bind   = [];

        if (!empty($params['proyecto_id'])) {
            $where[] = 't.proyecto_id = :p';
            $bind[':p'] = $params['proyecto_id'];
        }
        if (!empty($params['estatus_id'])) {
            $where[] = 't.estatus_id = :e';
            $bind[':e'] = $params['estatus_id'];
        }
        if (!empty($params['asignado_a'])) {
            $where[] = 't.asignado_a = :a';
            $bind[':a'] = $params['asignado_a'];
        }
        if (!empty($params['busqueda'])) {
            $where[] = 't.titulo LIKE :b';
            $bind[':b'] = '%' . $params['busqueda'] . '%';
        }

        $sql = 'SELECT t.*, s.nombre AS estatus_nombre, s.color AS estatus_color,
                       u.nombre AS asignado_nombre, p.nombre AS proyecto_nombre
                FROM tasks t
                JOIN statuses s ON s.id = t.estatus_id
                JOIN projects p ON p.id = t.proyecto_id
                LEFT JOIN users u ON u.id = t.asignado_a
                WHERE ' . implode(' AND ', $where) .
               ' ORDER BY t.fecha_fin ASC, t.prioridad DESC';

        return $this->db->fetchAll($sql, $bind);
    }

    public function statuses(): array
    {
        return $this->db->fetchAll('SELECT * FROM statuses ORDER BY orden');
    }

    // ── ACTUALIZAR ORDEN ──────────────────────────────────

    public function reorder(array $ids): void
    {
        foreach ($ids as $orden => $id) {
            $this->db->execute('UPDATE tasks SET orden = :o WHERE id = :id', [':o' => $orden, ':id' => $id]);
        }
    }
}
