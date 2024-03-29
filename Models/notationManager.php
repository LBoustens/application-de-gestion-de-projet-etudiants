<?php

/**
 * Définition d'une classe permettant de gérer les notes
 *   en relation avec la base de données
 */
class NotationManager
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
     * ajout d'une note dans la BD
     * @param Notation $note
     * @return mixed
     */
    public function ajouterNote(Notation $note)
    {
        // calcul d'un nouveau code du Projet non déja utilisé = Maximum + 1
        $stmt = $this->_db->prepare("SELECT max(idnote) AS maximum FROM notation");
        $stmt->execute();
        $note->setIdNote($stmt->fetchColumn() + 1);

        // requete d'ajout dans la BD
        $req = "INSERT INTO notation (idnote, note, datepublinote, idprojet, idutilisateur) VALUES (?,?,NOW(),?,?)";
        $stmt = $this->_db->prepare($req);
        $res = $stmt->execute(array($note->idNote(), $note->note(), $note->idProjet(), $note->idUtilisateur()));
        // pour debuguer les requêtes SQL
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        return $res;
    }

    /**
     * retourne l'ensemble des notes lié au projet présents dans la BD
     * @param $idprojet
     * @return array
     */
    public function getListNote($idprojet)
    {
        $notes = array();
        $req = "SELECT idnote, note, datepublinote, idutilisateur, photodeprofil, nom, prenom FROM notation NATURAL JOIN utilisateur WHERE idprojet= ?";
        $stmt = $this->_db->prepare($req);
        $stmt->execute(array($idprojet));
        // pour debuguer les requêtes SQL
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        // récup des données
        while ($donnees = $stmt->fetch()) {
            //fait avec chatgpt
            $user = new Utilisateur($donnees); // Création de l'objet Utilisateur
            $note = new Notation($donnees); // Création de l'objet notation
            $note->setUtilisateur($user); // Ajout de l'utilisateur au commentaire
            $notes[] = $note;
        }
        return $notes;
    }

    /**
     * suppression d'une note dans la base de données
     * @param Notation $note
     * @return boolean true si suppression, false sinon
     */
    public function deleteNote(Notation $note): bool
    {
        $req = "DELETE FROM notation WHERE idprojet = ?";
        $stmt = $this->_db->prepare($req);
        return $stmt->execute(array($note->idProjet()));
    }
}

?>