<?php
include "Modules/contexte.php";
include "Models/contexteManager.php";

// Définition d'une classe permettant de controller les contextes en relation avec la base de données/
class ContexteController
{
    private $contexteManager; // instance du manager
    private $twig;

    /**
     * Constructeur = initialisation de la connexion vers le SGBD
     */
    public function __construct($db, $twig)
    {
        $this->contexteManager = new ContexteManager($db);
        $this->twig = $twig;
    }

    /**  page gestion contexte
     * @return void
     */
    public function contAdmin()
    {
        $conts = $this->contexteManager->getContexteAdmin();
        echo $this->twig->render('contadmin.html.twig', array('conts' => $conts, 'admin' => $_SESSION['admin'], 'photodeprofil' => $_SESSION['photodeprofil'], 'acces' => $_SESSION['acces']));
    }

    /**
     * Ajout dans la BD d'un contexte par un admin
     * @return void
     */
    public function addContAdmin()
    {
        $contexte = new Contexte($_POST);
        $okConts = $this->contexteManager->addContexte($contexte);
        $conts = $this->contexteManager->getContexteAdmin();

        $message = "Contexte ajouté avec succès";

        if (!$okConts) {
            $message .= "Problème lors de l'ajout de la contexte";
        }

        echo $this->twig->render('contadmin.html.twig', array('conts' => $conts, 'message' => $message, 'admin' => $_SESSION['admin'], 'photodeprofil' => $_SESSION['photodeprofil'], 'acces' => $_SESSION['acces']));
    }

    /**
     * Suppression dans la BD d'un contexte par un admin
     * @return void
     */
    public function suppContAdmin()
    {
        $contexte = new Contexte($_POST);
        $okConts = $this->contexteManager->deleteContexte($contexte);
        $conts = $this->contexteManager->getContexteAdmin();

        $message = "Contexte supprimé avec succès";

        if (!$okConts) {
            $message .= "Problème lors de la suppression du contexte";
        }

        echo $this->twig->render('contadmin.html.twig', array('conts' => $conts, 'message' => $message, 'admin' => $_SESSION['admin'], 'photodeprofil' => $_SESSION['photodeprofil'], 'acces' => $_SESSION['acces']));
    }

}

?>
