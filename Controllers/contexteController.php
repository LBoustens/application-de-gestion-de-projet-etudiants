<?php
include "Modules/contexte.php";
include "Models/contexteManager.php";

class ContexteController {

    private $contexteManager; // instance du manager
    private $twig;

    /**
     * Constructeur = initialisation de la connexion vers le SGBD
     */
    public function __construct($db, $twig) {
        $this->contexteManager = new ContexteManager($db);
        $this->twig = $twig;
    }

public function listeContexte() {
    $contextes = $this->contexteManager->getContexte();
    echo $this->twig->render('projets_liste.html.twig',array('contextes'=>$contextes,'acces'=> $_SESSION['acces']));
}

}


