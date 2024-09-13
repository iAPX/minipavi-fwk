<?php

namespace MiniPaviFwk;

class Keywords
{
    /**
     * None Keywords, used as default to avoid tests
     */

    public function choix(string $touche, string $saisie): ?\MiniPaviFwk\actions\Action
    {
        DEBUG && trigger_error("\MiniPaviFwk\Keywords::choix(\"$touche\", \"$saisie\")");
        return null;
    }

    public function validationKeys(): array
    {
        DEBUG && trigger_error("\MiniPaviFwk\Keywords::validationKeys()");
        return [];
    }
}
