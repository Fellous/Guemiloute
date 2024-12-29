<?php
// commit name: admin-users-ajax-back
// - Reçoit une requête AJAX en JSON
// - Met à jour le rôle de l'utilisateur, seulement si on est admin

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier rôle admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    // Renvoie un JSON d'erreur (403)
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'error' => 'Accès refusé (admin requis)'
    ]);
    exit;
}

// On récupère la requête JSON
$rawData = file_get_contents('php://input');
$data = json_decode($rawData, true);

// user_id et role attendus
$userId = $data['user_id'] ?? null;
$newRole = $data['role'] ?? null;

if (!$userId || !$newRole) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'error' => 'Paramètres invalides'
    ]);
    exit;
}

// Vérifier que $newRole est valide
$validRoles = ['emprunteur','preteur','admin'];
if (!in_array($newRole, $validRoles)) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'error' => 'Rôle invalide'
    ]);
    exit;
}

// Connexion DB
require_once '../db.php';

// Mettre à jour en base
$stmt = $pdo->prepare("UPDATE users SET role = :r WHERE id = :id");
$stmt->execute([
    'r' => $newRole,
    'id' => $userId
]);

header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'message' => "Rôle de l'utilisateur #$userId mis à jour en '$newRole'"
]);
exit;
