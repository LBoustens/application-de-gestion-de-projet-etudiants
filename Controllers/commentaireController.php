<?php
include "Modules/commentaire.php";
include "Models/commentaireManager.php";

/**
 * Définition d'une classe permettant de gérer les projets
 *   en relation avec la base de données
 */
class CommentaireController
{

    private $commentManager;
    private $projetManager;
    private $utiManager;
    private $cateManager; // instance du manager
    private $contManager;
    private $sourcesManager;
    private $tagsManager;
    private $appartientManager;
    private $associerManager;
    private $participerManager;// instance du manager
    private $twig;

    /**
     * Constructeur = initialisation de la connexion vers le SGBD
     */
    public function __construct($db, $twig)
    {
        $this->commentManager = new CommentaireManager($db);
        $this->projetManager = new ProjetManager($db);
        $this->utiManager = new UtilisateurManager($db);
        $this->cateManager = new CategorieManager($db);
        $this->contManager = new ContexteManager($db);
        $this->sourcesManager = new SourcesManager($db);
        $this->tagsManager = new TagsManager($db);
        $this->appartientManager = new AppartientManager($db);
        $this->associerManager = new AssocierManager($db);
        $this->participerManager = new ParticiperManager($db);
        $this->twig = $twig;
    }


    public function addComment()
    {
        if(isset($_SESSION['acces']) && $_SESSION['acces'] == "oui") {

            print_r($_POST);
            $comment = new Commentaire($_POST);
            $okComment = $this->commentManager->ajouterCommentaire($comment);
            $projs = $this->projetManager->getDetailsProj($_POST["idprojet"]);
            $detailsources = $this->sourcesManager->getDetailsSource($_POST["idprojet"]);
            $detailconts = $this->contManager->getDetailsContexte($_POST["idprojet"]);
            $detailcates = $this->cateManager->getdetailsCate($_POST["idprojet"]);
            $detailtags = $this->tagsManager->getTag($_POST["idprojet"]);
            $detailutis = $this->utiManager->getDetailsUtilisateur($_POST["idprojet"]);
            $comments = $this->commentManager->getListComment($_POST["idprojet"]);
            $message = "Commentaire ajouté avec succès";

            if (!$okComment) {
                $message = "Problème lors de l'ajout du commentaire";
            }
            echo $this->twig->render('detailproj.html.twig', array('projs' => $projs, 'detailutis' => $detailutis, 'detailtags' => $detailtags, 'detailcates' => $detailcates, 'detailconts' => $detailconts , 'detailsources' => $detailsources, 'comments' => $comments, 'message' => $message, 'acces' => $_SESSION['acces']));
        }
        else {
            $message = "Vous devez vous connecté avant d'envoyer un commentaire";
            echo $this->twig->render('utilisateur_connexion.html.twig', array('message' => $message, 'acces' => $_SESSION['acces']));
        }
    }


}