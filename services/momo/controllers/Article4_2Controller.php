<?php

/**
 * Handle the article4-2 XML Page
 */

namespace service\controllers;

class Article4_2Controller extends \MiniPaviFwk\controllers\VideotexController
{


    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();

        $videotex->page('pages/9cdc90a41/article');
        $videotex->position(3, 32);
        $videotex->ecritUniCode(" page 2/5");
        $videotex->position(4, 1);
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode("surfshark est un vpn, réseau privé      virtuel, qui permet de se rendre        totalement anonyme et sécurisé en ligne.le logiciel offre de nombreuses         fonctionnalités pour facilement se      protéger en ligne, même pour les        néophytes. le produit est en fait encoreplus qu'un vpn, grâce à surfshark alert et search. si jamais votre numéro de    carte de crédit ou vos identifiants de  connexion se font pirater et diffusés   sur le dark web, surfshark alert vous eninformera en temps réel. search est un  moteur de recherche 100% sans pubs ni   trackers, pour garder votre vie privée  en ligne totalement anonyme.            ");


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
            \service\controllers\Article4_1Controller::class,
            $this->context
        );
    }

    public function choixSuite(): ?\MiniPaviFwk\actions\Action
    {
        // Handle '' + [Suite]
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\Article4_3Controller::class,
            $this->context
        );
    }
}
