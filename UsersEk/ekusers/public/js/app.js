/* ================================================================
   EK ACCESOS – Main JavaScript
   ================================================================ */

'use strict';

/* ── Sidebar toggle (mobile) ─────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.getElementById('sidebar-toggle');
  const sidebar = document.querySelector('.sidebar');
  if (toggle && sidebar) {
    toggle.addEventListener('click', () => sidebar.classList.toggle('open'));
  }

  // close sidebar on outside click (mobile)
  document.addEventListener('click', (e) => {
    if (window.innerWidth < 900 && sidebar && sidebar.classList.contains('open')) {
      if (!sidebar.contains(e.target) && e.target !== toggle) {
        sidebar.classList.remove('open');
      }
    }
  });

  initFlashDismiss();
  initModals();
  initPermissionTree();
  initSearchFilter();
  initConfirmActions();
});

/* ── Flash messages auto-dismiss ─────────────────────────────── */
function initFlashDismiss() {
  document.querySelectorAll('.alert[data-autodismiss]').forEach(el => {
    const ms = parseInt(el.dataset.autodismiss) || 4000;
    setTimeout(() => {
      el.style.transition = 'opacity 0.5s, transform 0.5s';
      el.style.opacity = '0';
      el.style.transform = 'translateY(-8px)';
      setTimeout(() => el.remove(), 500);
    }, ms);
  });

  document.querySelectorAll('.alert-close').forEach(btn => {
    btn.addEventListener('click', () => {
      const alert = btn.closest('.alert');
      if (alert) alert.remove();
    });
  });
}

/* ── Modal helpers ───────────────────────────────────────────── */
function initModals() {
  // Open modal
  document.querySelectorAll('[data-modal-open]').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.modalOpen;
      openModal(id);
    });
  });

  // Close modal
  document.querySelectorAll('[data-modal-close], .modal-close').forEach(btn => {
    btn.addEventListener('click', () => {
      const backdrop = btn.closest('.modal-backdrop');
      if (backdrop) closeModal(backdrop.id);
    });
  });

  // Close on backdrop click
  document.querySelectorAll('.modal-backdrop').forEach(bd => {
    bd.addEventListener('click', (e) => {
      if (e.target === bd) closeModal(bd.id);
    });
  });
}

function openModal(id) {
  const el = document.getElementById(id);
  if (el) el.classList.add('open');
}
function closeModal(id) {
  const el = document.getElementById(id);
  if (el) el.classList.remove('open');
}
window.openModal = openModal;
window.closeModal = closeModal;

/* ── Permission Tree (Programa Nivel) ───────────────────────── */
function initPermissionTree() {
  // Toggle expand/collapse
  document.querySelectorAll('.tree-toggle').forEach(btn => {
    btn.addEventListener('click', () => {
      const children = btn.closest('.tree-item').querySelector(':scope > .tree-children');
      if (children) {
        const collapsed = children.classList.toggle('collapsed');
        btn.textContent = collapsed ? '▶' : '▼';
        btn.classList.toggle('open', !collapsed);
      }
    });
  });

  // Checkbox propagation
  document.querySelectorAll('.tree-check').forEach(cb => {
    cb.addEventListener('change', () => {
      const item = cb.closest('.tree-item');
      // Cascade down
      item.querySelectorAll('.tree-check').forEach(c => {
        c.checked = cb.checked;
        c.indeterminate = false;
      });
      // Update ancestors
      updateAncestors(item);
    });
  });
}

function updateAncestors(item) {
  let parent = item.parentElement?.closest('.tree-item');
  while (parent) {
    const cb = parent.querySelector(':scope > .tree-label > .tree-check');
    const childCbs = [...parent.querySelectorAll('.tree-check')].filter(c => c !== cb);
    if (cb && childCbs.length) {
      const allChecked  = childCbs.every(c => c.checked);
      const noneChecked = childCbs.every(c => !c.checked && !c.indeterminate);
      cb.indeterminate = !allChecked && !noneChecked;
      cb.checked = allChecked;
    }
    parent = parent.parentElement?.closest('.tree-item');
  }
}

