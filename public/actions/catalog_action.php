<?php
require_once '../db.php';

// Récupérer les filtres
$category = $_GET['category'] ?? null;
$state = $_GET['state'] ?? null;
$availability = $_GET['availability'] ?? null;
$search = $_GET['search'] ?? null;

// Construire la requête SQL avec filtres
$sql = "
    SELECT 
        o.id, 
        o.name, 
        o.quantity, 
        o.state, 
        (SELECT image_url FROM object_images WHERE object_id = o.id LIMIT 1) AS image_url
    FROM objects o
    WHERE 1=1
";

$params = [];
if (!empty($category)) {
    $sql .= " AND o.category_id = :category";
    $params['category'] = $category;
}
if (!empty($state)) {
    $sql .= " AND o.state = :state";
    $params['state'] = $state;
}
if ($availability !== null) {
    $sql .= " AND (quantity > 0) = :availability";
    $params['availability'] = $availability;
}
if (!empty($search)) {
    $sql .= " AND o.name LIKE :search";
    $params['search'] = "%$search%";
}

$sql .= " ORDER BY o.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$objects = $stmt->fetchAll(PDO::FETCH_ASSOC);
header('Content-Type: application/json');
echo json_encode($objects);
