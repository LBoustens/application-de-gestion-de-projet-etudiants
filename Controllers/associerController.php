<?php
include "Modules/associer.php";
include "Models/associerManager.php";

// Définition d'une classe permettant de controller les les liaison entre tag et proj en relation avec la base de données
class AssocierController
{
    private $assosManager; // instance du manager
    private $twig;

    /**
     * Constructeur = initialisation de la connexion vers le SGBD
     */
    public function __construct($db, $twig)
    {
        $this->assosManager = new AssocierManager($db);
        $this->twig = $twig;
    }

}

?>