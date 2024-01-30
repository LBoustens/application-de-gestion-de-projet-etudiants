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

    /**
     * suppression d'une catégorie dans la base de données
     * @param Categorie
     * @return boolean true si suppression, false sinon
     */
    public function deleteCategorie(Categorie $cates): bool
    {

        $req = "DELETE FROM categorie  WHERE idcategorie = ?";
        $stmt = $this->_db->prepare($req);
        return $stmt->execute(array($cates->idCategorie()));
    }

    public function addCategorie(Categorie $cates)
    {
        // calcul d'un nouveau code du Projet non déja utilisé = Maximum + 1
        $stmt = $this->_db->prepare("SELECT max(idcategorie) AS maximum FROM categorie");
        $stmt->execute();
        $cates->setIdCategorie($stmt->fetchColumn() + 1);

        $req = "INSERT INTO categorie (idcategorie, nomcate)  VALUES (?,?)";
        $stmt = $this->_db->prepare($req);
        $res = $stmt->execute(array($cates->idCategorie(), $cates->nomCate()));
        // pour debuguer les requêtes SQL
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        return $res;
    }

    public function getCategorieAdmin()
    {
        $cates = array();
        $req = "SELECT idcategorie,nomcate FROM categorie WHERE idcategorie NOT IN (SELECT idcategorie FROM appartient)";
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
}

