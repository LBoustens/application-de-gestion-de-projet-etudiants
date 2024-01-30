<?php
include "Modules/tags.php";
include "Models/tagsManager.php";

// Définition d'une classe permettant de controller les tags en relation avec la base de données
class TagsController
{
    private $tagsManager; // instance du manager
    private $twig;

    /**
     * Constructeur = initialisation de la connexion vers le SGBD
     */
    public function __construct($db, $twig)
    {
        $this->tagsManager = new TagsManager($db);
        $this->twig = $twig;
    }
}

?>





















