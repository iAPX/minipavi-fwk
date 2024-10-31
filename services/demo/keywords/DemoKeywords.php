<?php

/**
 * Demo keywords for DemoKeywordsCodeController
 *
 *  * [SOMMAIRE] : Accueil (homepage)
 *    [SOMMAIRE] : XML Demo Sommaire (main menu)
 */

namespace service\keywords;

class DemoKeywords extends \MiniPaviFwk\Keywords
{
    public function validationKeys(): array
    {
        trigger_error("\service\keywords\DemoKeywords::validationKeys()");
        return ['SOMMAIRE', 'ENVOI'];
    }

    public function choix(string $touche, string $saisie): ?\MiniPaviFwk\actions\Action
    {
        trigger_error("\service\keywords\DemoKeywords::choix(\"$touche\", \"$saisie\")");
        if ($touche === 'SOMMAIRE' && $saisie === '*') {
            trigger_error("\service\keywords\DemoKeywords::choix() : * [SOMMAIRE]");
            return new \MiniPaviFwk\actions\AccueilAction([]);
        } elseif ($touche === 'SOMMAIRE' && $saisie === '') {
            trigger_error("\service\keywords\DemoKeywords::choix() : [SOMMAIRE]");
            // Handle [SOMMAIRE] to return to the Sommaire (service menu)
            return new \MiniPaviFwk\actions\ControllerAction(
                \service\controllers\DemoSommaireController::class,
                $this->context
            );
        }
        return null;
    }
}
