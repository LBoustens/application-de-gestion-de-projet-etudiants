<?php

/**
 * Définition d'une classe permettant de gérer la liaison entre cate et proj
 */
class AppartientManager
{
    private $_db; // Instance de PDO - objet de connexion au SGBD

    /**
     * Constructeur = initialisation de la connexion vers le SGBD
     */
    public function __construct($db)
    {
        $this->_db = $db;
    }

    /**
     * ajout de la catégorie lié au projet dans la BD
     * @param Appartient $liaisoncate
     * @param $proj
     * @return mixed
     */
    public function addLiaisonCate(Appartient $liaisoncate, $proj)
    {

        $req = "INSERT INTO appartient (idprojet, idcategorie) VALUES (?,?)";
        $stmt = $this->_db->prepare($req);
        $res = $stmt->execute(array($proj->idProjet(), $liaisoncate->idCategorie()));
        // pour debuguer les requêtes SQL
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        return $res;
    }

    /**
     * modification d'une liaison categorie dans la BD
     * @param Appartient $appartient
     * @return boolean
     */
    public function updateAppartient(Appartient $appartient): bool
    {
        $req = "UPDATE appartient SET idcategorie = :idcategorie "
            . " WHERE idprojet= :idprojet";

        $stmt = $this->_db->prepare($req);
        $stmt->execute(
            array(
                ":idcategorie" => $appartient->idCategorie(),
                ":idprojet" => $appartient->idProjet()
            )
        );
        // Modifie la ligne suivante pour renvoyer true si au moins une ligne est mise à jour
        return $stmt->rowCount() > 0;
    }

    /**
     * suppression d'appartient dans la base de données
     * @param Appartient $liaisoncate
     * @return boolean true si suppression, false sinon
     */
    public function deleteAppartient(Appartient $liaisoncate): bool
    {
        $req = "DELETE FROM appartient WHERE idprojet = ?";
        $stmt = $this->_db->prepare($req);
        return $stmt->execute(array($liaisoncate->idProjet()));
    }

}