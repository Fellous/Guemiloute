<?php
// commit name: backend-create-category
// - Ajoute une nouvelle catégorie dans la table "categories"

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

// Récupérer le nom de la catégorie
$name = trim($_POST['name'] ?? '');

if ($name === '') {
    http_response_code(400);
    echo "Le nom de la catégorie est requis.";
    exit;
}

// Vérifier si la catégorie existe déjà
$stmt = $pdo->prepare("SELECT id FROM categories WHERE name = :name");
$stmt->execute(['name' => $name]);
if ($stmt->fetch()) {
    http_response_code(400);
    echo "Cette catégorie existe déjà.";
    exit;
}

// Insérer la nouvelle catégorie
$sql = "INSERT INTO categories (name) VALUES (:name)";
$stmt = $pdo->prepare($sql);
$stmt->execute(['name' => $name]);

echo "Catégorie ajoutée avec succès.";
