<?php

/**
 * Action to output a unicode string in Ligne 0 of the Minitel.
 *
 * Curseur come back where it was before,
 * a specific behavior of Minitel when encountering a "\n" on ligne 0.
 */

namespace MiniPaviFwk\actions;

class Ligne00Action extends Action
{
    public function __construct(\MiniPaviFwk\controllers\VideotexController $thisController, string $output)
    {
        DEBUG && trigger_error("Action: Ligne 00 - " . mb_strlen($output) . " code points.");
        $this->controller = $thisController;
        $this->output = \MiniPavi\MiniPaviCli::writeLine0($output);
    }
}
