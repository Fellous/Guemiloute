<?php 
$pageTitle = "Liste des objets";
require_once '../includes/header.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../index.php?msg=Accès refusé");
    exit;
}

require_once '../db.php';

// Récupérer les catégories pour les filtres
$categories = $pdo->query("SELECT id, name FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container my-5">
    <h2 class="mb-4">Liste des objets</h2>

    <form id="filterForm" class="row g-3 mb-4">
        <div class="col-md-6">
            <label for="category" class="form-label">Filtrer par catégorie</label>
            <select id="category" class="form-select">
                <option value="">Toutes les catégories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category['id']) ?>"><?= htmlspecialchars($category['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label for="attributed" class="form-label">Attribué ou non attribué</label>
            <select id="attributed" class="form-select">
                <option value="">Tous</option>
                <option value="1">Attribué</option>
                <option value="0">Non attribué</option>
            </select>
        </div>
    </form>

    <div id="objectsContainer" class="row g-4">
        <!-- Les objets seront chargés ici via JavaScript -->
    </div>
</div>

<!-- Modal pour l'attribution d'un objet -->
<div class="modal fade" id="attributeModal" tabindex="-1" aria-labelledby="attributeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="attributeModalLabel">Attribuer un objet</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="attributeForm">
          <input type="hidden" id="objectId" name="object_id">
          <div class="mb-3">
            <label for="preteurSelect" class="form-label">Sélectionnez un prêteur</label>
            <select id="preteurSelect" class="form-select" required>
              <option value="">-- Sélectionnez un prêteur --</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Attribuer</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="../assets/js/list_objects.js"></script>
<?php require_once '../includes/footer.php'; ?>
