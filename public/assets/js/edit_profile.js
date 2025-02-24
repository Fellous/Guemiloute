// Gérer la soumission du formulaire d'édition de profil
document.getElementById("editProfileForm").addEventListener("submit", async function (e) {
    e.preventDefault();
    const formData = new FormData(this);

    try {
        const response = await fetch("actions/update_profile.php", {
            method: "POST",
            body: formData,
        });
        // Récupération de la réponse sous forme de texte
        const result = await response.text();
        const feedback = document.getElementById("feedback");
        feedback.innerText = result;
        feedback.className = response.ok ? "alert alert-success" : "alert alert-danger";
    } catch (error) {
        console.error("Erreur lors de la soumission du profil :", error);
        const feedback = document.getElementById("feedback");
        feedback.innerText = "Erreur lors de la communication avec le serveur.";
        feedback.className = "alert alert-danger";
    }
});
