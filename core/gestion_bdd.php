<?php

namespace Core;
require "../model/gestionnaires.php";
require "../model/InteractionFichier.php";

use Model\InteractionFichier;
use Model as M;
use SQLite3;

class BDD {

    private static string $cheminDeLaBDD = '../data/database.db';

    // Fonctions utiles pour le backoffice en général

    static public function deleteImageFromImageId($unIdImage, $nomIdDansBdd, $nomCheminDansBdd, $nomTable)
    {
        $messageErreur = "";
        $cheminImageASupprimer = "";

        // ETAPE 1 : RECUPERATION DU CHEMIN DE LIMAGE A SUPPRIMER
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);

        // Requetage de la bdd
        $requeteChemin = $bdd->prepare("select * from " . $nomTable . " where " . $nomIdDansBdd . " = ?");
        $requeteChemin->bindValue(1, $unIdImage, SQLITE3_INTEGER);
        $resultats = $requeteChemin->execute();

        // La requête a renvoyé un élément ?
        if ($res = $resultats->fetchArray(SQLITE3_ASSOC)) {

            $cheminImageASupprimer = $res[$nomCheminDansBdd];

        } else {
            $messageErreur .= "ERREUR : Pas d'images associées a cet id.\n";
        }

        // ETAPE 2 : SUPPRESSION DE L'IMAGE

        if ($cheminImageASupprimer !== "") {
            $messageErreur .= InteractionFichier::supprimeImageDansFichier($cheminImageASupprimer);
        }

        $bdd->close();

