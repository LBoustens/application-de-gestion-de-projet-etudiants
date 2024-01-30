<?php
// utilisation des sessions
global $bdd, $twig;
session_start();

include "moteurtemplate.php";
include "connect.php";

include "Controllers/projetsController.php";
include "Controllers/utilisateursController.php";
include "Controllers/contexteController.php";
include "Controllers/categorieController.php";
include "Controllers/sourcesController.php";
include "Controllers/tagsController.php";
include "Controllers/appartientController.php";
include "Controllers/associerController.php";
include "Controllers/participerController.php";
include "Controllers/commentaireController.php";
include "Controllers/notationController.php";
$projController = new ProjetController($bdd, $twig);
$utiController = new UtilisateurController($bdd, $twig);
$contController = new ContexteController($bdd, $twig);
$cateController = new CategorieController($bdd, $twig);
$sourcesController = new SourcesController($bdd, $twig);
$tagsController = new TagsController($bdd, $twig);
$appartientController = new AppartientController($bdd, $twig);
$associerController = new AssocierController($bdd, $twig);
$participerController = new ParticiperController($bdd, $twig);
$commentaireController = new CommentaireController($bdd, $twig);
$notationController = new NotationController($bdd, $twig);

// texte du message
$message = "";

// ============================== connexion / deconnexion - sessions ==================

// si les variables de session n'existe pas, on les crées
if (!isset($_SESSION['acces'])) {
  $_SESSION['acces'] = "non";
}
if (!isset($_SESSION['admin'])) {
    $_SESSION['admin'] = "non";

}if (!isset($_SESSION['photodeprofil'])) {
    $_SESSION['photodeprofil'] = NULL;
}

if (!isset($_SESSION['idutilisateur'])) {
    $_SESSION['idutilisateur'] = NULL;
}
// click sur le bouton connexion
if (isset($_POST["connexion"])) {
  $message = $utiController->utilisateurConnexion($_POST);
}
// deconnexion : click sur le bouton deconnexion
if (isset($_GET["action"]) && $_GET['action'] == "logout") {
  $message = $utiController->utilisateurDeconnexion();
}
// formulaire de connexion
if (isset($_GET["action"]) && $_GET["action"] == "login") {
  $utiController->utilisateurFormulaire();
}

// ============================== page d'accueil ==================

// cas par défaut = page d'accueil
if (!isset($_GET["action"]) && empty($_POST)) {
  echo $twig->render('accueil.html.twig', array('acces' => $_SESSION['acces']));
}

// ============================== gestion des Projets ==================

// liste des projets
if (isset($_GET["action"]) && $_GET["action"] == "projets") {
  $projController->listeProjets();
}
// liste des projets de l'utilisateur connecté
if (isset($_GET["action"]) && $_GET["action"] == "mesprojets") {
  $projController->listeMesProjets($_SESSION['idutilisateur']);
}
// formulaire ajout d'un projet
if (isset($_GET["action"]) && $_GET["action"] == "ajout") {
  $projController->formAjoutProjet();
}
// suppression d'un projet : choix du projet
if (isset($_GET["action"]) && $_GET["action"] == "suppr") {
  $projController->choixSuppProjet($_SESSION['idutilisateur']);
}
// supression d'un projet dans la base
if (isset($_POST["valider_supp"])) {
  $projController->suppProjet($_SESSION['idutilisateur']);
}
// modification d'un projet : choix du projet
if (isset($_GET["action"]) && $_GET["action"] == "modif") {
  $projController->choixModProjet($_SESSION['idutilisateur']);
}
// modification d'un projet : saisie des nouvelles valeurs
if (isset($_POST["saisie_modif"])) {
  $projController->saisieModProjet();
}
//modification d'un projet : enregistrement dans la bd
if (isset($_POST["valider_modif"])) {
  $projController->modProjet($_SESSION['idutilisateur']);
}
// ajout de l'utilisateur dans la base
if (isset($_POST["inscription"])) {
  $utiController->ajoutUtilisateur();
}
// ajout du projet dans la base
if (isset($_POST["ajouter_proj"])) {
  $projController->ajoutProjet($_SESSION['idutilisateur']);
}
//  recherche des itineraires : construction de la requete SQL en fonction des critères
// --> au clic sur le bouton "valider_recher" du form
if (isset($_POST["valider_recher"])) {
   $projController->rechercheProjet();
 }
// Page des détails d'un projet
if (isset($_GET["action"]) && $_GET["action"] == "details") {
  $projController->detailsProjet();
}
// Page d'accueil de l'app
if (isset($_GET["action"]) && $_GET["action"] == "accueil") {
  $projController->accueil();
}
// Formulaire d'inscription
if (isset($_GET["action"]) && $_GET["action"] == "inscription") {
  $projController->inscription();
}
// Page de contact
if (isset($_GET["action"]) && $_GET["action"] == "contact") {
  $projController->contact();
}
// Page d'info d'un utilisateur
if (isset($_GET["action"]) && $_GET["action"] == "infoprofil") {
    $utiController->infoprofil($_SESSION['idutilisateur']);
}
// form éditer profil
if (isset($_GET["action"]) && $_GET["action"] == "profil") {
  $utiController->profil($_SESSION['idutilisateur']);
}
//modification d'un projet : enregistrement dans la bd
if (isset($_POST["valider_modifuti"])) {
    $utiController->updateProfil($_SESSION['idutilisateur']);
}
// Envoie d'un commentaire dans la base
if (isset($_POST["ajouter_comment"])) {
    $commentaireController->addComment();
}
// Envoie d'une note dans la base
if (isset($_POST["ajouter_note"])) {
    $notationController->addNote();
}
// Page de mentions légales
if (isset($_GET["action"]) && $_GET["action"] == "mentions") {
  $projController->mentions();
}
// Page de politique de confidentialité
if (isset($_GET["action"]) && $_GET["action"] == "politique") {
  $projController->politique();
}
// Page utilisateur de l'admin
if (isset($_GET["action"]) && $_GET["action"] == "utiadmin") {
  $utiController->utiadmin();
}
// Supression d'un utilisateur dans la base
if (isset($_POST["supp_uti"])) {
    $utiController->suppUtiAdmin();
}
// Ajout d' utilisateur dans la base
if (isset($_POST["ajout_uti"])) {
    $utiController->addUtiAdmin();
}
// Page categorie de l'admin
if (isset($_GET["action"]) && $_GET["action"] == "cateadmin") {
  $cateController->cateadmin();
}
// Supression d'une categorie dans la base
if (isset($_POST["cate_supp"])) {
    $cateController->suppCateAdmin();
}
// Ajout d'une categorie dans la base
if (isset($_POST["ajouter_cate"])) {
    $cateController->addCateAdmin();
}
// Page contexte de l'admin
if (isset($_GET["action"]) && $_GET["action"] == "contadmin") {
  $contController->contAdmin();
}
// Suppression d'un contexte dans la base
if (isset($_POST["cont_supp"])) {
    $contController->suppContAdmin();
}
// Ajout d'un contexte dans la base
if (isset($_POST["ajouter_cont"])) {
    $contController->addContAdmin();
}


?>