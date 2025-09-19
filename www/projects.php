<?php
session_start();

require("../core/gestion_bdd.php");
require("../core/View.php");

use Core\BDD;
use Core\View;

// Controlleur

$mode = "User";
$message_erreur = "";
// si image trop grande, erreur
if (isset($_FILES['image']) && $_FILES['image']['error'] == 1) {
  $message_erreur = "ERREUR : Image trop volumineuse.";
}
// si informations d'image titre description lien et tableau d'ids, on les récupère et on rajoute
if (isset($_POST["titre"]) && isset($_POST["description"]) && isset($_POST["lien"]) && isset($_POST["tableau_ids"]) && isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
  $titre = $_POST["titre"];
  $description = $_POST["description"];
  $lien = $_POST["lien"];
  $tableau_ids = $_POST["tableau_ids"];
  $chemin_image_temp = $_FILES['image']['tmp_name'];
  $nom_image_temp = $_FILES['image']['name'];
  $message_erreur .= BDD::ajoutProjectDansBdd($chemin_image_temp, $nom_image_temp, $titre, $description, $lien, $tableau_ids);
}

// si informations d'id a supprimer, on supprime
if (isset($_POST["id_pour_suppression"])) {
  $id_pour_suppression_de_project = $_POST["id_pour_suppression"];
  $message_erreur .= BDD::suppressionProjectDansBdd($id_pour_suppression_de_project);
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
$gestionnaireProject = BDD::recupProjectsDansBdd();

// récupération des différents "bouts de page"
$barre_navigation = View::render("barre_bouton.php", ["mode" => $mode, "message_erreur" => $message_erreur]);
$barre_droite = View::render("barre_droite.php");
$barre_gauche = View::render("barre_gauche.php");
$barre_haut = View::render("barre_haut.php", ["mode" => $mode, "type" => "with_button"]);
$formulaire_ajout = View::render("form_ajout_project.php", ["mode" => $mode]);

// Vue

?>


<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Projets</title>
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

        <h1>Projets :</h1>

        <br>

        <div class="affichage_hobbies">
            <?php foreach ($gestionnaireProject->recupererProjects() as $projet): ?>

                <div class="boite_about_intro">

                    <strong><?= htmlspecialchars($projet->titre) ?></strong><br>
                    <?= nl2br(htmlspecialchars($projet->description)) ?><br>
                    <a href="<?= htmlspecialchars($projet->lien) ?>" target='_blank'>
                    <img src="<?= htmlspecialchars($projet->chemin_image) ?>" alt="" style="max-width:100%; height:auto;">

                    <div class="kpis">
                      <?php
                      foreach ($projet->recupererTags() as $id_tag) {
                        $tag_quon_veut_afficher = $gestionnaireProject->recupereTagDisponibleGraceAId($id_tag);
                        ?>

                        <span class="badge">
                          <?= $tag_quon_veut_afficher->titre ?>
                        </span>

                        <?php
                      }
                      ?>
                    </div>

                    <?php if ($mode === "Backoffice"): ?>
                        <form action="" method="post" class="bouton_suppression_hobby inactive">
                            <input type="hidden" name="id_pour_suppression" value="<?= $projet->id ?>">
                            <input type="submit" value="Supprimer" class="delete-button">
                        </form>
                    <?php endif; ?>

                </div>

            <?php endforeach; ?>
        </div>


      </div>




    </main>
    
    <!-- Barre droite -->
    <?php echo $barre_droite; ?>

  </div>

  <!-- Barre inférieure avec les contrôles -->
  <?php echo $barre_navigation; ?>


</body>

<script>
  // On récupère la valeur PHP dans une variable JS
  let mode = <?= json_encode($mode) ?>;

  if (mode === "Backoffice") {
    zone_formulaire_ajout_hobby = document.getElementById("formulaire_ajout_hobby");
    zones_boutons_supprimant_hobby = document.querySelectorAll(".bouton_suppression_hobby");
    zone_bouton_montrant_mode_backoffice = document.getElementById("bouton_montrant_mode_backoffice");
    zone_bouton_montrant_formulaire = document.getElementById("bouton_montrant_formulaire");

    document.querySelector('.switch-input').onclick = () => {

      if (zone_formulaire_ajout_hobby.classList.contains("active-form")) {
        zone_formulaire_ajout_hobby.classList.remove('active-form');
        zone_formulaire_ajout_hobby.classList.add('inactive-form');
        zones_boutons_supprimant_hobby.forEach(bouton => {
          bouton.classList.remove('active');
          bouton.classList.add('inactive');
        });
      } else {
        zone_formulaire_ajout_hobby.classList.remove('inactive-form');
        zone_formulaire_ajout_hobby.classList.add('active-form');
        zones_boutons_supprimant_hobby.forEach(bouton => {
          bouton.classList.remove('inactive');
          bouton.classList.add('active');
        });
      }

      if (zone_formulaire_ajout_hobby.classList.contains("form-is-visible")) {
          zone_formulaire_ajout_hobby.classList.remove('form-is-visible');
          zone_formulaire_ajout_hobby.classList.add('form-is-hidden');
        }

    };

    zone_bouton_montrant_formulaire.onclick = () => {

      if (zone_formulaire_ajout_hobby.classList.contains("form-is-visible")) {
        zone_formulaire_ajout_hobby.classList.remove('form-is-visible');
        zone_formulaire_ajout_hobby.classList.add('form-is-hidden');
      } else {
        zone_formulaire_ajout_hobby.classList.remove('form-is-hidden');
        zone_formulaire_ajout_hobby.classList.add('form-is-visible');
      }

    };

  }
</script>

</html>