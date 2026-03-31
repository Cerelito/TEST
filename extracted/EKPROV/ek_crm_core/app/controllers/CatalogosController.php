<?php
// app/controllers/CatalogosController.php

class CatalogosController
{
    private $catalogoModel;

    public function __construct()
    {
        requireAuth();
        requirePermiso('catalogos.ver');
        $this->catalogoModel = new Catalogo();
    }

    public function index()
    {
        $bancos = $this->catalogoModel->getBancos();
        $cias = $this->catalogoModel->getCias();
        $regimenes = $this->catalogoModel->getRegimenes();
        $estados = $this->catalogoModel->getEstados();

        require_once VIEWS_PATH . 'catalogos/index.php';
    }

    // ===========================================
    // BANCOS
    // ===========================================

    public function crearBanco()
    {
        requirePermiso('catalogos.editar');
        require_once VIEWS_PATH . 'catalogos/crear-banco.php';
    }

    public function guardarBanco()
    {
        requirePermiso('catalogos.editar');
        verificarCSRF();

        $clave = trim($_POST['Clave'] ?? '');
        $nombre = trim($_POST['Nombre'] ?? '');

        // 1. Validar campos vacíos
        if (empty($clave) || empty($nombre)) {
            setFlash('error', 'La clave y el nombre del banco son obligatorios.');
            redirect('catalogos/crearBanco');
        }

        // 2. Validar duplicados
        if ($this->catalogoModel->existeBanco($clave, $nombre)) {
            setFlash('error', 'Ya existe un banco con esa Clave o Nombre.');
            redirect('catalogos/crearBanco');
        }

        $datos = [
            'Clave' => $clave,
            'Nombre' => strtoupper($nombre),
            'Activo' => isset($_POST['Activo']) ? 1 : 0
        ];

        if ($this->catalogoModel->crearBanco($datos)) {
            setFlash('success', 'Banco creado correctamente');
            redirect('catalogos');
        } else {
            setFlash('error', 'Error al crear el banco');
            redirect('catalogos/crearBanco');
        }
    }

    public function editarBanco($id)
    {
        requirePermiso('catalogos.editar');

        $banco = $this->catalogoModel->getBancoById($id);

        if (!$banco) {
            setFlash('error', 'Banco no encontrado');
            redirect('catalogos');
            return;
        }

        require_once VIEWS_PATH . 'catalogos/editar-banco.php';
    }

    public function actualizarBanco($id)
    {
        requirePermiso('catalogos.editar');
        verificarCSRF();

        $clave = trim($_POST['Clave'] ?? '');
        $nombre = trim($_POST['Nombre'] ?? '');

        // 1. Validar vacíos
        if (empty($clave) || empty($nombre)) {
            setFlash('error', 'La clave y el nombre son obligatorios');
            redirect('catalogos/editarBanco/' . $id);
        }

        // 2. Validar duplicados (excluyendo el actual)
        if ($this->catalogoModel->existeBanco($clave, $nombre, $id)) {
            setFlash('error', 'Ya existe otro banco con esa Clave o Nombre.');
            redirect('catalogos/editarBanco/' . $id);
        }

        $datos = [
            'Clave' => $clave,
            'Nombre' => strtoupper($nombre),
            'Activo' => isset($_POST['Activo']) ? 1 : 0
        ];

        if ($this->catalogoModel->actualizarBanco($id, $datos)) {
            setFlash('success', 'Banco actualizado correctamente');
            redirect('catalogos');
        } else {
            setFlash('error', 'Error al actualizar el banco');
            redirect('catalogos/editarBanco/' . $id);
        }
    }

    public function eliminarBanco($id)
    {
        requirePermiso('catalogos.editar');
        verificarCSRF();

        if ($this->catalogoModel->eliminarBanco($id)) {
            setFlash('success', 'Banco eliminado correctamente');
        } else {
            setFlash('error', 'No se puede eliminar el banco (puede estar en uso)');
        }

        redirect('catalogos');
    }

    // ===========================================
    // COMPAÑÍAS
    // ===========================================

    public function crearCia()
    {
        requirePermiso('catalogos.editar');
        require_once VIEWS_PATH . 'catalogos/crear-cia.php';
    }

    public function guardarCia()
    {
        requirePermiso('catalogos.editar');
        verificarCSRF();

        $codigo = trim($_POST['Codigo'] ?? '');
        $nombre = trim($_POST['Nombre'] ?? '');

        // 1. Validar campos vacíos
        if (empty($codigo) || empty($nombre)) {
            setFlash('error', 'El código y nombre de la compañía son obligatorios.');
            redirect('catalogos/crearCia');
        }

        // 2. Validar duplicados
        if ($this->catalogoModel->existeCia($codigo, $nombre)) {
            setFlash('error', 'Ya existe una compañía con ese Código o Nombre.');
            redirect('catalogos/crearCia');
        }

        $datos = [
            'Codigo' => $codigo,
            'Nombre' => strtoupper($nombre),
            'Descripcion' => $_POST['Descripcion'] ?? '',
            'Activo' => isset($_POST['Activo']) ? 1 : 0
        ];

        if ($this->catalogoModel->crearCia($datos)) {
            setFlash('success', 'Compañía creada correctamente');
            redirect('catalogos');
        } else {
            setFlash('error', 'Error al crear la compañía');
            redirect('catalogos/crearCia');
        }
    }

