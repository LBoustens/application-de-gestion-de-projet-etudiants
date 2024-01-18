<?php
include "Modules/projets.php";
include "Models/projetsManager.php";
/**
 * Définition d'une classe permettant de gérer les projets
 *   en relation avec la base de données	
 */
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

	private $twig;

	/**
	 * Constructeur = initialisation de la connexion vers le SGBD
	 */
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
		$this->twig = $twig;
	}

	/**
	 * liste de tous les projets
	 * @param aucun
	 * @return rien
	 */
	public function listeProjets()
	{
		$projets = $this->projetManager->getList();
		// $tags = $this->tagsManager->getTags($projets);
		// var_dump($tags);
		// $cates = $this->cateManager->getCategorie();
		echo $this->twig->render('projets_liste.html.twig', array('projets' => $projets, 'acces' => $_SESSION['acces']));
	}

	// détails d'un projet
	function detailsProjet()
	{
		$projs = $this->projetManager->getDetailsProj($_POST["idprojet"]);
		$detailsources = $this->sourcesManager->getDetailsSource($_POST["idprojet"]);
		$detailconts = $this->contManager->getDetailsContexte($_POST["idprojet"]);
		$detailcates = $this->cateManager->getdetailsCate($_POST["idprojet"]);
		$detailtags = $this->tagsManager->getTag($_POST["idprojet"]);
		$detailutis = $this->utiManager->getDetailsUtilisateur($_POST["idprojet"]);
		echo $this->twig->render('detailproj.html.twig', array('projs' => $projs, 'detailutis' => $detailutis, 'detailtags' => $detailtags, 'detailcates' => $detailcates, 'detailconts' => $detailconts , 'detailsources' => $detailsources));
	}

	/**
	 * liste de mes projets
	 * @param aucun
	 * @return rien
	 */
	public function listeMesProjets($idutilisateur)
	{
		$projets = $this->projetManager->getListUtilisateur($idutilisateur);
		echo $this->twig->render('mesprojets.html.twig', array('projets' => $projets, 'acces' => $_SESSION['acces']));
	}
	/**
	 * formulaire ajout
	 * @param aucun
	 * @return rien
	 */
	public function formAjoutProjet()
	{
		$cates = $this->cateManager->getCategorie();
		$cont = $this->contManager->getContexte();
		echo $this->twig->render('projet_ajout.html.twig', array('acces' => $_SESSION['acces'], 'cates' => $cates, 'cont' => $cont, 'idutilisateur' => $_SESSION['idutilisateur']));
	}

	/**
	 * ajout dans la BD d'un projet à partir du form
	 * @param aucun
	 * @return rien
	 */
	public function ajoutProjet()
	{

        // fait avec Chat GPT
    $targetDirectory = "img/"; // Le dossier dans lequel vous souhaitez enregistrer les fichiers
    $targetFile = $targetDirectory . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Vérifier si le fichier est une image réelle ou un faux fichier image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        echo "Le fichier n'est pas une image.";
        $uploadOk = 0;
    }

    // Vérifier si le fichier existe déjà
    if (file_exists($targetFile)) {
        echo "Désolé, le fichier existe déjà.";
        $uploadOk = 0;
    }

    // Vérifier la taille du fichier
    if ($_FILES["image"]["size"] > 5000000) {
        echo "Désolé, votre fichier est trop volumineux.";
        $uploadOk = 0;
    }

    // Autoriser certains formats de fichiers (ajustez en fonction de vos besoins)
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" && $imageFileType != "webp") {
        echo "Désolé, seuls les fichiers JPG, JPEG, PNG GIF et WEBP sont autorisés.";
        $uploadOk = 0;
    }




        $proj = new Projet($_POST);
        // Si le fichier a été téléchargé avec succès, met à jour $proj avec le nom du fichier
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            // Stockez le nom du fichier dans l'objet $proj
            $proj->setImage(basename($_FILES["image"]["name"]));

            $message = "Le fichier " . htmlspecialchars(basename($_FILES["image"]["name"])) . " a été téléchargé.";

            // Continuez avec le reste de votre code
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
		$okAppartient = $this->appartientManager->liaisonCate($liaisoncate, $proj);
		$okAssocier = $this->associerManager->liaisonTags($tags, $proj);
		$okParti = $this->participerManager->liaisonUtilisateur($parti, $proj);

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


		echo $this->twig->render('index.html.twig', array('message' => $message, 'acces' => $_SESSION['acces']));
	}


	/**
	 * form de choix d'un projet  à supprimer
	 * @param aucun
	 * @return rien
	 */
	public function choixSuppProjet($idutilisateur)
	{
		$projets = $this->projetManager->getListUtilisateur($idutilisateur);
		echo $this->twig->render('projet_choix_suppression.html.twig', array('projets' => $projets, 'acces' => $_SESSION['acces']));
	}
	/**
	 * suppression dans la BD d'un projet à partir de l'id choisi dans le form précédent
	 * @param aucun
	 * @return rien
	 */
	public function suppProjet()
	{
		$proj = new Projet($_POST);
		$ok = $this->projetManager->delete($proj);
		$message = $ok ? "Projet supprimé" : "probleme lors de la supression";
		echo $this->twig->render('index.html.twig', array('message' => $message, 'acces' => $_SESSION['acces']));
	}
	/**
	 * form de choix du projet à modifier
	 * @param aucun
	 * @return rien
	 */
	public function choixModProjet($idutilisateur)
	{
		$projets = $this->projetManager->getListUtilisateur($idutilisateur);
		echo $this->twig->render('projet_choix_modification.html.twig', array('projets' => $projets, 'acces' => $_SESSION['acces']));
	}
	/**
	 * form de saisi des nouvelles valeurs du projet à modifier
	 * @param aucun
	 * @return rien
	 */
	public function saisieModProjet()
	{
		$projs = $this->projetManager->get($_POST["idprojet"]);
		echo $this->twig->render('projet_modification.html.twig', array('projs' => $projs, 'acces' => $_SESSION['acces']));
	}

	/**
	 * modification dans la BD d'un projet à partir des données du form précédent
	 * @param aucun
	 * @return rien
	 */
	public function modProjet()
	{
		$proj = new Projet($_POST);
		$ok = $this->projetManager->update($proj);
		$message = $ok ? "Projet modifié" : $message = "probleme lors de la modification";
		echo $this->twig->render('index.html.twig', array('message' => $message, 'acces' => $_SESSION['acces']));
	}

	/**
	 * form de saisie des criteres
	 * @param aucun
	 * @return rien
	 */
	// public function formRechercheProjet() {
	// 	echo $this->twig->render('projet_recherche.html.twig',array('acces'=> $_SESSION['acces']));
	// }

	/**
	 * recherche dans la BD d'un projet à partir des données du form précédent
	 * @param aucun
	 * @return rien
	 */
	// 	public function rechercheProjet() {
// 		$projets = $this->itiManager->search($_POST["lieudepart"], $_POST["lieuarrivee"], $_POST["datedepart"]);
// 		echo $this->twig->render('projets_liste.html.twig',array('itis'=>$projets,'acces'=> $_SESSION['acces']));
// 	}





	// affichage de la page d'accueil
	public function accueil()
	{
		echo $this->twig->render('accueil.html.twig', array('acces' => $_SESSION['acces']));
	}

	// affichage du form d'inscription
	public function inscription()
	{
		echo $this->twig->render('inscription.html.twig', array('acces' => $_SESSION['acces']));
	}
	// affichage du form de contact
	public function contact()
	{
		echo $this->twig->render('contact.html.twig', array('acces' => $_SESSION['acces']));
	}

	public function mentions()
	{
		echo $this->twig->render('mentions.html.twig', array('acces' => $_SESSION['acces']));
	}

	public function politique()
	{
		echo $this->twig->render('politique.html.twig', array('acces' => $_SESSION['acces']));
	}







}