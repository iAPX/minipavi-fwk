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

        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $vdt .= $videotex
        ->position(18, 1)->inversionDebut()->ecritUnicode('8')->inversionFin()
        ->ecritUnicode(" Service démo de chat")

        ->position(19, 1)->inversionDebut()->ecritUnicode('9')->inversionFin()
        ->ecritUnicode(" Service MacBidouille")

        ->position(20, 1)->inversionDebut()->ecritUnicode('10')->inversionFin()
        ->ecritUnicode(
            // @TODO check it!
            file_exists("./services/myservice/xml/default.xml") ? " MY SERVICE !!!!" : " not available"
        )

        // Displays Minitel type and DRCS mode support
        ->position(21, 1)->ecritUnicode("Minitel type : " . \MiniPavi\MiniPaviCli::$versionMinitel)
        ->ecritUnicode(" DRCS : " . ($_SESSION['is_drcs'] ? "oui" : "non"))

        ->getOutput();
        return $vdt;
    }

    public function choix8Envoi(): ?\MiniPaviFwk\actions\Action
    {
        // Switch to demo, displaying a message and waiting 2 seconds.
        return new \MiniPaviFwk\actions\SwitchServiceAction(
            'demochat',
            chr(12) . \MiniPavi\MiniPaviCli::toG2("*** REDIRECTION VERS LA DÉMO DE CHAT ***"),
            2
        );
    }

    public function choix9Envoi(): ?\MiniPaviFwk\actions\Action
    {
        // Switch to demo, displaying a message and waiting 2 seconds.
        return new \MiniPaviFwk\actions\SwitchServiceAction(
            'macbidouille',
            chr(12) . \MiniPavi\MiniPaviCli::toG2("*** REDIRECTION VERS MACBIDOUILLE ***"),
            2
        );
    }

    public function choix10Envoi(): ?\MiniPaviFwk\actions\Action
    {
        // Switch to myservice
        return new \MiniPaviFwk\actions\SwitchServiceAction(
            'myservice',
            chr(12) . \MiniPavi\MiniPaviCli::toG2("*** REDIRECTION VERS 'myservice' ***"),
            2
        );
    }
}
