document.addEventListener("DOMContentLoaded", () => {

    const profileBtn = document.querySelector(".btn-icon");

    if (!profileBtn) return;

    const popup = document.createElement("div");
    popup.classList.add("profile-popup");
    popup.style.position = "absolute";
    popup.style.top = "70px";
    popup.style.right = "20px";
    popup.style.background = "#fff";
    popup.style.border = "1px solid #ddd";
    popup.style.padding = "15px";
    popup.style.borderRadius = "10px";
    popup.style.boxShadow = "0 5px 15px rgba(0,0,0,0.1)";
    popup.style.display = "none";
    popup.style.zIndex = "1000";

    popup.innerHTML = `
        <p><strong>Non connecté</strong></p>
        <hr>
        <a href="/login">Modifier objectifs</a><br>
        <a href="/login">Modifier durée</a><br>
        <a href="/login">Entrer code</a>
    `;

    document.body.appendChild(popup);

    profileBtn.addEventListener("click", (e) => {
        e.preventDefault();
        popup.style.display = popup.style.display === "none" ? "block" : "none";
    });

    document.addEventListener("click", (e) => {
        if (!popup.contains(e.target) && !profileBtn.contains(e.target)) {
            popup.style.display = "none";
        }
    });

});