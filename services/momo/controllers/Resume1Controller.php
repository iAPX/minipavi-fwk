<?php

/**
 * Handle the resume1 XML Page
 */

namespace service\controllers;

class Resume1Controller extends \MiniPaviFwk\controllers\VideotexController
{


    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();

        $videotex->page('pages/9cdc90a41/resume');
        $videotex->position(3, 1);
        $videotex->ecritUniCode("samedi sécurité : les traceurs dans les apps de vos mac et iphone");
        $videotex->position(6, 1);
        $videotex->ecritUniCode("par ");
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode("philippe");
        $videotex->position(6, 26);
        $videotex->ecritUniCode("19 octobre 2024");
        $videotex->position(7, 1);
        $videotex->inversionDebut();
        $videotex->ecritUniCode(" résumé par mistral ai (mistral-large)  ");
        $videotex->inversionFin();
        $videotex->position(8, 1);
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode("l'article révèle que les applications   sur mac, iphone, pc et android          contiennent des traceurs, envoyant      régulièrement des informations à des    tiers comme sentry.io. ces données      incluent l'utilisation des logiciels,   les tâches effectuées et les adresses   ip, permettant de suivre les activités  des entreprises, souvent à leur insu, etde revendre ces informations.");


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
            \service\controllers\Article1_1Controller::class,
            $this->context
        );
    }
}
