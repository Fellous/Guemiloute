<?php
session_start();
header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(["error" => "Accès refusé"]);
    exit;
}

require_once '../db.php';
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get_dons':
        getDons($pdo);
        break;
    case 'create_don':
        createDon($pdo);
        break;
    case 'reserve_don':
        reserveDon($pdo);
        break;
    case 'confirm_don':
        confirmDon($pdo);
        break;
    case 'cancel_reservation':
        cancelReservation($pdo);
        break;
    case 'delete_don':
        deleteDon($pdo);
        break;
    case 'archive_don':
        archiveDon($pdo);
        break;
    case 'get_don_details':
        getDonDetails($pdo);
        break;
    default:
        http_response_code(400);
        echo json_encode(["error" => "Action non valide"]);
        break;
}

// Récupérer tous les dons (pour tous les utilisateurs)
function getDons($pdo) {
    $stmt = $pdo->query("
        SELECT a.id, a.user_id, a.title, a.description, a.image_url, a.reservation_status, a.reserved_by, a.reserved_at, u.last_name AS reserver_name
        FROM annonces a
        LEFT JOIN users u ON a.reserved_by = u.id
        WHERE a.type = 'don'
        ORDER BY a.created_at DESC
    ");
    $dons = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($dons);
}

// Récupérer les détails d'un don
function getDonDetails($pdo) {
    $don_id = $_GET['id'] ?? null;
    if (!$don_id) {
        http_response_code(400);
        echo json_encode(["error" => "Don non spécifié"]);
        exit;
    }
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
        http_response_code(404);
        echo json_encode(["error" => "Don introuvable"]);
        exit;
    }
    echo json_encode($don);
}

// Créer une annonce de don avec multi-upload
function createDon($pdo) {
    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';

    if (empty($title) || empty($description)) {
        echo json_encode(["error" => "Tous les champs sont requis"]);
        exit;
    }

    $imagePaths = [];
    if (!empty($_FILES['photos']['name'][0])) {
        $uploadDir = '../assets/uploads/';
        foreach ($_FILES['photos']['name'] as $index => $fileName) {
            $photoName = time() . '_' . basename($fileName);
            if (move_uploaded_file($_FILES['photos']['tmp_name'][$index], $uploadDir . $photoName)) {
                $imagePaths[] = $photoName;
            }
        }
    }
    $imagesJson = json_encode($imagePaths);

    $stmt = $pdo->prepare("
        INSERT INTO annonces (user_id, type, title, description, image_url, reservation_status)
        VALUES (?, 'don', ?, ?, ?, 'disponible')
    ");
    $stmt->execute([$user_id, $title, $description, $imagesJson]);

    echo json_encode(["success" => "Don ajouté avec succès"]);
}

// Réserver un don (vérification du profil complet)
function reserveDon($pdo) {
    $don_id = $_POST['don_id'] ?? null;
    $user_id = $_SESSION['user_id'];

    if (!$don_id) {
        echo json_encode(["error" => "Donnée invalide"]);
        exit;
    }

    // Vérifier que l'utilisateur a complété son profil (champ 'phone' non vide)
    $stmtUser = $pdo->prepare("SELECT phone FROM users WHERE id = ?");
    $stmtUser->execute([$user_id]);
    $userProfile = $stmtUser->fetch(PDO::FETCH_ASSOC);
    if (empty($userProfile['phone'])) {
        echo json_encode(["error" => "Veuillez compléter votre profil avec un numéro de téléphone pour réserver un don."]);
        exit;
    }

    $stmt = $pdo->prepare("
        UPDATE annonces
        SET reserved_by = ?, reservation_status = 'reserve', reserved_at = NOW()
        WHERE id = ? AND reservation_status = 'disponible'
    ");
    $stmt->execute([$user_id, $don_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => "Réservation effectuée"]);
    } else {
        echo json_encode(["error" => "Cet objet n'est plus disponible"]);
    }
}

// Confirmer un don (archiver)
function confirmDon($pdo) {
    $don_id = $_POST['don_id'] ?? null;
    if (!$don_id) {
        echo json_encode(["error" => "Donnée invalide"]);
        exit;
    }
    $stmtCheck = $pdo->prepare("
        SELECT reserved_by FROM annonces
        WHERE id = ? AND reservation_status = 'reserve'
    ");
    $stmtCheck->execute([$don_id]);
    if ($stmtCheck->rowCount() == 0) {
        echo json_encode(["error" => "Ce don n'est pas réservé"]);
        exit;
    }
    $stmt = $pdo->prepare("
        UPDATE annonces
        SET reservation_status = 'donne'
        WHERE id = ?
    ");
    $stmt->execute([$don_id]);
    echo json_encode(["success" => "Don confirmé et archivé"]);
}

// Annuler une réservation
function cancelReservation($pdo) {
    $don_id = $_POST['don_id'] ?? null;
    if (!$don_id) {
        echo json_encode(["error" => "Donnée invalide"]);
        exit;
    }
    $stmt = $pdo->prepare("
        UPDATE annonces
        SET reserved_by = NULL, reservation_status = 'disponible', reserved_at = NULL
        WHERE id = ? AND reservation_status = 'reserve'
    ");
    $stmt->execute([$don_id]);
    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => "Réservation annulée"]);
    } else {
        echo json_encode(["error" => "Aucune réservation en cours"]);
    }
}

// Archiver un don (bouton "Archiver")
function archiveDon($pdo) {
    $don_id = $_POST['don_id'] ?? null;
    if (!$don_id) {
        echo json_encode(["error" => "Donnée invalide"]);
        exit;
    }
    $stmt = $pdo->prepare("
        UPDATE annonces
        SET reservation_status = 'donne'
        WHERE id = ?
    ");
    $stmt->execute([$don_id]);
    echo json_encode(["success" => "Don archivé"]);
}

// Supprimer un don (seulement par le propriétaire ou admin)
function deleteDon($pdo) {
    $don_id = $_POST['don_id'] ?? null;
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['role'];

    if (!$don_id) {
        echo json_encode(["error" => "Donnée invalide"]);
        exit;
    }

    $stmtCheck = $pdo->prepare("
        SELECT user_id, image_url FROM annonces
        WHERE id = ?
    ");
    $stmtCheck->execute([$don_id]);
    $don = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if (!$don || ($don['user_id'] != $user_id && $role !== 'admin')) {
        echo json_encode(["error" => "Vous n'avez pas l'autorisation de supprimer ce don"]);
        exit;
    }

    if (!empty($don['image_url'])) {
        $images = json_decode($don['image_url'], true);
        if (is_array($images)) {
            foreach ($images as $img) {
                @unlink("../assets/uploads/" . $img);
            }
        }
    }

    $stmt = $pdo->prepare("DELETE FROM annonces WHERE id = ?");
    $stmt->execute([$don_id]);
    echo json_encode(["success" => "Don supprimé"]);
}
