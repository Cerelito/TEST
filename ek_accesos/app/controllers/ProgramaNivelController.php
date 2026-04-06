<?php

class ProgramaNivelController extends Controller
{
    private ProgramaNivel $model;

    public function __construct()
    {
        requireAuth();
        requireRole(['admin']);
        $this->model = new ProgramaNivel();
    }

    public function index()
    {
        $programas = $this->model->getAll();
        $this->render('programa-nivel/index', [
            'title'    => 'Programa Nivel',
            'programas'=> $programas,
        ]);
    }

    public function crear()
    {
        $arbol = $this->model->getArbolModulos();
        $this->render('programa-nivel/crear', [
            'title' => 'Nuevo Programa Nivel',
            'arbol' => $arbol,
        ]);
    }

    public function guardar()
    {
        verifyCSRF();

        $nombre      = sanitize($_POST['nombre'] ?? '');
        $descripcion = sanitize($_POST['descripcion'] ?? '');

        if (!$nombre) {
            setFlash('error', 'El nombre es requerido.');
            redirect('programa-nivel/crear');
        }

        $id = $this->model->create([
            'nombre'      => $nombre,
            'descripcion' => $descripcion,
            'activo'      => 1,
        ]);

        // Save permissions
        $permisos = $_POST['permisos'] ?? [];
        $this->model->savePermisos($id, $permisos);

        logAction('crear', 'programa_nivel', "Programa nivel creado: $nombre (ID: $id)");
        setFlash('success', "Programa nivel <strong>$nombre</strong> creado.");
        redirect('programa-nivel/editar/' . $id);
    }

    public function editar($id)
    {
        $programa = $this->model->find((int)$id);
        if (!$programa) {
            setFlash('error', 'Programa nivel no encontrado.');
            redirect('programa-nivel');
        }

        $arbol     = $this->model->getArbolModulos((int)$id);
        $permisos  = $this->model->getPermisosFlat((int)$id);

        $this->render('programa-nivel/editar', [
            'title'    => 'Editar: ' . $programa['nombre'],
            'programa' => $programa,
            'arbol'    => $arbol,
            'permisos' => $permisos,
        ]);
    }

    public function actualizar($id)
    {
        verifyCSRF();
        $programa = $this->model->find((int)$id);
        if (!$programa) {
            setFlash('error', 'Programa nivel no encontrado.');
            redirect('programa-nivel');
        }

        $nombre      = sanitize($_POST['nombre'] ?? '');
        $descripcion = sanitize($_POST['descripcion'] ?? '');

        if (!$nombre) {
            setFlash('error', 'El nombre es requerido.');
            redirect('programa-nivel/editar/' . $id);
        }

        $this->model->update((int)$id, [
            'nombre'      => $nombre,
            'descripcion' => $descripcion,
            'activo'      => isset($_POST['activo']) ? 1 : 0,
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        // Save permissions
        $permisos = $_POST['permisos'] ?? [];
        $this->model->savePermisos((int)$id, $permisos);

        logAction('editar', 'programa_nivel', "Programa nivel actualizado: $nombre (ID: $id)");
        setFlash('success', 'Programa nivel actualizado.');
        redirect('programa-nivel/editar/' . $id);
    }

    public function eliminar($id)
    {
        verifyCSRF();
        $this->model->delete((int)$id);
        logAction('eliminar', 'programa_nivel', "Programa nivel eliminado ID: $id");
        setFlash('success', 'Programa nivel eliminado.');
        redirect('programa-nivel');
    }

    // AJAX: get permissions for a programa nivel (for comparison)
    public function apiPermisos($id)
    {
        $permisos = $this->model->getPermisosFlat((int)$id);
        $this->json(['ok' => true, 'data' => $permisos]);
    }
}
