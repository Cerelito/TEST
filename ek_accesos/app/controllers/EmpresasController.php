<?php

class EmpresasController extends Controller
{
    private Empresa $model;

    public function __construct()
    {
        requireAuth();
        requireRole(['admin', 'superadmin']);
        $this->model = new Empresa();
    }

    public function index(): void
    {
        $filters = [
            'buscar' => $_GET['buscar'] ?? '',
            'activo' => $_GET['activo'] ?? '',
        ];

        $empresas = $this->model->getAll(array_filter($filters, fn($v) => $v !== ''));

        $this->render('empresas/index', [
            'title'    => 'Empresas',
            'empresas' => $empresas,
            'filters'  => $filters,
        ]);
    }

    public function guardar(): void
    {
        verifyCSRF();

        $id     = (int)($_POST['id']     ?? 0);
        $nombre = trim(sanitize($_POST['nombre'] ?? ''));
        $codigo = trim(sanitize($_POST['codigo'] ?? ''));
        $activo = isset($_POST['activo']) ? 1 : 0;

        if (!$nombre) {
            setFlash('error', 'El nombre de la empresa es obligatorio.');
            redirect('empresas');
        }

        if ($this->model->existeNombre($nombre, $id)) {
            setFlash('error', 'Ya existe una empresa con ese nombre.');
            redirect('empresas');
        }

        $data = compact('nombre', 'codigo', 'activo');

        if ($id) {
            $this->model->update($id, $data);
            logAction('editar', 'empresas', "Empresa actualizada ID: $id – $nombre");
            setFlash('success', "Empresa <strong>$nombre</strong> actualizada.");
        } else {
            $newId = $this->model->create(['nombre' => $nombre, 'codigo' => $codigo, 'activo' => 1]);
            logAction('crear', 'empresas', "Empresa creada ID: $newId – $nombre");
            setFlash('success', "Empresa <strong>$nombre</strong> creada.");
        }

        redirect('empresas');
    }

    public function toggle($id): void
    {
        verifyCSRF();
        $empresa = $this->model->find((int)$id);
        if (!$empresa) {
            setFlash('error', 'Empresa no encontrada.');
            redirect('empresas');
        }
        $this->model->toggleActivo((int)$id);
        $estado = $empresa['activo'] ? 'desactivada' : 'activada';
        setFlash('success', "Empresa {$estado}.");
        redirect('empresas');
    }

    public function eliminar($id): void
    {
        verifyCSRF();
        $empresa = $this->model->find((int)$id);
        if (!$empresa) {
            setFlash('error', 'Empresa no encontrada.');
            redirect('empresas');
        }
        if ($this->model->tieneDependencias((int)$id)) {
            setFlash('error', 'No se puede eliminar: la empresa tiene centros de costo o empleados asociados. Desactívala en su lugar.');
            redirect('empresas');
        }
        $this->model->delete((int)$id);
        logAction('eliminar', 'empresas', "Empresa eliminada ID: $id");
        setFlash('success', 'Empresa eliminada.');
        redirect('empresas');
    }
}
