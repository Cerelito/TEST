    document.addEventListener('DOMContentLoaded', () => {
      /* ── Año ── */
      const yr = document.getElementById('yr');
      if (yr) yr.textContent = new Date().getFullYear();

      /* ── MOBILE MENU ── */
      const hambtn = document.getElementById('hambtn');
      const mm = document.getElementById('mobileMenu');
      const toggleMenu = () => {
        const open = mm.classList.toggle('open');
        hambtn.classList.toggle('open');
        hambtn.setAttribute('aria-expanded', open);
        document.documentElement.classList.toggle('menu-open', open);
        document.body.classList.toggle('menu-open', open);
      };
      hambtn.addEventListener('click', toggleMenu);
      mm.querySelectorAll('a').forEach(a => a.addEventListener('click', () => { if (mm.classList.contains('open')) toggleMenu(); }));

      /* ── NAV SCROLL (Nunca desaparece, cambia de verde a crema) ── */
      const mainNav = document.getElementById('mainNav');
      const navGlass = document.querySelector('.nav-glass');
      const logoImg = document.getElementById('nav-logo-img');

      let lastScroll = 0;
      function updateNav() {
        const scrollY = window.pageYOffset || document.documentElement.scrollTop;
        if (Math.abs(scrollY - lastScroll) < 5) return;
        lastScroll = scrollY;

        if (scrollY > 60) {
          if (!mainNav.classList.contains('scrolled')) {
            mainNav.classList.add('scrolled');
            navGlass.classList.add('scrolled');
            if (logoImg) logoImg.src = 'img/azul.svg';
          }
        } else {
          if (mainNav.classList.contains('scrolled')) {
            mainNav.classList.remove('scrolled');
            navGlass.classList.remove('scrolled');
            if (logoImg) logoImg.src = 'img/blanco.svg';
          }
        }
      }
      updateNav(); // Correr al inicio
      window.addEventListener('scroll', updateNav, { passive: true });

      /* ── HERO CANVAS PARTICLES ── */
      function initParticles(canvas) {
        if (!canvas || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        const ctx = canvas.getContext('2d');
        let W, H, dots = [], mx = -9999, my = -9999;
        const N = () => Math.min(80, Math.round(window.innerWidth / 16));

        function resize() {
          W = canvas.width = canvas.offsetWidth;
          H = canvas.height = canvas.offsetHeight;
        }
        resize();
        new ResizeObserver(resize).observe(canvas.parentElement);

        /* El cursor se convierte en un nodo: la red reacciona a él */
        const host = canvas.parentElement;
        host.addEventListener('pointermove', e => {
          const r = canvas.getBoundingClientRect();
          mx = e.clientX - r.left; my = e.clientY - r.top;
        }, { passive: true });
        host.addEventListener('pointerleave', () => { mx = my = -9999; });

        function initDots() {
          dots = [];
          for (let i = 0; i < N(); i++) {
            dots.push({
              x: Math.random() * W, y: Math.random() * H,
              r: Math.random() * 1.4 + .5,
              vx: (Math.random() - .5) * .4, vy: (Math.random() - .5) * .4,
              a: Math.random() * Math.PI * 2
            });
          }
        }
        initDots();

        function draw() {
          ctx.clearRect(0, 0, W, H);
          dots.forEach(d => {
            d.x += d.vx; d.y += d.vy; d.a += .007;
            if (d.x < 0) d.x = W; if (d.x > W) d.x = 0;
            if (d.y < 0) d.y = H; if (d.y > H) d.y = 0;
            const alpha = .35 + Math.sin(d.a) * .25;
            ctx.beginPath();
            ctx.arc(d.x, d.y, d.r, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(58,157,138,${alpha})`;
            ctx.fill();
          });
          for (let i = 0; i < dots.length; i++) {
            for (let j = i + 1; j < dots.length; j++) {
              const dx = dots[i].x - dots[j].x, dy = dots[i].y - dots[j].y;
              const dist = Math.sqrt(dx * dx + dy * dy);
              if (dist < 130) {
                ctx.beginPath();
                ctx.moveTo(dots[i].x, dots[i].y);
                ctx.lineTo(dots[j].x, dots[j].y);
                ctx.strokeStyle = `rgba(58,157,138,${(1 - dist / 130) * .12})`;
                ctx.lineWidth = .7;
                ctx.stroke();
              }
            }
          }
          if (mx > -9999) {
            for (let i = 0; i < dots.length; i++) {
              const dx = dots[i].x - mx, dy = dots[i].y - my, dist = Math.sqrt(dx * dx + dy * dy);
              if (dist < 175) {
                ctx.beginPath();
                ctx.moveTo(dots[i].x, dots[i].y);
                ctx.lineTo(mx, my);
                ctx.strokeStyle = `rgba(58,157,138,${(1 - dist / 175) * .5})`;
                ctx.lineWidth = 1;
                ctx.stroke();
              }
            }
            ctx.beginPath();
            ctx.arc(mx, my, 3, 0, Math.PI * 2);
            ctx.fillStyle = 'rgba(58,157,138,.9)';
            ctx.fill();
          }
          requestAnimationFrame(draw);
        }
        draw();
      }
      document.querySelectorAll('#hero-canvas, .ph-canvas').forEach(initParticles);

      /* ── SPOTLIGHT que sigue al cursor en tarjetas ── */
      if (window.matchMedia('(hover: hover) and (pointer: fine)').matches && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        document.querySelectorAll('.svc-card, .cloud-card').forEach(card => {
          card.addEventListener('pointermove', e => {
            const r = card.getBoundingClientRect();
            card.style.setProperty('--mx', (e.clientX - r.left) + 'px');
            card.style.setProperty('--my', (e.clientY - r.top) + 'px');
          });
        });
      }

      /* ── INTERSECTION OBSERVER (Animaciones fluidas en todo el sitio) ── */
      const io = new IntersectionObserver((entries, obs) => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('in'); obs.unobserve(e.target); } });
      }, { threshold: .1, rootMargin: '0px 0px -40px 0px' });
      document.querySelectorAll('.reveal, .reveal-l, .reveal-r, .reveal-scale').forEach(el => io.observe(el));

      /* ── WHATSAPP FAB ── */
      setTimeout(() => { const fab = document.getElementById('wa-fab'); if (fab) fab.classList.add('show'); }, 1800);

      /* ── COOKIE ── */
      const banner = document.getElementById('cookieBanner');
      const okBtn = document.getElementById('cookieOk');
      if (banner && localStorage.getItem('ck') !== '1') setTimeout(() => banner.classList.add('show'), 2500);
      if (okBtn) okBtn.addEventListener('click', () => { localStorage.setItem('ck', '1'); banner.classList.remove('show'); });

      /* ── MENÚ DESPLEGABLE "SERVICIOS" (accesible, hover + clic + teclado) ── */
      const dd = document.querySelector('.nav-dd');
      const ddBtn = document.querySelector('.nav-dd-btn');
      if (dd && ddBtn) {
        const closeDd = () => { dd.classList.remove('open'); ddBtn.setAttribute('aria-expanded', 'false'); };
        ddBtn.addEventListener('click', e => {
          e.stopPropagation();
          const open = dd.classList.toggle('open');
          ddBtn.setAttribute('aria-expanded', open);
        });
        document.addEventListener('click', e => { if (!dd.contains(e.target)) closeDd(); });
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDd(); });
      }

      /* ── PREFERENCIA DE MOVIMIENTO ── */
      const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

      /* ── ACORDEÓN FAQ (accesible) ── */
      document.querySelectorAll('.acc-head').forEach(btn => {
        btn.addEventListener('click', () => {
          const item = btn.closest('.acc-item');
          const isOpen = item.classList.toggle('open');
          btn.setAttribute('aria-expanded', isOpen);
        });
      });

      /* ── CONTADORES ANIMADOS ── */
      const easeOut = t => 1 - Math.pow(1 - t, 3);
      function animateCount(el) {
        const target = parseFloat(el.dataset.target);
        const decimals = (el.dataset.decimals ? parseInt(el.dataset.decimals, 10) : 0);
        const prefix = el.dataset.prefix || '';
        const suffix = el.dataset.suffix || '';
        if (reduceMotion) {
          el.textContent = prefix + target.toFixed(decimals) + suffix;
          return;
        }
        const dur = 1600;
        const start = performance.now();
        function step(now) {
          const p = Math.min((now - start) / dur, 1);
          const val = target * easeOut(p);
          el.textContent = prefix + val.toFixed(decimals) + suffix;
          if (p < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
      }
      const counters = document.querySelectorAll('.metric-num[data-target]');
      if (counters.length) {
        const cObs = new IntersectionObserver((entries, obs) => {
          entries.forEach(e => {
            if (e.isIntersecting) { animateCount(e.target); obs.unobserve(e.target); }
          });
        }, { threshold: .5 });
        counters.forEach(c => cObs.observe(c));
      }

      /* ── TILT 3D EN TARJETAS ── */
      const finePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
      if (finePointer && !reduceMotion) {
        document.querySelectorAll('.feat-card').forEach(card => {
          card.addEventListener('pointermove', e => {
            const r = card.getBoundingClientRect();
            const px = (e.clientX - r.left) / r.width;
            const py = (e.clientY - r.top) / r.height;
            card.style.setProperty('--mx', (px * 100) + '%');
            card.style.setProperty('--my', (py * 100) + '%');
            const rx = (py - .5) * -6;
            const ry = (px - .5) * 6;
            card.style.transform = `translateY(-8px) perspective(800px) rotateX(${rx}deg) rotateY(${ry}deg)`;
          });
          card.addEventListener('pointerleave', () => { card.style.transform = ''; });
        });
      }

      /* ── BARRA DE PROGRESO (fallback si no hay scroll-timeline nativo) ── */
      const bar = document.querySelector('.scroll-progress');
      if (bar && !CSS.supports('animation-timeline: scroll()')) {
        const onScroll = () => {
          const h = document.documentElement;
          const max = h.scrollHeight - h.clientHeight;
          bar.style.setProperty('--sp', max > 0 ? (h.scrollTop / max) : 0);
        };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
      }
    });
