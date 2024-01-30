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
        $req = "SELECT idutilisateur, nom, prenom, photodeprofil, mdp, statut FROM utilisateur WHERE email=:login";
        $stmt = $this->_db->prepare($req);
        $stmt->execute(array(":login" => $login));
        if ($data = $stmt->fetch()) {
            // Utilisez password_verify pour comparer le mot de passe saisi avec le hachage stocké
            if (password_verify($password, $data['mdp'])) {
                unset($data['mdp']); // Ne renvoyez pas le mot de passe haché
                $utilisateur = new Utilisateur($data);
                return $utilisateur;
            }
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
		$req = "INSERT INTO utilisateur (idutilisateur,nom,prenom,identifiantiut,email,mdp,photodeprofil,statut) VALUES (?,?,?,?,?,?,?,1)";
		$stmt = $this->_db->prepare($req);
		$res = $stmt->execute(array($utilisateur->idUtilisateur(), $utilisateur->nom(), $utilisateur->prenom(), $utilisateur->identifiantIut(), $utilisateur->email(), $utilisateur->mdp(), $utilisateur->photoDeProfil()));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;
	}

    public function getUtiConnecte(int $idutilisateur)
    {
        $req = "SELECT idutilisateur,nom,prenom,identifiantiut,email,mdp,photodeprofil FROM utilisateur WHERE idutilisateur=?";
        $stmt = $this->_db->prepare($req);
        $stmt->execute(array($idutilisateur));
        // pour debuguer les requêtes SQL
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        // recup des données
        $utis = new Utilisateur($stmt->fetch());
        return $utis;
    }


    public function getListUtiAdmin()
    {
        $utis = array();
        $req = "SELECT idutilisateur,nom,prenom,identifiantiut,email FROM utilisateur WHERE idutilisateur NOT IN (SELECT idutilisateur FROM participer)";
        $stmt = $this->_db->prepare($req);
        $stmt->execute();
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

    public function deleteUtilisateurAdmin(Utilisateur $uti): bool
    {
        $req = "DELETE FROM utilisateur WHERE idutilisateur = ?";
        $stmt = $this->_db->prepare($req);
        return $stmt->execute(array($uti->idUtilisateur()));
    }

    /**
     * modification d'un utilisateur dans la BD
     * @param Utilisateur
     * @return boolean
     */
    public function updateUtilisateur(Utilisateur $uti): bool
    {
        $req = "UPDATE utilisateur SET nom = :nom, "
            . "prenom = :prenom, "
            . "identifiantiut = :identifiantiut, "
            . "email  = :email, "
            . "photodeprofil  = :photodeprofil "
            . " WHERE idutilisateur= :idutilisateur";

        $stmt = $this->_db->prepare($req);
        $stmt->execute(
            array(
                ":nom" => $uti->nom(),
                ":prenom" => $uti->prenom(),
                ":identifiantiut" => $uti->identifiantIut(),
                ":email" => $uti->email(),
                ":photodeprofil" => $uti->photoDeProfil(),
                ":idutilisateur" => $uti->idUtilisateur()
            )
        );
        // Modifie la ligne suivante pour renvoyer true si au moins une ligne est mise à jour
        return $stmt->rowCount() > 0;

    }










}
?>