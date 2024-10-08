<?php

/**
 * Demo the zonesaisie to parametrize the input area,
 * in this case overload the zone defined in the XML and display it's own zone
 */

namespace service\controllers;

class DemoxmlZonesaisieCodeController extends \MiniPaviFwk\controllers\XmlController
{
    public function ecran(): string
    {
        $vdt = parent::ecran();

        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $vdt .= $videotex
        ->position(23, 1)->effaceFinDeLigne()->couleurFond("vert")->couleurTexte('noir')
        ->ecritUnicode(" " . end(explode('\\', $this::class)))
        ->getOutput();

        return $vdt;
    }

    public function getCmd(): array
    {
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd($this->validation(), 8, 38, 1, true);
    }
}
