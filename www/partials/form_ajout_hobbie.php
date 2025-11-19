<?php
if ($mode == "Backoffice") {
    ?>
    <div id="formulaire_ajout_hobby" class="inactive-form form_for_adding form-is-hidden">

        <div class="accordion">
            <span class="arrow">►</span> 
            <span class="title">Ajouter un Hobbie</span>
        </div>

        <div class="panel">
        <form action="" method="post" enctype="multipart/form-data">
            <div>
                <label  for="titre" >Titre :</label>
                <input required type="text" id="titre" name="titre">
                <br>
                <label for="description">Description :</label>
                <input required type="text" id="description" name="description">
                <br>
                <label for="image">Image :</label>
                <input required type="file" name="image" id="image" accept=".jpg, .jpeg, .png">
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