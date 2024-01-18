<?php
/**
* définition de la classe itineraire
*/
class Projet {
	private int $_idprojet;   
	private int $_idcontexte;
	private string $_titre;
	private string $_descproj;
	private $_image;
	private string $_liendemo;
    private int $_anneecrea;



    // contructeur
	public function __construct(array $donnees) {
	// initialisation d'un produit à partir d'un tableau de données
		if (isset($donnees['idprojet']))     { $this->_idprojet =  $donnees['idprojet']; }
		if (isset($donnees['idcontexte']))  { $this->_idcontexte =  $donnees['idcontexte']; }
		if (isset($donnees['titre'])) { $this->_titre = $donnees['titre']; }
		if (isset($donnees['descproj'])) { $this->_descproj = $donnees['descproj']; }
		if (isset($donnees['image']))  { $this->_image =  $donnees ['image'];}		
		if (isset($donnees['liendemo']))       { $this->_liendemo =   $donnees['liendemo']; }
        if (isset($donnees['anneecrea']))  { $this->_anneecrea =  $donnees['anneecrea'];}
	}

    // GETTERS //
	public function idProjet()   { return $this->_idprojet;}
	public function idContexte()  { return $this->_idcontexte;}
	public function titre() { return $this->_titre;}
	public function descProj() { return $this->_descproj;}
	public function image() { return $this->_image;}
	public function lienDemo() { return $this->_liendemo;}
    public function anneeCrea()  { return $this->_anneecrea;}

	// SETTERS //
	public function setIdProjet(int $idprojet)    { $this->_idprojet = $idprojet; }
	public function setIdContexte(int $idcontexte)   { $this->_idcontexte= $idcontexte; }
	public function setTitre(string $titre) { $this->_titre = $titre; }
	public function setDescProj(string $descproj) { $this->_descproj = $descproj; }
	public function setImage($image) { $this->_image = $image; }
	public function setLienDemo (string $liendemo)   { $this->_liendemo = $liendemo ; }
    public function setAnneeCrea (int $anneecrea)   { $this->_anneecrea = $anneecrea ; }

}

