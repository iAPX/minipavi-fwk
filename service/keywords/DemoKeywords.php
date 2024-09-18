<?php

/**
 * Demo keywords for DemoKeywordsCodeController
 *
 *  * [SOMMAIRE] : Accueil (homepage)
 *    [SOMMAIRE] : XML Demo Sommaire (main menu)
 *  * [ENVOI] : MacBidouille XML Demo
 */

namespace service\keywords;

class DemoKeywords extends \MiniPaviFwk\Keywords
{
    public function validationKeys(): array
    {
        DEBUG && trigger_error("\service\keywords\DemoKeywords::validationKeys()");
        return ['SOMMAIRE', 'ENVOI'];
    }

    public function choix(string $touche, string $saisie): ?\MiniPaviFwk\actions\Action
    {
        DEBUG && trigger_error("\service\keywords\DemoKeywords::choix(\"$touche\", \"$saisie\")");
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
}
