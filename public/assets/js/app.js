// ERID-AMRAfrica — interactions front-end
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('subForm');
  if (!form) return;

  // Récupère le jeton CSRF depuis un champ caché injecté si présent,
  // sinon le serveur acceptera l'envoi via la session (démo simplifiée).
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const msg = document.getElementById('subMsg');
    const data = new FormData(form);
    msg.textContent = '…';
    try {
      const res = await fetch('/subscribe', { method: 'POST', body: data });
      const json = await res.json();
      msg.textContent = json.ok ? '✓ Inscrit / Subscribed' : '✗ Email invalide';
      if (json.ok) form.reset();
    } catch (_) {
      msg.textContent = '✗ Erreur réseau';
    }
  });
});
