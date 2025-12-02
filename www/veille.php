<?php
@session_start();

require("../core/gestion_bdd.php");
require("../core/View.php");

use Core\BDD;
use Core\View;

// Controlleur

$mode = "User";
$message_erreur = "";

// si informations de titre et de contenu et de lien, on les récupère (+ la date) et on rajoute
if (isset($_POST["title"]) && isset($_POST["content"]) && isset($_POST["link"])) {
  $titre = $_POST["title"];
  $contenu = $_POST["content"];
  $lien = $_POST["link"];
  $date = date("Y-m-d H:i:s");
  $message_erreur .= BDD::ajoutArticleDansBdd($titre, $contenu, $lien, $date);
}

// si informations d'id a supprimer, on supprime
if (isset($_POST["id_pour_suppression"])) {
  $id_pour_suppression_de_article = $_POST["id_pour_suppression"];
  $message_erreur .= BDD::suppressionArticleDansBdd($id_pour_suppression_de_article);
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

// récupération des données de article dans la bdd
$gestionnaireArticle = BDD::recupArticlesDansBdd();

// récupération des différents "bouts de page"
$barre_navigation = View::render("barre_bouton.php", ["mode" => $mode, "message_erreur" => $message_erreur]);
$barre_droite = View::render("barre_droite.php");
$barre_gauche = View::render("barre_gauche.php");
$barre_haut = View::render("barre_haut.php", ["mode" => $mode, "type" => "with_button"]);
$formulaire_ajout = View::render("form_ajout_article.php", ["mode" => $mode]);

// Vue

?>


<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Blog</title>
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

        <h1>Veille Technologique : Laravelle</h1>

        <br>


        <div class="affichage_articles">
            <?php foreach ($gestionnaireArticle->recupererArticles() as $article): ?>

                <div class="boite_about_intro article-blog">

                    <div class="accordion">
                      <span class="arrow">►</span> 
                      <span class="title"><h1><strong><?= $article->title ?></strong></h1><br></span>
                    </div>

                    <!-- <div class="article-blog-barre-droite"></div>
                    <div class="article-blog-barre-gauche"></div> -->
                    <div class="panel">
                      
                      <i>Le <?= ($article->createdAt) ?></i> - 
                      <a href="<?= ($article->link) ?>"><i>source</i></a>

                      <p><strong><?= nl2br(htmlspecialchars($article->content)) ?></strong></p><br>
                      
                      <?php if ($mode === "Backoffice"): ?>
                          <form action="" method="post" class="bouton_suppression_article inactive">
                              <input type="hidden" name="id_pour_suppression" value="<?= $article->id ?>">
                              <input type="submit" value="Supprimer" class="delete-button">
                          </form>
                      <?php endif; ?>
                    </div>

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
    zone_formulaire_ajout_article = document.getElementById("formulaire_ajout_article");
    zones_boutons_supprimant_article = document.querySelectorAll(".bouton_suppression_article");
    zone_bouton_montrant_mode_backoffice = document.getElementById("bouton_montrant_mode_backoffice");
    zone_bouton_montrant_formulaire = document.getElementById("bouton_montrant_formulaire");

    document.querySelector('.switch-input').onclick = () => {

      if (zone_formulaire_ajout_article.classList.contains("active-form")) {
        zone_formulaire_ajout_article.classList.remove('active-form');
        zone_formulaire_ajout_article.classList.add('inactive-form');
        zones_boutons_supprimant_article.forEach(bouton => {
          bouton.classList.remove('active');
          bouton.classList.add('inactive');
        });
      } else {
        zone_formulaire_ajout_article.classList.remove('inactive-form');
        zone_formulaire_ajout_article.classList.add('active-form');
        zones_boutons_supprimant_article.forEach(bouton => {
          bouton.classList.remove('inactive');
          bouton.classList.add('active');
        });
      }

      if (zone_formulaire_ajout_article.classList.contains("form-is-visible")) {
          zone_formulaire_ajout_article.classList.remove('form-is-visible');
          zone_formulaire_ajout_article.classList.add('form-is-hidden');
        }

    };

    zone_bouton_montrant_formulaire.onclick = () => {

      if (zone_formulaire_ajout_article.classList.contains("form-is-visible")) {
        zone_formulaire_ajout_article.classList.remove('form-is-visible');
        zone_formulaire_ajout_article.classList.add('form-is-hidden');
      } else {
        zone_formulaire_ajout_article.classList.remove('form-is-hidden');
        zone_formulaire_ajout_article.classList.add('form-is-visible');
      }

    };

  }


document.addEventListener("DOMContentLoaded", () => {

    document.querySelectorAll('.article-blog').forEach(container => {

        const containerWidth = container.offsetWidth;

        const barWidth = containerWidth * 0.12;
        const spacing = containerWidth * 0.55;

        let x = -barWidth;

        while (x < containerWidth + barWidth) {

            const bar = document.createElement("div");
            bar.classList.add("article-blog-barre");

            bar.style.width = barWidth + "px";
            bar.style.left = x + "px";

            container.appendChild(bar);

            x += spacing;
        }

        // (Si tu veux vraiment cette barre supplémentaire)
        const bar = document.createElement("div");
        bar.classList.add("article-blog-barre");
        bar.style.width = barWidth + "px";
        bar.style.left = x + "px";
        container.appendChild(bar);

    });
});


// <!-- pour le menu déroulant -->
document.querySelectorAll(".accordion").forEach(acc => {
  acc.addEventListener("click", () => {
    acc.classList.toggle("open");
    const panel = acc.nextElementSibling;
    panel.classList.toggle("show-begin");
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
          panel.classList.toggle("show-end");

        });
    });
  });
});
</script>



</html>