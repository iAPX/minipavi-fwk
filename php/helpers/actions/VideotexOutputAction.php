<?php
/**
 * Action to output a Videotex string (as-is)
 */

class VideotexOutputAction extends Action
{
    public function __construct(\helpers\controllers\VideotexController $thisController, string $videotexOutput)
    {
        DEBUG && trigger_error("Action: Sortie de chaine - " . strlen($videotexOutput) . " octets vidéotex.");
        $this->controller = $thisController;
        $this->output = $videotexOutput;
    }
}
