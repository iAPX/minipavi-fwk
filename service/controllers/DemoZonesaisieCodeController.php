<?php

/**
 * Demo the Zonesaisie handling for a VideotexController
 */

namespace service\controllers;

class DemoZonesaisieCodeController extends \MiniPaviFwk\controllers\VideotexController
{
    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\videotex\Videotex();
        $vdt = $videotex
        ->ecritVideotex(file_get_contents("service/vdt/demo-controller-page.vdt"))
        ->ecritVideotex(file_get_contents("service/vdt/demo-choix-code.vdt"))
        ->position(8, 1)->ecritUnicode("Saisie forcée ici par zonesaisie() : .")

        ->position(23, 1)->effaceFinDeLigne()->couleurFond("vert")->couleurTexte('noir')->ecritUnicode(" " . end(explode('\\', $this::class)))

        ->getOutput();
        return $vdt;
    }

    public function zonesaisie(): \MiniPaviFwk\ZoneSaisie
    {
        return new \MiniPaviFwk\ZoneSaisie(8, 38, 1, true);
    }

    public function toucheSommaire(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, "demoxml-sommaire", "demo");
    }

    public function toucheRetour(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, "demoxml-zonesaisie-code", "demo");
    }

}
