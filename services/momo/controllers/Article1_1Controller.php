<?php

/**
 * Handle the article1-1 XML Page
 */

namespace service\controllers;

class Article1_1Controller extends \MiniPaviFwk\controllers\VideotexController
{


    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();

        $videotex->page('pages/9cdc90a41/article');
        $videotex->position(3, 32);
        $videotex->ecritUniCode(" page 1/3");
        $videotex->position(4, 1);
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode("quand on parle de traceurs, on pense    immanquablement au web et à google et   consorts.                                                                       et il est certain qu'ils ne se gênent   pas, google allant jusqu'à limiter les  fonctionnalités de chrome pour gêner lesbloqueurs de pubs et les bloqueurs de   traceurs, profitant ainsi de sa positionhégémonique, tant coté navigateur que   coté publicité. je vais y revenir.                                              mais savez-vous que nos logiciels sur   mac, iphone, pc, android, etc. intègrenteux-aussi des traceurs?                                                         généralement c'est indiqué, et mentionnépour avoir des retours d'information en cas d'erreur logicielle, sauf que...    ");


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
            \service\controllers\Resume1Controller::class,
            $this->context
        );
    }

    public function choixSuite(): ?\MiniPaviFwk\actions\Action
    {
        // Handle '' + [Suite]
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\Article1_2Controller::class,
            $this->context
        );
    }
}
