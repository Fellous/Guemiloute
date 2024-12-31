document.addEventListener('DOMContentLoaded', () => {
    const catalogContainer = document.getElementById('catalogContainer');
    const filters = {
        category: document.getElementById('categoryFilter'),
        state: document.getElementById('stateFilter'),
        availability: document.getElementById('availabilityFilter'),
        search: document.getElementById('searchFilter'),
    };

    const loadCatalog = () => {
        const params = new URLSearchParams();
        for (const key in filters) {
            if (filters[key].value) {
                params.append(key, filters[key].value);
            }
        }

        fetch(`actions/catalog_action.php?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                catalogContainer.innerHTML = data.map(object => {
                    // Construire le chemin de l'image
                    const imageUrl = object.image_url
                        ? `assets/uploads/objects/${object.image_url}`
                        : 'assets/images/default-placeholder.png';

                    return `
                        <div class="col-md-4">
                            <div class="card h-100 shadow-sm">
                                <img src="${imageUrl}" class="card-img-top" alt="${object.name}">
                                <div class="card-body">
                                    <h5 class="card-title">${object.name}</h5>
                                    <p class="card-text">Quantité disponible : ${object.quantity}</p>
                                    <a href="object_details.php?id=${object.id}" class="btn btn-primary">Voir détails</a>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');
            });
    };

    // Appliquer les filtres
    Object.values(filters).forEach(filter => filter.addEventListener('input', loadCatalog));

    loadCatalog();
});
