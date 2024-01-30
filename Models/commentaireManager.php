<?php

class CommentaireManager {

    private $_db; // Instance de PDO - objet de connexion au SGBD
    /**
     * Constructeur = initialisation de la connexion vers le SGBD
     */
    public function __construct($db)
    {
        $this->_db = $db;
    }

public function ajouterCommentaire(Commentaire $comment)
{
    // calcul d'un nouveau code du Projet non déja utilisé = Maximum + 1
    $stmt = $this->_db->prepare("SELECT max(idcommentaire) AS maximum FROM commentaire");
    $stmt->execute();
    $comment->setIdCommentaire($stmt->fetchColumn() + 1);

    // requete d'ajout dans la BD
    $req = "INSERT INTO commentaire (idcommentaire, messcom, datepublimess, idprojet, idutilisateur) VALUES (?,?,NOW(),?,?)";
    $stmt = $this->_db->prepare($req);
    $res = $stmt->execute(array($comment->idCommentaire(), $comment->messcom(), $comment->idProjet(),$comment->idUtilisateur()));
    // pour debuguer les requêtes SQL
    $errorInfo = $stmt->errorInfo();
    if ($errorInfo[0] != 0) {
        print_r($errorInfo);
    }
    return $res;
    /**$idcontexte = $bd->lastInsertId();	 */
}

    /**
     * retourne l'ensemble des commentaires présents dans la BD
     * @return Projet[]
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
     * suppression d'une Participation dans la base de données
     * @param Commentaire
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