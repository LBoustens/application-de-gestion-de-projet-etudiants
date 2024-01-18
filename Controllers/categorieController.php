<?php
include "Modules/categorie.php";
include "Models/categorieManager.php";
/**
 * Définition d'une classe permettant de gérer les projets
 *   en relation avec la base de données	
 */
class CategorieController
{

	private $categorieManager; // instance du manager
	private $twig;

	/**
	 * Constructeur = initialisation de la connexion vers le SGBD
	 */
	public function __construct($db, $twig)
	{
		$this->categorieManager = new CategorieManager($db);
		$this->twig = $twig;
	}


}
