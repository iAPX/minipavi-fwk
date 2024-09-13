<?php

namespace helpers;

class Keywords
{
    public function choix(string $touche, string $saisie): ?\helpers\actions\Action
    {
        DEBUG && trigger_error("\helpers\Keywords::choix(\"$touche\", \"$saisie\")");
        return null;
    }

    public function validationKeys(): array
    {
        DEBUG && trigger_error("\helpers\Keywords::validationKeys()");
        return [];
    }
}
