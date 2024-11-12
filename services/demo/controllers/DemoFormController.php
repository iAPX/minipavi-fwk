<?php

/**
 * Demo of the Message input with XML
 */

namespace service\controllers;

use MiniPaviFwk\helpers\FormField;

class DemoFormController extends \MiniPaviFwk\controllers\VideotexController
{
    private string $nom = '';
    private string $prenom = '';
    private string $cp = '';

    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $videotex
        ->effaceLigne00()
        ->page("demo-controller")
        ->page("demo-choix-sommaire")

        ->position(4, 1)->ecritUnicode("Nom*: ")
        ->position(5, 1)->ecritUnicode("Prénom*: ")
        ->position(6, 1)->ecritUnicode("Code Postal : ")
        ->position(7, 8)->ecritUnicode("Remplir chaque champ puis [SUITE]")
        ->position(8, 23)->ecritUnicode("Finir avec [ENVOI]")

        ->position(12, 1)->ecritUnicode("Formulaire précédent : ")

        ->position(23, 1)->effaceFinDeLigne()->couleurFond("vert")->couleurTexte('noir')
        ->ecritUnicode(" " . end(explode('\\', $this::class)));
        return $videotex->getOutput();
    }

    public function getCmd(): array
    {
        // We define the fields
        $fields = [
            new \MiniPaviFwk\helpers\FormField(4, 7, 34, $this->nom),
            new \MiniPaviFwk\helpers\FormField(5, 10, 31, $this->prenom),
            new \MiniPaviFwk\helpers\FormField(6, 15, 5, $this->cp),
        ];
        return \MiniPaviFwk\cmd\InputFormCmd::createMiniPaviCmd(null, $fields, true, ".");
    }

    public function formulaire(string $touche, string $nom, string $prenom, string $cp): ?\MiniPaviFwk\actions\Action
    {
        if ($touche === 'ENVOI') {
            // Nom and Prénom are mandatory (*)
            if ($nom === '' || $prenom === '') {
                // Prefill for modification
                $this->nom = $nom;
                $this->prenom = $prenom;
                $this->cp = $cp;
                return new \MiniPaviFwk\actions\Ligne00Action($this, "Nom et prénom obligatoires");
            }

            // Display the form content
            $vdt = $this->displayPrecedentForm($nom, $prenom, $cp);
            return new \MiniPaviFwk\actions\VideotexOutputAction($this, $vdt);
        } elseif ($touche === 'SOMMAIRE') {
            // Handle [SOMMAIRE] to return to the Sommaire (service menu)
            return new \MiniPaviFwk\actions\PageAction($this->context, 'demo-sommaire');
        }
        return null;
    }

    private function displayPrecedentForm(string $nom, string $prenom, string $cp): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $videotex
        ->effaceLigne00()
        ->position(13, 1)
        ->effaceFinDeLigne()
        ->ecritUnicode($prenom . ' ' . $nom . ' (' . $cp . ')');
        return $videotex->getOutput();
    }
}
