<?php
require_once ROOT_PATH . 'views/layouts/icons.php';
$editing = !empty($task);
$depIds  = array_column($deps ?? [], 'id');
ob_start();
?>
<div class="og-page">
  <div class="og-page__header">
    <div>
      <a href="<?= Router::url('tasks') ?>" class="og-back">&larr; Tareas</a>
      <h1 class="og-page__title">
        <?= ogIcon('check-sq', 20) ?>
        <?= $editing ? 'Editar tarea' : 'Nueva tarea' ?>
      </h1>
    </div>
  </div>

  <?php if (!empty($error)): ?>
    <div class="og-alert og-alert--error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <div class="og-two-col og-two-col--70-30">

    <!-- Columna izquierda -->
    <div>
      <div class="og-card">
        <form method="POST" enctype="multipart/form-data" class="og-form" id="task-form">
          <?= $csrfField ?>

          <div class="og-form__group">
            <label for="titulo">T&iacute;tulo <span class="og-req">*</span></label>
            <input type="text" id="titulo" name="titulo" required maxlength="255"
                   placeholder="Describe la tarea brevemente..."
                   value="<?= htmlspecialchars($task['titulo'] ?? $_POST['titulo'] ?? '') ?>">
          </div>

          <div class="og-form__group">
            <label for="descripcion">Descripci&oacute;n</label>
            <textarea id="descripcion" name="descripcion" rows="3"
                      placeholder="Alcance, criterios de aceptaci&oacute;n, notas t&eacute;cnicas..."><?= htmlspecialchars($task['descripcion'] ?? '') ?></textarea>
          </div>

          <div class="og-form__row">
            <div class="og-form__group">
              <label for="proyecto_id">Proyecto <span class="og-req">*</span></label>
              <select id="proyecto_id" name="proyecto_id" required>
                <option value="">— Seleccionar —</option>
                <?php foreach ($proyectos as $p): ?>
                <option value="<?= $p['id'] ?>"
                  <?= ($task['proyecto_id'] ?? $_POST['proyecto_id'] ?? '') == $p['id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($p['nombre']) ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="og-form__group">
              <label for="padre_id">Tarea padre</label>
              <select id="padre_id" name="padre_id">
                <option value="">— Ninguna (tarea ra&iacute;z) —</option>
                <?php foreach ($allTasks as $at):
                  if ($editing && $at['id'] == $task['id']) continue; ?>
                <option value="<?= $at['id'] ?>"
                  <?= ($task['padre_id'] ?? '') == $at['id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($at['titulo']) ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="og-form__row">
            <div class="og-form__group">
              <label for="estatus_id">Estatus</label>
              <select id="estatus_id" name="estatus_id" onchange="updateStatusDot(this)">
                <?php foreach ($statuses as $s): ?>
                <option value="<?= $s['id'] ?>"
                        data-color="<?= htmlspecialchars($s['color']) ?>"
                  <?= ($task['estatus_id'] ?? 1) == $s['id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($s['nombre']) ?>
                </option>
                <?php endforeach; ?>
              </select>
              <div class="og-status-preview">
                <span id="status-dot" class="og-status-dot"
                      style="background:<?php
                        $cur = array_values(array_filter($statuses, fn($s)=>$s['id']==($task['estatus_id']??1)));
                        echo htmlspecialchars($cur[0]['color'] ?? '#888');
                      ?>"></span>
                <span id="status-label" style="font-size:12px;color:var(--text-m)">
                  <?= htmlspecialchars($cur[0]['nombre'] ?? '') ?>
                </span>
              </div>
            </div>

            <div class="og-form__group">
              <label for="prioridad">Prioridad</label>
              <select id="prioridad" name="prioridad">
                <option value="1" <?= ($task['prioridad'] ?? 2) == 1 ? 'selected' : '' ?>>&#x1F7E2; Baja</option>
                <option value="2" <?= ($task['prioridad'] ?? 2) == 2 ? 'selected' : '' ?>>&#x1F7E1; Media</option>
                <option value="3" <?= ($task['prioridad'] ?? 2) == 3 ? 'selected' : '' ?>>&#x1F534; Alta</option>
              </select>
            </div>
          </div>

          <div class="og-form__group">
            <label for="asignado_a">Asignar a</label>
            <select id="asignado_a" name="asignado_a">
              <option value="">— Sin asignar —</option>
              <?php foreach ($usuarios as $u): ?>
              <option value="<?= $u['id'] ?>"
                <?= ($task['asignado_a'] ?? '') == $u['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($u['nombre']) ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="og-form__row">
            <div class="og-form__group">
              <label for="fecha_inicio">Fecha inicio</label>
              <input type="date" id="fecha_inicio" name="fecha_inicio"
                     value="<?= DateHelper::toInput($task['fecha_inicio'] ?? '') ?>">
            </div>
            <div class="og-form__group">
              <label for="fecha_fin">Fecha fin</label>
              <input type="date" id="fecha_fin" name="fecha_fin"
                     value="<?= DateHelper::toInput($task['fecha_fin'] ?? '') ?>">
            </div>
          </div>

          <!-- Dependencias (multi-select) -->
          <div class="og-form__group">
            <label for="depende_de"><?= ogIcon('link', 13) ?> Depende de</label>
            <select id="depende_de" name="depende_de[]" multiple>
              <?php foreach ($allTasks as $at):
                if ($editing && $at['id'] == $task['id']) continue; ?>
              <option value="<?= $at['id'] ?>"
                <?= in_array($at['id'], $depIds) ? 'selected' : '' ?>>
                <?= htmlspecialchars($at['titulo']) ?>
              </option>
              <?php endforeach; ?>
            </select>
            <p class="og-multi-hint">Ctrl+clic / Cmd+clic para selecci&oacute;n m&uacute;ltiple</p>
          </div>

          <?php if ($editing): ?>
          <div class="og-form__group">
            <label for="progreso">Progreso: <span id="prog-label" style="color:var(--accent-2);font-weight:700"><?= $task['progreso'] ?>%</span></label>
            <input type="range" id="progreso" name="progreso" min="0" max="100" step="5"
                   value="<?= $task['progreso'] ?>"
                   oninput="document.getElementById('prog-label').textContent=this.value+'%'">
          </div>
          <?php endif; ?>

          <div class="og-form__group">
            <label><?= ogIcon('clip', 13) ?> Adjuntar archivos (m&aacute;x. 5 MB c/u)</label>
            <input type="file" name="adjuntos[]" multiple
                   accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif,.txt,.csv">
          </div>

          <div class="og-form__actions">
            <a href="<?= Router::url('tasks') ?>" class="og-btn og-btn--ghost">Cancelar</a>
            <button type="submit" class="og-btn">
              <?= $editing ? 'Guardar cambios' : 'Crear tarea' ?>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Columna derecha (solo edición) -->
    <?php if ($editing): ?>
    <div>

      <!-- Chips de dependencias -->
      <?php if (!empty($deps)): ?>
      <div class="og-card og-card--sm">
        <div class="og-card__head">
          <h3 class="og-card__title"><?= ogIcon('link', 14) ?> Depende de</h3>
          <span class="og-badge og-badge--blue"><?= count($deps) ?></span>
        </div>
        <div class="og-dep-chips">
          <?php foreach ($deps as $d): ?>
          <span class="og-dep-chip">
            <?= ogIcon('check-sq', 11) ?>
            <?= htmlspecialchars($d['titulo']) ?>
          </span>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Adjuntos existentes -->
      <?php if (!empty($adjuntos)): ?>
      <div class="og-card og-card--sm">
        <div class="og-card__head">
          <h3 class="og-card__title"><?= ogIcon('clip', 14) ?> Adjuntos</h3>
          <span class="og-badge og-badge--gray"><?= count($adjuntos) ?></span>
        </div>
        <ul class="og-filelist">
          <?php foreach ($adjuntos as $a): ?>
          <li class="og-filelist__item">
            <?= ogIcon('clip', 13) ?>
            <a href="<?= UPLOAD_URL . htmlspecialchars($a['nombre_disco']) ?>" target="_blank">
              <?= htmlspecialchars($a['nombre_orig']) ?>
            </a>
            <small class="og-muted" style="margin-left:auto"><?= round($a['tamano']/1024, 1) ?> KB</small>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>

      <!-- Notas -->
      <div class="og-card og-card--sm">
        <div class="og-card__head">
          <h3 class="og-card__title"><?= ogIcon('file', 14) ?> Notas</h3>
          <?php if (!empty($notas)): ?>
          <span class="og-badge og-badge--blue"><?= count($notas) ?></span>
          <?php endif; ?>
        </div>

        <form method="POST" action="<?= Router::url('tasks/edit', $task['id']) ?>" class="og-note-form">
          <?= $csrfField ?>
          <input type="hidden" name="titulo"      value="<?= htmlspecialchars($task['titulo']) ?>">
          <input type="hidden" name="proyecto_id" value="<?= $task['proyecto_id'] ?>">
          <input type="hidden" name="estatus_id"  value="<?= $task['estatus_id'] ?>">
          <input type="hidden" name="prioridad"   value="<?= $task['prioridad'] ?>">
          <input type="hidden" name="progreso"    value="<?= $task['progreso'] ?>">
          <textarea name="nota_nueva" rows="2" placeholder="Agrega un comentario o nota..."></textarea>
          <button type="submit" class="og-btn og-btn--sm" style="align-self:flex-end">
            <?= ogIcon('plus', 12) ?> Agregar
          </button>
        </form>

        <?php if (!empty($notas)): ?>
        <ul class="og-notes">
          <?php foreach ($notas as $n): ?>
          <li class="og-notes__item">
            <div class="og-notes__head">
              <strong><?= htmlspecialchars($n['autor']) ?></strong>
              <span><?= DateHelper::format($n['created_at'], 'd/m/Y H:i') ?></span>
            </div>
            <div class="og-notes__body"><?= nl2br(htmlspecialchars($n['nota'])) ?></div>
          </li>
          <?php endforeach; ?>
        </ul>
        <?php else: ?>
          <p class="og-empty" style="padding:14px 0">Sin notas a&uacute;n.</p>
        <?php endif; ?>
      </div>

    </div>
    <?php endif; ?>

  </div>
</div>

<script>
const statusColors = {
<?php foreach ($statuses as $s): ?>
  <?= (int)$s['id'] ?>: { color: '<?= addslashes($s['color']) ?>', nombre: '<?= addslashes($s['nombre']) ?>' },
<?php endforeach; ?>
};
function updateStatusDot(sel) {
  const opt = statusColors[parseInt(sel.value)];
  if (!opt) return;
  const dot   = document.getElementById('status-dot');
  const label = document.getElementById('status-label');
  if (dot)   dot.style.background = opt.color;
  if (label) label.textContent    = opt.nombre;
}
(function(){ const s = document.getElementById('estatus_id'); if(s) updateStatusDot(s); })();
</script>
<?php
$content   = ob_get_clean();
$pageTitle = $editing ? 'Editar tarea' : 'Nueva tarea';
include ROOT_PATH . 'views/layouts/main.php';
