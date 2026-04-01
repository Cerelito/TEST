<?php
/**
 * ApiController - Controlador para endpoints AJAX/API
 *
 * Maneja las peticiones asíncronas del sistema como:
 * - Validaciones en tiempo real
 */

class ApiController
{

    public function __construct()
    {
        // Constructor vacío ya que se eliminó el dependiente CatalogoModel
    }

    /**
     * POST /api/validar-username
     * Valida si un username ya existe
     *
     * @return void (JSON response)
     */
    public function validarUsername()
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Método no permitido']);
            return;
        }

        $username = $_POST['username'] ?? '';
        $usuario_id = $_POST['usuario_id'] ?? null;

        if (empty($username)) {
            echo json_encode(['success' => false, 'error' => 'Username no proporcionado']);
            return;
        }

        try {
            $userModel = new User();
            $existe = $userModel->existeUsername($username, $usuario_id);

            echo json_encode([
                'success' => true,
                'existe' => $existe,
                'mensaje' => $existe ? 'El usuario ya existe' : 'Usuario disponible'
            ], JSON_UNESCAPED_UNICODE);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Método por defecto - retorna info de la API
     */
    public function index()
    {
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            'success' => true,
            'api' => 'Ekuora Admin API',
            'version' => '1.0.0',
            'endpoints' => [
                'POST /api/validar-username' => 'Validar disponibilidad de username'
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
