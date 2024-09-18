<?php

/**
 * Demo of the different ways to handle user's input
 */

namespace service\controllers;

class DemoxmlChoixCodeController extends \MiniPaviFwk\controllers\XmlController
{
    public function ecran(): string
    {
        $vdt = parent::ecran();

        $videotex = new \MiniPaviFwk\videotex\Videotex();
        $vdt .= $videotex
        ->position(23, 1)->effaceFinDeLigne()->couleurFond("vert")->couleurTexte('noir')
        ->ecritUnicode(" " . end(explode('\\', $this::class)))
        ->getOutput();
        return $vdt;
    }

    public function choix1Envoi(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, "demoxml-choix", "demo");
    }

    public function toucheEnvoi(string $saisie): ?\MiniPaviFwk\actions\Action
    {
        if ($saisie == '2') {
            return new \MiniPaviFwk\actions\PageAction($this->context, "demoxml-sommaire", "demo");
        }
        return null;
    }

    public function toucheSuite(string $saisie): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, "demo-choix-code", "demo");
    }

    public function toucheRetour(string $saisie): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, "demoxml-choix", "demo");
    }

    public function choix(string $touche, string $saisie): ?\MiniPaviFwk\actions\Action
    {
        if ($touche == 'ENVOI' and $saisie == "3") {
            return new \MiniPaviFwk\actions\PageAction($this->context, "demo-choix-code", "demo");
        }

        // Fallback to XML choices through XmlController
        return parent::choix($touche, $saisie);
    }
}
