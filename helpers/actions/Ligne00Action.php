<?php

/**
 * Action to output a unicode string in Ligne 0 of the Minitel
 */

namespace helpers\actions;

class Ligne00Action extends Action
{
    public function __construct(\helpers\controllers\VideotexController $thisController, string $output)
    {
        DEBUG && trigger_error("Action: Ligne 00 - " . mb_strlen($output) . " code points.");
        $this->controller = $thisController;
        $this->output = \MiniPavi\MiniPaviCli::writeLine0($output);
    }
}
