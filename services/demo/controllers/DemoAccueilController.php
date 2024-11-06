<?php

/**
 * Accueil of Demo : homepage and [Suite]
 *
 * Minimalist controller.
 */

namespace service\controllers;

class DemoAccueilController extends \MiniPaviFwk\controllers\VideotexController
{
    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $videotex
        ->page("demo-controller")

        ->position(4, 1)->ecritUnicode("Accueil de la démo des contrôleurs.")

        ->position(23, 1)->effaceFinDeLigne()->couleurFond("vert")->couleurTexte('noir')
        ->ecritUnicode(" " . end(explode('\\', $this::class)))
        ->position(24, 34)->inversionDebut()->ecritUnicode(" SUITE ")->inversionFin();

        return $videotex->getOutput();
    }

    public function getCmd(): array
    {
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd(null, 24, 40, 1);
    }

    public function choixSuite(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, 'demo-sommaire');
    }
}
