<?php
// commit name: admin-create-object-page
// - Page frontend pour créer un objet (admin uniquement)
// - Formulaire avec AJAX pour le traitement
$pageTitle = "Création d'un objet - Admin";
include '../includes/header.php';

// Vérification de l'accès admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../index.php?msg=Accès refusé");
    exit;
}

// Connexion à la BDD
require_once '../db.php';

// Récupérer la liste des prêteurs pour le sélecteur
$sql = "SELECT id, last_name FROM users WHERE role = 'preteur' ORDER BY last_name ASC";
$stmt = $pdo->query($sql);
$preteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container my-5">
    <h2 class="mb-4">Créer un nouvel objet</h2>
    <form id="createObjectForm" enctype="multipart/form-data">
        <!-- Nom de l'objet -->
        <div class="mb-3">
            <label for="name" class="form-label">Nom de l'objet</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>

        <!-- Catégorie -->
        <div class="mb-3">
            <label for="category" class="form-label">Catégorie</label>
            <select class="form-select" id="category" name="category" required>
                <option value="">Sélectionnez une catégorie</option>
                <?php
                $categories = $pdo->query("SELECT id, name FROM categories")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($categories as $cat):
                ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- État -->
        <div class="mb-3">
            <label for="state" class="form-label">État</label>
            <select class="form-select" id="state" name="state" required>
                <option value="neuf">Neuf</option>
                <option value="comme neuf">Comme neuf</option>
                <option value="bien">Bien</option>
                <option value="moyen">Moyen</option>
                <option value="mauvaise état">Mauvais état</option>
            </select>
        </div>

        <!-- Quantité -->
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantité</label>
            <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
        </div>

        <!-- Prêteur -->
        <div class="mb-3">
            <label for="preteur" class="form-label">Attribuer à un prêteur (optionnel)</label>
            <select class="form-select" id="preteur" name="preteur">
                <option value="">Non attribué</option>
                <?php foreach ($preteurs as $preteur): ?>
                    <option value="<?= $preteur['id'] ?>"><?= htmlspecialchars($preteur['last_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Images -->
        <div class="mb-3">
            <label for="images" class="form-label">Images de l'objet</label>
            <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
        </div>

        <!-- Bouton -->
        <button type="submit" class="btn btn-primary">Créer l'objet</button>
    </form>

    <div id="feedback" class="mt-3"></div>
</div>

<script src="../assets/js/create_object.js"></script>
<?php include '../includes/footer.php'; ?>
