<?php
// commit name: backend-get-preteurs
// - Retourne la liste des prêteurs en JSON

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

// Récupérer les prêteurs
$sql = "SELECT id, username FROM users WHERE role = 'preteur' ORDER BY username ASC";
$stmt = $pdo->query($sql);
$preteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($preteurs);
