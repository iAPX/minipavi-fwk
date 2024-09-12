<?php

/**
 * Controller for deconnected people, overriding behaviours!
 */

 namespace helpers;

class DeconnexionController extends VideotexController
{
    // Disconnect user from the Service, ensure blocking in case of errors on MiniPavi or Emulator

    public function ecran(): string
    {
        return "\e9g";
    }

    public function getAction(string $saisie, string $touche): ?\helpers\ActionInterface
    {
        DEBUG && error_log("Deconnexion controller : NONE CHOICE");
        return new \helpers\DeconnexionAction();
    }
}
