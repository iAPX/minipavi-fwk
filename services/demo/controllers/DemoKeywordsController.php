<?php

/**
 * Demo for the Keywords handling, work the same on a VideotexController
 */

namespace service\controllers;

class DemoKeywordsController extends \MiniPaviFwk\controllers\VideotexController
{
    public function __construct($context, $params = [])
    {
        // This all you need to add Keyword handling to your VideotexController
        // Keywords are handled through a MiniPaviFwk\keywords\Keywords derived instantiated class.
        parent::__construct($context, $params);
        $this->keywordHandler = new \service\keywords\DemoKeywords();
    }

    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $vdt = $videotex
        ->effaceLigne00()
        ->page("demo-controller")

        ->position(3, 1)
        ->ecritUnicode("Démo d'un objet Keywords, tous les choixsont gérés par celui-ci et non ce       contrôleur.")

        ->position(7, 1)->ecritUnicode("* [SOMMAIRE] : Accueil")
        ->position(9, 1)->ecritUnicode("[SOMMAIRE] : Sommaire démo")
        ->position(11, 1)->ecritUnicode("[REPETITION] : Automatique géré par     VideotexController, surchargeable")

        ->position(23, 1)->effaceFinDeLigne()->couleurFond("vert")->couleurTexte('noir')
        ->ecritUnicode(" " . end(explode('\\', $this::class)))

        ->position(24, 25)->ecritUnicode("Choix : .. ")
        ->inversionDebut()->ecritUnicode("ENVOI")->inversionFin()

        ->getOutput();
        return $vdt;
    }

    public function getCmd(): array
    {
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd($this->validation(), 24, 33, 2, true, '.');
    }
}