    public function editarCia($id)
    {
        requirePermiso('catalogos.editar');

        $cia = $this->catalogoModel->getCiaById($id);

        if (!$cia) {
            setFlash('error', 'Compañía no encontrada');
            redirect('catalogos');
            return;
        }

        require_once VIEWS_PATH . 'catalogos/editar-cia.php';
    }

    public function actualizarCia($id)
    {
        requirePermiso('catalogos.editar');
        verificarCSRF();

        $codigo = trim($_POST['Codigo'] ?? '');
        $nombre = trim($_POST['Nombre'] ?? '');

        // 1. Validar vacíos
        if (empty($codigo) || empty($nombre)) {
            setFlash('error', 'El código y nombre son obligatorios');
            redirect('catalogos/editarCia/' . $id);
        }

        // 2. Validar duplicados (excluyendo el actual)
        if ($this->catalogoModel->existeCia($codigo, $nombre, $id)) {
            setFlash('error', 'Ya existe otra compañía con ese Código o Nombre.');
            redirect('catalogos/editarCia/' . $id);
        }

        $datos = [
            'Codigo' => $codigo,
            'Nombre' => strtoupper($nombre),
            'Descripcion' => $_POST['Descripcion'] ?? '',
            'Activo' => isset($_POST['Activo']) ? 1 : 0
        ];

        if ($this->catalogoModel->actualizarCia($id, $datos)) {
            setFlash('success', 'Compañía actualizada correctamente');
            redirect('catalogos');
        } else {
            setFlash('error', 'Error al actualizar la compañía');
            redirect('catalogos/editarCia/' . $id);
        }
    }

    public function eliminarCia($id)
    {
        requirePermiso('catalogos.editar');
        verificarCSRF();

        if ($this->catalogoModel->eliminarCia($id)) {
            setFlash('success', 'Compañía eliminada correctamente');
        } else {
            setFlash('error', 'No se puede eliminar la compañía (puede estar en uso)');
        }

        redirect('catalogos');
    }

    // ===========================================
    // REGÍMENES FISCALES
    // ===========================================

    public function crearRegimen()
    {
        requirePermiso('catalogos.editar');
        require_once VIEWS_PATH . 'catalogos/crear-regimen.php';
    }

    public function guardarRegimen()
    {
        requirePermiso('catalogos.editar');
        verificarCSRF();

        $clave = trim($_POST['Clave'] ?? '');
        $descripcion = trim($_POST['Descripcion'] ?? '');

        // 1. Validar campos vacíos
        if (empty($clave) || empty($descripcion)) {
            setFlash('error', 'La clave y descripción del régimen son obligatorias.');
            redirect('catalogos/crearRegimen');
        }

        // 2. Validar duplicados
        if ($this->catalogoModel->existeRegimen($clave)) {
            setFlash('error', 'Ya existe un régimen con esa Clave.');
            redirect('catalogos/crearRegimen');
        }

        $datos = [
            'Clave' => $clave,
            'Descripcion' => strtoupper($descripcion),
            'TipoPersona' => $_POST['TipoPersona'] ?? 'Ambas',
            'Activo' => isset($_POST['Activo']) ? 1 : 0
        ];

        if ($this->catalogoModel->crearRegimen($datos)) {
            setFlash('success', 'Régimen fiscal creado correctamente');
            redirect('catalogos');
        } else {
            setFlash('error', 'Error al crear el régimen fiscal');
            redirect('catalogos/crearRegimen');
        }
    }

    public function editarRegimen($id)
    {
        requirePermiso('catalogos.editar');

        $regimen = $this->catalogoModel->getRegimenById($id);

        if (!$regimen) {
            setFlash('error', 'Régimen fiscal no encontrado');
            redirect('catalogos');
            return;
        }

        require_once VIEWS_PATH . 'catalogos/editar-regimen.php';
    }

    public function actualizarRegimen($id)
    {
        requirePermiso('catalogos.editar');
        verificarCSRF();

        $clave = trim($_POST['Clave'] ?? '');
        $descripcion = trim($_POST['Descripcion'] ?? '');

        // 1. Validar vacíos
        if (empty($clave) || empty($descripcion)) {
            setFlash('error', 'La clave y descripción son obligatorias');
            redirect('catalogos/editarRegimen/' . $id);
        }

        // 2. Validar duplicados (excluyendo el actual)
        if ($this->catalogoModel->existeRegimen($clave, $id)) {
            setFlash('error', 'Ya existe otro régimen con esa Clave.');
            redirect('catalogos/editarRegimen/' . $id);
        }

        $datos = [
            'Clave' => $clave,
            'Descripcion' => strtoupper($descripcion),
            'TipoPersona' => $_POST['TipoPersona'] ?? 'Ambas',
            'Activo' => isset($_POST['Activo']) ? 1 : 0
        ];

        if ($this->catalogoModel->actualizarRegimen($id, $datos)) {
            setFlash('success', 'Régimen fiscal actualizado correctamente');
            redirect('catalogos');
        } else {
            setFlash('error', 'Error al actualizar el régimen fiscal');
            redirect('catalogos/editarRegimen/' . $id);
        }
    }

    public function eliminarRegimen($id)
    {
        requirePermiso('catalogos.editar');
        verificarCSRF();

        if ($this->catalogoModel->eliminarRegimen($id)) {
            setFlash('success', 'Régimen fiscal eliminado correctamente');
        } else {
            setFlash('error', 'No se puede eliminar el régimen fiscal (puede estar en uso)');
        }

        redirect('catalogos');
    }
}