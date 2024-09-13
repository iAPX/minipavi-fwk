<?php

namespace helpers\xml;

class ChoixXml
{
    public static function choix(
        \SimpleXMLElement $page,
        string $touche,
        string $saisie,
        array $context
    ): ?\helpers\actions\Action {
        DEBUG && trigger_error("ChoixXml : " . $saisie . " + " . $touche);

        if (mb_strtolower($saisie) == "d" && mb_strtoupper($touche) == "ENVOI") {
            return new \helpers\actions\DeconnexionAction();
        }

        foreach ($page->action->saisie as $option) {
            $option_touche = mb_strtoupper((string) $option['touche']);
            $option_saisie = mb_strtolower((string) $option['choix']);
            DEBUG && trigger_error("Option : " . $option_touche . " + " . $option_saisie);
            if ($option_touche === $touche && $option_saisie === mb_strtolower($saisie)) {
                $pagename = (string) $option['suivant'];
                DEBUG && trigger_error("ChoixXml : Page " . $pagename);
                return new \helpers\actions\PageAction($context, $pagename);
            }
        }

        return null;
    }
}
