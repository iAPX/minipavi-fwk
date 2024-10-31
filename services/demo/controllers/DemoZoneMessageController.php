<?php

/**
 * Demo of the Message input with XML
 */

namespace service\controllers;

class DemoZoneMessageController extends \MiniPaviFwk\controllers\VideotexController
{
    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $videotex
        ->effaceLigne00()
        ->page("demo-controller")
        ->page("demo-choix-sommaire")

        ->position(4, 1)->inversionDebut()->ecritUnicode("Message + [ENVOI] : ")->inversionFin()

        ->position(12, 1)->inversionDebut()->ecritUnicode("Message précédent : ")->inversionFin()

        ->position(23, 1)->effaceFinDeLigne()->couleurFond("vert")->couleurTexte('noir')
        ->ecritUnicode(" " . end(explode('\\', $this::class)));
        return $videotex->getOutput();
    }

    public function validation(): \MiniPaviFwk\Validation
    {
        // Allow [SOMMAIRE], [ENVOI] keys
        $validation = parent::validation();
        $validation->addValidKeys(['sommaire', 'envoi']);
        return $validation;
    }

    public function getCmd(): array
    {
        // Here we define the Message input area
        return \MiniPaviFwk\cmd\ZoneMessageCmd::createMiniPaviCmd($this->validation(), 5, 4, true, '.', '-');
    }

    public function message(string $touche, array $message): ?\MiniPaviFwk\actions\Action
    {
        if ($touche === 'ENVOI') {
            if (empty(implode('', $message))) {
                return new \MiniPaviFwk\actions\Ligne00Action($this, "Message vide");
            }

            // Display the input message as preceding message.
            $vdt = $this->displayPrecedentMessage($message);
            return new \MiniPaviFwk\actions\VideotexOutputAction($this, $vdt);
        } elseif ($touche === 'SOMMAIRE') {
            // Handle [SOMMAIRE] to return to the Sommaire (service menu)
            return new \MiniPaviFwk\actions\ControllerAction(
                \service\controllers\DemoSommaireController::class,
                $this->context
            );
        }
    }

    private function displayPrecedentMessage(array $message): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $videotex->effaceLigne00();
        foreach ($message as $messageLigne => $messageSaisie) {
            $videotex->position($messageLigne + 13, 1)->effaceFinDeLigne()->ecritUnicode($messageSaisie);
        }
        return $videotex->getOutput();
    }
}
