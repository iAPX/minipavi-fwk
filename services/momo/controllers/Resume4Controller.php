<?php

/**
 * Handle the resume4 XML Page
 */

namespace service\controllers;

class Resume4Controller extends \MiniPaviFwk\controllers\VideotexController
{


    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();

        $videotex->page('pages/9cdc90a41/resume');
        $videotex->position(3, 1);
        $videotex->ecritUniCode("[sponsorisé] pourquoi utiliser surfshark- le vpn de première qualité à tout     petit prix");
        $videotex->position(6, 1);
        $videotex->ecritUniCode("par ");
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode("lionel");
        $videotex->position(6, 26);
        $videotex->ecritUniCode("14 octobre 2024");
        $videotex->position(7, 1);
        $videotex->inversionDebut();
        $videotex->ecritUniCode(" résumé par mistral ai (mistral-large)  ");
        $videotex->inversionFin();
        $videotex->position(8, 1);
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode("surfshark, un vpn de qualité, offre     anonymat et sécurité en ligne avec des  fonctionnalités avancées comme surfsharkalert et search. la version 3.2 pour    macos est disponible, avec des          améliorations significatives. promotionsen cours : économisez jusqu'à 81% sur unforfait de 2 ans, utilisable sur un     nombre illimité d'appareils.");


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
            \service\controllers\Article4_1Controller::class,
            $this->context
        );
    }
}
