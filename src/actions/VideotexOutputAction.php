<?php

/**
 * Action to output a Videotex string (raw output technically)
 */

namespace MiniPaviFwk\actions;

use MiniPaviFwk\controllers\VideotexController;

class VideotexOutputAction extends Action
{
    public function __construct(VideotexController $thisController, string $videotexOutput)
    {
        trigger_error("Action: Sortie de chaine - " . strlen($videotexOutput) . " octets vidéotex.", E_USER_NOTICE);
        $this->controller = $thisController;
        $this->output = $videotexOutput;
    }
}
