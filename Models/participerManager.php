<?php
/**
 * Définition d'une classe permettant de gérer la liaison entre uti et proj
 */
class ParticiperManager
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
     * ajout de participer dans la BD
     * @param Participer $parti
     * @param $proj
     * @return mixed
     */
    public function addLiaisonUtilisateur(Participer $parti, $proj)
    {
        $req = "INSERT INTO participer (idprojet, idutilisateur) VALUES (?,?)";
        $stmt = $this->_db->prepare($req);
        $res = $stmt->execute(array($proj->idProjet(), $parti->idUtilisateur()));
        // pour debuguer les requêtes SQL
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        return $res;
    }

    /**
     * suppression d'une Participation lié au projet dans la base de données
     * @param Participer $parti
     * @return boolean true si suppression, false sinon
     */
    public function deleteParticiper(Participer $parti): bool
    {
        $req = "DELETE FROM participer WHERE idprojet = ?";
        $stmt = $this->_db->prepare($req);
        return $stmt->execute(array($parti->idProjet()));
    }

}
