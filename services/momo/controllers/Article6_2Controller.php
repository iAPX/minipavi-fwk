<?php

/**
 * Handle the article6-2 XML Page
 */

namespace service\controllers;

class Article6_2Controller extends \MiniPaviFwk\controllers\VideotexController
{


    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();

        $videotex->page('pages/9cdc90a41/article');
        $videotex->position(3, 32);
        $videotex->ecritUniCode(" page 2/6");
        $videotex->position(4, 1);
        $videotex->couleurTexte("cyan");
        $videotex->ecritUniCode("je continue à la recommander chaudement:bons scenarii, bons metteurs en scène,  bons acteurs et de la subtilité. la     majorité des épisodes mérite d'être vue au moins deux fois.                                                             un petit florilège sur la vie privée ce samedi...                                                                       iphone \"black mirroring\" sur mac                                                lorsqu'on utilise la fonction iphone    mirroring arrivée avec macos 15 sequoia et ios 18 \"ia plus tard\", le mac stocke des métadonnées sur les applications    installées sur l'iphone.                ");


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
            \service\controllers\Article6_1Controller::class,
            $this->context
        );
    }

    public function choixSuite(): ?\MiniPaviFwk\actions\Action
    {
        // Handle '' + [Suite]
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\Article6_3Controller::class,
            $this->context
        );
    }
}
