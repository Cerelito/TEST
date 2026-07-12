// G1 Construcciones — interactions

document.getElementById('year').textContent = new Date().getFullYear();

const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const finePointer = window.matchMedia('(pointer: fine)').matches;

// ---------- Mobile nav (backdrop + scroll lock + hamburger -> X) ----------
const navToggle = document.getElementById('navToggle');
const navLinks = document.getElementById('navLinks');
const navBackdrop = document.getElementById('navBackdrop');

function closeMobileNav(){
  navLinks.classList.remove('open');
  navToggle.classList.remove('active');
  navBackdrop.classList.remove('open');
  document.body.style.overflow = '';
}
function openMobileNav(){
  navLinks.classList.add('open');
  navToggle.classList.add('active');
  navBackdrop.classList.add('open');
  document.body.style.overflow = 'hidden';
}
navToggle.addEventListener('click', () => {
  navLinks.classList.contains('open') ? closeMobileNav() : openMobileNav();
});
navBackdrop.addEventListener('click', closeMobileNav);
navLinks.querySelectorAll('a').forEach(a => a.addEventListener('click', closeMobileNav));

// ---------- Service tabs (with a soft crossfade instead of an instant swap) ----------
const tabButtons = document.querySelectorAll('.tab-btn');
const tabPanels = document.querySelectorAll('.tab-panel');
tabButtons.forEach(btn => {
  btn.addEventListener('click', () => {
    if (btn.classList.contains('active')) return;
    tabButtons.forEach(b => { b.classList.remove('active'); b.setAttribute('aria-selected', 'false'); });
    btn.classList.add('active');
    btn.setAttribute('aria-selected', 'true');

    const nextPanel = document.getElementById('panel-' + btn.dataset.tab);
    const currentPanel = document.querySelector('.tab-panel.active');

    if (reduceMotion || !currentPanel || currentPanel === nextPanel) {
      tabPanels.forEach(p => p.classList.remove('active'));
      nextPanel.classList.add('active');
      return;
    }

    currentPanel.style.transition = 'opacity .18s var(--ease), transform .18s var(--ease)';
    currentPanel.style.opacity = '0';
    currentPanel.style.transform = 'translateY(8px)';
    setTimeout(() => {
      tabPanels.forEach(p => { p.classList.remove('active'); p.style.opacity = ''; p.style.transform = ''; p.style.transition = ''; });
      nextPanel.classList.add('active');
    }, 180);
  });
});

// ---------- Scroll reveal — content must never be permanently stuck invisible ----------
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
  setTimeout(revealAll, 1500);
} else {
  revealAll();
}

// ---------- Animated counters ----------
const counters = document.querySelectorAll('.counter');
function runCounter(el){
  if (el.dataset.done) return;
  el.dataset.done = '1';
  const target = parseInt(el.dataset.target, 10);
  if (reduceMotion) { el.textContent = target; return; }
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
  setTimeout(() => counters.forEach(runCounter), 1500);
} else {
  counters.forEach(runCounter);
}

// ---------- Nav background + scroll progress bar + hero parallax (single rAF-throttled scroll loop) ----------
const nav = document.querySelector('.nav');
const scrollProgress = document.getElementById('scrollProgress');
const heroImg = document.querySelector('.hero-media img');
const heroEl = document.querySelector('.hero');

let ticking = false;
function onScrollFrame(){
  const scrollY = window.scrollY;
  const docHeight = document.documentElement.scrollHeight - window.innerHeight;

  nav.style.background = scrollY > 40 ? 'rgba(10,11,8,0.55)' : '';

  const progress = docHeight > 0 ? Math.min(scrollY / docHeight, 1) : 0;
  scrollProgress.style.transform = `scaleX(${progress})`;

  if (!reduceMotion && heroImg && heroEl) {
    const heroHeight = heroEl.offsetHeight;
    if (scrollY < heroHeight) {
      heroImg.style.transform = `translateY(${scrollY * 0.12}px)`;
    }
  }
  ticking = false;
}
window.addEventListener('scroll', () => {
  if (!ticking) {
    requestAnimationFrame(onScrollFrame);
    ticking = true;
  }
}, { passive: true });
onScrollFrame();

// ---------- Scrollspy: highlight the nav link for the section in view ----------
const spySections = ['nosotros', 'valores', 'servicios', 'numeros'].map(id => document.getElementById(id)).filter(Boolean);
const spyLinks = document.querySelectorAll('.nav-links a[data-section]');
if ('IntersectionObserver' in window && spySections.length) {
  const spyObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      const id = entry.target.id;
      spyLinks.forEach(link => link.classList.toggle('active', link.dataset.section === id));
    });
  }, { rootMargin: '-45% 0px -50% 0px', threshold: 0 });
  spySections.forEach(sec => spyObserver.observe(sec));
}

// ---------- Subtle 3D tilt on glass cards (fine pointers only, no reduced motion) ----------
if (finePointer && !reduceMotion) {
  document.querySelectorAll('.tilt').forEach(card => {
    let raf = null;
    card.addEventListener('mousemove', (e) => {
      const rect = card.getBoundingClientRect();
      const px = (e.clientX - rect.left) / rect.width - 0.5;
      const py = (e.clientY - rect.top) / rect.height - 0.5;
      card.classList.add('tilting');
      if (raf) cancelAnimationFrame(raf);
      raf = requestAnimationFrame(() => {
        card.style.setProperty('--rx', (-py * 6).toFixed(2) + 'deg');
        card.style.setProperty('--ry', (px * 6).toFixed(2) + 'deg');
      });
    });
    card.addEventListener('mouseleave', () => {
      card.classList.remove('tilting');
      card.style.setProperty('--rx', '0deg');
      card.style.setProperty('--ry', '0deg');
    });
  });
}
