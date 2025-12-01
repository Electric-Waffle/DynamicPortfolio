<?php
if ($mode == "Backoffice") {
    ?>
    <div id="formulaire_ajout_article" class="inactive-form form_for_adding form-is-hidden">

        <div class="accordion">
            <span class="arrow">►</span> 
            <span class="title">Ajouter un Article</span>
        </div>

        <div class="panel">
        <form action="" method="post" enctype="multipart/form-data">
            <div>
                <label  for="title" >Titre :</label>
                <input required type="text" id="title" name="title">
                <br>
                <label for="content">Contenu :</label>
                <textarea required name="content" id="content"></textarea>
                <br>
                <label for="link">Lien :</label>
                <input required type="text" name="link" id="link">
                <br>
                <input type="submit" value="Valider">
            </div>
        </form>
        </div>

        <button class="btn button-backoffice" id="bouton_montrant_formulaire" style="pointer-events: auto;" type="button"></button>

    </div>


    <?php
}
?>

<script>
// <!-- pour le menu déroulant -->
document.querySelectorAll(".accordion").forEach(acc => {
  acc.addEventListener("click", () => {
    acc.classList.toggle("open");
    const panel = acc.nextElementSibling;
    panel.classList.toggle("show");
  });
});
</script>