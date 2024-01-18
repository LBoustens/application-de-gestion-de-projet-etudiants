<?php
/**
* définition de la classe Tags
*/
class Tags {
	private int $_idtags;   
	private string $_nomtag;
		
	// contructeur
	public function __construct(array $donnees) {
	// initialisation d'un produit à partir d'un tableau de données
    if (isset($donnees['idtags']))  { $this->_idtags =  $donnees['idtags']; }
		if (isset($donnees['nomtag']))    { $this->_nomtag =    $donnees['nomtag']; }	
	}           
	// GETTERS //
	public function idTags()   { return $this->_idtags;}
	public function nomTag()    { return $this->_nomtag;}

		
	// SETTERS //
    public function setIdTags(int $idtags)   { $this->_idtags= $idtags; }
	public function setNomTag(string $nomtag)  { $this->_nomtag = $nomtag; }

}