/* Select all / deselect all for a module */
window.treeSelectAll = function(moduleKey, checked) {
  const container = document.querySelector(`[data-module="${moduleKey}"]`);
  if (!container) return;
  container.querySelectorAll('.tree-check').forEach(cb => {
    cb.checked = checked;
    cb.indeterminate = false;
  });
};

/* ── Search/filter table ─────────────────────────────────────── */
function initSearchFilter() {
  document.querySelectorAll('[data-search-table]').forEach(input => {
    const tableId = input.dataset.searchTable;
    const table = document.getElementById(tableId);
    if (!table) return;
    input.addEventListener('input', () => {
      const q = input.value.toLowerCase();
      table.querySelectorAll('tbody tr').forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(q) ? '' : 'none';
      });
    });
  });
}

/* ── Confirm delete actions ──────────────────────────────────── */
function initConfirmActions() {
  document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', (e) => {
      const msg = el.dataset.confirm || '¿Está seguro?';
      if (!confirm(msg)) e.preventDefault();
    });
  });
}

/* ── Org chart dynamic loading ───────────────────────────────── */
window.loadOrganigrama = function(empresaId) {
  const container = document.getElementById('org-container');
  if (!container) return;
  container.innerHTML = '<div class="empty-state"><div class="spinner"></div></div>';
  fetch(`/ek_accesos/organigrama/data?empresa_id=${empresaId}`)
    .then(r => r.json())
    .then(data => {
      if (data.ok) renderOrgNode(container, data.root);
      else container.innerHTML = `<div class="empty-state"><p>${data.error}</p></div>`;
    })
    .catch(() => {
      container.innerHTML = '<div class="empty-state"><p class="text-muted">Error al cargar organigrama.</p></div>';
    });
};

function renderOrgNode(container, node) {
  if (!node) { container.innerHTML = '<div class="empty-state"><div class="empty-icon">🏢</div><p class="text-muted">Sin datos de organigrama</p></div>'; return; }
  container.innerHTML = buildOrgHTML([node]);
}

function buildOrgHTML(nodes) {
  if (!nodes || !nodes.length) return '';
  const items = nodes.map(n => {
    const initials = n.nombre.split(' ').slice(0,2).map(w => w[0]).join('').toUpperCase();
    const childrenHtml = n.subordinados?.length ? `
      <div class="org-children-wrap" style="display:flex;gap:24px;justify-content:center;margin-top:20px;position:relative;">
        ${n.subordinados.map(c => buildOrgNodeHTML(c)).join('')}
      </div>` : '';
    return `<div class="org-node">${buildOrgCardHTML(n, initials)}${childrenHtml}</div>`;
  }).join('');
  return `<div style="display:flex;justify-content:center;">${items}</div>`;
}

function buildOrgCardHTML(n, initials) {
  return `
    <div style="display:flex;flex-direction:column;align-items:center;">
      <div class="glass org-card glow-blue" style="min-width:160px;">
        <div class="org-avatar">${initials}</div>
        <div class="org-name">${n.nombre}</div>
        <div class="org-role">${n.puesto || ''}</div>
        ${n.programa_nivel ? `<div class="badge badge-blue mt-1" style="font-size:10px;">${n.programa_nivel}</div>` : ''}
      </div>
    </div>`;
}

function buildOrgNodeHTML(n) {
  const initials = n.nombre.split(' ').slice(0,2).map(w => w[0]).join('').toUpperCase();
  const childrenHtml = n.subordinados?.length
    ? `<div style="display:flex;gap:16px;justify-content:center;margin-top:16px;">${n.subordinados.map(c => buildOrgNodeHTML(c)).join('')}</div>`
    : '';
  return `
    <div class="org-node">
      <div class="org-connector"></div>
      ${buildOrgCardHTML(n, initials)}
      ${childrenHtml}
    </div>`;
}

/* ── Format money ────────────────────────────────────────────── */
window.fmtMoney = function(val) {
  return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(val);
};

/* ── AJAX helper ─────────────────────────────────────────────── */
window.apiPost = async function(url, data) {
  const res = await fetch(url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    body: JSON.stringify(data)
  });
  return res.json();
};
