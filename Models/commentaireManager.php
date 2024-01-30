<?php

/**
 * Définition d'une classe permettant de gérer les commentaires
 *   en relation avec la base de données
 */
class CommentaireManager
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
     * ajout d'un commentaire dans la BD
     * @param Commentaire $comment
     * @return mixed
     */
    public function ajouterCommentaire(Commentaire $comment)
    {
        // calcul d'un nouveau code du Projet non déja utilisé = Maximum + 1
        $stmt = $this->_db->prepare("SELECT max(idcommentaire) AS maximum FROM commentaire");
        $stmt->execute();
        $comment->setIdCommentaire($stmt->fetchColumn() + 1);

        // requete d'ajout dans la BD
        $req = "INSERT INTO commentaire (idcommentaire, messcom, datepublimess, idprojet, idutilisateur) VALUES (?,?,NOW(),?,?)";
        $stmt = $this->_db->prepare($req);
        $res = $stmt->execute(array($comment->idCommentaire(), $comment->messcom(), $comment->idProjet(), $comment->idUtilisateur()));
        // pour debuguer les requêtes SQL
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        return $res;
    }

    /**
     * retourne l'ensemble des commentaires liés aux utilisateurs en fonction du projet présents dans la BD
     * @param $idprojet
     * @return array
     */
    public function getListComment($idprojet)
    {
        $comments = array();
        $req = "SELECT idcommentaire, messcom, datepublimess, idutilisateur, photodeprofil, nom, prenom FROM commentaire NATURAL JOIN utilisateur WHERE idprojet= ?";
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
            $comment = new Commentaire($donnees); // Création de l'objet Commentaire
            $comment->setUtilisateur($user); // Ajout de l'utilisateur au commentaire
            $comments[] = $comment;
        }
        return $comments;
    }

    /**
     * suppression d'un commentaire dans la base de données
     * @param Commentaire $comment
     * @return boolean true si suppression, false sinon
     */
    public function deleteComment(Commentaire $comment): bool
    {
        $req = "DELETE FROM commentaire WHERE idprojet = ?";
        $stmt = $this->_db->prepare($req);
        return $stmt->execute(array($comment->idProjet()));
    }

}

?>