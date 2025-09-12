<?php
/*****************************************************************************
 *
 *
 * C L A S S E . I N T E R A C T I O N . F I C H I E R
 *
 */

// La classe fait partie de l'espace de nommage Model du paradigme MVC
namespace Model;

// Nous utilisons l'espace de nommage SQLite3 pour accéder à la base.
use SQLite3;


/**
 * Cette classe sert d'interface d'encapsulation pour toute les interactions avec les fichiers du site
 */
class InteractionFichier
{

    /**
     * Cette méthode supprime un fichier image a partir de son chemin et retourne un message d'erreur
     *
     * @param string $unCheminFichier
     * @return string 
     */
    static public function supprimeImageDansFichier($unCheminFichier)
    {

        $messageErreur = "";
        // Vérification de l'existence du fichier
        if (file_exists($unCheminFichier)) {

            // Suppression du fichier
            if (unlink($unCheminFichier)) {
                $messageErreur = "";
            } else {
                $messageErreur = "Une erreur s'est produite lors de la suppression du fichier.";
            }
        } else {
            $messageErreur = "Le fichier ne peut pas être supprimé car il n'existe pas.";
        }

        return $messageErreur;
    }

    /**
     * Cette méthode prend une image téléversée dans un répertoire temporaire,
     * Effectue des vérifications,
     * Genere un nom de fichier unique,
     * Déplace l'image vers un répertoire final avec son nom unique,
     * et retourne un array associatif contenant:
     *  - "messageErreur" : "message d'erreur sous format string"
     *  - "nouveauChemin" : "le chemin de l'image telle qu'elle dans le répertoire final"
     *
     * @param string $unCheminFichierTemporaire
     * @param string $unNomDeFichierAvecExtention
     * @return array 
     */
    static public function transformationImageTempEnFinal($unCheminFichierTemporaire, $unNomDeFichierAvecExtention)
    {

        // VERIFICATIONS  (Existance, extention, format mime, taille)

        $messageErreur = "";
        $chemin_dest = "";
        // Vérifier si le fichier existe
        if (!file_exists($unCheminFichierTemporaire)) {
            $messageErreur .= "Le fichier ne peut pas etre transformé car il n'existe pas.\n";
        }
        // Vérifier l'extension du fichier
        $extensionsAutorisees = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($unNomDeFichierAvecExtention, PATHINFO_EXTENSION));
        if (!in_array($ext, $extensionsAutorisees)) {
            $messageErreur .= "Extension de fichier non autorisée. Extensions permises : " . implode(', ', $extensionsAutorisees) . ".\n";
        }
        // Vérifier le type MIME pour plus de sécurité
        $typesMimeAutorises = ['image/jpeg', 'image/png'];
        $info = new  \finfo(FILEINFO_MIME_TYPE);
        $typeMime = $info->file($unCheminFichierTemporaire);
        if (!in_array($typeMime, $typesMimeAutorises)) {
            $messageErreur .= "Type MIME du fichier non autorisé.\n";
        }
        // Vérifier la taille du fichier (exemple max 5MB)
        $tailleMax = 10 * 1024 * 1024; // 10 Mo en octets
        if (filesize($unCheminFichierTemporaire) > $tailleMax) {
            $messageErreur .= "Le fichier est trop volumineux. Taille maximale autorisée: 10 Mo.\n";
        }

        // Si pas d'erreurs
        if ($messageErreur == "") {

            // Définir le répertoire de destination
            $chemin_dest = "./uploads/";

            // Créer un nom de fichier unique pour éviter les conflits, utilisant un
            // nom original nettoyé pour éviter les caractères dangereux
            $nom_fichier_origine = basename($unCheminFichierTemporaire) . $unNomDeFichierAvecExtention;
            $nom_fichier_propre = preg_replace("/[^A-Za-z0-9_\-\.]/", '_', $nom_fichier_origine);
            $nom_fichier_unique = uniqid() . "_" . $nom_fichier_propre;
            $chemin_dest .= $nom_fichier_unique;

            // Déplacement de l'image jusqu'à son répertoire final
            if (!move_uploaded_file($unCheminFichierTemporaire, $chemin_dest)) {
                // Échec du déplacement
                $messageErreur = "Échec du déplacement";
                $chemin_dest = "";
            }
        }

        // Construction de l'array de retour
        $arrayDeRetour["messageErreur"] = $messageErreur;
        $arrayDeRetour["nouveauChemin"] = $chemin_dest;

        return $arrayDeRetour;

    }

}