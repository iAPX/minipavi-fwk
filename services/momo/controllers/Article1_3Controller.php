<?php

/**
 * Handle the article1-3 XML Page
 */

namespace service\controllers;

class Article1_3Controller extends \MiniPaviFwk\controllers\VideotexController
{


    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();

        $videotex->page('pages/9cdc90a41/article2');
        $videotex->position(3, 32);
        $videotex->ecritUniCode(" page 3/3");
        $videotex->position(4, 1);
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode("ce ne sont plus les particuliers qui    sont visées mais principalement les     entreprises, dont la plupart ne         comprennent pas qu'elles envoient à des acteurs extérieurs, souvent étrangers,  des informations vitales pour analyser  leurs fonctionnements internes et qui   peuvent être revendues ayant une grande valeur...                               ");


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
            \service\controllers\Article1_2Controller::class,
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
