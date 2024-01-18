<?php
/**
* définition de la classe Sources
*/
class Sources {
    private int $_idprojet;  
	private int $_idsources;   
	private string $_url;
		
	// contructeur
	public function __construct(array $donnees) {
        if (isset($donnees['idprojet']))     { $this->_idprojet =  $donnees['idprojet']; }
	// initialisation d'un produit à partir d'un tableau de données
    if (isset($donnees['idsources']))  { $this->_idsources =  $donnees['idsources']; }
		if (isset($donnees['url']))    { $this->_url =    $donnees['url']; }	
	}           
	// GETTERS //
    public function idProjet()   { return $this->_idprojet;}
	public function idSources()   { return $this->_idsources;}
	public function url()    { return $this->_url;}

		
	// SETTERS //
    public function setIdProjet(int $idprojet)    { $this->_idprojet = $idprojet; }
    public function setIdSources(int $idsources)   { $this->_idsources= $idsources; }
	public function setUrl(string $url)  { $this->_url = $url; }

}
