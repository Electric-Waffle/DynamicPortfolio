<?php
@session_start();

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
if (isset($_POST["titre"]) && isset($_POST["description"]) && isset($_POST["lien"]) && isset($_POST["tags_selectionnes"]) && isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
  $titre = $_POST["titre"];
  $description = $_POST["description"];
  $lien = $_POST["lien"];
  $tags_selectionnes_str = $_POST['tags_selectionnes']; 
  $tableau_ids_tags_selectionnes = array_filter(
    explode(',', $tags_selectionnes_str),
    fn($val) => $val !== '' // On garde "0", on enlève juste les chaînes vides
  );
  $chemin_image_temp = $_FILES['image']['tmp_name'];
  $nom_image_temp = $_FILES['image']['name'];
  $message_erreur .= BDD::ajoutProjectDansBdd($chemin_image_temp, $nom_image_temp, $titre, $description, $lien, $tableau_ids_tags_selectionnes);
}
// si informations de nom et de description pour un tag, on les récupère et on rajoute
if (isset($_POST["nom_tag"]) && isset($_POST["description_tag"])) {
  $nom = $_POST["nom_tag"];
  $description = $_POST["description_tag"];
  $message_erreur .= BDD::ajoutTagDansBdd($nom, $description);
}

// si informations d'id a supprimer, on supprime
if (isset($_POST["id_pour_suppression"])) {
  $id_pour_suppression_de_project = $_POST["id_pour_suppression"];
  $message_erreur .= BDD::suppressionProjectDansBdd($id_pour_suppression_de_project);
}
if (isset($_POST["id_pour_suppression_tag"])) {
  $id_pour_suppression_de_tag = $_POST["id_pour_suppression_tag"];
  $message_erreur .= BDD::suppressionTagDansBdd($id_pour_suppression_de_tag);
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

// création d'une liste de tout les id de tag utilisés dans des projets (pour éviter suppression d'un tag déja utilisé)
$liste_id_tags_utilises = [];
foreach ($gestionnaireProject->recupererProjects() as $projet) {
  foreach ($projet->recupererTags() as $id_tag_utilise) {
    if (!in_array($id_tag_utilise, $liste_id_tags_utilises)) {
      $liste_id_tags_utilises[]=$id_tag_utilise;
    }
  }
}

// récupération des différents "bouts de page"
$barre_navigation = View::render("barre_bouton.php", ["mode" => $mode, "message_erreur" => $message_erreur]);
$barre_droite = View::render("barre_droite.php");
$barre_gauche = View::render("barre_gauche.php");
$barre_haut = View::render("barre_haut.php", ["mode" => $mode, "type" => "with_button"]);
$formulaire_ajout = View::render("form_ajout_project.php", ["mode" => $mode, 'tags_disponibles' => $gestionnaireProject->recupererTableauTagsDisponible(), 'liste_id_tags_utilises' => $liste_id_tags_utilises]);

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

      <div class="div-inside-content" style="height: auto; background-color: #d2c45b;">

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
                    </a>

                    <div class="kpis">
                      <?php
                      foreach ($projet->recupererTags() as $id_tag) {
                        $tag_quon_veut_afficher = $gestionnaireProject->recupereTagDisponibleGraceAId($id_tag);
                        ?>

                        <span class="badge boite_tag">
                          <?= $tag_quon_veut_afficher->titre ?>
                          <p style="display: none;" class="description_popup"><?= $tag_quon_veut_afficher->description ?></p>
                          <p style="display: none;" class="titre_popup"><?= $tag_quon_veut_afficher->titre ?></p>
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

  zone_qui_affiche_explication = document.querySelectorAll(".boite_tag");

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
      console.log("HALLo")

      // récupere information
      const description_du_tag = zone.querySelector(".description_popup").textContent;
      const titre_du_tag = zone.querySelector(".titre_popup").textContent;

      // cree la boite
      const titre_boite_information = document.createElement("h1");
      titre_boite_information.textContent = titre_du_tag;

      const description_boite_information = document.createElement("h2");
      description_boite_information.textContent = description_du_tag;


      zoneBoiteInformation.innerHTML = ''; // vide l'interieur de la boite 
      zoneBoiteInformation.appendChild(titre_boite_information);
      zoneBoiteInformation.appendChild(description_boite_information);

      // replace la boite
      barre_du_bas = document.querySelector('.bottom-bar').getBoundingClientRect();
      barre_du_haut = document.querySelector('.top-bar').getBoundingClientRect();
      taille_screen_gameboy = (window.innerHeight - (barre_du_haut.height + barre_du_bas.height))
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