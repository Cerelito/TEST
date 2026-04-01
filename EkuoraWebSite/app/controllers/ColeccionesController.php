<?php
// app/controllers/ColeccionesController.php

class ColeccionesController
{
    private $model;

    public function __construct()
    {
        $this->model = new Coleccion();
    }

    /**
     * Listado de colecciones (Admin)
     */
    public function admin()
    {
        requireAuth();
        requirePermiso('productos.ver'); // Reutilizamos permisos de productos

        $colecciones = $this->model->getAll();
        require_once VIEWS_PATH . 'colecciones/admin/index.php';
    }

    /**
     * Crear colección (formulario)
     */
    public function crear()
    {
        requireAuth();
        requirePermiso('productos.crear');
        require_once VIEWS_PATH . 'colecciones/admin/crear.php';
    }

    /**
     * Guardar colección
     */
    public function guardar()
    {
        requireAuth();
        requirePermiso('productos.crear');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verificarToken($_POST['csrf_token'] ?? '');

            $imagen = null;
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $imagen = $this->subirImagen($_FILES['imagen'], 'colecciones');
            }

            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'slug' => generarSlug($_POST['nombre'] ?? ''),
                'descripcion' => $_POST['descripcion'] ?? null,
                'imagen' => $imagen,
                'orden' => $_POST['orden'] ?? 0,
                'activo' => isset($_POST['activo']) ? 1 : 0
            ];

            if ($this->model->create($datos)) {
                setFlash('success', 'Colección creada correctamente');
            } else {
                setFlash('error', 'Error al crear la colección');
            }
        }

        header('Location: ' . BASE_URL . 'colecciones/admin');
        exit;
    }

    /**
     * Editar colección (formulario)
     */
    public function editar($id)
    {
        requireAuth();
        requirePermiso('productos.editar');

        $coleccion = $this->model->getById($id);
        if (!$coleccion) {
            setFlash('error', 'Colección no encontrada');
            header('Location: ' . BASE_URL . 'colecciones/admin');
            exit;
        }

        require_once VIEWS_PATH . 'colecciones/admin/editar.php';
    }

    /**
     * Actualizar colección
     */
    public function actualizar($id)
    {
        requireAuth();
        requirePermiso('productos.editar');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verificarToken($_POST['csrf_token'] ?? '');

            $coleccion = $this->model->getById($id);
            if (!$coleccion) {
                setFlash('error', 'Colección no encontrada');
                header('Location: ' . BASE_URL . 'colecciones/admin');
                exit;
            }

            $imagen = $coleccion['imagen'];
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $imagen = $this->subirImagen($_FILES['imagen'], 'colecciones');
            }

            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'slug' => generarSlug($_POST['nombre'] ?? ''),
                'descripcion' => $_POST['descripcion'] ?? null,
                'imagen' => $imagen,
                'orden' => $_POST['orden'] ?? 0,
                'activo' => isset($_POST['activo']) ? 1 : 0
            ];

            if ($this->model->update($id, $datos)) {
                setFlash('success', 'Colección actualizada correctamente');
            } else {
                setFlash('error', 'Error al actualizar la colección');
            }
        }

        header('Location: ' . BASE_URL . 'colecciones/admin');
        exit;
    }

    /**
     * Eliminar colección
     */
    public function eliminar($id)
    {
        requireAuth();
        requirePermiso('productos.eliminar');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verificarToken($_POST['csrf_token'] ?? '');
            if ($this->model->delete($id)) {
                setFlash('success', 'Colección eliminada');
            } else {
                setFlash('error', 'Error al eliminar');
            }
        }

        header('Location: ' . BASE_URL . 'colecciones/admin');
        exit;
    }

    /**
     * Helper para subir imágenes
     */
    private function subirImagen($file, $folder = 'colecciones')
    {
        $dir_uploads = UPLOADS_PATH . $folder . '/';

        if (!is_dir($dir_uploads)) {
            mkdir($dir_uploads, 0755, true);
        }

        // Validar tamaño máximo (3 MB)
        if ($file['size'] > 3 * 1024 * 1024) {
            throw new Exception('La imagen supera el tamaño máximo permitido (3 MB)');
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

        if (!in_array($extension, $permitidas)) {
            throw new Exception('Tipo de archivo no permitido');
        }

        // Verificar que sea imagen real
        if ($extension !== 'svg') {
            $mime = mime_content_type($file['tmp_name']);
            if (!str_starts_with($mime, 'image/')) {
                throw new Exception('El archivo no es una imagen válida');
            }
        }

        // Convertir a WebP si procede
        if (in_array($extension, ['jpg', 'jpeg', 'png']) && function_exists('imagewebp')) {
            $nombre = uniqid($folder . '_') . '.webp';
            $ruta   = $dir_uploads . $nombre;
            $src = ($extension === 'png') ? imagecreatefrompng($file['tmp_name']) : imagecreatefromjpeg($file['tmp_name']);
            if ($src && imagewebp($src, $ruta, 80)) {
                imagedestroy($src);
                return 'uploads_privados/' . $folder . '/' . $nombre;
            }
            if ($src) imagedestroy($src);
        }

        $nombre = uniqid($folder . '_') . '.' . $extension;
        $ruta   = $dir_uploads . $nombre;

        if (move_uploaded_file($file['tmp_name'], $ruta)) {
            return 'uploads_privados/' . $folder . '/' . $nombre;
        }

        return null;
    }
}
