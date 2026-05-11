document.addEventListener('DOMContentLoaded', function () {
  const page = document.querySelector('.code-validation-page');
  const form = document.getElementById('codeValidationForm');
  const input = document.getElementById('code-input');
  const btn = document.getElementById('btn-submit-code');
  const msg = document.getElementById('code-msg');

  if (!page || !form || !input || !btn || !msg) return;

  const redeemUrl = page.getAttribute('data-redeem-url');

  function showMessage(text, type) {
    msg.style.display = 'block';
    msg.textContent = text;

    // styles simples (sans toucher au CSS global)
    msg.style.padding = '12px';
    msg.style.borderRadius = '12px';
    msg.style.border = '1px solid transparent';

    if (type === 'success') {
      msg.style.background = '#eafaf1';
      msg.style.color = '#1e7e34';
      msg.style.borderColor = '#c3e6cb';
    } else {
      msg.style.background = '#fdeaea';
      msg.style.color = '#b02a37';
      msg.style.borderColor = '#f5c2c7';
    }
  }

  form.addEventListener('submit', async function (e) {
    e.preventDefault();

    const code = (input.value || '').trim();

    if (!code) {
      showMessage('Veuillez entrer un code de validation.', 'error');
      return;
    }
    if (code.length < 6) {
      showMessage('Le code doit contenir au moins 6 caractères.', 'error');
      return;
    }
    if (!redeemUrl) {
      showMessage('Configuration manquante (URL).', 'error');
      return;
    }

    btn.disabled = true;
    btn.textContent = 'Envoi...';

    try {
      const res = await fetch(redeemUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ code })
      });

      const data = await res.json().catch(() => null);

      if (!res.ok) {
        showMessage((data && data.message) ? data.message : 'Erreur serveur.', 'error');
        return;
      }

      if (data && data.success) {
        showMessage(data.message || 'Demande envoyée avec succès.', 'success');
        input.value = '';
      } else {
        showMessage((data && data.message) ? data.message : 'Une erreur est survenue.', 'error');
      }
    } catch (err) {
      showMessage('Erreur réseau.', 'error');
    } finally {
      btn.disabled = false;
      btn.textContent = 'Envoyer pour validation';
    }
  });
});