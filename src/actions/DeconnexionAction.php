<?php

/**
 * Action to exit the service, called déconnexion (disconnection) in French.
 */

namespace MiniPaviFwk\actions;

class DeconnexionAction extends Action
{
    // Close the access to the service
    public function __construct(string $ligne00 = 'Déconnexion service.')
    {
        trigger_error("Action: Deconnexion", E_USER_NOTICE);
        $this->controller = new \MiniPaviFwk\controllers\DeconnexionController([]);
        $this->output = \MiniPavi\MiniPaviCli::writeLine0($ligne00);
    }
}
