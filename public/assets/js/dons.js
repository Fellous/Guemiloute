document.addEventListener("DOMContentLoaded", function () {
    function labelStatus(status) {
        switch(status) {
            case 'reserve': return 'Réservé';
            case 'donne':   return 'Donné';
            case 'annule':  return 'Annulé';
            default:        return 'Disponible';
        }
    }

    function loadDons() {
        fetch("actions/don_action.php?action=get_dons")
            .then(res => res.json())
            .then(data => {
                console.log("Dons chargés :", data);
                
                const currentUserId = document.body.getAttribute('data-user-id');
                let donDisponibleHTML = "";
                let mesDonsHTML = "";
                let donsPassesHTML = "";

                data.forEach(don => {
                    let images = [];
                    try {
                        images = JSON.parse(don.image_url);
                    } catch (e) {
                        console.error("Erreur lors du parsing des images:", e);
                    }
                    let displayImage = (images.length > 0 && images[0] !== "") ? images[0] : 'default.jpg';

                    // Si le don n'appartient pas à l'utilisateur connecté et est disponible
                    if (!(currentUserId && parseInt(currentUserId) === parseInt(don.user_id)) && don.reservation_status === "disponible") {
                        donDisponibleHTML += `
                            <a href="don_detail.php?id=${don.id}" style="text-decoration:none; color:inherit;">
                            <div class="card mb-3">
                                <img src="assets/uploads/${displayImage}" class="card-img-top" alt="${don.title}">
                                <div class="card-body">
                                    <h5 class="card-title">${don.title}</h5>
                                    <p class="card-text">${don.description}</p>
                                    <button class="btn btn-primary reserve-btn" data-id="${don.id}">Réserver</button>
                                </div>
                            </div>
                            </a>
                        `;
                    }

                    // Si le don appartient à l'utilisateur connecté
                    if (currentUserId && parseInt(currentUserId) === parseInt(don.user_id)) {
                        if (don.reservation_status === "donne") {
                            donsPassesHTML += `
                                <a href="don_detail.php?id=${don.id}" style="text-decoration:none; color:inherit;">
                                <div class="card mb-3">
                                    <img src="assets/uploads/${displayImage}" class="card-img-top" alt="${don.title}">
                                    <div class="card-body">
                                        <h5 class="card-title">${don.title}</h5>
                                        <p class="card-text">${don.description}</p>
                                        <span class="badge bg-warning">${labelStatus(don.reservation_status)}</span>
                                        <button class="btn btn-danger delete-btn mt-2" data-id="${don.id}">Supprimer</button>
                                    </div>
                                </div>
                                </a>
                            `;
                        } else {
                            mesDonsHTML += `
                                <a href="don_detail.php?id=${don.id}" style="text-decoration:none; color:inherit;">
                                <div class="card mb-3">
                                    <img src="assets/uploads/${displayImage}" class="card-img-top" alt="${don.title}">
                                    <div class="card-body">
                                        <h5 class="card-title">${don.title}</h5>
                                        <p class="card-text">${don.description}</p>
                                        ${
                                          don.reservation_status === "disponible" 
                                          ? `<button class="btn btn-primary reserve-btn" data-id="${don.id}">Réserver</button>`
                                          : `<span class="badge bg-warning">${labelStatus(don.reservation_status)}</span>`
                                        }
                                        <button class="btn btn-danger delete-btn mt-2" data-id="${don.id}">Supprimer</button>
                                        ${
                                          don.reservation_status === "reserve" 
                                          ? `<button class="btn btn-success archive-btn mt-2" data-id="${don.id}">Archiver</button>`
                                          : ``
                                        }
                                    </div>
                                </div>
                                </a>
                            `;
                        }
                    }
                });

                document.getElementById("donDisponibleContainer").innerHTML = donDisponibleHTML;
                document.getElementById("mesDonsContainer").innerHTML = mesDonsHTML;
                document.getElementById("donsPassesContainer").innerHTML = donsPassesHTML;

                attachReserveEvents();
                attachCancelEvents();
                attachDeleteEvents();
                attachArchiveEvents();
            })
            .catch(error => console.error("Erreur lors du chargement des dons :", error));
    }

    function attachReserveEvents() {
        document.querySelectorAll(".reserve-btn").forEach(button => {
            button.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                const donId = this.getAttribute("data-id");
                if (confirm("Confirmer la réservation ?")) {
                    let formData = new FormData();
                    formData.append("don_id", donId);
                    fetch("actions/don_action.php?action=reserve_don", {
                        method: "POST",
                        body: formData
                    })
                    .then(response => response.text())
                    .then(raw => {
                        try {
                            let data = JSON.parse(raw);
                            if (data.success) {
                                alert("Réservation effectuée");
                                loadDons();
                            } else {
                                alert("Erreur: " + data.error);
                            }
                        } catch (e) {
                            console.error("Réponse invalide:", raw);
                            alert("Erreur lors de la communication avec le serveur.");
                        }
                    })
                    .catch(err => console.error(err));
                }
            });
        });
    }

    function attachCancelEvents() {
        document.querySelectorAll(".cancel-btn").forEach(button => {
            button.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                const donId = this.getAttribute("data-id");
                if (confirm("Voulez-vous annuler votre réservation ?")) {
                    let formData = new FormData();
                    formData.append("don_id", donId);
                    fetch("actions/don_action.php?action=cancel_reservation", {
                        method: "POST",
                        body: formData
                    })
                    .then(response => response.text())
                    .then(raw => {
                        try {
                            let data = JSON.parse(raw);
                            if (data.success) {
                                alert("Réservation annulée");
                                loadDons();
                            } else {
                                alert("Erreur: " + data.error);
                            }
                        } catch (e) {
                            console.error("Réponse invalide:", raw);
                            alert("Erreur lors de la communication avec le serveur.");
                        }
                    })
                    .catch(err => console.error(err));
                }
            });
        });
    }

    function attachDeleteEvents() {
        document.querySelectorAll(".delete-btn").forEach(button => {
            button.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                const donId = this.getAttribute("data-id");
                if (confirm("Voulez-vous vraiment supprimer ce don ?")) {
                    let formData = new FormData();
                    formData.append("don_id", donId);
                    fetch("actions/don_action.php?action=delete_don", {
                        method: "POST",
                        body: formData
                    })
                    .then(response => response.text())
                    .then(raw => {
                        try {
                            let data = JSON.parse(raw);
                            if (data.success) {
                                alert("Don supprimé");
                                loadDons();
                            } else {
                                alert("Erreur: " + data.error);
                            }
                        } catch (e) {
                            console.error("Réponse invalide:", raw);
                            alert("Erreur lors de la communication avec le serveur.");
                        }
                    })
                    .catch(err => console.error(err));
                }
            });
        });
    }

    function attachArchiveEvents() {
        document.querySelectorAll(".archive-btn").forEach(button => {
            button.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                const donId = this.getAttribute("data-id");
                if (confirm("Confirmer l'archivage de ce don ?")) {
                    let formData = new FormData();
                    formData.append("don_id", donId);
                    fetch("actions/don_action.php?action=archive_don", {
                        method: "POST",
                        body: formData
                    })
                    .then(response => response.text())
                    .then(raw => {
                        try {
                            let data = JSON.parse(raw);
                            if (data.success) {
                                alert("Don archivé");
                                loadDons();
                            } else {
                                alert("Erreur: " + data.error);
                            }
                        } catch (e) {
                            console.error("Réponse invalide:", raw);
                            alert("Erreur lors de la communication avec le serveur.");
                        }
                    })
                    .catch(err => console.error(err));
                }
            });
        });
    }

    document.getElementById("donForm").addEventListener("submit", function (event) {
        event.preventDefault();
        let formData = new FormData(this);

        fetch("actions/don_action.php?action=create_don", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(raw => {
            try {
                let jsonData = JSON.parse(raw);
                if (jsonData.success) {
                    alert("Votre don a été publié !");
                    document.getElementById("donForm").reset();
                    loadDons();
                } else {
                    alert("Erreur : " + jsonData.error);
                }
            } catch (e) {
                console.error("Réponse invalide (non JSON) :", raw);
                alert("Erreur lors de la communication avec le serveur. Vérifiez la console.");
            }
        })
        .catch(error => console.error("Erreur AJAX :", error));
    });

    loadDons();
});
