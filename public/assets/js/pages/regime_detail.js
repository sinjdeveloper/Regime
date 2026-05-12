document.addEventListener('DOMContentLoaded', function () {
  const root = document.querySelector('.regime-detail');
  if (!root) return;

  const btn = document.getElementById('btn-buy-regime');
  const msg = document.getElementById('buy-msg');
  if (!btn || !msg) return;

  const buyUrl = root.getAttribute('data-buy-url');
  const regimeId = root.getAttribute('data-regime-id');

  btn.addEventListener('click', async function () {
    btn.disabled = true;
    msg.style.display = 'block';
    msg.textContent = 'Traitement...';
    msg.style.padding = '10px';
    msg.style.borderRadius = '8px';
    msg.style.background = '#f7f7f7';

    try {
      const res = await fetch(buyUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'regime_id=' + encodeURIComponent(regimeId || '')
      });

      const resClone = res.clone();
      const data = await res.json().catch(() => null);
      let serverMsg = null;
      if (data && data.message) serverMsg = data.message;
      else {
        const text = await resClone.text().catch(() => null);
        if (text) serverMsg = text;
      }

      if (!res.ok || !data) {
        msg.textContent = serverMsg || "Erreur lors de l'achat.";
        btn.disabled = false;
        return;
      }

      if (data.success) {
        msg.textContent = data.message || 'Achat réussi';
        setTimeout(() => location.reload(), 800);
      } else {
        msg.textContent = data.message || serverMsg || 'Achat échoué';
        btn.disabled = false;
      }
    } catch (e) {
      msg.textContent = 'Erreur réseau';
      btn.disabled = false;
    }
  });
});