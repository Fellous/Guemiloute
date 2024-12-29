<?php
// commit name: separated-register-page
// - Page front : n'affiche que le formulaire
// - Pas de logique d'insertion dans cette page
// - On récupère un éventuel message d'erreur via GET

$pageTitle = "Inscription - Guemiloute";
include 'includes/header.php'; // Va appeler session_start() si besoin
?>

<div class="container my-5">
    <h2 class="mb-4">Inscription</h2>

    <?php
    // On peut récupérer un message depuis l'URL
    if (!empty($_GET['msg'])) {
        echo '<div class="alert alert-danger">'.htmlspecialchars($_GET['msg']).'</div>';
    }
    ?>

    <!-- Le formulaire pointe vers notre script back -->
    <form action="../actions/register_action.php" method="post">
        <div class="mb-3">
            <label for="username" class="form-label">Nom d'utilisateur :</label>
            <input type="text" name="username" id="username" class="form-control" required />
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email :</label>
            <input type="email" name="email" id="email" class="form-control" required />
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe :</label>
            <input type="password" name="password" id="password" class="form-control" required />
        </div>
        <button type="submit" class="btn btn-primary">Créer un compte</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
