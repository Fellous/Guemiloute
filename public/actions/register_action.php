<?php
// commit name: separated-register-action-updated
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../db.php';

$first_name = trim($_POST['first_name'] ?? '');
$last_name  = trim($_POST['last_name'] ?? '');
$email      = trim($_POST['email'] ?? '');
$phone      = trim($_POST['phone'] ?? '');
$address    = trim($_POST['address'] ?? '');
$city       = trim($_POST['city'] ?? '');
$pass       = $_POST['password'] ?? '';

if ($first_name === '' || $last_name === '' || $email === '' || $phone === '' || $address === '' || $city === '' || $pass === '') {
    header("Location: ../public/register.php?msg=Veuillez remplir tous les champs");
    exit;
}

// Vérifier si l'utilisateur existe déjà (par email)
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
$stmt->execute(['email' => $email]);
$existing = $stmt->fetch();

if ($existing) {
    header("Location: ../public/register.php?msg=Email déjà pris");
    exit;
}

$passwordHash = password_hash($pass, PASSWORD_DEFAULT);

$insertSql = "INSERT INTO users (first_name, last_name, email, phone, address, city, password_hash, role) 
              VALUES (:first_name, :last_name, :email, :phone, :address, :city, :p, 'emprunteur')";
$insertStmt = $pdo->prepare($insertSql);
$insertStmt->execute([
    'first_name' => $first_name,
    'last_name'  => $last_name,
    'email'      => $email,
    'phone'      => $phone,
    'address'    => $address,
    'city'       => $city,
    'p'          => $passwordHash
]);

header("Location: /login.php?msg==Inscription réussie, connectez-vous");
exit;
