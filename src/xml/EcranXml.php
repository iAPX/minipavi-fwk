<?php

/**
 * Provides ecran() output by parsing XMl <ecran> element
 *
 * Extensively use Videotex object.
 */

namespace MiniPaviFwk\xml;

class EcranXml
{
    public static function ecran(\SimpleXMLElement $page): string
    {
        $ecran = $page->ecran;
        $videotex = new \MiniPaviFwk\videotex\Videotex();
        foreach ($ecran->children() as $element) {
            // get element name and attributes
            $name = (string) $element->getName();
            DEBUG && trigger_error("element name: " . print_r($name, true));
            $attributes = $element->attributes();
            DEBUG && trigger_error("element attribute: " . print_r($attributes, true));

            $private_function_name = "element" . \MiniPaviFwk\strings\mb_ucfirst($name);
            if (method_exists(static::class, $private_function_name)) {
                static::$private_function_name($videotex, ...$attributes);
            } else {
                DEBUG && trigger_error("Unhandled element: " . $name);
                $vdt = "Unhandled element: " . $name;
            }
        }

        return $videotex->getOutput();
    }


    private static function elementAffiche(\MiniPaviFwk\Videotex\Videotex $videotex, string $url): void
    {
        // Local file if it exists
        DEBUG && trigger_error("page url _element_affiche: " . $url);
        if (! empty(XML_PAGES_URL) && substr($url, 0, strlen(XML_PAGES_URL)) === XML_PAGES_URL) {
            $filename = SERVICE_DIR . "vdt/" . mb_substr($url, strlen(XML_PAGES_URL));
            DEBUG && trigger_error("page filename from url: " . $filename);
            if (file_exists($filename)) {
                $videotex->ecritVideotex(file_get_contents($filename));
                return;
            }
        }

        if (mb_substr($url, 0, 4) !== "http") {
            $filename = SERVICE_DIR . "vdt/" . $url;
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
        \MiniPaviFwk\Videotex\Videotex $videotex,
        string $ligne,
        string $col = "1"
    ): void {
        $videotex->position((int) $ligne, (int) $col);
    }

    private static function elementCurseur(\MiniPaviFwk\Videotex\Videotex $videotex, string $mode): void
    {
        $mode === "visible" ? $videotex->curseurVisible() : $videotex->curseurInvisible();
    }

    private static function elementClignote(\MiniPaviFwk\Videotex\Videotex $videotex, string $mode): void
    {
        $mode === "actif" ? $videotex->texteClignote() : $videotex->texteFixe();
    }

    private static function elementSouligne(\MiniPaviFwk\Videotex\Videotex $videotex, string $mode): void
    {
        $mode === "actif" ? $videotex->souligneDebut() : $videotex->souligneFin();
    }

    private static function elementInversion(\MiniPaviFwk\Videotex\Videotex $videotex, string $mode): void
    {
        $mode === "actif" ? $videotex->inversionDebut() : $videotex->inversionFin();
    }

    private static function elementEcrit(\MiniPaviFwk\Videotex\Videotex $videotex, string $texte): void
    {
        $videotex->ecritUnicode($texte);
    }

    private static function elementCouleur(
        \MiniPaviFwk\Videotex\Videotex $videotex,
        string $texte = "",
        string $fond = ""
    ): void {
        !empty($texte) && $videotex->couleurTexte($texte);
        !empty($fond) && $videotex->couleurFond($fond);
    }

    private static function elementDoublehauteur(\MiniPaviFwk\Videotex\Videotex $videotex): void
    {
        $videotex->doubleHauteur();
    }

    private static function elementDoublelargeur(\MiniPaviFwk\Videotex\Videotex $videotex): void
    {
        $videotex->doubleLargeur();
    }

    private static function elementDoubletaille(\MiniPaviFwk\Videotex\Videotex $videotex): void
    {
        $videotex->doubleTaille();
    }

    private static function elementTaillenormale(\MiniPaviFwk\Videotex\Videotex $videotex): void
    {
        $videotex->tailleNormale();
    }

    private static function elementEffacefindeligne(\MiniPaviFwk\Videotex\Videotex $videotex): void
    {
        $videotex->effaceFinDeLigne();
    }

    private static function elementGraphique(\MiniPaviFwk\Videotex\Videotex $videotex): void
    {
        $videotex->modeGraphique();
    }

    private static function elementTexte(\MiniPaviFwk\Videotex\Videotex $videotex): void
    {
        $videotex->modeTexte();
    }

    private static function elementEfface(\MiniPaviFwk\Videotex\Videotex $videotex): void
    {
        $videotex->effaceEcran();
    }

    private static function elementDate(\MiniPaviFwk\Videotex\Videotex $videotex): void
    {
        $videotex->afficheDateParis();
    }

    private static function elementHeure(\MiniPaviFwk\Videotex\Videotex $videotex): void
    {
        $videotex->afficheHeureParis();
    }

    private static function elementRepete(
        \MiniPaviFwk\Videotex\Videotex $videotex,
        string $caractere,
        string $nombre
    ): void {
        $videotex->repeteCaractere($caractere, (int) $nombre);
    }

    private static function elementRectangle(
        \MiniPaviFwk\Videotex\Videotex $videotex,
        string $ligne,
        string $col,
        string $largeur,
        string $hauteur,
        string $couleur
    ): void {
        $videotex->afficheRectangleInverse((int) $ligne, (int) $col, (int) $largeur, (int) $hauteur, $couleur);
    }
}
