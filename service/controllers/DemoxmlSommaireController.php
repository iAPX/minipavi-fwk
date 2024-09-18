<?php

/**
 * Demo Sommaire (main menu) using XmlController to add the * [SUITE] option and display it on screen
 */

namespace service\controllers;

class DemoxmlSommaireController extends \MiniPaviFwk\controllers\XmlController
{
    public function ecran(): string
    {
        // Display the option * [SUITE] on screen
        $vdt = parent::ecran();

        $videotex = new \MiniPaviFwk\videotex\Videotex();
        $vdt .= $videotex
        ->position(20, 2)
        ->inversionDebut()
        ->ecritUnicode(" * SUITE ")
        ->inversionFin()
        ->couleurTexte("jaune")
        ->ecritUnicode(" Démo MacBidouille en pur XML")

        // Name of the Controller
        ->position(23, 1)->effaceFinDeLigne()->couleurFond("vert")->couleurTexte('noir')
        ->ecritUnicode(" " . end(explode('\\', $this::class)))

        ->getOutput();
        return $vdt;
    }

    public function choixETOILESuite(): ?\MiniPaviFwk\actions\Action
    {
        // Handle * [SUITE], send to the MACBIDOUILLE XML service
        // [SUITE] is added to the validated fn keys by introspection
        return new \MiniPaviFwk\actions\PageAction($this->context, "", "macbidouille");
    }
}
