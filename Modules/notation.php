<?php

/**
 * Définition de la classe notation
 */
class Notation {
    private int $_idnote;
    private string $_note;
    private string $_datepublinote;
    private int $_idprojet;
    private int $_idutilisateur;

    // Constructeur
    public function __construct(array $donnees) {
        // Initialisation d'un commentaire à partir d'un tableau de données
        if (isset($donnees['idnote'])) { $this->_idnote = $donnees['idnote']; }
        if (isset($donnees['note']))       { $this->_note = $donnees['note']; }
        if (isset($donnees['datepublinote'])) { $this->_datepublinote = $donnees['datepublinote']; }
        if (isset($donnees['idprojet']))      { $this->_idprojet = $donnees['idprojet']; }
        if (isset($donnees['idutilisateur'])) { $this->_idutilisateur = $donnees['idutilisateur']; }
    }

    // Getters
    public function idNote()       { return $this->_idnote; }
    public function note()             { return $this->_note; }
    public function datePubliNote()       { return $this->_datepublinote; }
    public function idProjet()            { return $this->_idprojet; }
    public function idUtilisateur()       { return $this->_idutilisateur; }

    // Setters
    public function setIdNote(int $idnote)       { $this->_idnote = $idnote; }
    public function setNote(string $note)                { $this->_note = $note; }
    public function setDatePubliNote(string $datepublinote)    { $this->_datepublinote= $datepublinote; }
    public function setIdProjet(int $idprojet)                 { $this->_idprojet = $idprojet; }
    public function setIdUtilisateur(int $idutilisateur)       { $this->_idutilisateur = $idutilisateur; }
    public function setUtilisateur(Utilisateur $utilisateur)
    {
        $this->utilisateur = $utilisateur;
    }
}
