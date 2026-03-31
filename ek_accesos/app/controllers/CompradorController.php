<?php

class CompradorController extends Controller
{
    private Comprador $model;
    private Empleado  $empleadoModel;

    public function __construct()
    {
        requireAuth();
        $this->model         = new Comprador();
        $this->empleadoModel = new Empleado();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // index — list compradores
    // ─────────────────────────────────────────────────────────────────────────

    public function index(): void
    {
        $filters = [
            'buscar'     => $_GET['buscar']     ?? '',
            'empresa_id' => $_GET['empresa_id'] ?? '',
            'activo'     => $_GET['activo']     ?? '',
        ];

        $compradores = $this->model->getAll(
            array_filter($filters, fn($v) => $v !== '')
        );
        $empresas = $this->empleadoModel->getEmpresasList();

        $pdo = Database::getInstance();
        $empleados_disponibles = $pdo->query(
            "SELECT DISTINCT e.id, e.nombre, emp.nombre AS empresa_nombre
             FROM empleados e
             LEFT JOIN empresas emp ON emp.id = e.empresa_id
             INNER JOIN empleado_cc ecc ON ecc.empleado_id = e.id
                 AND ecc.tipo IN ('OC','AMBOS') AND ecc.activo = 1
             WHERE e.deleted_at IS NULL AND e.activo = 1
               AND e.id NOT IN (SELECT empleado_id FROM compradores WHERE activo = 1)
             ORDER BY e.nombre"
        )->fetchAll(\PDO::FETCH_ASSOC);

        $this->render('compradores/index', [
            'title'                => 'Compradores',
            'compradores'          => $compradores,
            'empresas'             => $empresas,
            'filters'              => $filters,
            'empleados_disponibles'=> $empleados_disponibles,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // asignar — add employee to compradores (POST)
    // ─────────────────────────────────────────────────────────────────────────

    public function asignar(): void
    {
        requireRole(['admin', 'capturista']);
        verifyCSRF();

        $empleadoId = (int)($_POST['empleado_id'] ?? 0);

        if (!$empleadoId) {
            setFlash('error', 'Selecciona un empleado.');
            redirect('compradores');
        }

        $empleado = $this->empleadoModel->find($empleadoId);
        if (!$empleado) {
            setFlash('error', 'Empleado no encontrado.');
            redirect('compradores');
        }

        $this->model->asignar($empleadoId, ['activo' => 1]);

        logAction('asignar', 'compradores', "Comprador asignado: empleado ID $empleadoId");
        setFlash('success', 'Comprador asignado exitosamente.');
        redirect('compradores');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // quitar — remove from compradores (POST)
    // ─────────────────────────────────────────────────────────────────────────

    public function quitar($id): void
    {
        requireRole(['admin']);
        verifyCSRF();

        $this->model->eliminar((int)$id);

        logAction('quitar', 'compradores', "Comprador removido empleado ID: $id");
        setFlash('success', 'Comprador eliminado.');
        redirect('compradores');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ver — detail view
    // ─────────────────────────────────────────────────────────────────────────

    public function ver($id): void
    {
        $data = $this->model->getWithCentrosCosto((int)$id);
        if (!$data) {
            setFlash('error', 'Comprador no encontrado.');
            redirect('compradores');
        }

        $this->render('compradores/ver', [
            'title' => 'Comprador: ' . htmlspecialchars($data['nombre_completo'] ?? $data['nombre'] ?? ''),
            'comp'  => $data,
        ]);
    }
}
