<?php
// commit name: backend-create-object
// - Insère un nouvel objet dans la table objects
// - Gère l'upload d'images associées

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

// Récupérer les données du formulaire
$name = trim($_POST['name']);
$description = trim($_POST['description']);
$category_id = intval($_POST['category']);
$state = $_POST['state'];
$quantity = intval($_POST['quantity']);
$preteur_id = !empty($_POST['preteur']) ? intval($_POST['preteur']) : null;

// Vérification des champs obligatoires
if (!$name || !$description || !$category_id || !$state || !$quantity) {
    echo "Veuillez remplir tous les champs requis.";
    exit;
}

// Insertion dans la table objects
$sql = "INSERT INTO objects (name, description, category_id, state, quantity, preteur_id) 
        VALUES (:name, :description, :category_id, :state, :quantity, :preteur_id)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'name' => $name,
    'description' => $description,
    'category_id' => $category_id,
    'state' => $state,
    'quantity' => $quantity,
    'preteur_id' => $preteur_id ?: null, // Remplace une chaîne vide par NULL
]);


$object_id = $pdo->lastInsertId();

// Gérer les images
$upload_dir = '../assets/uploads/objects/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

if (!empty($_FILES['images']['name'][0])) {
    foreach ($_FILES['images']['tmp_name'] as $index => $tmp_name) {
        $filename = uniqid() . '_' . basename($_FILES['images']['name'][$index]);
        $target = $upload_dir . $filename;

        if (move_uploaded_file($tmp_name, $target)) {
            $pdo->prepare("INSERT INTO object_images (object_id, image_url) VALUES (?, ?)")
                ->execute([$object_id, $filename]);
        }
    }
}

echo "Objet créé avec succès !";
