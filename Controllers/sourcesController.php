<?php
include "Modules/sources.php";
include "Models/sourcesManager.php";

class SourcesController
{
    private $sourcesManager; // instance du manager
    private $twig;

    /**
     * Constructeur = initialisation de la connexion vers le SGBD
     */
    public function __construct($db, $twig)
    {
        $this->sourcesManager = new SourcesManager($db);
        $this->twig = $twig;
    }
}

?>