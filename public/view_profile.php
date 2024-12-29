<?php
// commit name: view-profile-for-connected-user
// - Affiche les informations du profil de l'utilisateur connecté

session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?msg=Veuillez vous connecter pour accéder à votre profil.");
    exit;
}

$pageTitle = "Mon profil";
require_once 'includes/header.php';
require_once 'db.php';

// Récupérer les informations de l'utilisateur connecté
$sql = "SELECT username, email, phone, address, city, additional_info, availability 
        FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<div class='container my-5'><p class='alert alert-danger'>Impossible de charger votre profil.</p></div>";
    require_once 'includes/footer.php';
    exit;
}

// Décoder les disponibilités
$availability = json_decode($user['availability'] ?? '[]', true);
?>

<div class="container my-5">
    <h2 class="mb-4">Mon profil</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="card-title"><?= htmlspecialchars($user['username']) ?></h4>
            <p class="card-text"><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p class="card-text"><strong>Téléphone :</strong> <?= htmlspecialchars($user['phone'] ?? 'Non renseigné') ?></p>
            <p class="card-text"><strong>Adresse :</strong> <?= htmlspecialchars($user['address'] ?? 'Non renseignée') ?></p>
            <p class="card-text"><strong>Ville :</strong> <?= htmlspecialchars($user['city'] ?? 'Non renseignée') ?></p>
            <p class="card-text"><strong>Informations supplémentaires :</strong></p>
            <p class="card-text"><?= nl2br(htmlspecialchars($user['additional_info'] ?? 'Aucune information supplémentaire.')) ?></p>
            <p class="card-text"><strong>Disponibilités :</strong></p>
            <ul class="list-unstyled">
                <?php foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day): ?>
                    <li>
                        <strong><?= $day ?> :</strong>
                        <?= htmlspecialchars($availability[$day] ?? 'Non disponible') ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="mt-4">
        <a href="edit_profile.php" class="btn btn-primary">Modifier mon profil</a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
