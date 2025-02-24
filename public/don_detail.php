<?php
ob_start();
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$pageTitle = "Détails du Don - Guemiloute";
require_once 'includes/header.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div class='container my-5'><div class='alert alert-danger'>Don non spécifié.</div></div>";
    require_once 'includes/footer.php';
    exit;
}

$don_id = $_GET['id'];

// Connexion à la base de données
require_once 'db.php';

$stmt = $pdo->prepare("
    SELECT a.*, u.last_name AS reserver_name, d.last_name AS donateur_name, d.phone AS donateur_phone, d.address AS donateur_address, d.city AS donateur_city
    FROM annonces a
    LEFT JOIN users u ON a.reserved_by = u.id
    LEFT JOIN users d ON a.user_id = d.id
    WHERE a.id = ?
");
$stmt->execute([$don_id]);
$don = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$don) {
    echo "<div class='container my-5'><div class='alert alert-danger'>Don introuvable.</div></div>";
    require_once 'includes/footer.php';
    exit;
}

// Décoder les images (stockées en JSON)
$images = [];
if (!empty($don['image_url'])) {
    $images = json_decode($don['image_url'], true);
}
if (!is_array($images)) {
    $images = [];
}

// Fonction de mapping pour afficher le statut avec accents
function displayStatus($status) {
    switch($status) {
        case 'reserve': return 'Réservé';
        case 'donne':   return 'Donné';
        case 'annule':  return 'Annulé';
        default:        return 'Disponible';
    }
}
?>

<link rel="stylesheet" href="assets/css/don_detail.css">

<div class="container my-5" id="donDetailPage">
    <h1 class="text-center"><?= htmlspecialchars($don['title']) ?></h1>
    <div class="row">
        <div class="col-md-6">
            <?php if (count($images) > 0): ?>
                <div id="donDetailsCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php foreach ($images as $index => $img): ?>
                            <div class="carousel-item <?= ($index === 0) ? 'active' : '' ?>">
                                <img src="assets/uploads/<?= htmlspecialchars($img) ?>" class="d-block w-100" alt="<?= htmlspecialchars($don['title']) ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#donDetailsCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Précédent</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#donDetailsCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Suivant</span>
                    </button>
                </div>
            <?php else: ?>
                <img src="assets/uploads/default.jpg" class="img-fluid" alt="Image par défaut">
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <h3>Description</h3>
            <p><?= nl2br(htmlspecialchars($don['description'])) ?></p>
            
            <h4>Statut de la réservation :</h4>
            <p>
                <?= displayStatus($don['reservation_status']) ?>
                <?php if ($don['reservation_status'] === 'reserve' && !empty($don['reserver_name'])): ?>
                    <br><small>Réservé par : <?= htmlspecialchars($don['reserver_name']) ?></small>
                    <?php if (!empty($don['reserved_at'])): ?>
                        <br><small>Le : <?= date("d/m/Y H:i", strtotime($don['reserved_at'])) ?></small>
                    <?php endif; ?>
                <?php endif; ?>
            </p>
            
            <div class="action-buttons mt-4">
                <?php if (intval($_SESSION['user_id']) === intval($don['user_id'])): ?>
                    <button class="btn btn-danger" id="deleteDonBtn" data-id="<?= $don['id'] ?>">Supprimer le don</button>
                <?php endif; ?>
                
                <?php if ($don['reservation_status'] === 'reserve' && isset($don['reserved_by']) && intval($don['reserved_by']) === intval($_SESSION['user_id'])): ?>
                    <button class="btn btn-warning" id="cancelReservationBtn" data-id="<?= $don['id'] ?>">Annuler la réservation</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <hr>
    <!-- Section pour afficher les informations du donneur -->
    <div id="donateurSection">
        <h2>Informations sur le donneur</h2>
        <p><strong>Nom :</strong> <?= htmlspecialchars($don['donateur_name'] ?? 'Non disponible') ?></p>
        <p><strong>Téléphone :</strong> <?= htmlspecialchars($don['donateur_phone'] ?? 'Non fourni') ?></p>
        <p><strong>Adresse :</strong> <?= htmlspecialchars($don['donateur_address'] ?? 'Non fournie') ?>, <?= htmlspecialchars($don['donateur_city'] ?? 'Non fournie') ?></p>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const deleteBtn = document.getElementById("deleteDonBtn");
    if(deleteBtn) {
        deleteBtn.addEventListener("click", function() {
            if(confirm("Voulez-vous vraiment supprimer ce don ?")) {
                const donId = this.getAttribute("data-id");
                let formData = new FormData();
                formData.append("don_id", donId);
                fetch("actions/don_action.php?action=delete_don", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        alert("Don supprimé");
                        window.location.href = "dons.php";
                    } else {
                        alert("Erreur: " + data.error);
                    }
                })
                .catch(err => console.error("Erreur lors de la suppression:", err));
            }
        });
    }
    
    const cancelBtn = document.getElementById("cancelReservationBtn");
    if(cancelBtn) {
        cancelBtn.addEventListener("click", function() {
            if(confirm("Voulez-vous annuler votre réservation ?")) {
                const donId = this.getAttribute("data-id");
                let formData = new FormData();
                formData.append("don_id", donId);
                fetch("actions/don_action.php?action=cancel_reservation", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        alert("Réservation annulée");
                        window.location.reload();
                    } else {
                        alert("Erreur: " + data.error);
                    }
                })
                .catch(err => console.error("Erreur lors de l'annulation:", err));
            }
        });
    }
});
</script>

<?php include 'includes/footer.php'; ?>
