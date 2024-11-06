<?php

/**
 * Import Cmd from XML
 */

function xml_cmd(ControllerBuilder $controller, \SimpleXMLElement $xml_entree): void
{
    // Get ZoneSaisies, process the first encountered
    foreach ($xml_entree->zonesaisie as $element) {
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

        // Store into the controllerBuilder, and stop there!
        $controller->createCmd($code);
        return;
    }
}
