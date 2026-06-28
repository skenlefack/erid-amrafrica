// ERID-AMRAfrica — interactions
document.addEventListener('DOMContentLoaded', () => {

  /* ---- 1. Header scroll shadow ---- */
  const nav = document.querySelector('.nav-bar');
  if (nav) {
    window.addEventListener('scroll', () => {
      nav.classList.toggle('scrolled', window.scrollY > 10);
    }, { passive: true });
  }

  /* ---- 2. Mobile menu (left slide) ---- */
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
});
