<?php

/**
 * FormatHelper offers services to format strings for Minitel output
 *
 * - Title formatting
 * - Unicode raw text formatting
 * - HTML code formatting
 */

namespace MiniPaviFwk\helpers;

class FormatHelper
{
    // Text alignment
    public const ALIGN_LEFT = 0;
    public const ALIGN_CENTER = 1;
    public const ALIGN_RIGHT = 2;

    // Text attributes
    public const ATTRIBUTE_DEFAULT = 0;
    public const ATTRIBUTE_DOUBLE_LARGEUR = 1;
    public const ATTRIBUTE_DOUBLE_HAUTEUR = 2;
    public const ATTRIBUTE_DOUBLE_TAILLE = 3;
    public const ATTRIBUTE_INVERSION = 4;

    public static function formatTitle(
        string $titre,
        int $ligne,
        int $nb_texte_lignes,
        int $retrait = 0,
        int $margeDroite = 0,
        string $couleur = 'blanc',
        string $continuation = ' ...',
        string $couleurContinuation = 'blanc',
        int $alignement = self::ALIGN_LEFT,
        int $attributes = self::ATTRIBUTE_DEFAULT,
        ?int $retraitPremiereLigne = null
    ): string {
        $words = explode(' ', $titre);

        $videotex = new VideotexHelper();
        for ($ligne_number = 0; $ligne_number < $nb_texte_lignes; $ligne_number++) {
            // End of the title?
            if (count($words) == 0) {
                break;
            }

            // Compute the aavailable space
            $ligne_retrait = !is_null($retraitPremiereLigne) && $ligne_number == 0 ? $retraitPremiereLigne : $retrait;
            $nb_ligne_cars = intdiv(
                40 - $ligne_retrait - $margeDroite,
                $attributes & self::ATTRIBUTE_DOUBLE_LARGEUR ? 2 : 1
            );
            //// $videotex->ecritUnicode($nb_ligne_cars);

            $current_ligne = array_shift($words);
            $display_continuation = false;
            while (count($words) > 0) {
                // Try adding a new word
                $next_word = reset($words);

                if (strlen($current_ligne . ' ' . $next_word) > $nb_ligne_cars) {
                    // If we are on the last line, ensure $continuation could be added
                    if ($ligne_number == $nb_texte_lignes - 1 && $continuation !== '') {
                        $current_ligne = mb_substr($current_ligne, 0, $nb_ligne_cars - mb_strlen($continuation));
                        $display_continuation = true;
                    }
                    break;
                }

                $current_ligne .= ' ' . array_shift($words);
            }

            // Compute the line position
            $current_ligne_cars = mb_strlen($current_ligne) + ($display_continuation ? mb_strlen($continuation) : 0);
            $current_ligne_cars *= $attributes & self::ATTRIBUTE_DOUBLE_LARGEUR ? 2 : 1;
            $col = 1 + $ligne_retrait;
            if ($alignement === self::ALIGN_CENTER) {
                $col = 1 + intdiv(40 - $ligne_retrait - $margeDroite - $current_ligne_cars, 2);
            } elseif ($alignement === self::ALIGN_RIGHT) {
                $col = 41 - $margeDroite - $current_ligne_cars;
            }

            // Output the text and optional continuation
            $videotex->position($ligne, $col);
            if ($couleur !== 'blanc') {
                $videotex->couleurTexte($couleur);
            }
            switch ($attributes  & self::ATTRIBUTE_DOUBLE_TAILLE) {
                case self::ATTRIBUTE_DOUBLE_LARGEUR:
                    $videotex->doubleLargeur();
                    break;
                case self::ATTRIBUTE_DOUBLE_HAUTEUR:
                    $videotex->doubleHauteur();
                    break;
                case self::ATTRIBUTE_DOUBLE_TAILLE:
                    $videotex->doubleTaille();
                    break;
                default:
            }
            if ($attributes & self::ATTRIBUTE_INVERSION) {
                $videotex->inversionDebut();
            }
            $videotex->ecritUnicode($current_ligne);
            if ($display_continuation) {
                if ($couleurContinuation !== $couleur) {
                    $videotex->couleurTexte($couleurContinuation);
                }
                $videotex->ecritUnicode($continuation);
            }

            $ligne += $attributes & self::ATTRIBUTE_DOUBLE_HAUTEUR ? 2 : 1;
        }

        return $videotex->getOutput();
    }

    public static function formatMultipageRawText(
        string $texte,
        int $ligne,
        int $hauteur,
        string $couleur = 'blanc',
        int $retrait = 0,
        int $margeDroite = 0,
        int $alignement = self::ALIGN_LEFT,
        bool $firstParagraphLetterDoubleTaille = false,
    ): array {
    }
}
