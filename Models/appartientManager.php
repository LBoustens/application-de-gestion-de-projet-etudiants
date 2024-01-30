<?php
/**
 * définition de la classe itineraire
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
     * retourne l'ensemble des Projets présents dans la BD
     * @return Projet[]
     */
    public function liaisonCate(Appartient $liaisoncate, $proj){

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
     * @param Appartient
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
     * @param Participer
     * @return boolean true si suppression, false sinon
     */
    public function deleteAppartient(Appartient $liaisoncate): bool
    {
        $req = "DELETE FROM appartient WHERE idprojet = ?";
        $stmt = $this->_db->prepare($req);
        return $stmt->execute(array($liaisoncate->idProjet()));
    }

    public function deleteAppartientAdmin(Appartient $liaisoncate): bool
    {
        $req = "DELETE FROM appartient WHERE idcategorie = ?";
        $stmt = $this->_db->prepare($req);
        return $stmt->execute(array($liaisoncate->idCategorie()));
    }
}