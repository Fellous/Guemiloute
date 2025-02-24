<?php
// Vérification si la session est démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclusion de la connexion à la base de données
require_once __DIR__ . '/../db.php';

// Récupération des données POST
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    // Redirection avec un message d'erreur
    header("Location: ../login.php?msg=Veuillez remplir tous les champs.");
    exit;
}

// Préparer la requête pour récupérer les informations de l'utilisateur
$sql = "SELECT id, last_name, password_hash, role 
        FROM users 
        WHERE email = :email";
$stmt = $pdo->prepare($sql);
$stmt->execute(['email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // Email inconnu
    header("Location: ../login.php?msg=Email ou mot de passe incorrect.");
    exit;
}

// Vérifier le mot de passe
if (password_verify($password, $user['password_hash'])) {
    // Définir les informations de session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['last_name'] = $user['last_name'];
    $_SESSION['role'] = $user['role'];

    // Redirection en fonction du rôle
    switch ($user['role']) {
        case 'emprunteur':
            header("Location: ../emprunteur_emprunts.php");
            break;
        case 'preteur':
            header("Location: ../preteur_emprunts.php");
            break;
        case 'admin':
            header("Location: ../admin/admin_users.php");
            break;
        default:
            header("Location: ../index.php");
            break;
    }
    exit;
} else {
    // Mot de passe incorrect
    header("Location: ../login.php?msg=Email ou mot de passe incorrect.");
    exit;
}
?>
