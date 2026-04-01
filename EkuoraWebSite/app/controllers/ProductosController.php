<?php
// app/controllers/ProductosController.php

class ProductosController
{
    private $model;

    public function __construct()
    {
        $this->model = new ProductoCatalogo();
    }

    // ===========================================
    // VISTAS PÚBLICAS (Sin autenticación)
    // ===========================================

    /**
     * Página principal del catálogo (pública)
     */
    public function index()
    {
        // Obtener las categorías activas (para el menú)
        $categorias = $this->model->getCategorias(true) ?: [];

        // Obtener categorías destacadas para el grid del Home
        $categorias_destacadas = array_filter($categorias, fn($c) => !empty($c['destacado']));

        // Cargar familias para el menú
        $familiaModel = new Familia();
        foreach ($categorias as &$cat) {
            $cat['familias'] = $familiaModel->getByCategoria($cat['id'], true);
        }
        unset($cat);

        // Obtener productos destacados
        $productos_destacados = $this->model->getProductos(['destacado' => true, 'activo' => true, 'limite' => 12]) ?: [];

        // Cargar banners, ajustes y colecciones
        $banners_hero = [];
        $banners_recom = [];
        $ajustes = [];
        $colecciones = [];
        try {
            $bannerModel = new Banner();
            $banners_hero = $bannerModel->getAll(true, 'hero') ?: [];
            $banners_recom = $bannerModel->getAll(true, 'recommend') ?: [];

            $ajusteModel = new Ajuste();
            $ajustes = $ajusteModel->getAll() ?: [];

            $coleccionModel = new Coleccion();
            $colecciones = $coleccionModel->getAll(true) ?: [];
        } catch (Exception $e) {
            // Silently fail if models or tables don't exist yet
        }

        // Variables adicionales para la vista
        $pagina_actual = 'inicio';

        require VIEWS_PATH . 'productos/catalogo_publico.php';
    }

    /**
     * Ver productos por categoría (público)
     */
    public function categoria($slug)
    {
        $categoria = $this->model->getCategoriaBySlug($slug);

        // Obtener categorías con familias para el menú
        $categorias = $this->model->getCategorias(true);
        $familiaModel = new Familia();
        foreach ($categorias as &$cat) {
            $cat['familias'] = $familiaModel->getByCategoria($cat['id'], true);
        }
        unset($cat);

        if (!$categoria) {
            header('Location: ' . BASE_URL . 'productos');
            exit;
        }

        $filtros = [
            'categoria_id' => $categoria['id'],
            'activo' => true
        ];

        // Filtrar por familia si se proporciona el slug
        $familia_seleccionada = null;
        if (isset($_GET['familia'])) {
            $familiaModel = new Familia();
            $familia_seleccionada = $familiaModel->getBySlug($_GET['familia']);
            if ($familia_seleccionada && $familia_seleccionada['categoria_id'] == $categoria['id']) {
                $filtros['familia_id'] = $familia_seleccionada['id'];
            }
        }

        $productos = $this->model->getProductos($filtros);

        // Obtener familias de esta categoría para mostrar filtros laterales/superiores
        $familiaModel = $familiaModel ?? new Familia();
        $familias_categoria = $familiaModel->getByCategoria($categoria['id'], true);

        // Cargar ajustes para el pie de página y logo
        $ajustes = [];
        try {
            $ajusteModel = new Ajuste();
            $ajustes = $ajusteModel->getAll() ?: [];
        } catch (Exception $e) {
        }

        require_once VIEWS_PATH . 'productos/categoria.php';
    }

