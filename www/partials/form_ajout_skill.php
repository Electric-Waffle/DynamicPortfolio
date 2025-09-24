<?php
if ($mode == "Backoffice") {
    ?>
    <div id="formulaire_ajout_skill" class="inactive-form form_for_adding form-is-hidden">

        <div class="accordion">
            <span class="arrow">►</span> 
            <span class="title">Ajouter un Skill</span>
        </div>

        <div class="panel">
        <form action="" method="post" enctype="multipart/form-data">
            <div>
                <label for="image" >Image :</label>
                <input required type="file" name="image" id="image" style="margin-right: 10px;" accept=".jpg, .jpeg, .png">
                <br>
                <label for="titre" style="margin-right: 10px;">Titre :</label>
                <input required type="text" id="titre" name="titre">
                <br>
                <label for="extra" >Extra :</label>
                <input type="text" id="extra" name="extra" style="margin-right: 10px;">
                <br>
                <label required for="description" >Description :</label>
                <input type="text" id="description" name="description" style="margin-right: 10px;">
                <br>
                <label for="lien" >Lien :</label>
                <input type="text" id="lien" name="lien" style="margin-right: 10px;">
                <br>
                
                <input type="submit" value="Valider">
            </div>
        </form>
        </div>

        <button class="btn button-a" id="bouton_montrant_formulaire" style="pointer-events: auto;" type="button"></button>

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