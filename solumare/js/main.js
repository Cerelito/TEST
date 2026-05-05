/* ============================================================
   SOLUMARE — Main JavaScript
   Animations · Particles · Counter · Navbar · Form · Filters
   ============================================================ */

'use strict';

/* ── Page Loader ─────────────────────────────────────────────── */
window.addEventListener('load', () => {
    setTimeout(() => {
        const loader = document.getElementById('pageLoader');
        if (loader) loader.classList.add('hidden');
    }, 1200);
});

/* ── Navbar scroll behavior ──────────────────────────────────── */
const navbar = document.getElementById('navbar');
const scrollThreshold = 60;

function updateNavbar() {
    if (!navbar) return;
    if (window.scrollY > scrollThreshold) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
}
window.addEventListener('scroll', updateNavbar, { passive: true });
updateNavbar();

/* ── Mobile nav toggle ───────────────────────────────────────── */
const navToggle = document.getElementById('navToggle');
const navMenu   = document.getElementById('navMenu');

if (navToggle && navMenu) {
    navToggle.addEventListener('click', () => {
        const open = navMenu.classList.toggle('open');
        navToggle.classList.toggle('open', open);
        navToggle.setAttribute('aria-expanded', open);
        document.body.style.overflow = open ? 'hidden' : '';
    });

    navMenu.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
            navMenu.classList.remove('open');
            navToggle.classList.remove('open');
            document.body.style.overflow = '';
        });
    });

    document.addEventListener('click', e => {
        if (!navMenu.contains(e.target) && !navToggle.contains(e.target)) {
            navMenu.classList.remove('open');
            navToggle.classList.remove('open');
            document.body.style.overflow = '';
        }
    });
}

/* ── Intersection Observer — Reveal on scroll ────────────────── */
const revealEls = document.querySelectorAll('.reveal');

if (revealEls.length) {
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    revealEls.forEach(el => revealObserver.observe(el));
}

/* ── Animated counters ───────────────────────────────────────── */
function animateCounter(el, target, duration = 2000) {
    const start    = Date.now();
    const isLarge  = target >= 1000;
    const easeOut  = t => 1 - Math.pow(1 - t, 3);

    function tick() {
        const elapsed  = Date.now() - start;
        const progress = Math.min(elapsed / duration, 1);
        const value    = Math.round(easeOut(progress) * target);

        if (isLarge) {
            el.textContent = (value >= 1000)
                ? (value / 1000).toFixed(1).replace('.0', '') + 'K'
                : value;
        } else {
            el.textContent = value;
        }

        if (progress < 1) requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
}

const counterEls = document.querySelectorAll('.stat-number[data-target]');
if (counterEls.length) {
    const counterObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = parseInt(entry.target.dataset.target, 10);
                animateCounter(entry.target, target);
                counterObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });
    counterEls.forEach(el => counterObserver.observe(el));
}

/* ── Hero particles ──────────────────────────────────────────── */
(function spawnParticles() {
    const container = document.getElementById('heroParticles');
    if (!container) return;

    const colors = ['#38bdf8', '#7dd3fc', '#C9A84C', '#e8c96b', '#06b6d4'];
    const count  = window.innerWidth < 768 ? 20 : 40;

    for (let i = 0; i < count; i++) {
        const p = document.createElement('div');
        p.className = 'particle';
        const size = Math.random() * 4 + 2;
        p.style.cssText = `
            left: ${Math.random() * 100}%;
            top:  ${Math.random() * 100}%;
            width:  ${size}px;
            height: ${size}px;
            background: ${colors[Math.floor(Math.random() * colors.length)]};
            --dur:   ${Math.random() * 8 + 6}s;
            --delay: ${Math.random() * 6}s;
            border-radius: 50%;
            box-shadow: 0 0 ${size * 2}px currentColor;
        `;
        container.appendChild(p);
    }
})();

/* ── Scroll to top ───────────────────────────────────────────── */
const scrollTopBtn = document.getElementById('scrollTop');
if (scrollTopBtn) {
    window.addEventListener('scroll', () => {
        scrollTopBtn.classList.toggle('visible', window.scrollY > 500);
    }, { passive: true });
    scrollTopBtn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

/* ── Properties filter ───────────────────────────────────────── */
const filterBtns = document.querySelectorAll('.filter-btn');
const propGrid   = document.getElementById('propGrid');

if (filterBtns.length && propGrid) {
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const filter = btn.dataset.filter;
            const cards  = propGrid.querySelectorAll('.prop-card');

            cards.forEach(card => {
                const show = filter === 'all' || card.dataset.zone === filter;
                card.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                if (show) {
                    card.style.opacity  = '1';
                    card.style.transform = 'none';
                    card.style.pointerEvents = '';
                } else {
                    card.style.opacity  = '0';
                    card.style.transform = 'scale(0.95)';
                    card.style.pointerEvents = 'none';
                }
            });
        });
    });
}

/* ── Contact form (AJAX) ─────────────────────────────────────── */
const contactForm = document.getElementById('contactForm');
const formMsg     = document.getElementById('formMsg');

