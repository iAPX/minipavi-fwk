<?php

/**
 * Videotex helpers to output Videotex code
 */

namespace helpers\videotex;

class Videotex
{
    /**
     * Videotex creation used both in EcranXml and VideotexController derived controllers->ecran()
     * Could also be used with OutputAction (see helpers/Actions.php)
     */


    // These colors are meant to make code usable without using magic constants.
    public const NOIR = 'noir';
    public const ROUGE = 'rouge';
    public const VERT = 'vert';
    public const JAUNE = 'jaune';
    public const BLEU = 'bleu';
    public const MAGENTA = 'magenta';
    public const CYAN = 'cyan';
    public const BLANC = 'blanc';

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

    public function page($pagename): \helpers\videotex\Videotex
    {
        $filename = "vdt/" . $pagename . ".vdt";
        if (file_exists($filename)) {
            $this->output .= file_get_contents($filename);
        }
        return $this;
    }

    public function position(int $ligne, int $col = 1): \helpers\videotex\Videotex
    {
        $this->output .= \MiniPavi\MiniPaviCli::setPos($col, $ligne);
        return $this;
    }

    public function curseurVisible(): \helpers\videotex\Videotex
    {
        $this->output .= VDT_CURON;
        return $this;
    }

    public function curseurInvisible(): \helpers\videotex\Videotex
    {
        $this->output .= VDT_CUROFF;
        return $this;
    }

    public function curseurClignote(): \helpers\videotex\Videotex
    {
        $this->output .= VDT_BLINK;
        return $this;
    }

    public function curseurFixe(): \helpers\videotex\Videotex
    {
        $this->output .= VDT_FIXED;
        return $this;
    }

    public function souligneDebut(): \helpers\videotex\Videotex
    {
        $this->output .= VDT_STARTUNDERLINE;
        return $this;
    }

    public function souligneFin(): \helpers\videotex\Videotex
    {
        $this->output .= VDT_STOPUNDERLINE;
        return $this;
    }

    public function inversionDebut(): \helpers\videotex\Videotex
    {
        $this->output .= VDT_FDINV;
        return $this;
    }

    public function inversionFin(): \helpers\videotex\Videotex
    {
        $this->output .= VDT_FDNORM;
        return $this;
    }

    public function ecritUnicode(string $unicodeTexte): \helpers\videotex\Videotex
    {
        $this->output .= \minipavi\MiniPaviCli::toG2($unicodeTexte);
        return $this;
    }

    public function ecritVideotex(string $videotexTexte): \helpers\videotex\Videotex
    {
        $this->output .= $videotexTexte;
        return $this;
    }


    public function couleurTexte(string $couleur): \helpers\videotex\Videotex
    {
        $this->output .= "\x1B" . chr(64 + self::COULEURS_VALUES[mb_strtolower($couleur)]);
        return $this;
    }

    public function couleurFond(string $couleur): \helpers\videotex\Videotex
    {
        $this->output .= "\x1B" . chr(80 + self::COULEURS_VALUES[mb_strtolower($couleur)]);
        return $this;
    }

    public function tailleDoubleHauteur(): \helpers\videotex\Videotex
    {
        $this->output .= VDT_SZDBLH;
        return $this;
    }

    public function tailleDoubleLargeur(): \helpers\videotex\Videotex
    {
        $this->output .= VDT_SZDBLW;
        return $this;
    }

    public function tailleDouble(): \helpers\videotex\Videotex
    {
        $this->output .= VDT_SZDBLHW;
        return $this;
    }

    public function tailleNormale(): \helpers\videotex\Videotex
    {
        $this->output .= VDT_SZNORM;
        return $this;
    }

    public function effaceFinDeLigne(): \helpers\videotex\Videotex
    {
        $this->output .= VDT_CLRLN;
        return $this;
    }

    public function modeGraphique(): \helpers\videotex\Videotex
    {
        $this->output .= VDT_G0;
        return $this;
    }

    public function modeTexte(): \helpers\videotex\Videotex
    {
        $this->output .= VDT_G1;
        return $this;
    }

    public function effaceEcran(): \helpers\videotex\Videotex
    {
        $this->output .= VDT_CLR;
        return $this;
    }

    public function afficheDateParis(): \helpers\videotex\Videotex
    {
        $parisTime = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $this->output .= $parisTime->format('d-m-Y');
        return $this;
    }

    public function afficheHeureParis(): \helpers\videotex\Videotex
    {
        $parisTime = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $this->output .= $parisTime->format('H:i');
        return $this;
    }

    public function repeteCaractere(string $caractere, int $nombre): \helpers\videotex\Videotex
    {
        $this->output .= \MiniPavi\MiniPaviCli::repeatChar($caractere, $nombre);
        return $this;
    }

    public function afficheRectangleInverse(
        int $ligne,
        int $col,
        int $largeur,
        int $hauteur,
        string $couleur
    ): \helpers\Videotex\Videotex {
        for ($dy = 0; $dy < $hauteur; $dy++) {
            $this
            ->position($ligne + dy, $col)
            ->couleurTexte($couleur)
            ->inversionDebut()
            ->repeteCaractere(' ', $largeur);
        }
        return $this;
    }
}
