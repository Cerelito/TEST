// G1 Construcciones — interactions

document.getElementById('year').textContent = new Date().getFullYear();

// Mobile nav toggle
const navToggle = document.getElementById('navToggle');
const navLinks = document.getElementById('navLinks');
navToggle.addEventListener('click', () => {
  navLinks.classList.toggle('open');
});
navLinks.querySelectorAll('a').forEach(a => {
  a.addEventListener('click', () => navLinks.classList.remove('open'));
});

// Service tabs
const tabButtons = document.querySelectorAll('.tab-btn');
const tabPanels = document.querySelectorAll('.tab-panel');
tabButtons.forEach(btn => {
  btn.addEventListener('click', () => {
    tabButtons.forEach(b => { b.classList.remove('active'); b.setAttribute('aria-selected', 'false'); });
    tabPanels.forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    btn.setAttribute('aria-selected', 'true');
    document.getElementById('panel-' + btn.dataset.tab).classList.add('active');
  });
});

// Scroll reveal — content must never be permanently stuck invisible
// (deep links to a #section, reduced-motion, or a missed observer tick
// should all still end up showing everything).
const revealEls = document.querySelectorAll('.reveal');
function revealAll(){ revealEls.forEach(el => el.classList.add('in')); }

if ('IntersectionObserver' in window && !location.hash) {
  const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('in');
        revealObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.15, rootMargin: '0px 0px 100px 0px' });
  revealEls.forEach(el => revealObserver.observe(el));
  // Safety net: force-reveal anything left un-triggered after a short delay.
  setTimeout(revealAll, 1500);
} else {
  // No IntersectionObserver support, or the page was opened on a deep
  // link (e.g. shared #contacto URL) — show everything immediately.
  revealAll();
}

// Animated counters
const counters = document.querySelectorAll('.counter');
function runCounter(el){
  if (el.dataset.done) return;
  el.dataset.done = '1';
  const target = parseInt(el.dataset.target, 10);
  const duration = 1400;
  const start = performance.now();
  function tick(now) {
    const progress = Math.min((now - start) / duration, 1);
    const eased = 1 - Math.pow(1 - progress, 3);
    el.textContent = Math.round(eased * target);
    if (progress < 1) requestAnimationFrame(tick);
  }
  requestAnimationFrame(tick);
}
if ('IntersectionObserver' in window) {
  const counterObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      runCounter(entry.target);
      counterObserver.unobserve(entry.target);
    });
  }, { threshold: 0.4 });
  counters.forEach(el => counterObserver.observe(el));
  // Safety net: if a counter is already on-screen at load (deep link) or
  // the observer never fires for any reason, count up anyway.
  setTimeout(() => counters.forEach(runCounter), 1500);
} else {
  counters.forEach(runCounter);
}

// Nav background on scroll
const navWrap = document.getElementById('navWrap');
const nav = document.querySelector('.nav');
window.addEventListener('scroll', () => {
  if (window.scrollY > 40) {
    nav.style.background = 'rgba(10,11,8,0.55)';
  } else {
    nav.style.background = '';
  }
}, { passive: true });
