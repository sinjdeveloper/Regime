document.addEventListener("DOMContentLoaded", () => {

    const auth = document.body?.dataset?.auth === '1';
    const role = document.body?.dataset?.role || '';
    const username = document.body?.dataset?.username || '';
    const isAdmin = role === 'admin';

    const urls = {
        login: '/user/login',
        logout: '/logout',
        dashboard: isAdmin ? '/admin/dashboard' : '/dashboard',
        profile: isAdmin ? '/admin/dashboard' : '/dashboard',
        imc: isAdmin ? '/admin/dashboard' : '/dashboard',
        suivi: isAdmin ? '/admin/dashboard' : '/dashboard',
        gold: isAdmin ? '/admin/dashboard' : '/gold',
        wallet: '/wallet'
    };

    function buildProfilePopupHtml() {
        if (!auth) {
            return `
                <a href="${urls.login}">Se connecter</a>
                <div class="popup-divider"></div>
                <a href="${urls.login}">Mon IMC</a>
                <a href="${urls.login}">Suivi</a>
                <a href="${urls.login}">Offre Gold</a>
            `;
        }

        if (isAdmin) {
            return `
                <a href="${urls.dashboard}">Administration</a>
                <div class="popup-divider"></div>
                <a href="${urls.logout}">Déconnexion</a>
            `;
        }

        const label = username ? `Connecté: ${username}` : 'Mon compte';
        return `
            <a href="${urls.profile}">${label}</a>
            <div class="popup-divider"></div>
            <a href="${urls.imc}">Mon IMC</a>
            <a href="${urls.suivi}">Modifier durée</a>
            <a href="${urls.wallet}">Entrer code</a>
            <div class="popup-divider"></div>
            <a href="${urls.logout}">Déconnexion</a>
        `;
    }

    const profileBtn = document.querySelector(".btn-icon");
    if (profileBtn) {

        const popup = document.createElement("div");
        popup.className = "profile-popup";

        popup.innerHTML = buildProfilePopupHtml();

        document.body.appendChild(popup);

        let isOpen = false;

        function positionPopup() {
            const rect = profileBtn.getBoundingClientRect();

            popup.style.top = (rect.bottom + window.scrollY + 10) + "px";
            popup.style.left = (rect.left + window.scrollX - 120) + "px";
        }

        profileBtn.addEventListener("click", (e) => {
            e.preventDefault();

            // Rebuild content in case auth state changed
            popup.innerHTML = buildProfilePopupHtml();

            isOpen = !isOpen;

            if (isOpen) {
                positionPopup();
                popup.classList.add("show");
            } else {
                popup.classList.remove("show");
            }
        });

        document.addEventListener("click", (e) => {
            if (!popup.contains(e.target) && !profileBtn.contains(e.target)) {
                popup.classList.remove("show");
                isOpen = false;
            }
        });

        window.addEventListener("resize", () => {
            if (isOpen) positionPopup();
        });
    }


    const buttons = document.querySelectorAll('.filter-btn');
    const cards = document.querySelectorAll('.card');

    buttons.forEach(button => {

        button.addEventListener('click', () => {

            buttons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            const filter = button.dataset.filter;

            cards.forEach(card => {

                if (filter === 'all') {
                    card.style.display = 'flex';
                }
                else if (card.classList.contains(filter)) {
                    card.style.display = 'flex';
                }
                else {
                    card.style.display = 'none';
                }

            });

        });

    });

});