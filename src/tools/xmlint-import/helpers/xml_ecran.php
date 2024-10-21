<?php

/**
 * Import Ecran from XML
 */

$xml_ecran_conversion = [
    "affiche" => ["page", ["url" => ""]],
];

function xml_ecran(ControllerBuilder $controller, \SimpleXMLElement $ecran, string $pages_path): void
{
    $inner_code = "";
    foreach($ecran->children() as $element) {
        // get element name and attributes
        $name = (string) $element->getName();

        switch($name) {
            case "affiche":
                $url = (string) $element['url'];
                echo "url : ";
                print_r($url);
                echo "\n";
                if (substr($url, 0, strlen($pages_path)) !== $pages_path) {
                    // Fallback to file_get_contents
                    $inner_code .= "        \$videotex->ecritVideotex(file_get_contents('$url'));\n";
                } else {
                    $filepath = substr($url, strlen($pages_path));
                    $inner_code .= "        \$videotex->page('$filepath');\n";
                }
                break;
            case "position":
                $ligne = (string) $element['ligne'];
                $col = (string) $element['col'];
                $inner_code .= "        \$videotex->position($ligne, $col);\n";
                break;
            case "curseur":
                $visible = strtolower((string) $element['mode']);
                $method = $visible === 'visible' ? 'curseurVisible' : 'curseurInvisible';
                $inner_code .= "        \$videotex->$method();\n";
                break;
            case "clignote":
                $actif = strtolower((string) $element['mode']);
                $method = $actif === 'actif' ? 'texteClignote' : 'texteFixe';
                $inner_code .= "        \$videotex->$method();\n";
                break;
            case "souligne":
                $actif = strtolower((string) $element['mode']);
                $method = $actif === 'actif' ? 'souligneDebut' : 'souligneFin';
                $inner_code .= "        \$videotex->$method();\n";
                break;
            case "inversion":
                $actif = strtolower((string) $element['mode']);
                $method = $actif === 'actif' ? 'inversionDebut' : 'inversionFin';
                $inner_code .= "        \$videotex->$method();\n";
                break;
            case "ecrit":
                $texte = strtolower((string) $element['texte']);
                $clean_texte = str_replace(['\\', '"'], ['\\\\', '\\"'], $texte);
                $inner_code .= "        \$videotex->ecritUniCode(\"$clean_texte\");\n";
                break;
            case "couleur":
                $texte = strtolower((string) $element['texte']);
                $fond = strtolower((string) $element['fond']);
                if ($texte) {
                    $inner_code .= "        \$videotex->couleurTexte(\"$texte\");\n";
                }
                if ($fond) {
                    $inner_code .= "        \$videotex->couleurFond(\"$fond\");\n";
                }
                break;
            case "doublehauteur":
                $inner_code .= "        \$videotex->doubleHauteur();\n";
                break;
            case "doublelargeur":
                $inner_code .= "        \$videotex->doubleLargeur();\n";
                break;
            case "doubletaille":
                $inner_code .= "        \$videotex->doubleTaille();\n";
                break;
            case "taillenormale":
                $inner_code .= "        \$videotex->tailleNormale();\n";
                break;
            case "effacefindeligne":
                $inner_code .= "        \$videotex->effaceFinDeLigne();\n";
                break;
            case "graphique":
                $inner_code .= "        \$videotex->graphique();\n";
                break;
            case "texte":
                $inner_code .= "        \$videotex->texte();\n";
                break;
            case "efface":
                $inner_code .= "        \$videotex->effaceEcran();\n";
                break;
            case "date":
                $inner_code .= "        \$videotex->afficheDateParis();\n";
                break;
            case "time":
                $inner_code .= "        \$videotex->afficheHeureParis();\n";
                break;
            case "repete":
                $caractere = (string) $element['caractere'];
                $nombre = (string) $element['nombre'];
                $clean_caractere = str_replace(['\\', '"'], ['\\\\', '\\"'], $caractere);
                $inner_code .= "        \$videotex->repeteCaractere(\"$clean_caractere\", $nombre);\n";
                break;
            case "rectangle":
                $ligne = (string) $element['ligne'];
                $col = (string) $element['col'];
                $largeur = (string) $element['largeur'];
                $hauteur = (string) $element['hauteur'];
                $couleur = (string) $element['couleur'];
                $inner_code .= "        \$videotex->afficheRectangleInverse($ligne, $col, $largeur, $hauteur, \"$couleur\");\n";
                break;
            default:
                $inner_code .= "        // Element $name unsupported.\n";
        }
    }

    // We always add a ecran() function
    $code = <<<EOF
\n
    public function ecran(): string
    {
        \$videotex = new \MiniPaviFwk\helpers\VideotexHelper();

$inner_code

        return \$videotex->getOutput();
    }

EOF;
    $controller->createEcran($code);
}
