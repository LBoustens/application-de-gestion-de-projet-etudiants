<?php
include "Modules/tags.php";
include "Models/tagsManager.php";

class TagsController {

    private $tagsManager; // instance du manager
    private $twig;

    /**
     * Constructeur = initialisation de la connexion vers le SGBD
     */
    public function __construct($db, $twig) {
        $this->tagsManager = new TagsManager($db);
        $this->twig = $twig;
    }


}






















