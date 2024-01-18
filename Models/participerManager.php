<?php
/**
 * définition de la classe itineraire
 */
class ParticiperManager
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
	public function liaisonUtilisateur(Participer $parti, $proj)
	{

		$req = "INSERT INTO participer (idprojet, idutilisateur) VALUES (?,?)";
		$stmt = $this->_db->prepare($req);
		$res = $stmt->execute(array($proj->idProjet(), $parti->idUtilisateur()));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;
	}
}
