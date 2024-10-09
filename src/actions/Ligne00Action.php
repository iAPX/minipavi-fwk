<?php

/**
 * Action to output a unicode string in Ligne 0 of the Minitel.
 *
 * Cursor come back where it was before,
 * a specific behavior of Minitel when encountering a "\n" on ligne 0.
 */

namespace MiniPaviFwk\actions;

class Ligne00Action extends Action
{
    public function __construct(\MiniPaviFwk\controllers\VideotexController $thisController, string $output)
    {
        trigger_error("Action: Ligne 00 - " . mb_strlen($output) . " code points.", E_USER_NOTICE);
        $this->controller = $thisController;
        $this->output = \MiniPavi\MiniPaviCli::writeLine0($output);
    }
}
