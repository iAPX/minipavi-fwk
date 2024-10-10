<?php

/**
 * Extracts and interprets zonesaisie from XML <zonesaisie> element
 */

namespace MiniPaviFwk\xml;

use MiniPaviFwk\cmd\ZoneMessageCmd;
use MiniPaviFwk\cmd\ZoneSaisieCmd;
use MiniPaviFwk\Validation;

class ZoneSaisieMessageCmdXml
{
    public static function createMiniPaviCmd(
        Validation $validation,
        \SimpleXMLElement $page
    ): array {
        $zonesaisie = $page->entree->zonesaisie;
        if (!empty($zonesaisie)) {
            $ligne = (int) $zonesaisie['ligne'];
            $col = (int) $zonesaisie['col'];
            $longueur = (int) $zonesaisie['longueur'];
            $curseur = ((string) $zonesaisie['curseur']) === 'visible';

            return ZoneSaisieCmd::createMiniPaviCmd(
                $validation,
                $ligne,
                $col,
                $longueur,
                $curseur
            );
        }

        $zonemessage = $page->entree->zonemessage;
        if (!empty($zonemessage)) {
            $ligne = (int) $zonemessage['ligne'];
            $col = 1;
            $longueur = 40;
            $hauteur = (int) $zonemessage['hauteur'];
            $curseur = ((string) $zonemessage['curseur']) === 'visible';

            return ZoneMessageCmd::createMiniPaviCmd(
                $validation,
                $ligne,
                $hauteur,
                $curseur,
                " ",
                '',
                $col,
                $longueur
            );
        }

        trigger_error("XML <zonesaisie> or <zonemessage> not found", E_USER_WARNING);
        return null;
    }
}
