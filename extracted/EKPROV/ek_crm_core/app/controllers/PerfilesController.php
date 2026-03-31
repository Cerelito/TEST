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
        // Obtener perfiles (La consulta en el modelo ya evita duplicados)
        $perfiles = $this->perfilModel->getAll();

        // Para el index, necesitamos una lista simple de los permisos asignados (claves)
        // para mostrar los badges en la tarjeta.
        foreach ($perfiles as &$perfil) {
            // Obtenemos los nombres/claves de los permisos para mostrarlos
            $stmt = (new Database())->getConnection()->prepare("
                SELECT p.clave 
                FROM permisos p
                JOIN perfil_permisos pp ON p.id = pp.permiso_id
                WHERE pp.perfil_id = :pid
            ");
            $stmt->execute([':pid' => $perfil['id']]);
            $perfil['permisos'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }

        require_once VIEWS_PATH . 'perfiles/index.php';
    }

    public function crear()
    {
        requirePermiso('perfiles.crear');

        // Obtener TODOS los permisos disponibles agrupados para el formulario
        // Esto asegura que la vista tenga qué iterar
        $permisos_agrupados = $this->perfilModel->getPermisosAgrupados();

        // Array vacío para los permisos seleccionados (porque es nuevo)
        $permisos_asignados = [];

        require_once VIEWS_PATH . 'perfiles/crear.php';
    }

    public function guardar()
    {
        requirePermiso('perfiles.crear');
        verificarCSRF();

        $nombre = trim($_POST['nombre'] ?? '');

        if (empty($nombre)) {
            setFlash('error', 'El nombre es obligatorio.');
            redirect('perfiles/crear');
        }

        if ($this->perfilModel->existeNombre($nombre)) {
            setFlash('error', 'Ya existe un perfil con ese nombre.');
            redirect('perfiles/crear');
        }

        $datos = [
            'nombre' => $nombre,
            'descripcion' => $_POST['descripcion'] ?? null,
            'permisos' => $_POST['permisos'] ?? [] // Array de IDs
        ];

        if ($this->perfilModel->create($datos)) {
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

        // 1. Obtener IDs de permisos asignados (Array simple [1, 5, 8...])
        // Esto sirve para marcar los checkboxes como "checked"
        $permisos_asignados = $this->perfilModel->getPermisos($id);

        // 2. Obtener estructura completa de permisos disponibles
        // Esto sirve para construir la interfaz con todos los módulos
        $permisos_agrupados = $this->perfilModel->getPermisosAgrupados();

        require_once VIEWS_PATH . 'perfiles/editar.php';
    }

    public function actualizar($id)
    {
        requirePermiso('perfiles.editar');
        verificarCSRF();

        $nombre = trim($_POST['nombre'] ?? '');

        if (empty($nombre)) {
            setFlash('error', 'El nombre es obligatorio.');
            redirect('perfiles/editar/' . $id);
        }

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