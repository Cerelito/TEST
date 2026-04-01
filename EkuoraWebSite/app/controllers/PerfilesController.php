<?php
// app/controllers/PerfilesController.php

class PerfilesController
{
    private $perfilModel;

    public function __construct()
    {
        requireAuth();
        requirePermiso('perfiles.ver');
        $this->perfilModel = new Perfil();
    }

    public function index()
    {
        $perfiles = $this->perfilModel->getAll();

        require_once VIEWS_PATH . 'perfiles/index.php';
    }

    public function crear()
    {
        requirePermiso('perfiles.crear');

        $modulos = $this->perfilModel->getPermisosAgrupados();

        require_once VIEWS_PATH . 'perfiles/crear.php';
    }

    public function guardar()
    {
        requirePermiso('perfiles.crear');
        verificarCSRF();

        $nombre = trim($_POST['nombre'] ?? '');

        if ($this->perfilModel->existeNombre($nombre)) {
            setFlash('error', 'Ya existe un perfil con ese nombre.');
            redirect('perfiles/crear');
        }

        $datos = [
            'nombre' => $nombre,
            'descripcion' => $_POST['descripcion'] ?? null,
            'permisos' => $_POST['permisos'] ?? []
        ];

        $perfil_id = $this->perfilModel->create($datos);

        if ($perfil_id) {
            setFlash('success', 'Perfil creado exitosamente.');
            redirect('perfiles');
        } else {
            setFlash('error', 'Error al crear el perfil.');
            redirect('perfiles/crear');
        }
    }

    public function editar($id)
    {
        requirePermiso('perfiles.editar');

        $perfil = $this->perfilModel->getById($id);

        if (!$perfil) {
            setFlash('error', 'Perfil no encontrado.');
            redirect('perfiles');
        }

        $permisos_asignados = $this->perfilModel->getPermisosIds($id);
        $permisos_agrupados = $this->perfilModel->getPermisosAgrupados();

        require_once VIEWS_PATH . 'perfiles/editar.php';
    }

    public function actualizar($id)
    {
        requirePermiso('perfiles.editar');
        verificarCSRF();

        $perfil = $this->perfilModel->getById($id);

        if (!$perfil) {
            setFlash('error', 'Perfil no encontrado.');
            redirect('perfiles');
        }

        $nombre = trim($_POST['nombre'] ?? '');

        if ($this->perfilModel->existeNombre($nombre, $id)) {
            setFlash('error', 'Ya existe un perfil con ese nombre.');
            redirect('perfiles/editar/' . $id);
        }

        $datos = [
            'nombre' => $nombre,
            'descripcion' => $_POST['descripcion'] ?? null,
            'permisos' => $_POST['permisos'] ?? []
        ];

        if ($this->perfilModel->update($id, $datos)) {
            setFlash('success', 'Perfil actualizado exitosamente.');
            redirect('perfiles');
        } else {
            setFlash('error', 'Error al actualizar el perfil.');
            redirect('perfiles/editar/' . $id);
        }
    }

    public function eliminar($id)
    {
        requirePermiso('perfiles.eliminar');
        verificarCSRF();

        $perfil = $this->perfilModel->getById($id);

        if (!$perfil) {
            setFlash('error', 'Perfil no encontrado.');
            redirect('perfiles');
        }

        if ($perfil['total_usuarios'] > 0) {
            setFlash('error', 'No se puede eliminar un perfil con usuarios asignados.');
            redirect('perfiles');
        }

        if ($this->perfilModel->delete($id)) {
            setFlash('success', 'Perfil eliminado exitosamente.');
        } else {
            setFlash('error', 'Error al eliminar el perfil.');
        }

        redirect('perfiles');
    }
}
