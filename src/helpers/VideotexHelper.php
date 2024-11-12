<?php

/**
 * Videotex helpers to output Videotex
 *
 * Used by VideotexControllers
 */

namespace MiniPaviFwk\helpers;

use MiniPavi\MiniPaviCli;

class VideotexHelper
{
    // These colors are meant to make code usable without using magic constants.
    public const NOIR = 'noir';
    public const ROUGE = 'rouge';
    public const VERT = 'vert';
    public const JAUNE = 'jaune';
    public const BLEU = 'bleu';
    public const MAGENTA = 'magenta';
    public const CYAN = 'cyan';
    public const BLANC = 'blanc';

    // Notice that colours are not classed by luminance value!
    // RGB : Red bit 0, Green bit 1, Blue bit 2
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

    private string $output = '';

    public function getOutput(): string
    {
        return $this->output;
    }

    public function page(string $pagename): VideotexHelper
    {
        // Serve pages with explicit extension explicit or .vdt omitted
        $filename = SERVICE_DIR . "vdt/" . $pagename;
        if (file_exists($filename)) {
            $this->output .= file_get_contents($filename);
        } elseif (file_exists($filename . ".vdt")) {
            $this->output .= file_get_contents($filename . ".vdt");
        }
        return $this;
    }

    public function position(int $ligne, int $col = 1): VideotexHelper
    {
        $this->output .= MiniPaviCli::setPos($col, $ligne);
        return $this;
    }

    public function curseurVisible(): VideotexHelper
    {
        $this->output .= VDT_CURON;
        return $this;
    }

    public function curseurInvisible(): VideotexHelper
    {
        $this->output .= VDT_CUROFF;
        return $this;
    }

    public function texteClignote(): VideotexHelper
    {
        $this->output .= VDT_BLINK;
        return $this;
    }

    public function texteFixe(): VideotexHelper
    {
        $this->output .= VDT_FIXED;
        return $this;
    }

    public function souligneDebut(): VideotexHelper
    {
        $this->output .= VDT_STARTUNDERLINE;
        return $this;
    }

    public function souligneFin(): VideotexHelper
    {
        $this->output .= VDT_STOPUNDERLINE;
        return $this;
    }

    public function inversionDebut(): VideotexHelper
    {
        $this->output .= VDT_FDINV;
        return $this;
    }

    public function inversionFin(): VideotexHelper
    {
        $this->output .= VDT_FDNORM;
        return $this;
    }

    public function ecritUnicode(string $unicodeTexte): VideotexHelper
    {
        $this->output .= MiniPaviCli::toG2($unicodeTexte);
        return $this;
    }

    public function ecritVideotex(string $videotexTexte): VideotexHelper
    {
        $this->output .= $videotexTexte;
        return $this;
    }


    public function couleurTexte(string $couleur): VideotexHelper
    {
        $this->output .= "\x1B" . chr(64 + self::COULEURS_VALUES[mb_strtolower($couleur)]);
        return $this;
    }

    public function couleurFond(string $couleur): VideotexHelper
    {
        $this->output .= "\x1B" . chr(80 + self::COULEURS_VALUES[mb_strtolower($couleur)]);
        return $this;
    }

    public function doubleTaille(): VideotexHelper
    {
        $this->output .= VDT_SZDBLHW;
        return $this;
    }

    public function doubleHauteur(): VideotexHelper
    {
        $this->output .= VDT_SZDBLH;
        return $this;
    }

    public function doubleLargeur(): VideotexHelper
    {
        $this->output .= VDT_SZDBLW;
        return $this;
    }

    public function tailleNormale(): VideotexHelper
    {
        $this->output .= VDT_SZNORM;
        return $this;
    }

    public function effaceFinDeLigne(): VideotexHelper
    {
        $this->output .= VDT_CLRLN;
        return $this;
    }

    public function effaceZone(int $ligne, int $hauteur): VideotexHelper
    {
        $this->position($ligne, 1);
        $this->modeGraphique();

        // Now use semigraphique spaces to clear without side-effect
        $nb_cars = ($hauteur * 40) - 1;
        $this->output .= ' ';
        while ($nb_cars > 0) {
            $repetitions = ($nb_cars > 63) ? 63 : $nb_cars;
            $this->output .= VDT_REP . chr(64 + $repetitions);
            $nb_cars -= $repetitions;
        }

        $this->position($ligne, 1);
        return $this;
    }

    public function modeGraphique(): VideotexHelper
    {
        $this->output .= VDT_G1;
        return $this;
    }

    public function modeTexte(): VideotexHelper
    {
        $this->output .= VDT_G0;
        return $this;
    }

    public function effaceEcran(): VideotexHelper
    {
        $this->output .= VDT_CLR;
        return $this;
    }

    public function afficheDateParis(): VideotexHelper
    {
        $parisTime = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        $this->output .= $parisTime->format('d-m-Y');
        return $this;
    }

    public function afficheHeureParis(): VideotexHelper
    {
        $parisTime = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        $this->output .= $parisTime->format('H:i');
        return $this;
    }

    public function repeteCaractere(string $caractere, int $nombre): VideotexHelper
    {
        $this->output .= MiniPaviCli::repeatChar($caractere, $nombre);
        return $this;
    }

    public function afficheRectangleInverse(
        int $ligne,
        int $col,
        int $largeur,
        int $hauteur,
        string $couleur
    ): VideotexHelper {
        // Optimized version.
        $this
        ->position($ligne, $col)
        ->couleurTexte($couleur)
        ->inversionDebut()
        ->repeteCaractere(' ', $largeur);
        for ($dy = 1; $dy < $hauteur; $dy++) {
            $this
            ->position($ligne + $dy, $col)
            ->couleurTexte($couleur)
            ->inversionDebut()
            ->repeteCaractere('', $largeur + 1);
        }
        return $this;
    }

    public function effaceLigne00(): VideotexHelper
    {
        $this->output .= MiniPaviCli::writeLine0('');
        return $this;
    }

    public function ecritUnicodeLigne00(string $unicodeTexte): VideotexHelper
    {
        $this->output .= MiniPaviCli::writeLine0(MiniPaviCli::toG2($unicodeTexte));
        return $this;
    }

    public function ecritPIN(): VideotexHelper
    {
        $this->output .= substr(MiniPaviCli::$uniqueId, -4);
        return $this;
    }

    public function webMedia(string $type, string $url): VideotexHelper
    {
        // Workaround a little bug in the MiniPavi.
        $webmedia_code = "\x14#D" . strtoupper($type) . ':' . $url . "\x14#F";
        $this->output .= "\x1F\x40\x68" . $webmedia_code .  " \x0A";
        return $this;
    }

    public function deconnexionModem(): VideotexHelper
    {
        // The well known ESc 9 g that ask the terminal to hang up!
        $this->output .= "\x1B9g";
        return $this;
    }
}
