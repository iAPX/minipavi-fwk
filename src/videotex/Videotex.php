<?php

/**
 * Videotex helpers to output Videotex code
 */

namespace MiniPaviFwk\videotex;

class Videotex
{
    /**
     * Videotex creation used both in EcranXml and VideotexController derived controllers->ecran()
     * Could also be used with OutputAction (see MiniPaviFwk/Actions.php)
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

    public function page($pagename): \MiniPaviFwk\videotex\Videotex
    {
        $filename = "vdt/" . $pagename . ".vdt";
        if (file_exists($filename)) {
            $this->output .= file_get_contents($filename);
        }
        return $this;
    }

    public function position(int $ligne, int $col = 1): \MiniPaviFwk\videotex\Videotex
    {
        $this->output .= \MiniPavi\MiniPaviCli::setPos($col, $ligne);
        return $this;
    }

    public function curseurVisible(): \MiniPaviFwk\videotex\Videotex
    {
        $this->output .= VDT_CURON;
        return $this;
    }

    public function curseurInvisible(): \MiniPaviFwk\videotex\Videotex
    {
        $this->output .= VDT_CUROFF;
        return $this;
    }

    public function curseurClignote(): \MiniPaviFwk\videotex\Videotex
    {
        $this->output .= VDT_BLINK;
        return $this;
    }

    public function curseurFixe(): \MiniPaviFwk\videotex\Videotex
    {
        $this->output .= VDT_FIXED;
        return $this;
    }

    public function souligneDebut(): \MiniPaviFwk\videotex\Videotex
    {
        $this->output .= VDT_STARTUNDERLINE;
        return $this;
    }

    public function souligneFin(): \MiniPaviFwk\videotex\Videotex
    {
        $this->output .= VDT_STOPUNDERLINE;
        return $this;
    }

    public function inversionDebut(): \MiniPaviFwk\videotex\Videotex
    {
        $this->output .= VDT_FDINV;
        return $this;
    }

    public function inversionFin(): \MiniPaviFwk\videotex\Videotex
    {
        $this->output .= VDT_FDNORM;
        return $this;
    }

    public function ecritUnicode(string $unicodeTexte): \MiniPaviFwk\videotex\Videotex
    {
        $this->output .= \minipavi\MiniPaviCli::toG2($unicodeTexte);
        return $this;
    }

    public function ecritVideotex(string $videotexTexte): \MiniPaviFwk\videotex\Videotex
    {
        $this->output .= $videotexTexte;
        return $this;
    }


    public function couleurTexte(string $couleur): \MiniPaviFwk\videotex\Videotex
    {
        $this->output .= "\x1B" . chr(64 + self::COULEURS_VALUES[mb_strtolower($couleur)]);
        return $this;
    }

    public function couleurFond(string $couleur): \MiniPaviFwk\videotex\Videotex
    {
        $this->output .= "\x1B" . chr(80 + self::COULEURS_VALUES[mb_strtolower($couleur)]);
        return $this;
    }

    public function tailleDoubleHauteur(): \MiniPaviFwk\videotex\Videotex
    {
        $this->output .= VDT_SZDBLH;
        return $this;
    }

    public function tailleDoubleLargeur(): \MiniPaviFwk\videotex\Videotex
    {
        $this->output .= VDT_SZDBLW;
        return $this;
    }

    public function tailleDouble(): \MiniPaviFwk\videotex\Videotex
    {
        $this->output .= VDT_SZDBLHW;
        return $this;
    }

    public function tailleNormale(): \MiniPaviFwk\videotex\Videotex
    {
        $this->output .= VDT_SZNORM;
        return $this;
    }

    public function effaceFinDeLigne(): \MiniPaviFwk\videotex\Videotex
    {
        $this->output .= VDT_CLRLN;
        return $this;
    }

    public function modeGraphique(): \MiniPaviFwk\videotex\Videotex
    {
        $this->output .= VDT_G0;
        return $this;
    }

    public function modeTexte(): \MiniPaviFwk\videotex\Videotex
    {
        $this->output .= VDT_G1;
        return $this;
    }

    public function effaceEcran(): \MiniPaviFwk\videotex\Videotex
    {
        $this->output .= VDT_CLR;
        return $this;
    }

    public function afficheDateParis(): \MiniPaviFwk\videotex\Videotex
    {
        $parisTime = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $this->output .= $parisTime->format('d-m-Y');
        return $this;
    }

    public function afficheHeureParis(): \MiniPaviFwk\videotex\Videotex
    {
        $parisTime = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $this->output .= $parisTime->format('H:i');
        return $this;
    }

    public function repeteCaractere(string $caractere, int $nombre): \MiniPaviFwk\videotex\Videotex
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
    ): \MiniPaviFwk\Videotex\Videotex {
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
