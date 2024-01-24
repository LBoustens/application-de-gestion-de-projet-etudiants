<?php
/**
 * définition de la classe itineraire
 */
class AssocierManager
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
    public function liaisonTags(Tags $tags, $proj){

        $req = "INSERT INTO associer (idprojet, idtags) VALUES (?,?)";
        $stmt = $this->_db->prepare($req);
        $res = $stmt->execute(array($proj->idProjet(), $tags->idTags()));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
        return $res;
    }

    /**
     * suppression d'un Tags dans la base de données
     * @param Tags
     * @return boolean true si suppression, false sinon
     */
    public function deleteLiaisonTags(Associer $assostags): bool
    {
        $req = "DELETE FROM associer WHERE idprojet = ?";
        $stmt = $this->_db->prepare($req);
        return $stmt->execute(array($assostags->idProjet()));
    }
}

