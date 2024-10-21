<?php

/**
 * Handle the article4-4 XML Page
 */

namespace service\controllers;

class Article4_4Controller extends \MiniPaviFwk\controllers\VideotexController
{


    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();

        $videotex->page('pages/9cdc90a41/article');
        $videotex->position(3, 32);
        $videotex->ecritUniCode(" page 4/5");
        $videotex->position(4, 1);
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode("offre de 24 mois :                                                              starter - 2,09 eur + 4 mois             supplémentaires                                                                 one - 2,59 eur + 4 mois supplémentaires                                         one+ - 4,09 eur + 4 mois supplémentaires                                        offre de 12 mois :                                                              starter - 2,59 eur + 4 mois             supplémentaires                                                                 one - 2,89 eur + 4 mois supplémentaires                                         one+ - 5,69 eur + 4 mois supplémentaires");


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
            \service\controllers\Article4_3Controller::class,
            $this->context
        );
    }

    public function choixSuite(): ?\MiniPaviFwk\actions\Action
    {
        // Handle '' + [Suite]
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\Article4_5Controller::class,
            $this->context
        );
    }
}
