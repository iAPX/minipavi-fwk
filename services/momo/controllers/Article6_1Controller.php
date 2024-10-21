<?php

/**
 * Handle the article6-1 XML Page
 */

namespace service\controllers;

class Article6_1Controller extends \MiniPaviFwk\controllers\VideotexController
{


    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();

        $videotex->page('pages/9cdc90a41/article');
        $videotex->position(3, 32);
        $videotex->ecritUniCode(" page 1/6");
        $videotex->position(4, 1);
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode("la série black mirror était basée sur   l'idée de montrer de quelles façons     notre vie privée et nos informations    personnelles pouvaient être affectées ouexposées via des changements            technologiques, mais aussi par les      changements comportementaux qu'elles    créent ou permettent.                                                           las, elle est terminée, et les épisodes existants commencent à vieillir, la     réalité vécue rattrapant leurs fictions dystopiques, comme narré ici. je ne     pense pas qu'il faille s'en réjouir.    ");


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
            \service\controllers\Resume6Controller::class,
            $this->context
        );
    }

    public function choixSuite(): ?\MiniPaviFwk\actions\Action
    {
        // Handle '' + [Suite]
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\Article6_2Controller::class,
            $this->context
        );
    }
}
