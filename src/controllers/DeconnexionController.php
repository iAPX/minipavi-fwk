<?php

/**
 * Controller for deconnected people, overriding behaviours!
 */

 namespace MiniPaviFwk\controllers;

class DeconnexionController extends VideotexController
{
    // Disconnect user from the Service, ensure blocking in case of errors on MiniPavi or Emulator

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
