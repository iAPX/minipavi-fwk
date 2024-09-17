<?php

/**
 * Demo keywords for DemoKeywordsCodeController
 */

namespace service\keywords;

class DemoKeywords extends \MiniPaviFwk\Keywords
{
    public function choix(string $touche, string $saisie): ?\MiniPaviFwk\actions\Action
    {
        DEBUG && trigger_error("\service\keywords\Keywords::choix(\"$touche\", \"$saisie\")");
        // * [SOMMAIRE] return to the Accueil page
        if ($touche === 'SOMMAIRE' && $saisie === '*') {
            DEBUG && trigger_error("\service\keywords\DemoKeywords::choix() : * [SOMMAIRE]");
            return new \MiniPaviFwk\actions\AccueilAction(DEFAULT_CONTROLLER, DEFAULT_XML_FILE, $context);
        } elseif ($touche === 'SOMMAIRE' && $saisie === '') {
            DEBUG && trigger_error("\service\keywords\DemoKeywords::choix() : [SOMMAIRE]");
            return new \MiniPaviFwk\actions\PageAction([], "demoxml-sommaire", "demo");
        } elseif ($touche === 'ENVOI' && $saisie === '*') {
            DEBUG && trigger_error("\service\keywords\DemoKeywords::choix() : * [ENVOI]");
            return new \MiniPaviFwk\actions\PageAction([], "", "macbidouille");
        }

        return null;
    }

    public function validationKeys(): array
    {
        // Here the Keys that needs to be allowed for the Keywords.
        DEBUG && trigger_error("\service\keywords\DemoKeywords::validationKeys()");
        return ['SOMMAIRE', 'ENVOI'];
    }
}
