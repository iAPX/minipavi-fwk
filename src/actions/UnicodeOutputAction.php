<?php

/**
 * Action to output a Unicode string, converting it to Vidéotex
 */

namespace MiniPaviFwk\actions;

use MiniPavi\MiniPaviCli;
use MiniPaviFwk\controllers\VideotexController;

class UnicodeOutputAction extends VideotexOutputAction
{
    public function __construct(VideotexController $thisController, string $unicodeOutput)
    {
        trigger_error(
            "Action: Sortie de chaine unicode - " . mb_strlen($unicodeOutput) . " code points.",
            E_USER_NOTICE
        );
        parent::__construct($thisController, MiniPaviCli::toG2($unicodeOutput));
    }
}
