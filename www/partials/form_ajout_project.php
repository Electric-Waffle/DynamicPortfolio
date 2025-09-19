<?php
if ($mode == "Backoffice") {
    ?>
    <div id="formulaire_ajout_hobby" class="inactive-form form_for_adding form-is-hidden">

        <div>
            <h3>Ajouter un Projet</h3>
        </div>

        <form action="" method="post" enctype="multipart/form-data">
            <div>
                <label  for="titre" style="margin-right: 10px;">Titre :</label>
                <input required type="text" id="titre" name="titre">
                <label for="description" style="margin-left: 10px; margin-right: 10px;">Description :</label>
                <input required type="text" id="description" name="description" style="margin-right: 10px;">
                <label for="image" style="margin-left: 10px; margin-right: 10px;">Image :</label>
                <input required type="file" name="image" id="image" style="margin-right: 10px;" accept=".jpg, .jpeg, .png">
                <input type="submit" value="Valider">
            </div>
        </form>

        <div>
            <h3>Ajouter un Tag</h3>
        </div>

        <form action="" method="post">
            <div>
                <label  for="titre" style="margin-right: 10px;">Titre :</label>
                <input required type="text" id="titre" name="titre">
                <label for="description" style="margin-left: 10px; margin-right: 10px;">Description :</label>
                <input required type="text" id="description" name="description" style="margin-right: 10px;">
                <input type="submit" value="Valider">
            </div>
        </form>

        <button class="btn button-a" id="bouton_montrant_formulaire" style="pointer-events: auto;" type="button"></button>

    </div>


    <?php
}
?>