(function () {
    const btn = document.getElementById('btn-subscribe-gold');
    const msg = document.getElementById('gold-msg');
    if (!btn || !msg) return;

    const subscribeUrl = btn.getAttribute('data-subscribe-url');

    btn.addEventListener('click', async function () {
        btn.disabled = true;
        msg.style.display = 'block';
        msg.textContent = 'Traitement...';

        try {
            const res = await fetch(subscribeUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({})
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
                msg.textContent = serverMsg || 'Erreur pendant l\'activation.';
                btn.disabled = false;
                return;
            }

            msg.textContent = data.message || 'Terminé.';
            if (data.success) {
                setTimeout(() => window.location.reload(), 800);
            } else {
                btn.disabled = false;
            }
        } catch (e) {
            msg.textContent = 'Erreur réseau.';
            btn.disabled = false;
        }
    });
})();