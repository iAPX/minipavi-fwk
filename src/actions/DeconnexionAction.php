<?php

/**
 * Action to exit the service, called déconnexion (disconnection) in French.
 */

namespace MiniPaviFwk\actions;

use MiniPavi\MiniPaviCli;
use MiniPaviFwk\controllers\DeconnexionController;

class DeconnexionAction extends Action
{
    // Close the access to the service
    public function __construct(string $ligne00 = 'Déconnexion service.')
    {
        trigger_error("Action: Deconnexion", E_USER_NOTICE);
        $this->controller = new DeconnexionController([]);
        $this->output = MiniPaviCli::writeLine0($ligne00);
    }
}
