<?php
// app/helpers/auth.php - Autenticación y autorización

/**
 * Verificar si el usuario está autenticado
 */
function estaAutenticado() {
    iniciarSesion();
    return isset($_SESSION['usuario']) && isset($_SESSION['usuario']['id']);
}

/**
 * Obtener usuario actual
 */
function usuarioActual() {
    iniciarSesion();
    return $_SESSION['usuario'] ?? null;
}

/**
 * Obtener ID del usuario actual
 */
function usuarioId() {
    $usuario = usuarioActual();
    return $usuario['id'] ?? null;
}

/**
 * Verificar si es administrador
 */
function esAdmin() {
    $usuario = usuarioActual();
    return isset($usuario['rol']) && $usuario['rol'] === 'admin';
}

/**
 * Refrescar los permisos del usuario desde la BD si su perfil cambió.
 * Se llama en requirePermiso() para que cambios de perfil surtan efecto sin
 * obligar al usuario a cerrar sesión.
 */
function refrescarPermisosUsuario() {
    iniciarSesion();
    $usuario = usuarioActual();
    if (!$usuario) return;

    // Usar marca de tiempo para no consultar la BD en cada request; refrescar cada 5 minutos.
    $ahora = time();
    if (isset($_SESSION['permisos_refreshed_at']) && ($ahora - $_SESSION['permisos_refreshed_at']) < 300) {
        return;
    }

    try {
        $database = new Database();
        $db = $database->getConnection();
        // Verificar si el usuario sigue activo y obtener su perfil_id actual
        $stmt = $db->prepare("SELECT activo, perfil_id, rol FROM usuarios WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $usuario['id']]);
        $row = $stmt->fetch();

        if (!$row || !$row['activo']) {
            // Usuario desactivado: forzar logout
            logoutUsuario();
            setFlash('error', 'Su cuenta ha sido desactivada. Contacte al administrador.');
            redirect('login');
        }

        // Recargar permisos del perfil actual desde la BD
        $userModel = new User();
        $permisos = $userModel->getPermisos($usuario['id']);

        $_SESSION['usuario']['permisos']   = $permisos;
        $_SESSION['usuario']['perfil_id']  = $row['perfil_id'];
        $_SESSION['usuario']['rol']        = $row['rol'];
        $_SESSION['permisos_refreshed_at'] = $ahora;
    } catch (Exception $e) {
        // Si falla la BD, mantener los permisos en sesión (fail-open intencional para UX)
    }
}

/**
 * Verificar si tiene un permiso específico
 */
function tienePermiso($permiso) {
    if (esAdmin()) {
        return true; // Admin tiene todos los permisos
    }

    $usuario = usuarioActual();
    if (!isset($usuario['permisos'])) {
        return false;
    }

    return in_array($permiso, $usuario['permisos']);
}

/**
 * Verificar si puede ver un módulo
 */
function puedeVer($modulo) {
    return tienePermiso($modulo . '.ver');
}

/**
 * Verificar si puede crear en un módulo
 */
function puedeCrear($modulo) {
    return tienePermiso($modulo . '.crear');
}

/**
 * Verificar si puede editar en un módulo
 */
function puedeEditar($modulo) {
    return tienePermiso($modulo . '.editar');
}

/**
 * Verificar si puede eliminar en un módulo
 */
function puedeEliminar($modulo) {
    return tienePermiso($modulo . '.eliminar');
}

/**
 * Requerir autenticación (middleware)
 */
function requireAuth() {
    if (!estaAutenticado()) {
        setFlash('error', 'Debe iniciar sesión para acceder a esta página.');
        redirect('login');
    }
}

/**
 * Requerir rol de administrador
 */
function requireAdmin() {
    requireAuth();

    if (!esAdmin()) {
        setFlash('error', 'No tiene permisos para acceder a esta sección.');
        redirect('dashboard');
    }
}

/**
 * Requerir permiso específico
 */
function requirePermiso($permiso) {
    requireAuth();
    refrescarPermisosUsuario();

    if (!tienePermiso($permiso)) {
        setFlash('error', 'No tiene permisos para realizar esta acción.');
        redirect('dashboard');
    }
}

/**
 * Login de usuario
 */
function loginUsuario($usuario) {
    iniciarSesion();
    regenerarSesion();

    $_SESSION['usuario'] = [
        'id' => $usuario['id'],
        'username' => $usuario['username'],
        'email' => $usuario['email'],
        'nombre' => $usuario['nombre'],
        'rol' => $usuario['rol'],
        'perfil_id' => $usuario['perfil_id'],
        'perfil_nombre' => $usuario['perfil_nombre'] ?? null,
        'permisos' => $usuario['permisos'] ?? [],
        'debe_cambiar_password' => $usuario['debe_cambiar_password'] ?? 0
    ];

    $_SESSION['usuario_login_time'] = time();

    // Actualizar último acceso
    try {
        $database = new Database();
        $db = $database->getConnection();
        $stmt = $db->prepare("UPDATE usuarios SET ultimo_acceso = NOW(), intentos_fallidos = 0 WHERE id = :id");
        $stmt->execute([':id' => $usuario['id']]);
    } catch (Exception $e) {
        // Log error
    }

    logSeguridad('login_exitoso', "Usuario {$usuario['username']} inició sesión", $usuario['id'], 'info');
}

/**
 * Logout de usuario
 */
function logoutUsuario() {
    $usuario = usuarioActual();

    if ($usuario) {
        logSeguridad('logout', "Usuario {$usuario['username']} cerró sesión", $usuario['id'], 'info');
    }

    destruirSesion();
}

/**
 * Verificar si la sesión ha expirado
 */
function verificarExpiracionSesion() {
    iniciarSesion();

    if (isset($_SESSION['usuario_login_time'])) {
        $tiempo_transcurrido = time() - $_SESSION['usuario_login_time'];
        $tiempo_maximo = SESSION_LIFETIME * 60; // Convertir minutos a segundos

        if ($tiempo_transcurrido > $tiempo_maximo) {
            logoutUsuario();
            setFlash('warning', 'Su sesión ha expirado. Por favor, inicie sesión nuevamente.');
            redirect('login');
        }

        // Actualizar tiempo de actividad
        $_SESSION['usuario_login_time'] = time();
    }
}
