<?php

/**
 * Action to output a Videotex string (raw output technically)
 */

namespace MiniPaviFwk\actions;

class VideotexOutputAction extends Action
{
    public function __construct(\MiniPaviFwk\controllers\VideotexController $thisController, string $videotexOutput)
    {
        DEBUG && trigger_error("Action: Sortie de chaine - " . strlen($videotexOutput) . " octets vidéotex.");
        $this->controller = $thisController;
        $this->output = $videotexOutput;
    }
}
