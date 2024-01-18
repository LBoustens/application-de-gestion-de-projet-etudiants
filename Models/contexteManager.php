<?php
/**
 * définition de la classe itineraire
 */
class ContexteManager
{

    private $_db; // Instance de PDO - objet de connexion au SGBD

    /**
     * Constructeur = initialisation de la connexion vers le SGBD
     */
    public function __construct($db)
    {
        $this->_db = $db;
    }

    public function getContexte()
    {
        $cont = array();
        $req = "SELECT idcontexte,identifiant,semestre,intitule FROM contexte";
        $stmt = $this->_db->prepare($req);
        $stmt->execute();
        // pour debuguer les requêtes SQL
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        // récup des données
        while ($donnees = $stmt->fetch()) {
            $cont[] = new Contexte($donnees);
        }
        return $cont;
    }

    public function getDetailsContexte($idprojet)
	{
		$contexte = array();
		$req = "SELECT idcontexte,identifiant,semestre,intitule FROM contexte NATURAL JOIN projet WHERE idprojet= ?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idprojet));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// récup des données
		while ($donnees = $stmt->fetch()) {
			$contexte[] = new Contexte($donnees);
		}
		return $contexte;
	}
}

