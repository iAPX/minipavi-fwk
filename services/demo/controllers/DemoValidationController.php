<?php

/**
 * Demo for validation of function keys in a Videotex Controller
 */

namespace service\controllers;

class DemoValidationController extends \MiniPaviFwk\controllers\VideotexController
{
    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $vdt = $videotex
        ->effaceLigne00()
        ->page("demo-controller")

        ->position(7, 1)
        ->ecritUnicode("Le contrôleur autorise [SUITE] et [ENVOI] qui génèreront une erreur via nonPropose()")
        ->ecritUnicode(", en sus de [RETOUR] et [SOMMAIRE]")

        ->position(23, 1)->effaceFinDeLigne()->couleurFond("vert")->couleurTexte('noir')
        ->ecritUnicode(" " . end(explode('\\', $this::class)))

        ->position(24, 30)->inversionDebut()->ecritUnicode(' SOMMAIRE ')->inversionFin()

        ->getOutput();
        return $vdt;
    }

    public function getCmd(): array
    {
        // On autorise SUITE, ENVOI, RETOUR et SOMMAIRE
        $validation = \MiniPaviFwk\helpers\ValidationHelper::SUITE | \MiniPaviFwk\helpers\ValidationHelper::ENVOI | \MiniPaviFwk\helpers\ValidationHelper::RETOUR | \MiniPaviFwk\helpers\ValidationHelper::SOMMAIRE;
        return ZoneSaisieCmd::createMiniPaviCmd($validation);
    }

    public function choix(string $touche, string $saisie): ?\MiniPaviFwk\actions\Action
    {
        if ($touche == 'RETOUR') {
            // Handle [RETOUR] to return to the Sommaire (service menu)
            return new \MiniPaviFwk\actions\PageAction($this->context, 'demo-sommaire');
        } elseif ($touche == 'SOMMAIRE') {
            // Handle [SOMMAIRE] to return to the Sommaire (service menu)
            return new \MiniPaviFwk\actions\PageAction($this->context, 'demo-sommaire');
        }
        return null;
    }

    public function nonPropose(string $touche, string $saisie): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\Ligne00Action($this, "Message erreur codé PHP");
    }
}
