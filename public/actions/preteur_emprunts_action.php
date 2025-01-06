<?php
session_start();
header('Content-Type: application/json');

// Vérification de l'accès
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'preteur') {
    http_response_code(403);
    echo json_encode(["error" => "Accès refusé"]);
    exit;
}

require_once '../db.php';

// Identifier l'action
$action = $_POST['action'] ?? ($_GET['action'] ?? '');

switch ($action) {
    case 'get_en_cours':
        getEmprunts('en_cours', $pdo);
        break;
    case 'get_termines':
        getEmprunts('termine', $pdo);
        break;
    case 'get_objects':
        getObjects($pdo);
        break;
    case 'get_emprunteurs':
        getEmprunteurs($pdo);
        break;
    case 'create_emprunt':
        createEmprunt($pdo);
        break;
    case 'restitute':
        restituteEmprunt($pdo);
        break;
    default:
        http_response_code(400);
        echo json_encode(["error" => "Action non valide"]);
        break;
}

// Récupérer les emprunts
function getEmprunts($status, $pdo)
{
    $preteur_id = $_SESSION['user_id'];
    $sql = "
        SELECT e.id, o.name AS object_name, u.username AS emprunteur_name,
               e.date_start, e.date_end, e.returned_date, e.quantity_borrowed, o.state
        FROM emprunts e
        JOIN objects o ON e.object_id = o.id
        JOIN users u ON e.emprunteur_id = u.id
        WHERE e.preteur_id = :preteur_id AND e.status = :status
        ORDER BY e.date_start DESC
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['preteur_id' => $preteur_id, 'status' => $status]);
    $emprunts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($emprunts ?: ["message" => "Aucun emprunt trouvé"]);
}

// Récupérer les objets disponibles pour le prêteur
function getObjects($pdo)
{
    $preteur_id = $_SESSION['user_id'];
    $sql = "SELECT id, name, quantity FROM objects WHERE preteur_id = :preteur_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['preteur_id' => $preteur_id]);
    $objects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($objects ?: ["message" => "Aucun objet disponible"]);
}

// Récupérer tous les emprunteurs
function getEmprunteurs($pdo)
{
    $sql = "SELECT id, username FROM users WHERE role IN ('emprunteur', 'preteur') ORDER BY username ASC";
    $stmt = $pdo->query($sql);
    $emprunteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($emprunteurs ?: ["message" => "Aucun emprunteur trouvé"]);
}

// Créer un nouvel emprunt
function createEmprunt($pdo)
{
    $object_id = $_POST['object_id'] ?? null;
    $emprunteur_id = $_POST['emprunteur_id'] ?? null;
    $date_start = $_POST['date_start'] ?? null;
    $date_end = $_POST['date_end'] ?? null;
    $quantity = $_POST['quantity'] ?? null;
    $preteur_id = $_SESSION['user_id'];

    if (!$object_id || !$emprunteur_id || !$date_start || !$date_end || !$quantity) {
        http_response_code(400);
        echo json_encode(["error" => "Données manquantes"]);
        return;
    }

    $stmt = $pdo->prepare("SELECT quantity FROM objects WHERE id = :object_id AND preteur_id = :preteur_id");
    $stmt->execute(['object_id' => $object_id, 'preteur_id' => $preteur_id]);
    $object = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$object || $object['quantity'] < $quantity) {
        http_response_code(400);
        echo json_encode(["error" => "Quantité demandée non disponible"]);
        return;
    }

    $sql = "
        INSERT INTO emprunts (object_id, emprunteur_id, preteur_id, date_start, date_end, quantity_borrowed)
        VALUES (:object_id, :emprunteur_id, :preteur_id, :date_start, :date_end, :quantity)
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'object_id' => $object_id,
        'emprunteur_id' => $emprunteur_id,
        'preteur_id' => $preteur_id,
        'date_start' => $date_start,
        'date_end' => $date_end,
        'quantity' => $quantity,
    ]);

    $stmt = $pdo->prepare("UPDATE objects SET quantity = quantity - :quantity WHERE id = :object_id");
    $stmt->execute(['quantity' => $quantity, 'object_id' => $object_id]);

    echo json_encode(["success" => "Emprunt créé avec succès"]);
}

// Restituer un emprunt
function restituteEmprunt($pdo)
{
    $emprunt_id = $_POST['emprunt_id'] ?? null;
    $quantity = $_POST['quantity'] ?? null;
    $state = $_POST['state'] ?? null;
    $date = $_POST['date'] ?? null;

    if (!$emprunt_id || !$quantity || !$state || !$date) {
        http_response_code(400);
        echo json_encode(["error" => "Données manquantes."]);
        exit;
    }

    $stmt = $pdo->prepare("SELECT object_id, quantity_borrowed FROM emprunts WHERE id = :id AND status = 'en_cours'");
    $stmt->execute(['id' => $emprunt_id]);
    $emprunt = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$emprunt) {
        http_response_code(404);
        echo json_encode(["error" => "Emprunt introuvable ou déjà restitué."]);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE objects SET quantity = quantity + :quantity, state = :state WHERE id = :object_id");
    $stmt->execute([
        'quantity' => $quantity,
        'state' => $state,
        'object_id' => $emprunt['object_id'],
    ]);

    $stmt = $pdo->prepare("UPDATE emprunts SET status = 'termine', returned_date = :date WHERE id = :id");
    $stmt->execute([
        'date' => $date,
        'id' => $emprunt_id,
    ]);

    echo json_encode(["success" => "Restitution effectuée avec succès."]);
}
