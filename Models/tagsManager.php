<?php

/**
 * Définition d'une classe permettant de gérer les tags
 *   en relation avec la base de données
 */
class TagsManager
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
     * ajout d'un tag dans la BD
     * @param Tags $tags
     * @return mixed
     */
    public function addTags(Tags $tags)
    {
        // calcul d'un nouveau code du Projet non déja utilisé = Maximum + 1
        $stmt = $this->_db->prepare("SELECT max(idtags) AS maximum FROM tags");
        $stmt->execute();
        $tags->setIdTags($stmt->fetchColumn() + 1);

        $req = "INSERT INTO tags (idtags, nomtag)  VALUES (?,?)";
        $stmt = $this->_db->prepare($req);
        $res = $stmt->execute(array($tags->idTags(), $tags->nomtag()));
        // pour debuguer les requêtes SQL
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        return $res;
    }

    /**
     * retourne le tag à partir de l'id projet lié à associer
     * @return Tags
     */
    public function getDetailsTag($idprojet)
    {
        $req = "SELECT idtags,nomtag FROM tags NATURAL JOIN associer WHERE idprojet= ?";
        $stmt = $this->_db->prepare($req);
        $stmt->execute(array($idprojet));
        // pour debuguer les requêtes SQL
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        // récup des données
        $tag = new Tags($stmt->fetch());
        return $tag;
    }

    /**
     * suppression du tag quand il n'est pas sélectioner dans associer dans la base de données
     */
    public function deleteTags()
    {
        $req = "DELETE FROM tags WHERE idtags NOT IN (SELECT idtags FROM associer)";
        $stmt = $this->_db->prepare($req);
        return $stmt->execute();
    }

    /**
     * modification d'un tag dans la BD
     * @param Tags $tag
     * @return boolean
     */
    public function updateTag(Tags $tag): bool
    {
        $req = "UPDATE tags SET nomtag = :nomtag "
            . " WHERE idtags= :idtags";

        $stmt = $this->_db->prepare($req);
        $stmt->execute(
            array(
                ":nomtag" => $tag->nomTag(),
                ":idtags" => $tag->idTags()
            )
        );
        // Modifie la ligne suivante pour renvoyer true si au moins une ligne est mise à jour
        return $stmt->rowCount() > 0;
    }
}

