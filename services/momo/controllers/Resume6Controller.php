<?php

/**
 * Handle the resume6 XML Page
 */

namespace service\controllers;

class Resume6Controller extends \MiniPaviFwk\controllers\VideotexController
{


    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();

        $videotex->page('pages/9cdc90a41/resume');
        $videotex->position(3, 1);
        $videotex->ecritUniCode("samedi sécurité : black mirror(ing)");
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
        $videotex->ecritUniCode("l'article met en garde contre des       risques de sécurité liés à la fonction  \"iphone mirroring\" sur macos 15 et ios  18, qui stocke des métadonnées          d'applications pouvant être exploitées  par des logiciels de sécurité en        entreprise. de plus, il rapporte que la chine aurait piraté des backdoors de    surveillance téléphonique aux usa, et   que moneygram a subi une fuite de       données affectant 50 millions de        comptes, y compris des numéros de       sécurité sociale et des photos          d'identité. enfin, il souligne que les");


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
            \service\controllers\Article6_1Controller::class,
            $this->context
        );
    }
}