    /**
     * Buscar productos (público)
     */
    public function buscar()
    {
        $query = $_GET['q'] ?? '';
        $query = trim(strip_tags($query));

        if (empty($query)) {
            header('Location: ' . BASE_URL . 'productos');
            exit;
        }

        $productos = $this->model->getProductos(['buscar' => $query, 'activo' => true]);

        // Mock category structure for the view
        $categoria = [
            'nombre' => 'Búsqueda: ' . $query,
            'descripcion' => count($productos) . ' resultado(s) encontrado(s)',
            'id' => 0,
            'slug' => 'busqueda',
            'imagen' => '' // Optional: add a generic search banner if needed
        ];

        // Load categories for menu
        $categorias = $this->model->getCategorias(true);
        $familiaModel = new Familia();
        foreach ($categorias as &$cat) {
            $cat['familias'] = $familiaModel->getByCategoria($cat['id'], true);
        }
        unset($cat);

        // Cargar ajustes para el pie de página y logo
        $ajustes = [];
        try {
            $ajusteModel = new Ajuste();
            $ajustes = $ajusteModel->getAll() ?: [];
        } catch (Exception $e) {
        }

        require_once VIEWS_PATH . 'productos/categoria.php';
    }

    /**
     * Ver detalle de producto (público)
     */
    public function detalle($slug)
    {
        $producto = $this->model->getProductoBySlug($slug);

        // Obtener categorías con familias para el menú
        $categorias = $this->model->getCategorias(true);
        $familiaModel = new Familia();
        foreach ($categorias as &$cat) {
            $cat['familias'] = $familiaModel->getByCategoria($cat['id'], true);
        }
        unset($cat);

        if (!$producto) {
            header('Location: ' . BASE_URL . 'productos');
            exit;
        }

        // Incrementar vistas
        $this->model->incrementarVistas($producto['id']);

        // Obtener imágenes adicionales
        $imagenes = $this->model->getImagenesProducto($producto['id']);

        // Productos relacionados de la misma categoría
        $relacionados = $this->model->getProductos([
            'categoria_id' => $producto['categoria_id'],
            'activo' => true,
            'limite' => 4
        ]);

        // Filtrar el producto actual de los relacionados
        $relacionados = array_filter($relacionados, function ($p) use ($producto) {
            return $p['id'] != $producto['id'];
        });

        // Cargar ajustes para el pie de página y logo
        $ajustes = [];
        try {
            $ajusteModel = new Ajuste();
            $ajustes = $ajusteModel->getAll() ?: [];
        } catch (Exception $e) {
        }

        require_once VIEWS_PATH . 'productos/detalle.php';
    }

    // ===========================================
    // PANEL DE ADMINISTRACIÓN (Requiere auth)
    // ===========================================

    /**
     * Panel de administración de productos
     */
    public function admin()
    {
        requireAuth();
        requirePermiso('productos.ver');

        $productos = $this->model->getProductos();
        $categorias = $this->model->getCategorias();
        $estadisticas = $this->model->getEstadisticas();

        // Obtener familias para filtros si es necesario
        $familiaModel = new Familia();
        $familias = $familiaModel->getAll();

        require_once VIEWS_PATH . 'productos/admin/index.php';
    }

    /**
     * Crear producto (formulario)
     */
    public function crear()
    {
        requireAuth();
        requirePermiso('productos.crear');

        $categorias = $this->model->getCategorias(true);

        // Familias se cargarán por AJAX o estarán vacías inicialmente
        $familias = [];

        require_once VIEWS_PATH . 'productos/admin/crear.php';
    }

