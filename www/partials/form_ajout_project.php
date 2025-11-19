<!-- Début formulaire ajout -->

<?php
if ($mode == "Backoffice") {
    ?>
    <div id="formulaire_ajout_hobby" class="inactive-form form_for_adding form-is-hidden">

        <div class="accordion">
            <span class="arrow">►</span> 
            <span class="title">Ajouter un Projet</span>
        </div>

        <div class="panel">
        <form action="" method="post" enctype="multipart/form-data">
            <div>
                <label  for="titre" >Titre :</label>
                <input required type="text" id="titre" name="titre">
                <br>
                <label for="description" >Description :</label>
                <input required type="text" id="description" name="description" style="margin-right: 10px;">
                <br>
                <label for="lien" >Lien Vers Projet :</label>
                <input required type="text" id="lien" name="lien" style="margin-right: 10px;">
                <br>
                <label for="image" >Image :</label>
                <input required type="file" name="image" id="image" style="margin-right: 10px;" accept=".jpg, .jpeg, .png">
                <p>Tags Du Nouveau Projet :</p>
                <div class="kpis kpis-limited form-kpis choix_tag_disponible">

                </div>
                <input type="hidden" name="tags_selectionnes" id="tags_selectionnes">
                <br>

                <input type="submit" value="Valider">
                <br>
                <br>

                <p>Tags Disponibles :</p>
                <div class="kpis kpis-limited form-kpis liste_tag_disponible">
                    <?php foreach ($tags_disponibles as $tag): ?>
                        <span class="badge-formulaire" data-id="<?= htmlspecialchars($tag->id) ?>">
                            <?= htmlspecialchars($tag->titre) ?>
                        </span>
                    <?php endforeach; ?>
                </div>

            </div>
        </form>
        </div>


        <div class="accordion">
            <span class="arrow">►</span> 
            <span class="title">Ajouter un Tag</span>
        </div>

        <div class="panel">
        <form action="" method="post">
            <div>
                <label  for="nom_tag" style="margin-right: 10px;">Nom :</label>
                <input required type="text" id="nom_tag" name="nom_tag">
                <label for="description_tag" style="margin-left: 10px; margin-right: 10px;">Description :</label>
                <input required type="text" id="description_tag" name="description_tag" style="margin-right: 10px;">
                <input type="submit" value="Valider">
            </div>
        </form>
        </div>

        <div class="accordion">
            <span class="arrow">►</span> 
            <span class="title">Supprimer un Tag</span>
        </div>

        <div class="panel">
        <p>Tags Disponibles :</p>
                <div class="kpis kpis-limited form-kpis liste_tag_disponible">
                    <?php foreach ($tags_disponibles as $tag):

                        // tag utilisé, ne peut pas etre supprimé
                        if (in_array($tag->id, $liste_id_tags_utilises)) {
                        ?>
                            <span class="non-deletable-badge">
                                <?= htmlspecialchars($tag->titre) ?>
                            </span>
                        <?php
                        }

                        // tag non utilisé, peut etre supprimé
                        else {
                        ?>
                            <form action="" method="post">
                            <input type="hidden" name="id_pour_suppression_tag" id="id_pour_suppression_tag" value="<?= htmlspecialchars($tag->id) ?>">
                            <input class="badge" type="submit" value="<?= htmlspecialchars($tag->titre) ?>">
                            </form>
                        <?php
                        }

                    endforeach; ?>
                </div>
        </div>

        <button class="btn button-backoffice" id="bouton_montrant_formulaire" style="pointer-events: auto;" type="button"></button>

    </div>


    <?php
}
?>

<!-- pour l'ajout de tags -->
<script>
document.addEventListener("DOMContentLoaded", () => {
  const listeDispo = document.querySelector(".liste_tag_disponible");
  const listeChoix = document.querySelector(".choix_tag_disponible");
  const hiddenInput = document.getElementById("tags_selectionnes");

  // Met à jour le champ caché avec les IDs des tags choisis
  function updateHiddenInput() {
    const ids = Array.from(listeChoix.querySelectorAll(".badge-formulaire"))
                     .map(badge => badge.dataset.id);
    hiddenInput.value = ids.join(",");
    console.log(ids);
    
  }

  // Fonction de déplacement
  function moveTag(badge, targetList) {
    targetList.appendChild(badge);
    updateHiddenInput();
  }

  // Délégation d'événements (gère les clics sur badges)
  document.body.addEventListener("click", (e) => {
    if (e.target.classList.contains("badge-formulaire")) {
      if (e.target.parentElement === listeDispo) {
        moveTag(e.target, listeChoix);
      } else {
        moveTag(e.target, listeDispo);
      }
    }
  });
});

// <!-- pour le menu déroulant -->
document.querySelectorAll(".accordion").forEach(acc => {
  acc.addEventListener("click", () => {
    acc.classList.toggle("open");
    const panel = acc.nextElementSibling;
    panel.classList.toggle("show");
  });
});
</script>

