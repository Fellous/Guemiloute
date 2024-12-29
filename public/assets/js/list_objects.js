// Charger les objets dynamiquement
async function loadObjects(filters = {}) {
    const queryString = new URLSearchParams(filters).toString();
    const response = await fetch(`../actions/list_objects_action.php?${queryString}`);
    const objects = await response.json();

    const container = document.getElementById("objectsContainer");
    container.innerHTML = objects.map((obj) => {
        const imageUrl = obj.image_url
            ? `../assets/uploads/objects/${obj.image_url}`
            : '../assets/images/default.jpg';

        return `
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm">
                <img src="${imageUrl}" class="card-img-top" alt="${obj.name}">
                <div class="card-body">
                    <h5 class="card-title">${obj.name}</h5>
                    <p class="card-text">Quantité : ${obj.quantity}</p>
                    <p class="card-text">Catégorie : ${obj.category_name || 'Non classée'}</p>
                    <p class="card-text">
                        ${obj.preteur_name 
                            ? `Attribué à : ${obj.preteur_name}` 
                            : `<button class="btn btn-warning btn-sm attribute-btn" data-id="${obj.id}">Attribuer</button>`}
                    </p>
                </div>
            </div>
        </div>
        `;
    }).join('');

    // Ajouter des événements sur les boutons "Attribuer"
    const attributeButtons = document.querySelectorAll(".attribute-btn");
    attributeButtons.forEach(button => {
        button.addEventListener("click", async function () {
            const objectId = this.dataset.id;
            document.getElementById("objectId").value = objectId;

            // Charger les prêteurs dans le sélecteur
            const response = await fetch("../actions/get_preteurs_action.php");
            const preteurs = await response.json();

            const preteurSelect = document.getElementById("preteurSelect");
            preteurSelect.innerHTML = '<option value="">-- Sélectionnez un prêteur --</option>';
            preteurs.forEach(preteur => {
                const option = document.createElement("option");
                option.value = preteur.id;
                option.textContent = preteur.username;
                preteurSelect.appendChild(option);
            });

            // Ouvrir le modal
            const modal = new bootstrap.Modal(document.getElementById("attributeModal"));
            modal.show();
        });
    });
}

// Fonction pour gérer les filtres dynamiquement
function setupFilters() {
    const categoryFilter = document.getElementById("category");
    const attributedFilter = document.getElementById("attributed");

    const updateFilters = () => {
        const filters = {
            category_id: categoryFilter.value,
            attribute: attributedFilter.value,
        };
        loadObjects(filters);
    };

    categoryFilter.addEventListener("change", updateFilters);
    attributedFilter.addEventListener("change", updateFilters);
}

// Charger initialement tous les objets
loadObjects();

// Initialiser les filtres
setupFilters();
