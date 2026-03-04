<?php
/**
 * ApiController - Controlador para endpoints AJAX/API
 *
 * Maneja las peticiones asíncronas del sistema como:
 * - Carga dinámica de municipios por estado
 * - Validaciones en tiempo real
 * - Búsquedas autocomplete
 */

class ApiController
{

    private $catalogoModel;

    public function __construct()
    {
        // No requerimos autenticación estricta para todos los endpoints para permitir validaciones ágiles,
        // pero idealmente deberías verificar sesión si es información sensible.
        // En este caso, como son catálogos y validaciones de existencia, es seguro.
        $this->catalogoModel = new Catalogo();
    }

    /**
     * GET /api/municipios/{estado_id}
     * Retorna los municipios de un estado en formato JSON
     * Utilizado en: Crear/Editar Proveedor
     *
     * @param int $estado_id ID del estado
     */
    public function municipios($estado_id = null)
    {
        // Establecer header JSON
        header('Content-Type: application/json; charset=utf-8');

        // Validar que se proporcionó un ID de estado
        if (!$estado_id || !is_numeric($estado_id)) {
            http_response_code(400);
            echo json_encode(['error' => 'ID de estado no válido'], JSON_UNESCAPED_UNICODE);
            return;
        }

        try {
            // Obtener municipios del estado desde el Modelo
            $municipios = $this->catalogoModel->getMunicipios((int) $estado_id);

            // Retornar respuesta exitosa (Array directo para fácil consumo en JS)
            http_response_code(200);
            echo json_encode($municipios, JSON_UNESCAPED_UNICODE);

        } catch (Exception $e) {
            // Error en la consulta
            http_response_code(500);
            echo json_encode(['error' => 'Error al obtener municipios: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * GET /api/validarRfc/{rfc}
     * Valida si un RFC ya existe en el sistema y retorna el código EK si existe.
     * Utilizado en: Crear Proveedor (Validación en tiempo real)
     *
     * @param string $rfc RFC a validar
     */
    public function validarRfc($rfc = null)
    {
        header('Content-Type: application/json; charset=utf-8');

        if (empty($rfc)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'RFC no proporcionado'
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        // Normalizar RFC (mayúsculas, sin espacios)
        $rfc = strtoupper(trim($rfc));

        try {
            $proveedorModel = new Proveedor();

            // Buscar si existe el RFC
            $proveedor = $proveedorModel->getByRFC($rfc);

            if ($proveedor) {
                // RFC existe - retornar datos para mostrar alerta
                echo json_encode([
                    'success' => true,
                    'existe' => true,
                    'codigo' => $proveedor['IdManual'] ?? 'Sin código asignado',
                    'razonSocial' => $proveedor['RazonSocial'] ?? ($proveedor['Nombre'] . ' ' . $proveedor['ApellidoPaterno']),
                    'mensaje' => 'El RFC ya está registrado'
                ], JSON_UNESCAPED_UNICODE);
            } else {
                // RFC disponible
                echo json_encode([
                    'success' => true,
                    'existe' => false,
                    'mensaje' => 'RFC disponible'
                ], JSON_UNESCAPED_UNICODE);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Error al validar RFC: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * POST /api/validar-username
     * Valida si un username ya existe
     * Utilizado en: Crear Usuario
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
     * GET /api/validarCodigoProveedor/{codigo}
     * Valida si un código de proveedor (ID Manual) ya existe
     * Utilizado en: Crear/Editar Proveedor (Solo Admin)
     *
     * @param string $codigo Código del proveedor a validar
     */
    public function validarCodigoProveedor($codigo = null)
    {
        header('Content-Type: application/json; charset=utf-8');

        if (empty($codigo)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Código no proporcionado'
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        try {
            $proveedorModel = new Proveedor();

            // Verificar si existe el código
            $existe = $proveedorModel->existeCodigoProveedor($codigo);

            http_response_code(200);
            echo json_encode([
                'success' => true,
                'existe' => $existe,
                'mensaje' => $existe ? 'El código ya está en uso' : 'Código disponible'
            ], JSON_UNESCAPED_UNICODE);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Error al validar código: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * GET /api/estados
     * Retorna todos los estados
     */
    public function estados()
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $estados = $this->catalogoModel->getEstados();
            http_response_code(200);
            echo json_encode($estados, JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * GET /api/bancos
     * Retorna todos los bancos activos
     */
    public function bancos()
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $bancos = $this->catalogoModel->getBancos(true);
            http_response_code(200);
            echo json_encode($bancos, JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * GET /api/cias
     * Retorna todas las compañías activas
     */
    public function cias()
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $cias = $this->catalogoModel->getCias(true);
            http_response_code(200);
            echo json_encode($cias, JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * GET /api/regimenes
     * Retorna todos los regímenes fiscales activos
     */
    public function regimenes()
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $regimenes = $this->catalogoModel->getRegimenes(true);
            http_response_code(200);
            echo json_encode($regimenes, JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * POST /api/buscar-proveedores
     * Búsqueda de proveedores para autocomplete
     */
    public function buscarProveedores()
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Método no permitido']);
            return;
        }

        $termino = $_POST['q'] ?? '';

        if (strlen($termino) < 2) {
            echo json_encode(['success' => false, 'error' => 'Término de búsqueda muy corto']);
            return;
        }

        try {
            $proveedorModel = new Proveedor();
            $resultados = $proveedorModel->buscar($termino);

            // Formatear resultados para autocomplete
            $items = array_map(function ($p) {
                return [
                    'id' => $p['Id'],
                    'rfc' => $p['RFC'],
                    'razon_social' => $p['RazonSocial'],
                    'label' => $p['RFC'] . ' - ' . $p['RazonSocial']
                ];
            }, $resultados);

            echo json_encode([
                'success' => true,
                'items' => $items
            ], JSON_UNESCAPED_UNICODE);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Método por defecto
     */
    public function index()
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => true,
            'api' => 'EK Proveedores MVC v2.1 API',
            'status' => 'Running'
        ]);
    }
}