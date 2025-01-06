<?php
// commit name: frosted-glass-login
// - Ajout d'une carte effet glace pour le formulaire de connexion
// - Couleurs et style adaptés à l'arrière-plan animé existant

$pageTitle = "Connexion - Guemiloute";
include 'includes/header.php';
?>

<div class="login-container">
    <div class="login-card">
        <h2 class="login-title">Connexion</h2>

        <?php
        // Affichage d'un message d'information, si présent dans l'URL
        if (!empty($_GET['msg'])) {
            echo '<div class="alert alert-info">'.htmlspecialchars($_GET['msg']).'</div>';
        }
        ?>

        <form action="actions/login_action.php" method="post">
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Entrez votre email" required />
            </div>
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Entrez votre mot de passe" required />
            </div>
            <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
            <p class="text-center mt-3">
                Pas de compte ? <a href="register.php">S’inscrire</a>
            </p>
            
        </form>
    </div>
</div>

<link rel="stylesheet" href="assets/css/login.css">
<?php include 'includes/footer.php'; ?>
