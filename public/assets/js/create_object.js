document.getElementById("createObjectForm").addEventListener("submit", async function (e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    const response = await fetch("../../actions/create_object_action.php", {
        method: "POST",
        body: formData,
    });

    const feedback = document.getElementById("feedback");
    if (response.ok) {
        feedback.innerHTML = '<div class="alert alert-success">Objet créé avec succès !</div>';
        form.reset();
    } else {
        const errorText = await response.text();
        feedback.innerHTML = `<div class="alert alert-danger">${errorText}</div>`;
    }
});
