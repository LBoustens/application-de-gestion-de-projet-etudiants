<?php
include "Modules/utilisateurs.php";
include "Models/utilisateursManager.php";
/**
 * Définition d'une classe permettant de gérer les utilisateurs 
 *   en relation avec la base de données	
 */
class UtilisateurController
{
	private $utilisateurManager;

    private $participerManager;// instance du manager
	private $twig;

	/**
	 * Constructeur = initialisation de la connexion vers le SGBD
	 */
	public function __construct($db, $twig)
	{
		$this->utilisateurManager = new UtilisateurManager($db);
        $this->participerManager = new ParticiperManager($db);
		$this->twig = $twig;
	}

    /**
     * connexion
     * @param aucun
     * @return rien
     */
    function utilisateurConnexion($data)
    {

        // Vérification du login et du mot de passe et du statut pour savoir si admin
        $utilisateur = $this->utilisateurManager->verif_identification($_POST['login'], $_POST['passwd']);
        if ($utilisateur && $utilisateur->statutAdmin() == 1) { // Accès autorisé en tant qu'utilisateur
            $_SESSION['acces'] = "oui";
            $_SESSION['admin'] = "non";
            $_SESSION['idutilisateur'] = $utilisateur->idUtilisateur();
            $_SESSION['photodeprofil'] = $utilisateur->photoDeProfil();
            echo $this->twig->render('accueil.html.twig', array('admin' => $_SESSION['admin'], 'acces' => $_SESSION['acces'], 'photodeprofil'=>$_SESSION['photodeprofil'],'idutilisateur'=>$_SESSION['idutilisateur']));
        } elseif ($utilisateur && $utilisateur->statutAdmin() == 0) { // Accès autorisé en tant qu'admin
            $_SESSION['admin'] = "oui";
            $_SESSION['acces'] = "oui";
            $_SESSION['idutilisateur'] = $utilisateur->idUtilisateur();
            $_SESSION['photodeprofil'] = $utilisateur->photoDeProfil();
            echo $this->twig->render('accueil.html.twig', array('admin' => $_SESSION['admin'], 'photodeprofil'=>$_SESSION['photodeprofil'], 'acces' => $_SESSION['acces'], 'idutilisateur'=>$_SESSION['idutilisateur']));
        } else { // Accès non autorisé : variable de session acces = non
            $message = "Identification incorrecte";
            $_SESSION['admin'] = "non";
            $_SESSION['acces'] = "non";
            $_SESSION['photodeprofil'] = NULL;
            $_SESSION['idutilisateur'] = NULL;
            echo $this->twig->render('utilisateur_connexion.html.twig', array('admin' => $_SESSION['admin'], 'photodeprofil'=>$_SESSION['photodeprofil'], 'acces' => $_SESSION['acces'], 'idutilisateur'=>$_SESSION['idutilisateur'], 'message' => $message));
        }

    }

