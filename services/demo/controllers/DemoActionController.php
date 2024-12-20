<?php

/**
 * Demonstrate Actions from a Videotex controller or a XML controller
 */

namespace service\controllers;

class DemoActionController extends \MiniPaviFwk\controllers\VideotexController
{
    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $vdt = $videotex
        ->effaceLigne00()
        ->page("demo-controller")

        ->position(3, 1)
        ->ecritUnicode("Démo des différentes Actions intégrablesdans les contrôleurs Vidéotex.")

        ->position(6, 1)->inversionDebut()->ecritUnicode('1')->inversionFin()
        ->ecritUnicode(" Accueil / AccueilAction()")
        ->position(8, 1)->inversionDebut()->ecritUnicode('2')->inversionFin()
        ->ecritUnicode(" Démo Choix Controller / ControllerAction()")
        ->position(10, 1)->inversionDebut()->ecritUnicode('3')->inversionFin()
        ->ecritUnicode(" Dcx / DeconnexionAction()")
        ->position(12, 1)->inversionDebut()->ecritUnicode('4')->inversionFin()
        ->ecritUnicode(" Ligne 00 / Ligne00Action()")
        ->position(14, 1)->inversionDebut()->ecritUnicode('5')->inversionFin()
        ->ecritUnicode(" Accueil / PageAction()")
        ->position(16, 1)->inversionDebut()->ecritUnicode('6')->inversionFin()
        ->ecritUnicode(" Répète / RepetitionAction()")
        ->position(18, 1)->inversionDebut()->ecritUnicode('7')->inversionFin()
        ->ecritUnicode(" code vdt / VideotexOutputAction()")
        ->position(20, 1)->inversionDebut()->ecritUnicode('8')->inversionFin()
        ->ecritUnicode(" switch service/SwitchServiceAction()")


        ->position(23, 1)->effaceFinDeLigne()->couleurFond("vert")->couleurTexte('noir')
        ->ecritUnicode(" " . end(explode('\\', $this::class)))

        ->position(24, 25)->ecritUnicode("Choix : .. ")
        ->inversionDebut()->ecritUnicode("ENVOI")->inversionFin()

        ->getOutput();
        return $vdt;
    }

    public function getCmd(): array
    {
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd(null, 24, 33, 2, true, '.');
    }

    public function choix1Envoi(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\AccueilAction($this->context);
    }

    public function choix2Envoi(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\DemoChoixController::class,
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
        return new \MiniPaviFwk\actions\PageAction($this->context, 'demo-accueil');
    }

    public function choix6Envoi(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\RepetitionAction($this);
    }

    public function choix7Envoi(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\VideotexOutputAction($this, " \x1bETexte \x1bFvideotex               ");
    }

    public function choix8Envoi(): ?\MiniPaviFwk\actions\Action
    {
        // Switch to MacBidouille, displaying a message and waiting 2 seconds.
        return new \MiniPaviFwk\actions\SwitchServiceAction(
            'demochat',
            chr(12) . \MiniPavi\MiniPaviCli::toG2("*** REDIRECTION VERS DÉMOCHAT ***"),
            2
        );
    }

    public function toucheSommaire(string $saisie): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\DemoSommaireController::class,
            $this->context
        );
    }
}
