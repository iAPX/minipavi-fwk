<?php

/**
 * Handle the infos XML Page
 */

namespace service\controllers;

class InfosController extends \MiniPaviFwk\controllers\VideotexController
{


    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();

        $videotex->page('pages/9cdc90a41/infos');


        return $videotex->getOutput();
    }



    public function validation(): \MiniPaviFwk\Validation
    {
        // Allow [repetition], [sommaire] keys
        // Others could be added by VideotexController through introspection,
        // such as discovering touche*() or choix**() methods
        $validation = parent::validation();
        $validation->addValidKeys(['repetition', 'sommaire']);
        return $validation;
    }


    public function getCmd(): array
    {
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd($this->validation(), 24, 1, 1, false);
    }


    public function choixRepetition(): ?\MiniPaviFwk\actions\Action
    {
        // Handle '' + [Repetition]
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\InfosController::class,
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
}
