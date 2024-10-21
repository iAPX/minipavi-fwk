<?php

/**
 * Handle the article4-3 XML Page
 */

namespace service\controllers;

class Article4_3Controller extends \MiniPaviFwk\controllers\VideotexController
{


    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();

        $videotex->page('pages/9cdc90a41/article');
        $videotex->position(3, 32);
        $videotex->ecritUniCode(" page 3/5");
        $videotex->position(4, 1);
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode("au-delà de la confidentialité, surfsharkpermet aussi de débloquer 15+           bibliothèques de contenu sur netflix à  travers le monde, ainsi que de          nombreuses chaînes en direct, il est    parfait pour les amateurs de cinéma qui ont déjà fait le tour des catalogues de service streaming français. vous avez legoût de regarder le seigneur des        anneaux? et hop, une petite connexion auserveur polonais de surfshark, ouverte  de netflix, et c'est parti !                                                    surfshark est maintenant en offre       promotionnelle, vous permettant d'      économiser 81% sur un forfait de 2 ans. un seul abonnement peut être utilisé surun nombre illimité d'appareils sans     contrôle de débit - mac, iphone, et     apple tv inclus.                        ");


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
            \service\controllers\Article4_2Controller::class,
            $this->context
        );
    }

    public function choixSuite(): ?\MiniPaviFwk\actions\Action
    {
        // Handle '' + [Suite]
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\Article4_4Controller::class,
            $this->context
        );
    }
}
