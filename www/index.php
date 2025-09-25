<?php
@session_start();
require("../core/View.php");

use Core\View;

// Controlleur

$mode = "User";
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

// récupération des différents "bouts de page"
$barre_navigation = View::render("barre_bouton.php", ["mode" => $mode]);
$barre_droite = View::render("barre_droite.php");
$barre_gauche = View::render("barre_gauche.php");
$barre_haut = View::render("barre_haut.php", ["mode" => $mode, "type" => "without_button"]);

// Vue
?>


<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GameBoy Page</title>
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
      <div class="div-inside-content" style="background: #d2c45b;">
        <h1>
          Profil
        </h1>
        <p>Je m'appelle Favre Sylvain, j'ai 23 ans, et je suis un étudiant du BTS SIO du lycée Dominique Villars à Gap en première année.
          Je suis né a Gap le 18 Juin 2001 et j'habite aujourd'hui cette même ville.
          J'aime les jeux vidéos, la sculpture en coche, la poésie anglaise, la musique, le sport et les ordinateurs de manière générale.
        </p>
        <br>
        <h1>En quelques points : </h1>
        <p>
          Gapencais de 23 ans <br>
          Touche a tout en informatique <br>
          Natation, Tennis de Table, Tennis, Badminton, Escrime, Ski de Fond, Ski de Piste <br>
          Scientifique <br>
          Artiste <br>
        </p>
        <br>

        <h1>
          Ma motivation :
        </h1>

        <p>
          Mon intérêt pour l'informatique a commencé dès mon adolescence, lorsque j'ai
          commencé à modifier des fichiers dans certains jeux vidéo pour y appliquer ma propre
          vision du programme et améliorer mon expérience de joueur. J’ai interragi avec les
          entrailles de nombreux programmes créés pour des besoins spécifiques (émulateurs en
          C+, Ui Python pour installer des mods, recherche/applications de patch binaires pour
          certains programmes). Et avec l’apprentissage et mise en place des connaissances
          apprises lors de mes formations, j’ai consolidé mes attentes et ma motivation à
          travailler dans le domaine du developpement.
        </p>
        <br>

        <h1>
          Qualités, Défauts :
        </h1>

        <ul>
          + Force de Motivation <br>
          + Collaboratif <br>
          + Créatif <br> ============ <br>
          - Retardataire <br>
          - Emotif <br>
          - Entêté <br>
        </ul>
      </div>
    </main>

    <!-- Barre droite -->
    <?php echo $barre_droite; ?>

  </div>

  <!-- Barre inférieure avec les contrôles -->
  <?php echo $barre_navigation; ?>


</body>

</html>