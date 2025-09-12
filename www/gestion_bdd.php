<?php

namespace Model;
require "gestionnaires.php";
require "../model/InteractionFichier.php";
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
            $messageErreur .= "Pas d'images associées a cet id.\n";
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

        $gestionnaire = new GestionnaireContacts();

        while ($row = $resultat->fetchArray(SQLITE3_ASSOC)) {

            $unContact = new LienContact($row['id_contact'], $row['chemin_logo'], $row['lien']);

            $gestionnaire->ajouterContact($unContact);

        }

        // Fermeture de la base de donnée
        $connexionBaseDeDonnee->close();

        return $gestionnaire;
    }

    static public function ajoutContactDansBdd($chemin_logo_temp, $nom_logo_temp, $description){
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
        $requetage = $connexionBaseDeDonnee->prepare("insert into contact_link (chemin_logo, lien) values (?, ?)");
            
        // Liage des variables a la requete
        $requetage->bindValue(1, $cheminFinalNouvelleImage, SQLITE3_TEXT);
        $requetage->bindValue(2, $description, SQLITE3_TEXT);
            
        // Execution de la requete
        if ($requetage->execute()) {
                
            // Message en cas de succès de l'édition
            $message_erreur .= "<h1>Contact Ajouté Avec Succès</h1>";
                
        } else {
                
            // Message en cas d'échec de l'édition
            $message_erreur .= "<h1>Erreur lors de l'Ajout du Contact</h1>";
                
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
            $message_erreur .= "<h1>Contact Supprimé Avec Succès</h1>";
                
        } else {
                
            // Message en cas d'échec de l'édition
            $message_erreur .= "<h1>Erreur lors de la Suppression du Contact</h1>";
                
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

        $gestionnaire = new GestionnaireSkills();

        while ($row = $resultat->fetchArray(SQLITE3_ASSOC)) {

            $unSkill = new Skill($row['id_skill'], $row['chemin_logo'], $row['titre'], $row['extra'], $row['description']);

            $gestionnaire->ajouterSkill($unSkill);

        }

        // Fermeture de la base de donnée
        $connexionBaseDeDonnee->close();

        return $gestionnaire;
    }

    static public function ajoutSkillDansBdd($chemin_logo_temp, $nom_logo_temp, $titre, $extra, $description){
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
        $requetage = $connexionBaseDeDonnee->prepare("insert into skill (chemin_logo, titre, extra, description) values (?, ?, ?, ?)");
            
        // Liage des variables a la requete
        $requetage->bindValue(1, $cheminFinalNouvelleImage, SQLITE3_TEXT);
        $requetage->bindValue(2, $titre, SQLITE3_TEXT);
        $requetage->bindValue(3, $extra, SQLITE3_TEXT);
        $requetage->bindValue(4, $description, SQLITE3_TEXT);
            
        // Execution de la requete
        if ($requetage->execute()) {
                
            // Message en cas de succès de l'édition
            $message_erreur .= "<h1>Skill Ajouté Avec Succès</h1>";
                
        } else {
                
            // Message en cas d'échec de l'édition
            $message_erreur .= "<h1>Erreur lors de l'Ajout du Skill</h1>";
                
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
            $message_erreur .= "<h1>Skill Supprimé Avec Succès</h1>";
                
        } else {
                
            // Message en cas d'échec de l'édition
            $message_erreur .= "<h1>Erreur lors de la Suppression du Skill</h1>";
                
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

        $gestionnaire = new GestionnaireHobbies();

        while ($row = $resultat->fetchArray(SQLITE3_ASSOC)) {

            $unHobby = new Hobby($row['id_hobby'], $row['titre'], $row['description'], $row['chemin_image']);

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
            $message_erreur .= "<h1>Hobby Ajouté Avec Succès</h1>";
                
        } else {
                
            // Message en cas d'échec de l'édition
            $message_erreur .= "<h1>Erreur lors de l'Ajout du Hobby</h1>";
                
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
            $message_erreur .= "<h1>Hobby Supprimé Avec Succès</h1>";
                
        } else {
                
            // Message en cas d'échec de l'édition
            $message_erreur .= "<h1>Erreur lors de la Suppression du Hobby</h1>";
                
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

        $gestionnaire = new GestionnaireTimelines();

        while ($row = $resultat->fetchArray(SQLITE3_ASSOC)) {

            $uneTimeline = new Timeline($row['id_timeline'], $row['date'], $row['description']);

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
            $message_erreur .= "<h1>Timeline Ajouté Avec Succès</h1>";
                
        } else {
                
            // Message en cas d'échec de l'édition
            $message_erreur .= "<h1>Erreur lors de l'Ajout de la Timeline</h1>";
                
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
            $message_erreur .= "<h1>Timeline Supprimée Avec Succès</h1>";
                
        } else {
                
            // Message en cas d'échec de l'édition
            $message_erreur .= "<h1>Erreur lors de la Suppression de la Timeline</h1>";
                
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

        $gestionnaire = new GestionnaireTags();

        while ($row = $resultat->fetchArray(SQLITE3_ASSOC)) {

            $unTagDisponible = new Tag($row['id_tag'], $row['titre'], $row['description']);

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
            $message_erreur .= "<h1>Tag Ajouté Avec Succès</h1>";
                
        } else {
                
            // Message en cas d'échec de l'édition
            $message_erreur .= "<h1>Erreur lors de l'Ajout du Tag</h1>";
                
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
            $message_erreur .= "<h1>Tag Supprimé Avec Succès</h1>";
                
        } else {
                
            // Message en cas d'échec de l'édition
            $message_erreur .= "<h1>Erreur lors de la Suppression du Tag</h1>";
                
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
        $gestionnaire = new GestionnaireProjects();

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

            $unProject = new Project($row['id_project'], $row['titre'], $row['description'], $row["lien"], $row["chemin_image"]);

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
            $message_erreur .= "<h1>Erreur lors de l'Ajout du Projet</h1>";
                
        }

        if ($message_erreur != "") {
            $connexionBaseDeDonnee->close();
            return $message_erreur;
        }

        // ETAPE 2 : RECUPERATION DE L'ID DU PROJET RAJOUTE
        $id_du_projet = $connexionBaseDeDonnee->lastInsertRowID();

        // ETAPE 3 : AJOUT DANS LA TABLE POSSEDE DES ID DE TAG AINSI QUE LEUR RELATION A L'ID DU PROJET
        foreach ($tableau_id_tag_du_projet as $id_tag_associe_au_projet) {
            // Préparation du requetage pour la table projet
            $requetage = $connexionBaseDeDonnee->prepare("insert into possede (id_project, id_tag) values (?, ?)");
                
            // Liage des variables a la requete
            $requetage->bindValue(1, $id_du_projet, SQLITE3_INTEGER);
            $requetage->bindValue(2, $id_tag_associe_au_projet, SQLITE3_INTEGER);
                
            // Execution de la requete
            if ($requetage->execute() == false) {
                    
                // Message en cas d'échec de l'édition
                $message_erreur .= "<h1>Erreur lors de l'Ajout du tag d'id " . $id_tag_associe_au_projet . " au projet</h1>";
                    
            }
        }
        
            
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
            $message_erreur .= "<h1>Erreur lors de la supression des relations Tag/Projet</h1>";
                
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
            $message_erreur .= "<h1>Erreur lors de la supression du Projet</h1>";
                
        }

        // Fermeture de la base de donnée
        $connexionBaseDeDonnee->close();

        return $message_erreur;
    }
    
}
?>