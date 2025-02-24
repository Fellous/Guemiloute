<?php
// commit name: separated-register-page-updated
$pageTitle = "Inscription - Guemiloute";
include 'includes/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4">Inscription</h2>
    <?php
    if (!empty($_GET['msg'])) {
        echo '<div class="alert alert-danger">'.htmlspecialchars($_GET['msg']).'</div>';
    }
    ?>
    <form action="../actions/register_action.php" method="post">
        <div class="mb-3">
            <label for="first_name" class="form-label">Prénom :</label>
            <input type="text" name="first_name" id="first_name" class="form-control" required />
        </div>
        <div class="mb-3">
            <label for="last_name" class="form-label">Nom :</label>
            <input type="text" name="last_name" id="last_name" class="form-control" required />
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email :</label>
            <input type="email" name="email" id="email" class="form-control" required />
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Téléphone :</label>
            <input type="text" name="phone" id="phone" class="form-control" required />
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Adresse :</label>
            <input type="text" name="address" id="address" class="form-control" required />
        </div>
        <div class="mb-3">
            <label for="city" class="form-label">Ville :</label>
            <input type="text" name="city" id="city" class="form-control" required />
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe :</label>
            <input type="password" name="password" id="password" class="form-control" required />
        </div>
        <button type="submit" class="btn btn-primary">Créer un compte</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
