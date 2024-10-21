<?php

/**
 * Handle the article2-3 XML Page
 */

namespace service\controllers;

class Article2_3Controller extends \MiniPaviFwk\controllers\VideotexController
{


    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();

        $videotex->page('pages/9cdc90a41/article2');
        $videotex->position(3, 32);
        $videotex->ecritUniCode(" page 3/3");
        $videotex->position(4, 1);
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode("a ce niveau apple a certainement raté leplus gros coche de son histoire post    steve jobs. elle a été totalement prise par surprise par la bulle ia. nous      parlons bien de bulle car cela nous faitpenser à ce que l'on a connu avec       l'arrivée d'internet dans les années    2000.                                                                           bref, aujourd'hui apple tente de        rassurer les marchés avec des promesses d'un énorme potentiel futur. il en va demême avec les clients à qui l'on vend   des produits qui seront un jour         compatibles avec des super              fonctionnalités encore en développement dans le meilleur des cas.                                                       pour finir, le prix de l'entrée de gammeest à 609 euros.                        ");


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
            \service\controllers\Article2_2Controller::class,
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
