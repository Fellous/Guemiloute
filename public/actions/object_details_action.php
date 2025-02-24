<?php
// commit name: backend-object-details
require_once '../db.php';

// Vérifier si l'ID de l'objet est passé
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    echo json_encode(["error" => "ID invalide"]);
    exit;
}

$objectId = (int)$_GET['id'];

// Récupérer les informations de l'objet et du prêteur
$sql = "
    SELECT 
        o.name AS object_name,
        o.description,
        o.state,
        o.quantity,
        o.address AS object_address,
        o.city AS object_city,
        u.last_name AS preteur_name,
        u.phone AS preteur_phone,
        u.address AS preteur_address,
        u.city AS preteur_city,
        c.name AS category_name
    FROM objects o
    JOIN users u ON o.preteur_id = u.id
    LEFT JOIN categories c ON o.category_id = c.id
    WHERE o.id = :objectId
";
$stmt = $pdo->prepare($sql);
$stmt->execute(['objectId' => $objectId]);

$object = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$object) {
    http_response_code(404);
    echo json_encode(["error" => "Objet introuvable"]);
    exit;
}

// Récupérer les images associées à l'objet
$imageStmt = $pdo->prepare("SELECT image_url FROM object_images WHERE object_id = :objectId");
$imageStmt->execute(['objectId' => $objectId]);
$images = $imageStmt->fetchAll(PDO::FETCH_ASSOC);

// Retourner les données en JSON
header('Content-Type: application/json');
echo json_encode([
    "object" => $object,
    "images" => $images
]);
