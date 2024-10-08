<?php

/**
 * Handles XML <page><action><saisie> for XmlController
 */

namespace MiniPaviFwk\xml;

class ChoixXml
{
    public static function choix(
        \SimpleXMLElement $page,
        string $touche,
        string $saisie,
        array $context
    ): ?\MiniPaviFwk\actions\Action {
        trigger_error("ChoixXml : " . $saisie . " + " . $touche, E_USER_NOTICE);
        foreach ($page->action->saisie as $option) {
            $option_touche = mb_strtoupper((string) $option['touche']);
            $option_saisie = mb_strtolower((string) $option['choix']);
            trigger_error("Option : " . $option_saisie . " + " . $option_touche, E_USER_NOTICE);
            if ($option_touche === $touche && $option_saisie === mb_strtolower($saisie)) {
                $pagename = (string) $option['suivant'];
                trigger_error("ChoixXml : Page " . $pagename, E_USER_NOTICE);
                return new \MiniPaviFwk\actions\PageAction($context, $pagename);
            }
        }

        trigger_error("ChoixXml : aucun choix correspondant", E_USER_NOTICE);
        return null;
    }
}
