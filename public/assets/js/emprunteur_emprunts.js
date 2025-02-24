document.addEventListener("DOMContentLoaded", () => {
    const enCoursContainer = document.getElementById("enCoursContainer");
    const passesContainer = document.getElementById("passesContainer");

    // Charger les emprunts en cours
    async function loadEnCours() {
        const response = await fetch("actions/emprunteur_emprunts_action.php?action=get_en_cours");
        const data = await response.json();
        enCoursContainer.innerHTML = data.message || data.map(emprunt => {
            const dateEnd = new Date(emprunt.date_end);
            const now = new Date();
            const diffDays = Math.ceil((dateEnd - now) / (1000 * 60 * 60 * 24));

            let statusClass = "text-success";
            let message = "Pas d'urgence pour rendre l'objet.";

            if (diffDays <= 0) {
                statusClass = "text-danger";
                message = "Vous avez dépassé la date de restitution !";
            } else if (diffDays <= 2) {
                statusClass = "text-warning";
                message = "Rendez l'objet sous 2 jours pour éviter des pénalités.";
            }

            return `
                <div class="card mb-3">
                    <div class="card-body">
                        <h5>${emprunt.object_name}</h5>
                        <p>Prêteur : ${emprunt.preteur_name}</p>
                        <p>Début : ${emprunt.date_start}</p>
                        <p class="${statusClass}">Fin prévue : ${emprunt.date_end}</p>
                        <p>${message}</p>
                        <p>Quantité : ${emprunt.quantity_borrowed}</p>
                    </div>
                </div>
            `;
        }).join("");
    }

    // Charger les emprunts passés
    async function loadPasses() {
        const response = await fetch("actions/emprunteur_emprunts_action.php?action=get_passes");
        const data = await response.json();
        passesContainer.innerHTML = data.message || data.map(emprunt => `
            <div class="card mb-3">
                <div class="card-body">
                    <h5>${emprunt.object_name}</h5>
                    <p>Prêteur : ${emprunt.preteur_name}</p>
                    <p>Début : ${emprunt.date_start}</p>
                    <p>Rendu le : ${emprunt.returned_date || "Non précisé"}</p>
                    <p>Quantité : ${emprunt.quantity_borrowed}</p>
                </div>
            </div>
        `).join("");
    }

    // Initialisation
    loadEnCours();
    loadPasses();
});
