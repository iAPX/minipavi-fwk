<?php

namespace MiniPaviFwk\xml;

class ZonesaisieXml
{
    /**
     * Extracts and interprets zonesaisie from XML <zonesaisie> element
    */

    public static function zonesaisie(\SimpleXMLElement $page): \MiniPaviFwk\zonesaisie
    {
        $zonesaisie = $page->entree->zonesaisie;
        $ligne = (int) $zonesaisie['ligne'];
        $col = (int) $zonesaisie['col'];
        $longueur = (int) $zonesaisie['longueur'];
        $curseur = ((string) $zonesaisie['curseur']) === 'visible';

        return new \MiniPaviFwk\ZoneSaisie($ligne, $col, $longueur, $curseur);
    }
}