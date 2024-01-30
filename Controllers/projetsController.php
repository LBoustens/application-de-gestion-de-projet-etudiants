<?php
include "Modules/projets.php";
include "Models/projetsManager.php";

// Définition d'une classe permettant de controller les projets en relation avec la base de données
class ProjetController
{
    private $projetManager;
    private $utiManager;
    private $cateManager; // instance du manager
    private $contManager;
    private $sourcesManager;
    private $tagsManager;
    private $appartientManager;
    private $associerManager;
    private $participerManager;
    private $commentManager;
    private $noteManager;
    private $twig;

    // Constructeur = initialisation de la connexion vers le SGBD
    public function __construct($db, $twig)
    {
        $this->projetManager = new ProjetManager($db);
        $this->utiManager = new UtilisateurManager($db);
        $this->cateManager = new CategorieManager($db);
        $this->contManager = new ContexteManager($db);
        $this->sourcesManager = new SourcesManager($db);
        $this->tagsManager = new TagsManager($db);
        $this->appartientManager = new AppartientManager($db);
        $this->associerManager = new AssocierManager($db);
        $this->participerManager = new ParticiperManager($db);
        $this->commentManager = new CommentaireManager($db);
        $this->noteManager = new NotationManager($db);
        $this->twig = $twig;
    }

    /**
     * liste de tous les projets
     * @return void
     */
    public function listeProjets()
    {
        $projets = $this->projetManager->getList();
        echo $this->twig->render('projets_liste.html.twig', array('projets' => $projets, 'admin' => $_SESSION['admin'], 'photodeprofil' => $_SESSION['photodeprofil'], 'acces' => $_SESSION['acces']));
    }

    /** détail d'un projet
     * @return void
     */
    function detailsProjet()
    {
        $projs = $this->projetManager->getProj($_POST["idprojet"]);
        $detailsources = $this->sourcesManager->getDetailsSource($_POST["idprojet"]);
        $detailconts = $this->contManager->getDetailsContexte($_POST["idprojet"]);
        $detailcates = $this->cateManager->getdetailsCate($_POST["idprojet"]);
        $detailtags = $this->tagsManager->getDetailsTag($_POST["idprojet"]);
        $detailutis = $this->utiManager->getDetailsUtilisateur($_POST["idprojet"]);
        $comments = $this->commentManager->getListComment($_POST["idprojet"]);
        $notes = $this->noteManager->getListNote($_POST["idprojet"]);
        echo $this->twig->render('detailproj.html.twig', array('projs' => $projs, 'detailutis' => $detailutis, 'detailtags' => $detailtags, 'detailcates' => $detailcates, 'detailconts' => $detailconts, 'detailsources' => $detailsources, 'comments' => $comments, 'notes' => $notes, 'acces' => $_SESSION['acces'], 'photodeprofil' => $_SESSION['photodeprofil'], 'admin' => $_SESSION['admin'], 'idutilisateur' => $_SESSION['idutilisateur']));
    }

    /** liste des projets de l'utilisateur connecté
     * @param $idutilisateur
     * @return void
     */
    public function listeMesProjets($idutilisateur)
    {
        $projets = $this->projetManager->getListUtilisateur($idutilisateur);
        echo $this->twig->render('mesprojets.html.twig', array('projets' => $projets, 'admin' => $_SESSION['admin'], 'photodeprofil' => $_SESSION['photodeprofil'], 'acces' => $_SESSION['acces']));
    }

    /** formulaire ajout d'un projet
     * @return void
     */
    public function formAjoutProjet()
    {
        $cates = $this->cateManager->getCategorie();
        $cont = $this->contManager->getContexte();
        echo $this->twig->render('projet_ajout.html.twig', array('cates' => $cates, 'cont' => $cont, 'photodeprofil' => $_SESSION['photodeprofil'], 'admin' => $_SESSION['admin'], 'acces' => $_SESSION['acces'], 'idutilisateur' => $_SESSION['idutilisateur']));
    }

