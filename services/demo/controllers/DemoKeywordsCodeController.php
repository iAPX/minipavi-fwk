<?php

/**
 * Demo for the Keywords handling, work the same on a VideotexController or XMLController
 */

namespace service\controllers;

class DemoKeywordsCodeController extends \MiniPaviFwk\controllers\VideotexController
{
    public function __construct($context, $params = [])
    {
        // This all you need to add Keyword handling to your VideotexController or XMLController
        // Keywords are handled through a MiniPaviFwk\keywords\Keywords derived instantiated class.
        parent::__construct($context, $params);
        $this->keywordHandler = new \service\keywords\DemoKeywords();
    }

    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $vdt = $videotex
        ->effaceLigne00()
        ->ecritVideotex(file_get_contents(SERVICE_DIR . "vdt/demo-controller-page.vdt"))
        ->ecritVideotex(file_get_contents(SERVICE_DIR . "vdt/demo-choix-sommaire.vdt"))

        ->position(3, 1)
        ->ecritUnicode("Démo d'un objet Keywords, tous les choixsont gérés par celui-ci et non ce       contrôleur.")

        ->position(7, 1)->ecritUnicode("* [SOMMAIRE] : Accueil")
        ->position(9, 1)->ecritUnicode("[SOMMAIRE] : Sommaire démo")

        ->position(23, 1)->effaceFinDeLigne()->couleurFond("vert")->couleurTexte('noir')
        ->ecritUnicode(" " . end(explode('\\', $this::class)))
        ->getOutput();
        return $vdt;
    }
}
