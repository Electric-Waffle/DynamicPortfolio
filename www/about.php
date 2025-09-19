<?php
session_start();

require("../core/gestion_bdd.php");
require("../core/View.php");

use Model\BDD;
use Core\View;


// Controlleur


$mode = "User";
$message_erreur = "";
// si informations de date et de description, on les récupère et on rajoute
if (isset($_POST["date"]) && isset($_POST["description"])) {
  $date = $_POST["date"];
  $description = $_POST["description"];
  $message_erreur .= BDD::ajoutTimelineDansBdd($date, $description);
}

// si informations d'id a supprimer, on supprime
if (isset($_POST["id_pour_suppression"])) {
  $id_pour_suppression_de_timeline = $_POST["id_pour_suppression"];
  $message_erreur .= BDD::suppressionTimelineDansBdd($id_pour_suppression_de_timeline);
}

// si information de session, on se met en mode backoffice
if (isset($_POST['connexion'])) {
  $_SESSION['nom'] = 'Sylvain';
}
if (isset($_POST['deconnexion'])) {
  session_unset();
}
if (isset($_SESSION['nom']) && $_SESSION['nom'] == "Sylvain") {
  $mode = "Backoffice";
}

// récupération des données de timeline dans la bdd
$gestionnaireTimeline = BDD::recupTimelinesDansBdd();

// récupération des différents "bouts de page"
$barre_navigation = View::render("barre_bouton.php", ["mode" => $mode, "message_erreur" => $message_erreur]);
$barre_droite = View::render("barre_droite.php");
$barre_gauche = View::render("barre_gauche.php");
$barre_haut = View::render("barre_haut.php", ["mode" => $mode, "type" => "with_button"]);
$formulaire_ajout = View::render("form_ajout_about.php", ["mode" => $mode]);

// Vue

?>


<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>About</title>
  <link rel="stylesheet" href="style.css" />
</head>

<body>

  <!-- Barre supérieure -->
  <?php echo $barre_haut; ?>

  <!-- Conteneur principal -->
  <div class="layout">
    <!-- Barre gauche -->
    <?php echo $barre_gauche; ?>

    <!-- Contenu principal scrollable -->
    <main class="content">

      <!-- formulaire d'ajout -->
      <?php echo $formulaire_ajout; ?>
      

      <div style="height: auto; background: #f9f9f9;">

        <br>

        <h1>A propos :</h1>
        <div class="boite_about_intro">
          <h2>Description</h2>
        </div>

        <br>

        <h1>Timeline</h1>
        <div class="boite_about_intro">
            <?php foreach ($gestionnaireTimeline->recupererTimelines() as $timeline): ?>
                <div class="timeline-item">
                    <?= htmlspecialchars($timeline->date) ?> - <?= htmlspecialchars($timeline->description) ?>

                    <?php if ($mode === "Backoffice"): ?>
                        <form action="" method="post" class="bouton_suppression_timeline inactive">
                            <input type="hidden" name="id_pour_suppression" value="<?= $timeline->id ?>">
                            <input type="submit" value="Supprimer" class="delete-button">
                        </form>
                    <?php endif; ?>

                    <hr>
                </div>
            <?php endforeach; ?>
        </div>


      </div>



    </main>

    <!-- Barre droite -->
    <?php echo $barre_droite; ?>

  </div>

  <?php echo $barre_navigation; ?>

</body>

<script>
  // On récupère la valeur PHP dans une variable JS
  let mode = <?= json_encode($mode) ?>;

  if (mode === "Backoffice") {
    zone_formulaire_ajout_timeline = document.getElementById("formulaire_ajout_timeline");
    zones_boutons_supprimant_timeline = document.querySelectorAll(".bouton_suppression_timeline");
    zone_bouton_montrant_mode_backoffice = document.getElementById("bouton_montrant_mode_backoffice");
    zone_bouton_montrant_formulaire = document.getElementById("bouton_montrant_formulaire");

    document.querySelector('.switch-input').onclick = () => {

      if (zone_formulaire_ajout_timeline.classList.contains("active-form")) {
        zone_formulaire_ajout_timeline.classList.remove('active-form');
        zone_formulaire_ajout_timeline.classList.add('inactive-form');
        zones_boutons_supprimant_timeline.forEach(bouton => {
          bouton.classList.remove('active');
          bouton.classList.add('inactive');
        });
      } else {
        zone_formulaire_ajout_timeline.classList.remove('inactive-form');
        zone_formulaire_ajout_timeline.classList.add('active-form');
        zones_boutons_supprimant_timeline.forEach(bouton => {
          bouton.classList.remove('inactive');
          bouton.classList.add('active');
        });
      }

      if (zone_formulaire_ajout_timeline.classList.contains("form-is-visible")) {
        zone_formulaire_ajout_timeline.classList.remove('form-is-visible');
        zone_formulaire_ajout_timeline.classList.add('form-is-hidden');
      }


    };

    zone_bouton_montrant_formulaire.onclick = () => {

      if (zone_formulaire_ajout_timeline.classList.contains("form-is-visible")) {
        zone_formulaire_ajout_timeline.classList.remove('form-is-visible');
        zone_formulaire_ajout_timeline.classList.add('form-is-hidden');
      } else {
        zone_formulaire_ajout_timeline.classList.remove('form-is-hidden');
        zone_formulaire_ajout_timeline.classList.add('form-is-visible');
      }

    };

  }

</script>

</html>