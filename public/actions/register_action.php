<?php
// commit name: separated-register-action
// - Gère l'inscription en base, redirige vers register.php en cas d'erreur
// - Redirige vers login.php en cas de succès

// On lance la session tout de suite, avant tout echo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Connexion à la DB (assure-toi que db.php n'émet aucun HTML avant)
require_once __DIR__ . '/../db.php';

// Récupération des données du formulaire
$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');
$pass     = $_POST['password'] ?? '';

// Vérifications minimales
if ($username === '' || $email === '' || $pass === '') {
    // Rediriger avec un message d'erreur
    header("Location: ../public/register.php?msg=Veuillez remplir tous les champs");
    exit;
}

// Vérifier si l'utilisateur existe déjà (email OU username)
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email OR username = :username");
$stmt->execute(['email' => $email, 'username' => $username]);
$existing = $stmt->fetch();

if ($existing) {
    // Déjà pris
    header("Location: /register.php?msg=Email ou nom d'utilisateur déjà pris");
    exit;
}

// On insère le nouveau user
$passwordHash = password_hash($pass, PASSWORD_DEFAULT);

$insertSql = "INSERT INTO users (username, email, password_hash) 
              VALUES (:u, :e, :p)";
$insertStmt = $pdo->prepare($insertSql);
$insertStmt->execute([
    'u' => $username,
    'e' => $email,
    'p' => $passwordHash
]);

// Rediriger vers login avec un message
header("Location: /login.php?msg==Inscription réussie, connectez-vous");
exit;
