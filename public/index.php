<?php
// commit name: improved-index-gmah-version
// - Page d'accueil stylée pour Guemiloute Sarcelles
// - Met en valeur la dimension de gmah (חסד) / prêteur / emprunteur
// - Animations, design, responsive

$pageTitle = "Accueil - Guemiloute Sarcelles";
include 'includes/header.php';

// Affiche un éventuel message (?msg=...) en haut
if (!empty($_GET['msg'])) {
    echo '<div class="container mt-3"><div class="alert alert-success">'
         . htmlspecialchars($_GET['msg'])
         . '</div></div>';
}
?>

<div class="container py-5">
  <!-- Hero Section -->
  <div class="row align-items-center gy-4">
    <div class="col-12 col-md-6 animate__animated animate__fadeInLeft">
      <h1 class="display-5 fw-bold text-primary">Guemiloute Sarcelles</h1>
      <p class="lead mt-3">
        Bienvenue sur la plateforme de prêt et d'entraide au sein de la communauté juive de Sarcelles.
        Une <strong>gmah</strong> (גמ״ח) ou <em>Guemiloute Hassadim</em> est un réseau de familles
        <span class="text-secondary fw-semibold">prêteuses</span> et 
        <span class="text-secondary fw-semibold">emprunteuses</span> qui se mobilisent
        pour s'entraider en mettant des objets à disposition de tous.
      </p>
      <?php if (!isset($_SESSION['user_id'])): ?>
        <a href="login.php" class="btn btn-primary btn-lg mt-3 animate__animated animate__fadeInUp">
          Rejoindre l'entraide
        </a>
      <?php else: ?>
        <a href="logout.php" class="btn btn-secondary btn-lg mt-3 animate__animated animate__fadeInUp">
          Se déconnecter
        </a>
      <?php endif; ?>
    </div>

    <div class="col-12 col-md-6 animate__animated animate__fadeInRight">
      <img src="assets/images/gmah-hero.jpg" alt="Famille prêteuse" class="img-fluid rounded shadow">
    </div>
  </div>
</div>

<hr class="my-5" />

<!-- Section sur les fonctionnalités principales -->
<div class="container pb-5">
  <h2 class="mb-4 fw-bold text-center animate__animated animate__fadeInDown">
    Fonctionnalités de Guemiloute
  </h2>

  <div class="row g-4">
    <!-- Ex : Catalogue objet -->
    <div class="col-12 col-sm-6 col-lg-4 animate__animated animate__zoomIn">
      <div class="card h-100 shadow-sm">
        <img src="assets/images/catalogue.jpg" class="card-img-top img-fluid" alt="Catalogue d'objets">
        <div class="card-body">
          <h5 class="card-title">Catalogue d'objets disponible</h5>
          <p class="card-text">
            Parcourez la liste des objets en stock chez les familles prêteuses : meubles, livres, matériel
            de bébé, etc. Affichage de la quantité et de l'état (neuf, bien, moyen...). Système de
            disponibilité si partiellement emprunté.
          </p>
        </div>
      </div>
    </div>

    <!-- Ex : Emprunts & Retours -->
    <div class="col-12 col-sm-6 col-lg-4 animate__animated animate__zoomIn">
      <div class="card h-100 shadow-sm">
        <img src="assets/images/emprunt.jpg" class="card-img-top img-fluid" alt="Emprunt d'objets">
        <div class="card-body">
          <h5 class="card-title">Gestion des emprunts</h5>
          <p class="card-text">
            Organisez vos prêts et retours, fixez la date de restitution, suivez l'historique complet
            (dates d'emprunt, quantités, emprunteur précédent). Les familles chômer-hinam peuvent
            enregistrer chaque sortie d'objets.
          </p>
        </div>
      </div>
    </div>

    <!-- Ex : Dons et petites ventes -->
    <div class="col-12 col-sm-6 col-lg-4 animate__animated animate__zoomIn">
      <div class="card h-100 shadow-sm">
        <img src="assets/images/don-vente.jpg" class="card-img-top img-fluid" alt="Dons et ventes">
        <div class="card-body">
          <h5 class="card-title">Dons & Petites Ventes</h5>
          <p class="card-text">
            Proposez un <strong>don</strong> ou une <strong>vente à petit prix</strong> à la communauté,
            téléchargez une photo et une courte description, précisez le prix si besoin, mettez l'annonce
            en "réservé" lorsqu'un emprunteur se manifeste.
          </p>
        </div>
      </div>
    </div>
  </div>

  <div class="mt-5 text-center animate__animated animate__fadeInUp">
    <p class="mb-2">
      <strong>Entraide</strong> — <strong>Partage</strong> — <strong>Hessed</strong>
    </p>
    <p class="text-muted">
      Guemiloute Sarcelles : au service de la communauté
    </p>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