        return $messageErreur;

    }


    // Récup . Ajout . Suppression dans la table [contact_link] de la bdd /data/database.db
    static public function recupContactsDansBdd(){
        // Récupération des contacts dans la base de donnée

        // Connexion a la db
        $connexionBaseDeDonnee = new SQLite3(BDD::$cheminDeLaBDD);
            
        $requete = "select * from contact_link";

        $resultat = $connexionBaseDeDonnee->query($requete);

        $gestionnaire = new M\GestionnaireContacts();

        while ($row = $resultat->fetchArray(SQLITE3_ASSOC)) {

            $unContact = new M\LienContact($row['id_contact'], $row['chemin_logo'], $row['lien'], $row['nom']);

            $gestionnaire->ajouterContact($unContact);

        }

        // Fermeture de la base de donnée
        $connexionBaseDeDonnee->close();

        return $gestionnaire;
    }

    static public function ajoutContactDansBdd($chemin_logo_temp, $nom_logo_temp, $description, $nom){
        // chemin d'image obtenu en général avec $_FILES['image']['tmp_name'] et ca $_FILES['image']['name']
        // AJout d'un contact dans la base de donnée
            
        $message_erreur = "";

        // transformation du chemin temporaire du logo en chemin final
        $tableauResultatAjoutImage = InteractionFichier::transformationImageTempEnFinal($chemin_logo_temp, $nom_logo_temp);
        $cheminFinalNouvelleImage = $tableauResultatAjoutImage["nouveauChemin"];
        $message_erreur .= $tableauResultatAjoutImage["messageErreur"];

        if ($message_erreur != "") {
            return $message_erreur;
        }

        // Connexion a la db
        $connexionBaseDeDonnee = new SQLite3(BDD::$cheminDeLaBDD);

        // Préparation du requetage
        $requetage = $connexionBaseDeDonnee->prepare("insert into contact_link (chemin_logo, lien, nom) values (?, ?, ?)");
            
        // Liage des variables a la requete
        $requetage->bindValue(1, $cheminFinalNouvelleImage, SQLITE3_TEXT);
        $requetage->bindValue(2, $description, SQLITE3_TEXT);
        $requetage->bindValue(3, $nom, SQLITE3_TEXT);
            
        // Execution de la requete
        if ($requetage->execute()) {
                
            // Message en cas de succès de l'édition
            $message_erreur .= "Contact Ajouté Avec Succès";
                
        } else {
                
            // Message en cas d'échec de l'édition
            $message_erreur .= "ERREUR : Lors de l'Ajout du Contact";
                
        }
            
        // Fermeture de la base de donnée
        $connexionBaseDeDonnee->close();

        return $message_erreur;
    }

    static public function suppressionContactDansBdd($unId){
        // Suppression d'un contact dans la base de donnée
            
        // Connexion a la db
        $connexionBaseDeDonnee = new SQLite3(BDD::$cheminDeLaBDD);

        // Suppression de l'image 
        $message_erreur = BDD::deleteImageFromImageId($unId, "id_contact", "chemin_logo", "contact_link");
            
        // Préparation du requetage
        $requetage = $connexionBaseDeDonnee->prepare("delete from contact_link where id_contact = ?");
            
        // Liage des variables a la requete
        $requetage->bindValue(1, $unId, SQLITE3_INTEGER);
            
        // Execution de la requete
        if ($requetage->execute()) {
                
            // Message en cas de succès de l'édition
            $message_erreur .= "Contact Supprimé Avec Succès";
                
        } else {
                
            // Message en cas d'échec de l'édition
            $message_erreur .= "ERREUR : Lors de la Suppression du Contact";
                
        }
            
        // Fermeture de la base de donnée
        $connexionBaseDeDonnee->close();

        return $message_erreur;
    }

    // Récup . Ajout . Suppression dans la table [skill] de la bdd /data/database.db
    static public function recupSkillsDansBdd(){
        // Récupération des skills dans la base de donnée

        // Connexion a la db
        $connexionBaseDeDonnee = new SQLite3(BDD::$cheminDeLaBDD);
            
        $requete = "select * from skill";

        $resultat = $connexionBaseDeDonnee->query($requete);

        $gestionnaire = new M\GestionnaireSkills();

        while ($row = $resultat->fetchArray(SQLITE3_ASSOC)) {

            $unSkill = new M\Skill($row['id_skill'], $row['chemin_logo'], $row['titre'], $row['extra'], $row['description'], $row['lien']);

            $gestionnaire->ajouterSkill($unSkill);

        }

        // Fermeture de la base de donnée
        $connexionBaseDeDonnee->close();

        return $gestionnaire;
    }

    static public function ajoutSkillDansBdd($chemin_logo_temp, $nom_logo_temp, $titre, $extra, $description, $lien){
        // chemin d'image obtenu en général avec $_FILES['image']['tmp_name'] et ca $_FILES['image']['name']
        // Ajout d'un skill dans la base de donnée
            
        $message_erreur = "";

        // transformation du chemin temporaire du logo en chemin final
        $tableauResultatAjoutImage = InteractionFichier::transformationImageTempEnFinal($chemin_logo_temp, $nom_logo_temp);
        $cheminFinalNouvelleImage = $tableauResultatAjoutImage["nouveauChemin"];
        $message_erreur .= $tableauResultatAjoutImage["messageErreur"];

        if ($message_erreur != "") {
            return $message_erreur;
        }

        // Connexion a la db
        $connexionBaseDeDonnee = new SQLite3(BDD::$cheminDeLaBDD);

        // Préparation du requetage
        $requetage = $connexionBaseDeDonnee->prepare("insert into skill (chemin_logo, titre, extra, description, lien) values (?, ?, ?, ?, ?)");
            
        // Liage des variables a la requete
        $requetage->bindValue(1, $cheminFinalNouvelleImage, SQLITE3_TEXT);
        $requetage->bindValue(2, $titre, SQLITE3_TEXT);
        $requetage->bindValue(3, $extra, SQLITE3_TEXT);
        $requetage->bindValue(4, $description, SQLITE3_TEXT);
        $requetage->bindValue(5, $lien, SQLITE3_TEXT);
            
        // Execution de la requete
        if ($requetage->execute()) {
                
            // Message en cas de succès de l'édition
            $message_erreur .= "Skill Ajouté Avec Succès";
                
        } else {
                
            // Message en cas d'échec de l'édition
            $message_erreur .= "ERREUR : Lors de l'Ajout du Skill";
                
        }
            
        // Fermeture de la base de donnée
        $connexionBaseDeDonnee->close();

        return $message_erreur;
    }

    static public function suppressionSkillDansBdd($unId){
        // Suppression d'un skill dans la base de donnée
            
        // Connexion a la db
        $connexionBaseDeDonnee = new SQLite3(BDD::$cheminDeLaBDD);

        // Suppression de l'image 
        $message_erreur = BDD::deleteImageFromImageId($unId, "id_skill", "chemin_logo", "skill");
            
        // Préparation du requetage
        $requetage = $connexionBaseDeDonnee->prepare("delete from skill where id_skill = ?");
            
        // Liage des variables a la requete
        $requetage->bindValue(1, $unId, SQLITE3_INTEGER);
            
        // Execution de la requete
        if ($requetage->execute()) {
                
            // Message en cas de succès de l'édition
            $message_erreur .= "Skill Supprimé Avec Succès";
                
        } else {
                
            // Message en cas d'échec de l'édition
            $message_erreur .= "ERREUR : Lors de la Suppression du Skill";
                
        }
            
        // Fermeture de la base de donnée
        $connexionBaseDeDonnee->close();

        return $message_erreur;
    }

    // Récup . Ajout . Suppression dans la table [hobby] de la bdd /data/database.db
    static public function recupHobbiesDansBdd(){
        // Récupération des hobbies dans la base de donnée

        // Connexion a la db
        $connexionBaseDeDonnee = new SQLite3(BDD::$cheminDeLaBDD);
            
        $requete = "select * from hobby";

        $resultat = $connexionBaseDeDonnee->query($requete);

        $gestionnaire = new M\GestionnaireHobbies();

        while ($row = $resultat->fetchArray(SQLITE3_ASSOC)) {

            $unHobby = new M\Hobby($row['id_hobby'], $row['titre'], $row['description'], $row['chemin_image']);

            $gestionnaire->ajouterHobby($unHobby);

        }

        // Fermeture de la base de donnée
        $connexionBaseDeDonnee->close();

        return $gestionnaire;
    }

    static public function ajoutHobbyDansBdd($chemin_image_temp, $nom_image_temp, $titre, $description){
        // chemin d'image obtenu en général avec $_FILES['image']['tmp_name'] et ca $_FILES['image']['name']
        // Ajout d'un hobby dans la base de donnée
            
        $message_erreur = "";

        // transformation du chemin temporaire du logo en chemin final
        $tableauResultatAjoutImage = InteractionFichier::transformationImageTempEnFinal($chemin_image_temp, $nom_image_temp);
        $cheminFinalNouvelleImage = $tableauResultatAjoutImage["nouveauChemin"];
        $message_erreur .= $tableauResultatAjoutImage["messageErreur"];

        if ($message_erreur != "") {
            return $message_erreur;
        }

        // Connexion a la db
        $connexionBaseDeDonnee = new SQLite3(BDD::$cheminDeLaBDD);

        // Préparation du requetage
        $requetage = $connexionBaseDeDonnee->prepare("insert into hobby (titre, description, chemin_image) values (?, ?, ?)");
            
        // Liage des variables a la requete
        $requetage->bindValue(1, $titre, SQLITE3_TEXT);
        $requetage->bindValue(2, $description, SQLITE3_TEXT);
        $requetage->bindValue(3, $cheminFinalNouvelleImage, SQLITE3_TEXT);
            
        // Execution de la requete
        if ($requetage->execute()) {
                
            // Message en cas de succès de l'édition
            $message_erreur .= "Hobby Ajouté Avec Succès";
                
        } else {
                
            // Message en cas d'échec de l'édition
            $message_erreur .= "ERREUR : Lors de l'Ajout du Hobby";
                
        }
            
        // Fermeture de la base de donnée
        $connexionBaseDeDonnee->close();

        return $message_erreur;
    }

    static public function suppressionHobbyDansBdd($unId){
        // Suppression d'un hobby dans la base de donnée
            
        // Connexion a la db
        $connexionBaseDeDonnee = new SQLite3(BDD::$cheminDeLaBDD);

        // Suppression de l'image 
        $message_erreur = BDD::deleteImageFromImageId($unId, "id_hobby", "chemin_image", "hobby");
            
        // Préparation du requetage
        $requetage = $connexionBaseDeDonnee->prepare("delete from hobby where id_hobby = ?");
            
        // Liage des variables a la requete
        $requetage->bindValue(1, $unId, SQLITE3_INTEGER);
            
        // Execution de la requete
        if ($requetage->execute()) {
                
            // Message en cas de succès de l'édition
            $message_erreur .= "Hobby Supprimé Avec Succès";
                
        } else {
                
            // Message en cas d'échec de l'édition
            $message_erreur .= "ERREUR : Lors de la Suppression du Hobby";
                
        }
            
        // Fermeture de la base de donnée
        $connexionBaseDeDonnee->close();

        return $message_erreur;
    }

    // Récup . Ajout . Suppression dans la table [timeline] de la bdd /data/database.db
    static public function recupTimelinesDansBdd(){
        // Récupération des timelines dans la base de donnée

        // Connexion a la db
        $connexionBaseDeDonnee = new SQLite3(BDD::$cheminDeLaBDD);
            
        $requete = "select * from timeline";

        $resultat = $connexionBaseDeDonnee->query($requete);

        $gestionnaire = new M\GestionnaireTimelines();

        while ($row = $resultat->fetchArray(SQLITE3_ASSOC)) {

            $uneTimeline = new M\Timeline($row['id_timeline'], $row['date'], $row['description']);

            $gestionnaire->ajouterTimeline($uneTimeline);

        }

        // Fermeture de la base de donnée
        $connexionBaseDeDonnee->close();

        return $gestionnaire;
    }

    static public function ajoutTimelineDansBdd($date, $description){
        // Ajout d'une timeline dans la base de donnée
            
        $message_erreur = "";

        // Connexion a la db
        $connexionBaseDeDonnee = new SQLite3(BDD::$cheminDeLaBDD);

        // Préparation du requetage
        $requetage = $connexionBaseDeDonnee->prepare("insert into timeline (date, description) values (?, ?)");
            
        // Liage des variables a la requete
        $requetage->bindValue(1, $date, SQLITE3_TEXT);
        $requetage->bindValue(2, $description, SQLITE3_TEXT);
            
        // Execution de la requete
        if ($requetage->execute()) {
                
            // Message en cas de succès de l'édition
            $message_erreur .= "Timeline Ajoutée Avec Succès";
                
        } else {
                
            // Message en cas d'échec de l'édition
            $message_erreur .= "ERREUR : Lors de l'Ajout de la Timeline";
                
        }
            
        // Fermeture de la base de donnée
        $connexionBaseDeDonnee->close();

        return $message_erreur;
    }

    static public function suppressionTimelineDansBdd($unId){
        // Suppression d'une timeline dans la base de donnée
        $message_erreur = "";

        // Connexion a la db
        $connexionBaseDeDonnee = new SQLite3(BDD::$cheminDeLaBDD);

        // Préparation du requetage
        $requetage = $connexionBaseDeDonnee->prepare("delete from timeline where id_timeline = ?");
            
        // Liage des variables a la requete
        $requetage->bindValue(1, $unId, SQLITE3_INTEGER);
            
        // Execution de la requete
        if ($requetage->execute()) {
                
            // Message en cas de succès de l'édition
            $message_erreur .= "Timeline Supprimée Avec Succès";
                
        } else {
                
            // Message en cas d'échec de l'édition
            $message_erreur .= "ERREUR : lors de la Suppression de la Timeline";
                
        }
            
        // Fermeture de la base de donnée
        $connexionBaseDeDonnee->close();

        return $message_erreur;
    }

    // Récup . Ajout . Suppression dans la table [tag] de la bdd /data/database.db
    static public function recupTagsDisponiblesDansBdd(){
        // Récupération des tags disponibles dans la base de donnée

        // Connexion a la db
        $connexionBaseDeDonnee = new SQLite3(BDD::$cheminDeLaBDD);
            
        $requete = "select * from tag";

        $resultat = $connexionBaseDeDonnee->query($requete);

        $gestionnaire = new M\GestionnaireTags();

        while ($row = $resultat->fetchArray(SQLITE3_ASSOC)) {

            $unTagDisponible = new M\Tag($row['id_tag'], $row['titre'], $row['description']);

            $gestionnaire->ajouterTag($unTagDisponible);

        }

        // Fermeture de la base de donnée
        $connexionBaseDeDonnee->close();

        return $gestionnaire;
    }

    static public function ajoutTagDansBdd($titre, $description){
        // Ajout d'un tag dans la base de donnée
            
        $message_erreur = "";

        // Connexion a la db
        $connexionBaseDeDonnee = new SQLite3(BDD::$cheminDeLaBDD);

        // Préparation du requetage
        $requetage = $connexionBaseDeDonnee->prepare("insert into tag (titre, description) values (?, ?)");
            
        // Liage des variables a la requete
        $requetage->bindValue(1, $titre, SQLITE3_TEXT);
        $requetage->bindValue(2, $description, SQLITE3_TEXT);
            
        // Execution de la requete
        if ($requetage->execute()) {
                
            // Message en cas de succès de l'édition
            $message_erreur .= "Tag Ajouté Avec Succès";
                
        } else {
                
            // Message en cas d'échec de l'édition
            $message_erreur .= "ERREUR : Lors de l'Ajout du Tag";
                
        }
            
        // Fermeture de la base de donnée
        $connexionBaseDeDonnee->close();

        return $message_erreur;
    }

    static public function suppressionTagDansBdd($unId){
        // Suppression d'un tag dans la base de donnée
        $message_erreur = "";

        // Connexion a la db
        $connexionBaseDeDonnee = new SQLite3(BDD::$cheminDeLaBDD);

        // Préparation du requetage
        $requetage = $connexionBaseDeDonnee->prepare("delete from tag where id_tag = ?");
            
        // Liage des variables a la requete
        $requetage->bindValue(1, $unId, SQLITE3_INTEGER);
            
        // Execution de la requete
        if ($requetage->execute()) {
                
            // Message en cas de succès de l'édition
            $message_erreur .= "Tag Supprimé Avec Succès";
                
        } else {
                
            // Message en cas d'échec de l'édition
            $message_erreur .= "ERREUR : Lors de la Suppression du Tag";
                
        }
            
        // Fermeture de la base de donnée
        $connexionBaseDeDonnee->close();

        return $message_erreur;
    }

    // Récup . Ajout . Suppression dans la table [project/tags/pSossede] de la bdd /data/database.db
    static public function recupRelationsTagsProjectsDansBdd(){
        // création d'un tableau id_project => [id_tags, id_tags, id_tags, ...] a partir de la table possede de la bdd

        // Connexion a la db
        $connexionBaseDeDonnee = new SQLite3(BDD::$cheminDeLaBDD);
            
        $requete = "select * from possede";

        $resultat = $connexionBaseDeDonnee->query($requete);

        $tableau = [];

        while ($row = $resultat->fetchArray(SQLITE3_ASSOC)) {

            $id_projet = $row["id_project"];
            $id_tag = $row["id_tag"];

            if (!isset($tableau[$id_projet])) {
                // le tableau de tags pour id_projet n'existe pas, on le crée
                $tableau[$id_projet] = [];
            }

            // on rajoute le tag au tableau de tag pour id_projet
            $tableau[$id_projet][] = $id_tag;

        }

        // Fermeture de la base de donnée
        $connexionBaseDeDonnee->close();

        return $tableau;
    }
    static public function recupProjectsDansBdd(){
        // Récupération des projets dans la base de donnée
        $gestionnaire = new M\GestionnaireProjects();

        // Récupération des relations projet/tag dans un tableau
        $tableau_relation_projet_tag = BDD::recupRelationsTagsProjectsDansBdd();

        // Récupération de tout les tags crées pour association future
        $gestionnaire_tags_disponibles = BDD::recupTagsDisponiblesDansBdd();
        foreach ($gestionnaire_tags_disponibles->donnerTags() as $tag_disponible) {
            $gestionnaire->ajouterTagsDisponibles($tag_disponible);
        }

        // Connexion a la db
        $connexionBaseDeDonnee = new SQLite3(BDD::$cheminDeLaBDD);
            
        $requete = "select * from project";

        $resultat = $connexionBaseDeDonnee->query($requete);


        while ($row = $resultat->fetchArray(SQLITE3_ASSOC)) {

            $unProject = new M\Project($row['id_project'], $row['titre'], $row['description'], $row["lien"], $row["chemin_image"]);

            if (isset($tableau_relation_projet_tag[$unProject->id])) {

                // On a une relation entre le projet et une liste d'id de tag que le projet possede, alors on rajoute
                foreach ($tableau_relation_projet_tag[$unProject->id] as $id_tag_du_projet) {
                    $unProject->ajouterTags($id_tag_du_projet);
                }

            }

            $gestionnaire->ajouterProjects($unProject);

        }

        // Fermeture de la base de donnée
        $connexionBaseDeDonnee->close();

        return $gestionnaire;
    }

    static public function ajoutProjectDansBdd($chemin_image_temp, $nom_image_temp, $titre, $description, $lien, $tableau_id_tag_du_projet){
        // chemin d'image obtenu en général avec $_FILES['image']['tmp_name'] et ca $_FILES['image']['name']
        // Ajout d'un projet dans la base de donnée
            
        $message_erreur = "";

        // transformation du chemin temporaire du logo en chemin final
        $tableauResultatAjoutImage = InteractionFichier::transformationImageTempEnFinal($chemin_image_temp, $nom_image_temp);
        $cheminFinalNouvelleImage = $tableauResultatAjoutImage["nouveauChemin"];
        $message_erreur .= $tableauResultatAjoutImage["messageErreur"];

        if ($message_erreur != "") {
            return $message_erreur;
        }

        // Connexion a la db
        $connexionBaseDeDonnee = new SQLite3(BDD::$cheminDeLaBDD);

        // ETAPE 1 : AJOUT DU PROJET A LA TABLE PROJET
        // Préparation du requetage pour la table projet
        $requetage = $connexionBaseDeDonnee->prepare("insert into project (titre, description, chemin_image, lien) values (?, ?, ?, ?)");
            
        // Liage des variables a la requete
        $requetage->bindValue(1, $titre, SQLITE3_TEXT);
        $requetage->bindValue(2, $description, SQLITE3_TEXT);
        $requetage->bindValue(3, $cheminFinalNouvelleImage, SQLITE3_TEXT);
        $requetage->bindValue(4, $lien, SQLITE3_TEXT);
            
        // Execution de la requete
        if ($requetage->execute() == false) {
                
            // Message en cas d'échec de l'édition
            $message_erreur .= "ERREUR : Lors de l'Ajout du Projet";
                
        }

        if ($message_erreur != "") {
            $connexionBaseDeDonnee->close();
            return $message_erreur;
        }

        // ETAPE 2 : RECUPERATION DE L'ID DU PROJET RAJOUTE
        $id_du_projet = $connexionBaseDeDonnee->lastInsertRowID();

        // ETAPE 3 : AJOUT DANS LA TABLE POSSEDE DES ID DE TAG AINSI QUE LEUR RELATION A L'ID DU PROJET
        // Commencer une transaction (pour le faire en une fois et pas overload la bdd)
        $connexionBaseDeDonnee->exec("BEGIN TRANSACTION");

        // Préparer une seule requete
        $requetage = $connexionBaseDeDonnee->prepare(
            "INSERT INTO possede (id_project, id_tag) VALUES (?, ?)"
        );

        foreach ($tableau_id_tag_du_projet as $id_tag_associe_au_projet) {
            // Reset du statement pour réutiliser la même requête
            $requetage->reset();

            // Liage des variables
            $requetage->bindValue(1, $id_du_projet, SQLITE3_INTEGER);
            $requetage->bindValue(2, $id_tag_associe_au_projet, SQLITE3_INTEGER);

            // Execution
            if ($requetage->execute() === false) {
                $message_erreur .= "ERREUR : Ajout du tag $id_tag_associe_au_projet au projet $id_du_projet a échoué.<br>";
            }
        }

        // Commit la transaction (execute toute les requetes)
        $connexionBaseDeDonnee->exec("COMMIT");
            
        // Fermeture de la base de donnée
        $connexionBaseDeDonnee->close();

        return $message_erreur;
    }

    static public function suppressionProjectDansBdd($unId){
        // Suppression d'un projet dans la base de donnée
            
        // Connexion a la db
        $connexionBaseDeDonnee = new SQLite3(BDD::$cheminDeLaBDD);

        // Suppression de l'image 
        $message_erreur = BDD::deleteImageFromImageId($unId, "id_project", "chemin_image", "project");
        
        // ETAPE 1 : SUPRESSION DES RELATION PROJET/TAGS DANS LA TABLE POSSEDE
        // Préparation du requetage
        $requetage = $connexionBaseDeDonnee->prepare("delete from possede where id_project = ?");
            
        // Liage des variables a la requete
        $requetage->bindValue(1, $unId, SQLITE3_INTEGER);
            
        // Execution de la requete
        if ($requetage->execute() == false) {
                
            // Message en cas d'échec de l'édition
            $message_erreur .= "ERREUR : Lors de la supression des relations Tag/Projet";
                
        }

        if ($message_erreur != "") {
            $connexionBaseDeDonnee->close();
            return $message_erreur;
        }

        // ETAPE 2 : SUPRESSION DU PROJET DANS LA TABLE PROJET
        // Préparation du requetage
        $requetage = $connexionBaseDeDonnee->prepare("delete from project where id_project = ?");
            
        // Liage des variables a la requete
        $requetage->bindValue(1, $unId, SQLITE3_INTEGER);
            
        // Execution de la requete
        if ($requetage->execute() == false) {
                
            // Message en cas d'échec de l'édition
            $message_erreur .= "ERREUR : Lors de la supression du Projet";
                
        }

        // Fermeture de la base de donnée
        $connexionBaseDeDonnee->close();

        return $message_erreur;
    }
    
}
?>