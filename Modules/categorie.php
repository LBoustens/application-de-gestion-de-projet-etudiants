<?php
/**
* définition de la classe Categorie
*/
class Categorie {
    private int $_idcategorie;  
	private string $_nomcate;  
		
	// contructeur
	public function __construct(array $donnees) {
	// initialisation d'un produit à partir d'un tableau de données
    if (isset($donnees['idcategorie']))     { $this->_idcategorie =  $donnees['idcategorie']; }
    if (isset($donnees['nomcate']))  { $this->_nomcate =  $donnees['nomcate']; }

	}           
	// GETTERS //
    public function idCategorie()   { return $this->_idcategorie;}
	public function nomCate()   { return $this->_nomcate;}
		
	// SETTERS //
    public function setIdCategorie(int $idcategorie)    { $this->_idcategorie = $idcategorie; }
    public function setNomCate(string $nomcate)   { $this->_nomcate= $nomcate; }

}
