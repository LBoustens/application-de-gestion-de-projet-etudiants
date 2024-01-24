<?php

/**
 * Définition d'une classe permettant de gérer les Utilisateurs
 * en relation avec la base de données
 *
 */

class UtilisateurManager
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
	 * verification de l'identité d'un Utilisateur (Login/password)
	 * @param string $login
	 * @param string $password
	 * @return Utilisateur si authentification ok, false sinon
	 */
	public function verif_identification($login, $password)
	{
		//echo $login." : ".$password;
		$req = "SELECT idutilisateur, nom, prenom FROM utilisateur WHERE email=:login and mdp=:password ";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array(":login" => $login, ":password" => $password));
		if ($data = $stmt->fetch()) {
			$utilisateur = new Utilisateur($data);
			return $utilisateur;
		} else
			return false;
	}

	/**
	 * retourne l'ensemble des Utilisateurs présents dans la BD 
	 * @return Utilisateur[]
	 */
	public function getDetailsUtilisateur($idprojet)
	{
        $utis = array();
		$req = "SELECT idutilisateur, nom, prenom, identifiantiut, email, photodeprofil  FROM utilisateur NATURAL JOIN participer WHERE idprojet = ?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idprojet));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// récup des données
        while ($donnees = $stmt->fetch()) {
            $utis[] = new Utilisateur($donnees);
        }
        return $utis;
	}

	public function add(Utilisateur $utilisateur)
	{
		// calcul d'un nouveau code d'utilisateur non déja utilisé = Maximum + 1
		$stmt = $this->_db->prepare("SELECT max(idutilisateur) AS maximum FROM utilisateur");
		$stmt->execute();
		$utilisateur->setIdUtilisateur($stmt->fetchColumn() + 1);

		// requete d'ajout dans la BD
		$req = "INSERT INTO utilisateur (idutilisateur,nom,prenom,identifiantiut,email,mdp,statut) VALUES (?,?,?,?,?,?,1)";
		$stmt = $this->_db->prepare($req);
		$res = $stmt->execute(array($utilisateur->idUtilisateur(), $utilisateur->nom(), $utilisateur->prenom(), $utilisateur->identifiantIut(), $utilisateur->email(), $utilisateur->mdp()));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;
	}

    public function getUtiConnecte(int $idutilisateur)
    {
        $utis = array();
        $req = "SELECT idutilisateur,nom,prenom,identifiantiut,email,mdp,photodeprofil FROM utilisateur WHERE idutilisateur=?";
        $stmt = $this->_db->prepare($req);
        $stmt->execute(array($idutilisateur));
        // pour debuguer les requêtes SQL
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        // recup des données
        while ($donnees = $stmt->fetch()) {
            $utis[] = new Utilisateur($donnees);
        }
        return $utis;
    }














}
?>