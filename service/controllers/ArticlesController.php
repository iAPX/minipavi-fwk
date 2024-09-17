<?php

/**
 * Adds a jump to the demo.xml file from the MacBidouille.xml demo,
 * And display the optionn on-screen.
 */

namespace service\controllers;

class ArticlesController extends \MiniPaviFwk\controllers\XmlController
{
    public function ecran(): string
    {
        $vdt = parent::ecran();
        $videotex = new \MiniPaviFwk\videotex\Videotex();
        $vdt .= $videotex
        ->position(22, 1)
        ->inversionDebut()
        ->ecritUnicode(" * SUITE ")
        ->inversionFin()
        ->couleurTexte("jaune")
        ->ecritUnicode(" Aller sur la démo XML")
        ->getOutput();
        return $vdt;
    }

    public function choixETOILESuite(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, "demoxml-sommaire", "demo");
    }
}
