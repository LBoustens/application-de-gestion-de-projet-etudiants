<?php
/** 
 * définition de la classe itineraire
 */
class Utilisateur
{
	private int $_idutilisateur;
	private string $_nom;
	private string $_prenom;
	private string $_identifiantiut;
	private string $_email;
	private string $_password;
	private int $_photoprofil;
	private int $_statut;

	// contructeur
	public function __construct(array $donnees)
	{
		// initialisation d'un produit à partir d'un tableau de données
		if (isset($donnees['idutilisateur'])) {
			$this->_idutilisateur = $donnees['idutilisateur'];
		}
		if (isset($donnees['nom'])) {
			$this->_nom = $donnees['nom'];
		}
		if (isset($donnees['prenom'])) {
			$this->_prenom = $donnees['prenom'];
		}
		if (isset($donnees['identifiantiut'])) {
			$this->_identifiantiut = $donnees['identifiantiut'];
		}
		if (isset($donnees['email'])) {
			$this->_email = $donnees['email'];
		}
		if (isset($donnees['mdp'])) {
			$this->_password = $donnees['mdp'];
		}
		if (isset($donnees['photoprofil'])) {
			$this->_photoprofil = $donnees['photoprofil'];
		}
		if (isset($donnees['statut'])) {
			$this->_statut = $donnees['statut'];
		}
	}
	// GETTERS //
	public function idUtilisateur()
	{
		return $this->_idutilisateur;
	}
	public function nom()
	{
		return $this->_nom;
	}
	public function prenom()
	{
		return $this->_prenom;
	}
	public function identifiantIut()
	{
		return $this->_identifiantiut;
	}
	public function email()
	{
		return $this->_email;
	}
	public function mdp()
	{
		return $this->_password;
	}
	public function photoProfil()
	{
		return $this->_photoprofil;
	}
	public function statutAdmin()
	{
		return $this->_statut;
	}


	// SETTERS //
	public function setIdUtilisateur(int $idutilisateur)
	{
		$this->_idutilisateur = $idutilisateur;
	}
	public function setNom(string $nom)
	{
		$this->_nom = $nom;
	}
	public function setPrenom(string $prenom)
	{
		$this->_prenom = $prenom;
	}
	public function setIdentifiantIut(string $identifiantiut)
	{
		$this->_identifiantiut = $identifiantiut;
	}
	public function setEmail(string $email)
	{
		$this->_email = $email;
	}
	public function setPassword(string $password)
	{
		$this->_password = $password;
	}
	public function setPhotoProfil(string $photoprofil)
	{
		$this->_photoprofil = $photoprofil;
	}
	public function setStatutAdmin(int $statut)
	{
		$this->_statut = $statut;
	}

}

?>