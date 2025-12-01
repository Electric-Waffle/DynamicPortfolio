<?php

namespace Model;

// Classe LienContact
class LienContact
{
    public $id;
    public $chemin_image;
    public $description;
    public $nom;
    public function __construct($id, $chemin_image, $description, $nom)
    {
        $this->id = $id;
        $this->chemin_image = $chemin_image;
        $this->description = $description;
        $this->nom = $nom;
    }
}

// Classe GestionnaireContacts
class GestionnaireContacts
{
    private $contacts = [];

    public function ajouterContact($contact) {
        $this->contacts[] = $contact;
    }

    public function afficherContacts()
    {
        foreach ($this->contacts as $contact) {
            echo "Id : " . $contact->id . "<br>";
            echo "Chemin Image : " . $contact->chemin_image . "<br>";
            echo "Lien : " . $contact->description . "<br>";
            echo "Nom : " . $contact->nom . "<br>";
            echo "<hr>";
        }
    }

    public function recupererContacts()
    {
        return $this->contacts;
    }

}

// Classe Skill
class Skill
{
    public $id;
    public $chemin_logo;
    public $titre;
    public $extra;
    public $description;
    public $lien;
    public function __construct($id, $chemin_logo, $titre, $extra, $description, $lien)
    {
        $this->id = $id;
        $this->chemin_logo = $chemin_logo;
        $this->titre = $titre;
        $this->extra = $extra;
        $this->description = $description;
        $this->lien = $lien;
    }
}

// Classe GestionnaireSkills
class GestionnaireSkills
{
    private $skills = [];

    public function ajouterSkill($skill) {
        $this->skills[] = $skill;
    }

    public function afficherSkills()
    {
        foreach ($this->skills as $skill) {
            echo "Id : " . $skill->id . "<br>";
            echo "Chemin Image : " . $skill->chemin_logo . "<br>";
            echo "Titre : " . $skill->titre . "<br>";
            echo "Extra : " . $skill->extra . "<br>";
            echo "Description : " . $skill->description . "<br>";
            echo "Lien : " . $skill->lien . "<br>";
            echo "<hr>";
        }
    }

    public function recupererSkills()
    {
        return $this->skills;
    }

}

// Classe Hobby
class Hobby
{
    public $id;
    public $titre;
    public $description;
    public $chemin_image;
    public function __construct($id, $titre, $description, $chemin_image)
    {
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $description;
        $this->chemin_image = $chemin_image;
    }
}

// Classe GestionnaireHobbies
class GestionnaireHobbies
{
    private $hobbies = [];

    public function ajouterHobby($hobby) {
        $this->hobbies[] = $hobby;
    }

    public function afficherHobbies()
    {
        foreach ($this->hobbies as $hobby) {
            echo "Id : " . $hobby->id . "<br>";
            echo "Titre : " . $hobby->titre . "<br>";
            echo "Description : " . $hobby->description . "<br>";
            echo "Chemin Image : " . $hobby->chemin_image . "<br>";
            echo "<hr>";
        }
    }

    public function recupererHobbies()
    {
        return $this->hobbies;
    }

}

// Classe Timeline
class Timeline
{
    public $id;
    public $date;
    public $description;
    public function __construct($id, $date, $description,)
    {
        $this->id = $id;
        $this->date = $date;
        $this->description = $description;
    }
}

// Classe GestionnaireTimelines
class GestionnaireTimelines
{
    private $timelines = [];

    public function ajouterTimeline($timeline) {
        $this->timelines[] = $timeline;
    }

    public function afficherTimelines()
    {
        foreach ($this->timelines as $timeline) {
            echo $timeline->date . " - " . $timeline->description ;
            echo "<hr>";
        }
    }

    public function recupererTimelines()
    {
        return $this->timelines;
    }

}

// Classe Tag
class Tag
{
    public $id;
    public $titre;
    public $description;
    public function __construct($id, $titre, $description,)
    {
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $description;
    }
}

// Classe GestionnaireTags
class GestionnaireTags
{
    private $tags = [];

    public function ajouterTag($tag) {
        $this->tags[] = $tag;
    }

    public function donnerTags() {
        return $this->tags;
    }

    public function afficherTags()
    {
        foreach ($this->tags as $tag) {
            echo "Id : " . $tag->id . "<br>";
            echo "Titre : " . $tag->titre . "<br>";
            echo "Description : " . $tag->description . "<br>";
            echo "<hr>";
        }
    }

}

// Classe Project
class Project
{
    public $id;
    public $titre;
    public $description;
    public $lien;
    public $chemin_image;

    // Liste de tout les id des tags possédés par le project
    public $tags;

    public function __construct($id, $titre, $description, $lien, $chemin_image)
    {
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $description;
        $this->lien = $lien;
        $this->chemin_image = $chemin_image;
        $this->tags = [];
    }

    public function ajouterTags($tag) {
        $this->tags[] = $tag;
    }

    public function recupererTags() {
        return $this->tags;
    }
}

// Classe GestionnaireProjects
class GestionnaireProjects
{
    // Liste de tout les projets
    private $projects = [];

    // Liste de tout les tags qui existent, avec leurs informations
    private $tags_disponibles = [];

    public function ajouterProjects($project) {
        $this->projects[] = $project;
    }

    public function ajouterTagsDisponibles($tag_disponible) {
        $this->tags_disponibles[] = $tag_disponible;
    }

    public function afficherProjects()
    {
        foreach ($this->projects as $project) {
            echo "Id : " . $project->id . "<br>";
            echo "Titre : " . $project->titre . "<br>";
            echo "Description : " . $project->description . "<br>";
            echo "Lien : " . $project->lien . "<br>";
            echo "Chemin Image : " . $project->chemin_image . "<br>";
            echo "<hr>";
        }
    }

    public function recupererProjects()
    {
        return $this->projects;
    }

    public function recupererTableauTagsDisponible()
    {
        return $this->tags_disponibles;
    }

    public function recupereTagDisponibleGraceAId($unId){

        foreach ($this->tags_disponibles as $tag) {

            if ($tag->id == $unId) {
                return $tag;
            }

        }
        
        return false;
    }
}

    // Classe Timeline
class Article
{
    public $id;
    public $title;
    public $content;
    public $link;
    public $createdAt;
    public function __construct($id, $title, $content, $link, $createdAt)
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->link = $link;
        $this->createdAt = $createdAt;
    }
}

// Classe GestionnaireTimelines
class GestionnaireArticles
{
    private $articles = [];

    public function ajouterArticle($article) {
        $this->articles[] = $article;
    }

    public function afficherArticles()
    {
        foreach ($this->articles as $article) {
            echo "Id : " . $article->id . "<br>";
            echo "Titre : " . $article->title . "<br>";
            echo "Contenu : " . $article->content . "<br>";
            echo "Lien : " . $article->link . "<br>";
            echo "Date de Création : " . $article->createdAt . "<br>";
            echo "<hr>";
        }
    }

    public function recupererArticles()
    {
        return $this->articles;
    }

}


?>