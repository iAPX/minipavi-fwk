<?php

/**
 * Sommaire de la démo du service
 *
 * Exemple utilisant les Touche*() et Choix() ainsi que la page affichée par Videotex
 */

namespace service\controllers;

class DemoZonesaisieCodeController extends \MiniPaviFwk\controllers\VideotexController
{
    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\videotex\Videotex();

        // Redo the same demo as in demoxml-ecran page in demo.xml
        $vdt = $videotex

        // Simulate Videotex page loading as in <affiche><url>
        ->ecritVideotex(file_get_contents("service/vdt/demo-controller-page.vdt"))
        ->ecritVideotex(file_get_contents("service/vdt/demo-choix-code.vdt"))

        // Display the message for zonesaisie
        ->position(8, 1)->ecritUnicode("Saisie forcée ici par zonesaisie() : .")
        // Name of the Controller
        ->position(23, 1)->effaceFinDeLigne()->inversionDebut()->ecritUnicode("Controller: DemoZonesaisieCodeController")->inversionFin()

        ->getOutput();
        return $vdt;
    }

    public function zonesaisie(): \MiniPaviFwk\ZoneSaisie
    {
        // Overload 
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
