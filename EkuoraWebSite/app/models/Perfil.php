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
     * Obtener todos los perfiles (excluye soft-deleted)
     */
    public function getAll()
    {
        $stmt = $this->conn->prepare("
            SELECT p.*,
                   (SELECT COUNT(*) FROM usuarios u
                    WHERE u.perfil_id = p.id AND u.deleted_at IS NULL) AS total_usuarios
            FROM {$this->table} p
            WHERE p.deleted_at IS NULL
            ORDER BY p.nombre ASC
        ");

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
                   (SELECT COUNT(*) FROM usuarios u
                    WHERE u.perfil_id = p.id AND u.deleted_at IS NULL) AS total_usuarios
            FROM {$this->table} p
            WHERE p.id = :id AND p.deleted_at IS NULL
            LIMIT 1
        ");

        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Obtener IDs de permisos asignados a un perfil.
     * BUG FIX: PerfilesController llamaba getPermisosIds() pero el modelo
     * solo tenía getPermisos() — se unifica bajo getPermisosIds() para
     * que coincida con el controller.
     */
    public function getPermisosIds($perfil_id)
    {
        $stmt = $this->conn->prepare("
            SELECT permiso_id
            FROM perfil_permisos
            WHERE perfil_id = :perfil_id
        ");

        $stmt->execute([':perfil_id' => $perfil_id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    /**
     * Obtener todos los permisos disponibles agrupados por módulo.
     * Usado para construir el formulario de creación/edición de perfiles.
     */
    public function getPermisosAgrupados()
    {
        $stmt = $this->conn->query("SELECT * FROM permisos ORDER BY modulo, nombre");
        $permisos = $stmt->fetchAll();

        $infoModulos = [
            'dashboard'     => ['icono' => 'bi-speedometer2',     'titulo' => 'Dashboard'],
            'banners'       => ['icono' => 'bi-image',            'titulo' => 'Banners'],
            'productos'     => ['icono' => 'bi-box-seam',         'titulo' => 'Productos'],
            'categorias'    => ['icono' => 'bi-tag',              'titulo' => 'Categorías'],
            'colecciones'   => ['icono' => 'bi-collection',       'titulo' => 'Colecciones'],
            'usuarios'      => ['icono' => 'bi-people',           'titulo' => 'Usuarios'],
            'perfiles'      => ['icono' => 'bi-shield-check',     'titulo' => 'Perfiles'],
            'ajustes'       => ['icono' => 'bi-gear',             'titulo' => 'Ajustes'],
            'configuracion' => ['icono' => 'bi-sliders',          'titulo' => 'Configuración'],
        ];

        $agrupados = [];
        foreach ($permisos as $p) {
            $key = strtolower($p['modulo']);
            if (!isset($agrupados[$key])) {
                $agrupados[$key] = [
                    'info'     => $infoModulos[$key] ?? ['icono' => 'bi-box', 'titulo' => ucfirst($key)],
                    'permisos' => [],
                ];
            }
            $agrupados[$key]['permisos'][] = $p;
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

            $stmt = $this->conn->prepare("
                INSERT INTO {$this->table} (nombre, descripcion)
                VALUES (:nombre, :descripcion)
            ");
            $stmt->execute([
                ':nombre'      => $datos['nombre'],
                ':descripcion' => $datos['descripcion'] ?? null,
            ]);

            $perfil_id = $this->conn->lastInsertId();

            if (!empty($datos['permisos'])) {
                $this->asignarPermisos($perfil_id, $datos['permisos']);
            }

            $this->conn->commit();
            logSeguridad('perfil_creado', "Perfil '{$datos['nombre']}' creado", null, 'info');

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

            $stmt = $this->conn->prepare("
                UPDATE {$this->table}
                SET nombre = :nombre, descripcion = :descripcion, updated_at = NOW()
                WHERE id = :id AND deleted_at IS NULL
            ");
            $stmt->execute([
                ':id'          => $id,
                ':nombre'      => $datos['nombre'],
                ':descripcion' => $datos['descripcion'] ?? null,
            ]);

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
     * Eliminar perfil (soft-delete)
     * No elimina si tiene usuarios asignados.
     */
    public function delete($id)
    {
        $perfil = $this->getById($id);
        if ($perfil && $perfil['total_usuarios'] > 0) {
            return false;
        }

        $stmt = $this->conn->prepare("
            UPDATE {$this->table} SET deleted_at = NOW()
            WHERE id = :id AND deleted_at IS NULL
        ");
        $result = $stmt->execute([':id' => $id]);

        if ($result) {
            logSeguridad('perfil_eliminado', "Perfil ID: $id eliminado", null, 'warning');
        }

        return $result;
    }

    /**
     * ¿Existe nombre de perfil? (para validar duplicados)
     */
    public function existeNombre($nombre, $exclude_id = null)
    {
        $sql = "SELECT id FROM {$this->table}
                WHERE nombre = :nombre AND deleted_at IS NULL";

        if ($exclude_id) {
            $sql .= " AND id != :exclude_id";
        }

        $params = [':nombre' => $nombre];
        if ($exclude_id) {
            $params[':exclude_id'] = $exclude_id;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }

    // ─── Privados ────────────────────────────────────────────────────────────

    private function asignarPermisos($perfil_id, array $permisos_ids)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO perfil_permisos (perfil_id, permiso_id)
            VALUES (:perfil_id, :permiso_id)
        ");

        foreach ($permisos_ids as $permiso_id) {
            if (is_numeric($permiso_id)) {
                $stmt->execute([':perfil_id' => $perfil_id, ':permiso_id' => $permiso_id]);
            }
        }
    }

    private function limpiarPermisos($perfil_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM perfil_permisos WHERE perfil_id = :perfil_id");
        $stmt->execute([':perfil_id' => $perfil_id]);
    }
}
