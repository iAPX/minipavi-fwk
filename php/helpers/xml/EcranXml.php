<?php

/**
 * Provides ecran() output by parsing XMl <ecran> element
 */

namespace helpers\xml;

class EcranXml
{
    public static function ecran(\SimpleXMLElement $page): string
    {
        //// DEBUG && trigger_error("page: " . print_r($page, true));

        $ecran = $page->ecran;
        // DEBUG && trigger_error("ecran: " . print_r($ecran, true));

        $videotex = new \helpers\videotex\Videotex();

        foreach ($ecran->children() as $element) {
            // get element name and attributes
            $name = (string) $element->getName();
            DEBUG && trigger_error("element name: " . print_r($name, true));
            $attributes = $element->attributes();
            DEBUG && trigger_error("element attribute: " . print_r($attributes, true));

            $private_function_name = "element" . \helpers\strings\mb_ucfirst($name);
            if (method_exists(self::class, $private_function_name)) {
                // @TODO rewrite
                self::$private_function_name($videotex, ...$attributes);
            } else {
                DEBUG && trigger_error("Unhandled element: " . $name);
                $vdt = "Unhandled element: " . $name;
            }
        }

        return $videotex->getOutput();
    }


    private static function elementAffiche(\helpers\Videotex\Videotex $videotex, string $url): void
    {
        // Local file if it exists
        DEBUG && trigger_error("page url _element_affiche: " . $url);
        if (! empty(XML_PAGES_URL) && substr($url, 0, strlen(XML_PAGES_URL)) === XML_PAGES_URL) {
            $filename = "vdt/" . mb_substr($url, strlen(XML_PAGES_URL));
            DEBUG && trigger_error("page filename from url: " . $filename);
            if (file_exists($filename)) {
                $videotex->ecritVideotex(file_get_contents($filename));
                return;
            }
        }

        if (mb_substr($url, 0, 4) !== "http") {
            $filename = "vdt/" . $url;
            DEBUG && trigger_error("page filename from path: " . $filename);
            if (file_exists($filename)) {
                $videotex->ecritVideotex(file_get_contents($filename));
                return;
            }
        }

        // Fallabck using curl
        // @TODO adds error management
        DEBUG && trigger_error("Page downloaded from url: " . $url);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $videotex->ecritVideotex(curl_exec($ch));
        curl_close($ch);
    }

    private static function elementPosition(
        \helpers\Videotex\Videotex $videotex,
        string $ligne,
        string $col = "1"
    ): void {
        $videotex->position((int) $ligne, (int) $col);
    }

    private static function elementCurseur(\helpers\Videotex\Videotex $videotex, string $mode): void
    {
        // @TODO validation
        $mode === "visible" ? $videotex->curseurVisible() : $videotex->curseurInvisible();
    }

    private static function elementClignote(\helpers\Videotex\Videotex $videotex, string $mode): void
    {
        $mode === "actif" ? $videotex->curseurClignote() : $videotex->curseurFixe();
    }

    private static function elementSouligne(\helpers\Videotex\Videotex $videotex, string $mode): void
    {
        $mode === "actif" ? $videotex->souligneDebut() : $videotex->souligneFin();
    }

    private static function elementInversion(\helpers\Videotex\Videotex $videotex, string $mode): void
    {
        $mode === "actif" ? $videotex->inversionDebut() : $videotex->inversionFin();
    }

    private static function elementEcrit(\helpers\Videotex\Videotex $videotex, string $texte): void
    {
        $videotex->ecritUnicode($texte);
    }

    private static function elementCouleur(
        \helpers\Videotex\Videotex $videotex,
        string $texte = "",
        string $fond = ""
    ): void {
        !empty($texte) && $videotex->couleurTexte($texte);
        !empty($fond) && $videotex->couleurFond($fond);
    }

    private static function elementDoublehauteur(\helpers\Videotex\Videotex $videotex): void
    {
        $videotex->tailleDoubleHauteur();
    }

    private static function elementDoublelargeur(\helpers\Videotex\Videotex $videotex): void
    {
        $videotex->tailleDoubleLargeur();
    }

    private static function elementDoubletaille(\helpers\Videotex\Videotex $videotex): void
    {
        $videotex->tailleDouble();
    }

    private static function elementTaillenormale(\helpers\Videotex\Videotex $videotex): void
    {
        $videotex->tailleNormale();
    }

    private static function elementEffacefindeligne(\helpers\Videotex\Videotex $videotex): void
    {
        $videotex->effaceFinDeLigne();
    }

    private static function elementGraphique(\helpers\Videotex\Videotex $videotex): void
    {
        $videotex->modeGraphique();
    }

    private static function elementTexte(\helpers\Videotex\Videotex $videotex): void
    {
        $videotex->modeTexte();
    }

    private static function elementEfface(\helpers\Videotex\Videotex $videotex): void
    {
        $videotex->effaceEcran();
    }

    private static function elementDate(\helpers\Videotex\Videotex $videotex): void
    {
        $videotex->afficheDateParis();
    }

    private static function elementHeure(\helpers\Videotex\Videotex $videotex): void
    {
        $videotex->afficheHeureParis();
    }

    private static function elementRepete(
        \helpers\Videotex\Videotex $videotex,
        string $caractere,
        string $nombre
    ): void {
        $videotex->repeteCaractere($caractere, (int) $nombre);
    }

    private static function elementRectangle(
        string $ligne,
        string $col,
        string $largeur,
        string $hauteur,
        string $couleur
    ): void {
        $videotex->afficheRectangleInverse((int) $ligne, (int) $col, (int) $largeur, (int) $hauteur, $couleur);
    }
}
