<?php 
$pageTitle = "Mes emprunts";
require_once 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<div class="container my-5">
    <h1 class="mb-4">Mes emprunts</h1>
    <ul class="nav nav-tabs" id="empruntTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="enCours-tab" data-bs-toggle="tab" data-bs-target="#enCours" type="button" role="tab">En cours</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="passes-tab" data-bs-toggle="tab" data-bs-target="#passes" type="button" role="tab">Passés</button>
        </li>
    </ul>

    <div class="tab-content mt-4">
        <div class="tab-pane fade show active" id="enCours" role="tabpanel">
            <div id="enCoursContainer"></div>
        </div>
        <div class="tab-pane fade" id="passes" role="tabpanel">
            <div id="passesContainer"></div>
        </div>
    </div>
</div>

<script src="assets/js/borrower_emprunts.js"></script>
<?php require_once 'includes/footer.php'; ?>
