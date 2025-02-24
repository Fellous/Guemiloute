<?php
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
$sql = "SELECT id, last_name, email, role FROM users ORDER BY id ASC";
$stmt = $pdo->query($sql);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Rôles disponibles
$roles = ['emprunteur', 'preteur', 'admin'];
?>

<link rel="stylesheet" href="../assets/css/admin.css">

<div class="container my-5 admin-container">
  <div class="admin-header d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-people-fill me-2"></i>Gestion des utilisateurs</h2>
    <a href="../index.php" class="btn btn-secondary shadow-sm">
      <i class="bi bi-arrow-left"></i> Retour
    </a>
  </div>

  <p class="text-muted">
    Modifiez les rôles des utilisateurs avec ce tableau interactif. Les modifications sont appliquées en temps réel.
  </p>

  <div class="card shadow-lg border-0">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="usersTable">
          <thead class="table-dark">
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
                <td><?= htmlspecialchars($user['last_name']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td>
                  <select class="form-select role-select shadow-sm" data-user-id="<?= $user['id'] ?>">
                    <?php foreach ($roles as $r): ?>
                      <option value="<?= $r ?>" <?= ($r === $user['role']) ? 'selected' : '' ?>>
                        <?= ucfirst($r) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="../assets/js/admin_users.js"></script>
<?php require_once '../includes/footer.php'; ?>
