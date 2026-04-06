<?php
// app/models/Familia.php

class Familia
{
    private $conn;
    private $table = 'familias_productos';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Obtener todas las familias
     */
    public function getAll($activo = null)
    {
        $sql = "SELECT f.*, c.nombre as categoria_nombre 
                FROM {$this->table} f 
                INNER JOIN categorias_productos c ON f.categoria_id = c.id
                WHERE f.deleted_at IS NULL";

        if ($activo !== null) {
            $sql .= " AND f.activo = :activo";
        }

        $sql .= " ORDER BY f.orden ASC, f.nombre ASC";

        $stmt = $this->conn->prepare($sql);
        if ($activo !== null) {
            $stmt->bindValue(':activo', $activo ? 1 : 0);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Obtener familias por categoría
     */
    public function getByCategoria($categoria_id, $activo = null)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE categoria_id = :categoria_id AND deleted_at IS NULL";

        if ($activo !== null) {
            $sql .= " AND activo = :activo";
        }

        $sql .= " ORDER BY orden ASC, nombre ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':categoria_id', $categoria_id);
        if ($activo !== null) {
            $stmt->bindValue(':activo', $activo ? 1 : 0);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Obtener familia por ID
     */
    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ? AND deleted_at IS NULL");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Obtener familia por slug
     */
    public function getBySlug($slug)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE slug = ? AND deleted_at IS NULL");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    /**
     * Crear familia
     */
    public function create($datos)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO {$this->table} (categoria_id, nombre, slug, descripcion, imagen, orden, activo) 
            VALUES (:categoria_id, :nombre, :slug, :descripcion, :imagen, :orden, :activo)
        ");

        return $stmt->execute([
            ':categoria_id' => $datos['categoria_id'],
            ':nombre' => $datos['nombre'],
            ':slug' => $datos['slug'],
            ':descripcion' => $datos['descripcion'] ?? null,
            ':imagen' => $datos['imagen'] ?? null,
            ':orden' => $datos['orden'] ?? 0,
            ':activo' => $datos['activo'] ?? 1
        ]);
    }

    /**
     * Actualizar familia
     */
    public function update($id, $datos)
    {
        $stmt = $this->conn->prepare("
            UPDATE {$this->table} 
            SET categoria_id = :categoria_id, 
                nombre = :nombre, 
                slug = :slug, 
                descripcion = :descripcion, 
                imagen = :imagen, 
                orden = :orden, 
                activo = :activo 
            WHERE id = :id
        ");

        return $stmt->execute([
            ':id' => $id,
            ':categoria_id' => $datos['categoria_id'],
            ':nombre' => $datos['nombre'],
            ':slug' => $datos['slug'],
            ':descripcion' => $datos['descripcion'] ?? null,
            ':imagen' => $datos['imagen'] ?? null,
            ':orden' => $datos['orden'] ?? 0,
            ':activo' => $datos['activo'] ?? 1
        ]);
    }

    /**
     * Eliminar familia (soft delete)
     */
    public function delete($id)
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET deleted_at = NOW() WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
