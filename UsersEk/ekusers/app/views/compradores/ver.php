<?php
$title = 'Comprador';
ob_start();
$comp = $comp ?? [];
$cc   = $comp['centros_costo'] ?? [];

$TIPOS_INSUMO = ['TODOS','MATERIALES','MANO DE OBRA','HERRAMIENTA Y EQUIPO','SUBCONTRATOS',
                 'INDIRECTOS','ADMINISTRATIVOS','TRAMITES Y PROYECTOS','BASICOS','COMERCIAL'];
?>
<div class="mb-4 d-flex gap-2 align-items-center">
  <a href="<?= BASE_URL ?>/compradores" class="btn btn-glass btn-sm">← Compradores</a>
  <a href="<?= BASE_URL ?>/empleados/<?= (int)($comp['empleado_id'] ?? 0) ?>" class="btn btn-glass btn-sm">Ver empleado completo</a>
</div>

<!-- Header -->
<div class="glass" style="padding:20px 24px;border-radius:var(--radius-lg);margin-bottom:20px;display:flex;align-items:center;gap:20px;">
  <div style="width:56px;height:56px;border-radius:14px;background:linear-gradient(135deg,#6366f1,#8b5cf6);
              display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:700;color:#fff;flex-shrink:0;">
    <?= strtoupper(substr($comp['nombre'] ?? 'C', 0, 1)) ?>
  </div>
  <div style="flex:1;">
    <h2 style="font-size:20px;font-weight:800;margin-bottom:2px;"><?= htmlspecialchars($comp['nombre'] ?? '') ?></h2>
    <div style="font-size:13px;color:#64748b;">
      <?= htmlspecialchars($comp['puesto'] ?? '—') ?>
      <?php if (!empty($comp['empresa_nombre'])): ?>
       · <span style="color:#818cf8;"><?= htmlspecialchars($comp['empresa_nombre']) ?></span>
      <?php endif; ?>
    </div>
  </div>
  <div>
    <?php if (!empty($comp['activo'])): ?>
    <span class="badge badge-teal"><span class="dot dot-green"></span> Activo</span>
    <?php else: ?>
    <span class="badge badge-red"><span class="dot dot-red"></span> Inactivo</span>
    <?php endif; ?>
  </div>
</div>

<!-- Centros de Costo con permisos OC -->
<div class="glass" style="border-radius:var(--radius-lg);overflow:hidden;">
  <div style="padding:16px 20px;border-bottom:1px solid rgba(255,255,255,0.06);display:flex;align-items:center;justify-content:space-between;">
    <h3 style="font-size:15px;font-weight:700;margin:0;">Centros de Costo — Órdenes de Compra</h3>
    <span class="badge badge-purple"><?= count($cc) ?> CC</span>
  </div>
  <?php if (empty($cc)): ?>
  <div style="padding:40px 20px;text-align:center;color:#64748b;">
    <p>Sin centros de costo de tipo OC asignados.</p>
  </div>
  <?php else: ?>
  <div class="table-scroll">
    <table>
      <thead>
        <tr>
          <th>Empresa</th>
          <th>Código</th>
          <th>Descripción</th>
          <th>Insumo</th>
          <th style="text-align:center;">Elab</th>
          <th style="text-align:center;">VoBo</th>
          <th style="text-align:center;">Aut</th>
          <th>Monto Máx.</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($cc as $c): ?>
        <?php $tiIdx = (int)($c['tipo_insumo'] ?? 0); ?>
        <tr>
          <td><span class="badge badge-blue"><?= htmlspecialchars($c['empresa_nombre'] ?? '—') ?></span></td>
          <td style="font-family:monospace;font-size:13px;"><?= htmlspecialchars($c['codigo'] ?? '') ?></td>
          <td><?= htmlspecialchars($c['descripcion'] ?? '') ?></td>
          <td>
            <span class="badge" style="background:rgba(139,92,246,0.1);color:#a78bfa;border:1px solid rgba(139,92,246,0.2);">
              <?= htmlspecialchars($TIPOS_INSUMO[$tiIdx] ?? 'TODOS') ?>
            </span>
          </td>
          <td style="text-align:center;">
            <?= !empty($c['elab']) ? '<span style="color:#10b981;font-size:16px;">✓</span>' : '<span style="color:#475569;">—</span>' ?>
          </td>
          <td style="text-align:center;">
            <?= !empty($c['vobo']) ? '<span style="color:#10b981;font-size:16px;">✓</span>' : '<span style="color:#475569;">—</span>' ?>
          </td>
          <td style="text-align:center;">
            <?= !empty($c['aut']) ? '<span style="color:#10b981;font-size:16px;">✓</span>' : '<span style="color:#475569;">—</span>' ?>
          </td>
          <td style="font-family:monospace;">
            <?= (float)($c['monto'] ?? 0) > 0 ? '$' . number_format((float)$c['monto'], 2) : '—' ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
include VIEWS_PATH . 'layouts/app.php';
?>
