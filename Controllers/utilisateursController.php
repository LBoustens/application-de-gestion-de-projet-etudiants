<?php
include "Modules/utilisateurs.php";
include "Models/utilisateursManager.php";
/**
 * Définition d'une classe permettant de gérer les utilisateurs 
 *   en relation avec la base de données	
 */
class UtilisateurController
{
	private $utilisateurManager; // instance du manager
	private $twig;

	/**
	 * Constructeur = initialisation de la connexion vers le SGBD
	 */
	public function __construct($db, $twig)
	{
		$this->utilisateurManager = new UtilisateurManager($db);
		$this->twig = $twig;
	}

	/**
	 * connexion
	 * @param aucun
	 * @return rien
	 */
	function utilisateurConnexion($data)
	{
		// verif du login et mot de passe
		// if ($_POST['login']=="user" && $_POST['passwd']=="pass")
		$utilisateur = $this->utilisateurManager->verif_identification($_POST['login'], $_POST['passwd']);
		if ($utilisateur != false) { // acces autorisé : variable de session acces = oui
			$_SESSION['acces'] = "oui";
			$_SESSION['idutilisateur'] = $utilisateur->idUtilisateur();
			$message = "Bonjour " . $utilisateur->prenom() . " " . $utilisateur->nom() . "!";
			echo $this->twig->render('accueil.html.twig', array('acces' => $_SESSION['acces'], 'message' => $message));
		} else { // acces non autorisé : variable de session acces = non
			$message = "identification incorrecte";
			$_SESSION['acces'] = "non";
			echo $this->twig->render('utilisateur_connexion.html.twig', array('acces' => $_SESSION['acces'], 'message' => $message));
		}
	}

	/**
	 * deconnexion
	 * @param aucun
	 * @return rien
	 */
	function utilisateurDeconnexion()
	{
		$_SESSION['acces'] = "non"; // acces non autorisé
		$message = "vous êtes déconnecté";
		echo $this->twig->render('accueil.html.twig', array('acces' => $_SESSION['acces'], 'message' => $message));

	}

	/**
	 * formulaire de connexion
	 * @param aucun
	 * @return rien
	 */
	function utilisateurFormulaire()
	{
		echo $this->twig->render('utilisateur_connexion.html.twig', array('acces' => $_SESSION['acces']));
	}

/**
	* ajout d'un Projet dans la BD
	* @param Utilisateur à ajouter
	* @return int true si l'ajout a bien eu lieu, false sinon
	*/
	public function ajoutUtilisateur() {
		$utilisateur= new Utilisateur($_POST);
		$ok = $this->utilisateurManager->add($utilisateur);
		$message = $ok ? "Votre compte à été crée" : "probleme lors de l'ajout";
		echo $this->twig->render('utilisateur_connexion.html.twig',array('message'=>$message,'acces'=> $_SESSION['acces'])); 

	}



	public function profil()
	{
		echo $this->twig->render('profil.html.twig', array('acces' => $_SESSION['acces']));
	}

	public function utiadmin()
	{
		echo $this->twig->render('utiadmin.html.twig', array('acces' => $_SESSION['acces']));
	}
	public function cateadmin()
	{
		echo $this->twig->render('cateadmin.html.twig', array('acces' => $_SESSION['acces']));
	}
	public function verifprojet()
	{
		echo $this->twig->render('verifprojet.html.twig', array('acces' => $_SESSION['acces']));
	}

}