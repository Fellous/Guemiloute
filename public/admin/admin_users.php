<?php
// commit name: admin-users-front-beautify
// - Vérifie si user est admin, sinon redirection
// - Affiche un design plus sympa (card, style) + retire "famille"

$pageTitle = "Gestion des utilisateurs - Admin";
require_once '../includes/header.php';

// Vérifier que la session est démarrée et le rôle admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../index.php?msg=Accès refusé");
    exit;
}

// Connexion DB
require_once '../db.php';

// Récupération de la liste des utilisateurs
$sql = "SELECT id, username, email, role FROM users ORDER BY id ASC";
$stmt = $pdo->query($sql);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Rôles disponibles (sans 'famille')
$roles = ['emprunteur','preteur','admin'];
?>
<!-- Inclusion du CSS "admin.css" spécifique -->
<link rel="stylesheet" href="../assets/css/admin.css">

<div class="container my-5 admin-container">
  <h2 class="mb-4"><i class="bi bi-people-fill me-2"></i>Gestion des utilisateurs</h2>
  
  <p class="text-muted">
    Depuis cette interface, vous pouvez modifier le rôle des utilisateurs 
    (ex: le passer en "admin" ou "preteur").
  </p>

  <div class="card">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle mb-0" id="usersTable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nom d'utilisateur</th>
              <th>Email</th>
              <th>Rôle</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $user): ?>
              <tr data-user-id="<?= $user['id'] ?>">
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td>
                  <select class="form-select role-select">
                    <?php foreach ($roles as $r): ?>
                      <option value="<?= $r ?>" 
                        <?= ($r === $user['role']) ? 'selected' : '' ?>>
                        <?= $r ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div> <!-- /.table-responsive -->
    </div><!-- /.card-body -->
  </div> <!-- /.card -->
</div> <!-- /.admin-container -->

<!-- Script Ajax -->
<script src="../assets/js/admin_users.js"></script>
<?php require_once '../includes/footer.php'; ?>