    /** ajout dans la BD d'un projet à partir du form
     * @return void
     */
    public function ajoutProjet($idutilisateur)
    {
        // fait avec Chat GPT
        $targetDirectory = "img/"; // Le dossier dans lequel j'ai enregistré les fichiers
        $targetFile = $targetDirectory . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Vérifie si le fichier est une image réelle ou un faux fichier image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            echo "Le fichier n'est pas une image.";
        }
        // Vérifie si le fichier existe déjà
        if (file_exists($targetFile)) {
            echo "Désolé, le fichier existe déjà.";
        }
        // Vérifie la taille du fichier
        if ($_FILES["image"]["size"] > 5000000) {
            echo "Désolé, votre fichier est trop volumineux.";
        }
        // Autorise certains formats de fichiers
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" && $imageFileType != "webp") {
            echo "Désolé, seuls les fichiers JPG, JPEG, PNG GIF et WEBP sont autorisés.";
        }

        $proj = new Projet($_POST);
        // Si le fichier a été téléchargé avec succès, met à jour $proj avec le nom du fichier
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            // Stocke le nom du fichier dans l'objet $proj
            $proj->setImage(basename($_FILES["image"]["name"]));
        } else {
            echo "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
        }
        // Fin de l'utilisation de Chat GPT
        $tags = new Tags($_POST);
        $sources = new Sources($_POST);
        $liaisoncate = new Appartient($_POST);
        $parti = new Participer($_POST);
        $okProjet = $this->projetManager->add($proj);
        $okTags = $this->tagsManager->addTags($tags);
        $okSources = $this->sourcesManager->addSources($sources, $proj);
        $okAppartient = $this->appartientManager->addLiaisonCate($liaisoncate, $proj);
        $okAssocier = $this->associerManager->addLiaisonTags($tags, $proj);
        $okParti = $this->participerManager->addLiaisonUtilisateur($parti, $proj);
        $projets = $this->projetManager->getListUtilisateur($idutilisateur);

        $message = "Projet ajouté avec succès";

        if (!$okProjet) {
            $message = "Problème lors de l'ajout du projet";
        }

        if (!$okTags) {
            $message .= " - Problème lors de l'ajout des tags";
        }

        if (!$okSources) {
            $message .= " - Problème lors de l'ajout des sources";
        }

        if (!$okAppartient) {
            $message .= " - Problème lors de l'ajout des sources";
        }

        if (!$okAssocier) {
            $message .= " - Problème lors de l'ajout des sources";
        }

        if (!$okParti) {
            $message .= " - Problème lors de l'ajout des sources";
        }

        echo $this->twig->render('mesprojets.html.twig', array('projets' => $projets, 'message' => $message, 'admin' => $_SESSION['admin'], 'photodeprofil' => $_SESSION['photodeprofil'], 'acces' => $_SESSION['acces']));
    }

    /**
     * form de choix d'un projet à supprimer
     * @param  $idutilisateur
     * @return void
     */
    public function choixSuppProjet($idutilisateur)
    {
        $projets = $this->projetManager->getListUtilisateur($idutilisateur);
        echo $this->twig->render('projet_choix_suppression.html.twig', array('projets' => $projets, 'photodeprofil' => $_SESSION['photodeprofil'], 'admin' => $_SESSION['admin'], 'acces' => $_SESSION['acces']));
    }

    /**
     * suppression dans la BD d'un projet avec les tags, les sources et les liaisons à partir de l'id choisi dans le form
     * @param $idutilisateur
     * @return void
     */
    public function suppProjet($idutilisateur)
    {
        $sources = new Sources($_POST);
        $liaisoncate = new Appartient($_POST);
        $parti = new Participer($_POST);
        $assostags = new Associer($_POST);
        $note = new Notation($_POST);
        $comment = new Commentaire($_POST);
        $proj = new Projet($_POST);

        $okSources = $this->sourcesManager->deleteSources($sources);
        $okAppartient = $this->appartientManager->deleteAppartient($liaisoncate);
        $okParti = $this->participerManager->deleteParticiper($parti);
        $okAssostags = $this->associerManager->deleteLiaisonTags($assostags);
        $okTags = $this->tagsManager->deleteTags();
        $okNote = $this->noteManager->deleteNote($note);
        $okComment = $this->commentManager->deleteComment($comment);
        $okProjet = $this->projetManager->delete($proj);
        $projets = $this->projetManager->getListUtilisateur($idutilisateur);

        $message = "Projet supprimé avec succès";

        if (!$okSources) {
            $message .= "Problème lors de la suppression des sources";
        }

        if (!$okAppartient) {
            $message .= "Problème lors de la suppression des sources";
        }

        if (!$okParti) {
            $message .= "Problème lors de la suppression des sources";
        }

        if (!$okAssostags) {
            $message .= "Problème lors de la suppression des tags";
        }

        if (!$okTags) {
            $message .= "Problème lors de la suppression des tags";
        }

        if (!$okNote) {
            $message .= "Problème lors de la suppression des tags";
        }

        if (!$okComment) {
            $message .= "Problème lors de la suppression des tags";
        }

        if (!$okProjet) {
            $message = "Problème lors de la suppression du projet";
        }

        echo $this->twig->render('mesprojets.html.twig', array('projets' => $projets, 'message' => $message, 'admin' => $_SESSION['admin'], 'photodeprofil' => $_SESSION['photodeprofil'], 'acces' => $_SESSION['acces']));
    }

    /**
     * form de choix du projet à modifier
     * @param $idutilisateur
     * @return void
     */
    public function choixModProjet($idutilisateur)
    {
        $projets = $this->projetManager->getListUtilisateur($idutilisateur);
        echo $this->twig->render('projet_choix_modification.html.twig', array('projets' => $projets, 'admin' => $_SESSION['admin'], 'photodeprofil' => $_SESSION['photodeprofil'], 'acces' => $_SESSION['acces']));
    }

    /**
     * form de saisi des nouvelles valeurs du projet à modifier
     * @return void
     */
    public function saisieModProjet()
    {
        $projs = $this->projetManager->getProj($_POST["idprojet"]);
        $sources = $this->sourcesManager->getDetailsSource($_POST["idprojet"]);
        $cates = $this->cateManager->getCategorie();
        $cont = $this->contManager->getContexte();
        $tags = $this->tagsManager->getDetailsTag($_POST["idprojet"]);
        echo $this->twig->render('projet_modification.html.twig', array('projs' => $projs, 'tags' => $tags, 'cates' => $cates, 'cont' => $cont, 'sources' => $sources, 'admin' => $_SESSION['admin'], 'photodeprofil' => $_SESSION['photodeprofil'], 'acces' => $_SESSION['acces']));
    }

    /**
     * Modification dans la BD d'un projet à partir des données du formulaire précédent
     * @param int $idutilisateur
     * @return void
     */
    public function modProjet(int $idutilisateur)
    {
        $appartient = new Appartient($_POST);
        $source = new Sources($_POST);
        $tag = new Tags($_POST);
        $proj = new Projet($_POST);
        $message = "";

        // Vérifie si le fichier a été modifié
        if ($_FILES["image"]["size"] > 0) {
            $targetDirectory = "img/";
            $targetFile = $targetDirectory . basename($_FILES["image"]["name"]);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Vérifie si le fichier est une image réelle ou un faux fichier image
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check === false) {
                // Gére l'erreur
                echo "Le fichier n'est pas une image.";
                return;
            }

            // Vérifie la taille du fichier
            if ($_FILES["image"]["size"] > 5000000) {
                // Gérer l'erreur
                echo "Désolé, votre fichier est trop volumineux.";
                return;
            }

            // Autorise certains formats de fichiers
            $allowedFormats = ["jpg", "jpeg", "png", "gif", "webp"];
            if (!in_array($imageFileType, $allowedFormats)) {
                // Gérer l'erreur
                echo "Désolé, seuls les fichiers JPG, JPEG, PNG GIF et WEBP sont autorisés.";
                return;
            }

            // Si le fichier a été téléchargé avec succès, met à jour $proj avec le nom du fichier
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                $proj->setImage(basename($_FILES["image"]["name"]));
                $message .= "Le fichier " . htmlspecialchars(basename($_FILES["image"]["name"])) . " a été téléchargé.";
            } else {
                // Gére l'erreur d'upload
                echo "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
                return;
            }
        } else {
            // Si aucun fichier n'est téléchargé, conserve le nom de l'image existante
            $projExistant = $this->projetManager->getProj($_POST["idprojet"]); // Vous devrez peut-être ajuster la méthode selon votre structure de base de données
            $proj->setImage($projExistant->image());
        }

        $okAppartient = $this->appartientManager->updateAppartient($appartient);
        $okSource = $this->sourcesManager->updateSource($source);
        $okTag = $this->tagsManager->updateTag($tag);
        $okProj = $this->projetManager->update($proj);
        $projets = $this->projetManager->getListUtilisateur($idutilisateur);

        // Vérifie s'il y a eu au moins une modification
        if ($okAppartient > 0 || $okSource > 0 || $okProj > 0 || $okTag > 0) {
            $message .= "Projet modifié avec succès ";
        } else {
            $message .= "Aucune modification n'a été effectuée";
        }

        if ($message != "") {
            echo $this->twig->render('mesprojets.html.twig', array('projets' => $projets, 'message' => $message, 'admin' => $_SESSION['admin'], 'photodeprofil' => $_SESSION['photodeprofil'], 'acces' => $_SESSION['acces']));
        }
    }

    /**
     * recherche dans la BD d'un projet à partir des données du form
     * @return void
     */
    public function rechercheProjet()
    {
        $projets = $this->projetManager->searchProj($_POST["titre"], $_POST["descproj"]);
        echo $this->twig->render('projets_liste.html.twig', array('projets' => $projets, 'admin' => $_SESSION['admin'], 'photodeprofil' => $_SESSION['photodeprofil'], 'acces' => $_SESSION['acces']));
    }

    /** affichage de la page d'accueil
     * @return void
     */
    public function accueil()
    {
        echo $this->twig->render('accueil.html.twig', array('photodeprofil' => $_SESSION['photodeprofil'], 'admin' => $_SESSION['admin'], 'acces' => $_SESSION['acces']));
    }

    /** affichage du form d'inscription
     * @return void
     */
    public function inscription()
    {
        echo $this->twig->render('inscription.html.twig', array('acces' => $_SESSION['acces']));
    }

    /** affichage de la page mentions légales
     * @return void
     */
    public function mentions()
    {
        echo $this->twig->render('mentions.html.twig', array('admin' => $_SESSION['admin'], 'photodeprofil' => $_SESSION['photodeprofil'], 'acces' => $_SESSION['acces']));
    }

    /** affichage de la page politique de confidentialité
     * @return void
     */
    public function politique()
    {
        echo $this->twig->render('politique.html.twig', array('admin' => $_SESSION['admin'], 'photodeprofil' => $_SESSION['photodeprofil'], 'acces' => $_SESSION['acces']));
    }

}

?>