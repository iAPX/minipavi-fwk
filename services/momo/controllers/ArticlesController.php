<?php

/**
 * Handle the articles XML Page
 */

namespace service\controllers;

class ArticlesController extends \MiniPaviFwk\controllers\VideotexController
{


    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();

        $videotex->page('pages/9cdc90a41/articles');
        $videotex->page('pages/9cdc90a41/logo-drcs');
        $videotex->position(4, 1);
        $videotex->inversionDebut();
        $videotex->couleurTexte("jaune");
        $videotex->ecritUniCode(" 1 ");
        $videotex->inversionFin();
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode(" samedi sécurité : les traceurs dans     les apps de vos mac et iphone");
        $videotex->position(7, 1);
        $videotex->inversionDebut();
        $videotex->couleurTexte("jaune");
        $videotex->ecritUniCode(" 2 ");
        $videotex->inversionFin();
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode(" [màj] ok, l'ipad mini a été mis à       jour");
        $videotex->position(10, 1);
        $videotex->inversionDebut();
        $videotex->couleurTexte("jaune");
        $videotex->ecritUniCode(" 3 ");
        $videotex->inversionFin();
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode(" intel et amd créent le x86 ecosystem    advisory group");
        $videotex->position(13, 1);
        $videotex->inversionDebut();
        $videotex->couleurTexte("jaune");
        $videotex->ecritUniCode(" 4 ");
        $videotex->inversionFin();
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode(" [sponsorisé] pourquoi utiliser          surfshark - le vpn de première          qualité à tout petit prix");
        $videotex->position(16, 1);
        $videotex->inversionDebut();
        $videotex->couleurTexte("jaune");
        $videotex->ecritUniCode(" 5 ");
        $videotex->inversionFin();
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode(" samedi sécurité : black mirror,         billet d'humeur");
        $videotex->position(19, 1);
        $videotex->inversionDebut();
        $videotex->couleurTexte("jaune");
        $videotex->ecritUniCode(" 6 ");
        $videotex->inversionFin();
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode(" samedi sécurité : black mirror(ing)");


        return $videotex->getOutput();
    }



    public function validation(): \MiniPaviFwk\Validation
    {
        // Allow [repetition], [guide], [envoi] keys
        // Others could be added by VideotexController through introspection,
        // such as discovering touche*() or choix**() methods
        $validation = parent::validation();
        $validation->addValidKeys(['repetition', 'guide', 'envoi']);
        return $validation;
    }


    public function getCmd(): array
    {
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd($this->validation(), 24, 30, 1, true);
    }


    public function choixRepetition(): ?\MiniPaviFwk\actions\Action
    {
        // Handle '' + [Repetition]
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\ArticlesController::class,
            $this->context
        );
    }

    public function choixGuide(): ?\MiniPaviFwk\actions\Action
    {
        // Handle '' + [Guide]
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\InfosController::class,
            $this->context
        );
    }

    public function choix0Envoi(): ?\MiniPaviFwk\actions\Action
    {
        // Handle '0' + [Envoi]
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\StatsController::class,
            $this->context
        );
    }

    public function choix1Envoi(): ?\MiniPaviFwk\actions\Action
    {
        // Handle '1' + [Envoi]
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\Resume1Controller::class,
            $this->context
        );
    }

    public function choix2Envoi(): ?\MiniPaviFwk\actions\Action
    {
        // Handle '2' + [Envoi]
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\Resume2Controller::class,
            $this->context
        );
    }

    public function choix3Envoi(): ?\MiniPaviFwk\actions\Action
    {
        // Handle '3' + [Envoi]
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\Resume3Controller::class,
            $this->context
        );
    }

    public function choix4Envoi(): ?\MiniPaviFwk\actions\Action
    {
        // Handle '4' + [Envoi]
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\Resume4Controller::class,
            $this->context
        );
    }

    public function choix5Envoi(): ?\MiniPaviFwk\actions\Action
    {
        // Handle '5' + [Envoi]
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\Resume5Controller::class,
            $this->context
        );
    }

    public function choix6Envoi(): ?\MiniPaviFwk\actions\Action
    {
        // Handle '6' + [Envoi]
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\Resume6Controller::class,
            $this->context
        );
    }
}
