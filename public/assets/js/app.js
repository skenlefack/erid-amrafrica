// ERID-AMRAfrica — vanilla JS interactions
document.addEventListener('DOMContentLoaded', () => {

  /* ---- 1. Header shadow on scroll ---- */
  const header = document.querySelector('.site-header');
  if (header) {
    const onScroll = () => {
      header.classList.toggle('scrolled', window.scrollY > 10);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  /* ---- 2. Mobile menu toggle ---- */
  const toggle = document.getElementById('menuToggle');
  const menu = document.getElementById('mainMenu');
  const overlay = document.getElementById('menuOverlay');

  if (toggle && menu) {
    const openMenu = () => {
      menu.classList.add('open');
      if (overlay) overlay.classList.add('open');
      toggle.setAttribute('aria-expanded', 'true');
      document.body.style.overflow = 'hidden';
    };
    const closeMenu = () => {
      menu.classList.remove('open');
      if (overlay) overlay.classList.remove('open');
      toggle.setAttribute('aria-expanded', 'false');
      document.body.style.overflow = '';
    };

    toggle.addEventListener('click', () => {
      const isOpen = toggle.getAttribute('aria-expanded') === 'true';
      isOpen ? closeMenu() : openMenu();
    });

    if (overlay) overlay.addEventListener('click', closeMenu);

    // Close on Escape
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
        closeMenu();
        toggle.focus();
      }
    });

    // Close when a menu link is clicked (mobile)
    menu.querySelectorAll('a').forEach((a) => {
      a.addEventListener('click', closeMenu);
    });
  }

  /* ---- 3. Newsletter subscription ---- */
  const form = document.getElementById('subForm');
  if (form) {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const msg = document.getElementById('subMsg');
      const data = new FormData(form);
      msg.textContent = '\u2026';
      try {
        const res = await fetch('/subscribe', { method: 'POST', body: data });
        const json = await res.json();
        msg.textContent = json.ok ? '\u2713 Inscrit / Subscribed' : '\u2717 Email invalide';
        if (json.ok) form.reset();
      } catch (_) {
        msg.textContent = '\u2717 Erreur r\u00e9seau';
      }
    });
  }
});
