// commit name: object-details-specific-script
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('objectDetailsPage');
    const objectId = new URLSearchParams(window.location.search).get('id');

    if (!objectId) {
        container.innerHTML = "<p class='text-danger'>Aucun ID valide fourni pour cet objet.</p>";
        return;
    }

    fetch(`actions/object_details_action.php?id=${objectId}`)
        .then((response) => response.json())
        .then((data) => {
            if (data.error) {
                container.innerHTML = `<p class='text-danger'>${data.error}</p>`;
                return;
            }

            const { object, images } = data;

            container.innerHTML = `
                <div class="row">
                    <!-- Section image -->
                    <div class="col-md-6">
                        ${images.length ? `
                        <div id="objectDetailsCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                ${images.map((image, index) => `
                                    <div class="carousel-item ${index === 0 ? 'active' : ''}">
                                        <img src="assets/uploads/objects/${image.image_url}" class="d-block w-100" alt="${object.object_name}">
                                    </div>
                                `).join('')}
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#objectDetailsCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Précédent</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#objectDetailsCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Suivant</span>
                            </button>
                        </div>` : `<p>Aucune image disponible pour cet objet.</p>`}
                    </div>
                    
                    <!-- Section détails -->
                    <div class="col-md-6">
                        <h1>${object.object_name}</h1>
                        <p><strong>Catégorie :</strong> ${object.category_name || 'Non classée'}</p>
                        <p><strong>Description :</strong> ${object.description || 'Non disponible'}</p>
                        <p><strong>État :</strong> ${object.state}</p>
                        <p><strong>Quantité disponible :</strong> ${object.quantity}</p>
                        <p><strong>Adresse de l'objet :</strong> ${object.object_address || 'Non fournie'}, ${object.object_city || 'Non fournie'}</p>
                    </div>
                </div>
                <hr>
                <!-- Section prêteur -->
                <div id="preteurSection">
                    <h2>Informations sur le prêteur</h2>
                    <p><strong>Nom :</strong> ${object.preteur_name}</p>
                    <p><strong>Téléphone :</strong> ${object.preteur_phone || 'Non fourni'}</p>
                    <p><strong>Adresse :</strong> ${object.preteur_address || 'Non fournie'}, ${object.preteur_city || 'Non fournie'}</p>
                </div>
            `;
        })
        .catch(() => {
            container.innerHTML = "<p class='text-danger'>Erreur lors du chargement des détails de l'objet.</p>";
        });
});
