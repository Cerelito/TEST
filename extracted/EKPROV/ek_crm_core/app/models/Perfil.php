<?php
// app/models/Perfil.php - Modelo de Perfil

class Perfil
{
    private $conn;
    private $table = 'perfiles';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Obtener todos los perfiles (CORREGIDO: Evita duplicados)
     */
    public function getAll()
    {
        // Usamos una subconsulta para contar usuarios, es más seguro que JOINs múltiples
        $sql = "
            SELECT p.*,
                   (SELECT COUNT(*) FROM usuarios u WHERE u.perfil_id = p.id AND u.deleted_at IS NULL) as total_usuarios
            FROM {$this->table} p
            WHERE p.deleted_at IS NULL
            ORDER BY p.nombre ASC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Obtener perfil por ID
     */
    public function getById($id)
    {
        $stmt = $this->conn->prepare("
            SELECT p.*,
                   (SELECT COUNT(*) FROM usuarios WHERE perfil_id = p.id AND deleted_at IS NULL) as total_usuarios
            FROM {$this->table} p
            WHERE p.id = :id AND p.deleted_at IS NULL
            LIMIT 1
        ");

        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Obtener permisos asignados a un perfil (Solo devuelve los IDs)
     * Usado para marcar los checkboxes en editar
     */
    public function getPermisos($perfil_id)
    {
        $stmt = $this->conn->prepare("
            SELECT p.id
            FROM permisos p
            INNER JOIN perfil_permisos pp ON p.id = pp.permiso_id
            WHERE pp.perfil_id = :perfil_id
        ");

        $stmt->execute([':perfil_id' => $perfil_id]);

        // Retornamos un array plano de IDs [1, 5, 8...]
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    /**
     * Obtener TODOS los permisos disponibles agrupados por módulo
     * Usado para construir el formulario de creación/edición
     */
    public function getPermisosAgrupados()
    {
        // 1. Obtener todos los permisos ordenados
        $stmt = $this->conn->query("SELECT * FROM permisos ORDER BY modulo, nombre");
        $permisos = $stmt->fetchAll();

        $agrupados = [];

        // 2. Definir iconos y títulos bonitos (Opcional)
        $infoModulos = [
            'dashboard' => ['icono' => 'bi-speedometer2', 'titulo' => 'Dashboard'],
            'proveedores' => ['icono' => 'bi-building', 'titulo' => 'Proveedores'],
            'solicitudes' => ['icono' => 'bi-file-earmark-text', 'titulo' => 'Solicitudes'],
            'usuarios' => ['icono' => 'bi-people', 'titulo' => 'Usuarios'],
            'perfiles' => ['icono' => 'bi-shield-check', 'titulo' => 'Perfiles'],
            'catalogos' => ['icono' => 'bi-list-ul', 'titulo' => 'Catálogos'],
            'configuracion' => ['icono' => 'bi-gear', 'titulo' => 'Configuración'],
            'reportes' => ['icono' => 'bi-graph-up', 'titulo' => 'Reportes']
        ];

        // 3. Agrupar manualmente
        foreach ($permisos as $p) {
            $moduloKey = strtolower($p['modulo']);

            if (!isset($agrupados[$moduloKey])) {
                $agrupados[$moduloKey] = [
                    'info' => $infoModulos[$moduloKey] ?? ['icono' => 'bi-box', 'titulo' => ucfirst($moduloKey)],
                    'permisos' => []
                ];
            }

            $agrupados[$moduloKey]['permisos'][] = $p;
        }

        return $agrupados;
    }

    /**
     * Crear perfil
     */
    public function create($datos)
    {
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("INSERT INTO {$this->table} (nombre, descripcion) VALUES (:nombre, :descripcion)");
            $stmt->execute([':nombre' => $datos['nombre'], ':descripcion' => $datos['descripcion'] ?? null]);
            $perfil_id = $this->conn->lastInsertId();

            // Asignar permisos (recibimos IDs)
            if (!empty($datos['permisos'])) {
                $this->asignarPermisos($perfil_id, $datos['permisos']);
            }

            $this->conn->commit();
            logSeguridad('perfil_creado', "Perfil {$datos['nombre']} creado", null, 'info');
            return $perfil_id;

        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error crear perfil: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar perfil
     */
    public function update($id, $datos)
    {
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("UPDATE {$this->table} SET nombre = :nombre, descripcion = :descripcion, updated_at = NOW() WHERE id = :id");
            $stmt->execute([':id' => $id, ':nombre' => $datos['nombre'], ':descripcion' => $datos['descripcion'] ?? null]);

            // Actualizar permisos
            $this->limpiarPermisos($id);
            if (!empty($datos['permisos'])) {
                $this->asignarPermisos($id, $datos['permisos']);
            }

            $this->conn->commit();
            logSeguridad('perfil_actualizado', "Perfil ID: $id actualizado", null, 'info');
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error actualizar perfil: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar perfil (soft delete)
     */
    public function delete($id)
    {
        // Verificar usuarios asignados
        $perfil = $this->getById($id);
        if ($perfil && $perfil['total_usuarios'] > 0) {
            return false;
        }

        $stmt = $this->conn->prepare("UPDATE {$this->table} SET deleted_at = NOW() WHERE id = :id");
        $result = $stmt->execute([':id' => $id]);

        if ($result) {
            logSeguridad('perfil_eliminado', "Perfil ID: $id eliminado", null, 'warning');
        }

        return $result;
    }

    /**
     * Asignar permisos a un perfil (Recibe array de IDs)
     */
    private function asignarPermisos($perfil_id, $permisos_ids)
    {
        $stmt = $this->conn->prepare("INSERT INTO perfil_permisos (perfil_id, permiso_id) VALUES (:perfil_id, :permiso_id)");

        foreach ($permisos_ids as $permiso_id) {
            // Asegurar que sea numérico para evitar inyecciones raras
            if (is_numeric($permiso_id)) {
                $stmt->execute([
                    ':perfil_id' => $perfil_id,
                    ':permiso_id' => $permiso_id
                ]);
            }
        }
    }

    /**
     * Limpiar permisos de un perfil
     */
    private function limpiarPermisos($perfil_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM perfil_permisos WHERE perfil_id = :perfil_id");
        $stmt->execute([':perfil_id' => $perfil_id]);
    }

    /**
     * Verificar si existe nombre (para evitar duplicados al crear/editar)
     */
    public function existeNombre($nombre, $exclude_id = null)
    {
        $sql = "SELECT id FROM {$this->table} WHERE nombre = :nombre AND deleted_at IS NULL";

        if ($exclude_id) {
            $sql .= " AND id != :exclude_id";
        }

        $stmt = $this->conn->prepare($sql);
        $params = [':nombre' => $nombre];

        if ($exclude_id) {
            $params[':exclude_id'] = $exclude_id;
        }

        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }
}