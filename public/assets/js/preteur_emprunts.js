document.addEventListener("DOMContentLoaded", () => { 
    const enCoursContainer = document.getElementById("enCoursContainer");
    const passesContainer = document.getElementById("passesContainer");
    const objectSelect = document.getElementById("objectSelect");
    const emprunteurSelect = document.getElementById("emprunteurSelect");
    const nouveauEmpruntForm = document.getElementById("nouveauEmpruntForm");
    const restitutionModal = new bootstrap.Modal(document.getElementById("restitutionModal"));
    const restitutionForm = document.getElementById("restitutionForm");

    // Charger les emprunts en cours
    async function loadEnCours() {
        const response = await fetch("actions/preteur_emprunts_action.php?action=get_en_cours");
        const data = await response.json();
        enCoursContainer.innerHTML = data.message || data.map(emprunt => `
            <div class="card mb-3">
                <div class="card-body">
                    <h5>${emprunt.object_name}</h5>
                    <p>Emprunteur : ${emprunt.emprunteur_name}</p>
                    <p>Début : ${emprunt.date_start}</p>
                    <p>Fin prévue : ${emprunt.date_end}</p>
                    <p>Quantité : ${emprunt.quantity_borrowed}</p>
                    <button class="btn btn-success btn-sm open-restitution-modal" 
                        data-id="${emprunt.id}" 
                        data-object-name="${emprunt.object_name}" 
                        data-quantity="${emprunt.quantity_borrowed}" 
                        data-state="${emprunt.state}">
                        Restituer
                    </button>
                </div>
            </div>
        `).join("");
    }

    // Charger les emprunts terminés
    async function loadPasses() {
        const response = await fetch("actions/preteur_emprunts_action.php?action=get_termines");
        const data = await response.json();
        passesContainer.innerHTML = data.message || data.map(emprunt => `
            <div class="card mb-3">
                <div class="card-body">
                    <h5>${emprunt.object_name}</h5>
                    <p>Emprunteur : ${emprunt.emprunteur_name}</p>
                    <p>Rendu le : ${emprunt.returned_date || "Non précisé"}</p>
                </div>
            </div>
        `).join("");
    }

    // Charger les objets
    async function loadObjects() {
        const response = await fetch("actions/preteur_emprunts_action.php?action=get_objects");
        const data = await response.json();
        objectSelect.innerHTML = data.message || data.map(
            obj => `<option value="${obj.id}">${obj.name} (Quantité : ${obj.quantity})</option>`
        ).join("");
    }

    // Charger les emprunteurs
    async function loadEmprunteurs() {
        const response = await fetch("actions/preteur_emprunts_action.php?action=get_emprunteurs");
        const data = await response.json();
        emprunteurSelect.innerHTML = data.message || data.map(
            user => `<option value="${user.id}">${user.last_name}</option>`
        ).join("");
    }

    // Créer un emprunt
    nouveauEmpruntForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        const formData = new FormData();
        formData.append("object_id", objectSelect.value);
        formData.append("emprunteur_id", emprunteurSelect.value);
        formData.append("date_start", document.getElementById("dateStart").value);
        formData.append("date_end", document.getElementById("dateEnd").value);
        formData.append("quantity", document.getElementById("quantity").value);

        if (!objectSelect.value || !emprunteurSelect.value || !formData.get("date_start") || !formData.get("date_end") || !formData.get("quantity")) {
            alert("Veuillez remplir tous les champs.");
            return;
        }

        try {
            const response = await fetch("actions/preteur_emprunts_action.php?action=create_emprunt", {
                method: "POST",
                body: formData,
            });
            const data = await response.json();

            if (data.success) {
                alert(data.success);
                loadEnCours();
            } else {
                alert(data.error || "Une erreur est survenue.");
            }
        } catch (error) {
            console.error("Erreur lors de la création de l'emprunt :", error);
            alert("Une erreur est survenue lors de la communication avec le serveur.");
        }
    });

    // Gérer l'ouverture du modal de restitution
    document.addEventListener("click", (e) => {
        if (e.target.classList.contains("open-restitution-modal")) {
            const empruntId = e.target.dataset.id;
            const objectName = e.target.dataset.objectName;
            const quantity = e.target.dataset.quantity;
            const state = e.target.dataset.state;

            document.getElementById("empruntId").value = empruntId;
            document.getElementById("restitutionQuantity").value = quantity;
            document.getElementById("restitutionState").value = state;

            const today = new Date().toISOString().split("T")[0];
            document.getElementById("restitutionDate").value = today;

            restitutionModal.show();
        }
    });

    // Soumettre la restitution
    restitutionForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        const formData = new FormData();
        formData.append("action", "restitute");
        formData.append("emprunt_id", document.getElementById("empruntId").value);
        formData.append("quantity", document.getElementById("restitutionQuantity").value);
        formData.append("state", document.getElementById("restitutionState").value);
        formData.append("date", document.getElementById("restitutionDate").value);

        try {
            const response = await fetch("actions/preteur_emprunts_action.php", {
                method: "POST",
                body: formData,
            });

            const data = await response.json();

            if (data.success) {
                alert(data.success);
                restitutionModal.hide();
                loadEnCours();
                loadPasses();
            } else {
                alert(data.error || "Une erreur est survenue.");
            }
        } catch (error) {
            console.error("Erreur lors de la restitution :", error);
            alert("Une erreur est survenue lors de la communication avec le serveur.");
        }
    });

    // Initialisation
    loadEnCours();
    loadPasses();
    loadObjects();
    loadEmprunteurs();
});
