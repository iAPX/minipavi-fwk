<?php

/**
 * Demo of the Message input controller-only
 */

namespace service\controllers;

class DemoMessageCodeController extends \MiniPaviFwk\controllers\VideotexController
{
    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\videotex\Videotex();
        $videotex->effaceLigne00()->ecritVideotex(file_get_contents(SERVICE_DIR . "vdt/demo-controller-page.vdt"));
        $videotex->ecritVideotex(file_get_contents(SERVICE_DIR . "vdt/demo-choix-sommaire.vdt"));

        $videotex->position(4, 1)->inversionDebut()->ecritUnicode("Message + [ENVOI] : ")->inversionFin();

        $videotex->position(12, 1)->inversionDebut()->ecritUnicode("Message précédent : ")->inversionFin();

        $videotex->position(23, 1)->effaceFinDeLigne()->couleurFond("vert")->couleurTexte('noir')
        ->ecritUnicode(" " . end(explode('\\', $this::class)));
        $vdt .= $videotex->getOutput();
        return $vdt;
    }

    public function getCmd(): array
    {
        // Here we define the Message input area
        return \MiniPaviFwk\cmd\ZoneMessageCmd::createMiniPaviCmd($this->validation(), 5, 4, true, '.', '-');
    }

    public function messageToucheSommaire(array $message): ?\MiniPaviFwk\actions\Action
    {
        // Handle [SOMMAIRE] to return to the Sommaire (service menu)
        // We could also process the multiline message received if needed,
        // for example save it as a draft to display it again when back in this page (using prefill)
        return new \MiniPaviFwk\actions\PageAction($this->context, "demoxml-sommaire", "demo");
    }

    public function messageToucheEnvoi(array $message): ?\MiniPaviFwk\actions\Action
    {
        if (empty(implode('', $message))) {
            return new \MiniPaviFwk\actions\Ligne00Action($this, "Message vide");
        }

        // Display the input message as preceding message.
        $vdt = $this->displayPrecedentMessage($message);
        return new \MiniPaviFwk\actions\VideotexOutputAction($this, $vdt);
    }

    private function displayPrecedentMessage(array $message): string
    {
        $videotex = new \MiniPaviFwk\videotex\Videotex();
        $videotex->effaceLigne00();
        foreach ($message as $messageLigne => $messageSaisie) {
            $videotex->position($messageLigne + 13, 1)->effaceFinDeLigne()->ecritUnicode($messageSaisie);
        }
        return $videotex->getOutput();
    }
}
