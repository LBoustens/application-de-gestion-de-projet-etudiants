<?php
/**
 * définition de la classe itineraire
 */
class SourcesManager
{

	private $_db; // Instance de PDO - objet de connexion au SGBD

	/**
	 * Constructeur = initialisation de la connexion vers le SGBD
	 */
	public function __construct($db)
	{
		$this->_db = $db;
	}

	public function addSources(Sources $sources, $proj)
	{
		// calcul d'un nouveau code du Projet non déja utilisé = Maximum + 1
		$stmt = $this->_db->prepare("SELECT max(idsources) AS maximum FROM sources");
		$stmt->execute();
		$sources->setIdSources($stmt->fetchColumn() + 1);

		$req = "INSERT INTO sources (idprojet, idsources, url)  VALUES (?,?,?)";
		$stmt = $this->_db->prepare($req);
		$res = $stmt->execute(array($proj->idProjet(), $sources->idSources(), $sources->url()));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;
	}

	public function getDetailsSource($idprojet)
	{
		$sources = array();
		$req = "SELECT idsources, url FROM sources WHERE idprojet= ?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idprojet));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// récup des données
		while ($donnees = $stmt->fetch()) {
			$sources[] = new Sources($donnees);
		}
		return $sources;
	}

    /**
     * suppression d'une Sources dans la base de données
     * @param Sources
     * @return boolean true si suppression, false sinon
     */
    public function deleteSources(Sources $sources): bool
    {
        $req = "DELETE FROM sources WHERE idprojet = ?";
        $stmt = $this->_db->prepare($req);
        return $stmt->execute(array($sources->idProjet()));
    }
}

