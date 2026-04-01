<?php
// app/controllers/AjustesController.php

class AjustesController
{
    private $ajusteModel;

    public function __construct()
    {
        requireAuth();
        $this->ajusteModel = new Ajuste();
    }

    /**
     * Mostrar formulario de ajustes
     */
    public function index()
    {
        requirePermiso('configuracion.ver');
        $ajustes = $this->ajusteModel->getAll();
        require_once VIEWS_PATH . 'ajustes/index.php';
    }

    /**
     * Guardar ajustes
     */
    public function guardar()
    {
        requirePermiso('configuracion.editar');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verificarCSRF();

            $datos = [
                'promo_titulo' => $_POST['promo_titulo'] ?? '',
                'promo_subtitulo' => $_POST['promo_subtitulo'] ?? '',
                'promo_texto_boton' => $_POST['promo_texto_boton'] ?? '',
                'promo_enlace' => $_POST['promo_enlace'] ?? '',
                'about_badge' => $_POST['about_badge'] ?? '',
                'about_titulo' => $_POST['about_titulo'] ?? '',
                'about_descripcion' => $_POST['about_descripcion'] ?? '',
                'about_f1_titulo' => $_POST['about_f1_titulo'] ?? '',
                'about_f1_texto' => $_POST['about_f1_texto'] ?? '',
                'about_f2_titulo' => $_POST['about_f2_titulo'] ?? '',
                'about_f2_texto' => $_POST['about_f2_texto'] ?? '',
                'about_f3_titulo' => $_POST['about_f3_titulo'] ?? '',
                'about_f3_texto' => $_POST['about_f3_texto'] ?? '',
                'about_f4_titulo' => $_POST['about_f4_titulo'] ?? '',
                'about_f4_texto' => $_POST['about_f4_texto'] ?? '',
                'footer_texto' => $_POST['footer_texto'] ?? '',
                'footer_email' => $_POST['footer_email'] ?? '',
                'footer_telefono' => $_POST['footer_telefono'] ?? '',
                'footer_direccion' => $_POST['footer_direccion'] ?? '',
                'footer_facebook' => $_POST['footer_facebook'] ?? '',
                'footer_instagram' => $_POST['footer_instagram'] ?? '',
                'footer_youtube' => $_POST['footer_youtube'] ?? '',
                'footer_tiktok' => $_POST['footer_tiktok'] ?? ''
            ];

            $uploadDir = UPLOADS_PATH . 'ajustes/';
            if (!is_dir($uploadDir))
                mkdir($uploadDir, 0755, true);

            $ext_permitidas = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
            $max_bytes      = 5 * 1024 * 1024; // 5 MB

            // Helper local para subir una imagen de ajuste con validación
            $subirAjuste = function($campo, $prefijo) use ($uploadDir, $ext_permitidas, $max_bytes) {
                if (empty($_FILES[$campo]['name'])) return null;
                $file = $_FILES[$campo];
                if ($file['size'] > $max_bytes) {
                    throw new Exception("La imagen '$campo' supera el tamaño máximo (5 MB)");
                }
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                if (!in_array($ext, $ext_permitidas)) {
                    throw new Exception("Tipo de archivo no permitido para '$campo'");
                }
                $mime = mime_content_type($file['tmp_name']);
                if (!str_starts_with($mime, 'image/')) {
                    throw new Exception("El archivo '$campo' no es una imagen válida");
                }
                // Convertir a WebP si procede
                if (in_array($ext, ['jpg', 'jpeg', 'png']) && function_exists('imagewebp')) {
                    $nombre = time() . "_{$prefijo}.webp";
                    $ruta   = $uploadDir . $nombre;
                    $src = ($ext === 'png') ? imagecreatefrompng($file['tmp_name']) : imagecreatefromjpeg($file['tmp_name']);
                    if ($src && imagewebp($src, $ruta, 82)) {
                        imagedestroy($src);
                        return 'uploads_privados/ajustes/' . $nombre;
                    }
                    if ($src) imagedestroy($src);
                }
                $nombre = time() . "_{$prefijo}." . $ext;
                if (move_uploaded_file($file['tmp_name'], $uploadDir . $nombre)) {
                    return 'uploads_privados/ajustes/' . $nombre;
                }
                return null;
            };

            try {
                if ($r = $subirAjuste('promo_imagen',  'promo'))  $datos['promo_imagen']  = $r;
                if ($r = $subirAjuste('logo_navbar',   'logo'))   $datos['logo_navbar']   = $r;
                if ($r = $subirAjuste('about_imagen',  'about'))  $datos['about_imagen']  = $r;
            } catch (Exception $e) {
                setFlash('error', $e->getMessage());
                header('Location: ' . BASE_URL . 'ajustes');
                exit;
            }

            if ($this->ajusteModel->saveMultiple($datos)) {
                setFlash('success', 'Ajustes actualizados correctamente');
            } else {
                setFlash('error', 'Error al guardar los ajustes');
            }
        }

        header('Location: ' . BASE_URL . 'ajustes');
        exit;
    }
}
