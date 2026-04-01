<?php
// app/controllers/UsuariosController.php

class UsuariosController
{
    private $userModel;
    private $perfilModel;

    public function __construct()
    {
        requireAuth();
        $this->userModel = new User();
        $this->perfilModel = new Perfil();
    }

    public function index()
    {
        requirePermiso('usuarios.ver');

        $filtros = [];
        if (!empty($_GET['perfil_id'])) {
            $filtros['perfil_id'] = $_GET['perfil_id'];
        }
        if (isset($_GET['activo'])) {
            $filtros['activo'] = $_GET['activo'];
        }
        if (!empty($_GET['buscar'])) {
            $filtros['buscar'] = $_GET['buscar'];
        }

        $usuarios = $this->userModel->getAll($filtros);
        $perfiles = $this->perfilModel->getAll();

        require_once VIEWS_PATH . 'usuarios/index.php';
    }

    public function crear()
    {
        requirePermiso('usuarios.crear');

        $perfiles = $this->perfilModel->getAll();

        require_once VIEWS_PATH . 'usuarios/crear.php';
    }

    public function guardar()
    {
        requirePermiso('usuarios.crear');
        verificarCSRF();

        // Validaciones
        $nombre = trim($_POST['nombre'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $perfil_id = !empty($_POST['perfil_id']) ? $_POST['perfil_id'] : null;

        if (empty($nombre) || empty($username) || empty($email) || empty($password) || empty($perfil_id)) {
            setFlash('error', 'Por favor complete todos los campos obligatorios.');
            redirect('usuarios/crear');
        }

        if ($this->userModel->existeUsername($username)) {
            setFlash('error', 'El nombre de usuario ya existe.');
            redirect('usuarios/crear');
        }

        if ($this->userModel->existeEmail($email)) {
            setFlash('error', 'El email ya está registrado.');
            redirect('usuarios/crear');
        }

        $datos = [
            'perfil_id' => $perfil_id,
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'nombre' => $nombre,
            'apellido' => !empty($_POST['apellido']) ? trim($_POST['apellido']) : null,
            'telefono' => !empty($_POST['telefono']) ? trim($_POST['telefono']) : null,
            'rol' => $_POST['rol'] ?? 'usuario',
            'debe_cambiar_password' => isset($_POST['debe_cambiar_password']) ? 1 : 0,
            'activo' => isset($_POST['activo']) ? 1 : 0
        ];

        $usuario_id = $this->userModel->create($datos);

        if ($usuario_id) {
            setFlash('success', 'Usuario creado exitosamente.');
            redirect('usuarios');
        } else {
            setFlash('error', 'Error al crear el usuario.');
            redirect('usuarios/crear');
        }
    }

    public function editar($id)
    {
        requirePermiso('usuarios.editar');

        $usuario = $this->userModel->getById($id);

        if (!$usuario) {
            setFlash('error', 'Usuario no encontrado.');
            redirect('usuarios');
        }

        $perfiles = $this->perfilModel->getAll();

        require_once VIEWS_PATH . 'usuarios/editar.php';
    }

    public function actualizar($id)
    {
        requirePermiso('usuarios.editar');
        verificarCSRF();

        $usuario = $this->userModel->getById($id);

        if (!$usuario) {
            setFlash('error', 'Usuario no encontrado.');
            redirect('usuarios');
        }

        // Validaciones
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if ($this->userModel->existeUsername($username, $id)) {
            setFlash('error', 'El nombre de usuario ya existe.');
            redirect('usuarios/editar/' . $id);
        }

        if ($this->userModel->existeEmail($email, $id)) {
            setFlash('error', 'El email ya está registrado.');
            redirect('usuarios/editar/' . $id);
        }

        $datos = [
            'perfil_id' => !empty($_POST['perfil_id']) ? $_POST['perfil_id'] : null,
            'username' => $username,
            'email' => $email,
            'nombre' => trim($_POST['nombre'] ?? ''),
            'apellido' => !empty($_POST['apellido']) ? trim($_POST['apellido']) : null,
            'telefono' => !empty($_POST['telefono']) ? trim($_POST['telefono']) : null,
            'rol' => $_POST['rol'] ?? 'usuario',
            'activo' => isset($_POST['activo']) ? 1 : 0
        ];

        // Si se proporciona nueva contraseña
        if (!empty($_POST['nueva_password'])) {
            $datos['password'] = $_POST['nueva_password'];
        }

        if ($this->userModel->update($id, $datos)) {
            setFlash('success', 'Usuario actualizado exitosamente.');
            redirect('usuarios');
        } else {
            setFlash('error', 'Error al actualizar el usuario.');
            redirect('usuarios/editar/' . $id);
        }
    }

    public function toggleEstado($id)
    {
        requirePermiso('usuarios.activar');
        verificarCSRF();

        if ($this->userModel->toggleEstado($id)) {
            setFlash('success', 'Estado del usuario actualizado.');
        } else {
            setFlash('error', 'Error al actualizar el estado.');
        }

        redirect('usuarios');
    }

    public function eliminar($id)
    {
        requirePermiso('usuarios.eliminar');
        verificarCSRF();

        // No permitir eliminar al usuario actual
        if ($id == usuarioId()) {
            setFlash('error', 'No puede eliminar su propio usuario.');
            redirect('usuarios');
        }

        if ($this->userModel->delete($id)) {
            setFlash('success', 'Usuario eliminado exitosamente.');
        } else {
            setFlash('error', 'Error al eliminar el usuario.');
        }

        redirect('usuarios');
    }
}
