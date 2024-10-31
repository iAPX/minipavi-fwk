<?php

/**
 * Demo the Zonesaisie through getCmd handling for a VideotexController
 */

namespace service\controllers;

class DemoZonesaisieController extends \MiniPaviFwk\controllers\VideotexController
{
    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $vdt = $videotex
        ->ecritVideotex(file_get_contents(SERVICE_DIR . "vdt/demo-controller-page.vdt"))
        ->ecritVideotex(file_get_contents(SERVICE_DIR . "vdt/demo-choix-code.vdt"))
        ->position(8, 1)->ecritUnicode("Saisie forcée ici par zonesaisie() : .")

        ->position(23, 1)->effaceFinDeLigne()->couleurFond("vert")->couleurTexte('noir')
        ->ecritUnicode(" " . end(explode('\\', $this::class)))

        ->getOutput();
        return $vdt;
    }

    public function getCmd(): array
    {
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd($this->validation(), 8, 38, 1, true);
    }

    public function choixSommaire(): ?\MiniPaviFwk\actions\Action
    {
        // Handle [SOMMAIRE] to return to the Sommaire (service menu)
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\DemoSommaireController::class,
            $this->context
        );
    }

    public function choixRetour(): ?\MiniPaviFwk\actions\Action
    {
        // Handle [SOMMAIRE] to return to the Sommaire (service menu)
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\DemoSommaireController::class,
            $this->context
        );
    }
}
