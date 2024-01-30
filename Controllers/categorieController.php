<?php
include "Modules/categorie.php";
include "Models/categorieManager.php";

// Définition d'une classe permettant de controller les categories en relation avec la base de données
class CategorieController
{
    private $categorieManager;
    private $appartientManager;// instance du manager
    private $twig;

    /**
     * Constructeur = initialisation de la connexion vers le SGBD
     */
    public function __construct($db, $twig)
    {
        $this->categorieManager = new CategorieManager($db);
        $this->appartientManager = new AppartientManager($db);
        $this->twig = $twig;
    }

    /**  page gestion categorie
     * @return void
     */
    public function cateadmin()
    {
        $cates = $this->categorieManager->getCategorieAdmin();
        echo $this->twig->render('cateadmin.html.twig', array('cates' => $cates, 'admin' => $_SESSION['admin'], 'photodeprofil' => $_SESSION['photodeprofil'], 'acces' => $_SESSION['acces']));
    }

    /**
     * Ajout dans la BD d'une categorie par un admin
     * @return void
     */
    public function addCateAdmin()
    {
        $categorie = new Categorie($_POST);
        $okCates = $this->categorieManager->addCategorie($categorie);
        $cates = $this->categorieManager->getCategorieAdmin();

        $message = "Categorie ajouté avec succès";

        if (!$okCates) {
            $message .= "Problème lors de l'ajout de la categorie";
        }

        echo $this->twig->render('cateadmin.html.twig', array('cates' => $cates, 'message' => $message, 'admin' => $_SESSION['admin'], 'photodeprofil' => $_SESSION['photodeprofil'], 'acces' => $_SESSION['acces']));
    }

    /**
     * Suppression dans la BD d'une categorie par un admin
     * @return void
     */
    public function suppCateAdmin()
    {
        $categorie = new Categorie($_POST);
        $okCates = $this->categorieManager->deleteCategorie($categorie);
        $cates = $this->categorieManager->getCategorieAdmin();

        $message = "Categorie supprimé avec succès";

        if (!$okCates) {
            $message .= "Problème lors de la suppression de la categorie";
        }

        echo $this->twig->render('cateadmin.html.twig', array('cates' => $cates, 'message' => $message, 'admin' => $_SESSION['admin'], 'photodeprofil' => $_SESSION['photodeprofil'], 'acces' => $_SESSION['acces']));
    }

}

?>