<?php
require_once ROOT_PATH . 'views/layouts/icons.php';
ob_start();
?>
<div class="og-page">
  <div class="og-page__header">
    <h1 class="og-page__title">Diagrama Gantt</h1>
    <form method="GET" action="<?= Router::url('tasks/gantt') ?>" class="og-filters" style="margin:0">
      <input type="hidden" name="route" value="tasks/gantt">
      <select name="proyecto_id" onchange="this.form.submit()">
        <option value="">— Seleccionar proyecto —</option>
        <?php foreach ($proyectos as $p): ?>
        <option value="<?= $p['id'] ?>" <?= ($_GET['proyecto_id'] ?? '') == $p['id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($p['nombre']) ?>
        </option>
        <?php endforeach; ?>
      </select>
    </form>
  </div>

  <?php if (empty($ganttTasks)): ?>
    <div class="og-card" style="text-align:center;padding:3rem">
      <p class="og-empty">Selecciona un proyecto para ver el diagrama Gantt.</p>
    </div>
  <?php else: ?>

  <!-- Leyenda de prioridades -->
  <div class="og-gantt-legend">
    <span><span class="og-gantt-dot" style="background:#10B981"></span>Completado</span>
    <span><span class="og-gantt-dot" style="background:#3B82F6"></span>En progreso</span>
    <span><span class="og-gantt-dot" style="background:#F59E0B"></span>Pendiente</span>
    <span><span class="og-gantt-dot" style="background:#EF4444"></span>Vencida</span>
  </div>

  <div class="og-card" style="padding:0;overflow:auto">
    <div id="gantt-container"></div>
  </div>

  <script>
  const TASKS = <?= json_encode($ganttTasks, JSON_HEX_TAG) ?>;

  (function() {
    const today = new Date();
    today.setHours(0,0,0,0);

    // Rango de fechas
    let minDate = new Date(today);
    let maxDate = new Date(today);
    TASKS.forEach(t => {
      if (t.fecha_inicio) { const d = new Date(t.fecha_inicio); if (d < minDate) minDate = d; }
      if (t.fecha_fin)    { const d = new Date(t.fecha_fin);    if (d > maxDate) maxDate = d; }
    });
    minDate.setDate(minDate.getDate() - 2);
    maxDate.setDate(maxDate.getDate() + 5);

    const totalDays = Math.round((maxDate - minDate) / 86400000) + 1;
    const DAY_W    = 32; // px por día
    const ROW_H    = 42;
    const LABEL_W  = 260;
    const HEAD_H   = 56;

    const container = document.getElementById('gantt-container');
    container.style.position   = 'relative';
    container.style.minWidth   = (LABEL_W + totalDays * DAY_W) + 'px';
    container.style.minHeight  = (HEAD_H + TASKS.length * ROW_H + 16) + 'px';
    container.style.fontFamily = 'system-ui,sans-serif';
    container.style.fontSize   = '13px';

    // ── Encabezado de fechas ──────────────────────────
    const head = document.createElement('div');
    head.style.cssText = `position:sticky;top:0;z-index:10;background:#fff;border-bottom:1px solid #e5e7eb;display:flex;height:${HEAD_H}px;`;

    // Etiqueta fija izquierda
    const labelHead = document.createElement('div');
    labelHead.style.cssText = `width:${LABEL_W}px;min-width:${LABEL_W}px;padding:0 16px;display:flex;align-items:center;font-weight:600;color:#374151;border-right:1px solid #e5e7eb;position:sticky;left:0;background:#fff;z-index:2;`;
    labelHead.textContent = 'Tarea';
    head.appendChild(labelHead);

    // Celdas de días
    const daysHead = document.createElement('div');
    daysHead.style.cssText = `display:flex;flex:1;`;
    const months = {};
    for (let i = 0; i < totalDays; i++) {
      const d = new Date(minDate);
      d.setDate(minDate.getDate() + i);
      const isToday = d.toDateString() === today.toDateString();
      const isWeekend = d.getDay() === 0 || d.getDay() === 6;
      const mk = d.getFullYear() + '-' + d.getMonth();
      if (!months[mk]) months[mk] = { label: d.toLocaleDateString('es', {month:'short', year:'numeric'}), start: i };

      const cell = document.createElement('div');
      cell.style.cssText = `width:${DAY_W}px;min-width:${DAY_W}px;text-align:center;padding-top:28px;font-size:11px;color:${isToday?'#5563DE':'#9CA3AF'};background:${isToday?'#EEF2FF':isWeekend?'#F9FAFB':'transparent'};border-right:1px solid #F3F4F6;font-weight:${isToday?'700':'400'};`;
      cell.textContent = d.getDate();
      daysHead.appendChild(cell);
    }
    head.appendChild(daysHead);
    container.appendChild(head);

    // Meses encima (overlay)
    Object.values(months).forEach(m => {
      const ml = document.createElement('div');
      ml.style.cssText = `position:absolute;top:0;left:${LABEL_W + m.start * DAY_W}px;height:26px;display:flex;align-items:center;padding:0 8px;font-size:11px;font-weight:600;color:#6B7280;pointer-events:none;`;
      ml.textContent = m.label;
      container.appendChild(ml);
    });

    // Línea de hoy
    const todayOffset = Math.round((today - minDate) / 86400000);
    const todayLine = document.createElement('div');
    todayLine.style.cssText = `position:absolute;top:${HEAD_H}px;left:${LABEL_W + todayOffset * DAY_W + DAY_W/2}px;width:2px;height:${TASKS.length * ROW_H}px;background:#5563DE;opacity:.5;pointer-events:none;z-index:5;`;
    container.appendChild(todayLine);

    // ── Filas de tareas ───────────────────────────────
    TASKS.forEach((t, idx) => {
      const row = document.createElement('div');
      row.style.cssText = `display:flex;height:${ROW_H}px;border-bottom:1px solid #F3F4F6;align-items:center;`;
      row.onmouseenter = () => row.style.background = '#F9FAFB';
      row.onmouseleave = () => row.style.background = '';

      // Etiqueta
      const label = document.createElement('div');
      const indent = t.padre_id ? '28px' : '8px';
      label.style.cssText = `width:${LABEL_W}px;min-width:${LABEL_W}px;padding:0 12px 0 ${indent};display:flex;align-items:center;gap:6px;overflow:hidden;border-right:1px solid #e5e7eb;position:sticky;left:0;background:inherit;z-index:1;`;

      const dot = document.createElement('span');
      dot.style.cssText = `width:8px;height:8px;border-radius:50%;flex-shrink:0;background:${t.estatus_color||'#888'}`;
      label.appendChild(dot);

      const title = document.createElement('a');
      title.href = '<?= BASE_URL ?>/tasks/edit/' + t.id;
      title.style.cssText = `text-decoration:none;color:#111827;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-size:13px;`;
      title.title = t.titulo;
      title.textContent = (t.padre_id ? '↳ ' : '') + t.titulo;
      label.appendChild(title);
      row.appendChild(label);

      // Zona de barras
      const zone = document.createElement('div');
      zone.style.cssText = `flex:1;position:relative;height:100%;`;

      // Fondo de fin de semana
      for (let i = 0; i < totalDays; i++) {
        const d = new Date(minDate);
        d.setDate(minDate.getDate() + i);
        if (d.getDay() === 0 || d.getDay() === 6) {
          const wk = document.createElement('div');
          wk.style.cssText = `position:absolute;top:0;left:${i*DAY_W}px;width:${DAY_W}px;height:100%;background:#F9FAFB;`;
          zone.appendChild(wk);
        }
      }

      // Barra de tarea
      if (t.fecha_inicio && t.fecha_fin) {
        const s = new Date(t.fecha_inicio + 'T00:00:00');
        const e = new Date(t.fecha_fin   + 'T00:00:00');
        const startOff = Math.round((s - minDate) / 86400000);
        const dur      = Math.round((e - s) / 86400000) + 1;
        const isOver   = e < today && t.estatus !== 'Completado' && t.estatus !== 'Cancelado';

        const bar = document.createElement('a');
        bar.href = '<?= BASE_URL ?>/tasks/edit/' + t.id;
        bar.title = `${t.titulo}\n${t.fecha_inicio} → ${t.fecha_fin}\nProgreso: ${t.progreso}%`;
        bar.style.cssText = `
          position:absolute;
          top:8px;height:26px;
          left:${startOff * DAY_W + 2}px;
          width:${Math.max(dur * DAY_W - 4, 20)}px;
          background:${isOver ? '#FEE2E2' : (t.estatus_color || '#5563DE') + '22'};
          border:1.5px solid ${isOver ? '#EF4444' : t.estatus_color || '#5563DE'};
          border-radius:6px;
          display:flex;align-items:center;overflow:hidden;
          text-decoration:none;
        `;

        // Progreso dentro de la barra
        const prog = document.createElement('div');
        prog.style.cssText = `height:100%;width:${t.progreso}%;background:${isOver ? '#EF4444' : t.estatus_color || '#5563DE'};opacity:.3;border-radius:4px 0 0 4px;`;
        bar.appendChild(prog);

        // Label dentro de la barra
        if (dur >= 3) {
          const bl = document.createElement('span');
          bl.style.cssText = `position:absolute;left:6px;font-size:11px;color:#1F2937;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:${Math.max(dur*DAY_W-16,0)}px;`;
          bl.textContent = t.titulo;
          bar.appendChild(bl);
        }

        zone.appendChild(bar);
      }

      row.appendChild(zone);
      container.appendChild(row);
    });

  })();
  </script>

  <?php endif; ?>
</div>
<?php
$content   = ob_get_clean();
$pageTitle = 'Gantt';
include ROOT_PATH . 'views/layouts/main.php';
