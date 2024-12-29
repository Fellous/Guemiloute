// Gérer la soumission du formulaire d'édition de profil
document.getElementById("editProfileForm").addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const response = await fetch("actions/update_profile.php", {
        method: "POST",
        body: formData,
    });

    const result = await response.text();
    const feedback = document.getElementById("feedback");
    feedback.innerText = result;

    feedback.className = response.ok ? "alert alert-success" : "alert alert-danger";
});
