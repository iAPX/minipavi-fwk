<?php

/**
 * Handle the article6-6 XML Page
 */

namespace service\controllers;

class Article6_6Controller extends \MiniPaviFwk\controllers\VideotexController
{


    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();

        $videotex->page('pages/9cdc90a41/article2');
        $videotex->position(3, 32);
        $videotex->ecritUniCode(" page 6/6");
        $videotex->position(4, 1);
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode("le ministère de la sécurité d'État      chinois pourrait être impliqué dans     cette attaque d'après certains rapports.                                        on ne sait quelles données ont été      capturées, ni si des écoutes ont été    effectuées ou lesquelles et les cibles  de celles-ci.                                                                   le gouvernement américain et les        entreprises concernées refusent de      communiquer là-dessus, indiquant sans   risque de se tromper que l'impact en estmassif.                                                                         toutes les backdoors se retournent      contre ceux y étant exposés. toujours...                                        moneygram a été piraté, 50 millions de  comptes ont fuité dont ssn et photos    ");


        return $videotex->getOutput();
    }



    public function validation(): \MiniPaviFwk\Validation
    {
        // Allow [sommaire], [retour], [suite] keys
        // Others could be added by VideotexController through introspection,
        // such as discovering touche*() or choix**() methods
        $validation = parent::validation();
        $validation->addValidKeys(['sommaire', 'retour', 'suite']);
        return $validation;
    }


    public function getCmd(): array
    {
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd($this->validation(), 3, 1, 1, false);
    }


    public function choixSommaire(): ?\MiniPaviFwk\actions\Action
    {
        // Handle '' + [Sommaire]
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\ArticlesController::class,
            $this->context
        );
    }

    public function choixRetour(): ?\MiniPaviFwk\actions\Action
    {
        // Handle '' + [Retour]
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\Article6_5Controller::class,
            $this->context
        );
    }

    public function choixSuite(): ?\MiniPaviFwk\actions\Action
    {
        // Handle '' + [Suite]
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\ArticlesController::class,
            $this->context
        );
    }
}
