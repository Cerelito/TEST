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

        $empresas = $this->model->getAll(
            array_filter($filters, fn($v) => $v !== '')
        );

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
        $nombre = sanitize($_POST['nombre'] ?? '');
        $codigo = sanitize($_POST['codigo'] ?? '');
        $activo = isset($_POST['activo']) ? 1 : 0;

        if (!$nombre) {
            setFlash('error', 'El nombre es obligatorio.');
            redirect('empresas');
        }

        if ($this->model->existeNombre($nombre, $id)) {
            setFlash('error', "Ya existe una empresa con el nombre '$nombre'.");
            redirect('empresas');
        }

        if ($id) {
            $this->model->update($id, [
                'nombre' => $nombre,
                'codigo' => $codigo,
                'activo' => $activo,
            ]);
            logAction('editar', 'empresas', "Empresa actualizada ID: $id – $nombre");
            setFlash('success', 'Empresa actualizada.');
        } else {
            $newId = $this->model->create([
                'nombre' => $nombre,
                'codigo' => $codigo,
                'activo' => 1,
            ]);
            logAction('crear', 'empresas', "Empresa creada ID: $newId – $nombre");
            setFlash('success', "Empresa <strong>$nombre</strong> creada.");
        }

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
            setFlash('error', 'No se puede eliminar: la empresa tiene centros de costo asociados.');
            redirect('empresas');
        }

        $this->model->update((int)$id, ['activo' => 0]);
        logAction('eliminar', 'empresas', "Empresa desactivada ID: $id");
        setFlash('success', 'Empresa desactivada.');
        redirect('empresas');
    }

    public function toggleActivo($id): void
    {
        verifyCSRF();
        $pdo = Database::getInstance();
        $pdo->prepare("UPDATE empresas SET activo = IF(activo=1,0,1) WHERE id=?")->execute([(int)$id]);
        logAction('toggle', 'empresas', "Toggle activo empresa ID: $id");
        redirect('empresas');
    }
}
