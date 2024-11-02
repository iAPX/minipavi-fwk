<?php

/**
 * Demonstrate Actions from a Videotex controller or a XML controller
 */

namespace service\controllers;

class DemoSommaireController extends \MiniPaviFwk\controllers\VideotexController
{
    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $videotex
        ->page("demo-controller")

        // Displays Minitel type and DRCS mode support
        ->position(24, 1)->ecritUnicode("Minitel: " . \MiniPavi\MiniPaviCli::$versionMinitel)
        ->ecritUnicode(" DRCS: " . ($_SESSION['is_drcs'] ? "oui" : "non"))

        ->position(23, 1)->effaceFinDeLigne()->couleurFond("vert")->couleurTexte('noir')
        ->ecritUnicode(" " . end(explode('\\', $this::class)));

        // Menu
        $videotex
        ->position(3, 1)->inversionDebut()->ecritUnicode('1')->inversionFin()
        ->ecritUnicode(" Ecran / DemoEcranController")

        ->position(5, 1)->inversionDebut()->ecritUnicode('2')->inversionFin()
        ->ecritUnicode(" Validation / DemoValidationController")

        ->position(7, 1)->inversionDebut()->ecritUnicode('3')->inversionFin()
        ->ecritUnicode(" ZoneSaisie / DemoZoneSaisieController")

        ->position(9, 1)->inversionDebut()->ecritUnicode('4')->inversionFin()
        ->ecritUnicode(" ZoneMessage/DemoZoneMessageController")

        ->position(11, 1)->inversionDebut()->ecritUnicode('5')->inversionFin()
        ->ecritUnicode(" Keywords / DemoKeywordsController")

        ->position(13, 1)->inversionDebut()->ecritUnicode('6')->inversionFin()
        ->ecritUnicode(" Choix / DemoChoixController")

        ->position(15, 1)->inversionDebut()->ecritUnicode('7')->inversionFin()
        ->ecritUnicode(" Actions / DemoActionController");

        // Dynamic Menu of allowed services
        $ligne = 17;
        $choix = 10;
        foreach (\ALLOWED_SERVICES as $service) {
            if ($service !== 'demo') {
                $videotex
                ->position($ligne, 1)->effaceFinDeLigne()
                ->inversionDebut()->ecritUnicode($choix++)->inversionFin()
                ->ecritUnicode(" Service : $service");
                $ligne += 2;
            }
        }

        // Choix
        $videotex
        ->position(24, 25)->ecritUnicode("Choix : .. ")
        ->inversionDebut()->ecritUnicode("ENVOI")->inversionFin();

        return $videotex->getOutput();
    }

    public function getCmd(): array
    {
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd($this->validation(), 24, 33, 2, true, '.');
    }

    public function choix1Envoi(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, 'demo-ecran');
    }

    public function choix2Envoi(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, 'demo-validation');
    }

    public function choix3Envoi(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, 'demo-zone-saisie');
    }

    public function choix4Envoi(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, 'demo-zone-message');
    }

    public function choix5Envoi(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, 'demo-keywords');
    }

    public function choix6Envoi(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, 'demo-choix');
    }

    public function choix7Envoi(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, 'demo-action');
    }

    public function choix(string $touche, string $saisie): ?\MiniPaviFwk\actions\Action
    {
        $choix = 10;
        foreach (\ALLOWED_SERVICES as $service) {
            if ($service !== 'demo') {
                if ($saisie == $choix) {
                    // Switch to new service, displaying a message and waiting 2 seconds.
                    return new \MiniPaviFwk\actions\SwitchServiceAction(
                        $service,
                        chr(12) . \MiniPavi\MiniPaviCli::toG2("*** REDIRECTION VERS $service ***"),
                        2
                    );
                }
                $choix++;
            }
        }
        return null;
    }
}