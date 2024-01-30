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

    /**
     * suppression d'un contexte dans la base de données
     * @param Categorie
     * @return boolean true si suppression, false sinon
     */
    public function deleteContexte(Contexte $contexte): bool
    {
        $req = "DELETE FROM contexte  WHERE idcontexte = ?";
        $stmt = $this->_db->prepare($req);
        return $stmt->execute(array($contexte->idContexte()));
    }

    public function addContexte(Contexte $contexte)
    {
        // calcul d'un nouveau code du Projet non déja utilisé = Maximum + 1
        $stmt = $this->_db->prepare("SELECT max(idcontexte) AS maximum FROM contexte");
        $stmt->execute();
        $contexte->setIdContexte($stmt->fetchColumn() + 1);

        $req = "INSERT INTO contexte (idcontexte, identifiant, semestre, intitule)  VALUES (?,?,?,?)";
        $stmt = $this->_db->prepare($req);
        $res = $stmt->execute(array($contexte->idContexte(), $contexte->identifiant(),$contexte->semestre(),$contexte->intitule()));
        // pour debuguer les requêtes SQL
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        return $res;
    }

    public function getContexteAdmin()
    {
        $cont = array();
        $req = "SELECT idcontexte,identifiant,semestre,intitule FROM contexte WHERE idcontexte NOT IN (SELECT idcontexte FROM projet)";
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
}



