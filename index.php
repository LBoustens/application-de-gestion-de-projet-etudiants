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
$projController = new ProjetController($bdd, $twig);
$utiController = new UtilisateurController($bdd, $twig);
$contController = new ContexteController($bdd, $twig);
$cateController = new CategorieController($bdd, $twig);
$sourcesController = new SourcesController($bdd, $twig);
$tagsController = new TagsController($bdd, $twig);
$appartientController = new AppartientController($bdd, $twig);
$associerController = new AssocierController($bdd, $twig);
$participerController = new ParticiperController($bdd, $twig);


// texte du message
$message = "";

// ============================== connexion / deconnexion - sessions ==================

// si la variable de session n'existe pas, on la crée
if (!isset($_SESSION['acces'])) {
  $_SESSION['acces'] = "non";
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

// ============================== gestion des itineraires ==================

// liste des itinéraires dans un tableau HTML
//  https://.../index/php?action=liste
if (isset($_GET["action"]) && $_GET["action"] == "projets") {
  $projController->listeProjets();
  // $contController->listeContexte();
}
// liste de mes itinéraires dans un tableau HTML
if (isset($_GET["action"]) && $_GET["action"] == "mesprojets") {
  $projController->listeMesProjets($_SESSION['idutilisateur']);
}

// formulaire ajout d'un itineraire : saisie des caractéristiques à ajouter dans la BD
//  https://.../index/php?action=ajout
// version 0 : l'itineraire est rattaché automatiquement à un membre déjà présent dans la BD
//              l'idmembre est en champ caché dans le formulaire
if (isset($_GET["action"]) && $_GET["action"] == "ajout") {
  $projController->formAjoutProjet();
}

// ajout de l'itineraire dans la base
// --> au clic sur le bouton "valider_ajout" du form précédent
if (isset($_POST["valider_ajout"])) {
  $projController->ajoutProjet();
}


// suppression d'un itineraire : choix de l'itineraire
//  https://.../index/php?action=suppr
if (isset($_GET["action"]) && $_GET["action"] == "suppr") {
  $projController->choixSuppProjet($_SESSION['idutilisateur']);
}

// supression d'un itineraire dans la base
// --> au clic sur le bouton "valider_supp" du form précédent
if (isset($_POST["valider_supp"])) {
  $projController->suppProjet();
}

// modification d'un itineraire : choix de l'itineraire
//  https://.../index/php?action=modif
if (isset($_GET["action"]) && $_GET["action"] == "modif") {
  $projController->choixModProjet($_SESSION['idutilisateur']);
}

// modification d'un itineraire : saisie des nouvelles valeurs
// --> au clic sur le bouton "saisie modif" du form précédent
//  ==> version 0 : pas modif de l'iditi ni de l'idmembre
if (isset($_POST["saisie_modif"])) {
  $projController->saisieModProjet();
}

//modification d'un itineraire : enregistrement dans la bd
// --> au clic sur le bouton "valider_modif" du form précédent
if (isset($_POST["valider_modif"])) {
  $projController->modProjet();
}

// ajout de l'utilisateur dans la base
// --> au clic sur le bouton "inscription" du form précédent
if (isset($_POST["inscription"])) {
  $utiController->ajoutUtilisateur();
}

// ajout du projet dans la base
// --> au clic sur le bouton "inscription" du form précédent
if (isset($_POST["ajouter_proj"])) {
  $projController->ajoutProjet();
}
// recherche d'itineraire : saisie des critres de recherche dans un formulaire
//  https://.../index/php?action=recherc
// if (isset($_GET["action"]) && $_GET["action"] == "recher") {
//   $itiController->formRechercheItineraire();
// }

//  recherche des itineraires : construction de la requete SQL en fonction des critères 
// de recherche et affichage du résultat dans un tableau HTML 
// --> au clic sur le bouton "valider_recher" du form précédent
// if (isset($_POST["valider_recher"])) {
//   $itiController->rechercheItineraire();
// }

if (isset($_GET["action"]) && $_GET["action"] == "details") {
  $details = $projController->detailsProjet();
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

// form éditer profil
if (isset($_GET["action"]) && $_GET["action"] == "profil") {
  $utiController->profil();
}

if (isset($_GET["action"]) && $_GET["action"] == "mentions") {
  $projController->mentions();
}

if (isset($_GET["action"]) && $_GET["action"] == "politique") {
  $projController->politique();
}

if (isset($_GET["action"]) && $_GET["action"] == "utiadmin") {
  $utiController->utiadmin();
}

if (isset($_GET["action"]) && $_GET["action"] == "cateadmin") {
  $utiController->cateadmin();
}

if (isset($_GET["action"]) && $_GET["action"] == "verifprojet") {
  $utiController->verifprojet();
}


?>