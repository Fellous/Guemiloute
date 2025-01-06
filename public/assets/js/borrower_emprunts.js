document.addEventListener("DOMContentLoaded", () => {
    const enCoursContainer = document.getElementById("enCoursContainer");
    const passesContainer = document.getElementById("passesContainer");

    // Charger les emprunts en cours
    async function loadEnCours() {
        const response = await fetch("actions/get_borrower_emprunts_action.php?status=en_cours");
        const emprunts = await response.json();
        enCoursContainer.innerHTML = emprunts.map(emprunt => `
            <div class="card mb-3">
                <div class="card-body">
                    <h5>${emprunt.object_name}</h5>
                    <p>Prêteur : ${emprunt.preteur_name}</p>
                    <p>Début : ${emprunt.date_start}</p>
                    <p>Fin prévue : ${emprunt.date_end}</p>
                </div>
            </div>
        `).join("");
    }

    // Charger les emprunts passés
    async function loadPasses() {
        const response = await fetch("actions/get_borrower_emprunts_action.php?status=termine");
        const emprunts = await response.json();
        passesContainer.innerHTML = emprunts.map(emprunt => `
            <div class="card mb-3">
                <div class="card-body">
                    <h5>${emprunt.object_name}</h5>
                    <p>Prêteur : ${emprunt.preteur_name}</p>
                    <p>Rendu le : ${emprunt.returned_date}</p>
                </div>
            </div>
        `).join("");
    }

    // Charger les emprunts
    loadEnCours();
    loadPasses();
});
