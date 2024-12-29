<?php
// commit name: backend-update-profile
// - Met à jour les informations du profil utilisateur

session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'preteur') {
    http_response_code(403);
    echo "Accès refusé";
    exit;
}

require_once '../db.php';

// Récupérer les données POST
$address = trim($_POST['address'] ?? '');
$city = trim($_POST['city'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$additional_info = trim($_POST['additional_info'] ?? '');
$availability = json_encode($_POST['availability'] ?? []);

// Validation simple
if (empty($address) || empty($city) || empty($phone)) {
    http_response_code(400);
    echo "Tous les champs obligatoires doivent être remplis.";
    exit;
}

// Mettre à jour la base de données
$sql = "UPDATE users SET address = :address, city = :city, phone = :phone, additional_info = :additional_info, availability = :availability WHERE id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'address' => $address,
    'city' => $city,
    'phone' => $phone,
    'additional_info' => $additional_info,
    'availability' => $availability,
    'user_id' => $_SESSION['user_id'],
]);

echo "Profil mis à jour avec succès.";
