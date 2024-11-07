<?php

/**
 * Import Cmd from XML
 */

function xml_cmd(ControllerBuilder $controller, \SimpleXMLElement $xml_entree): void
{
    if ($xml_entree->zonesaisie) {
        // Get ZoneSaisies, process the first encountered
        $element = $xml_entree->zonesaisie[0];
        $ligne = (string) $element['ligne'];
        $col = (string) $element['col'];
        $longueur = (string) $element['longueur'];
        $curseur = ((string) $element['curseur']) === 'visible';

        // Convert to Code
        $curseur_bool = $curseur ? 'true' : 'false';
        $code = <<<EOF
\n
    public function getCmd(): array
    {
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd(
            \$this->validation(),
            $ligne,
            $col,
            $longueur,
            $curseur_bool
        );
    }
EOF;
    } else {
        // Get ZoneMessages, process the first encountered
        $element = $xml_entree->zonemessage[0];
        $ligne = (string) $element['ligne'];
        $hauteur = (string) $element['hauteur'];
        $curseur = ((string) $element['curseur']) === 'visible';

        // Convert to Code
        $curseur_bool = $curseur ? 'true' : 'false';
        $code = <<<EOF
\n
    public function getCmd(): array
    {
        return \MiniPaviFwk\cmd\ZoneMessageCmd::createMiniPaviCmd(
            \$this->validation(),
            $ligne,
            $hauteur,
            $curseur_bool
        );
    }
EOF;
    }

    // Store into the controllerBuilder, and stop there!
    $controller->createCmd($code);
}
