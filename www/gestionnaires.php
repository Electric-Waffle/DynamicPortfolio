<?php

namespace Model;

// Classe LienContact
class LienContact
{
    public $id;
    public $chemin_image;
    public $description;
    public function __construct($id, $chemin_image, $description)
    {
        $this->id = $id;
        $this->chemin_image = $chemin_image;
        $this->description = $description;
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
            echo "Description : " . $contact->description . "<br>";
            echo "<hr>";
        }
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
    public function __construct($id, $chemin_logo, $titre, $extra, $description)
    {
        $this->id = $id;
        $this->chemin_logo = $chemin_logo;
        $this->titre = $titre;
        $this->extra = $extra;
        $this->description = $description;
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
            echo "<hr>";
        }
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
            echo "Id : " . $timeline->id . "<br>";
            echo "Date : " . $timeline->date . "<br>";
            echo "Description : " . $timeline->description . "<br>";
            echo "<hr>";
        }
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

}

?>