<?php

/**
 * Here add your Keywords handling
 *
 * Could be removed if not needed
 */

namespace service;

class Keywords extends \helpers\Keywords
{
    public function choix(string $touche, string $saisie): ?\helpers\ActionInterface
    {
        DEBUG && trigger_error("\service\Keywords::choix(\"$touche\", \"$saisie\")");
        // * [SOMMAIRE] return to the Accueil page
        if ($touche === 'SOMMAIRE' and $saisie === '*') {
            DEBUG && trigger_error("\service\Keywords::choix() : * [SOMMAIRE]");
            return new \helpers\AccueilAction(DEFAULT_CONTROLLER, DEFAULT_XML_FILE, $context);
        }

        return null;
    }

    public function validationKeys(): array
    {
        // Here the Keys that needs to be allowed for the Keywords.
        DEBUG && trigger_error("\service\Keywords::validationKeys()");
        return ['SOMMAIRE'];
    }
}
