document.addEventListener('DOMContentLoaded', function () {
  const buyUrl = '/regime/buy'; // même endpoint que la page détail

  document.querySelectorAll('.btn-buy-regime').forEach((btn) => {
    btn.addEventListener('click', async () => {
      const card = btn.closest('.card');
      const msg = card?.querySelector('.buy-msg');
      const regimeId = card?.getAttribute('data-regime-id');

      if (!card || !msg || !regimeId) return;

      btn.disabled = true;
      msg.style.display = 'block';
      msg.style.padding = '10px';
      msg.style.borderRadius = '8px';
      msg.style.background = '#f7f7f7';
      msg.textContent = 'Traitement...';

      try {
        const res = await fetch(buyUrl, {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'regime_id=' + encodeURIComponent(regimeId)
        });

        const data = await res.json().catch(() => null);

        if (!res.ok || !data) {
          msg.textContent = "Erreur lors de l'achat.";
          btn.disabled = false;
          return;
        }

        if (data.success) {
          msg.style.background = '#f0fdf4';
          msg.style.border = '1px solid #86efac';
          msg.style.color = '#166534';
          msg.textContent = data.message || 'Achat réussi';
          setTimeout(() => location.reload(), 800);
        } else {
          msg.style.background = '#fef2f2';
          msg.style.border = '1px solid #fecaca';
          msg.style.color = '#991b1b';
          msg.textContent = data.message || 'Achat échoué';
          btn.disabled = false;
        }
      } catch (e) {
        msg.textContent = 'Erreur réseau';
        btn.disabled = false;
      }
    });
  });
});