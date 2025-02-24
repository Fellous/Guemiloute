<?php
// commit name: backend-list-objects
// - Récupère les objets filtrés

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo "Méthode non autorisée";
    exit;
}

session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo "Accès refusé";
    exit;
}

require_once '../db.php';

// Récupérer les filtres
$category_id = $_GET['category_id'] ?? null;
$attribute = $_GET['attribute'] ?? null;

// Construire la requête SQL avec filtres
$sql = "
    SELECT 
        o.id, o.name, o.quantity, o.state, c.name AS category_name, u.last_name AS preteur_name,
        (SELECT image_url FROM object_images WHERE object_id = o.id LIMIT 1) AS image_url
    FROM objects o
    LEFT JOIN categories c ON o.category_id = c.id
    LEFT JOIN users u ON o.preteur_id = u.id
    WHERE 1=1
";

$params = [];
if (!empty($category_id)) {
    $sql .= " AND o.category_id = :category_id";
    $params['category_id'] = $category_id;
}

if ($attribute === '1') {
    $sql .= " AND o.preteur_id IS NOT NULL";
} elseif ($attribute === '0') {
    $sql .= " AND o.preteur_id IS NULL";
}

$sql .= " ORDER BY o.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$objects = $stmt->fetchAll(PDO::FETCH_ASSOC);
header('Content-Type: application/json');
echo json_encode($objects);
