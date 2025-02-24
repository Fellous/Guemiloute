document.addEventListener('DOMContentLoaded', () => {
  const roleSelects = document.querySelectorAll('.role-select');

  roleSelects.forEach((select) => {
    select.addEventListener('change', function () {
      const newRole = this.value;
      const tr = this.closest('tr');
      const userId = tr.getAttribute('data-user-id');

      fetch('../actions/admin_users_ajax.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          user_id: userId,
          role: newRole,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            alert(`Rôle mis à jour avec succès : ${data.message}`);
          } else {
            alert(data.error || 'Une erreur est survenue.');
            window.location.reload();
          }
        })
        .catch((err) => {
          console.error('Erreur :', err);
          alert('Une erreur de requête est survenue.');
          window.location.reload();
        });
    });
  });
});
