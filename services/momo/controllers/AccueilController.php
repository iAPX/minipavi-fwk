<?php

/**
 * Handle the accueil XML Page
 */

namespace service\controllers;

class AccueilController extends \MiniPaviFwk\controllers\VideotexController
{


    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();

        $videotex->page('pages/9cdc90a41/accueil');


        return $videotex->getOutput();
    }



    public function validation(): \MiniPaviFwk\Validation
    {
        // Allow [repetition], [sommaire], [guide] keys
        // Others could be added by VideotexController through introspection,
        // such as discovering touche*() or choix**() methods
        $validation = parent::validation();
        $validation->addValidKeys(['repetition', 'sommaire', 'guide']);
        return $validation;
    }


    public function getCmd(): array
    {
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd($this->validation(), 23, 40, 1, false);
    }


    public function choixRepetition(): ?\MiniPaviFwk\actions\Action
    {
        // Handle '' + [Repetition]
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\AccueilController::class,
            $this->context
        );
    }

    public function choixSommaire(): ?\MiniPaviFwk\actions\Action
    {
        // Handle '' + [Sommaire]
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\ArticlesController::class,
            $this->context
        );
    }

    public function choixGuide(): ?\MiniPaviFwk\actions\Action
    {
        // Handle '' + [Guide]
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\InfosController::class,
            $this->context
        );
    }
}
