// ERID-AMRAfrica — Interactions & Animations v2
document.addEventListener('DOMContentLoaded', () => {

  /* ---- 1. Scroll animations — fade-in elements on scroll ---- */
  const observerOptions = { threshold: 0.08, rootMargin: '0px 0px -40px 0px' };
  const animateOnScroll = new IntersectionObserver((entries) => {
    entries.forEach((entry, i) => {
      if (entry.isIntersecting) {
        entry.target.style.animationDelay = (i * 0.08) + 's';
        entry.target.classList.add('visible');
        animateOnScroll.unobserve(entry.target);
      }
    });
  }, observerOptions);

  // Observe all animatable elements
  document.querySelectorAll(
    '.td-module, .td-module-horiz, .card, .service-row, .widget, .panel, .kpi-card, .tier, .section, .cta-block, .cta-band'
  ).forEach(el => {
    el.classList.add('scroll-animate');
    animateOnScroll.observe(el);
  });

  // CSS for scroll animations (injected to keep it self-contained)
  const style = document.createElement('style');
  style.textContent = `
    .scroll-animate {
      opacity: 0;
      transform: translateY(24px);
      transition: opacity .6s cubic-bezier(.4,0,.2,1), transform .6s cubic-bezier(.4,0,.2,1);
    }
    .scroll-animate.visible {
      opacity: 1;
      transform: translateY(0);
    }
  `;
  document.head.appendChild(style);

  /* ---- 2. Header scroll shadow ---- */
  const nav = document.querySelector('.nav-bar');
  if (nav) {
    window.addEventListener('scroll', () => {
      nav.style.boxShadow = window.scrollY > 10
        ? '0 4px 30px rgba(27,58,107,.2)'
        : '0 4px 20px rgba(27,58,107,.15)';
    }, { passive: true });
  }

  /* ---- 3. Mobile menu (left slide) ---- */
  const toggle = document.getElementById('menuToggle');
  const menu = document.getElementById('mainMenu');
  const overlay = document.getElementById('menuOverlay');
  if (toggle && menu) {
    const open = () => {
      menu.classList.add('open');
      overlay && overlay.classList.add('open');
      toggle.setAttribute('aria-expanded','true');
      document.body.style.overflow='hidden';
    };
    const close = () => {
      menu.classList.remove('open');
      overlay && overlay.classList.remove('open');
      toggle.setAttribute('aria-expanded','false');
      document.body.style.overflow='';
    };
    toggle.addEventListener('click', () =>
      toggle.getAttribute('aria-expanded')==='true' ? close() : open()
    );
    overlay && overlay.addEventListener('click', close);
    document.addEventListener('keydown', e => {
      if (e.key==='Escape' && toggle.getAttribute('aria-expanded')==='true') {
        close(); toggle.focus();
      }
    });
    menu.querySelectorAll('a').forEach(a => a.addEventListener('click', close));
  }

  /* ---- 4. Newsletter ---- */
  const form = document.getElementById('subForm');
  if (form) {
    form.addEventListener('submit', async e => {
      e.preventDefault();
      const msg = document.getElementById('subMsg');
      msg.textContent = '\u2026';
      try {
        const res = await fetch('/subscribe', { method:'POST', body: new FormData(form) });
        const json = await res.json();
        msg.textContent = json.ok ? '\u2713 Inscrit / Subscribed' : '\u2717 Email invalide';
        msg.style.color = json.ok ? '#2E8B57' : '#e74c3c';
        if (json.ok) form.reset();
      } catch(_) { msg.textContent = '\u2717 Erreur r\u00e9seau'; msg.style.color = '#e74c3c'; }
    });
  }

  /* ---- 5. Hero Slideshow ---- */
  const slides = document.querySelectorAll('.slide');
  const dots = document.querySelectorAll('.dot');
  if (slides.length > 1) {
    let current = 0;
    let interval;

    const goTo = (idx) => {
      slides[current].classList.remove('active');
      dots[current] && dots[current].classList.remove('active');
      current = (idx + slides.length) % slides.length;
      slides[current].classList.add('active');
      dots[current] && dots[current].classList.add('active');
    };

    const startAuto = () => { interval = setInterval(() => goTo(current + 1), 5000); };
    const stopAuto = () => clearInterval(interval);

    dots.forEach(d => d.addEventListener('click', () => { stopAuto(); goTo(+d.dataset.slide); startAuto(); }));
    const prevBtn = document.querySelector('.slide-prev');
    const nextBtn = document.querySelector('.slide-next');
    if (prevBtn) prevBtn.addEventListener('click', () => { stopAuto(); goTo(current - 1); startAuto(); });
    if (nextBtn) nextBtn.addEventListener('click', () => { stopAuto(); goTo(current + 1); startAuto(); });

    // Swipe support
    const slideshow = document.querySelector('.hero-slideshow');
    let touchX = 0;
    if (slideshow) {
      slideshow.addEventListener('touchstart', e => { touchX = e.touches[0].clientX; }, { passive: true });
      slideshow.addEventListener('touchend', e => {
        const diff = touchX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 50) { stopAuto(); goTo(current + (diff > 0 ? 1 : -1)); startAuto(); }
      }, { passive: true });
    }

    startAuto();
  }

  /* ---- 6. Smooth hover tilt on cards ---- */
  document.querySelectorAll('.big-grid__main, .big-grid__card').forEach(el => {
    el.addEventListener('mousemove', e => {
      const rect = el.getBoundingClientRect();
      const x = (e.clientX - rect.left) / rect.width - 0.5;
      const y = (e.clientY - rect.top) / rect.height - 0.5;
      el.style.transform = `perspective(800px) rotateY(${x * 4}deg) rotateX(${-y * 4}deg)`;
    });
    el.addEventListener('mouseleave', () => {
      el.style.transform = '';
      el.style.transition = 'transform .4s ease';
    });
    el.addEventListener('mouseenter', () => {
      el.style.transition = 'transform .1s ease';
    });
  });

  /* ---- 7. Counter animation for KPI values ---- */
  document.querySelectorAll('.kpi-value, .kpi-row strong').forEach(el => {
    const text = el.textContent.trim();
    const match = text.match(/^[\$€]?([\d,]+)/);
    if (!match) return;
    const target = parseInt(match[1].replace(/,/g, ''), 10);
    if (isNaN(target) || target === 0) return;
    const prefix = text.match(/^([\$€])/)?.[1] || '';
    const suffix = text.slice(text.indexOf(match[1]) + match[1].length);
    let current = 0;
    const step = Math.ceil(target / 40);
    const obs = new IntersectionObserver(entries => {
      if (entries[0].isIntersecting) {
        const timer = setInterval(() => {
          current = Math.min(current + step, target);
          el.textContent = prefix + current.toLocaleString() + suffix;
          if (current >= target) clearInterval(timer);
        }, 30);
        obs.unobserve(el);
      }
    }, { threshold: 0.5 });
    obs.observe(el);
  });

  /* ---- 8. Modern file upload ---- */
  document.querySelectorAll('.file-upload').forEach(zone => {
    const input = zone.querySelector('.file-upload__input');
    const nameEl = zone.closest('.file-upload-wrapper')?.querySelector('.file-upload__name');
    if (!input) return;

    input.addEventListener('change', () => {
      if (input.files.length && nameEl) {
        const f = input.files[0];
        const size = f.size < 1048576 ? (f.size / 1024).toFixed(0) + ' Ko' : (f.size / 1048576).toFixed(1) + ' Mo';
        nameEl.textContent = '📄 ' + f.name + ' (' + size + ')';
      }
    });

    ['dragenter', 'dragover'].forEach(ev => zone.addEventListener(ev, e => { e.preventDefault(); zone.classList.add('dragover'); }));
    ['dragleave', 'drop'].forEach(ev => zone.addEventListener(ev, e => { e.preventDefault(); zone.classList.remove('dragover'); }));
    zone.addEventListener('drop', e => {
      if (e.dataTransfer.files.length) {
        input.files = e.dataTransfer.files;
        input.dispatchEvent(new Event('change'));
      }
    });
  });

  /* ---- 9. Active sidebar link highlight ---- */
  const currentPath = window.location.pathname;
  document.querySelectorAll('.sidebar nav a').forEach(a => {
    if (a.getAttribute('href') === currentPath) {
      a.style.background = 'rgba(255,255,255,.1)';
      a.style.color = '#fff';
      a.style.fontWeight = '700';
    }
  });

});
