<?php
$title    = $title    ?? 'Editar Módulo';
$modulo   = $modulo   ?? [];
$flatList = $flatList ?? [];
ob_start();
?>
<div class="mb-4">
  <a href="<?= BASE_URL ?>/modulos-erp" class="btn btn-glass btn-sm">← Módulos ERP</a>
</div>

<div style="max-width:640px;">
  <div class="glass" style="padding:28px 32px;border-radius:var(--radius-lg);">

    <h2 style="font-size:18px;font-weight:700;margin-bottom:4px;">Editar Módulo</h2>
    <p class="text-muted text-sm mb-5">
      ID: <strong><?= (int)($modulo['id'] ?? 0) ?></strong>
      — Clave actual: <code style="font-size:12px;color:#6366f1;background:rgba(99,102,241,.08);padding:2px 8px;border-radius:4px;"><?= htmlspecialchars($modulo['clave'] ?? '') ?></code>
    </p>

    <form method="POST" action="<?= BASE_URL ?>/modulos-erp/actualizar/<?= (int)($modulo['id'] ?? 0) ?>">
      <?= csrfField() ?>

      <div class="form-group">
        <label class="form-label">Nombre *</label>
        <input type="text" name="nombre" class="form-control" required
               value="<?= htmlspecialchars($modulo['nombre'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label class="form-label" style="display:flex;align-items:center;justify-content:space-between;">
          Clave *
          <span style="font-size:11px;color:#f59e0b;font-weight:400;">
            ⚠ Cambiar la clave no afecta a los submódulos — solo es un identificador
          </span>
        </label>
        <input type="text" name="clave" class="form-control"
               style="font-family:monospace;font-size:13px;"
               required value="<?= htmlspecialchars($modulo['clave'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label class="form-label">Módulo padre</label>
        <select name="parent_id" class="form-control">
          <option value="0" <?= empty($modulo['parent_id']) ? 'selected' : '' ?>>— Raíz (sin padre) —</option>
          <?php foreach ($flatList as $m): ?>
          <?php $indent = str_repeat('　', (int)($m['depth'] ?? 0)); ?>
          <option value="<?= (int)$m['id'] ?>"
            <?= ((int)($modulo['parent_id'] ?? 0) === (int)$m['id']) ? 'selected' : '' ?>>
            <?= $indent ?><?= htmlspecialchars($m['nombre']) ?>
            <span style="color:#475569;"> — <?= htmlspecialchars($m['clave']) ?></span>
          </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-row form-row-2">
        <div class="form-group">
          <label class="form-label">Orden</label>
          <input type="number" name="orden" class="form-control" min="0"
                 value="<?= (int)($modulo['orden'] ?? 0) ?>">
        </div>
        <div class="form-group" style="display:flex;flex-direction:column;justify-content:flex-end;">
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding-bottom:4px;">
            <input type="checkbox" name="es_separador" value="1"
                   <?= !empty($modulo['es_separador']) ? 'checked' : '' ?>
                   style="width:15px;height:15px;">
            <span class="form-label" style="margin:0;">Es separador visual</span>
          </label>
        </div>
      </div>

      <div class="form-group">
        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
          <input type="checkbox" name="activo" value="1"
                 <?= !empty($modulo['activo']) ? 'checked' : '' ?>
                 style="width:15px;height:15px;">
          <span class="form-label" style="margin:0;">Módulo activo</span>
        </label>
        <p style="font-size:11px;color:#64748b;margin-top:4px;">
          Los módulos inactivos no aparecen en los checkboxes de Programa Nivel.
        </p>
      </div>

      <?php if (!empty($modulo['hijos_count']) && (int)$modulo['hijos_count'] > 0): ?>
      <div style="padding:12px 16px;background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.2);border-radius:10px;margin-bottom:20px;font-size:13px;color:#fbbf24;">
        Este módulo tiene <strong><?= (int)$modulo['hijos_count'] ?> submódulo(s)</strong>.
        Cambiar el padre o la clave solo afecta a este nodo, no a sus hijos.
      </div>
      <?php endif; ?>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="<?= BASE_URL ?>/modulos-erp" class="btn btn-glass">Cancelar</a>
      </div>
    </form>
  </div>
</div>
<?php
$content = ob_get_clean();
include VIEWS_PATH . 'layouts/app.php';
?>
