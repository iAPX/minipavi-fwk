<?php

/**
 * Action to close the access to the service
 */

namespace MiniPaviFwk\actions;

class DeconnexionAction extends Action
{
    // Close the access to the service
    public function __construct(string $ligne00 = 'Déconnexion service.')
    {
        DEBUG && trigger_error("Action: Deconnexion");
        $this->controller = new \MiniPaviFwk\controllers\DeconnexionController([]);
        $this->output = \MiniPavi\MiniPaviCli::writeLine0($ligne00) . "\e9g";     // Escape 9 g = deconnexion
    }
}
