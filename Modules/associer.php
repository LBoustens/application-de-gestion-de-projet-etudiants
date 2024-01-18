<?php
/**
 * définition de la classe Appartient
 */
class Associer
{
    private int $_idprojet;
    private int $_idtags;

    // contructeur
    public function __construct(array $donnees)
    {
        // initialisation d'un produit à partir d'un tableau de données
        if (isset($donnees['idprojet'])) {
            $this->_idprojet = $donnees['idprojet'];
        }
        if (isset($donnees['idtags'])) {
            $this->_idtags = $donnees['idtags'];
        }

    }
    // GETTERS //
    public function idProjet()
    {
        return $this->_idprojet;
    }
    public function idTags()
    {
        return $this->_idtags;
    }

    // SETTERS //
    public function setIdProjet(int $idprojet)
    {
        $this->_idprojet = $idprojet;
    }
    public function setIdTags(int $idtags)
    {
        $this->_idtags = $idtags;
    }


}