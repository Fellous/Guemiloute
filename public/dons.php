<?php 
ob_start();
session_start();

$pageTitle = "Dons - Guemiloute";
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$isAdmin = ($_SESSION['role'] ?? '') === 'admin';

include 'includes/header.php';
ob_end_flush();
?>
<link rel="stylesheet" href="assets/css/dons.css">

<div class="container my-5">
    <h2 class="text-center mb-4"><i class="bi bi-gift"></i> Gestion des Dons</h2>

    <!-- Onglets -->
    <ul class="nav nav-tabs" id="donTabs">
        <li class="nav-item">
            <button class="nav-link active" id="disponibles-tab" data-bs-toggle="tab" data-bs-target="#disponibles">
                Dons disponibles
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="mesDons-tab" data-bs-toggle="tab" data-bs-target="#mesDons">
                Mes Dons
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="donsPasses-tab" data-bs-toggle="tab" data-bs-target="#donsPasses">
                Dons précédents
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="faireDon-tab" data-bs-toggle="tab" data-bs-target="#faireDon">
                Faire un Don
            </button>
        </li>
    </ul>

    <div class="tab-content mt-4">
        <!-- Dons disponibles -->
        <div class="tab-pane fade show active" id="disponibles">
            <div id="donDisponibleContainer"></div>
        </div>

        <!-- Mes Dons -->
        <div class="tab-pane fade" id="mesDons">
            <div id="mesDonsContainer"></div>
        </div>

        <!-- Dons précédents -->
        <div class="tab-pane fade" id="donsPasses">
            <div id="donsPassesContainer"></div>
        </div>

        <!-- Formulaire pour créer un Don -->
        <div class="tab-pane fade" id="faireDon">
            <div class="p-4">
                <h3 class="mb-3">Publier un Don</h3>
                <form id="donForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="title" class="form-label">Nom de l'objet</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="photos" class="form-label">Photos de l'objet</label>
                        <input type="file" class="form-control" id="photos" name="photos[]" accept="image/*" multiple required>
                    </div>
                    <button type="submit" class="btn btn-success">Publier mon Don</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/dons.js"></script>
<?php include 'includes/footer.php'; ?>
