<?php

class RequisitorController extends Controller
{
    private Requisitor $model;
    private Empleado   $empleadoModel;

    public function __construct()
    {
        requireAuth();
        $this->model         = new Requisitor();
        $this->empleadoModel = new Empleado();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // index — list requisitores
    // ─────────────────────────────────────────────────────────────────────────

    public function index(): void
    {
        $filters = [
            'buscar'     => $_GET['buscar']     ?? '',
            'empresa_id' => $_GET['empresa_id'] ?? '',
            'activo'     => $_GET['activo']     ?? '',
        ];

        $requisitores = $this->model->getAll(
            array_filter($filters, fn($v) => $v !== '')
        );
        $empresas = $this->empleadoModel->getEmpresasList();

        $pdo = Database::getInstance();
        $empleados_disponibles = $pdo->query(
            "SELECT DISTINCT e.id, e.nombre, emp.nombre AS empresa_nombre
             FROM empleados e
             LEFT JOIN empresas emp ON emp.id = e.empresa_id
             INNER JOIN empleado_cc ecc ON ecc.empleado_id = e.id
                 AND ecc.tipo IN ('REQ','AMBOS') AND ecc.activo = 1
             WHERE e.deleted_at IS NULL AND e.activo = 1
               AND e.id NOT IN (SELECT empleado_id FROM requisitores WHERE activo = 1)
             ORDER BY e.nombre"
        )->fetchAll(\PDO::FETCH_ASSOC);

        $this->render('requisitores/index', [
            'title'                => 'Requisitores',
            'requisitores'         => $requisitores,
            'empresas'             => $empresas,
            'filters'              => $filters,
            'empleados_disponibles'=> $empleados_disponibles,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // asignar — add employee to requisitores (POST)
    // ─────────────────────────────────────────────────────────────────────────

    public function asignar(): void
    {
        requireRole(['admin', 'capturista']);
        verifyCSRF();

        $empleadoId = (int)($_POST['empleado_id'] ?? 0);

        if (!$empleadoId) {
            setFlash('error', 'Selecciona un empleado.');
            redirect('requisitores');
        }

        $empleado = $this->empleadoModel->find($empleadoId);
        if (!$empleado) {
            setFlash('error', 'Empleado no encontrado.');
            redirect('requisitores');
        }

        $this->model->asignar($empleadoId, ['activo' => 1]);

        logAction('asignar', 'requisitores', "Requisitor asignado: empleado ID $empleadoId");
        setFlash('success', 'Requisitor asignado exitosamente.');
        redirect('requisitores');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // quitar — remove from requisitores (POST)
    // ─────────────────────────────────────────────────────────────────────────

    public function quitar($id): void
    {
        requireRole(['admin']);
        verifyCSRF();

        $this->model->eliminar((int)$id);

        logAction('quitar', 'requisitores', "Requisitor removido empleado ID: $id");
        setFlash('success', 'Requisitor eliminado.');
        redirect('requisitores');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ver — detail view
    // ─────────────────────────────────────────────────────────────────────────

    public function ver($id): void
    {
        $data = $this->model->getWithCentrosCosto((int)$id);
        if (!$data) {
            setFlash('error', 'Requisitor no encontrado.');
            redirect('requisitores');
        }

        $this->render('requisitores/ver', [
            'title' => 'Requisitor: ' . htmlspecialchars($data['nombre_completo'] ?? $data['nombre'] ?? ''),
            'req'   => $data,
        ]);
    }
}
