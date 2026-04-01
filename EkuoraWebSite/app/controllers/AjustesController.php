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

            $uploadDir = UPLOADS_PATH . 'ajustes/'; // Use UPLOADS_PATH constant
            if (!is_dir($uploadDir))
                mkdir($uploadDir, 0755, true);

            // Manejo de imagen promo
            if (!empty($_FILES['promo_imagen']['name'])) {
                $fileName = time() . '_promo_' . basename($_FILES['promo_imagen']['name']);
                $targetFile = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['promo_imagen']['tmp_name'], $targetFile)) {
                    $datos['promo_imagen'] = 'uploads_privados/ajustes/' . $fileName;
                }
            }

            // Manejo de Logo Navbar
            if (!empty($_FILES['logo_navbar']['name'])) {
                $fileName = time() . '_logo_' . basename($_FILES['logo_navbar']['name']);
                $targetFile = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['logo_navbar']['tmp_name'], $targetFile)) {
                    $datos['logo_navbar'] = 'uploads_privados/ajustes/' . $fileName;
                }
            }

            // Manejo de Imagen About
            if (!empty($_FILES['about_imagen']['name'])) {
                $fileName = time() . '_about_' . basename($_FILES['about_imagen']['name']);
                $targetFile = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['about_imagen']['tmp_name'], $targetFile)) {
                    $datos['about_imagen'] = 'uploads_privados/ajustes/' . $fileName;
                }
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
