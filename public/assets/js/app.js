// ERID-AMRAfrica — interactions
document.addEventListener('DOMContentLoaded', () => {

  /* ---- 1. Header scroll shadow ---- */
  const header = document.querySelector('.site-header');
  if (header) {
    const onScroll = () => header.classList.toggle('scrolled', window.scrollY > 10);
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  /* ---- 2. Mobile menu ---- */
  const toggle = document.getElementById('menuToggle');
  const menu = document.getElementById('mainMenu');
  const overlay = document.getElementById('menuOverlay');
  if (toggle && menu) {
    const open = () => { menu.classList.add('open'); overlay && overlay.classList.add('open'); toggle.setAttribute('aria-expanded','true'); document.body.style.overflow='hidden'; };
    const close = () => { menu.classList.remove('open'); overlay && overlay.classList.remove('open'); toggle.setAttribute('aria-expanded','false'); document.body.style.overflow=''; };
    toggle.addEventListener('click', () => toggle.getAttribute('aria-expanded')==='true' ? close() : open());
    overlay && overlay.addEventListener('click', close);
    document.addEventListener('keydown', e => { if (e.key==='Escape' && toggle.getAttribute('aria-expanded')==='true') { close(); toggle.focus(); }});
    menu.querySelectorAll('a').forEach(a => a.addEventListener('click', close));
  }

  /* ---- 3. Newsletter ---- */
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
        if (json.ok) form.reset();
      } catch(_) { msg.textContent = '\u2717 Erreur r\u00e9seau'; }
    });
  }

  /* ---- 4. Slideshow ---- */
  const slideshow = document.querySelector('.slideshow');
  if (!slideshow) return;

  const slides = slideshow.querySelectorAll('.slide');
  const dots   = slideshow.querySelectorAll('.slide-dot');
  const bar    = slideshow.querySelector('.slide-progress__bar');
  const prevBtn = slideshow.querySelector('.slide-arrow--prev');
  const nextBtn = slideshow.querySelector('.slide-arrow--next');
  const total  = slides.length;
  let current  = 0;
  let timer    = null;
  let paused   = false;
  const INTERVAL = 6000;

  // Respect reduced motion
  const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function goTo(idx) {
    if (idx === current) return;
    const prev = current;
    current = ((idx % total) + total) % total;

    // Animate out
    slides[prev].classList.remove('slide--active');
    slides[prev].classList.add('slide--exit');
    dots[prev].classList.remove('slide-dot--active');

    // Animate in
    slides[current].classList.add('slide--active');
    dots[current].classList.add('slide-dot--active');

    // Clean up exit class
    setTimeout(() => slides[prev].classList.remove('slide--exit'), 800);

    resetTimer();
  }

  function next() { goTo(current + 1); }
  function prev() { goTo(current - 1); }

  function resetTimer() {
    clearInterval(timer);
    if (bar) { bar.style.transition = 'none'; bar.style.width = '0%'; }
    if (!paused) {
      // Trigger reflow then animate
      requestAnimationFrame(() => {
        if (bar) {
          bar.style.transition = 'width ' + INTERVAL + 'ms linear';
          bar.style.width = '100%';
        }
      });
      timer = setInterval(next, INTERVAL);
    }
  }

  // Button events
  prevBtn && prevBtn.addEventListener('click', prev);
  nextBtn && nextBtn.addEventListener('click', next);

  // Dot events
  dots.forEach(dot => {
    dot.addEventListener('click', () => goTo(parseInt(dot.dataset.goto, 10)));
  });

  // Pause on hover / focus
  slideshow.addEventListener('mouseenter', () => { paused = true; clearInterval(timer); if (bar) bar.style.animationPlayState = 'paused'; });
  slideshow.addEventListener('mouseleave', () => { paused = false; resetTimer(); });

  // Keyboard
  slideshow.addEventListener('keydown', e => {
    if (e.key === 'ArrowRight') next();
    else if (e.key === 'ArrowLeft') prev();
  });

  // Touch swipe
  let touchX = 0;
  slideshow.addEventListener('touchstart', e => { touchX = e.changedTouches[0].screenX; }, { passive: true });
  slideshow.addEventListener('touchend', e => {
    const diff = e.changedTouches[0].screenX - touchX;
    if (Math.abs(diff) > 50) diff < 0 ? next() : prev();
  }, { passive: true });

  // Start
  if (!prefersReduced) {
    resetTimer();
  }
});