if (contactForm && formMsg) {
    contactForm.addEventListener('submit', async e => {
        e.preventDefault();

        const btn = contactForm.querySelector('.form-submit');
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<span>⏳ ' + (document.documentElement.lang === 'en' ? 'Sending...' : 'Enviando...') + '</span>';
        btn.disabled = true;

        const data = new FormData(contactForm);

        try {
            const res = await fetch(contactForm.action, {
                method: 'POST',
                body: data,
            });
            const json = await res.json();

            formMsg.style.display = 'block';
            if (json.success) {
                formMsg.className  = 'form-message success';
                formMsg.textContent = json.message;
                contactForm.reset();
            } else {
                formMsg.className  = 'form-message error';
                formMsg.textContent = json.message;
            }
        } catch {
            formMsg.style.display = 'block';
            formMsg.className  = 'form-message error';
            formMsg.textContent = document.documentElement.lang === 'en'
                ? 'Connection error. Please try again.'
                : 'Error de conexión. Por favor intenta de nuevo.';
        } finally {
            btn.innerHTML = originalHtml;
            btn.disabled  = false;
            setTimeout(() => { formMsg.style.display = 'none'; }, 6000);
        }
    });
}

/* ── Smooth scroll for anchor links ─────────────────────────── */
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', e => {
        const target = document.querySelector(anchor.getAttribute('href'));
        if (target) {
            e.preventDefault();
            const navH = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--nav-h'), 10) || 80;
            const top  = target.getBoundingClientRect().top + window.scrollY - navH - 20;
            window.scrollTo({ top, behavior: 'smooth' });
        }
    });
});

/* ── Parallax hero ───────────────────────────────────────────── */
(function heroParallax() {
    const hero = document.querySelector('.hero');
    if (!hero) return;
    let ticking = false;

    window.addEventListener('scroll', () => {
        if (!ticking) {
            requestAnimationFrame(() => {
                const offset = window.scrollY;
                const bg = hero.querySelector('.hero-bg');
                if (bg) bg.style.transform = `translateY(${offset * 0.25}px)`;
                ticking = false;
            });
            ticking = true;
        }
    }, { passive: true });
})();

/* ── Gallery lightbox ────────────────────────────────────────── */
(function initGallery() {
    const cells = document.querySelectorAll('.gallery-cell');
    if (!cells.length) return;

    const overlay = document.createElement('div');
    overlay.style.cssText = `
        position:fixed;inset:0;z-index:9000;
        background:rgba(12,26,46,0.95);
        display:none;align-items:center;justify-content:center;
        cursor:pointer;backdrop-filter:blur(8px);
    `;
    const inner = document.createElement('div');
    inner.style.cssText = `
        display:flex;flex-direction:column;align-items:center;gap:20px;
        padding:40px;text-align:center;
    `;
    const closeBtn = document.createElement('button');
    closeBtn.innerHTML = '✕';
    closeBtn.style.cssText = `
        position:absolute;top:24px;right:32px;
        background:none;border:none;color:white;font-size:1.6rem;
        cursor:pointer;opacity:0.7;transition:opacity 0.2s;
    `;
    overlay.appendChild(inner);
    overlay.appendChild(closeBtn);
    document.body.appendChild(overlay);

    cells.forEach(cell => {
        cell.addEventListener('click', () => {
            const spans = cell.querySelectorAll('span');
            inner.innerHTML = `
                <div style="font-size:8rem;line-height:1;">${spans[0]?.textContent || '🌊'}</div>
                <div style="font-family:'Playfair Display',serif;font-size:1.5rem;color:white;font-weight:700;">${spans[1]?.textContent || ''}</div>
            `;
            overlay.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
    });

    function closeOverlay() {
        overlay.style.display = 'none';
        document.body.style.overflow = '';
    }
    overlay.addEventListener('click', closeOverlay);
    closeBtn.addEventListener('click', closeOverlay);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeOverlay(); });
})();

/* ── Navbar active link on scroll ───────────────────────────── */
(function trackActiveSection() {
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-link');
    if (!sections.length || !navLinks.length) return;

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                navLinks.forEach(link => {
                    const href = link.getAttribute('href') || '';
                    link.classList.toggle('active', href.includes('#' + entry.target.id));
                });
            }
        });
    }, { rootMargin: '-40% 0px -40% 0px' });

    sections.forEach(s => observer.observe(s));
})();

/* ── Card tilt effect (desktop only) ────────────────────────── */
(function cardTilt() {
    if (window.matchMedia('(hover: none)').matches) return;

    document.querySelectorAll('.prop-card').forEach(card => {
        card.addEventListener('mousemove', e => {
            const rect   = card.getBoundingClientRect();
            const cx     = (e.clientX - rect.left) / rect.width  - 0.5;
            const cy     = (e.clientY - rect.top)  / rect.height - 0.5;
            card.style.transform = `
                translateY(-8px)
                rotateX(${-cy * 4}deg)
                rotateY(${cx * 4}deg)
            `;
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = '';
            card.style.transition = 'transform 0.5s ease';
        });
    });
})();
