<?php
session_start();
header('Content-Type: application/json');

// Vérification de l'accès
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'emprunteur') {
    http_response_code(403);
    echo json_encode(["error" => "Accès refusé"]);
    exit;
}

require_once '../db.php';

// Identifier l'action
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get_en_cours':
        getEmprunts('en_cours', $pdo);
        break;
    case 'get_passes':
        getEmprunts('termine', $pdo);
        break;
    default:
        http_response_code(400);
        echo json_encode(["error" => "Action non valide"]);
        break;
}

// Récupérer les emprunts
function getEmprunts($status, $pdo)
{
    $emprunteur_id = $_SESSION['user_id'];
    $sql = "
        SELECT e.id, o.name AS object_name, u.last_name AS preteur_name,
               e.date_start, e.date_end, e.returned_date, e.quantity_borrowed
        FROM emprunts e
        JOIN objects o ON e.object_id = o.id
        JOIN users u ON e.preteur_id = u.id
        WHERE e.emprunteur_id = :emprunteur_id AND e.status = :status
        ORDER BY e.date_end DESC
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['emprunteur_id' => $emprunteur_id, 'status' => $status]);
    $emprunts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($emprunts ?: ["message" => "Aucun emprunt trouvé"]);
}
