<?php
// app/models/Banner.php - Modelo para Banners de Inicio

class Banner
{
    private $conn;
    private $table = 'banners_home';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Obtener banners filtrados por sección
     */
    public function getAll($soloActivos = false, $seccion = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";

        if ($soloActivos) {
            $sql .= " AND activo = 1";
        }

        if ($seccion) {
            $sql .= " AND seccion = :seccion";
        }

        $sql .= " ORDER BY orden ASC, created_at DESC";

        $stmt = $this->conn->prepare($sql);

        if ($seccion) {
            $stmt->bindValue(':seccion', $seccion);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Obtener banner por ID
     */
    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Crear banner
     */
    public function create($datos)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO {$this->table} (titulo, subtitulo, texto_boton, enlace, seccion, imagen, orden, activo)
            VALUES (:titulo, :subtitulo, :texto_boton, :enlace, :seccion, :imagen, :orden, :activo)
        ");

        return $stmt->execute([
            ':titulo' => $datos['titulo'] ?? null,
            ':subtitulo' => $datos['subtitulo'] ?? null,
            ':texto_boton' => $datos['texto_boton'] ?? 'VER MÁS',
            ':enlace' => $datos['enlace'] ?? null,
            ':seccion' => $datos['seccion'] ?? 'hero',
            ':imagen' => $datos['imagen'],
            ':orden' => $datos['orden'] ?? 0,
            ':activo' => $datos['activo'] ?? 1
        ]);
    }

    /**
     * Actualizar banner
     */
    public function update($id, $datos)
    {
        $stmt = $this->conn->prepare("
            UPDATE {$this->table}
            SET titulo = :titulo,
                subtitulo = :subtitulo,
                texto_boton = :texto_boton,
                enlace = :enlace,
                seccion = :seccion,
                imagen = :imagen,
                orden = :orden,
                activo = :activo,
                updated_at = NOW()
            WHERE id = :id
        ");

        return $stmt->execute([
            ':id' => $id,
            ':titulo' => $datos['titulo'] ?? null,
            ':subtitulo' => $datos['subtitulo'] ?? null,
            ':texto_boton' => $datos['texto_boton'] ?? 'VER MÁS',
            ':enlace' => $datos['enlace'] ?? null,
            ':seccion' => $datos['seccion'] ?? 'hero',
            ':imagen' => $datos['imagen'],
            ':orden' => $datos['orden'] ?? 0,
            ':activo' => $datos['activo'] ?? 1
        ]);
    }

    /**
     * Eliminar banner
     */
    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Cambiar estado activo/inactivo
     */
    public function toggleEstado($id)
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET activo = NOT activo WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
