<?php

/**
 * Handle the article2-1 XML Page
 */

namespace service\controllers;

class Article2_1Controller extends \MiniPaviFwk\controllers\VideotexController
{


    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();

        $videotex->page('pages/9cdc90a41/article');
        $videotex->position(3, 32);
        $videotex->ecritUniCode(" page 1/3");
        $videotex->position(4, 1);
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode("[màj] la puce a17 pro perd dans cet ipadun de ses coeurs gpu. elle en a         seulement 5 et pas 6, certainement les  puces qui n'avaient pas passé toutes lescertifications lors de la production en masse des iphone de la génération       précédente.                                                                     sans tambour ni trompette apple a mis à jour son ipad mini.                                                             extérieurement rien ne change, ni le    touch-id toujours présent, ni la qualitéde la caméra ni celle de l'écran.       ");


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
            \service\controllers\Resume2Controller::class,
            $this->context
        );
    }

    public function choixSuite(): ?\MiniPaviFwk\actions\Action
    {
        // Handle '' + [Suite]
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\Article2_2Controller::class,
            $this->context
        );
    }
}