    /**
     * Guardar producto
     */
    public function guardar()
    {
        requireAuth();
        requirePermiso('productos.crear');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'productos/admin');
            exit;
        }

        verificarToken($_POST['csrf_token'] ?? '');

        // Validación estricta: Categoría y Familia son obligatorias
        if (empty($_POST['categoria_id']) || empty($_POST['familia_id'])) {
            setFlash('error', 'La Categoría y la Familia son obligatorias para crear un producto.');
            header('Location: ' . BASE_URL . 'productos/crear');
            exit;
        }

        try {
            // Procesar imagen principal si existe
            $imagen_principal = null;
            if (isset($_FILES['imagen_principal']) && $_FILES['imagen_principal']['error'] === UPLOAD_ERR_OK) {
                $imagen_principal = $this->subirImagen($_FILES['imagen_principal']);
            }

            $datos = [
                'categoria_id' => $_POST['categoria_id'] ?? null,
                'familia_id' => $_POST['familia_id'] ?? null,
                'nombre' => $_POST['nombre'] ?? '',
                'descripcion' => $_POST['descripcion'] ?? null,
                'descripcion_corta' => $_POST['descripcion_corta'] ?? null,
                'imagen_principal' => $imagen_principal,
                'precio_referencia' => !empty($_POST['precio_referencia']) ? $_POST['precio_referencia'] : null,
                'sku' => $_POST['sku'] ?? null,
                'marca' => $_POST['marca'] ?? null,
                'orden' => $_POST['orden'] ?? 0,
                'activo' => isset($_POST['activo']) ? 1 : 0,
                'destacado' => isset($_POST['destacado']) ? 1 : 0,
                'nuevo' => isset($_POST['nuevo']) ? 1 : 0,
                'rating' => $_POST['rating'] ?? 0
            ];

            $producto_id = $this->model->crearProducto($datos);
            if ($producto_id) {
                // Procesar imágenes adicionales
                if (isset($_FILES['imagenes_adicionales'])) {
                    $total_files = count($_FILES['imagenes_adicionales']['name']);
                    for ($i = 0; $i < $total_files; $i++) {
                        if ($_FILES['imagenes_adicionales']['error'][$i] === UPLOAD_ERR_OK) {
                            $file = [
                                'name' => $_FILES['imagenes_adicionales']['name'][$i],
                                'type' => $_FILES['imagenes_adicionales']['type'][$i],
                                'tmp_name' => $_FILES['imagenes_adicionales']['tmp_name'][$i],
                                'error' => $_FILES['imagenes_adicionales']['error'][$i],
                                'size' => $_FILES['imagenes_adicionales']['size'][$i]
                            ];
                            try {
                                $ruta = $this->subirImagen($file);
                                $this->model->agregarImagen($producto_id, $ruta, $datos['nombre'], $i);
                            } catch (Exception $e) {
                                // Continuar con otras imágenes si una falla
                            }
                        }
                    }
                }
                setFlash('success', 'Producto creado exitosamente');
            } else {
                setFlash('error', 'Error al crear el producto');
            }
        } catch (Exception $e) {
            setFlash('error', 'Error: ' . $e->getMessage());
            logSecurityEvent('error_producto', $e->getMessage());
        }

        header('Location: ' . BASE_URL . 'productos/admin');
        exit;
    }

    /**
     * Editar producto (formulario)
     */
    public function editar($id)
    {
        requireAuth();
        requirePermiso('productos.editar');

        $producto = $this->model->getProductoById($id);
        if (!$producto) {
            setFlash('error', 'Producto no encontrado');
            header('Location: ' . BASE_URL . 'productos/admin');
            exit;
        }

        $categorias = $this->model->getCategorias(true);

        $familiaModel = new Familia();
        $familias = $familiaModel->getByCategoria($producto['categoria_id'], true);

        $imagenes = $this->model->getImagenesProducto($id);

        require_once VIEWS_PATH . 'productos/admin/editar.php';
    }

    /**
     * Actualizar producto
     */
    public function actualizar($id)
    {
        requireAuth();
        requirePermiso('productos.editar');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'productos/admin');
            exit;
        }

        verificarToken($_POST['csrf_token'] ?? '');

        // Validación estricta: Categoría y Familia son obligatorias
        if (empty($_POST['categoria_id']) || empty($_POST['familia_id'])) {
            setFlash('error', 'La Categoría y la Familia son obligatorias para actualizar un producto.');
            header('Location: ' . BASE_URL . 'productos/editar/' . $id);
            exit;
        }

        try {
            $producto = $this->model->getProductoById($id);
            if (!$producto) {
                setFlash('error', 'Producto no encontrado');
                header('Location: ' . BASE_URL . 'productos/admin');
                exit;
            }

            // Procesar nueva imagen si existe
            $imagen_principal = $producto['imagen_principal'];
            if (isset($_FILES['imagen_principal']) && $_FILES['imagen_principal']['error'] === UPLOAD_ERR_OK) {
                $imagen_principal = $this->subirImagen($_FILES['imagen_principal']);
            }

            $datos = [
                'categoria_id' => $_POST['categoria_id'] ?? null,
                'familia_id' => $_POST['familia_id'] ?? null,
                'nombre' => $_POST['nombre'] ?? '',
                'descripcion' => $_POST['descripcion'] ?? null,
                'descripcion_corta' => $_POST['descripcion_corta'] ?? null,
                'imagen_principal' => $imagen_principal,
                'precio_referencia' => !empty($_POST['precio_referencia']) ? $_POST['precio_referencia'] : null,
                'sku' => $_POST['sku'] ?? null,
                'marca' => $_POST['marca'] ?? null,
                'orden' => $_POST['orden'] ?? 0,
                'activo' => isset($_POST['activo']) ? 1 : 0,
                'destacado' => isset($_POST['destacado']) ? 1 : 0,
                'nuevo' => isset($_POST['nuevo']) ? 1 : 0,
                'rating' => $_POST['rating'] ?? 0
            ];

            if ($this->model->actualizarProducto($id, $datos)) {
                // Procesar imágenes adicionales
                if (isset($_FILES['imagenes_adicionales'])) {
                    $total_files = count($_FILES['imagenes_adicionales']['name']);
                    for ($i = 0; $i < $total_files; $i++) {
                        if ($_FILES['imagenes_adicionales']['error'][$i] === UPLOAD_ERR_OK) {
                            $file = [
                                'name' => $_FILES['imagenes_adicionales']['name'][$i],
                                'type' => $_FILES['imagenes_adicionales']['type'][$i],
                                'tmp_name' => $_FILES['imagenes_adicionales']['tmp_name'][$i],
                                'error' => $_FILES['imagenes_adicionales']['error'][$i],
                                'size' => $_FILES['imagenes_adicionales']['size'][$i]
                            ];
                            try {
                                $ruta = $this->subirImagen($file);
                                $this->model->agregarImagen($id, $ruta, $datos['nombre'], $i);
                            } catch (Exception $e) {
                                // Continuar
                            }
                        }
                    }
                }
                setFlash('success', 'Producto actualizado exitosamente');
            } else {
                setFlash('error', 'Error al actualizar el producto');
            }
        } catch (Exception $e) {
            setFlash('error', 'Error: ' . $e->getMessage());
            logSecurityEvent('error_producto', $e->getMessage());
        }

        header('Location: ' . BASE_URL . 'productos/admin');
        exit;
    }

    /**
     * Eliminar producto
     */
    public function eliminar($id)
    {
        requireAuth();
        requirePermiso('productos.eliminar');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'productos/admin');
            exit;
        }

        verificarToken($_POST['csrf_token'] ?? '');

        try {
            if ($this->model->eliminarProducto($id)) {
                setFlash('success', 'Producto eliminado exitosamente');
                logSecurityEvent('producto_eliminado', "Producto ID: {$id}");
            } else {
                setFlash('error', 'Error al eliminar el producto');
            }
        } catch (Exception $e) {
            setFlash('error', 'Error: ' . $e->getMessage());
        }

        header('Location: ' . BASE_URL . 'productos/admin');
        exit;
    }

    // ===========================================
    // GESTIÓN DE CATEGORÍAS
    // ===========================================

    /**
     * Administración de categorías
     */
    public function categorias()
    {
        requireAuth();
        requirePermiso('categorias.ver');

        $categorias = $this->model->getCategorias();

        // Cargar familias para cada categoría
        $familiaModel = new Familia();
        foreach ($categorias as &$cat) {
            $cat['familias'] = $familiaModel->getByCategoria($cat['id']);
        }
        unset($cat);

        require_once VIEWS_PATH . 'productos/admin/categorias.php';
    }

    /**
     * Crear categoría
     */
    public function crearCategoria()
    {
        requireAuth();
        requirePermiso('categorias.crear');

        require_once VIEWS_PATH . 'productos/admin/crear_categoria.php';
    }

    /**
     * Guardar categoría
     */
    public function guardarCategoria()
    {
        requireAuth();
        requirePermiso('categorias.crear');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'productos/categorias');
            exit;
        }

        verificarToken($_POST['csrf_token'] ?? '');

        try {
            // Procesar imagen si existe
            $imagen = null;
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $imagen = $this->subirImagen($_FILES['imagen'], 'categorias');
            }

            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'descripcion' => $_POST['descripcion'] ?? null,
                'imagen' => $imagen,
                'icono' => $_POST['icono'] ?? 'bi-box-seam',
                'orden' => $_POST['orden'] ?? 0,
                'activo' => isset($_POST['activo']) ? 1 : 0,
                'destacado' => isset($_POST['destacado']) ? 1 : 0
            ];

            if ($this->model->crearCategoria($datos)) {
                setFlash('success', 'Categoría creada exitosamente');
            } else {
                setFlash('error', 'Error al crear la categoría');
            }
        } catch (Exception $e) {
            setFlash('error', 'Error: ' . $e->getMessage());
        }

        header('Location: ' . BASE_URL . 'productos/categorias');
        exit;
    }

    /**
     * Editar categoría
     */
    public function editarCategoria($id)
    {
        requireAuth();
        requirePermiso('categorias.editar');

        $categoria = $this->model->getCategoriaById($id);
        if (!$categoria) {
            setFlash('error', 'Categoría no encontrada');
            header('Location: ' . BASE_URL . 'productos/categorias');
            exit;
        }

        require_once VIEWS_PATH . 'productos/admin/editar_categoria.php';
    }

    /**
     * Actualizar categoría
     */
    public function actualizarCategoria($id)
    {
        requireAuth();
        requirePermiso('categorias.editar');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'productos/categorias');
            exit;
        }

        verificarToken($_POST['csrf_token'] ?? '');

        try {
            $categoria = $this->model->getCategoriaById($id);
            if (!$categoria) {
                setFlash('error', 'Categoría no encontrada');
                header('Location: ' . BASE_URL . 'productos/categorias');
                exit;
            }

            // Procesar nueva imagen si existe
            $imagen = $categoria['imagen'];
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $imagen = $this->subirImagen($_FILES['imagen'], 'categorias');
            }

            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'descripcion' => $_POST['descripcion'] ?? null,
                'imagen' => $imagen,
                'icono' => $_POST['icono'] ?? 'bi-box-seam',
                'orden' => $_POST['orden'] ?? 0,
                'activo' => isset($_POST['activo']) ? 1 : 0,
                'destacado' => isset($_POST['destacado']) ? 1 : 0
            ];

            if ($this->model->actualizarCategoria($id, $datos)) {
                setFlash('success', 'Categoría actualizada exitosamente');
            } else {
                setFlash('error', 'Error al actualizar la categoría');
            }
        } catch (Exception $e) {
            setFlash('error', 'Error: ' . $e->getMessage());
        }

        header('Location: ' . BASE_URL . 'productos/categorias');
        exit;
    }

    /**
     * Eliminar categoría
     */
    public function eliminarCategoria($id)
    {
        requireAuth();
        requirePermiso('categorias.eliminar');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'productos/categorias');
            exit;
        }

        verificarToken($_POST['csrf_token'] ?? '');

        try {
            if ($this->model->eliminarCategoria($id)) {
                setFlash('success', 'Categoría eliminada exitosamente');
                logSecurityEvent('categoria_eliminada', "Categoría ID: {$id}");
            } else {
                setFlash('error', 'Error al eliminar la categoría');
            }
        } catch (Exception $e) {
            setFlash('error', 'Error: ' . $e->getMessage());
        }

        header('Location: ' . BASE_URL . 'productos/categorias');
        exit;
    }

    // ===========================================
    // GESTIÓN DE FAMILIAS
    // ===========================================

    public function familias()
    {
        requireAuth();
        requirePermiso('categorias.ver'); // Reusar permiso o crear uno nuevo

        $familiaModel = new Familia();
        $familias = $familiaModel->getAll();

        require_once VIEWS_PATH . 'productos/admin/familias.php';
    }

    public function crearFamilia()
    {
        requireAuth();
        requirePermiso('categorias.crear');

        $categorias = $this->model->getCategorias(true);
        require_once VIEWS_PATH . 'productos/admin/crear_familia.php';
    }

    public function guardarFamilia()
    {
        requireAuth();
        requirePermiso('categorias.crear');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'productos/familias');
            exit;
        }

        verificarToken($_POST['csrf_token'] ?? '');

        try {
            $imagen = null;
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $imagen = $this->subirImagen($_FILES['imagen'], 'familias');
            }

            $familiaModel = new Familia();
            $datos = [
                'categoria_id' => $_POST['categoria_id'],
                'nombre' => $_POST['nombre'],
                'slug' => $_POST['slug'] ?? generarSlug($_POST['nombre']),
                'descripcion' => $_POST['descripcion'] ?? null,
                'imagen' => $imagen,
                'orden' => $_POST['orden'] ?? 0,
                'activo' => isset($_POST['activo']) ? 1 : 0
            ];

            if ($familiaModel->create($datos)) {
                setFlash('success', 'Familia creada exitosamente');
            } else {
                setFlash('error', 'Error al crear la familia');
            }
        } catch (Exception $e) {
            setFlash('error', 'Error: ' . $e->getMessage());
        }

        header('Location: ' . BASE_URL . 'productos/familias');
        exit;
    }

    public function editarFamilia($id)
    {
        requireAuth();
        requirePermiso('categorias.editar');

        $familiaModel = new Familia();
        $familia = $familiaModel->getById($id);

        if (!$familia) {
            setFlash('error', 'Familia no encontrada');
            header('Location: ' . BASE_URL . 'productos/familias');
            exit;
        }

        $categorias = $this->model->getCategorias(true);
        require_once VIEWS_PATH . 'productos/admin/editar_familia.php';
    }

    public function actualizarFamilia($id)
    {
        requireAuth();
        requirePermiso('categorias.editar');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'productos/familias');
            exit;
        }

        verificarToken($_POST['csrf_token'] ?? '');

        // Validación
        if (empty($_POST['nombre']) || empty($_POST['categoria_id'])) {
            setFlash('error', 'El nombre y la categoría son obligatorios.');
            header('Location: ' . BASE_URL . 'productos/editar-familia/' . $id);
            exit;
        }

        try {
            $familiaModel = new Familia();
            $familia = $familiaModel->getById($id);

            if (!$familia) {
                setFlash('error', 'Familia no encontrada.');
                header('Location: ' . BASE_URL . 'productos/familias');
                exit;
            }

            $imagen = $familia['imagen'];
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $imagen = $this->subirImagen($_FILES['imagen'], 'familias');
            }

            // Regenerar slug solo si el nombre cambió y no se proveyó uno manual (aunque el form no tiene manual)
            // O simplemente regenerar siempre que se cambie el nombre para mantener consistencia
            $slug = $_POST['slug'] ?? $familia['slug'];
            if ($familia['nombre'] !== $_POST['nombre']) {
                $slug = generarSlug($_POST['nombre']);
            }

            $datos = [
                'categoria_id' => (int) $_POST['categoria_id'],
                'nombre' => trim($_POST['nombre']),
                'slug' => $slug,
                'descripcion' => $_POST['descripcion'] ?? null,
                'imagen' => $imagen,
                'orden' => (int) ($_POST['orden'] ?? 0),
                'activo' => isset($_POST['activo']) ? 1 : 0
            ];

            if ($familiaModel->update($id, $datos)) {
                setFlash('success', 'Familia actualizada exitosamente');
            } else {
                setFlash('error', 'Error al actualizar la familia');
            }
        } catch (Exception $e) {
            setFlash('error', 'Error: ' . $e->getMessage());
        }

        header('Location: ' . BASE_URL . 'productos/familias');
        exit;
    }

    public function eliminarFamilia($id)
    {
        requireAuth();
        requirePermiso('categorias.eliminar');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'productos/familias');
            exit;
        }

        verificarToken($_POST['csrf_token'] ?? '');

        $familiaModel = new Familia();
        if ($familiaModel->delete($id)) {
            setFlash('success', 'Familia eliminada exitosamente');
        } else {
            setFlash('error', 'Error al eliminar la familia');
        }

        header('Location: ' . BASE_URL . 'productos/familias');
        exit;
    }

    /**
     * Obtener familias por categoría (AJAX)
     */
    public function getFamiliasAjax($categoria_id)
    {
        requireAuth();
        $familiaModel = new Familia();
        $familias = $familiaModel->getByCategoria($categoria_id, true);

        header('Content-Type: application/json');
        echo json_encode($familias);
        exit;
    }

    // ===========================================
    // IMPORTACIÓN MASIVA
    // ===========================================

    /**
     * Vista de importación masiva
     */
    public function importarMasivo()
    {
        requireAuth();
        requirePermiso('productos.crear');

        $categorias = $this->model->getCategorias(true);

        require_once VIEWS_PATH . 'productos/admin/importar.php';
    }

    /**
     * Procesar importación desde CSV
     */
    public function procesarImportacion()
    {
        requireAuth();
        requirePermiso('productos.crear');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'productos/admin');
            exit;
        }

        verificarToken($_POST['csrf_token'] ?? '');

        try {
            // Validar archivo
            if (!isset($_FILES['archivo_csv']) || $_FILES['archivo_csv']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('No se subió ningún archivo o hubo un error');
            }

            $archivo = $_FILES['archivo_csv']['tmp_name'];
            $extension = strtolower(pathinfo($_FILES['archivo_csv']['name'], PATHINFO_EXTENSION));

            if (!in_array($extension, ['csv', 'txt'])) {
                throw new Exception('Solo se permiten archivos CSV o TXT');
            }

            // Cargar helper de importación
            require_once ROOT_PATH . 'app/helpers/ExcelImporter.php';
            $importer = new ExcelImporter();

            // Procesar importación
            $resultado = $importer->importarCSV($archivo);

            // Preparar mensaje de resultado
            $mensaje = "Importación completada: ";
            $mensaje .= "{$resultado['importados']} productos nuevos, ";
            $mensaje .= "{$resultado['actualizados']} productos actualizados.";

            if (!empty($resultado['errores'])) {
                $mensaje .= " Se encontraron " . count($resultado['errores']) . " errores.";
                $_SESSION['import_errors'] = $resultado['errores'];
                setFlash('warning', $mensaje);
            } else {
                setFlash('success', $mensaje);
            }

            logSecurityEvent('productos_importados', "Importados: {$resultado['importados']}, Actualizados: {$resultado['actualizados']}");

        } catch (Exception $e) {
            setFlash('error', 'Error en la importación: ' . $e->getMessage());
            logSecurityEvent('error_importacion', $e->getMessage());
        }

        header('Location: ' . BASE_URL . 'productos/admin');
        exit;
    }

    /**
     * Descargar plantilla CSV de ejemplo
     */
    public function descargarPlantilla()
    {
        requireAuth();

        require_once ROOT_PATH . 'app/helpers/ExcelImporter.php';

        $csv = ExcelImporter::generarCSVEjemplo();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="plantilla_productos.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo "\xEF\xBB\xBF"; // BOM para UTF-8
        echo $csv;
        exit;
    }

    // ===========================================
    // GESTIÓN DE IMÁGENES ADICIONALES
    // ===========================================

    /**
     * Reordenar imágenes adicionales (AJAX POST)
     * Recibe: orden[] = [id1, id2, id3, ...]
     */
    public function reordenarImagenes()
    {
        requireAuth();
        requirePermiso('productos.editar');

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false]);
            exit;
        }

        verificarToken($_POST['csrf_token'] ?? '');

        $orden = $_POST['orden'] ?? [];
        if (empty($orden) || !is_array($orden)) {
            echo json_encode(['success' => false, 'message' => 'Sin datos de orden']);
            exit;
        }

        $conn = $this->model->getConn();
        foreach ($orden as $posicion => $img_id) {
            $stmt = $conn->prepare(
                "UPDATE imagenes_productos SET orden = :orden WHERE id = :id"
            );
            $stmt->execute([':orden' => (int)$posicion, ':id' => (int)$img_id]);
        }

        echo json_encode(['success' => true]);
        exit;
    }

    /**
     * Eliminar imagen adicional (AJAX POST)
     */
    public function eliminarImagen($id)
    {
        requireAuth();
        requirePermiso('productos.editar');

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit;
        }

        verificarToken($_POST['csrf_token'] ?? '');

        // Verificar que la imagen existe
        $conn = $this->model->getConn();
        $stmt = $conn->prepare("SELECT * FROM imagenes_productos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $imagen = $stmt->fetch();

        if (!$imagen) {
            echo json_encode(['success' => false, 'message' => 'Imagen no encontrada']);
            exit;
        }

        if ($this->model->eliminarImagen($id)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar la imagen']);
        }
        exit;
    }

    /**
     * Establecer imagen adicional como imagen principal del producto (AJAX POST)
     */
    public function establecerImagenPrincipal($imagen_id)
    {
        requireAuth();
        requirePermiso('productos.editar');

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit;
        }

        verificarToken($_POST['csrf_token'] ?? '');

        try {
            // Obtener datos de la imagen adicional
            $conn = $this->model->getConn();
            $stmt = $conn->prepare("SELECT * FROM imagenes_productos WHERE id = :id");
            $stmt->execute([':id' => $imagen_id]);
            $imagen = $stmt->fetch();

            if (!$imagen) {
                echo json_encode(['success' => false, 'message' => 'Imagen no encontrada']);
                exit;
            }

            $producto_id = $imagen['producto_id'];

            // Obtener imagen principal actual del producto
            $stmtP = $conn->prepare("SELECT imagen_principal FROM productos_catalogo WHERE id = :id");
            $stmtP->execute([':id' => $producto_id]);
            $producto = $stmtP->fetch();

            // Intercambiar: la nueva principal va al producto, la antigua queda como adicional si existe
            // 1. Actualizar imagen_principal del producto
            $stmtU = $conn->prepare(
                "UPDATE productos_catalogo SET imagen_principal = :ruta WHERE id = :id"
            );
            $stmtU->execute([':ruta' => $imagen['ruta'], ':id' => $producto_id]);

            // 2. Si había imagen principal anterior, agregarla como adicional
            if (!empty($producto['imagen_principal'])) {
                $this->model->agregarImagen($producto_id, $producto['imagen_principal'], null, 99);
            }

            // 3. Borrar la imagen de la tabla de adicionales
            $this->model->eliminarImagen($imagen_id);

            echo json_encode(['success' => true, 'nueva_principal' => $imagen['ruta']]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    // ===========================================
    // UTILIDADES
    // ===========================================

    /**
     * Subir imagen
     */
    private function subirImagen($file, $subcarpeta = 'productos')
    {
        $dir_uploads = UPLOADS_PATH . $subcarpeta . '/';

        if (!is_dir($dir_uploads)) {
            mkdir($dir_uploads, 0755, true);
        }

        // Validar tamaño: máximo 3 MB para productos (optimizado para 10k imágenes)
        $max_bytes = 3 * 1024 * 1024;
        if ($file['size'] > $max_bytes) {
            throw new Exception('La imagen supera el tamaño máximo permitido (3 MB)');
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

        if (!in_array($extension, $permitidas)) {
            throw new Exception('Tipo de archivo no permitido');
        }

        // Verificar que sea una imagen real (no un archivo renombrado)
        if ($extension !== 'svg') {
            $mime = mime_content_type($file['tmp_name']);
            if (!str_starts_with($mime, 'image/')) {
                throw new Exception('El archivo no es una imagen válida');
            }
        }

        // Convertir jpg/jpeg/png a WebP para reducir peso ~30% —clave para escalar a 10k imágenes.
        if (in_array($extension, ['jpg', 'jpeg', 'png']) && function_exists('imagewebp')) {
            $nombre = uniqid($subcarpeta . '_') . '.webp';
            $ruta   = $dir_uploads . $nombre;

            $src = ($extension === 'png')
                ? imagecreatefrompng($file['tmp_name'])
                : imagecreatefromjpeg($file['tmp_name']);

            if ($src && imagewebp($src, $ruta, 80)) {
                imagedestroy($src);
                return 'uploads_privados/' . $subcarpeta . '/' . $nombre;
            }
            if ($src) imagedestroy($src);
        }

        $nombre = uniqid($subcarpeta . '_') . '.' . $extension;
        $ruta   = $dir_uploads . $nombre;

        if (move_uploaded_file($file['tmp_name'], $ruta)) {
            return 'uploads_privados/' . $subcarpeta . '/' . $nombre;
        }

        throw new Exception('Error al subir la imagen');
    }
}
