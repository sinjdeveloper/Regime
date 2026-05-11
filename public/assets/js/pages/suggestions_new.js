// Modal Functions
function openProfileModal() {
    document.getElementById('profileModal')?.classList.add('active');
}

function closeProfileModal() {
    document.getElementById('profileModal')?.classList.remove('active');
}

function openGoldModal() {
    document.getElementById('goldModal')?.classList.add('active');
}

function closeGoldModal() {
    document.getElementById('goldModal')?.classList.remove('active');
}

function openCodeModal() {
    document.getElementById('codeModal')?.classList.add('active');
    const msg = document.getElementById('codeMessage');
    const input = document.getElementById('codeInput');
    if (msg) msg.innerHTML = '';
    if (input) input.value = '';
}

function closeCodeModal() {
    document.getElementById('codeModal')?.classList.remove('active');
}

function subscribeToGold() {
    alert('Redirection vers la page de paiement pour l\'abonnement GOLD...');
    // window.location.href = '...';
}

function validateCode() {
    const input = document.getElementById('codeInput');
    const messageDiv = document.getElementById('codeMessage');
    const container = document.getElementById('codeModal');
    const validateUrl = container?.getAttribute('data-validate-code-url');

    const code = (input?.value || '').trim();

    if (!messageDiv) return;

    if (!code) {
        messageDiv.innerHTML = '<div class="code-message error">❌ Veuillez entrer un code promo</div>';
        return;
    }

    fetch(validateUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ code: code })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageDiv.innerHTML = `<div class="code-message success">✓ ${data.message}<br><strong>+${data.amount}€ ajoutés!</strong></div>`;
                if (input) input.value = '';
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
                messageDiv.innerHTML = `<div class="code-message error">❌ ${data.message}</div>`;
            }
        })
        .catch(error => {
            messageDiv.innerHTML = '<div class="code-message error">❌ Erreur lors de la validation</div>';
            console.error('Error:', error);
        });
}

// Close modals when clicking outside + Escape
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('profileModal')?.addEventListener('click', function (e) {
        if (e.target === this) closeProfileModal();
    });

    document.getElementById('goldModal')?.addEventListener('click', function (e) {
        if (e.target === this) closeGoldModal();
    });

    document.getElementById('codeModal')?.addEventListener('click', function (e) {
        if (e.target === this) closeCodeModal();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeProfileModal();
            closeGoldModal();
            closeCodeModal();
        }
    });
});