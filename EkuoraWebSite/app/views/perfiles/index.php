<?php
$pagina_actual = 'perfiles';
$titulo = 'Perfiles y Permisos';
require_once VIEWS_PATH . 'layouts/header.php';
?>

<div class="d-flex justify-between align-center mb-4">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.25rem;">
            <i class="bi bi-shield-check"></i> Perfiles y Permisos
        </h1>
        <p style="color: var(--text-muted); margin: 0;">
            Gestión de roles y permisos del sistema
        </p>
    </div>
    <?php if (puedeCrear('perfiles')): ?>
        <a href="<?= BASE_URL ?>perfiles/crear" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nuevo Perfil
        </a>
    <?php endif; ?>
</div>

<!-- Lista de Perfiles -->
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 1.5rem;">
    <?php foreach ($perfiles as $perfil): ?>
        <div class="glass-panel">
            <div class="d-flex justify-between align-center mb-3">
                <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin: 0;">
                    <i class="bi bi-award"></i> <?= e($perfil['nombre'] ?? $perfil['Nombre'] ?? 'Sin Nombre') ?>
                </h3>
                <?php if ($perfil['total_usuarios'] > 0): ?>
                    <span class="badge badge-primary" title="Usuarios asignados">
                        <i class="bi bi-people"></i> <?= $perfil['total_usuarios'] ?>
                    </span>
                <?php endif; ?>
            </div>

            <?php if (!empty($perfil['descripcion'])): ?>
                <p style="color: var(--text-muted); font-size: 0.9375rem; margin-bottom: 1rem;">
                    <?= e($perfil['descripcion']) ?>
                </p>
            <?php endif; ?>

            <!-- Permisos del Perfil -->
            <div style="margin-bottom: 1rem;">
                <div
                    style="font-size: 0.8125rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;">
                    Permisos (<?= count($perfil['permisos'] ?? []) ?>)
                </div>

                <?php if (!empty($perfil['permisos'])): ?>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                        <?php
                        // Agrupar permisos por módulo
                        $permisos_agrupados = [];
                        foreach ($perfil['permisos'] as $perm) {
                            $codigo = $perm['codigo'] ?? '';
                            if (strpos($codigo, '.') !== false) {
                                list($modulo, $accion) = explode('.', $codigo, 2);
                                $permisos_agrupados[$modulo][] = $accion;
                            } else {
                                $permisos_agrupados['otros'][] = $codigo;
                            }
                        }
                        ?>

                        <?php foreach ($permisos_agrupados as $modulo => $acciones): ?>
                            <div
                                style="background: var(--bg-secondary); padding: 0.375rem 0.75rem; border-radius: 6px; font-size: 0.8125rem;">
                                <strong style="text-transform: capitalize;"><?= $modulo ?>:</strong>
                                <span style="color: var(--text-muted);"><?= implode(', ', $acciones) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p style="color: var(--text-muted); font-size: 0.875rem; font-style: italic;">
                        Sin permisos asignados
                    </p>
                <?php endif; ?>
            </div>

            <!-- Información Adicional -->
            <div
                style="padding-top: 1rem; border-top: 1px solid var(--border-color); font-size: 0.8125rem; color: var(--text-muted);">
                Creado: <?= formatoFecha($perfil['created_at'] ?? '') ?>
            </div>

            <!-- Acciones -->
            <div class="d-flex gap-2 mt-3">
                <?php if (puedeEditar('perfiles')): ?>
                    <a href="<?= BASE_URL ?>perfiles/editar/<?= $perfil['id'] ?? $perfil['Id'] ?>"
                        class="btn btn-sm btn-primary" style="flex: 1;">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                <?php endif; ?>

                <?php if (puedeEliminar('perfiles') && $perfil['total_usuarios'] == 0 && !in_array($perfil['nombre'] ?? $perfil['Nombre'] ?? '', ['Administrador', 'Supervisor', 'Capturista'])): ?>
                    <button
                        onclick="confirmarEliminacion(<?= $perfil['id'] ?? $perfil['Id'] ?>, '<?= e($perfil['nombre'] ?? $perfil['Nombre'] ?? '') ?>')"
                        class="btn btn-sm btn-danger">
                        <i class="bi bi-trash"></i>
                    </button>
                <?php endif; ?>
            </div>

            <?php if ($perfil['total_usuarios'] > 0): ?>
                <div class="alert alert-info mt-2" style="padding: 0.5rem; font-size: 0.8125rem;">
                    <i class="bi bi-info-circle"></i>
                    No se puede eliminar porque tiene usuarios asignados
                </div>
            <?php elseif (in_array($perfil['nombre'] ?? $perfil['Nombre'] ?? '', ['Administrador', 'Supervisor', 'Capturista'])): ?>
                <div class="alert alert-warning mt-2" style="padding: 0.5rem; font-size: 0.8125rem;">
                    <i class="bi bi-shield-lock"></i>
                    Perfil del sistema protegido
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<!-- Información de Permisos Disponibles -->
<div class="glass-panel mt-4">
    <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem; color: var(--text-primary);">
        <i class="bi bi-key"></i> Permisos Disponibles en el Sistema
    </h3>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
        <?php
        // Obtener todos los permisos y agruparlos
        $todos_permisos = [];
        foreach ($perfiles as $p) {
            foreach ($p['permisos'] as $perm) {
                list($modulo, $accion) = explode('.', $perm['codigo'], 2);
                if (!isset($todos_permisos[$modulo])) {
                    $todos_permisos[$modulo] = [];
                }
                if (!in_array($accion, $todos_permisos[$modulo])) {
                    $todos_permisos[$modulo][] = $accion;
                }
            }
        }
        ksort($todos_permisos);
        ?>

        <?php foreach ($todos_permisos as $modulo => $acciones): ?>
            <div>
                <h4
                    style="font-size: 0.9375rem; font-weight: 600; color: var(--primary); margin-bottom: 0.5rem; text-transform: capitalize;">
                    <i class="bi bi-folder"></i> <?= $modulo ?>
                </h4>
                <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.875rem; color: var(--text-muted);">
                    <?php foreach ($acciones as $accion): ?>
                        <li style="padding: 0.25rem 0;">
                            <i class="bi bi-chevron-right" style="font-size: 0.75rem;"></i> <?= ucfirst($accion) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    function confirmarEliminacion(id, nombre) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '¿Eliminar Perfil?',
                text: `¿Está seguro de eliminar el perfil "${nombre}"? Esta acción no se puede deshacer.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f43f5e',
                cancelButtonColor: '#425563',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                backdrop: `rgba(0,43,73,0.5) blur(4px)`
            }).then((result) => {
                if (result.isConfirmed) {
                    ejecutarEliminacion(id);
                }
            });
        } else {
            if (confirm(`¿Está seguro de eliminar el perfil "${nombre}"?\n\nEsta acción no se puede deshacer.`)) {
                ejecutarEliminacion(id);
            }
        }
    }

    function ejecutarEliminacion(id) {
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
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>