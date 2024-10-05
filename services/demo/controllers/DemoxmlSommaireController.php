<?php

/**
 * Demonstrate Actions from a Videotex controller or a XML controller
 */

namespace service\controllers;

class DemoxmlSommaireController extends \MiniPaviFwk\controllers\XmlController
{
    public function ecran(): string
    {
        $vdt = parent::ecran();

        $videotex = new \MiniPaviFwk\videotex\Videotex();
        $vdt .= $videotex
        ->position(18, 1)->inversionDebut()->ecritUnicode('8')->inversionFin()
        ->ecritUnicode(" Service démo de chat")

        ->position(20, 1)->inversionDebut()->ecritUnicode('9')->inversionFin()
        ->ecritUnicode(" Service MacBidouille")
        ->getOutput();

        return $vdt;
    }

    public function choix8Envoi(): ?\MiniPaviFwk\actions\Action
    {
        // Switch to demo, displaying a message and waiting 3 seconds.
        return new \MiniPaviFwk\actions\SwitchServiceAction(
            'demochat',
            chr(12) . \MiniPavi\MiniPaviCli::toG2("*** REDIRECTION VERS LA DÉMO DE CHAT ***"),
            3
        );
    }

    public function choix9Envoi(): ?\MiniPaviFwk\actions\Action
    {
        // Switch to demo, displaying a message and waaiting 3 seconds.
        return new \MiniPaviFwk\actions\SwitchServiceAction(
            'macbidouille',
            chr(12) . \MiniPavi\MiniPaviCli::toG2("*** REDIRECTION VERS MACBIDOUILLE ***"),
            3
        );
    }
}
