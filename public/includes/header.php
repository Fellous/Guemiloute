<?php
// commit name: header-with-icons-and-mobile-fix

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

  <!-- Style personnalisé -->
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/menu.css">
  
  <script src="<?= BASE_URL ?>assets/js/animated_background.js" defer></script>

</head>

<body>
  <div id="background-animation"></div>



  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-darkblue shadow sticky-top">
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

            <?php if (isset($_SESSION['user_id'])): ?>
              <!-- Salutation utilisateur connecté -->
              <li class="nav-item">
                <span class="nav-link">
                  <i class="bi bi-person-circle"></i> Bonjour, <?= htmlspecialchars($_SESSION['username'] ?? 'Utilisateur') ?>
                </span>
              </li>

              <!-- Lien Accueil -->
              <li class="nav-item">
                <a class="nav-link" href="<?= BASE_URL ?>index.php">
                  <i class="bi bi-house-door"></i> Accueil
                </a>
              </li>
              <!-- Lien Catalogue -->
              <li class="nav-item">
                <a class="nav-link" href="<?= BASE_URL ?>catalog.php">
                  <i class="bi bi-bag"></i> Catalogue
                </a>
              </li>


              <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
                <!-- Menu déroulant pour l'admin -->
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-tools"></i> Administration
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
                <li class="nav-item">
                  <a class="nav-link" href="<?= BASE_URL ?>preteur_emprunts.php">
                    <i class="bi bi-card-list"></i> Gestion des emprunts
                  </a>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="preteurDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-lines-fill"></i> Profil
                  </a>
                  <ul class="dropdown-menu" aria-labelledby="preteurDropdown">
                    <li><a class="dropdown-item" href="<?= BASE_URL ?>edit_profile.php">Modifier mon profil</a></li>
                    <li><a class="dropdown-item" href="<?= BASE_URL ?>view_profile.php">Voir mon profil</a></li>
                  </ul>
                </li>
              <?php endif; ?>

              <!-- Lien Déconnexion -->
              <li class="nav-item">
                <a class="nav-link" href="<?= BASE_URL ?>logout.php">
                  <i class="bi bi-box-arrow-right"></i> Déconnexion
                </a>
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