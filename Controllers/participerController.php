<?php
include "Modules/participer.php";
include "Models/participerManager.php";
/**
 * Définition d'une classe permettant de gérer les projets
 *   en relation avec la base de données
 */
class ParticiperController
{

    private $partiManager; // instance du manager
    private $twig;

    /**
     * Constructeur = initialisation de la connexion vers le SGBD
     */
    public function __construct($db, $twig)
    {
        $this->partiManager = new ParticiperManager($db);
        $this->twig = $twig;
    }


}