<?php
/**
 * Définition d'une classe permettant de gérer les Projet
 *   en relation avec la base de données
 */
class ProjetManager
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
	 * ajout d'un Projet dans la BD
	 * @param Projet à ajouter
	 * @return int true si l'ajout a bien eu lieu, false sinon
	 */
	public function add(Projet $proj)
	{
		// calcul d'un nouveau code du Projet non déja utilisé = Maximum + 1
		$stmt = $this->_db->prepare("SELECT max(idprojet) AS maximum FROM projet");
		$stmt->execute();
		$proj->setIdProjet($stmt->fetchColumn() + 1);

		// requete d'ajout dans la BD
		$req = "INSERT INTO projet (idprojet,titre,descproj,image,liendemo,idcontexte,anneecrea) VALUES (?,?,?,?,?,?,?)";
		$stmt = $this->_db->prepare($req);
		$res = $stmt->execute(array($proj->idProjet(), $proj->titre(), $proj->descProj(), $proj->image(), $proj->lienDemo(), $proj->idContexte(), $proj->anneeCrea()));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;
		/**$idcontexte = $bd->lastInsertId();	 */
	}

	/**
	 * nombre de Projets dans la base de données
	 * @return int le nb de Projet
	 */
	public function count(): int
	{
		$stmt = $this->_db->prepare('SELECT COUNT(*) FROM projet');
		$stmt->execute();
		return $stmt->fetchColumn();
	}

	/**
	 * suppression d'un Projet dans la base de données
	 * @param Projet
	 * @return boolean true si suppression, false sinon
	 */
	public function delete(Projet $proj): bool
	{
		$req = "DELETE FROM projet WHERE idprojet = ?";
		$stmt = $this->_db->prepare($req);
		return $stmt->execute(array($proj->idProjet()));
	}

	/**
	 * recherche dans la BD d'un Projet à partir de son id
	 * @param int $iditi
	 * @return Projet
	 */
	public function get(int $idprojet): Projet
	{
		$req = 'SELECT idprojet,titre,descproj,image,liendemo,idcontexte,anneecrea FROM projet WHERE idprojet=?';
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idprojet));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		$projs = new Projet($stmt->fetch());
		return $projs;
	}

	/**
	 * retourne l'ensemble des Projets présents dans la BD
	 * @return Projet[]
	 */
	public function getList()
	{
		$projets = array();
		$req = "SELECT idprojet,titre,descproj,image,liendemo,idcontexte,anneecrea FROM projet";
		$stmt = $this->_db->prepare($req);
		$stmt->execute();
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// récup des données
		while ($donnees = $stmt->fetch()) {
			$projets[] = new Projet($donnees);
		}
		return $projets;
	}

	/**
	 * retourne l'ensemble des Projets présents dans la BD pour un utilisateur
	 * @param int idutilisateur
	 * @return Projet[]
	 */
	public function getListUtilisateur(int $idutilisateur)
	{
		$projets = array();
		$req = "SELECT idprojet,idutilisateur,titre,descproj,image,liendemo,idcontexte,anneecrea FROM projet NATURAL JOIN participer WHERE idutilisateur=?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idutilisateur));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// recup des données
		while ($donnees = $stmt->fetch()) {
			$projets[] = new Projet($donnees);
		}
		return $projets;
	}

    public function getDetailsProj($idprojet)
    {
        $projs = array();
        $req = 'SELECT idprojet,titre,descproj,image,liendemo,idcontexte,anneecrea FROM projet WHERE idprojet=?';
        $stmt = $this->_db->prepare($req);
        $stmt->execute(array($idprojet));
        // pour debuguer les requêtes SQL
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        while ($donnees = $stmt->fetch()) {
            $projs = new Projet($donnees);
        }
        return $projs;
    }
	/**
	 * méthode de recherche d'un Projet dans la BD à partir des critères passés en paramètre
	 * @param string $titre
	 * @param string $descproj
	 * @return Projet[]
	 */
    public function searchProj(string $titre, string $descproj) {
        $req = "SELECT idprojet,titre,descproj,image,liendemo,idcontexte,anneecrea FROM projet";
        $cond = '';

        if ($titre<>"")
        { 	$cond = $cond . " titre like '%". $titre ."%'";
        }
        if ($descproj<>"")
        { 	if ($cond<>"") $cond .= " AND ";
            $cond = $cond . " descproj like '%" . $descproj ."%'";
        }
        if ($cond <>"")
        { 	$req .= " WHERE " . $cond;
        }
        // execution de la requete
        $stmt = $this->_db->prepare($req);
        $stmt->execute();
        // pour debuguer les requêtes SQL
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        $projets = array();
        while ($donnees = $stmt->fetch())
        {
            $projets[] = new Projet($donnees);
        }
        return $projets;
    }
	/**
	 * modification d'un Projet dans la BD
	 * @param Projet
	 * @return boolean
	 */
	public function update(Projet $proj): bool
	{
		$req = "UPDATE projet SET titre = :titre, "
			. "descproj = :descproj, "
			. "image = :image, "
			. "liendemo  = :liendemo, "
            . "idcontexte  = :idcontexte, "
            . "anneecrea  = :anneecrea "
			. " WHERE idprojet= :idprojet";

		$stmt = $this->_db->prepare($req);
		$stmt->execute(
			array(
				":titre" => $proj->titre(),
				":descproj" => $proj->descProj(),
				":image" => $proj->image(),
				":liendemo" => $proj->lienDemo(),
                ":idcontexte" => $proj->idContexte(),
                ":anneecrea" => $proj->anneeCrea(),
				":idprojet" => $proj->idProjet()
			)
		);
        // Modifie la ligne suivante pour renvoyer true si au moins une ligne est mise à jour
        return $stmt->rowCount() > 0;

	}
}

?>