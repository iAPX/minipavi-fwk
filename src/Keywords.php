<?php

/**
 * Default keywords handling, that provide none keyword.
 *
 * Helps avoid testing if keywords should be supported or not on validation() and getSaisieAction() !
 */

namespace MiniPaviFwk;

use MiniPaviFwk\actions\Action;

class Keywords
{
    /**
     * None Keywords, used as default to avoid tests, thus none function key to validate
     */
    public function validationKeys(): array
    {
        trigger_error("\MiniPaviFwk\Keywords::validationKeys()", E_USER_NOTICE);
        return [];
    }

    public function choix(string $touche, string $saisie): ?Action
    {
        trigger_error("\MiniPaviFwk\Keywords::choix(\"$touche\", \"$saisie\")", E_USER_NOTICE);
        return null;
    }
}
