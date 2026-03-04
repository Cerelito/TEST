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
        if (isset($_GET['estado'])) {
            $filtros['activo'] = $_GET['estado'];
        }
        if (!empty($_GET['busqueda'])) {
            $filtros['buscar'] = $_GET['busqueda'];
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
        verificarCSRF(); // Corrección: verificarCSRF, no verificarToken

        // Validaciones básicas
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $nombre = trim($_POST['nombre'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (empty($username) || empty($email) || empty($nombre)) {
            setFlash('error', 'Todos los campos marcados con * son obligatorios.');
            redirect('usuarios/crear');
        }

        // Validar Contraseña
        if (empty($password)) {
            setFlash('error', 'La contraseña es obligatoria.');
            redirect('usuarios/crear');
        }

        if ($password !== $confirm_password) {
            setFlash('error', 'Las contraseñas no coinciden.');
            redirect('usuarios/crear');
        }

        if (strlen($password) < 8) {
            setFlash('error', 'La contraseña debe tener al menos 8 caracteres.');
            redirect('usuarios/crear');
        }

        // Validar duplicados
        if ($this->userModel->existeUsername($username)) {
            setFlash('error', 'El nombre de usuario ya existe.');
            redirect('usuarios/crear');
        }

        if ($this->userModel->existeEmail($email)) {
            setFlash('error', 'El email ya está registrado.');
            redirect('usuarios/crear');
        }

        // Preparar datos
        $datos = [
            'perfil_id' => $_POST['perfil_id'] ?? null,
            'username' => $username,
            'email' => $email,
            'password' => $password, // El modelo se encarga de hashear
            'nombre' => $nombre,
            'rol' => 'usuario', // Deprecated, se usa perfil_id
            'activo' => isset($_POST['activo']) ? 1 : 0,
            'debe_cambiar_password' => isset($_POST['cambiar_password']) ? 1 : 0
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
        $username = trim($_POST['username'] ?? ''); // Username no se edita generalmente, pero se valida
        $email = trim($_POST['email'] ?? '');
        $nombre = trim($_POST['nombre'] ?? '');

        // Validar si intenta cambiar el email a uno que ya existe
        if ($this->userModel->existeEmail($email, $id)) {
            setFlash('error', 'El email ya está registrado por otro usuario.');
            redirect('usuarios/editar/' . $id);
        }

        $datos = [
            'perfil_id' => $_POST['perfil_id'] ?? null,
            // 'username' => $username, // Generalmente no permitimos cambiar username
            'email' => $email,
            'nombre' => $nombre,
            'activo' => isset($_POST['activo']) ? 1 : 0
        ];

        // Si se proporciona nueva contraseña
        if (!empty($_POST['nueva_password'])) {
            $nueva = $_POST['nueva_password'];
            $confirmar = $_POST['confirmar_password'];

            if ($nueva !== $confirmar) {
                setFlash('error', 'Las nuevas contraseñas no coinciden.');
                redirect('usuarios/editar/' . $id);
            }

            if (strlen($nueva) < 8) {
                setFlash('error', 'La nueva contraseña debe tener al menos 8 caracteres.');
                redirect('usuarios/editar/' . $id);
            }

            $datos['password'] = $nueva;
            $datos['debe_cambiar_password'] = isset($_POST['enviar_email_password']) ? 1 : 0;
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
        requirePermiso('usuarios.activar'); // Asegúrate de tener este permiso o usa 'usuarios.editar'
        verificarCSRF();

        // Evitar desactivarse a uno mismo
        if ($id == usuarioId()) {
            setFlash('error', 'No puede desactivar su propio usuario.');
            redirect('usuarios');
        }

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