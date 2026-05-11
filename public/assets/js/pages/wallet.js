(function () {
  const btn = document.getElementById('btn-redeem-code');
  const input = document.getElementById('code');
  const msg = document.getElementById('redeem-msg');

  if (!btn || !input || !msg) return;

  const redeemUrl = btn.getAttribute('data-redeem-url');
  if (!redeemUrl) {
    console.error('[wallet] Missing data-redeem-url on #btn-redeem-code');
    msg.className = 'msg error';
    msg.style.display = 'block';
    msg.textContent = 'Configuration manquante (URL).';
    return;
  }

  async function submitCode() {
    const code = input.value.trim();

    msg.className = 'msg';
    msg.style.display = 'block';

    if (!code) {
      msg.classList.add('error');
      msg.textContent = 'Veuillez saisir un code.';
      return;
    }

    btn.disabled = true;
    msg.textContent = 'Envoi de la demande...';

    try {
      const response = await fetch(redeemUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ code })
      });

      const data = await response.json().catch(() => null);

      if (!response.ok) {
        msg.classList.add('error');
        msg.textContent = (data && data.message) ? data.message : 'Erreur serveur.';
        return;
      }

      if (!data) {
        msg.classList.add('error');
        msg.textContent = 'Réponse invalide du serveur.';
        return;
      }

      if (data.success) {
        msg.classList.add('success');
        msg.textContent = data.message || 'Demande envoyée avec succès.';
        input.value = '';
      } else {
        msg.classList.add('error');
        msg.textContent = data.message || 'Une erreur est survenue.';
      }
    } catch (e) {
      msg.classList.add('error');
      msg.textContent = 'Erreur réseau.';
    } finally {
      btn.disabled = false;
    }
  }

  btn.addEventListener('click', submitCode);
  input.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      submitCode();
    }
  });
})();