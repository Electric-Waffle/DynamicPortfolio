<?php
if ($mode == "Backoffice") {
    ?>
    <div id="formulaire_ajout_contact" class="inactive-form form_for_adding">

        <div>
            <h3>Ajouter un Contact</h3>
        </div>

        <form action="" method="post" enctype="multipart/form-data">
            <div>
                <label for="image" style="margin-left: 10px; margin-right: 10px;">Logo :</label>
                <input type="file" name="image" id="image" style="margin-right: 10px;" accept=".jpg, .jpeg, .png">
                <label for="nom" style="margin-left: 10px; margin-right: 10px;">Nom :</label>
                <input type="text" id="nom" name="nom" style="margin-right: 10px;">
                <label for="description" style="margin-left: 10px; margin-right: 10px;">Lien :</label>
                <input type="text" id="description" name="description" style="margin-right: 10px;">
                <input type="submit" value="Valider">
            </div>
        </form>

    </div>


    <?php
}
?>