<?php

/**
 * Demonstrate Actions from a Videotex controller or a XML controller
 */

namespace service\controllers;

class DemoActionCodeController extends \MiniPaviFwk\controllers\VideotexController
{
    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\videotex\Videotex();
        $vdt = $videotex
        ->effaceLigne00()
        ->ecritVideotex(file_get_contents(SERVICE_DIR . "vdt/demo-controller-page.vdt"))
        ->ecritVideotex(file_get_contents(SERVICE_DIR . "vdt/demo-choix-sommaire.vdt"))

        ->position(3, 1)
        ->ecritUnicode("Démo des différentes Actions intégrablesdans les contrôleurs, qu'il soient XML ou Videotex.")

        ->position(7, 1)->inversionDebut()->ecritUnicode('1')->inversionFin()
        ->ecritUnicode(" Accueil / AccueilAction()")
        ->position(9, 1)->inversionDebut()->ecritUnicode('2')->inversionFin()
        ->ecritUnicode(" Démo Choix Controller / ControllerAction()")
        ->position(11, 1)->inversionDebut()->ecritUnicode('3')->inversionFin()
        ->ecritUnicode(" Dcx / DeconnexionAction()")
        ->position(13, 1)->inversionDebut()->ecritUnicode('4')->inversionFin()
        ->ecritUnicode(" Ligne 00 / Ligne00Action()")
        ->position(15, 1)->inversionDebut()->ecritUnicode('5')->inversionFin()
        ->ecritUnicode(" Sommaire XML / PageAction()")
        ->position(17, 1)->inversionDebut()->ecritUnicode('6')->inversionFin()
        ->ecritUnicode(" Répète / RepetitionAction()")
        ->position(19, 1)->inversionDebut()->ecritUnicode('7')->inversionFin()
        ->ecritUnicode(" Texte / UnicodeOutputAction()")
        ->position(21, 1)->inversionDebut()->ecritUnicode('8')->inversionFin()
        ->ecritUnicode(" code vdt / VideotexOutputAction()")
        ->position(22, 1)->inversionDebut()->ecritUnicode('9')->inversionFin()
        ->ecritUnicode(" change service/SwitchServiceAction()")


        ->position(23, 1)->effaceFinDeLigne()->couleurFond("vert")->couleurTexte('noir')
        ->ecritUnicode(" " . end(explode('\\', $this::class)))
        ->getOutput();
        return $vdt;
    }

    public function choix1Envoi(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\AccueilAction(DEFAULT_CONTROLLER, DEFAULT_XML_FILE, $this->context);
    }

    public function choix2Envoi(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\DemoChoixCodeController::class,
            $this->context
        );
    }

    public function choix3Envoi(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\DeconnexionAction();
    }

    public function choix4Envoi(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\Ligne00Action($this, "Ligne 00 via Ligne00Action()");
    }

    public function choix5Envoi(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, "demoxml-sommaire", "demo");
    }

    public function choix6Envoi(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\RepetitionAction($this);
    }

    public function choix7Envoi(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\UnicodeOutputAction($this, " Texte unicode : çàèéwejfweoij ");
    }

    public function choix8Envoi(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\VideotexOutputAction($this, " \x1bETexte \x1bFvideotex               ");
    }

    public function choix9Envoi(): ?\MiniPaviFwk\actions\Action
    {
        // Switch to MacBidouille, displaying a message and waaiting 3 seconds.
        return new \MiniPaviFwk\actions\SwitchServiceAction(
            'macbidouille',
            "\x0c\x07*** REDIRECTION VERS MACBIDOUILLE ***",
            3
        );
    }

    public function toucheSommaire(string $saisie): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, "demoxml-sommaire", "demo");
    }
}
