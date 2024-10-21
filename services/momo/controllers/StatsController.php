<?php

/**
 * Handle the stats XML Page
 */

namespace service\controllers;

class StatsController extends \MiniPaviFwk\controllers\VideotexController
{


    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();

        $videotex->page('pages/9cdc90a41/stats');
        $videotex->position(3, 1);
        $videotex->ecritUniCode("visites : ");
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode(" 1577");
        $videotex->position(4, 1);
        $videotex->ecritUniCode("24h : ");
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode(" 75");
        $videotex->position(5, 1);
        $videotex->ecritUniCode("semaine : ");
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode(" 142");
        $videotex->position(6, 1);
        $videotex->ecritUniCode("mois : ");
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode(" 306");
        $videotex->position(7, 1);
        $videotex->ecritUniCode("articles : ");
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode(" 449");
        $videotex->position(8, 1);
        $videotex->ecritUniCode("articles finis : ");
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode(" 138");
        $videotex->position(9, 1);
        $videotex->ecritUniCode("pages lues : ");
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode(" 936");
        $videotex->position(10, 1);
        $videotex->ecritUniCode("session id : ");
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode(" 9cdc90a41");
        $videotex->page('pages.cache/stats-graph');


        return $videotex->getOutput();
    }



    public function validation(): \MiniPaviFwk\Validation
    {
        // Allow [sommaire] keys
        // Others could be added by VideotexController through introspection,
        // such as discovering touche*() or choix**() methods
        $validation = parent::validation();
        $validation->addValidKeys(['sommaire']);
        return $validation;
    }


    public function getCmd(): array
    {
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd($this->validation(), 24, 1, 1, false);
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
