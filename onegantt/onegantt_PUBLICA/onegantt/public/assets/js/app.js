// OneGantt — JS principal · Apotema Lab

// ══════════════════════════════════════════════════════════
// OGToast — Crystal Apple Toast Notification System
// ══════════════════════════════════════════════════════════
const OGToast = (function () {
  const DURATION = 4800;

  const ICONS = {
    success: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>',
    error:   '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
    warn:    '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
    info:    '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
  };

  const TITLES = { success: 'Éxito', error: 'Error', warn: 'Atención', info: 'Información' };

  function _escape(str) {
    const d = document.createElement('div');
    d.textContent = str;
    return d.innerHTML;
  }

  function show(msg, type) {
    if (!msg) return;
    const validTypes = ['success', 'error', 'warn', 'info'];
    if (!validTypes.includes(type)) type = 'info';

    const container = document.getElementById('og-toasts');
    if (!container) return;

    const el = document.createElement('div');
    el.className = 'og-toast og-toast--' + type;
    el.style.setProperty('--toast-dur', DURATION + 'ms');

    el.innerHTML =
      '<div class="og-toast__icon">' + ICONS[type] + '</div>' +
      '<div class="og-toast__body">' +
        '<div class="og-toast__title">' + TITLES[type] + '</div>' +
        '<div class="og-toast__msg">' + _escape(msg) + '</div>' +
      '</div>' +
      '<button class="og-toast__close" aria-label="Cerrar">' +
        '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>' +
      '</button>' +
      '<div class="og-toast__bar"></div>';

    el.querySelector('.og-toast__close').addEventListener('click', function () { _dismiss(el, true); });

    el._timer = setTimeout(function () { _dismiss(el); }, DURATION);

    el.addEventListener('mouseenter', function () {
      clearTimeout(el._timer);
      var bar = el.querySelector('.og-toast__bar');
      if (bar) bar.style.animationPlayState = 'paused';
    });
    el.addEventListener('mouseleave', function () {
      el._timer = setTimeout(function () { _dismiss(el); }, 1200);
      var bar = el.querySelector('.og-toast__bar');
      if (bar) bar.style.animationPlayState = 'running';
    });

    container.appendChild(el);
    return el;
  }

  function _dismiss(el, immediate) {
    if (el._dismissed) return;
    el._dismissed = true;
    clearTimeout(el._timer);
    el.classList.add('og-toast--out');
    el.addEventListener('animationend', function () { el.remove(); }, { once: true });
  }

  return {
    show:    show,
    success: function (m) { return show(m, 'success'); },
    error:   function (m) { return show(m, 'error'); },
    warn:    function (m) { return show(m, 'warn'); },
    info:    function (m) { return show(m, 'info'); },
  };
})();


// ══════════════════════════════════════════════════════════
// OGConfirm — Crystal Glass Confirmation Modal
// ══════════════════════════════════════════════════════════
const OGConfirm = (function () {

  function show(message, onConfirm) {
    var existing = document.getElementById('og-confirm-overlay');
    if (existing) existing.remove();

    var overlay = document.createElement('div');
    overlay.id = 'og-confirm-overlay';
    overlay.className = 'og-confirm-overlay';
    overlay.innerHTML =
      '<div class="og-confirm" role="dialog" aria-modal="true">' +
        '<div class="og-confirm__icon">' +
          '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
            '<path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>' +
            '<line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>' +
          '</svg>' +
        '</div>' +
        '<p class="og-confirm__msg" id="og-confirm-msg"></p>' +
        '<div class="og-confirm__btns">' +
          '<button class="og-btn og-btn--ghost og-btn--sm" id="og-confirm-cancel">Cancelar</button>' +
          '<button class="og-btn og-btn--sm og-btn--destructive" id="og-confirm-ok">Confirmar</button>' +
        '</div>' +
      '</div>';

    // Set message as text (no XSS)
    overlay.querySelector('#og-confirm-msg').textContent = message;
    document.body.appendChild(overlay);

    requestAnimationFrame(function () {
      overlay.classList.add('og-confirm-overlay--active');
    });

    function close() {
      overlay.classList.remove('og-confirm-overlay--active');
      overlay.addEventListener('transitionend', function () { overlay.remove(); }, { once: true });
      document.removeEventListener('keydown', onKey);
    }

    overlay.querySelector('#og-confirm-cancel').addEventListener('click', close);
    overlay.querySelector('#og-confirm-ok').addEventListener('click', function () {
      close();
      if (typeof onConfirm === 'function') onConfirm();
    });
    overlay.addEventListener('click', function (e) {
      if (e.target === overlay) close();
    });

    function onKey(e) { if (e.key === 'Escape') close(); }
    document.addEventListener('keydown', onKey);

    // Focus the cancel button for accessibility
    setTimeout(function () {
      var btn = overlay.querySelector('#og-confirm-cancel');
      if (btn) btn.focus();
    }, 60);
  }

  return { show: show };
})();


// ══════════════════════════════════════════════════════════
// Boot — DOMContentLoaded
// ══════════════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', function () {

  // ── Flash toast from PHP ──────────────────────────────
  if (window.__ogFlash) {
    var typeMap = { success: 'success', error: 'error', danger: 'error', warning: 'warn', info: 'info' };
    OGToast.show(window.__ogFlash.msg, typeMap[window.__ogFlash.type] || 'info');
  }

  // ── data-confirm on forms → OGConfirm modal ──────────
  document.querySelectorAll('form[data-confirm]').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var msg = this.dataset.confirm;
      var f   = this;
      OGConfirm.show(msg, function () { f.submit(); });
    });
  });

  // ── data-confirm on links ─────────────────────────────
  document.querySelectorAll('a[data-confirm]').forEach(function (link) {
    link.addEventListener('click', function (e) {
      e.preventDefault();
      var msg  = this.dataset.confirm;
      var href = this.href;
      OGConfirm.show(msg, function () { window.location.href = href; });
    });
  });

  // ── Sidebar toggle móvil ─────────────────────────────
  var sb      = document.getElementById('sidebar');
  var overlay = document.getElementById('overlay');
  var toggle  = document.getElementById('sb-toggle');

  if (sb && toggle && overlay) {
    function toggleMenu() {
      sb.classList.toggle('og-sidebar--open');
      overlay.classList.toggle('og-overlay--active');
    }
    toggle.addEventListener('click', toggleMenu);
    overlay.addEventListener('click', toggleMenu);
    sb.querySelectorAll('.og-nav__item').forEach(function (link) {
      link.addEventListener('click', function () {
        if (window.innerWidth <= 768) toggleMenu();
      });
    });
  }

});
