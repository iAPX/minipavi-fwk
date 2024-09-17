<?php

/**
 * Sommaire de la démo du service
 *
 * Exemple utilisant les Touche*() et Choix() ainsi que la page affichée par Videotex
 */

namespace service\controllers;

class DemoValidationCodeController extends \MiniPaviFwk\controllers\VideotexController
{
    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\videotex\Videotex();

        // Redo the same demo as in demoxml-ecran page in demo.xml
        $vdt = $videotex

        // Simulate Videotex page loading as in <affiche><url>
        ->effaceLigne00()
        ->ecritVideotex(file_get_contents("service/vdt/demo-controller-page.vdt"))
        ->ecritVideotex(file_get_contents("service/vdt/demo-choix-code.vdt"))

        ->position(7, 1)->ecritUnicode("Le contrôleur autorise [SUITE] et [ENVOI] qui génèreront une erreur via nonProppose(), en sus de [RETOUR] et [SOMMAIRE]")
        
        ->position(23, 1)->effaceFinDeLigne()->couleurFond("vert")->couleurTexte('noir')->ecritUnicode(" " . end(explode('\\', $this::class)))

        ->getOutput();
        return $vdt;
    }

    public function validation(): \MiniPaviFwk\Validation
    {
        $validation = parent::validation();
        $validation->addValidKeys(['suite', 'retour', 'sommaire', 'envoi']);
        return $validation;
    }

    public function choix(string $touche, string $saisie): ?\MiniPaviFwk\actions\Action
    {
        if ($touche=='RETOUR') {
            return new \MiniPaviFwk\actions\PageAction($this->context, "demoxml-validation-code", "demo");
        } elseif ($touche=='SOMMAIRE') {
            return new \MiniPaviFwk\actions\PageAction($this->context, "demoxml-sommaire", "demo");
        } 
        return null;
    }

    public function nonPropose(string $touche, string $saisie): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\Ligne00Action($this, "Message erreur codé PHP");
    }
}