    /**
	 * deconnexion
	 * @param aucun
	 * @return rien
	 */
	function utilisateurDeconnexion()
	{
		$_SESSION['acces'] = "non";// accès non autorisé
        $_SESSION['admin'] = "non";// admin non autorisé
        $_SESSION['photodeprofil'] = NULL; // photo de profil non autorisé
        $_SESSION['idutilisateur'] = NULL; //idutilisateur non autorisé
		$message = "vous êtes déconnecté";
		echo $this->twig->render('accueil.html.twig', array('acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'photodeprofil'=>$_SESSION['photodeprofil'],'idutilisateur'=>$_SESSION['idutilisateur'], 'message' => $message));
	}

	/**
	 * formulaire de connexion
	 * @param aucun
	 * @return rien
	 */
	function utilisateurFormulaire()
	{
		echo $this->twig->render('utilisateur_connexion.html.twig', array('acces' => $_SESSION['acces']));
	}

    /**
	* ajout d'un utilisateur dans la BD
	* @param aucun
    * @return rien
	*/
    public function ajoutUtilisateur()
    {
        //fait avec chatgpt
        $email = $_POST['email'];
        $password = $_POST['mdp'];

        // Vérifiez si l'e-mail se termine par "iut-tlse3.fr"
        if (strpos($email, 'iut-tlse3.fr') === false) {
            $message = "L'adresse e-mail doit se terminer par iut-tlse3.fr";
            echo $this->twig->render('inscription.html.twig', array('message' => $message, 'acces' => $_SESSION['acces']));
        } else {
            //Ajout photo de profil Même chose que pour projet
            $targetDirectory = "photodeprofil/"; // Le dossier dans lequel vous souhaitez enregistrer les fichiers
            $targetFile = $targetDirectory . basename($_FILES["photodeprofil"]["name"]);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Vérifier si le fichier est une image réelle ou un faux fichier image
            $check = getimagesize($_FILES["photodeprofil"]["tmp_name"]);
            if ($check === false) {
                echo "Le fichier n'est pas une image.";
            }
            // Vérifier si le fichier existe déjà
            if (file_exists($targetFile)) {
                echo "Désolé, le fichier existe déjà.";
            }
            // Vérifier la taille du fichier
            if ($_FILES["photodeprofil"]["size"] > 5000000) {
                echo "Désolé, votre fichier est trop volumineux.";
            }
            // Autoriser certains formats de fichiers
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" && $imageFileType != "webp") {
                echo "Désolé, seuls les fichiers JPG, JPEG, PNG GIF et WEBP sont autorisés.";
            }

            $utilisateur = new Utilisateur($_POST);

            // Hachez le mot de passe avant de l'ajouter à la base de données
            $crypterPassword = password_hash($password, PASSWORD_DEFAULT);
            $utilisateur->setPassword($crypterPassword);

            // Si le fichier a été téléchargé avec succès, met à jour $proj avec le nom du fichier
            if (move_uploaded_file($_FILES["photodeprofil"]["tmp_name"], $targetFile)) {
                // Stockez le nom du fichier dans l'objet $proj
                $utilisateur->setPhotoDeProfil(basename($_FILES["photodeprofil"]["name"]));
                $message = "Le fichier " . htmlspecialchars(basename($_FILES["photodeprofil"]["name"])) . " a été téléchargé.";
            } else {
                echo "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
            }
            // fait par moi
            // Créez un objet Utilisateur avec les données du formulaire


            // Ajoutez l'utilisateur en base de données
            $ok = $this->utilisateurManager->add($utilisateur);

            // Générez un message en fonction du succès ou de l'échec de l'ajout
            $message = $ok ? "Votre compte a été créé" : "Problème lors de l'ajout";

            // Affichez le message approprié dans le template
            echo $this->twig->render('utilisateur_connexion.html.twig', array('message' => $message, 'acces' => $_SESSION['acces']));
        }

    }


    public function  infoprofil($idutilisateur)
    {
        $utis = $this->utilisateurManager->getUtiConnecte($idutilisateur);
        echo $this->twig->render('infoprofil.html.twig', array('utis' => $utis,'admin' => $_SESSION['admin'],'photodeprofil'=>$_SESSION['photodeprofil'], 'acces' => $_SESSION['acces']));
    }

    public function profil($idutilisateur)
	{
        $utis = $this->utilisateurManager->getUtiConnecte($idutilisateur);
		echo $this->twig->render('modifprofil.html.twig', array('utis' => $utis,'admin' => $_SESSION['admin'], 'photodeprofil'=>$_SESSION['photodeprofil'], 'acces' => $_SESSION['acces']));
	}

    /**
     * Modification dans la BD d'un projet à partir des données du formulaire précédent
     * @param int $idutilisateur
     * @return void
     */
    public function updateProfil(int $idutilisateur)
    {
        $uti = new Utilisateur($_POST);
        $message = "";

        // Vérifier si le fichier a été modifié
        if ($_FILES["photodeprofil"]["size"] > 0) {
            $targetDirectory = "photodeprofil/";
            $targetFile = $targetDirectory . basename($_FILES["photodeprofil"]["name"]);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Vérifier si le fichier est une image réelle ou un faux fichier image
            $check = getimagesize($_FILES["photodeprofil"]["tmp_name"]);
            if ($check === false) {
                // Gérer l'erreur
                echo "Le fichier n'est pas une image.";
                return;
            }

            // Vérifier la taille du fichier
            if ($_FILES["photodeprofil"]["size"] > 5000000) {
                // Gérer l'erreur
                echo "Désolé, votre fichier est trop volumineux.";
                return;
            }

            // Autoriser certains formats de fichiers
            $allowedFormats = ["jpg", "jpeg", "png", "gif", "webp"];
            if (!in_array($imageFileType, $allowedFormats)) {
                // Gérer l'erreur
                echo "Désolé, seuls les fichiers JPG, JPEG, PNG GIF et WEBP sont autorisés.";
                return;
            }

            // Si le fichier a été téléchargé avec succès, met à jour $proj avec le nom du fichier
            if (move_uploaded_file($_FILES["photodeprofil"]["tmp_name"], $targetFile)) {
                $uti->setPhotoDeProfil(basename($_FILES["photodeprofil"]["name"]));
                $message .= "Le fichier " . htmlspecialchars(basename($_FILES["photodeprofil"]["name"])) . " a été téléchargé.";
            } else {
                // Gérer l'erreur d'upload
                echo "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
                return;
            }
        } else {
            // Si aucun fichier n'est téléchargé, conserve le nom de l'image existante
            $utiExistant = $this->utilisateurManager->getUtiConnecte($_POST["idutilisateur"]); // Vous devrez peut-être ajuster la méthode selon votre structure de base de données
            $uti->setPhotoDeProfil($utiExistant->photoDeProfil());
        }

        $okUti = $this->utilisateurManager->updateUtilisateur($uti);
        $utis = $this->utilisateurManager->getUtiConnecte($idutilisateur);
        $_SESSION['photodeprofil'] = $utis->photoDeProfil();

        // Vérifie s'il y a eu au moins une modification
        if ($okUti > 0 ) {
            $message .= "Projet modifié avec succès ";
        } else {
            $message .= "Aucune modification n'a été effectuée";
        }

        if ($message != "") {
            echo $this->twig->render('infoprofil.html.twig', array('utis' => $utis, 'message' => $message, 'admin' => $_SESSION['admin'], 'photodeprofil' => $_SESSION['photodeprofil'], 'acces' => $_SESSION['acces']));
        }
    }

    public function utiadmin()
    {
        $utis = $this->utilisateurManager->getListUtiAdmin();
        echo $this->twig->render('utiadmin.html.twig', array('utis' => $utis, 'admin' => $_SESSION['admin'], 'photodeprofil'=>$_SESSION['photodeprofil'], 'acces' => $_SESSION['acces']));
    }

    public function addUtiAdmin()
    {
        print_r($_POST);
        //Même chose que formulaire d'inscription
        $email = $_POST['email'];

        // Vérifiez si l'e-mail se termine par "iut-tlse3.fr"
        if (strpos($email, 'iut-tlse3.fr') === false) {
            $message = "L'adresse e-mail doit se terminer par iut-tlse3.fr";
            echo $this->twig->render('utiadmin.html.twig', array('message' => $message, 'admin' => $_SESSION['admin'], 'photodeprofil'=>$_SESSION['photodeprofil'], 'acces' => $_SESSION['acces']));
        } else {

            //Ajout photo de profil Même chose que pour projet
            $targetDirectory = "photodeprofil/"; // Le dossier dans lequel vous souhaitez enregistrer les fichiers
            $targetFile = $targetDirectory . basename($_FILES["photodeprofil"]["name"]);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Vérifier si le fichier est une image réelle ou un faux fichier image
            $check = getimagesize($_FILES["photodeprofil"]["tmp_name"]);
            if ($check === false) {
                echo "Le fichier n'est pas une image.";
            }
            // Vérifier si le fichier existe déjà
            if (file_exists($targetFile)) {
                echo "Désolé, le fichier existe déjà.";
            }
            // Vérifier la taille du fichier
            if ($_FILES["photodeprofil"]["size"] > 5000000) {
                echo "Désolé, votre fichier est trop volumineux.";
            }
            // Autoriser certains formats de fichiers
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" && $imageFileType != "webp") {
                echo "Désolé, seuls les fichiers JPG, JPEG, PNG GIF et WEBP sont autorisés.";
            }

            $utilisateur = new Utilisateur($_POST);
            // Si le fichier a été téléchargé avec succès, met à jour $proj avec le nom du fichier
            if (move_uploaded_file($_FILES["photodeprofil"]["tmp_name"], $targetFile)) {
                // Stockez le nom du fichier dans l'objet $proj
                $utilisateur->setPhotoDeProfil(basename($_FILES["photodeprofil"]["name"]));
                $message = "Le fichier " . htmlspecialchars(basename($_FILES["photodeprofil"]["name"])) . " a été téléchargé.";
            } else {
                echo "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
            }
            // Ajoutez l'utilisateur en base de données
            $ok = $this->utilisateurManager->add($utilisateur);
            $utis = $this->utilisateurManager->getListUtiAdmin();

            // Générez un message en fonction du succès ou de l'échec de l'ajout
            $message = $ok ? "Le compte a été créé" : "Problème lors de l'ajout";
            echo $this->twig->render('utiadmin.html.twig', array('utis' => $utis, 'message' => $message, 'admin' => $_SESSION['admin'], 'photodeprofil'=>$_SESSION['photodeprofil'], 'acces' => $_SESSION['acces']));
        }
    }
    public function suppUtiAdmin()
    {
        $liaisonuti = new Participer($_POST);
        $utilisateur = new Utilisateur($_POST);
        $okLiaisonuti = $this->participerManager->deleteParticiperAdmin($liaisonuti);
        $okUtilisateur = $this->utilisateurManager->deleteUtilisateurAdmin($utilisateur);
        $utis = $this->utilisateurManager->getListUtiAdmin();

        $message = "Utilisateur supprimé avec succès";

        if (!$okLiaisonuti) {
            $message .= "Problème lors de la suppression de la liaison entre participer et utilisateur";
        }
        if (!$okUtilisateur) {
            $message .= "Problème lors de la suppression d'un utilisateur";
        }
        echo $this->twig->render('utiadmin.html.twig', array('utis' => $utis, 'message' => $message, 'admin' => $_SESSION['admin'], 'photodeprofil'=>$_SESSION['photodeprofil'], 'acces' => $_SESSION['acces']));
    }




}