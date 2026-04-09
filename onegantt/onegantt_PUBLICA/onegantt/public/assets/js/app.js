// OneGantt — JS Vanilla principal

// ── Auto-cierre de flash ──────────────────────────────────
(function() {
  const flash = document.querySelector('.og-flash');
  if (flash) setTimeout(() => flash.remove(), 5000);
})();

// ── Confirmar acciones destructivas ──────────────────────
document.querySelectorAll('[data-confirm]').forEach(el => {
  el.addEventListener('click', function(e) {
    if (!confirm(this.dataset.confirm)) e.preventDefault();
  });
});

// ── Sidebar toggle móvil ─────────────────────────────────
const sb      = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');
const toggle  = document.getElementById('sb-toggle');

if (sb && toggle && overlay) {
  const toggleMenu = () => {
    sb.classList.toggle('og-sidebar--open');
    overlay.classList.toggle('og-overlay--active');
  };

  toggle.addEventListener('click', toggleMenu);
  overlay.addEventListener('click', toggleMenu);

  // Cerrar al hacer clic en un link (en móviles)
  sb.querySelectorAll('.og-nav__item').forEach(link => {
    link.addEventListener('click', () => {
      if (window.innerWidth <= 768) toggleMenu();
    });
  });
}

