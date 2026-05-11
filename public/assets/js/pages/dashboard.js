document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('goalModal');
    const openBtn = document.getElementById('openGoalModal');
    const closeBtn = document.querySelector('.close-modal');
    const cancelBtn = document.getElementById('closeGoalModal');
    const goalsContainer = document.getElementById('goalsContainer');
    const goalForm = document.getElementById('updateGoalForm');

    if (!modal || !openBtn || !closeBtn || !cancelBtn || !goalsContainer || !goalForm) {
        return;
    }

    const popupUrl = openBtn.getAttribute('data-popup-url');
    const updateUrl = openBtn.getAttribute('data-update-url');

    // Ouvrir la modal et charger les données
    openBtn.onclick = function () {
        modal.style.display = "block";
        loadGoalsData();
    };

    // Fermer la modal
    const closeModal = () => {
        modal.style.display = "none";
    };
    closeBtn.onclick = closeModal;
    cancelBtn.onclick = closeModal;
    window.onclick = function (event) {
        if (event.target === modal) closeModal();
    };

    async function loadGoalsData() {
        goalsContainer.innerHTML = '<p style="text-align: center;">Chargement...</p>';
        try {
            const response = await fetch(popupUrl);
            const result = await response.json();

            if (result.success) {
                renderGoals(result.data);
            } else {
                goalsContainer.innerHTML = `<p style="color: red;">Erreur: ${result.message}</p>`;
            }
        } catch (error) {
            console.error('Error loading goals:', error);
            goalsContainer.innerHTML = '<p style="color: red;">Erreur lors du chargement des données.</p>';
        }
    }

    function renderGoals(data) {
        const { objectifs_disponibles, objectifs_actuels } = data;
        goalsContainer.innerHTML = '';

        objectifs_disponibles.forEach(obj => {
            const actuel = objectifs_actuels.find(a => a.objectif_id == obj.id);
            const isSelected = !!actuel;

            const item = document.createElement('div');
            item.className = `goal-selection-item ${isSelected ? 'selected' : ''}`;
            item.innerHTML = `
                <div style="display: flex; align-items: center;">
                    <input type="checkbox" class="goal-checkbox" value="${obj.id}" ${isSelected ? 'checked' : ''}>
                    <strong>${obj.libelle}</strong>
                </div>
                <div class="goal-inputs">
                    <div class="form-group">
                        <label>Poids cible (kg)</label>
                        <input type="number" name="poids_cible_${obj.id}" step="0.1" value="${actuel ? actuel.poids_cible : ''}" required>
                    </div>
                    <div class="form-group">
                        <label>Durée (jours)</label>
                        <input type="number" name="duree_${obj.id}" value="${actuel ? actuel.duree : ''}" required>
                    </div>
                </div>
            `;

            const checkbox = item.querySelector('.goal-checkbox');
            checkbox.addEventListener('change', function () {
                if (this.checked) {
                    goalsContainer.querySelectorAll('.goal-selection-item').forEach(otherItem => {
                        const otherCheckbox = otherItem.querySelector('.goal-checkbox');
                        if (otherCheckbox && otherCheckbox !== this) {
                            otherCheckbox.checked = false;
                            otherItem.classList.remove('selected');
                        }
                    });

                    item.classList.add('selected');
                } else {
                    item.classList.remove('selected');
                }
            });

            goalsContainer.appendChild(item);
        });
    }

    goalForm.onsubmit = async function (e) {
        e.preventDefault();

        const selectedItems = goalsContainer.querySelectorAll('.goal-selection-item.selected');
        if (selectedItems.length === 0) {
            alert('Veuillez sélectionner au moins un objectif.');
            return;
        }

        const objectifs = [];
        selectedItems.forEach(item => {
            const id = item.querySelector('.goal-checkbox').value;
            const poidsCible = item.querySelector(`input[name="poids_cible_${id}"]`).value;
            const duree = item.querySelector(`input[name="duree_${id}"]`).value;

            objectifs.push({
                objectif_id: parseInt(id),
                poids_cible: parseFloat(poidsCible),
                duree: parseInt(duree)
            });
        });

        try {
            const response = await fetch(updateUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ objectifs })
            });

            const result = await response.json();
            if (result.success) {
                alert('Objectifs mis à jour avec succès !');
                location.reload();
            } else {
                alert('Erreur: ' + result.message);
            }
        } catch (error) {
            console.error('Error updating goals:', error);
            alert('Une erreur est survenue lors de la mise à jour.');
        }
    };
});