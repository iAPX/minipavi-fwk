<?php

/**
 * Demo the ZoneSaisie through getCmd handling for a VideotexController
 */

namespace service\controllers;

class DemoZoneSaisieController extends \MiniPaviFwk\controllers\VideotexController
{
    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $vdt = $videotex
        ->effaceLigne00()
        ->page("demo-controller")
        ->page("demo-choix-code")

        ->position(8, 1)->ecritUnicode("Saisie forcée ici par zonesaisie() :")

        ->position(23, 1)->effaceFinDeLigne()->couleurFond("vert")->couleurTexte('noir')
        ->ecritUnicode(" " . end(explode('\\', $this::class)))

        ->getOutput();
        return $vdt;
    }

    public function getCmd(): array
    {
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd(null, 8, 38, 2, true, '.');
    }

    public function choixSommaire(): ?\MiniPaviFwk\actions\Action
    {
        // Handle [SOMMAIRE] to return to the Sommaire (service menu)
        return new \MiniPaviFwk\actions\PageAction($this->context, 'demo-sommaire');
    }

    public function choixRetour(): ?\MiniPaviFwk\actions\Action
    {
        // Handle [RETOUR] to return to the Sommaire (service menu)
        return new \MiniPaviFwk\actions\PageAction($this->context, 'demo-sommaire');
    }
}
