<?php
// commit name: step4-create-db-php
// - Fichier de connexion PDO
// - DB: guemiloute, user/pass: guemiloute

$dsn = 'mysql:host=db;dbname=guemiloute;charset=utf8';
$dbUser = 'guemiloute';
$dbPass = 'guemiloute';

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass);
    // On configure un mode d’erreur
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // En prod, on éviterait d'afficher direct le message
    die("Erreur de connexion : " . $e->getMessage());
}
