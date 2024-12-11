<?php

/**
 * Handles user input and keywords
 */

namespace MiniPaviFwk\controllers\hierarchy;

use MiniPaviFwk\actions\Action;
use MiniPaviFwk\actions\Ligne00Action;
use MiniPaviFwk\actions\RepetitionAction;
use MiniPaviFwk\Keywords;

class InputVideotex extends DisplayVideotex
{
    // Keyword Handler
    public Keywords $keywordHandler;

    public function __construct($context, $params = [])
    {
        parent::__construct($context, $params);
        $this->keywordHandler = new Keywords();
    }

    public function preReponse()
    {
        // Hook for processing taking place before processing user response.
        trigger_error("InputVideotex::preReponse()", E_USER_NOTICE);
    }

    public function getSaisieAction(string $saisie, string $touche): ?Action
    {
        // Handle user input through keywordhandler, introspection and overridable methods
        $this->preReponse();

        // Keywords are prioritary, if present
        trigger_error("get-Action : appel keywordHanlder->choix()", E_USER_NOTICE);
        $action = $this->keywordHandler->choix($touche, $saisie);
        if (!is_null($action)) {
            return $action;
        }

        // Try all possibilities, in order
        $methods = [];
        if (preg_match('/^[A-Za-z0-9*#]+$/u', $saisie) == 1 || $saisie === '') {
            // Non-empty unicode string with AZaz09*# characters (specifically no ::, \\) to avoid security issues
            // * becomes ETOILE, # becomes DIESE. French word for "star" or * used in Minitel culture.
            $formatted_saisie = \MiniPaviFwk\helpers\mb_ucfirst(mb_strtolower($saisie));
            $cleaned_saisie = str_replace(['*', '#'], ['ETOILE', 'DIESE'], $formatted_saisie);
            $formatted_touche = \MiniPaviFwk\helpers\mb_ucfirst(mb_strtolower($touche));
            $methods[] = ['choix' . $cleaned_saisie . $formatted_touche, []];
        }
        $methods[] = ['touche' . \MiniPaviFwk\helpers\mb_ucfirst(mb_strtolower($touche)), [$saisie]];
        $methods[] = ['choix', [$touche, $saisie]];
        $methods[] = ['nonPropose', [$touche, $saisie]];

        foreach ($methods as $method) {
            trigger_error("getSaisieAction - try method : " . $method[0] . "()", E_USER_NOTICE);
            if (method_exists($this, $method[0])) {
                $method_name = $method[0];
                $method_params = $method[1];
                trigger_error("getSaisieAction-CHOIX : " . $this::class . " -> " . $method_name . "()", E_USER_NOTICE);
                $action = $this->$method_name(...$method_params);
                if (!is_null($action)) {
                    // Found an action!
                    break;
                }
            } else {
                trigger_error("getSaisieAction - NONE : " . $this::class . " -> " . $method[0] . "()", E_USER_NOTICE);
            }
        }
        if (is_null($action)) {
            // Fallback : nonPropose(should always return an Action)
            trigger_error("Aucun choix n'a été trouvé", E_USER_WARNING);
            $action = new Ligne00Action($this, "Commande inconnue.");
        }

        return $action;
    }

    public function getMessageAction(array $message, string $touche): ?Action
    {
        $this->preReponse();

        if (method_exists($this, 'formulaire')) {
            // if formulaire() is present, use it instead
            $action = $this->formulaire($touche, ...$message);
        } else {
            // Handle user Message through message() method
            $action = $this->message($touche, $message);
        }
        if (is_null($action)) {
            // Fallback
            trigger_error("Aucun choix n'a été trouvé", E_USER_WARNING);
            $action = new Ligne00Action($this, "Message non traité.");
        }
        return $action;
    }
}
