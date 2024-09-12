<?php

/**
 * Provides ecran() output by parsing XMl <ecran> element
 */

namespace helpers\xml;

class EcranXml
{
    private const COULEURS_VALUES = [
        'noir' => 0,
        'rouge' => 1,
        'vert' => 2,
        'jaune' => 3,
        'bleu' => 4,
        'magenta' => 5,
        'cyan' => 6,
        'blanc' => 7,
    ];

    public static function ecran(\SimpleXMLElement $page): string
    {
        //// DEBUG && trigger_error("page: " . print_r($page, true));

        $ecran = $page->ecran;
        // DEBUG && trigger_error("ecran: " . print_r($ecran, true));

        $vdt = "";
        foreach ($ecran->children() as $element) {
            // get element name and attributes
            $name = (string) $element->getName();
            DEBUG && trigger_error("element name: " . print_r($name, true));
            $attributes = $element->attributes();
            DEBUG && trigger_error("element attribute: " . print_r($attributes, true));

            $private_function_name = "element" . ucfirst($name);
            if (method_exists(self::class, $private_function_name)) {
                $vdt .= self::$private_function_name(...$attributes);
            } else {
                DEBUG && trigger_error("Unhandled element: " . $name);
                $vdt .= "Unhandled element: " . $name;
            }
        }

        return $vdt;
    }


    private static function elementAffiche(string $url): string
    {
        // Local file if it exists
        DEBUG && trigger_error("page url _element_affiche: " . $url);
        if (! empty(XML_PAGES_URL) && substr($url, 0, strlen(XML_PAGES_URL)) === XML_PAGES_URL) {
            $filename = "vdt/" . substr($url, strlen(XML_PAGES_URL));
            DEBUG && trigger_error("page filename from url: " . $filename);
            if (file_exists($filename)) {
                return file_get_contents($filename);
            }
        }

        if (substr($url, 0, 4) !== "http") {
            $filename = "vdt/" . $url;
            DEBUG && trigger_error("page filename from path: " . $filename);
            if (file_exists($filename)) {
                return file_get_contents($filename);
            }
        }

        // Fallabck using curl
        // @TODO adds error management
        DEBUG && trigger_error("Page downloaded from url: " . $url);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $vdt = curl_exec($ch);
        curl_close($ch);

        return $vdt;
    }

    private static function elementPosition(string $ligne, string $col = "1"): string
    {
        return \MiniPavi\MiniPaviCli::setPos((int) $col, (int) $ligne);
    }

    private static function elementCurseur(string $mode): string
    {
        // @TODO validation
        return $mode === "visible" ? VDT_CURON : VDT_CUROFF;
    }

    private static function elementClignote(string $mode): string
    {
        return $mode === "actif" ? VDT_BLINK : VDT_FIXED;
    }

    private static function elementSouligne(string $mode): string
    {
        return $mode === "actif" ? VDT_STARTUNDERLINE : VDT_STOPUNDERLINE;
    }

    private static function elementInversion(string $mode): string
    {
        return $mode === "actif" ? VDT_FDINV : VDT_FDNORM;
    }

    private static function elementEcrit(string $texte): string
    {
        return \minipavi\MiniPaviCli::toG2($texte);
    }

    private static function elementCouleur(string $texte = "", string $fond = ""): string
    {
        $foreground = empty($texte) ? "" : "\x1B" . chr(64 + self::COULEURS_VALUES[$texte]);
        $background = empty($fond) ? "" : "\x1B" . chr(80 + self::COULEURS_VALUES[$fond]);
        return $foreground . $background;
    }

    private static function elementDoublehauteur(): string
    {
        return VDT_SZDBLH;
    }

    private static function elementDoublelargeur(): string
    {
        return VDT_SZDBLW;
    }

    private static function elementDoubletaille(): string
    {
        return VDT_SZDBLHW;
    }

    private static function elementTaillenormale(): string
    {
        return VDT_SZNORM;
    }

    private static function elementEffacefindeligne(): string
    {
        return VDT_CLRLN;
    }

    private static function elementGraphique(): string
    {
        return VDT_G0;
    }

    private static function elementTexte(): string
    {
        return VDT_G1;
    }

    private static function elementEfface(): string
    {
        return VDT_CLR;
    }

    private static function elementDate(): string
    {
        // @TODO use Paris timezone
        return "";
    }

    private static function elementHeure(): string
    {
        // @TODO use Paris timezone
        return "";
    }

    private static function elementRepete(string $caractere, string $nombre): string
    {
        return \MiniPavi\MiniPaviCli::repeatChar($caractere, (int) $nombre);
    }

    private static function elementRectangle(
        string $ligne,
        string $col,
        string $largeur,
        string $hauteur,
        string $couleur
    ): string {
        // @TODO use _element_ functions
        return "";
    }
}
