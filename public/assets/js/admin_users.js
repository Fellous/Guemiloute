// commit name: admin-users-js
// - Gère l'AJAX pour changer le rôle d'un user

document.addEventListener('DOMContentLoaded', () => {
    // Sélectionne tous les <select> de rôle
    const roleSelects = document.querySelectorAll('.role-select');
  
    roleSelects.forEach((select) => {
      select.addEventListener('change', function() {
        const newRole = this.value;
        const tr = this.closest('tr');
        const userId = tr.getAttribute('data-user-id');
  
        // On envoie la requête AJAX
        fetch('../actions/admin_users_ajax.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            user_id: userId,
            role: newRole
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Optionnel: un petit alert ou un toast
            console.log('Rôle mis à jour:', data.message);
          } else {
            console.error('Erreur:', data.error);
            // On pourrait revert le select à l'ancienne valeur
            alert(data.error || "Une erreur est survenue.");
            window.location.reload();
          }
        })
        .catch(err => {
          console.error('Catch error:', err);
          alert("Erreur de requête AJAX");
          window.location.reload();
        });
      });
    });
  });
  