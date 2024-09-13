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

    // Default behaviours
    public function ecran(): string
    {
        // Overriden in sub-classes
        return "*** Ecran absent. ***";
    }

    public function zonesaisie(): \MiniPaviFwk\ZoneSaisie
    {
        // Default, overriden in sub-classes
        return new \MiniPaviFwk\ZoneSaisie();
    }

    public function validation(): \MiniPaviFwk\Validation
    {
        // Default, overriden in sub-classes
        return new \MiniPaviFwk\Validation($this);
    }

    public function toucheRepetition(string $saisie): ?\MiniPaviFwk\actions\Action
    {
        // Default overridable in sub-classes
        return new \MiniPaviFwk\actions\RepetitionAction($this);
    }


    public function getAction(string $saisie, string $touche): ?\MiniPaviFwk\actions\Action
    {
        // Keywords are prioritary, if present
        DEBUG && trigger_error("get-Action : appel keywordHanlder->choix()");
        $action = $this->keywordHandler->choix($touche, $saisie);
        if (!is_null($action)) {
            return $action;
        }

        $methods = [];
        if ($saisie !== '') {
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
            DEBUG && trigger_error("getAction - try method : " . $method[0] . "()");
            if (method_exists($this, $method[0])) {
                $method_name = $method[0];
                $method_params = $method[1];
                DEBUG && trigger_error("getAction - CHOIX : " . $this::class . " -> " . $method_name . "()");
                $action = $this->$method_name(...$method_params);
                break;
            } else {
                DEBUG && trigger_error("getAction - PAS DE CHOIX : " . $this::class . " -> " . $method_name . "()");
            }
        }
        if (is_null($action)) {
            // @TODO make it a configurable string
            DEBUG && trigger_error("Aucun choix n'a été trouvé");
            $action = new \MiniPaviFwk\actions\Ligne00Action($this, "Commande inconnue.");
        }

        return $action;
    }


    public function choix(string $touche, string $saisie): ?\MiniPaviFwk\actions\Action
    {
        // Default : error (nonPropose)
        return null;
    }

    public function nonPropose(string $touche, string $saisie): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\Ligne00Action($this, "Choix invalide.");
    }

    public function keywords(string $touche, string $saisie): ?\MiniPaviFwk\actions\Action
    {
        return $this->keywordHandler->choix($touche, $saisie);
    }

    public function keywordsValidationKeys(): array
    {
        return $this->keywordHandler->validationKeys();
    }
}
