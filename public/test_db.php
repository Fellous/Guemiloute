<!-- commit name: step2-test-db
     - Page simple qui se connecte à MySQL avec PDO et affiche un message
-->
<?php
$dsn = 'mysql:host=db;dbname=guemiloute;charset=utf8';
$user = 'guemiloute';
$pass = 'guemiloute';

try {
    // On tente la connexion
    $pdo = new PDO($dsn, $user, $pass);
    echo "<h3 style='color:green'>Connexion à MySQL réussie !</h3>";

    // Exemple: lister les tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "<p>Tables présentes :</p><ul>";
    foreach ($tables as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";

} catch (PDOException $e) {
    echo "<h3 style='color:red'>Échec de connexion : " . $e->getMessage() . "</h3>";
}
