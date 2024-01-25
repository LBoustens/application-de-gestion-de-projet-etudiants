<?php

/**
 * Définition de la classe Commentaire
 */
class Commentaire {
    private int $_idcommentaire;
    private string $_messcom;
    private string $_datepublimess;
    private int $_idprojet;
    private int $_idutilisateur;

    // Constructeur
    public function __construct(array $donnees) {
        // Initialisation d'un commentaire à partir d'un tableau de données
        if (isset($donnees['idcommentaire'])) { $this->_idcommentaire = $donnees['idcommentaire']; }
        if (isset($donnees['messcom']))       { $this->_messcom = $donnees['messcom']; }
        if (isset($donnees['datepublimess'])) { $this->_datepublimess = $donnees['datepublimess']; }
        if (isset($donnees['idprojet']))      { $this->_idprojet = $donnees['idprojet']; }
        if (isset($donnees['idutilisateur'])) { $this->_idutilisateur = $donnees['idutilisateur']; }
    }

    // Getters
    public function idCommentaire()       { return $this->_idcommentaire; }
    public function messcom()             { return $this->_messcom; }
    public function datePubliMess()       { return $this->_datepublimess; }
    public function idProjet()            { return $this->_idprojet; }
    public function idUtilisateur()       { return $this->_idutilisateur; }

    // Setters
    public function setIdCommentaire(int $idcommentaire)       { $this->_idcommentaire = $idcommentaire; }
    public function setMesscom(string $messcom)                { $this->_messcom = $messcom; }
    public function setDatePubliMess(string $datepublimess)    { $this->_datepublimess = $datepublimess; }
    public function setIdProjet(int $idprojet)                 { $this->_idprojet = $idprojet; }
    public function setIdUtilisateur(int $idutilisateur)       { $this->_idutilisateur = $idutilisateur; }
    public function setUtilisateur(Utilisateur $utilisateur)
    {
        $this->utilisateur = $utilisateur;
    }
}
