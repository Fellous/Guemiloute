<?php
// commit name: separated-login-action-with-role
// - Valide l'email, mot de passe
// - Stocke aussi le 'role' dans la session
// - Redirige selon succès ou échec

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fichier de connexion DB
require_once __DIR__ . '/../db.php';

// Récupération du POST
$email = trim($_POST['email'] ?? '');
$pass  = $_POST['password'] ?? '';

if ($email === '' || $pass === '') {
    // Redirection avec un message
    header("Location: ../login.php?msg=Veuillez remplir tous les champs");
    exit;
}

// Vérifier si l'utilisateur existe (on ajoute 'role')
$sql = "SELECT id, username, password_hash, role 
        FROM users 
        WHERE email = :email";
$stmt = $pdo->prepare($sql);
$stmt->execute(['email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // email inconnu
    header("Location: ../login.php?msg=Email inconnu");
    exit;
}

// Vérifier le mot de passe
if (password_verify($pass, $user['password_hash'])) {
    // OK => Création de la session
    $_SESSION['user_id']   = $user['id'];
    $_SESSION['username']  = $user['username'];
    $_SESSION['role']      = $user['role']; // <-- On stocke le rôle !

    // Redirige vers l'index avec un petit message
    header("Location: ../index.php?msg=Bienvenue " . urlencode($user['username']));
    exit;
} else {
    // Mot de passe incorrect
    header("Location: ../login.php?msg=Mot de passe incorrect");
    exit;
}
