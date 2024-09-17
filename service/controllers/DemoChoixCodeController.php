<?php

/**
 * Exemple utilisant les Touche*() et Choix() ainsi que la page affichée par Videotex
 */

namespace service\controllers;

class DemoChoixCodeController extends \MiniPaviFwk\controllers\VideotexController
{
    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\videotex\Videotex();
        $vdt = $videotex
        ->effaceLigne00()
        ->ecritVideotex(file_get_contents("service/vdt/demo-controller-page.vdt"))
        ->ecritVideotex(file_get_contents("service/vdt/demo-choix-code.vdt"))

        ->position(4, 1)->ecritUnicode("[RETOUR] : XML+Code / toucheRetour()")
        ->position(5, 1)->ecritUnicode("[SOMMAIRE] : Sommaire / toucheSommaire()")
        ->position(6, 1)->ecritUnicode("[REPETITION] : répét./VideotexController")

        ->position(9, 1)->ecritUnicode("1 + [ENVOI] : XML / choix1Envoi()")
        ->position(10, 1)->ecritUnicode("2 + [ENVOI] : XML+Code / toucheEnvoi()")
        ->position(11, 1)->ecritUnicode("3 + [ENVOI] : Sommaire / choix()")

        ->position(23, 1)->effaceFinDeLigne()->couleurFond("vert")->couleurTexte('noir')->ecritUnicode(" " . end(explode('\\', $this::class)))
        ->getOutput();
        return $vdt;
    }

    public function choix1Envoi(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, "demoxml-choix", "demo");
    }

    public function toucheEnvoi(string $saisie) : ?\MiniPaviFwk\actions\Action
    {
        if ($saisie=='2') {
            return new \MiniPaviFwk\actions\PageAction($this->context, "demoxml-choix-code", "demo");
        }
        return null;
    }

    public function toucheRetour(string $saisie): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, "demoxml-choix-code", "demo");
    }

    public function toucheSommaire(string $saisie): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, "demoxml-sommaire", "demo");
    }

    public function choix(string $touche, string $saisie): ?\MiniPaviFwk\actions\Action
    {
        if ($touche == 'ENVOI' and $saisie == "3") {
            return new \MiniPaviFwk\actions\PageAction($this->context, "demoxml-sommaire", "demo");
        }
        return null;
    }
}
