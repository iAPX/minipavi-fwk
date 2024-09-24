<?php

/**
 * Demonstrate Actions from a Videotex controller or a XML controller
 */

namespace service\controllers;

class ArticlesController extends \MiniPaviFwk\controllers\XmlController
{
    public function ecran(): string
    {
        $vdt = parent::ecran();

        $videotex = new \MiniPaviFwk\videotex\Videotex();
        $vdt .= $videotex
        ->position(22, 1)->inversionDebut()->ecritUnicode('9')->inversionFin()
        ->ecritUnicode(" change service/SwitchServiceAction()")
        ->getOutput();

        return $vdt;
    }

    public function choix9Envoi(): ?\MiniPaviFwk\actions\Action
    {
        // Switch to demo, displaying a message and waaiting 3 seconds.
        return new \MiniPaviFwk\actions\SwitchServiceAction(
            'demo',
            "\x0c\x07*** REDIRECTION VERS LE SERVICE DE DEMO ***",
            3
        );
    }
}
