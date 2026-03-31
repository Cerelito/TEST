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
                        <th style="min-width: 180px;">Usuario</th>
                        <th style="min-width: 180px;">Nombre</th>
                        <th style="min-width: 200px;">Email</th>
                        <th style="min-width: 130px;">Perfil</th>
                        <th style="min-width: 100px;">Estado</th>
                        <th style="min-width: 140px;">Último Acceso</th>
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

<style>
    /* ==========================================
   GLASSMORPHISM - USUARIOS
   ========================================== */

    :root {
        --glass-primary: #3b82f6;
        --glass-primary-dark: #2563eb;
        --glass-primary-light: rgba(59, 130, 246, 0.15);
        --glass-success: #10b981;
        --glass-warning: #f59e0b;
        --glass-danger: #ef4444;

        --glass-bg: rgba(255, 255, 255, 0.4);
        --glass-bg-card: rgba(255, 255, 255, 0.6);
        --glass-bg-input: rgba(255, 255, 255, 0.7);
        --glass-border: rgba(59, 130, 246, 0.25);

        --glass-text-main: #1e293b;
        --glass-text-muted: #64748b;
        --glass-text-light: #94a3b8;

        --glass-blur: blur(30px);
        --glass-shadow: 0 8px 32px 0 rgba(59, 130, 246, 0.2);
        --glass-shadow-hover: 0 12px 40px 0 rgba(59, 130, 246, 0.3);
        --glass-radius: 16px;
    }

    body.dark-mode {
        --glass-bg: rgba(30, 41, 59, 0.5);
        --glass-bg-card: rgba(30, 41, 59, 0.65);
        --glass-bg-input: rgba(15, 23, 42, 0.55);
        --glass-border: rgba(59, 130, 246, 0.35);

        --glass-text-main: rgba(255, 255, 255, 0.95);
        --glass-text-muted: rgba(255, 255, 255, 0.65);
        --glass-text-light: rgba(255, 255, 255, 0.45);

        --glass-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.5);
        --glass-shadow-hover: 0 12px 40px 0 rgba(0, 0, 0, 0.6);
    }

    /* Layout */
    .usuarios-page {
        padding: 2rem;
        max-width: 1600px;
        margin: 0 auto;
        animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    /* Hero Header */
    .page-hero-usuarios {
        background: var(--glass-bg-card);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        border-radius: var(--glass-radius);
        padding: 2rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--glass-shadow);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 2rem;
    }

    .hero-tag-usuarios {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--glass-bg-input);
        border: 2px solid var(--glass-border);
        padding: 0.4rem 1rem;
        border-radius: 50px;
        color: var(--glass-text-main);
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
    }

    .hero-title-usuarios {
        font-size: 1.875rem;
        font-weight: 800;
        color: var(--glass-text-main);
        margin: 0 0 0.5rem 0;
    }

    .hero-subtitle-usuarios {
        color: var(--glass-text-muted);
        font-size: 1rem;
        margin: 0;
    }

    .hero-actions-usuarios {
        display: flex;
        gap: 0.75rem;
    }

    .btn-hero-action-usuarios {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-hero-action-usuarios.primary {
        background: var(--glass-primary);
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-hero-action-usuarios.primary:hover {
        background: var(--glass-primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        color: white;
    }

    /* Filters */
    .filters-card-usuarios {
        background: var(--glass-bg-card);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        border-radius: var(--glass-radius);
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--glass-shadow);
    }

    .filters-form-usuarios {
        display: flex;
        gap: 1rem;
        align-items: flex-end;
        flex-wrap: wrap;
    }

    .filter-group-usuarios {
        flex: 1;
        min-width: 180px;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .filter-group-usuarios.search {
        flex: 2;
        min-width: 250px;
    }

    .filter-label-usuarios {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--glass-text-main);
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .filter-input-wrapper-usuarios {
        position: relative;
        display: flex;
        align-items: center;
    }

    .filter-input-wrapper-usuarios i {
        position: absolute;
        left: 1rem;
        color: var(--glass-text-muted);
        font-size: 0.9rem;
    }

    .filter-input-usuarios,
    .filter-select-usuarios {
        width: 100%;
        padding: 0.75rem 1rem;
        background: var(--glass-bg-input);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        border-radius: 10px;
        font-size: 0.875rem;
        color: var(--glass-text-main);
        transition: all 0.3s ease;
    }

    .filter-input-usuarios {
        padding-left: 2.75rem;
    }

    .filter-input-usuarios:focus,
    .filter-select-usuarios:focus {
        outline: none;
        border-color: var(--glass-primary);
        background: rgba(255, 255, 255, 0.8);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
    }

    body.dark-mode .filter-input-usuarios:focus,
    body.dark-mode .filter-select-usuarios:focus {
        background: rgba(15, 23, 42, 0.7);
    }

    .filter-actions-usuarios {
        display: flex;
        gap: 0.5rem;
    }

    .btn-filter-usuarios {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        font-size: 1rem;
    }

    .btn-filter-usuarios.primary {
        background: var(--glass-primary);
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-filter-usuarios.primary:hover {
        background: var(--glass-primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
    }

    .btn-filter-usuarios.secondary {
        background: var(--glass-bg-card);
        color: var(--glass-text-main);
        border: 2px solid var(--glass-border);
    }

    .btn-filter-usuarios.secondary:hover {
        background: var(--glass-bg-input);
        border-color: var(--glass-primary);
        transform: translateY(-2px);
    }

    /* Table */
    .table-card-usuarios {
        background: var(--glass-bg-card);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 2px solid var(--glass-border);
        border-radius: var(--glass-radius);
        overflow: hidden;
        box-shadow: var(--glass-shadow);
    }

    .table-wrapper-usuarios {
        overflow-x: auto;
    }

    .table-usuarios {
        width: 100%;
        border-collapse: collapse;
    }

    .table-usuarios thead {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.08) 0%, rgba(139, 92, 246, 0.08) 100%);
    }

    .table-usuarios th {
        padding: 1rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--glass-text-main);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--glass-border);
    }

    .table-actions-header-usuarios {
        text-align: right;
        position: sticky;
        right: 0;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.08) 0%, rgba(139, 92, 246, 0.08) 100%);
        border-left: 2px solid var(--glass-border);
        z-index: 10;
    }

    .table-row-usuarios {
        border-bottom: 1px solid var(--glass-border);
        transition: all 0.2s ease;
    }

    .table-row-usuarios:hover {
        background: rgba(59, 130, 246, 0.05);
    }

    .table-usuarios td {
        padding: 1rem;
        font-size: 0.875rem;
        color: var(--glass-text-main);
    }

    .table-actions-cell-usuarios {
        text-align: right;
        position: sticky;
        right: 0;
        background: var(--glass-bg-card);
        border-left: 2px solid var(--glass-border);
        z-index: 9;
    }

    .table-row-usuarios:hover .table-actions-cell-usuarios {
        background: rgba(59, 130, 246, 0.05);
    }

    /* User Cell */
    .user-cell-usuarios {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .user-avatar-usuarios {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, var(--glass-primary) 0%, var(--glass-primary-dark) 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.875rem;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
    }

    .user-name-usuarios {
        color: var(--glass-text-main);
        font-weight: 500;
    }

    .user-email-usuarios {
        color: var(--glass-text-muted);
        font-size: 0.85rem;
    }

    .perfil-badge-usuarios {
        display: inline-block;
        padding: 0.35rem 0.85rem;
        background: var(--glass-primary-light);
        color: var(--glass-primary);
        border: 1px solid rgba(59, 130, 246, 0.3);
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .status-badge-usuarios {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .status-dot-usuarios {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
            transform: scale(1);
        }

        50% {
            opacity: 0.7;
            transform: scale(0.95);
        }
    }

    .status-badge-usuarios.active {
        background: rgba(16, 185, 129, 0.15);
        color: #047857;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .status-badge-usuarios.active .status-dot-usuarios {
        background: #047857;
        box-shadow: 0 0 8px rgba(4, 120, 87, 0.6);
    }

    .status-badge-usuarios.inactive {
        background: rgba(100, 116, 139, 0.15);
        color: #64748b;
        border: 1px solid rgba(100, 116, 139, 0.3);
    }

    .status-badge-usuarios.inactive .status-dot-usuarios {
        background: #64748b;
    }

    .last-access-usuarios {
        color: var(--glass-text-muted);
        font-size: 0.8rem;
    }

    .last-access-usuarios.never {
        font-style: italic;
    }

    /* Actions */
    .actions-group-usuarios {
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
    }

    .btn-action-usuarios {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        font-size: 0.9rem;
    }

    .btn-action-usuarios.edit {
        background: var(--glass-primary);
        color: white;
    }

    .btn-action-usuarios.edit:hover {
        background: var(--glass-primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }

    .btn-action-usuarios.pause {
        background: var(--glass-warning);
        color: white;
    }

    .btn-action-usuarios.pause:hover {
        background: #d97706;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    }

    .btn-action-usuarios.play {
        background: var(--glass-success);
        color: white;
    }

    .btn-action-usuarios.play:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }

    .btn-action-usuarios.delete {
        background: transparent;
        color: var(--glass-danger);
        border: 2px solid var(--glass-border);
    }

    .btn-action-usuarios.delete:hover {
        background: var(--glass-danger);
        color: white;
        border-color: var(--glass-danger);
        transform: translateY(-2px);
    }

    /* Empty State */
    .empty-state-usuarios {
        text-align: center;
        padding: 5rem 2rem !important;
    }

    .empty-content-usuarios {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }

    .empty-content-usuarios i {
        font-size: 4rem;
        color: var(--glass-text-light);
        opacity: 0.3;
    }

    .empty-content-usuarios p {
        color: var(--glass-text-muted);
        font-size: 1rem;
        margin: 0;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .usuarios-page {
            padding: 1rem;
        }

        .page-hero-usuarios {
            flex-direction: column;
            align-items: flex-start;
        }

        .hero-actions-usuarios {
            width: 100%;
        }

        .btn-hero-action-usuarios {
            flex: 1;
            justify-content: center;
        }

        .filters-form-usuarios {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-group-usuarios,
        .filter-group-usuarios.search {
            width: 100%;
        }

        .filter-actions-usuarios {
            width: 100%;
        }

        .btn-filter-usuarios {
            flex: 1;
        }
    }

    @media (max-width: 640px) {
        .hero-title-usuarios {
            font-size: 1.5rem;
        }

        .actions-group-usuarios {
            flex-wrap: wrap;
        }
    }
</style>

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