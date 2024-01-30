<?php

/**
 * Définition d'une classe permettant de gérer la liaison entre tag et proj
 */
class AssocierManager
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
     * ajout du tags lié au projet dans la BD
     * @param Tags $tags
     * @param $proj
     * @return mixed
     */
    public function addLiaisonTags(Tags $tags, $proj)
    {
        $req = "INSERT INTO associer (idprojet, idtags) VALUES (?,?)";
        $stmt = $this->_db->prepare($req);
        $res = $stmt->execute(array($proj->idProjet(), $tags->idTags()));
        // pour debuguer les requêtes SQL
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        return $res;
    }

    /**
     * suppression d'associer en fontion de l'id projet dans la base de données
     * @param Associer $assostags
     * @return boolean true si suppression, false sinon
     */
    public function deleteLiaisonTags(Associer $assostags): bool
    {
        $req = "DELETE FROM associer WHERE idprojet = ?";
        $stmt = $this->_db->prepare($req);
        return $stmt->execute(array($assostags->idProjet()));
    }
}

