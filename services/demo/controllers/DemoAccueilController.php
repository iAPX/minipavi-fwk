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
        ->position(4, 1)->ecritUnicode("Accueil de la démo des contrôleurs.");

        // Mire de gris (ou couleurs)
        $videotex->position(9, 9)->doubleTaille()->ecritUnicode("Mire de gris");

        $luminances = [0, 4, 1 , 5, 2, 6, 3, 7];
        for ($ligne = 11; $ligne < 16; $ligne++) {
            $videotex->position($ligne, 1)->inversionDebut();
            for ($couleur = 0; $couleur < 8; $couleur++) {
                $videotex->ecritVideotex("\x1B" . chr(64 + $luminances[$couleur]) . "     ");
            }
        }

        $videotex
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
