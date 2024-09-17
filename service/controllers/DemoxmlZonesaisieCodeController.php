<?php

/**
 * Sommaire de la démo du service
 *
 * Exemple utilisant les Touche*() et Choix() ainsi que la page affichée par Videotex
 */

namespace service\controllers;

class DemoxmlZonesaisieCodeController extends \MiniPaviFwk\controllers\XmlController
{
    public function ecran(): string
    {
        $vdt = parent::ecran();

        $videotex = new \MiniPaviFwk\videotex\Videotex();
        $vdt .= $videotex
        ->position(23, 1)->effaceFinDeLigne()->couleurFond("vert")->couleurTexte('noir')->ecritUnicode(" " . end(explode('\\', $this::class)))
        ->getOutput();

        return $vdt;
    }

    public function zonesaisie(): \MiniPaviFwk\ZoneSaisie
    {
        // Overload 
        return new \MiniPaviFwk\ZoneSaisie(8, 38, 1, true);
    }
    
}
