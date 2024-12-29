<?php
// commit name: backend-attribute-object
// - Attribue un objet à un prêteur

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
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

$object_id = $_POST['object_id'] ?? null;
$preteur_id = $_POST['preteur_id'] ?? null;

if (empty($object_id) || empty($preteur_id)) {
    http_response_code(400);
    echo "Données manquantes.";
    exit;
}

$sql = "UPDATE objects SET preteur_id = :preteur_id WHERE id = :object_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'preteur_id' => $preteur_id,
    'object_id' => $object_id,
]);

echo "Objet attribué avec succès.";
