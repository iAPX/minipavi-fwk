<?php

/**
 * Controller to disconnect the user, overriding every behaviours!
 *
 * Emit a "\e9g" to ask the Minitel or Emulator to disconnect.
 * Ensure no future actions possible until reconnected.
 */

 namespace MiniPaviFwk\controllers;

class DeconnexionController extends VideotexController
{
    public function ecran(): string
    {
        return "\e9g";
    }

    public function getAction(string $saisie, string $touche): ?\MiniPaviFwk\actions\Action
    {
        DEBUG && error_log("Deconnexion controller : NONE CHOICE");
        return new \MiniPaviFwk\actions\DeconnexionAction();
    }
}
