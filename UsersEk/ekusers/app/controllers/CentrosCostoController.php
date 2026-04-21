<?php

class CentrosCostoController extends Controller
{
    private CentroCosto $model;
    private Empleado    $empleadoModel;

    public function __construct()
    {
        requireAuth();
        $this->model         = new CentroCosto();
        $this->empleadoModel = new Empleado();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // index — list centros_costo with optional empresa filter
    // ─────────────────────────────────────────────────────────────────────────

    public function index(): void
    {
        $filters = [
            'empresa_id' => $_GET['empresa_id'] ?? '',
            'buscar'     => $_GET['buscar']     ?? '',
            'activo'     => $_GET['activo']     ?? '',
        ];

        $centros  = $this->model->getAllWithEmpresa(
            array_filter($filters, fn($v) => $v !== '')
        );
        $empresas = $this->empleadoModel->getEmpresasList();

        $this->render('centros-costo/index', [
            'title'    => 'Centros de Costo',
            'centros'  => $centros,
            'empresas' => $empresas,
            'filters'  => $filters,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // guardar — create or update a centro de costo (POST)
    // ─────────────────────────────────────────────────────────────────────────

    public function guardar(): void
    {
        requireRole(['admin', 'capturista']);
        verifyCSRF();

        $id          = (int)($_POST['id']          ?? 0);
        $empresaId   = (int)($_POST['empresa_id']  ?? 0);
        $codigo      = sanitize($_POST['codigo']       ?? '');
        $descripcion = sanitize($_POST['descripcion']  ?? '');
        $activo      = isset($_POST['activo']) ? 1 : 0;

        if (!$empresaId || !$codigo) {
            setFlash('error', 'Empresa y código son obligatorios.');
            redirect('centros-costo');
        }

        if ($id) {
            // Update
            $this->model->update($id, [
                'empresa_id'  => $empresaId,
                'codigo'      => $codigo,
                'descripcion' => $descripcion,
                'activo'      => $activo,
            ]);
            logAction('editar', 'centros_costo', "CC actualizado ID: $id – $codigo");
            setFlash('success', 'Centro de costo actualizado.');
        } else {
            // Create
            $newId = $this->model->create([
                'empresa_id'  => $empresaId,
                'codigo'      => $codigo,
                'descripcion' => $descripcion,
                'activo'      => 1,
            ]);
            logAction('crear', 'centros_costo', "CC creado ID: $newId – $codigo");
            setFlash('success', "Centro de costo <strong>$codigo</strong> creado.");
        }

        redirect('centros-costo');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // eliminar — soft delete a centro de costo (POST)
    // ─────────────────────────────────────────────────────────────────────────

    public function eliminar($id): void
    {
        requireRole(['admin']);
        verifyCSRF();

        $cc = $this->model->find((int)$id);
        if (!$cc) {
            setFlash('error', 'Centro de costo no encontrado.');
            redirect('centros-costo');
        }

        // Soft-delete: set activo = 0
        $this->model->update((int)$id, ['activo' => 0]);

        logAction('eliminar', 'centros_costo', "CC eliminado ID: $id");
        setFlash('success', 'Centro de costo desactivado.');
        redirect('centros-costo');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // AJAX: get CCs by empresa
    // ─────────────────────────────────────────────────────────────────────────

    public function porEmpresa(): void
    {
        $empresaId = (int)($_GET['empresa_id'] ?? 0);
        $ccs = $this->model->getByEmpresa($empresaId);
        $this->json(['ok' => true, 'data' => $ccs]);
    }
}
