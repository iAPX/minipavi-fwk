<?php

/**
 * Demonstrate usage of touche*(), choix**() and choix() to handle user's input
 */

namespace service\controllers;

class DemoChoixController extends \MiniPaviFwk\controllers\VideotexController
{
    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $vdt = $videotex
        ->effaceLigne00()
        ->ecritVideotex(file_get_contents(SERVICE_DIR . "vdt/demo-controller-page.vdt"))
        ->ecritVideotex(file_get_contents(SERVICE_DIR . "/vdt/demo-choix-code.vdt"))

        ->position(5, 1)->ecritUnicode("[SOMMAIRE] : Sommaire / toucheSommaire()")
        ->position(6, 1)->ecritUnicode("[REPETITION] : répét./VideotexController")

        ->position(9, 1)->ecritUnicode("1 + [ENVOI] : Sommaire / choix1Envoi()")
        ->position(10, 1)->ecritUnicode("2 + [ENVOI] : Sommaire / toucheEnvoi()")
        ->position(11, 1)->ecritUnicode("3 + [ENVOI] : Sommaire / choix()")

        ->position(23, 1)->effaceFinDeLigne()->couleurFond("vert")->couleurTexte('noir')
        ->ecritUnicode(" " . end(explode('\\', $this::class)))
        ->getOutput();
        return $vdt;
    }

    public function choix1Envoi(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\DemoSommaireController::class,
            $this->context
        );
    }

    public function toucheEnvoi(string $saisie): ?\MiniPaviFwk\actions\Action
    {
        if ($saisie == '2') {
            return new \MiniPaviFwk\actions\ControllerAction(
                \service\controllers\DemoSommaireController::class,
                $this->context
            );
        }
        return null;
    }

    public function toucheSommaire(string $saisie): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\DemoSommaireController::class,
            $this->context
        );
    }

    public function choix(string $touche, string $saisie): ?\MiniPaviFwk\actions\Action
    {
        if ($touche == 'ENVOI' and $saisie == "3") {
            return new \MiniPaviFwk\actions\ControllerAction(
                \service\controllers\DemoSommaireController::class,
                $this->context
            );
        }
        return null;
    }
}
