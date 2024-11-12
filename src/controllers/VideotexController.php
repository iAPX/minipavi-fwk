<?php

/**
 * Provides basic Videotex method and functions
 */

namespace MiniPaviFwk\controllers;

use MiniPaviFwk\actions\Action;
use MiniPaviFwk\actions\Ligne00Action;
use MiniPaviFwk\actions\RepetitionAction;
use MiniPaviFwk\cmd\ZoneSaisieCmd;
use MiniPaviFwk\Keywords;
use MiniPaviFwk\Validation;

class VideotexController
{
    // Context of this controller, it coulld manipulate it the way it want
    protected $context;
    public Keywords $keywordHandler;

    public function __construct($context, $params = [])
    {
        // @TODO Stack managed here, including retrieving params!
        $context['params'] = $params;
        $context['controller'] = $this::class;
        $this->context = $context;
        $this->keywordHandler = new Keywords();
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function ecran(): string
    {
        // Overriden in sub-classes
        return chr(12) . "*** Ecran() absent. ***";
    }

    public function getCmd(): array
    {
        // Default, overridable in sub-classes, to simplify code.
        return ZoneSaisieCmd::createMiniPaviCmd();
    }

    public function toucheRepetition(string $saisie): ?Action
    {
        // Default overridable in sub-classes
        return new RepetitionAction($this);
    }

    public function getSaisieAction(string $saisie, string $touche): ?Action
    {
        // Handle user input through keywordhandler, introspection and overridable methods

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
                trigger_error("getSaisieAction - NONE : " . $this::class . " -> " . $method_name . "()", E_USER_NOTICE);
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


    public function choix(string $touche, string $saisie): ?Action
    {
        // Overridable in sub-classes
        // Default : error (nonPropose)
        return null;
    }

    public function message(string $touche, array $message): ?Action
    {
        // Overridable in sub-classes
        // Default : error (nonPropose)
        return null;
    }

    public function nonPropose(string $touche, string $saisie): ?Action
    {
        return null;
    }

    public function keywords(string $touche, string $saisie): ?Action
    {
        // Overridable in sub-classes
        return $this->keywordHandler->choix($touche, $saisie);
    }

    public function keywordsValidationKeys(): array
    {
        // Overridable in sub-classes
        return $this->keywordHandler->validationKeys();
    }
}
