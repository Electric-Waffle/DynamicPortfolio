<?php
if ($mode == "Backoffice") {
    ?>
    <div id="formulaire_ajout_timeline" class="inactive-form form_for_adding form-is-hidden">

        <div class="accordion">
            <span class="arrow">►</span> 
            <span class="title">Ajouter une Timeline</span>
        </div>

        <div class="panel">
        <form action="" method="post">
            <div>
                <label for="date" style="margin-right: 10px;">Date :</label>
                <input required type="text" id="date" name="date">
                <br>
                <label for="description" >Evenement :</label>
                <input required type="text" id="description" name="description">
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