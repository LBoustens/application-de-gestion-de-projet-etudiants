<?php
/**
 * définition de la classe Appartient
 */
class Appartient
{
    private int $_idprojet;
    private int $_idcategorie;

    // contructeur
    public function __construct(array $donnees)
    {
        // initialisation d'un produit à partir d'un tableau de données
        if (isset($donnees['idprojet'])) {
            $this->_idprojet = $donnees['idprojet'];
        }
        if (isset($donnees['idcategorie'])) {
            $this->_idcategorie = $donnees['idcategorie'];
        }

    }
    // GETTERS //
    public function idProjet()
    {
        return $this->_idprojet;
    }
    public function idCategorie()
    {
        return $this->_idcategorie;
    }

    // SETTERS //
    public function setIdProjet(int $idprojet)
    {
        $this->_idprojet = $idprojet;
    }
    public function setIdCategorie(int $idcategorie)
    {
        $this->_idcategorie = $idcategorie;
    }


}