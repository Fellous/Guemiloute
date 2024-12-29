<?php
// commit name: header-with-dropdown-menus

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Définir $pageTitle si non défini
if (!isset($pageTitle)) {
    $pageTitle = "Guemiloute";
}

// Inclure un fichier de configuration si nécessaire
require_once __DIR__ . '/../config.php'; // Assurez-vous que BASE_URL est défini ici
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
  
  <!-- Bootstrap 5.x -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  
  <!-- Animate.css -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css">
  
  <!-- Style personnalisé -->
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-darkblue shadow">
  <div class="container">
    <!-- Lien vers l'accueil -->
    <a class="navbar-brand" href="<?= BASE_URL ?>index.php">
      <i class="bi bi-gem"></i> Guemiloute
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="offcanvas offcanvas-end text-bg-darkblue" tabindex="-1" id="offcanvasNavbar">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">
          <i class="bi bi-gem"></i> Guemiloute
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">

          <!-- Lien Accueil -->
          <li class="nav-item">
            <a class="nav-link" href="<?= BASE_URL ?>index.php">Accueil</a>
          </li>

          <?php if (isset($_SESSION['user_id'])): ?>
            <!-- Salutation utilisateur connecté -->
            <li class="nav-item">
              <span class="nav-link disabled" style="opacity:0.8;">
                Bonjour, <?= htmlspecialchars($_SESSION['username'] ?? 'Utilisateur') ?>
              </span>
            </li>

            <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
              <!-- Menu déroulant pour l'admin -->
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Administration
                </a>
                <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                  <li><a class="dropdown-item" href="<?= BASE_URL ?>admin/create_object.php">Créer un objet</a></li>
                  <li><a class="dropdown-item" href="<?= BASE_URL ?>admin/create_category.php">Créer une catégorie</a></li>
                  <li><a class="dropdown-item" href="<?= BASE_URL ?>admin/list_objects.php">Lister les objets</a></li>
                  <li><a class="dropdown-item" href="<?= BASE_URL ?>admin/admin_users.php">Gestion des utilisateurs</a></li>
                </ul>
              </li>
            <?php endif; ?>

            <?php if (($_SESSION['role'] ?? '') === 'preteur' || ($_SESSION['role'] ?? '') === 'admin'): ?>
              <!-- Menu déroulant pour les prêteurs -->
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="preteurDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Profil
                </a>
                <ul class="dropdown-menu" aria-labelledby="preteurDropdown">
                  <li><a class="dropdown-item" href="<?= BASE_URL ?>edit_profile.php">Modifier mon profil</a></li>
                  <li><a class="dropdown-item" href="<?= BASE_URL ?>view_profile.php">Voir mon profil</a></li>
                </ul>
              </li>
            <?php endif; ?>

            <!-- Lien déconnexion -->
            <li class="nav-item">
              <a class="nav-link" href="<?= BASE_URL ?>logout.php">Déconnexion</a>
            </li>
          <?php else: ?>
            <!-- Liens pour les utilisateurs non connectés -->
            <li class="nav-item">
              <a class="nav-link" href="<?= BASE_URL ?>login.php">Connexion</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?= BASE_URL ?>register.php">Inscription</a>
            </li>
          <?php endif; ?>

        </ul>
      </div>
    </div>
  </div>
</nav>

<div class="content pt-4">
