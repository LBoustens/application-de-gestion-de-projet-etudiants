<?php
/**
 * définition de la classe itineraire
 */
class CategorieManager
{

	private $_db; // Instance de PDO - objet de connexion au SGBD

	/**
	 * Constructeur = initialisation de la connexion vers le SGBD
	 */
	public function __construct($db)
	{
		$this->_db = $db;
	}

	/**
	 * retourne l'ensemble des Projets présents dans la BD
	 * @return Projet[]
	 */
	public function getCategorie()
	{
		$cates = array();
		$req = "SELECT idcategorie,nomcate FROM categorie";
		$stmt = $this->_db->prepare($req);
		$stmt->execute();
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// récup des données
		while ($donnees = $stmt->fetch()) {
			$cates[] = new Categorie($donnees);
		}
		return $cates;
	}

	public function getdetailsCate($idprojet)
	{
		$categorie = array();
		$req = "SELECT idcategorie, nomcate FROM categorie NATURAL JOIN appartient WHERE idprojet= ?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idprojet));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// récup des données
		while ($donnees = $stmt->fetch()) {
			$categorie[] = new Categorie($donnees);
		}
		return $categorie;
	}
}

