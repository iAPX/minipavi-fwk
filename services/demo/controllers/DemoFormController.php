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
        ->position(7, 30)->ecritUnicode("Remplir chaque champ puis [SUITE]")
        ->position(8, 1)->ecritUnicode("Finir avec [ENVOI]")

        ->position(12, 1)->ecritUnicode("Formulaire précédent : ")

        ->position(23, 1)->effaceFinDeLigne()->couleurFond("vert")->couleurTexte('noir')
        ->ecritUnicode(" " . end(explode('\\', $this::class)));
        return $videotex->getOutput();
    }

    public function getCmd(): array
    {
        // We define the fields
        $fields = [
            new \MiniPaviFwk\helpers\FormField(4, 7, 34, '.'),
            new \MiniPaviFwk\helpers\FormField(5, 10, 31, '.'),
            new \MiniPaviFwk\helpers\FormField(6, 15, 5, ' '),
        ];

        $cmd = \MiniPaviFwk\cmd\InputFormCmd::createMiniPaviCmd(null, $fields, true);
        trigger_error("getCmd : " . print_r($cmd, true), E_USER_ERROR);

        return \MiniPaviFwk\cmd\InputFormCmd::createMiniPaviCmd(null, $fields, true);
    }

    public function choix(string $touche, string $saisie): ?\MiniPaviFwk\actions\Action
    {
        trigger_error("choix : $touche - $saisie", E_USER_ERROR);
        return null;
    }

    public function message(string $touche, array $message): ?\MiniPaviFwk\actions\Action
    {
        trigger_error("message : $touche", E_USER_ERROR);
        trigger_error(print_r($message, true), E_USER_ERROR);

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
        }
    }

    private function displayPrecedentForm(array $message): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $videotex
        ->effaceLigne00()
        ->position(13, 1)
        ->ecritUnicode($message[1] . ' ' . $message[0] . ' (' . $message[2] . ')');
        return $videotex->getOutput();
    }
}
