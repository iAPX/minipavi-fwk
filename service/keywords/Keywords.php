<?php

/**
 * Here add your Keywords handling
 *
 * Could be removed if not needed
 */

namespace service\keywords;

class Keywords extends \MiniPaviFwk\Keywords
{
    public function choix(string $touche, string $saisie): ?\MiniPaviFwk\actions\Action
    {
        DEBUG && trigger_error("\service\keywords\Keywords::choix(\"$touche\", \"$saisie\")");
        // * [SOMMAIRE] return to the Accueil page
        if ($touche === 'SOMMAIRE' and $saisie === '*') {
            DEBUG && trigger_error("\service\keywords\Keywords::choix() : * [SOMMAIRE]");
            return new \MiniPaviFwk\actions\AccueilAction(DEFAULT_CONTROLLER, DEFAULT_XML_FILE, $context);
        }

        return null;
    }

    public function validationKeys(): array
    {
        // Here the Keys that needs to be allowed for the Keywords.
        DEBUG && trigger_error("\service\keywords\Keywords::validationKeys()");
        return ['SOMMAIRE'];
    }
}
