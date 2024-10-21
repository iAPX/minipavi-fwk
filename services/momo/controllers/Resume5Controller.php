<?php

/**
 * Handle the resume5 XML Page
 */

namespace service\controllers;

class Resume5Controller extends \MiniPaviFwk\controllers\VideotexController
{


    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();

        $videotex->page('pages/9cdc90a41/resume');
        $videotex->position(3, 1);
        $videotex->ecritUniCode("samedi sécurité : black mirror, billet  d'humeur");
        $videotex->position(6, 1);
        $videotex->ecritUniCode("par ");
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode("philippe");
        $videotex->position(6, 26);
        $videotex->ecritUniCode("12 octobre 2024");
        $videotex->position(7, 1);
        $videotex->inversionDebut();
        $videotex->ecritUniCode(" résumé par mistral ai (mistral-large)  ");
        $videotex->inversionFin();
        $videotex->position(8, 1);
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode("l'article \"samedi sécurité : black      mirror\" déplore l'érosion quotidienne dela vie privée et de l'intimité, imputée à la technologie, aux entreprises, aux  gouvernements et à nos propres actions. la disparition de la vie privée semble  inévitable, nécessitant une redéfinitionradicale. que pensez-vous et que        faites-vous pour vous protéger ?");


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
            \service\controllers\Article5_1Controller::class,
            $this->context
        );
    }
}
