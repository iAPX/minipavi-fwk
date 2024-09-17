<?php

/**
 * Adds a jump to the demo.xml file from the MacBidouille.xml demo
 */

namespace service\controllers;

class ArticlesController extends \MiniPaviFwk\controllers\XmlController
{
    public function __construct(array $context, array $params = [])
    {
        parent::__construct($context, $params);

        // Now keywords are handled for this controller!
        $this->keywordHandler = new \service\keywords\Keywords();
    }

    public function ecran(): string
    {
        // Demo: overload ecran(), adding to it or replacing it, works as well for XmlController and VideotexController
        $vdt = parent::ecran();

        // This is an example of easy Vidéotex output on controllers
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
        // Easy behaviour coding!
        // Go back to another Xml, demo.xml on page "accueil"
        return new \MiniPaviFwk\actions\PageAction($this->context, "demoxml-sommaire", "demo");
    }
}
