<?php
// commit name: fix-logout-headers
// - Aucune ligne avant "<?php"
// - Lance la session, détruit, redirige

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si la session est déjà initialisée, on la supprime
session_unset();    
session_destroy();  

// Redirection vers l'accueil (ou autre page)
header("Location: index.php");
exit;
