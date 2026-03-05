<?php
$pagina_actual = 'usuarios';
$titulo = 'Usuarios';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<div class="usuarios-page">

    <!-- Hero Header -->
    <div class="page-hero-usuarios">
        <div class="hero-info-usuarios">
            <div class="hero-tag-usuarios">
                <i class="bi bi-people"></i>
                <span>Gestión de Accesos</span>
            </div>
            <h1 class="hero-title-usuarios">Usuarios</h1>
            <p class="hero-subtitle-usuarios">Gestión de usuarios del sistema</p>
        </div>
        <?php if (puedeCrear('usuarios')): ?>
            <div class="hero-actions-usuarios">
                <a href="<?= BASE_URL ?>usuarios/crear" class="btn-hero-action-usuarios primary">
                    <i class="bi bi-person-plus"></i>
                    <span>Nuevo Usuario</span>
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Filters -->
    <div class="filters-card-usuarios">
        <form method="GET" action="<?= BASE_URL ?>usuarios" class="filters-form-usuarios">
            <div class="filter-group-usuarios search">
                <label class="filter-label-usuarios">Búsqueda</label>
                <div class="filter-input-wrapper-usuarios">
                    <i class="bi bi-search"></i>
                    <input type="text" name="busqueda" class="filter-input-usuarios"
                        placeholder="Nombre, usuario, email..." value="<?= e($_GET['busqueda'] ?? '') ?>">
                </div>
            </div>

            <div class="filter-group-usuarios">
                <label class="filter-label-usuarios">Perfil</label>
                <select name="perfil_id" class="filter-select-usuarios">
                    <option value="">Todos los perfiles</option>
                    <?php foreach ($perfiles as $perfil): ?>
                        <option value="<?= $perfil['id'] ?>" <?= ($_GET['perfil_id'] ?? '') == $perfil['id'] ? 'selected' : '' ?>>
                            <?= e($perfil['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group-usuarios">
                <label class="filter-label-usuarios">Estado</label>
                <select name="estado" class="filter-select-usuarios">
                    <option value="">Todos</option>
                    <option value="1" <?= ($_GET['estado'] ?? '') === '1' ? 'selected' : '' ?>>Activos</option>
                    <option value="0" <?= ($_GET['estado'] ?? '') === '0' ? 'selected' : '' ?>>Inactivos</option>
                </select>
            </div>

            <div class="filter-actions-usuarios">
                <button type="submit" class="btn-filter-usuarios primary">
                    <i class="bi bi-search"></i>
                </button>
                <a href="<?= BASE_URL ?>usuarios" class="btn-filter-usuarios secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="table-card-usuarios">
        <div class="table-wrapper-usuarios">
            <table class="table-usuarios">
                <thead>
                    <tr>
                        <th class="col-mw-180">Usuario</th>
                        <th class="col-mw-180">Nombre</th>
                        <th class="col-mw-200">Email</th>
                        <th class="col-mw-130">Perfil</th>
                        <th class="col-mw-100">Estado</th>
                        <th class="col-mw-140">Último Acceso</th>
                        <th class="table-actions-header-usuarios">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($usuarios)): ?>
                        <?php foreach ($usuarios as $user): ?>
                            <tr class="table-row-usuarios">
                                <td>
                                    <div class="user-cell-usuarios">
                                        <div class="user-avatar-usuarios">
                                            <?= strtoupper(substr($user['nombre'], 0, 1)) ?>
                                        </div>
                                        <strong>
                                            <?= e($user['username']) ?>
                                        </strong>
                                    </div>
                                </td>
                                <td>
                                    <span class="user-name-usuarios">
                                        <?= e($user['nombre']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="user-email-usuarios">
                                        <?= e($user['email']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="perfil-badge-usuarios">
                                        <?= e($user['perfil_nombre']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($user['activo']): ?>
                                        <span class="status-badge-usuarios active">
                                            <span class="status-dot-usuarios"></span>
                                            <span>Activo</span>
                                        </span>
                                    <?php else: ?>
                                        <span class="status-badge-usuarios inactive">
                                            <span class="status-dot-usuarios"></span>
                                            <span>Inactivo</span>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($user['ultimo_acceso']): ?>
                                        <span class="last-access-usuarios">
                                            <?= formatoFechaHora($user['ultimo_acceso']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="last-access-usuarios never">Nunca</span>
                                    <?php endif; ?>
                                </td>
                                <td class="table-actions-cell-usuarios">
                                    <div class="actions-group-usuarios">
                                        <?php if (puedeEditar('usuarios')): ?>
                                            <a href="<?= BASE_URL ?>usuarios/editar/<?= $user['id'] ?>"
                                                class="btn-action-usuarios edit" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        <?php endif; ?>

                                        <?php if (puedeEditar('usuarios') && $user['id'] != usuarioId()): ?>
                                            <button
                                                onclick="toggleEstado(<?= $user['id'] ?>, '<?= e($user['nombre']) ?>', <?= $user['activo'] ? 'true' : 'false' ?>)"
                                                class="btn-action-usuarios <?= $user['activo'] ? 'pause' : 'play' ?>"
                                                title="<?= $user['activo'] ? 'Desactivar' : 'Activar' ?>">
                                                <i class="bi bi-<?= $user['activo'] ? 'pause' : 'play' ?>-circle"></i>
                                            </button>
                                        <?php endif; ?>

                                        <?php if (puedeEliminar('usuarios') && $user['id'] != usuarioId()): ?>
                                            <button onclick="confirmarEliminacion(<?= $user['id'] ?>, '<?= e($user['nombre']) ?>')"
                                                class="btn-action-usuarios delete" title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="empty-state-usuarios">
                                <div class="empty-content-usuarios">
                                    <i class="bi bi-inbox"></i>
                                    <p>No se encontraron usuarios</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    async function toggleEstado(id, nombre, activo) {
        const accion = activo ? 'desactivar' : 'activar';
        const titulo = activo ? '¿Desactivar Usuario?' : '¿Activar Usuario?';
        const mensaje = activo
            ? `El usuario "${nombre}" no podrá iniciar sesión hasta que sea reactivado`
            : `El usuario "${nombre}" podrá iniciar sesión nuevamente`;

        const confirmed = await confirmDialog(titulo, mensaje, 'Sí, ' + accion, 'Cancelar');

        if (confirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= BASE_URL ?>usuarios/toggleEstado/' + id;

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = 'csrf_token';
            csrfInput.value = '<?= generarToken() ?>';

            form.appendChild(csrfInput);
            document.body.appendChild(form);
            form.submit();
        }
    }

    async function confirmarEliminacion(id, nombre) {
        const confirmed = await confirmDelete(`Usuario: ${nombre}`, 'Esta acción no se puede deshacer');

        if (confirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= BASE_URL ?>usuarios/eliminar/' + id;

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = 'csrf_token';
            csrfInput.value = '<?= generarToken() ?>';

            form.appendChild(csrfInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>