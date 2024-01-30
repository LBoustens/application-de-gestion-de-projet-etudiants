<?php
include "Modules/appartient.php";
include "Models/appartientManager.php";

// Définition d'une classe permettant de controller les liaison entre categorie et proj en relation avec la base de données
class AppartientController
{
    private $appartientManager; // instance du manager
    private $twig;

    /**
     * Constructeur = initialisation de la connexion vers le SGBD
     */
    public function __construct($db, $twig)
    {
        $this->appartientManager = new AppartientManager($db);
        $this->twig = $twig;
    }

}

?>