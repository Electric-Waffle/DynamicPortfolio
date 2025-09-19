<?php
session_start();

require("../core/gestion_bdd.php");
require("../core/View.php");

use Model\BDD;
use Core\View;

// Controlleur
$mode = "User";
$message_erreur = "";
// si image trop grande, erreur
if (isset($_FILES['image']) && $_FILES['image']['error'] == 1) {
  $message_erreur = "ERREUR : Image trop volumineuse.";
}
// si informations de description et d'image et de nom, on les récupère et on rajoute
if (isset($_POST["description"]) && isset($_POST["nom"]) && isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
  $description = $_POST["description"];
  $nom = $_POST["nom"];
  $chemin_image_temp = $_FILES['image']['tmp_name'];
  $nom_image_temp = $_FILES['image']['name'];
  $message_erreur .= BDD::ajoutContactDansBdd($chemin_image_temp, $nom_image_temp,  $description, $nom);
}

// si informations d'id a supprimer, on supprime
if (isset($_POST["id_pour_suppression"])) {
  $id_pour_suppression_de_contact = $_POST["id_pour_suppression"];
  $message_erreur .= BDD::suppressionContactDansBdd($id_pour_suppression_de_contact);
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

// récupération des données de contact dans la bdd
$gestionnaireContacts = BDD::recupContactsDansBdd();

// récupération des différents "bouts de page"
$barre_navigation = View::render("barre_bouton.php", ["mode" => $mode, "message_erreur" => $message_erreur]);
$barre_droite = View::render("barre_droite.php");
$barre_gauche = View::render("barre_gauche.php");
$barre_haut = View::render("barre_haut.php", ["mode" => $mode, "type" => "with_button"]);
$formulaire_ajout = View::render("form_ajout_contact.php", ["mode" => $mode]);

// Vue
?>


<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact</title>
  <link rel="stylesheet" href="style.css" />
</head>

<body>

  <!-- Barre Haut -->
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

        <h1>Contactez Moi !</h1>

        <br>


        <div class="contact-list">
  <?php foreach ($gestionnaireContacts->recupererContacts() as $contact): ?>

            <div class="contact-item">

              <img src="<?= htmlspecialchars($contact->chemin_image) ?>" alt="logo de <?= htmlspecialchars($contact->nom) ?>"
                class="contact-logo">
        
              <a target="_blank" href="<?= htmlspecialchars($contact->description) ?>" class="contact-link">
                <?= htmlspecialchars($contact->nom) ?>
              </a>
        
              <?php if ($mode == "Backoffice"): ?>
                <form action="" method="post" class="delete-form inactive">
                  <input type="hidden" name="id_pour_suppression" value="<?= $contact->id ?>">
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

  <?php echo $barre_navigation; ?>

</body>

<script>
  // On récupère la valeur PHP dans une variable JS
  let mode = <?= json_encode($mode) ?>;

  if (mode === "Backoffice") {
    zone_formulaire_ajout_contact = document.getElementById("formulaire_ajout_contact");
    zones_boutons_supprimant_contact = document.querySelectorAll(".delete-form");
    zone_bouton_montrant_mode_backoffice = document.getElementById("bouton_montrant_mode_backoffice");
    zone_bouton_montrant_formulaire = document.getElementById("bouton_montrant_formulaire");

    document.querySelector('.switch-input').onclick = () => {

      if (zone_formulaire_ajout_contact.classList.contains("active-form")) {
        zone_formulaire_ajout_contact.classList.remove('active-form');
        zone_formulaire_ajout_contact.classList.add('inactive-form');
        zones_boutons_supprimant_contact.forEach(bouton => {
          bouton.classList.remove('active');
          bouton.classList.add('inactive');
        });
      } else {
        zone_formulaire_ajout_contact.classList.remove('inactive-form');
        zone_formulaire_ajout_contact.classList.add('active-form');
        zones_boutons_supprimant_contact.forEach(bouton => {
          bouton.classList.remove('inactive');
          bouton.classList.add('active');
        });
      }

          if (zone_formulaire_ajout_contact.classList.contains("form-is-visible")) {
        zone_formulaire_ajout_contact.classList.remove('form-is-visible');
        zone_formulaire_ajout_contact.classList.add('form-is-hidden');
      }

    };

    zone_bouton_montrant_formulaire.onclick = () => {

      if (zone_formulaire_ajout_contact.classList.contains("form-is-visible")) {
        zone_formulaire_ajout_contact.classList.remove('form-is-visible');
        zone_formulaire_ajout_contact.classList.add('form-is-hidden');
      } else {
        zone_formulaire_ajout_contact.classList.remove('form-is-hidden');
        zone_formulaire_ajout_contact.classList.add('form-is-visible');
      }

    };

  }
</script>

</html>