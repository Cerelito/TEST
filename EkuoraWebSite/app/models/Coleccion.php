<?php
// app/models/Coleccion.php

class Coleccion
{
    private $conn;
    private $table = 'colecciones';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Obtener todas las colecciones
     */
    public function getAll($activo = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE deleted_at IS NULL";

        if ($activo !== null) {
            $sql .= " AND activo = :activo";
        }

        $sql .= " ORDER BY orden ASC, nombre ASC";

        $stmt = $this->conn->prepare($sql);
        if ($activo !== null) {
            $stmt->bindValue(':activo', $activo ? 1 : 0, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Obtener colección por ID
     */
    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ? AND deleted_at IS NULL");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Obtener colección por slug
     */
    public function getBySlug($slug)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE slug = ? AND deleted_at IS NULL");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    /**
     * Crear colección
     */
    public function create($datos)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO {$this->table} (nombre, slug, descripcion, imagen, orden, activo) 
            VALUES (:nombre, :slug, :descripcion, :imagen, :orden, :activo)
        ");

        return $stmt->execute([
            ':nombre' => $datos['nombre'],
            ':slug' => $datos['slug'],
            ':descripcion' => $datos['descripcion'] ?? null,
            ':imagen' => $datos['imagen'] ?? null,
            ':orden' => $datos['orden'] ?? 0,
            ':activo' => $datos['activo'] ?? 1
        ]);
    }

    /**
     * Actualizar colección
     */
    public function update($id, $datos)
    {
        $stmt = $this->conn->prepare("
            UPDATE {$this->table} 
            SET nombre = :nombre, 
                slug = :slug, 
                descripcion = :descripcion, 
                imagen = :imagen, 
                orden = :orden, 
                activo = :activo 
            WHERE id = :id
        ");

        return $stmt->execute([
            ':id' => $id,
            ':nombre' => $datos['nombre'],
            ':slug' => $datos['slug'],
            ':descripcion' => $datos['descripcion'] ?? null,
            ':imagen' => $datos['imagen'] ?? null,
            ':orden' => $datos['orden'] ?? 0,
            ':activo' => $datos['activo'] ?? 1
        ]);
    }

    /**
     * Eliminar colección (soft delete)
     */
    public function delete($id)
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET deleted_at = NOW() WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
