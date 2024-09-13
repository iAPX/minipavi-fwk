<?php

/**
 * Action to output a Unicode string, converting it to Vidéotex
 */

namespace MiniPaviFwk\actions;

class UnicodeOutputAction extends VideotexOutputAction
{
    public function __construct(\MiniPaviFwk\controllers\VideotexController $thisController, string $unicodeOutput)
    {
        DEBUG && trigger_error("Action: Sortie de chaine unicode - " . mb_strlen($unicodeOutput) . " code points.");
        parent::__construct($thisController, \MiniPavi\MiniPaviCli::toG2($unicodeOutput));
    }
}