<?php

/**
 * Handle the resume3 XML Page
 */

namespace service\controllers;

class Resume3Controller extends \MiniPaviFwk\controllers\VideotexController
{


    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();

        $videotex->page('pages/9cdc90a41/resume');
        $videotex->position(3, 1);
        $videotex->ecritUniCode("intel et amd créent le x86 ecosystem    advisory group");
        $videotex->position(6, 1);
        $videotex->ecritUniCode("par ");
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode("lionel");
        $videotex->position(6, 26);
        $videotex->ecritUniCode("17 octobre 2024");
        $videotex->position(7, 1);
        $videotex->inversionDebut();
        $videotex->ecritUniCode(" résumé par mistral ai (mistral-large)  ");
        $videotex->inversionFin();
        $videotex->position(8, 1);
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode("intel et amd créent le x86 ecosystem    advisory group pour collaborer sur      l'évolution de l'architecture x86 et    faciliter le développement logiciel.    d'autres entreprises comme broadcom,    dell et google cloud participent à cetteinitiative. cette collaboration vise à  stabiliser et éventuellement            révolutionner l'architecture x86 face à la montée de l'arm.");


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
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd($this->validation(), 23, 40, 1, false);
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
            \service\controllers\ArticlesController::class,
            $this->context
        );
    }

    public function choixSuite(): ?\MiniPaviFwk\actions\Action
    {
        // Handle '' + [Suite]
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\Article3_1Controller::class,
            $this->context
        );
    }
}
