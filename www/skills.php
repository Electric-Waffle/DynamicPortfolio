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
// si informations de logo, titre, extra, description, lien on les récupère et on rajoute
if (isset($_POST["titre"]) && isset($_POST["extra"]) && isset($_POST["lien"]) && isset($_POST["description"]) && isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
  $titre = $_POST["titre"];
  $extra = $_POST["extra"];
  $lien = $_POST["lien"];
  $description = $_POST["description"];
  $chemin_image_temp = $_FILES['image']['tmp_name'];
  $nom_image_temp = $_FILES['image']['name'];
  $message_erreur .= BDD::ajoutSkillDansBdd($chemin_image_temp, $nom_image_temp, $titre, $extra, $description, $lien);
}

// si informations d'id a supprimer, on supprime
if (isset($_POST["id_pour_suppression"])) {
  $id_pour_suppression_de_skill = $_POST["id_pour_suppression"];
  $message_erreur .= BDD::suppressionSkillDansBdd($id_pour_suppression_de_skill);
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

// récupération des données de skill dans la bdd
$gestionnaireSkills = BDD::recupSkillsDansBdd();

// récupération des différents "bouts de page"
$barre_navigation = View::render("barre_bouton.php", ["mode" => $mode, "message_erreur" => $message_erreur]);
$barre_droite = View::render("barre_droite.php");
$barre_gauche = View::render("barre_gauche.php");
$barre_haut = View::render("barre_haut.php", ["mode" => $mode, "type" => "with_button"]);
$formulaire_ajout = View::render("form_ajout_skill.php", ["mode" => $mode]);

// Vue

?>


<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Compétences</title>
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
    <main class="content screen">

      <!-- formulaire d'ajout -->
      <?php echo $formulaire_ajout; ?>

      <div style="height: auto; background: #f9f9f9;">

        <br>

        <h1>Compétences</h1>

        <br>


        <div class="affichage_skills">
          <?php foreach ($gestionnaireSkills->recupererSkills() as $skill): ?>

            <div class="boite_skill">

              <img src="<?= htmlspecialchars($skill->chemin_logo) ?>" alt="" style="max-width:100%; height:auto;">
              <strong><?= htmlspecialchars($skill->titre) ?></strong><br>

              <?php
              if (!empty($skill->extra)) { ?>
                <?= nl2br(htmlspecialchars($skill->extra)) ?><br>
              <?php }

              if ($mode === "Backoffice"): ?>
                <form action="" method="post" class="bouton_suppression_skill inactive">
                  <input type="hidden" name="id_pour_suppression" value="<?= $skill->id ?>">
                  <input type="submit" value="Supprimer" class="delete-button">
                </form>
              <?php endif; ?>

              <p style="display: none;" class="chemin_logo_popup"><?= $skill->chemin_logo ?></p>
              <p style="display: none;" class="description_popup"><?= $skill->description ?></p>
              <p style="display: none;" class="lien_popup"><?= $skill->lien ?></p>

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

    // initialisation des zones
    zone_formulaire_ajout_skill = document.getElementById("formulaire_ajout_skill");
    zones_boutons_supprimant_skill = document.querySelectorAll(".bouton_suppression_skill");
    zone_bouton_montrant_mode_backoffice = document.getElementById("bouton_montrant_mode_backoffice");
    zone_bouton_montrant_formulaire = document.getElementById("bouton_montrant_formulaire");

    document.querySelector('.switch-input').onclick = () => {

      if (zone_formulaire_ajout_skill.classList.contains("active-form")) {
        zone_formulaire_ajout_skill.classList.remove('active-form');
        zone_formulaire_ajout_skill.classList.add('inactive-form');
        zones_boutons_supprimant_skill.forEach(bouton => {
          bouton.classList.remove('active');
          bouton.classList.add('inactive');
        });
      } else {
        zone_formulaire_ajout_skill.classList.remove('inactive-form');
        zone_formulaire_ajout_skill.classList.add('active-form');
        zones_boutons_supprimant_skill.forEach(bouton => {
          bouton.classList.remove('inactive');
          bouton.classList.add('active');
        });
      }

      if (zone_formulaire_ajout_skill.classList.contains("form-is-visible")) {
        zone_formulaire_ajout_skill.classList.remove('form-is-visible');
        zone_formulaire_ajout_skill.classList.add('form-is-hidden');
      }

    };

    zone_bouton_montrant_formulaire.onclick = () => {

      if (zone_formulaire_ajout_skill.classList.contains("form-is-visible")) {
        zone_formulaire_ajout_skill.classList.remove('form-is-visible');
        zone_formulaire_ajout_skill.classList.add('form-is-hidden');
      } else {
        zone_formulaire_ajout_skill.classList.remove('form-is-hidden');
        zone_formulaire_ajout_skill.classList.add('form-is-visible');
      }

    };

  }

  zone_qui_affiche_explication = document.querySelectorAll(".boite_skill");

  // initialisation de la boite d'information
  const overlay = document.createElement("div");
  overlay.style.position = "fixed";
  overlay.style.top = "0";
  overlay.style.left = 0;
  overlay.style.width = "100%";
  overlay.style.height = "100%";
  overlay.style.backgroundColor = "rgba(0, 0, 0, 0.5)";
  overlay.style.zIndex = 0;
  overlay.className = "inactive-boite-information";

  barre_du_bas = document.querySelector('.bottom-bar').getBoundingClientRect();
  barre_du_haut = document.querySelector('.top-bar').getBoundingClientRect();
  position_boite_y = barre_du_haut.height + ((window.innerHeight - (barre_du_haut.height + barre_du_bas.height)) / 2);
  position_boite_x = window.innerWidth / 2;

  const boiteInformation = document.createElement("div");
  boiteInformation.style.backgroundColor = "#ccc";
  boiteInformation.style.padding = "20px";
  boiteInformation.style.borderRadius = "8px";
  boiteInformation.style.boxShadow = "0 0 15px rgba(0,0,0,0.3)";
  boiteInformation.style.position = 'fixed';
  boiteInformation.style.top = position_boite_y + 'px';
  boiteInformation.style.left = position_boite_x + 'px';
  boiteInformation.style.transform = 'translate(-50%, -50%)';

  const boutonQuitterBoiteInformation = document.createElement("button");
  boutonQuitterBoiteInformation.id = "bouton_quitte_explication";
  boutonQuitterBoiteInformation.type = "button";
  boutonQuitterBoiteInformation.classList = "btn button-a";

  const zoneBoiteInformation = document.createElement("div");

  boiteInformation.appendChild(boutonQuitterBoiteInformation);
  boiteInformation.appendChild(zoneBoiteInformation);
  overlay.appendChild(boiteInformation);
  document.body.appendChild(overlay);

  // description
  // affiche la boite d'aide si on clique sur le bouton d'explication
  zone_qui_affiche_explication.forEach(zone => {
    zone.addEventListener("click", function(e) {
      e.stopPropagation(); // Évite de fermer tout de suite

      // récupere information
      const chemin_dimage_du_skill = zone.querySelector(".chemin_logo_popup").textContent;
      const description_du_skill = zone.querySelector(".description_popup").textContent;
      const lien_du_skill = zone.querySelector(".lien_popup").textContent;

      // cree la boite
      const image_boite_information = document.createElement("img");
      image_boite_information.src = chemin_dimage_du_skill;

      const description_boite_information = document.createElement("h2");
      description_boite_information.textContent = description_du_skill;

      const lien_boite_information = document.createElement("a");
      lien_boite_information.textContent = "lien";
      lien_boite_information.href = lien_du_skill;
      lien_boite_information.target = "_blank";

      zoneBoiteInformation.innerHTML = ''; // vide l'interieur de la boite 
      zoneBoiteInformation.appendChild(image_boite_information);
      zoneBoiteInformation.appendChild(description_boite_information);
      if (lien_du_skill != '') {
        zoneBoiteInformation.appendChild(lien_boite_information);
      }

      // replace la boite
      barre_du_bas = document.querySelector('.bottom-bar').getBoundingClientRect();
      barre_du_haut = document.querySelector('.top-bar').getBoundingClientRect();
      taille_screen_gameboy = (window.innerHeight - (barre_du_haut.height + barre_du_bas.height))
      image_boite_information.style.maxHeight = (taille_screen_gameboy / 5) + "px";
      image_boite_information.style.maxWidth = "auto";
      position_boite_y = barre_du_haut.height + (taille_screen_gameboy / 2);
      position_boite_x = window.innerWidth / 2;
      boiteInformation.style.top = position_boite_y + 'px';
      boiteInformation.style.left = position_boite_x + 'px';
      boiteInformation.style.transform = 'translate(-50%, -50%)';

      // montre la boite
      overlay.className = "active-boite-information";

    });
  });

  // Empêcher le clic dans la boîte d'aide de fermer cette denriere
  boiteInformation.addEventListener("click", function(e) {
    e.stopPropagation();
  });

  // Empêcher le clic dans le texte de la boîte d'aide de fermer cette denriere
  zoneBoiteInformation.addEventListener("click", function(e) {
    e.stopPropagation();
  });

  // Ferme la boite d'aide si on clique en dehors (sur l'overlay quoi)
  overlay.addEventListener("click", function() {
    overlay.className = "inactive-boite-information";
  });

  boutonQuitterBoiteInformation.addEventListener("click", function() {
    overlay.className = "inactive-boite-information";
  });
</script>

</html>