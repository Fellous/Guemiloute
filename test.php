<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Tableau des Clients</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="icon" href="img/favicon.png" type="image/x-icon">
    <style>
        body {
            background-image: url('img/FOND_clients.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.95);
            margin-bottom: 20px;
        }

        .card-header {
            background-color: rgba(0, 0, 0, 0.03);
        }

        .card-body {
            overflow-x: visible;
        }

        .table {
            min-width: 100%;
        }

        .prospect {
            background-color: skyblue;
            color: white;
        }

        /* Styles pour l'en-tête du tableau */
        .thead-dark th {
            background-color: #6f42c1;
            /* Violet Bootstrap */
            color: white;
        }

        /* Alternance des couleurs de fond pour les lignes du tableau */
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(206, 189, 236, 0.3);
            /* Une nuance plus claire de violet */
        }

        /* Couleur de survol pour les lignes du tableau */
        .table-hover tbody tr:hover {
            background-color: rgba(192, 159, 224, 0.5);
            /* Une couleur de survol dans le thème violet */
        }

        /* Couleur de survol spécifique pour les lignes avec la classe .prospect */
        .table-hover tbody tr.prospect:hover {
            background-color: #003366;
            /* Bleu foncé pour le survol des lignes Prospect */
            color: white;
        }
    </style>

</head>

<body>

    <div class="container-fluid mt-5">
        <?php
        include('menu_clients.php');
        ?>
        <!-- Bouton pour créer un nouveau client -->
        <div class="text-center mb-3">
            <a href="creation-client.php" class="btn btn-primary">Créer un Client etranger</a>
        </div>
        <!-- Bouton pour créer un nouveau client -->
        <div class="text-center mb-3">
            <a href="../API_capency/capency/" class="btn btn-primary">Créer un Client Francais</a>
        </div>
        <!-- Card pour les filtres -->
        <div class="card">
            <div class="card-header">Filtres</div>
            <div class="card-body">
                <!-- Utilisez la classe "row" pour définir une nouvelle ligne de grille -->
                <div class="row">
                    <!-- Utilisez la classe "col" pour définir les colonnes dans la grille -->
                    <div class="form-group col">
                        <label for="filterYear">Année:</label>
                        <select class="form-control" id="filterYear">
                            <option value="">Toutes les années</option>
                            <?php
                            // Assurez-vous que cette partie PHP génère correctement les options
                            $currentYear = date('Y');
                            for ($year = 2021; $year <= $currentYear; $year++) {
                                echo "<option value='$year'>$year</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!-- Card pour les filtres -->
                    <div class="card">
                        <div class="card-header">Filtres</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col">
                                    <label for="filterYear">Année:</label>
                                    <select class="form-control" id="filterYear">
                                        <option value="">Toutes les années</option>
                                        <?php
                                        // Génération des années
                                        $currentYear = date('Y');
                                        for ($year = 2021; $year <= $currentYear; $year++) {
                                            echo "<option value='$year'>$year</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <!-- Champ de recherche global -->
                                <div class="form-group col">
                                    <label for="filterGeneral">Recherche globale:</label>
                                    <input type="text" class="form-control" id="filterGeneral" placeholder="Tapez un mot-clé...">
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>

        <?php
        if ($_SESSION['user_title'] == "Téléphoniste") {
            include('db.php'); // Assurez-vous que le chemin d'accès est correct

            $id = $_SESSION['id'];

            $year = date("Y");
            $month = date("m");

            $stmt = $bdd->prepare("SELECT SUM(montant_ht) as total FROM bon_de_commande WHERE statut_bdc = 'Signé' AND caller_id = :id AND YEAR(created_at) = :year AND MONTH(created_at) = :month");
            $stmt->execute(['id' => $id, 'year' => $year, 'month' => $month]);

            $result = $stmt->fetch();

            $total = $result['total'] ?? 0;

            // Modification de la requête SQL pour ajouter le filtre telephoniste_id
            $stmt = $bdd->prepare("SELECT objectif FROM objectifs WHERE telephoniste_id = :telephoniste_id AND annee = :year AND mois = :month");
            $stmt->execute(['telephoniste_id' => $id, 'year' => $year, 'month' => $month]);

            $result = $stmt->fetch();

            $objectif = $result['objectif'] ?? 0;

            $reste = $objectif - $total;

            if ($total >= $objectif) {
                echo "
    <div class='d-flex justify-content-center'>
        <div class='alert alert-success text-center' style='max-width: 500px;'>Objectif réalisé: $total</div>
    </div>";
            } else {
                echo "
    <div class='d-flex justify-content-center'>
        <div class='alert alert-danger text-center' style='max-width: 500px;'>Il reste $reste à réaliser pour atteindre l'objectif</div>
    </div>";
            }
        }
        ?>
        <!-- Card pour le tableau des clients -->
        <div class="card">
            <div class="card-header">Tableau des Clients</div>
            <div class="card-body">
                <div id="tableauClients">
                    <!-- Le tableau des clients sera chargé ici via AJAX -->
                </div>
            </div>
        </div>
    </div>




    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            function chargerClients(page = 1) {
                var year = $('#filterYear').val();
                var businessName = $('#filterBusinessName').val();
                var nomEnseigne = $('#filterNomEnseigne').val();
                var name = $('#filterName').val(); // Ajout du nouveau champ de filtre



                $.ajax({
                    url: "chargerClients.php",
                    method: "POST",
                    data: {
                        page: page,
                        year: year,
                        businessName: businessName,
                        nomEnseigne: nomEnseigne,
                        name: name // Inclure le nouveau paramètre dans la requête

                    },
                    success: function(data) {
                        $('#tableauClients').html(data);
                    }
                });
            }

            $('#filterYear, #filterBusinessName, #filterNomEnseigne, #filterName').on('change keyup', function() {
                chargerClients();
            });


            // jQuery UI Autocomplete pour les champs de recherche
            $("#filterBusinessName").autocomplete({
                source: "rechercheRaisonSociale.php",
                minLength: 2
            });

            $("#filterNomEnseigne").autocomplete({
                source: "rechercheNomEnseigne.php",
                minLength: 2
            });
            $("#filterName").autocomplete({
                source: "rechercheNomPrenom.php",
                minLength: 2
            });


            // Gestion des clics sur les liens de pagination
            $(document).on('click', '.page-link', function(e) {
                e.preventDefault(); // Empêcher le comportement par défaut du lien
                var page = $(this).data('page_number');
                chargerClients(page);
            });

            // Appel initial pour charger les clients
            chargerClients();
        });
    </script>


</body>


</html>