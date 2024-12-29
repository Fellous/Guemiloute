<?php
// commit name: admin-create-category-page
// - Page frontend pour créer/modifier des catégories
// - Accessible uniquement aux admins

$pageTitle = "Gestion des catégories - Admin";
include '../includes/header.php';

// Vérifier l'accès admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../index.php?msg=Accès refusé");
    exit;
}

// Connexion à la BDD
require_once '../db.php';

// Récupérer les catégories existantes
$sql = "SELECT * FROM categories ORDER BY id ASC";
$stmt = $pdo->query($sql);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container my-5">
    <h2 class="mb-4">Gestion des catégories</h2>

    <!-- Formulaire pour ajouter une catégorie -->
    <form id="createCategoryForm">
        <div class="mb-3">
            <label for="categoryName" class="form-label">Nom de la catégorie</label>
            <input type="text" class="form-control" id="categoryName" name="name" required>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter la catégorie</button>
    </form>

    <div id="feedback" class="mt-3"></div>

    <hr class="my-5">

    <!-- Liste des catégories -->
    <h3>Catégories existantes</h3>
    <table class="table table-bordered mt-3" id="categoriesTable">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
            <tr data-category-id="<?= $category['id'] ?>">
                <td><?= $category['id'] ?></td>
                <td class="category-name"><?= htmlspecialchars($category['name']) ?></td>
                <td>
                    <button class="btn btn-warning btn-sm edit-category">Modifier</button>
                    <button class="btn btn-danger btn-sm delete-category">Supprimer</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="../assets/js/create_category.js"></script>
<?php include '../includes/footer.php'; ?>
