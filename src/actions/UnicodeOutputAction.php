<?php

/**
 * Action to output a Unicode string, converting it to Vidéotex
 */

namespace MiniPaviFwk\actions;

class UnicodeOutputAction extends VideotexOutputAction
{
    public function __construct(\MiniPaviFwk\controllers\VideotexController $thisController, string $unicodeOutput)
    {
        trigger_error(
            "Action: Sortie de chaine unicode - " . mb_strlen($unicodeOutput) . " code points.",
            E_USER_NOTICE
        );
        parent::__construct($thisController, \MiniPavi\MiniPaviCli::toG2($unicodeOutput));
    }
}
