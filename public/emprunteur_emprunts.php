<?php
session_start();

// // Débogage des informations de session
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";

// Vérification de connexion et rôle
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'emprunteur') {
    header("Location: login.php?msg=Accès refusé.");
    exit;
}

$pageTitle = "Mes emprunts - Guemiloute";
include 'includes/header.php';
?>


<div class="container my-5">
    <h1 class="mb-4">Gestion de mes emprunts</h1>

    <!-- Onglets -->
    <ul class="nav nav-tabs" id="emprunteurTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="enCours-tab" data-bs-toggle="tab" data-bs-target="#enCours" type="button" role="tab">
                Emprunts en cours
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="passes-tab" data-bs-toggle="tab" data-bs-target="#passes" type="button" role="tab">
                Emprunts passés
            </button>
        </li>
    </ul>

    <div class="tab-content mt-4">
        <!-- Emprunts en cours -->
        <div class="tab-pane fade show active" id="enCours" role="tabpanel">
            <div id="enCoursContainer"></div>
        </div>

        <!-- Emprunts passés -->
        <div class="tab-pane fade" id="passes" role="tabpanel">
            <div id="passesContainer"></div>
        </div>
    </div>
</div>

<script src="assets/js/emprunteur_emprunts.js"></script>
<link rel="stylesheet" href="assets/css/emprunteur_emprunts.css">

<?php include 'includes/footer.php'; ?>
