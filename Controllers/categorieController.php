<?php
include "Modules/categorie.php";
include "Models/categorieManager.php";
/**
 * Définition d'une classe permettant de gérer les projets
 *   en relation avec la base de données	
 */
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

    public function cateadmin()
    {
        $cates = $this->categorieManager->getCategorieAdmin();
        echo $this->twig->render('cateadmin.html.twig', array('cates'=>$cates, 'admin' => $_SESSION['admin'], 'photodeprofil'=>$_SESSION['photodeprofil'], 'acces' => $_SESSION['acces']));
    }

    public function addCateAdmin()
    {

        $categorie = new Categorie($_POST);
        $okCates = $this->categorieManager->addCategorie($categorie);
        $cates = $this->categorieManager->getCategorieAdmin();

        $message = "Categorie ajouté avec succès";

        if (!$okCates) {
            $message .= "Problème lors de l'ajout de la categorie";
        }

        echo $this->twig->render('cateadmin.html.twig', array('cates'=>$cates, 'message' => $message, 'admin' => $_SESSION['admin'], 'photodeprofil'=>$_SESSION['photodeprofil'], 'acces' => $_SESSION['acces']));
    }
    public function suppCateAdmin()
    {

        $liaisoncate = new Appartient($_POST);
        $categorie = new Categorie($_POST);
        $okAppartient = $this->appartientManager->deleteAppartientAdmin($liaisoncate);
        $okCates = $this->categorieManager->deleteCategorie($categorie);
        $cates = $this->categorieManager->getCategorieAdmin();

        $message = "Categorie supprimé avec succès";

        if (!$okAppartient) {
            $message .= "Problème lors de la suppression de la liaison entre appartient et categorie";
        }
        if (!$okCates) {
            $message .= "Problème lors de la suppression de la categorie";
        }

        echo $this->twig->render('cateadmin.html.twig', array('cates'=>$cates, 'message' => $message, 'admin' => $_SESSION['admin'], 'photodeprofil'=>$_SESSION['photodeprofil'], 'acces' => $_SESSION['acces']));
    }

}
