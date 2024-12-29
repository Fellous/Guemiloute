// Ajouter une catégorie
document.getElementById("createCategoryForm").addEventListener("submit", async function (e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    const response = await fetch("../actions/create_category_action.php", {
        method: "POST",
        body: formData,
    });

    const feedback = document.getElementById("feedback");
    if (response.ok) {
        feedback.innerHTML = '<div class="alert alert-success">Catégorie ajoutée avec succès !</div>';
        form.reset();

        // Ajouter la catégorie au tableau dynamiquement
        const newCategory = await response.text();
        const newRow = `
        <tr>
            <td>--</td>
            <td>${formData.get("name")}</td>
            <td>
                <button class="btn btn-warning btn-sm edit-category">Modifier</button>
                <button class="btn btn-danger btn-sm delete-category">Supprimer</button>
            </td>
        </tr>`;
        document.querySelector("#categoriesTable tbody").innerHTML += newRow;
    } else {
        const errorText = await response.text();
        feedback.innerHTML = `<div class="alert alert-danger">${errorText}</div>`;
    }
});
