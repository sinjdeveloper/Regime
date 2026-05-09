document.addEventListener("DOMContentLoaded", () => {

   

    const profileBtn = document.querySelector(".btn-icon");
    if (profileBtn) {

        const popup = document.createElement("div");
        popup.className = "profile-popup";

        popup.innerHTML = `
            <a href="/login"> Non connecté</a>
            <div class="popup-divider"></div>
            <a href="/login"> Modifier objectifs</a>
            <a href="/login">Modifier durée</a>
            <a href="/login"> Entrer code</a>
        `;

        document.body.appendChild(popup);

        let isOpen = false;

        function positionPopup() {
            const rect = profileBtn.getBoundingClientRect();

            popup.style.top = (rect.bottom + window.scrollY + 10) + "px";
            popup.style.left = (rect.left + window.scrollX - 120) + "px";
        }

        profileBtn.addEventListener("click", (e) => {
            e.preventDefault();

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