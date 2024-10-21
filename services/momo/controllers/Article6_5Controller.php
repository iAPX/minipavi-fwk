<?php

/**
 * Handle the article6-5 XML Page
 */

namespace service\controllers;

class Article6_5Controller extends \MiniPaviFwk\controllers\VideotexController
{


    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();

        $videotex->page('pages/9cdc90a41/article');
        $videotex->position(3, 32);
        $videotex->ecritUniCode(" page 5/6");
        $videotex->position(4, 1);
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode("aux usa il y a dans les différents      réseaux des backdoors voire des         dispositifs de surveillance de masse,   tout autant utilisables pour des écoutesou surveillances ciblées sous contrôle  d'un juge qu'en liaison avec la sécuriténationale ou pour la surveillance de    masse.                                                                          en france aussi d'ailleurs.                                                     pour les réseaux téléphoniques aux usa, cela date du programme calea qui a      démarré en 1994. trente ans, ça se fête!                                        des pirates chinois auraient réussi à   s'y introduire, notamment celles en     liaison avec les opérateurs verizon et  at&t.                                   ");


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
            \service\controllers\Article6_4Controller::class,
            $this->context
        );
    }

    public function choixSuite(): ?\MiniPaviFwk\actions\Action
    {
        // Handle '' + [Suite]
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\Article6_6Controller::class,
            $this->context
        );
    }
}
