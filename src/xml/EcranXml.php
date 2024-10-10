<?php

/**
 * Provides ecran() output by parsing XMl <ecran> element
 *
 * Extensively use Videotex object.
 */

namespace MiniPaviFwk\xml;

use MiniPaviFwk\helpers\VideotexHelper;

class EcranXml
{
    public static function ecran(\SimpleXMLElement $page): string
    {
        $ecran = $page->ecran;
        $videotex = new VideotexHelper();
        foreach ($ecran->children() as $element) {
            // get element name and attributes
            $name = (string) $element->getName();
            trigger_error("element name: " . print_r($name, true), E_USER_NOTICE);
            $attributes = $element->attributes();
            trigger_error("element attribute: " . print_r($attributes, true), E_USER_NOTICE);

            $private_function_name = "element" . \MiniPaviFwk\helpers\mb_ucfirst($name);
            if (method_exists(static::class, $private_function_name)) {
                static::$private_function_name($videotex, ...$attributes);
            } else {
                trigger_error("Unhandled element: " . $name, E_USER_WARNING);
                $vdt = "Unhandled element: " . $name;
            }
        }

        return $videotex->getOutput();
    }


    private static function elementAffiche(VideotexHelper $videotex, string $url): void
    {
        // Local file if it exists
        trigger_error("page url _element_affiche: " . $url, E_USER_NOTICE);
        if (
            !empty(\service\XML_PAGES_URL)
            && substr($url, 0, strlen(\service\XML_PAGES_URL)) === \service\XML_PAGES_URL
        ) {
            $filename = SERVICE_DIR . "vdt/" . mb_substr($url, strlen(\service\XML_PAGES_URL));
            trigger_error("page filename from url: " . $filename, E_USER_NOTICE);
            if (file_exists($filename)) {
                $videotex->ecritVideotex(file_get_contents($filename));
                return;
            }
        }

        if (mb_substr($url, 0, 4) !== "http") {
            $filename = SERVICE_DIR . "vdt/" . $url;
            trigger_error("page filename from path: " . $filename, E_USER_NOTICE);
            if (file_exists($filename)) {
                $videotex->ecritVideotex(file_get_contents($filename));
                return;
            }
        }

        // Fallabck using curl
        // @TODO adds error management
        trigger_error("Page downloaded from url: " . $url, E_USER_NOTICE);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $videotex->ecritVideotex(curl_exec($ch));
        curl_close($ch);
    }

    private static function elementPosition(
        VideotexHelper $videotex,
        string $ligne,
        string $col = "1"
    ): void {
        $videotex->position((int) $ligne, (int) $col);
    }

    private static function elementCurseur(VideotexHelper $videotex, string $mode): void
    {
        $mode === "visible" ? $videotex->curseurVisible() : $videotex->curseurInvisible();
    }

    private static function elementClignote(VideotexHelper $videotex, string $mode): void
    {
        $mode === "actif" ? $videotex->texteClignote() : $videotex->texteFixe();
    }

    private static function elementSouligne(VideotexHelper $videotex, string $mode): void
    {
        $mode === "actif" ? $videotex->souligneDebut() : $videotex->souligneFin();
    }

    private static function elementInversion(VideotexHelper $videotex, string $mode): void
    {
        $mode === "actif" ? $videotex->inversionDebut() : $videotex->inversionFin();
    }

    private static function elementEcrit(VideotexHelper $videotex, string $texte): void
    {
        $videotex->ecritUnicode($texte);
    }

    private static function elementCouleur(
        VideotexHelper $videotex,
        string $texte = "",
        string $fond = ""
    ): void {
        !empty($texte) && $videotex->couleurTexte($texte);
        !empty($fond) && $videotex->couleurFond($fond);
    }

    private static function elementDoublehauteur(VideotexHelper $videotex): void
    {
        $videotex->doubleHauteur();
    }

    private static function elementDoublelargeur(VideotexHelper $videotex): void
    {
        $videotex->doubleLargeur();
    }

    private static function elementDoubletaille(VideotexHelper $videotex): void
    {
        $videotex->doubleTaille();
    }

    private static function elementTaillenormale(VideotexHelper $videotex): void
    {
        $videotex->tailleNormale();
    }

    private static function elementEffacefindeligne(VideotexHelper $videotex): void
    {
        $videotex->effaceFinDeLigne();
    }

    private static function elementGraphique(VideotexHelper $videotex): void
    {
        $videotex->modeGraphique();
    }

    private static function elementTexte(VideotexHelper $videotex): void
    {
        $videotex->modeTexte();
    }

    private static function elementEfface(VideotexHelper $videotex): void
    {
        $videotex->effaceEcran();
    }

    private static function elementDate(VideotexHelper $videotex): void
    {
        $videotex->afficheDateParis();
    }

    private static function elementHeure(VideotexHelper $videotex): void
    {
        $videotex->afficheHeureParis();
    }

    private static function elementRepete(
        VideotexHelper $videotex,
        string $caractere,
        string $nombre
    ): void {
        $videotex->repeteCaractere($caractere, (int) $nombre);
    }

    private static function elementRectangle(
        VideotexHelper $videotex,
        string $ligne,
        string $col,
        string $largeur,
        string $hauteur,
        string $couleur
    ): void {
        $videotex->afficheRectangleInverse((int) $ligne, (int) $col, (int) $largeur, (int) $hauteur, $couleur);
    }
}
