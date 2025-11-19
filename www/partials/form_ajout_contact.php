<?php
if ($mode == "Backoffice") {
    ?>
    <div id="formulaire_ajout_contact" class="inactive-form form_for_adding form-is-hidden">

        <div class="accordion">
            <span class="arrow">►</span> 
            <span class="title">Ajouter un Contact</span>
        </div>

        <div class="panel">
        <form action="" method="post" enctype="multipart/form-data">
            <div>
                <label for="image" style="margin-left: 10px; margin-right: 10px;">Logo :</label>
                <input required type="file" name="image" id="image" accept=".jpg, .jpeg, .png">
                <br>
                <label for="nom" style="margin-left: 10px; margin-right: 10px;">Nom :</label>
                <input required type="text" id="nom" name="nom">
                <br>
                <label for="description" style="margin-left: 10px; margin-right: 10px;">Lien :</label>
                <input required type="text" id="description" name="description">
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