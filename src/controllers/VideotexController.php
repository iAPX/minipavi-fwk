<?php

/**
 * Provides basic Videotex method and functions
 */

namespace MiniPaviFwk\controllers;

class VideotexController
{
    // Context of this controller, it coulld manipulate it the way it want
    protected $context;
    public \MiniPaviFwk\Keywords $keywordHandler;

    public function __construct($context, $params = [])
    {
        // @TODO Stack managed here, including retrieving params!
        $context['params'] = $params;
        $context['controller'] = $this::class;
        $this->context = $context;
        $this->keywordHandler = new \MiniPaviFwk\Keywords();
    }

    public function getContext()
    {
        return $this->context;
    }

    public function getDirectCall(): string
    {
        // Overriden in sub-classes when needed
        return "no";
    }

    public function ecran(): string
    {
        // Overriden in sub-classes
        return "*** Ecran absent. ***";
    }

    public function validation(): \MiniPaviFwk\Validation
    {
        // Default, overridable in sub-classes
        return new \MiniPaviFwk\Validation($this);
    }

    public function getCmd(): array
    {
        // Default, overridable in sub-classes, to simplify code.
        // validation willl be overriden
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd($this->validation());
    }

    public function toucheRepetition(string $saisie): ?\MiniPaviFwk\actions\Action
    {
        // Default overridable in sub-classes
        return new \MiniPaviFwk\actions\RepetitionAction($this);
    }

    public function messageToucheRepetition(array $message): ?\MiniPaviFwk\actions\Action
    {
        // Default overridable in sub-classes
        return new \MiniPaviFwk\actions\RepetitionAction($this);
    }


    public function getSaisieAction(string $saisie, string $touche): ?\MiniPaviFwk\actions\Action
    {
        // Handle user input through keywordhandler, introspection and overridable methods

        // Keywords are prioritary, if present
        DEBUG && trigger_error("get-Action : appel keywordHanlder->choix()");
        $action = $this->keywordHandler->choix($touche, $saisie);
        if (!is_null($action)) {
            return $action;
        }

        // Try all possibilities, in order
        $methods = [];
        if (preg_match('/^[A-Za-z0-9*#]+$/u', $saisie) == 1) {
            // Non-empty unicode string with AZaz09*# characters (specifically no ::, \\) to avoid security issues
            // * becomes ETOILE, # becomes DIESE. French word for "star" or * used in Minitel culture.
            $formatted_saisie = \MiniPaviFwk\strings\mb_ucfirst(mb_strtolower($saisie));
            $cleaned_saisie = str_replace(['*', '#'], ['ETOILE', 'DIESE'], $formatted_saisie);
            $formatted_touche = \MiniPaviFwk\strings\mb_ucfirst(mb_strtolower($touche));
            $methods[] = ['choix' . $cleaned_saisie . $formatted_touche, []];
        }
        $methods[] = ['touche' . \MiniPaviFwk\strings\mb_ucfirst(mb_strtolower($touche)), [$saisie]];
        $methods[] = ['choix', [$touche, $saisie]];
        $methods[] = ['nonPropose', [$touche, $saisie]];

        foreach ($methods as $method) {
            DEBUG && trigger_error("getSaisieAction - try method : " . $method[0] . "()");
            if (method_exists($this, $method[0])) {
                $method_name = $method[0];
                $method_params = $method[1];
                DEBUG && trigger_error("getSaisieAction - CHOIX : " . $this::class . " -> " . $method_name . "()");
                $action = $this->$method_name(...$method_params);
                if (!is_null($action)) {
                    // Found an action!
                    break;
                }
            } else {
                DEBUG && trigger_error("getSaisieAction - NONE : " . $this::class . " -> " . $method_name . "()");
            }
        }
        if (is_null($action)) {
            // Fallback : nonPropose(should always return an Action)
            DEBUG && trigger_error("Aucun choix n'a été trouvé");
            $action = new \MiniPaviFwk\actions\Ligne00Action($this, "Commande inconnue.");
        }

        return $action;
    }

    public function getMessageAction(array $message, string $touche): ?\MiniPaviFwk\actions\Action
    {
        // Handle user Message through introspection and overridable methods
        // Try all possibilities, in order
        $saisie = $message[0];
        $methods = [];
        if (preg_match('/^[A-Za-z0-9*#]+$/u', $saisie) == 1) {
            // Non-empty unicode string with AZaz09*# characters (specifically no ::, \\) to avoid security issues
            // * becomes ETOILE, # becomes DIESE. French word for "star" or * used in Minitel culture.
            $formatted_saisie = \MiniPaviFwk\strings\mb_ucfirst(mb_strtolower($saisie));
            $cleaned_saisie = str_replace(['*', '#'], ['ETOILE', 'DIESE'], $formatted_saisie);
            $formatted_touche = \MiniPaviFwk\strings\mb_ucfirst(mb_strtolower($touche));
            $methods[] = ['messageChoix' . $cleaned_saisie . $formatted_touche, []];
        }
        $methods[] = ['messageTouche' . \MiniPaviFwk\strings\mb_ucfirst(mb_strtolower($touche)), [$message]];
        $methods[] = ['message', [$touche, $message]];
        $methods[] = ['nonPropose', [$touche, $saisie]];

        foreach ($methods as $method) {
            DEBUG && trigger_error("getMessageAction - try method : " . $method[0] . "()");
            if (method_exists($this, $method[0])) {
                $method_name = $method[0];
                $method_params = $method[1];
                DEBUG && trigger_error("getMessageAction - CHOIX : " . $this::class . " -> " . $method_name . "()");
                $action = $this->$method_name(...$method_params);
                if (!is_null($action)) {
                    // Found an action!
                    break;
                }
            } else {
                DEBUG && trigger_error("getMessageAction - NONE : " . $this::class . " -> " . $method_name . "()");
            }
        }
        if (is_null($action)) {
            // Fallback : nonPropose(should always return an Action)
            DEBUG && trigger_error("Aucun choix n'a été trouvé");
            $action = new \MiniPaviFwk\actions\Ligne00Action($this, "Message non traité.");
        }
        return $action;
    }


    public function choix(string $touche, string $saisie): ?\MiniPaviFwk\actions\Action
    {
        // Overridable in sub-classes
        // Default : error (nonPropose)
        return null;
    }

    public function message(string $touche, array $message): ?\MiniPaviFwk\actions\Action
    {
        // Overridable in sub-classes
        // Default : error (nonPropose)
        return null;
    }

    public function nonPropose(string $touche, string $saisie): ?\MiniPaviFwk\actions\Action
    {
        // Overridable in sub-classes
        DEBUG && trigger_error("VideotexController : nonPropose()");
        return new \MiniPaviFwk\actions\Ligne00Action($this, "Choix invalide.");
    }

    public function keywords(string $touche, string $saisie): ?\MiniPaviFwk\actions\Action
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
