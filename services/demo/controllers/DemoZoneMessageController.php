<?php

/**
 * Demo of the Message input with XML
 */

namespace service\controllers;

class DemoZoneMessageCodeController extends \MiniPaviFwk\controllers\VideotexController
{
    public function ecran(): string
    {
        $vdt = parent::ecran();
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $videotex->position(23, 1)->effaceFinDeLigne()->couleurFond("vert")->couleurTexte('noir')
        ->ecritUnicode(" " . end(explode('\\', $this::class)));
        $vdt .= $videotex->getOutput();
        return $vdt;
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
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $videotex->effaceLigne00();
        foreach ($message as $messageLigne => $messageSaisie) {
            $videotex->position($messageLigne + 13, 1)->effaceFinDeLigne()->ecritUnicode($messageSaisie);
        }
        return $videotex->getOutput();
    }
}
