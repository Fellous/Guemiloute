<?php
// commit name: separated-login-page
// - Page front : affiche un formulaire de connexion
// - Redirige l'action vers actions/login_action.php

$pageTitle = "Connexion - Guemiloute";
include 'includes/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4">Connexion</h2>

    <?php
    // On peut récupérer un message depuis l'URL
    if (!empty($_GET['msg'])) {
        echo '<div class="alert alert-info">'.htmlspecialchars($_GET['msg']).'</div>';
    }
    ?>

    <form action="actions/login_action.php" method="post">
        <div class="mb-3">
            <label for="email" class="form-label">Email :</label>
            <input type="email" name="email" id="email" class="form-control" required />
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe :</label>
            <input type="password" name="password" id="password" class="form-control" required />
        </div>
        <button type="submit" class="btn btn-primary">Se connecter</button>
        <a href="register.php" class="btn btn-link">Pas de compte ? S’inscrire</a>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
