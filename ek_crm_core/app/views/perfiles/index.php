<?php
$pagina_actual = 'perfiles';
$titulo = 'Perfiles y Permisos';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<div class="d-flex justify-between align-center mb-4">
    <div>
        <h1 class="section-title">
            <i class="bi bi-shield-check"></i> Perfiles y Permisos
        </h1>
        <p class="section-subtitle">
            Gestión de roles y permisos del sistema
        </p>
    </div>
    <?php if (puedeCrear('perfiles')): ?>
        <a href="<?= BASE_URL ?>perfiles/crear" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nuevo Perfil
        </a>
    <?php endif; ?>
</div>

<div class="grid-container mb-5">
    <?php foreach ($perfiles as $perfil): ?>
        <div class="glass-panel">
            <div class="d-flex justify-between align-center mb-3">
                <h3 class="perfil-card-title">
                    <i class="bi bi-award"></i> <?= e($perfil['nombre']) ?>
                </h3>
                <?php if (isset($perfil['total_usuarios']) && $perfil['total_usuarios'] > 0): ?>
                    <span class="badge badge-primary" title="Usuarios asignados">
                        <i class="bi bi-people"></i> <?= $perfil['total_usuarios'] ?>
                    </span>
                <?php endif; ?>
            </div>

            <?php if (!empty($perfil['descripcion'])): ?>
                <p class="perfil-description">
                    <?= e($perfil['descripcion']) ?>
                </p>
            <?php endif; ?>

            <div class="perfil-permissions-header">
                <div class="perfil-permissions-label">
                    Permisos (<?= isset($perfil['permisos']) ? count($perfil['permisos']) : 0 ?>)
                </div>

                <?php if (!empty($perfil['permisos'])): ?>
                    <div class="perfil-permissions-list">
                        <?php
                        // Agrupar permisos por módulo
                        $permisos_agrupados = [];
                        foreach ($perfil['permisos'] as $perm_clave) {
                            if (strpos($perm_clave, '.') !== false) {
                                list($modulo, $accion) = explode('.', $perm_clave, 2);
                                $permisos_agrupados[$modulo][] = $accion;
                            } else {
                                $permisos_agrupados['otros'][] = $perm_clave;
                            }
                        }
                        ?>

                        <?php foreach ($permisos_agrupados as $modulo => $acciones): ?>
                            <div class="perfil-permission-tag">
                                <strong class="perfil-module-name"><?= $modulo ?>:</strong>
                                <span class="perfil-actions-text"><?= implode(', ', $acciones) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="perfil-no-perms">
                        Sin permisos asignados
                    </p>
                <?php endif; ?>
            </div>

            <div class="perfil-card-footer">
                Creado: <?= formatoFecha($perfil['created_at']) ?>
            </div>

            <div class="d-flex gap-2 mt-3">
                <?php if (puedeEditar('perfiles')): ?>
                    <a href="<?= BASE_URL ?>perfiles/editar/<?= $perfil['id'] ?>" class="btn btn-sm btn-primary"
                        class="flex-1">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                <?php endif; ?>

                <?php if (puedeEliminar('perfiles') && ($perfil['total_usuarios'] ?? 0) == 0 && !in_array($perfil['nombre'], ['Administrador', 'Supervisor', 'Capturista'])): ?>
                    <button onclick="confirmarEliminacion(<?= $perfil['id'] ?>, '<?= e($perfil['nombre']) ?>')"
                        class="btn btn-sm btn-danger">
                        <i class="bi bi-trash"></i>
                    </button>
                <?php endif; ?>
            </div>

            <?php if (($perfil['total_usuarios'] ?? 0) > 0): ?>
                <div class="alert alert-info mt-2 mb-0 alert-compact">
                    <i class="bi bi-info-circle"></i>
                    Tiene usuarios asignados
                </div>
            <?php elseif (in_array($perfil['nombre'] ?? '', ['Administrador', 'Supervisor', 'Capturista'])): ?>
                <div class="alert alert-warning mt-2 mb-0 alert-compact">
                    <i class="bi bi-shield-lock"></i>
                    Perfil protegido
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<div class="glass-panel mt-4">
    <h3 class="card-title">
        <i class="bi bi-key"></i> Permisos Disponibles en el Sistema
    </h3>

    <div class="grid-container grid-auto-fill-220">
        <?php
        // Obtener todos los permisos y agruparlos
        $todos_permisos = [];
        foreach ($perfiles as $p) {
            if (!empty($p['permisos'])) {
                foreach ($p['permisos'] as $perm_clave) {
                    if (strpos($perm_clave, '.') !== false) {
                        list($modulo, $accion) = explode('.', $perm_clave, 2);
                        if (!isset($todos_permisos[$modulo])) {
                            $todos_permisos[$modulo] = [];
                        }
                        if (!in_array($accion, $todos_permisos[$modulo])) {
                            $todos_permisos[$modulo][] = $accion;
                        }
                    }
                }
            }
        }
        ksort($todos_permisos);
        ?>

        <?php foreach ($todos_permisos as $modulo => $acciones): ?>
            <div>
                <h4 class="perfil-permissions-module-title">
                    <i class="bi bi-folder"></i> <?= $modulo ?>
                </h4>
                <ul class="perfil-permissions-module-list">
                    <?php foreach ($acciones as $accion): ?>
                        <li class="perfil-permissions-module-item">
                            <i class="bi bi-chevron-right perfil-permissions-module-icon"></i>
                            <?= ucfirst($accion) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    async function confirmarEliminacion(id, nombre) {
        const confirmed = await confirmDelete(
            `Perfil: ${nombre}`,
            'Esta acción no se puede deshacer'
        );

        if (confirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= BASE_URL ?>perfiles/eliminar/' + id;

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