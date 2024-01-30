<?php

/**
 * Définition d'une classe permettant de gérer les contextes
 *   en relation avec la base de données
 */
class ContexteManager
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
     * retourne l'ensemble des contextes présents dans la BD
     * @return Contexte[]
     */
    public function getContexte()
    {
        $cont = array();
        $req = "SELECT idcontexte,identifiant,semestre,intitule FROM contexte";
        $stmt = $this->_db->prepare($req);
        $stmt->execute();
        // pour debuguer les requêtes SQL
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        // récup des données
        while ($donnees = $stmt->fetch()) {
            $cont[] = new Contexte($donnees);
        }
        return $cont;
    }

    /**
     * retourne le contexte lié au projet par l'id présents dans la BD
     * @param $idprojet
     * @return Contexte
     */
    public function getDetailsContexte($idprojet)
    {
        $req = "SELECT idcontexte,identifiant,semestre,intitule FROM contexte NATURAL JOIN projet WHERE idprojet= ?";
        $stmt = $this->_db->prepare($req);
        $stmt->execute(array($idprojet));
        // pour debuguer les requêtes SQL
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        // récup des données
        $contexte = new Contexte($stmt->fetch());
        return $contexte;
    }

    /**
     * suppression d'un contexte dans la base de données
     * @param Contexte $contexte
     * @return boolean true si suppression, false sinon
     */
    public function deleteContexte(Contexte $contexte): bool
    {
        $req = "DELETE FROM contexte  WHERE idcontexte = ?";
        $stmt = $this->_db->prepare($req);
        return $stmt->execute(array($contexte->idContexte()));
    }

    /**
     * ajout d'un contexte dans la BD
     * @param Contexte $contexte
     * @return mixed
     */
    public function addContexte(Contexte $contexte)
    {
        // calcul d'un nouveau code du Projet non déja utilisé = Maximum + 1
        $stmt = $this->_db->prepare("SELECT max(idcontexte) AS maximum FROM contexte");
        $stmt->execute();
        $contexte->setIdContexte($stmt->fetchColumn() + 1);

        $req = "INSERT INTO contexte (idcontexte, identifiant, semestre, intitule)  VALUES (?,?,?,?)";
        $stmt = $this->_db->prepare($req);
        $res = $stmt->execute(array($contexte->idContexte(), $contexte->identifiant(), $contexte->semestre(), $contexte->intitule()));
        // pour debuguer les requêtes SQL
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        return $res;
    }

    /**
     * retourne les contextes à partir de leur id et qui ne sont pas lié à projet
     * @return Contexte[]
     */
    public function getContexteAdmin()
    {
        $cont = array();
        $req = "SELECT idcontexte,identifiant,semestre,intitule FROM contexte WHERE idcontexte NOT IN (SELECT idcontexte FROM projet)";
        $stmt = $this->_db->prepare($req);
        $stmt->execute();
        // pour debuguer les requêtes SQL
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        // récup des données
        while ($donnees = $stmt->fetch()) {
            $cont[] = new Contexte($donnees);
        }
        return $cont;
    }
}



