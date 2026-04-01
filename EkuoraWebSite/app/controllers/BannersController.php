<?php
// app/controllers/BannersController.php

class BannersController
{
    private $model;

    public function __construct()
    {
        requireAuth();
        $this->model = new Banner();
    }

    public function admin()
    {
        requirePermiso('banners.ver');
        $banners = $this->model->getAll();
        require_once VIEWS_PATH . 'banners/admin/index.php';
    }

    public function crear()
    {
        requirePermiso('banners.crear');
        require_once VIEWS_PATH . 'banners/admin/crear.php';
    }

    public function guardar()
    {
        requirePermiso('banners.crear');
        verificarCSRF();

        try {
            $imagen = null;
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $imagen = $this->subirImagen($_FILES['imagen']);
            }

            if (!$imagen) {
                throw new Exception('La imagen es obligatoria');
            }

            $datos = [
                'titulo' => $_POST['titulo'] ?? null,
                'subtitulo' => $_POST['subtitulo'] ?? null,
                'texto_boton' => $_POST['texto_boton'] ?? 'VER MÁS',
                'enlace' => $_POST['enlace'] ?? null,
                'seccion' => $_POST['seccion'] ?? 'hero',
                'imagen' => $imagen,
                'orden' => $_POST['orden'] ?? 0,
                'activo' => isset($_POST['activo']) ? 1 : 0
            ];

            if ($this->model->create($datos)) {
                setFlash('success', 'Banner creado exitosamente');
            } else {
                setFlash('error', 'Error al crear el banner');
            }
        } catch (Exception $e) {
            setFlash('error', $e->getMessage());
        }

        header('Location: ' . BASE_URL . 'banners/admin');
        exit;
    }

    public function editar($id)
    {
        requirePermiso('banners.editar');
        $banner = $this->model->getById($id);
        if (!$banner) {
            setFlash('error', 'Banner no encontrado');
            header('Location: ' . BASE_URL . 'banners/admin');
            exit;
        }
        require_once VIEWS_PATH . 'banners/admin/editar.php';
    }

    public function actualizar($id)
    {
        requirePermiso('banners.editar');
        verificarCSRF();

        try {
            $banner = $this->model->getById($id);
            if (!$banner) {
                throw new Exception('Banner no encontrado');
            }

            $imagen = $banner['imagen'];
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $imagen = $this->subirImagen($_FILES['imagen']);
            }

            $datos = [
                'titulo' => $_POST['titulo'] ?? null,
                'subtitulo' => $_POST['subtitulo'] ?? null,
                'texto_boton' => $_POST['texto_boton'] ?? 'VER MÁS',
                'enlace' => $_POST['enlace'] ?? null,
                'seccion' => $_POST['seccion'] ?? 'hero',
                'imagen' => $imagen,
                'orden' => $_POST['orden'] ?? 0,
                'activo' => isset($_POST['activo']) ? 1 : 0
            ];

            if ($this->model->update($id, $datos)) {
                setFlash('success', 'Banner actualizado exitosamente');
            } else {
                setFlash('error', 'Error al actualizar el banner');
            }
        } catch (Exception $e) {
            setFlash('error', $e->getMessage());
        }

        header('Location: ' . BASE_URL . 'banners/admin');
        exit;
    }

    public function eliminar($id)
    {
        requirePermiso('banners.eliminar');
        verificarCSRF();
        if ($this->model->delete($id)) {
            setFlash('success', 'Banner eliminado exitosamente');
        } else {
            setFlash('error', 'Error al eliminar el banner');
        }
        header('Location: ' . BASE_URL . 'banners/admin');
        exit;
    }

    public function toggleEstado($id)
    {
        requirePermiso('banners.editar');
        if ($this->model->toggleEstado($id)) {
            setFlash('success', 'Estado actualizado');
        } else {
            setFlash('error', 'Error al actualizar estado');
        }
        header('Location: ' . BASE_URL . 'banners/admin');
        exit;
    }

    private function subirImagen($file)
    {
        $dir_uploads = UPLOADS_PATH . 'banners/';

        if (!is_dir($dir_uploads)) {
            mkdir($dir_uploads, 0755, true);
        }

        // Validar tamaño: máximo 5 MB para banners
        $max_bytes = 5 * 1024 * 1024;
        if ($file['size'] > $max_bytes) {
            throw new Exception('La imagen supera el tamaño máximo permitido (5 MB)');
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($extension, $permitidas)) {
            throw new Exception('Tipo de archivo no permitido');
        }

        // Verificar que sea una imagen real (no un archivo renombrado)
        $mime = mime_content_type($file['tmp_name']);
        if (!str_starts_with($mime, 'image/')) {
            throw new Exception('El archivo no es una imagen válida');
        }

        // Convertir a WebP si la extensión de entrada es jpg/jpeg/png y GD está disponible.
        // WebP reduce el peso entre 25-35% manteniendo calidad visual —ideal para 10k imágenes.
        if (in_array($extension, ['jpg', 'jpeg', 'png']) && function_exists('imagewebp')) {
            $nombre = uniqid('banner_') . '.webp';
            $ruta   = $dir_uploads . $nombre;

            $src = ($extension === 'png')
                ? imagecreatefrompng($file['tmp_name'])
                : imagecreatefromjpeg($file['tmp_name']);

            if ($src && imagewebp($src, $ruta, 82)) {
                imagedestroy($src);
                return 'uploads_privados/banners/' . $nombre;
            }
            if ($src) imagedestroy($src);
            // Si la conversión falla, caer al guardado original
        }

        $nombre = uniqid('banner_') . '.' . $extension;
        $ruta   = $dir_uploads . $nombre;

        if (move_uploaded_file($file['tmp_name'], $ruta)) {
            return 'uploads_privados/banners/' . $nombre;
        }

        throw new Exception('Error al subir la imagen');
    }
}
