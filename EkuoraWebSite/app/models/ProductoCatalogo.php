<?php
// app/models/ProductoCatalogo.php

class ProductoCatalogo
{
    private $conn;
    private $tabla_categorias = 'categorias_productos';
    private $tabla_productos = 'productos_catalogo';
    private $tabla_imagenes = 'imagenes_productos';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Exponer la conexión para uso en el controller
     */
    public function getConn()
    {
        return $this->conn;
    }

    // ===========================================
    // CATEGORÍAS
    // ===========================================

    /**
     * Obtener todas las categorías
     */
    public function getCategorias($activo = null, $destacado = null)
    {
        $sql = "SELECT c.*,
                COUNT(DISTINCT p.id) as total_productos
                FROM {$this->tabla_categorias} c
                LEFT JOIN {$this->tabla_productos} p ON c.id = p.categoria_id AND p.activo = 1 AND p.deleted_at IS NULL
                WHERE c.deleted_at IS NULL";

        if ($activo !== null) {
            $sql .= " AND c.activo = :activo";
        }

        if ($destacado !== null) {
            $sql .= " AND c.destacado = :destacado";
        }

        $sql .= " GROUP BY c.id ORDER BY c.orden ASC, c.nombre ASC";

        $stmt = $this->conn->prepare($sql);

        $params = [];
        if ($activo !== null)
            $params[':activo'] = $activo ? 1 : 0;
        if ($destacado !== null)
            $params[':destacado'] = $destacado ? 1 : 0;

        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Obtener categoría por ID
     */
    public function getCategoriaById($id)
    {
        $stmt = $this->conn->prepare("
            SELECT * FROM {$this->tabla_categorias}
            WHERE id = :id AND deleted_at IS NULL
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Obtener categoría por slug
     */
    public function getCategoriaBySlug($slug)
    {
        $stmt = $this->conn->prepare("
            SELECT c.*,
            COUNT(DISTINCT p.id) as total_productos
            FROM {$this->tabla_categorias} c
            LEFT JOIN {$this->tabla_productos} p ON c.id = p.categoria_id AND p.activo = 1 AND p.deleted_at IS NULL
            WHERE c.slug = :slug AND c.deleted_at IS NULL
            GROUP BY c.id
        ");
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch();
    }

    /**
     * Crear categoría
     */
    public function crearCategoria($datos)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO {$this->tabla_categorias}
            (nombre, slug, descripcion, imagen, icono, orden, activo, destacado)
            VALUES (:nombre, :slug, :descripcion, :imagen, :icono, :orden, :activo, :destacado)
        ");

        return $stmt->execute([
            ':nombre' => $datos['nombre'],
            ':slug' => $datos['slug'] ?? $this->generarSlug($datos['nombre']),
            ':descripcion' => $datos['descripcion'] ?? null,
            ':imagen' => $datos['imagen'] ?? null,
            ':icono' => $datos['icono'] ?? 'bi-box-seam',
            ':orden' => $datos['orden'] ?? 0,
            ':activo' => $datos['activo'] ?? 1,
            ':destacado' => $datos['destacado'] ?? 0
        ]);
    }

    /**
     * Actualizar categoría
     */
    public function actualizarCategoria($id, $datos)
    {
        $stmt = $this->conn->prepare("
            UPDATE {$this->tabla_categorias}
            SET nombre = :nombre,
                slug = :slug,
                descripcion = :descripcion,
                imagen = :imagen,
                icono = :icono,
                orden = :orden,
                activo = :activo,
                destacado = :destacado
            WHERE id = :id
        ");

        return $stmt->execute([
            ':id' => $id,
            ':nombre' => $datos['nombre'],
            ':slug' => $datos['slug'] ?? $this->generarSlug($datos['nombre']),
            ':descripcion' => $datos['descripcion'] ?? null,
            ':imagen' => $datos['imagen'] ?? null,
            ':icono' => $datos['icono'] ?? 'bi-box-seam',
            ':orden' => $datos['orden'] ?? 0,
            ':activo' => $datos['activo'] ?? 1,
            ':destacado' => $datos['destacado'] ?? 0
        ]);
    }

    /**
     * Eliminar categoría (soft delete)
     */
    public function eliminarCategoria($id)
    {
        $stmt = $this->conn->prepare("
            UPDATE {$this->tabla_categorias}
            SET deleted_at = NOW()
            WHERE id = :id
        ");
        return $stmt->execute([':id' => $id]);
    }

    // ===========================================
    // PRODUCTOS
    // ===========================================

    /**
     * Obtener productos con filtros
     */
    public function getProductos($filtros = [])
    {
        $sql = "SELECT p.*, c.nombre as categoria_nombre, c.slug as categoria_slug, f.nombre as familia_nombre
                FROM {$this->tabla_productos} p
                INNER JOIN {$this->tabla_categorias} c ON p.categoria_id = c.id
                LEFT JOIN familias_productos f ON p.familia_id = f.id
                WHERE p.deleted_at IS NULL AND c.deleted_at IS NULL";

        $params = [];

        if (isset($filtros['categoria_id'])) {
            $sql .= " AND p.categoria_id = :categoria_id";
            $params[':categoria_id'] = $filtros['categoria_id'];
        }

        if (isset($filtros['familia_id'])) {
            $sql .= " AND p.familia_id = :familia_id";
            $params[':familia_id'] = $filtros['familia_id'];
        }

        if (isset($filtros['activo'])) {
            $sql .= " AND p.activo = :activo";
            $params[':activo'] = $filtros['activo'] ? 1 : 0;
        }

        if (isset($filtros['destacado'])) {
            $sql .= " AND p.destacado = :destacado";
            $params[':destacado'] = $filtros['destacado'] ? 1 : 0;
        }

        if (isset($filtros['nuevo'])) {
            $sql .= " AND p.nuevo = :nuevo";
            $params[':nuevo'] = $filtros['nuevo'] ? 1 : 0;
        }

        if (isset($filtros['buscar']) && !empty($filtros['buscar'])) {
            $sql .= " AND (p.nombre LIKE :b1 OR p.descripcion LIKE :b2 OR p.sku LIKE :b3 OR c.nombre LIKE :b4 OR f.nombre LIKE :b5)";
            $busqueda = '%' . $filtros['buscar'] . '%';
            $params[':b1'] = $busqueda;
            $params[':b2'] = $busqueda;
            $params[':b3'] = $busqueda;
            $params[':b4'] = $busqueda;
            $params[':b5'] = $busqueda;
        }

        // Ordenamiento — whitelist para prevenir SQL injection
        $columnasPermitidas   = ['orden', 'nombre', 'created_at', 'precio_referencia', 'rating', 'vistas'];
        $direccionesPermitidas = ['ASC', 'DESC'];

        $orden     = in_array($filtros['orden'] ?? '', $columnasPermitidas, true)
                     ? $filtros['orden'] : 'orden';
        $direccion = in_array(strtoupper($filtros['direccion'] ?? ''), $direccionesPermitidas, true)
                     ? strtoupper($filtros['direccion']) : 'ASC';

        $sql .= " ORDER BY p.{$orden} {$direccion}";

        // Límite
        if (isset($filtros['limite'])) {
            $sql .= " LIMIT :limite";
        }

        $stmt = $this->conn->prepare($sql);

        // Bind filter params
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        // Bind limit specifically as INT
        if (isset($filtros['limite'])) {
            $stmt->bindValue(':limite', (int) $filtros['limite'], PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Obtener producto por ID
     */
    public function getProductoById($id)
    {
        $stmt = $this->conn->prepare("
            SELECT p.*, c.nombre as categoria_nombre, c.slug as categoria_slug, f.nombre as familia_nombre
            FROM {$this->tabla_productos} p
            INNER JOIN {$this->tabla_categorias} c ON p.categoria_id = c.id
            LEFT JOIN familias_productos f ON p.familia_id = f.id
            WHERE p.id = :id AND p.deleted_at IS NULL
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Obtener producto por slug
     */
    public function getProductoBySlug($slug)
    {
        $stmt = $this->conn->prepare("
            SELECT p.*, c.nombre as categoria_nombre, c.slug as categoria_slug, f.nombre as familia_nombre
            FROM {$this->tabla_productos} p
            INNER JOIN {$this->tabla_categorias} c ON p.categoria_id = c.id
            LEFT JOIN familias_productos f ON p.familia_id = f.id
            WHERE p.slug = :slug AND p.deleted_at IS NULL
        ");
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch();
    }

    /**
     * Obtener productos destacados
     */
    public function getProductosDestacados($limite = 8)
    {
        return $this->getProductos([
            'destacado' => true,
            'activo' => true,
            'limite' => $limite,
            'orden' => 'orden'
        ]);
    }

    /**
     * Crear producto
     */
    public function crearProducto($datos)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO {$this->tabla_productos}
            (categoria_id, familia_id, nombre, slug, descripcion, descripcion_corta, imagen_principal,
             precio_referencia, sku, marca, caracteristicas, orden, activo, destacado, nuevo, rating)
            VALUES (:categoria_id, :familia_id, :nombre, :slug, :descripcion, :descripcion_corta, :imagen_principal,
                    :precio_referencia, :sku, :marca, :caracteristicas, :orden, :activo, :destacado, :nuevo, :rating)
        ");

        if (
            $stmt->execute([
                ':categoria_id' => $datos['categoria_id'],
                ':familia_id' => $datos['familia_id'] ?? null,
                ':nombre' => $datos['nombre'],
                ':slug' => $datos['slug'] ?? $this->generarSlug($datos['nombre']),
                ':descripcion' => $datos['descripcion'] ?? null,
                ':descripcion_corta' => $datos['descripcion_corta'] ?? null,
                ':imagen_principal' => $datos['imagen_principal'] ?? null,
                ':precio_referencia' => $datos['precio_referencia'] ?? null,
                ':sku' => $datos['sku'] ?? null,
                ':marca' => $datos['marca'] ?? null,
                ':caracteristicas' => $datos['caracteristicas'] ?? null,
                ':orden' => $datos['orden'] ?? 0,
                ':activo' => $datos['activo'] ?? 1,
                ':destacado' => $datos['destacado'] ?? 0,
                ':nuevo' => $datos['nuevo'] ?? 0,
                ':rating' => $datos['rating'] ?? 0
            ])
        ) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    /**
     * Actualizar producto
     */
    public function actualizarProducto($id, $datos)
    {
        // Si no viene slug explícito, generarlo con el exclude_id para no
        // chocar con el slug actual del mismo producto.
        if (empty($datos['slug'])) {
            $datos['slug'] = $this->generarSlug($datos['nombre'], $id);
        }

        $stmt = $this->conn->prepare("
            UPDATE {$this->tabla_productos}
            SET categoria_id = :categoria_id,
                familia_id = :familia_id,
                nombre = :nombre,
                slug = :slug,
                descripcion = :descripcion,
                descripcion_corta = :descripcion_corta,
                imagen_principal = :imagen_principal,
                precio_referencia = :precio_referencia,
                sku = :sku,
                marca = :marca,
                caracteristicas = :caracteristicas,
                orden = :orden,
                activo = :activo,
                destacado = :destacado,
                nuevo = :nuevo,
                rating = :rating
            WHERE id = :id
        ");

        return $stmt->execute([
            ':id' => $id,
            ':categoria_id' => $datos['categoria_id'],
            ':familia_id' => $datos['familia_id'] ?? null,
            ':nombre' => $datos['nombre'],
            ':slug' => $datos['slug'],
            ':descripcion' => $datos['descripcion'] ?? null,
            ':descripcion_corta' => $datos['descripcion_corta'] ?? null,
            ':imagen_principal' => $datos['imagen_principal'] ?? null,
            ':precio_referencia' => $datos['precio_referencia'] ?? null,
            ':sku' => $datos['sku'] ?? null,
            ':marca' => $datos['marca'] ?? null,
            ':caracteristicas' => $datos['caracteristicas'] ?? null,
            ':orden' => $datos['orden'] ?? 0,
            ':activo' => $datos['activo'] ?? 1,
            ':destacado' => $datos['destacado'] ?? 0,
            ':nuevo' => $datos['nuevo'] ?? 0,
            ':rating' => $datos['rating'] ?? 0
        ]);
    }

    /**
     * Eliminar producto (soft delete).
     * Al eliminar, se le añade un sufijo al slug para liberar el índice UNIQUE
     * y permitir que se vuelva a crear un producto con el mismo nombre en el futuro.
     */
    public function eliminarProducto($id)
    {
        $stmt = $this->conn->prepare("
            UPDATE {$this->tabla_productos}
            SET deleted_at = NOW(),
                slug = CONCAT(slug, '-deleted-', :id)
            WHERE id = :id2 AND deleted_at IS NULL
        ");
        return $stmt->execute([':id' => $id, ':id2' => $id]);
    }

    /**
     * Incrementar vistas del producto
     */
    public function incrementarVistas($id)
    {
        $stmt = $this->conn->prepare("
            UPDATE {$this->tabla_productos}
            SET vistas = vistas + 1
            WHERE id = :id
        ");
        return $stmt->execute([':id' => $id]);
    }

    // ===========================================
    // IMÁGENES DE PRODUCTOS
    // ===========================================

    /**
     * Obtener imágenes de un producto
     */
    public function getImagenesProducto($producto_id)
    {
        $stmt = $this->conn->prepare("
            SELECT * FROM {$this->tabla_imagenes}
            WHERE producto_id = :producto_id
            ORDER BY orden ASC
        ");
        $stmt->execute([':producto_id' => $producto_id]);
        return $stmt->fetchAll();
    }

    /**
     * Agregar imagen a producto
     */
    public function agregarImagen($producto_id, $ruta, $alt_text = null, $orden = 0)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO {$this->tabla_imagenes} (producto_id, ruta, alt_text, orden)
            VALUES (:producto_id, :ruta, :alt_text, :orden)
        ");

        return $stmt->execute([
            ':producto_id' => $producto_id,
            ':ruta' => $ruta,
            ':alt_text' => $alt_text,
            ':orden' => $orden
        ]);
    }

    /**
     * Eliminar imagen
     */
    public function eliminarImagen($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->tabla_imagenes} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // ===========================================
    // UTILIDADES
    // ===========================================

    /**
     * Generar slug desde un texto
     */
    private function generarSlug($texto, $exclude_id = null)
    {
        $slug = strtolower(trim($texto));
        // Convertir caracteres acentuados y especiales comunes
        $slug = str_replace(
            ['á','é','í','ó','ú','ü','ñ','Á','É','Í','Ó','Ú','Ü','Ñ'],
            ['a','e','i','o','u','u','n','a','e','i','o','u','u','n'],
            $slug
        );
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        // Verificar si ya existe (excluyendo el producto actual al editar)
        $contador = 1;
        $slug_original = $slug;
        while ($this->slugExiste($slug, $exclude_id)) {
            $slug = $slug_original . '-' . $contador;
            $contador++;
        }

        return $slug;
    }

    /**
     * Verificar si un slug ya existe en la tabla de productos.
     * Nota: NO filtra por deleted_at porque el índice UNIQUE de la BD
     * aplica a TODOS los registros, incluyendo los soft-deleted.
     */
    private function slugExiste($slug, $exclude_id = null)
    {
        if ($exclude_id) {
            $stmt = $this->conn->prepare("
                SELECT COUNT(*) as total
                FROM {$this->tabla_productos}
                WHERE slug = :slug AND id != :exclude_id
            ");
            $stmt->execute([':slug' => $slug, ':exclude_id' => $exclude_id]);
        } else {
            $stmt = $this->conn->prepare("
                SELECT COUNT(*) as total
                FROM {$this->tabla_productos}
                WHERE slug = :slug
            ");
            $stmt->execute([':slug' => $slug]);
        }
        $result = $stmt->fetch();
        return $result['total'] > 0;
    }

    /**
     * Obtener estadísticas del catálogo
     */
    public function getEstadisticas()
    {
        $stmt = $this->conn->query("
            SELECT
                (SELECT COUNT(*) FROM {$this->tabla_categorias} WHERE deleted_at IS NULL AND activo = 1) as total_categorias,
                (SELECT COUNT(*) FROM {$this->tabla_productos} WHERE deleted_at IS NULL AND activo = 1) as total_productos,
                (SELECT COUNT(*) FROM {$this->tabla_productos} WHERE deleted_at IS NULL AND destacado = 1) as total_destacados,
                (SELECT SUM(vistas) FROM {$this->tabla_productos} WHERE deleted_at IS NULL) as total_vistas
        ");
        return $stmt->fetch();
    }
}
