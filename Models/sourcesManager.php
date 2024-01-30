<?php

/**
 * Définition d'une classe permettant de gérer les sources
 *   en relation avec la base de données
 */
class SourcesManager
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
     * ajout d'une source dans la BD
     * @param Sources $sources
     * @param $proj
     * @return rien
     */
    public function addSources(Sources $sources, $proj)
    {
        // calcul d'un nouveau code du Projet non déja utilisé = Maximum + 1
        $stmt = $this->_db->prepare("SELECT max(idsources) AS maximum FROM sources");
        $stmt->execute();
        $sources->setIdSources($stmt->fetchColumn() + 1);

        $req = "INSERT INTO sources (idprojet, idsources, url)  VALUES (?,?,?)";
        $stmt = $this->_db->prepare($req);
        $res = $stmt->execute(array($proj->idProjet(), $sources->idSources(), $sources->url()));
        // pour debuguer les requêtes SQL
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        return $res;
    }

    /**
     * retourne la tag à partir de l'id projet
     * @param $idprojet
     * @return Sources
     */
    public function getDetailsSource($idprojet)
    {
        $req = "SELECT idsources, url FROM sources WHERE idprojet= ?";
        $stmt = $this->_db->prepare($req);
        $stmt->execute(array($idprojet));
        // pour debuguer les requêtes SQL
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        // récup des données
        $sources = new Sources($stmt->fetch());
        return $sources;
    }


    /**
     * modification d'une source dans la BD
     * @param Sources $sources
     * @return boolean
     */
    public function updateSource(Sources $sources): bool
    {
        $req = "UPDATE sources SET url = :url "
            . " WHERE idprojet= :idprojet";

        $stmt = $this->_db->prepare($req);
        $stmt->execute(
            array(
                ":url" => $sources->url(),
                ":idprojet" => $sources->idProjet()
            )
        );
        // Modifie la ligne suivante pour renvoyer true si au moins une ligne est mise à jour
        return $stmt->rowCount() > 0;

    }

    /**
     * suppression d'une source dans la base de données
     * @param Sources $sources
     * @return boolean true si suppression, false sinon
     */
    public function deleteSources(Sources $sources): bool
    {
        $req = "DELETE FROM sources WHERE idprojet = ?";
        $stmt = $this->_db->prepare($req);
        return $stmt->execute(array($sources->idProjet()));
    }
}

