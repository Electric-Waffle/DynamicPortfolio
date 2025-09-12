<?php
session_start();

require("gestion_bdd.php");

use Model\BDD;

$mode = "User";
$message_erreur = "";
// si informations de titre et de description et d'image, on les récupère et on rajoute
if (isset($_POST["titre"]) && isset($_POST["description"]) && isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
  $titre = $_POST["titre"];
  $description = $_POST["description"];
  $chemin_image_temp = $_FILES['image']['tmp_name'];
  $nom_image_temp = $_FILES['image']['name'];
  $message_erreur .= BDD::ajoutHobbyDansBdd($chemin_image_temp, $nom_image_temp, $titre, $description);
}

// si informations d'id a supprimer, on supprime
if (isset($_POST["id_pour_suppression"])) {
  $id_pour_suppression_de_timeline = $_POST["id_pour_suppression"];
  $message_erreur .= BDD::suppressionHobbyDansBdd($id_pour_suppression_de_timeline);
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

// récupération des données de hobby dans la bdd
$gestionnaireHobby = BDD::recupHobbiesDansBdd();

?>


<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hobbies</title>
  <style>
    .active {
      display: contents;
    }

    .inactive {
      display: none;
    }
  </style>
  <link rel="stylesheet" href="style.css" />
</head>

<body>

  <!-- Barre supérieure -->
  <?php
  if ($mode == "Backoffice") {
  ?>
  <?php
  }
  ?>
  <header class="bar top-bar">

    <!-- Joli bouton css-->
    <?php
    if ($mode == "Backoffice") {
    ?>
      <div class="switch-container">
        <input class="switch-input" type="checkbox">
        <div class="switch-button">
          <div class="switch-button-inside">
            <svg class="switch-icon off" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" clip-rule="evenodd" d="M8 12C10.2091 12 12 10.2091 12 8C12 5.79086 10.2091 4 8 4C5.79086 4 4 5.79086 4 8C4 10.2091 5.79086 12 8 12ZM8 14C11.3137 14 14 11.3137 14 8C14 4.68629 11.3137 2 8 2C4.68629 2 2 4.68629 2 8C2 11.3137 4.68629 14 8 14Z" />
            </svg>
            <svg class="switch-icon on" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
              <rect x="2" y="7" width="12" height="2" rx="1" />
            </svg>
          </div>
        </div>
      </div>
    <?php
    }
    ?>

  </header>

  <!-- Conteneur principal -->
  <div class="layout">
    <!-- Barre gauche -->
    <aside class="bar side-bar left-bar"></aside>

    <!-- Contenu principal scrollable -->
    <main class="content">

      <!-- formulaire d'ajout -->
      <?php
      if ($mode == "Backoffice") {
      ?>
        <div id="formulaire_ajout_hobby" class="inactive">

          <div>
            <h3>Ajouter un Hobbie</h3>
          </div>

          <form action="" method="post" enctype="multipart/form-data">
            <div>
              <label for="titre" style="margin-right: 10px;">Titre :</label>
              <input type="text" id="titre" name="titre">
              <label for="description" style="margin-left: 10px; margin-right: 10px;">Description :</label>
              <input type="text" id="description" name="description" style="margin-right: 10px;">
              <label for="image" style="margin-left: 10px; margin-right: 10px;">Image :</label>
              <input type="file" name="image" id="image" style="margin-right: 10px;" accept=".jpg, .jpeg, .png">
              <input type="submit" value="Valider">
            </div>
          </form>

        </div>


      <?php
      }
      ?>

      <div style="height: auto; background: #f9f9f9;">



        <br>

        <?php
        if ($message_erreur != "") {
          echo "<h3>" . $message_erreur . "</h3>";
        }
        ?>

        <br>

        <h1>Hobbies :</h1>
        <div class="boite_about_intro">
          <h2>Description</h2>
        </div>

        <br>


        <div class="affichage_hobbies">
          <?php
          foreach ($gestionnaireHobby->recupererHobbies() as $hobby) {
            $affichage_hobby = "<div class=\"boite_about_intro\">";
            $affichage_hobby .= $hobby->titre . " <br> " . $hobby->description . " <br> " . "<img src=\"" . $hobby->chemin_image . "\" alt=\"\" >";
            if ($mode == "Backoffice") {
              $affichage_hobby .= "<form action=\"\" method=\"post\" class=\"inactive bouton_suppression_hobby\"><input type=\"hidden\" name=\"id_pour_suppression\" value=\"" . $hobby->id . "\"><input type=\"submit\" value=\"supprimer\"></form>";
            }
            $affichage_hobby .= "</div>";
            echo $affichage_hobby;
          }
          ?>
        </div>

      </div>




  </main>
    <!-- Barre droite -->
  <aside class="bar side-bar right-bar"></aside>
  </div>

  <!-- Barre inférieure avec les contrôles -->
  <footer class="bar bottom-bar">
    <!-- Croix directionnelle -->
    <div class="dpad">
      <a href="index.html" class="btn up">home</a>
      <a href="about.php" class="btn down">about</a>
      <a href="hobbies.php" class="btn left"></a>
      <a href="#" class="btn right"></a>
      <a href="#" class="btn center"></a>
    </div>

    <!-- Boutons A & B -->
    <div class="buttons">
      <?php
      if ($mode == "Backoffice") {
        echo "<form method=\"post\" action=\"\">";
        echo "<input class=\"btn button-b\" type=\"submit\" name=\"deconnexion\" value=\"B\">";
        echo "</form>";
      } else {
        echo "<form method=\"post\" action=\"\">";
        echo "<input class=\"btn button-a\" type=\"submit\" name=\"connexion\" value=\"A\">";
        echo "</form>";
      }
      ?>
    </div>
  </footer>

</body>

<script>
  // On récupère la valeur PHP dans une variable JS
  let mode = <?= json_encode($mode) ?>;

  if (mode === "Backoffice") {
    zone_formulaire_ajout_hobby = document.getElementById("formulaire_ajout_hobby");
    zones_boutons_supprimant_hobby = document.querySelectorAll(".bouton_suppression_hobby");
    zone_bouton_montrant_mode_backoffice = document.getElementById("bouton_montrant_mode_backoffice");

    document.querySelector('.switch-input').onclick = () => {

      if (zone_formulaire_ajout_hobby.className == "active") {
        zone_formulaire_ajout_hobby.className = "inactive";
        zones_boutons_supprimant_hobby.forEach(bouton => {
          bouton.classList.remove('active');
          bouton.classList.add('inactive');
        });
      } else {
        zone_formulaire_ajout_hobby.className = "active";
        zones_boutons_supprimant_hobby.forEach(bouton => {
          bouton.classList.remove('inactive');
          bouton.classList.add('active');
        });
      }

    }
  }
</script>

</html>