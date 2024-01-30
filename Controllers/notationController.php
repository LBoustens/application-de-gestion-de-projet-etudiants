<?php
include "Modules/notation.php";
include "Models/notationManager.php";

// Définition d'une classe permettant de controller les notes en relation avec la base de données
class NotationController
{
    private $noteManager;
    private $projetManager;
    private $utiManager;
    private $cateManager; // instance du manager
    private $contManager;
    private $sourcesManager;
    private $tagsManager;
    private $appartientManager;
    private $associerManager;
    private $commentManager;
    private $participerManager;// instance du manager
    private $twig;

    /**
     * Constructeur = initialisation de la connexion vers le SGBD
     */
    public function __construct($db, $twig)
    {
        $this->noteManager = new NotationManager($db);
        $this->projetManager = new ProjetManager($db);
        $this->utiManager = new UtilisateurManager($db);
        $this->cateManager = new CategorieManager($db);
        $this->contManager = new ContexteManager($db);
        $this->sourcesManager = new SourcesManager($db);
        $this->tagsManager = new TagsManager($db);
        $this->appartientManager = new AppartientManager($db);
        $this->associerManager = new AssocierManager($db);
        $this->commentManager = new CommentaireManager($db);
        $this->participerManager = new ParticiperManager($db);
        $this->twig = $twig;
    }

    /**
     * ajout d'une note dans la BD
     * @return void
     */
    public function addNote()
    {
        if (isset($_SESSION['acces']) && $_SESSION['acces'] == "oui") {
            $note = new Notation($_POST);
            $okNote = $this->noteManager->ajouterNote($note);
            //affichage des details appès l'envoie
            $projs = $this->projetManager->getProj($_POST["idprojet"]);
            $detailsources = $this->sourcesManager->getDetailsSource($_POST["idprojet"]);
            $detailconts = $this->contManager->getDetailsContexte($_POST["idprojet"]);
            $detailcates = $this->cateManager->getdetailsCate($_POST["idprojet"]);
            $detailtags = $this->tagsManager->getDetailsTag($_POST["idprojet"]);
            $detailutis = $this->utiManager->getDetailsUtilisateur($_POST["idprojet"]);
            $comments = $this->commentManager->getListComment($_POST["idprojet"]);
            $notes = $this->noteManager->getListNote($_POST["idprojet"]);
            $message = "Note ajouté avec succès";

            if (!$okNote) {
                $message = "Problème lors de l'ajout du note";
            }
            echo $this->twig->render('detailproj.html.twig', array('projs' => $projs, 'detailutis' => $detailutis, 'detailtags' => $detailtags, 'detailcates' => $detailcates, 'detailconts' => $detailconts, 'detailsources' => $detailsources, 'comments' => $comments, 'notes' => $notes, 'message' => $message, 'admin' => $_SESSION['admin'], 'acces' => $_SESSION['acces'], 'photodeprofil' => $_SESSION['photodeprofil'], 'idutilisateur' => $_SESSION['idutilisateur']));
        } else {
            $message = "Vous devez vous connecté avant d'envoyer une note";
            echo $this->twig->render('utilisateur_connexion.html.twig', array('message' => $message, 'acces' => $_SESSION['acces']));
        }
    }
}

?>