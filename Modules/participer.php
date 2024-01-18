<?php
/**
 * définition de la classe Appartient
 */
class Participer
{
    private int $_idprojet;
    private int $_idutilisateur;

    // contructeur
    public function __construct(array $donnees)
    {
        // initialisation d'un produit à partir d'un tableau de données
        if (isset($donnees['idprojet'])) {
            $this->_idprojet = $donnees['idprojet'];
        }
        if (isset($donnees['idutilisateur'])) {
            $this->_idutilisateur = $donnees['idutilisateur'];
        }

    }
    // GETTERS //
    public function idProjet()
    {
        return $this->_idprojet;
    }
    public function idUtilisateur()
    {
        return $this->_idutilisateur;
    }

    // SETTERS //
    public function setIdProjet(int $idprojet)
    {
        $this->_idprojet = $idprojet;
    }
    public function setIdUtilisateur(int $idutilisateur)
    {
        $this->_idutilisateur = $idutilisateur;
    }


}