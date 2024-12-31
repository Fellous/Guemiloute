<?php
// commit name: catalog-page-with-ajax-filters

$pageTitle = "Catalogue d'objets";
include 'includes/header.php';
require_once 'db.php';

// Récupérer les catégories pour les filtres
$categories = $pdo->query("SELECT id, name FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-5">
    <h1 class="mb-4 text-center">Catalogue d'objets</h1>
    
    <!-- Filtres -->
    <div class="row mb-4">
        <div class="col-md-3">
            <label for="categoryFilter" class="form-label">Catégorie</label>
            <select id="categoryFilter" class="form-select">
                <option value="">Toutes les catégories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category['id']) ?>"><?= htmlspecialchars($category['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label for="stateFilter" class="form-label">État</label>
            <select id="stateFilter" class="form-select">
                <option value="">Tous les états</option>
                <option value="neuf">Neuf</option>
                <option value="bon">Bon</option>
                <option value="moyen">Moyen</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="availabilityFilter" class="form-label">Disponibilité</label>
            <select id="availabilityFilter" class="form-select">
                <option value="">Toutes</option>
                <option value="1">Disponible</option>
                <option value="0">En cours d'emprunt</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="searchFilter" class="form-label">Recherche</label>
            <input type="text" id="searchFilter" class="form-control" placeholder="Nom de l'objet">
        </div>
    </div>

    <!-- Liste des objets -->
    <div id="catalogContainer" class="row g-4">
        <!-- Les objets seront chargés ici par AJAX -->
    </div>
</div>

<script src="assets/js/catalog.js"></script>

<?php include 'includes/footer.php'; ?>
