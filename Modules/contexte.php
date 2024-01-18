<?php
/**
* définition de la classe itineraire
*/
class Contexte {
	private int $_idcontexte;   
	private string $_identifiant;
	private string $_semestre;
	private string $_intitule;

		
	// contructeur
	public function __construct(array $donnees) {
	// initialisation d'un produit à partir d'un tableau de données
    if (isset($donnees['idcontexte']))  { $this->_idcontexte =  $donnees['idcontexte']; }
		if (isset($donnees['identifiant']))    { $this->_identifiant =    $donnees['identifiant']; }
		if (isset($donnees['semestre'])) { $this->_semestre = $donnees['semestre']; }
		if (isset($donnees['intitule'])) { $this->_intitule = $donnees['intitule']; }
	}           
	// GETTERS //
	public function idContexte()   { return $this->_idcontexte;}
	public function identifiant()    { return $this->_identifiant;}
	public function semestre() { return $this->_semestre;}
	public function intitule() { return $this->_intitule;}
		
	// SETTERS //
    public function setIdContexte(int $idcontexte)   { $this->_idcontexte= $idcontexte; }
	public function setIdentifiant(string $identifiant)  { $this->_identifiant = $identifiant; }
	public function setSemestre(string $semestre) { $this->_semestre = $semestre; }
	public function setIntitule(string $intitule) { $this->_intitule = $intitule; }

}

