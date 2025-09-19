<?php
if ($mode == "Backoffice") {
    ?>
    <div id="formulaire_ajout_timeline" class="inactive-form form_for_adding form-is-hidden">

        <div>
            <h3>Ajouter une Timeline</h3>
        </div>

        <form action="" method="post">
            <div>
                <label for="date" style="margin-right: 10px;">Date :</label>
                <input required type="text" id="date" name="date">
                <label for="description" style="margin-left: 10px; margin-right: 10px;">Evenement :</label>
                <input required type="text" id="description" name="description" style="margin-right: 10px;">
                <input type="submit" value="Valider">
            </div>
        </form>

        <button class="btn button-a" id="bouton_montrant_formulaire" style="pointer-events: auto;" type="button"></button>

    </div>


    <?php
}
?>