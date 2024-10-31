<?php

/**
 * Accueil of Demo : homepage and [Suite]
 * 
 * Minimalist controller.
 */

namespace service\controllers;

class AccueilController extends \MiniPaviFwk\controllers\VideotexController
{
    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        return $videotex->page("demo-controller")->getOutput();
    }

    public function getCmd(): array
    {
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd($this->validation(), 24, 40, 1);
    }

    public function choixSuite(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\DemoSommaireController::class,
            $this->context
        );
    }
}
