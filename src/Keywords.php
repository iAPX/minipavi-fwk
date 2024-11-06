<?php

/**
 * Default keywords handling, that provide none keyword.
 *
 * Helps avoid testing if keywords should be supported or not on getSaisieAction() !
 */

namespace MiniPaviFwk;

use MiniPaviFwk\actions\Action;

class Keywords
{
    public function choix(string $touche, string $saisie): ?Action
    {
        trigger_error("\MiniPaviFwk\Keywords::choix(\"$touche\", \"$saisie\")", E_USER_NOTICE);
        return null;
    }
}
