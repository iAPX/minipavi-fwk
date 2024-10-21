<?php

/**
 * Handle the article3-3 XML Page
 */

namespace service\controllers;

class Article3_3Controller extends \MiniPaviFwk\controllers\VideotexController
{


    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();

        $videotex->page('pages/9cdc90a41/article2');
        $videotex->position(3, 32);
        $videotex->ecritUniCode(" page 3/3");
        $videotex->position(4, 1);
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode("l'autre problème est lié à l'explosion  de l'architecture arm qui prend toujoursplus de place et de parts de marché.    apple avait ouvert le bal avec ses pucesax mais surtout mx, les autres suivent, avec du retard certes mais suivent la   tendance.                                                                       l'association de ces deux géants du x86 pourrait stabiliser les choses dans le  meilleur des cas et dans le pire freinerla perte de vitesse de cette            architecture complexe à moins qu'ils    n'arrivent à la révolutionner.          ");


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
            \service\controllers\Article3_2Controller::class,
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
