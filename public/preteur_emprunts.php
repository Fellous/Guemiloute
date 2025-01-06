<?php
$pageTitle = "Mes objets prêtés";
require_once 'includes/header.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'preteur') {
    header("Location: login.php");
    exit;
}
?>

<div class="container my-5">
    <h1 class="mb-4">Gestion des emprunts</h1>

    <ul class="nav nav-tabs" id="preteurTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="enCours-tab" data-bs-toggle="tab" data-bs-target="#enCours" type="button" role="tab">Emprunts en cours</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="passes-tab" data-bs-toggle="tab" data-bs-target="#passes" type="button" role="tab">Emprunts terminés</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="nouveau-tab" data-bs-toggle="tab" data-bs-target="#nouveau" type="button" role="tab">Créer un emprunt</button>
        </li>
    </ul>

    <div class="tab-content mt-4">
        <!-- Emprunts en cours -->
        <div class="tab-pane fade show active" id="enCours" role="tabpanel">
            <div id="enCoursContainer"></div>
        </div>
        <!-- Emprunts terminés -->
        <div class="tab-pane fade" id="passes" role="tabpanel">
            <div id="passesContainer"></div>
        </div>
        <!-- Création d'emprunt -->
        <div class="tab-pane fade" id="nouveau" role="tabpanel">
            <form id="nouveauEmpruntForm" class="mt-4">
                <div class="mb-3">
                    <label for="objectSelect" class="form-label">Objet à prêter</label>
                    <select id="objectSelect" class="form-select" required></select>
                </div>
                <div class="mb-3">
                    <label for="emprunteurSelect" class="form-label">Emprunteur</label>
                    <select id="emprunteurSelect" class="form-select" required></select>
                </div>
                <div class="mb-3">
                    <label for="dateStart" class="form-label">Date de début</label>
                    <input type="date" id="dateStart" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="dateEnd" class="form-label">Date de fin</label> <input type="date" id="dateEnd" class="form-control" required>
                </div>
                <div class="mb-3"> <label for="quantity" class="form-label">Quantité à prêter</label> <input type="number" id="quantity" class="form-control" required min="1"> </div> <button type="submit" class="btn btn-primary">Créer l'emprunt</button>
            </form>
        </div>
    </div>

</div>
<!-- Modal de restitution -->
<div class="modal fade" id="restitutionModal" tabindex="-1" aria-labelledby="restitutionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="restitutionModalLabel">Restitution de l'objet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="restitutionForm">
                    <input type="hidden" id="empruntId">
                    <div class="mb-3">
                        <label for="restitutionQuantity" class="form-label">Quantité retournée</label>
                        <input type="number" id="restitutionQuantity" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="restitutionState" class="form-label">État retourné</label>
                        <select id="restitutionState" class="form-select">
                            <option value="neuf">Neuf</option>
                            <option value="comme neuf">Comme neuf</option>
                            <option value="bien">Bien</option>
                            <option value="moyen">Moyen</option>
                            <option value="mauvaise état">Mauvais état</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="restitutionDate" class="form-label">Date de restitution</label>
                        <input type="date" id="restitutionDate" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Valider la restitution</button>
                </form>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/emprunt.css">
<script src="assets/js/preteur_emprunts.js"></script> <?php require_once 'includes/footer.php'; ?>
