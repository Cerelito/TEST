// OneGantt — JS principal · Apotema Lab

// ══════════════════════════════════════════════════════════
// OGToast — Crystal Apple Toast Notification System
// ══════════════════════════════════════════════════════════
const OGToast = (function () {
  const DURATION = 4800; // ms before auto-dismiss

  const ICONS = {
    success: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>',
    error:   '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
    warn:    '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
    info:    '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
  };

  const TITLES = {
    success: 'Éxito',
    error:   'Error',
    warn:    'Atención',
    info:    'Información',
  };

  function _container() {
    return document.getElementById('og-toasts');
  }

  function _dismiss(el, immediate) {
    if (el._dismissed) return;
    el._dismissed = true;

    const run = () => {
      el.classList.add('og-toast--out');
      el.addEventListener('animationend', () => el.remove(), { once: true });
    };

    if (immediate) run();
    else setTimeout(run, 0);
  }

  function show(msg, type) {
    if (!msg) return;

    // Normalize type
    const validTypes = ['success', 'error', 'warn', 'info'];
    if (!validTypes.includes(type)) type = 'info';

    const container = _container();
    if (!container) return;

    const el = document.createElement('div');
    el.className = `og-toast og-toast--${type}`;
    el.style.setProperty('--toast-dur', DURATION + 'ms');

    el.innerHTML = `
      <div class="og-toast__icon">${ICONS[type]}</div>
      <div class="og-toast__body">
        <div class="og-toast__title">${TITLES[type]}</div>
        <div class="og-toast__msg">${_escape(msg)}</div>
      </div>
      <button class="og-toast__close" aria-label="Cerrar">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
      <div class="og-toast__bar"></div>
    `;

    // Close button
    el.querySelector('.og-toast__close').addEventListener('click', () => _dismiss(el, true));

    // Auto-dismiss
    el._timer = setTimeout(() => _dismiss(el), DURATION);

    // Pause on hover
    el.addEventListener('mouseenter', () => {
      clearTimeout(el._timer);
      const bar = el.querySelector('.og-toast__bar');
      if (bar) bar.style.animationPlayState = 'paused';
    });
    el.addEventListener('mouseleave', () => {
      el._timer = setTimeout(() => _dismiss(el), 1200);
      const bar = el.querySelector('.og-toast__bar');
      if (bar) bar.style.animationPlayState = 'running';
    });

    container.appendChild(el);
    return el;
  }

  function _escape(str) {
    const d = document.createElement('div');
    d.textContent = str;
    return d.innerHTML;
  }

  // Convenience shorthands
  return {
    show,
    success: (msg) => show(msg, 'success'),
    error:   (msg) => show(msg, 'error'),
    warn:    (msg) => show(msg, 'warn'),
    info:    (msg) => show(msg, 'info'),
  };
})();


// ══════════════════════════════════════════════════════════
// Boot — run on DOMContentLoaded
// ══════════════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', function () {

  // ── Fire flash toast from PHP ─────────────────────────
  if (window.__ogFlash) {
    const { msg, type } = window.__ogFlash;
    // Map PHP flash types to toast types (success→success, error→error, etc.)
    const typeMap = { success: 'success', error: 'error', danger: 'error', warning: 'warn', info: 'info' };
    OGToast.show(msg, typeMap[type] || 'info');
  }

  // ── Confirmar acciones destructivas ──────────────────
  document.querySelectorAll('[data-confirm]').forEach(function (el) {
    el.addEventListener('click', function (e) {
      if (!confirm(this.dataset.confirm)) e.preventDefault();
    });
  });

  // ── Sidebar toggle móvil ─────────────────────────────
  const sb      = document.getElementById('sidebar');
  const overlay = document.getElementById('overlay');
  const toggle  = document.getElementById('sb-toggle');

  if (sb && toggle && overlay) {
    const toggleMenu = function () {
      sb.classList.toggle('og-sidebar--open');
      overlay.classList.toggle('og-overlay--active');
    };

    toggle.addEventListener('click', toggleMenu);
    overlay.addEventListener('click', toggleMenu);

    sb.querySelectorAll('.og-nav__item').forEach(function (link) {
      link.addEventListener('click', function () {
        if (window.innerWidth <= 768) toggleMenu();
      });
    });
  }

});
