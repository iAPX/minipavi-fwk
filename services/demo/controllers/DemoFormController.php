<?php

/**
 * Demo of the Message input with XML
 */

namespace service\controllers;

use MiniPaviFwk\helpers\FormField;

class DemoFormController extends \MiniPaviFwk\controllers\VideotexController
{
    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $videotex
        ->effaceLigne00()
        ->page("demo-controller")
        ->page("demo-choix-sommaire")

        ->position(4, 1)->ecritUnicode("Nom : ")
        ->position(5, 1)->ecritUnicode("Prénom : ")
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
            new \MiniPaviFwk\helpers\FormField(4, 7, 34),
            new \MiniPaviFwk\helpers\FormField(5, 10, 31),
            new \MiniPaviFwk\helpers\FormField(6, 15, 5),
        ];
        return \MiniPaviFwk\cmd\InputFormCmd::createMiniPaviCmd(null, $fields, true, ".");
    }

    public function message(string $touche, array $message): ?\MiniPaviFwk\actions\Action
    {
        if ($touche === 'ENVOI') {
            if (empty(implode('', $message))) {
                return new \MiniPaviFwk\actions\Ligne00Action($this, "Formulaire vide");
            }

            // Display the input message as preceding message.
            $vdt = $this->displayPrecedentForm($message);
            return new \MiniPaviFwk\actions\VideotexOutputAction($this, $vdt);
        } elseif ($touche === 'SOMMAIRE') {
            // Handle [SOMMAIRE] to return to the Sommaire (service menu)
            return new \MiniPaviFwk\actions\PageAction($this->context, 'demo-sommaire');
        } elseif ($touche === 'REPETITION') {
            // Display again
            return new \MiniPaviFwk\actions\RepetitionAction($this);
        }
    }

    private function displayPrecedentForm(array $message): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $videotex
        ->effaceLigne00()
        ->position(13, 1)
        ->effaceFinDeLigne()
        ->ecritUnicode($message[1] . ' ' . $message[0] . ' (' . $message[2] . ')');
        return $videotex->getOutput();
    }
}
