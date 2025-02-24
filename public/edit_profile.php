<?php
// commit name: fix-edit-profile-for-all-users
// - Permet à tout utilisateur connecté d'éditer son profil
session_start();

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?msg=Veuillez vous connecter");
    exit;
}

$pageTitle = "Édition du profil";
require_once 'includes/header.php';
require_once 'db.php';

// Récupérer les informations actuelles de l'utilisateur connecté
$sql = "SELECT address, phone, city, additional_info, availability 
        FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Initialiser les disponibilités si aucune n'est définie
$availability = json_decode($user['availability'] ?? '[]', true);
?>
<div class="container my-5">
    <h2 class="mb-4">Édition du profil</h2>
    <form id="editProfileForm">
        <!-- Adresse -->
        <div class="mb-3">
            <label for="address" class="form-label">Adresse</label>
            <input type="text" class="form-control" id="address" name="address" value="<?= htmlspecialchars($user['address'] ?? '') ?>" required>
        </div>
        <!-- Ville -->
        <div class="mb-3">
            <label for="city" class="form-label">Ville</label>
            <input type="text" class="form-control" id="city" name="city" value="<?= htmlspecialchars($user['city'] ?? '') ?>" required>
        </div>
        <!-- Téléphone -->
        <div class="mb-3">
            <label for="phone" class="form-label">Téléphone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required>
        </div>
        <!-- Infos supplémentaires -->
        <div class="mb-3">
            <label for="additional_info" class="form-label">Indications supplémentaires (ex : étage, porte, etc.)</label>
            <textarea class="form-control" id="additional_info" name="additional_info" rows="3"><?= htmlspecialchars($user['additional_info'] ?? '') ?></textarea>
        </div>
        <!-- Disponibilités -->
        <div class="mb-3">
            <label class="form-label">Disponibilités</label>
            <?php 
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            foreach ($days as $day): 
            ?>
                <div class="row mb-2">
                    <div class="col-4">
                        <strong><?= $day ?></strong>
                    </div>
                    <div class="col-8">
                        <input type="text" class="form-control" name="availability[<?= $day ?>]" placeholder="ex : 10:00-18:00" value="<?= htmlspecialchars($availability[$day] ?? '') ?>">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- Bouton -->
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
    <div id="feedback" class="mt-3"></div>
</div>
<script src="assets/js/edit_profile.js"></script>
<?php include 'includes/footer.php'; ?>
