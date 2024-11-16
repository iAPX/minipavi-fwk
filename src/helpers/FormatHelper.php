<?php

/**
 * FormatHelper offers services to format strings for Minitel output
 *
 * - Title formatting
 * - Unicode raw text formatting
 * - HTML code formatting
 */

namespace MiniPaviFwk\helpers;

use MiniPavi\MiniPaviCli;

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

            // Compute the available space
            $ligne_retrait = !is_null($retraitPremiereLigne) && $ligne_number == 0 ? $retraitPremiereLigne : $retrait;
            $nb_ligne_cars = intdiv(
                40 - $ligne_retrait - $margeDroite,
                $attributes & self::ATTRIBUTE_DOUBLE_LARGEUR ? 2 : 1
            );

            $current_ligne = array_shift($words);
            $display_continuation = false;
            while (count($words) > 0) {
                // Try adding a new word
                $next_word = reset($words);

                if (mb_strlen($current_ligne . ' ' . $next_word) > $nb_ligne_cars) {
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

    public static function fillTextLines(string $texte, int $largeur = 40): array
    {
        // Just in case!
        if (trim($texte) === '') {
            return [];
        }

        // From text to words into lines.
        $words = explode(' ', trim($texte));
        $lignes = [];
        $current_ligne = array_shift($words);
        foreach ($words as $word) {
            if (mb_strlen($current_ligne . ' ' . $word) > $largeur) {
                $lignes[] = $current_ligne;
                $current_ligne = $word;
            } else {
                $current_ligne .= ' ' . $word;
            }
        }
        if ($current_ligne !== '') {
            $lignes[] = $current_ligne;
        }

        return $lignes;
    }

    public static function formatMultipageRawText(string $unicodeText, int $hauteur): array
    {
        $paragraphs = explode("\n\n", $unicodeText);
        $pages = [];
        $page = [];
        foreach ($paragraphs as $paragraph) {
            // Transform the paragraph into a list of lines, respecting LF
            $output_lines = [];
            $lines = explode("\n", $paragraph);
            foreach ($lines as $line) {
                $output_lines = array_merge($output_lines, self::fillTextLines($line));
            }

            // Reposition the cursor and make these lines Vidéotex lines
            foreach ($output_lines as $key => $line) {
                $line_length = mb_strlen($line);
                $videotex_line = MiniPaviCli::toG2($line);
                if ($line_length === 0) {
                    $output_lines[$key] = chr(10);
                } elseif ($line_length == 39) {
                    // PAD is faster
                    $output_lines[$key] = $videotex_line . ' ';
                } elseif ($line_length < 40) {
                    // CRLF to start the next line!
                    $output_lines[$key] = $videotex_line . chr(13) . chr(10);
                }
            }

            // Page skip?
            if (count($page) > 0 && count($page) + 1 + count($output_lines) > $hauteur) {
                // Start on next page!
                $pages[] = implode('', $page);
                $page = [];
            } elseif (count($page) > 0) {
                // Paragraph Separator: Line Feed!
                $page[] = chr(10);
            }

            // Adds the lines on one or multiple pages
            foreach ($output_lines as $output_line) {
                $page[] = $output_line;
                if (count($page) >= $hauteur) {
                    // Next page when filled up
                    $pages[] = implode('', $page);
                    $page = [];
                }
            }
        }
        if (count($page) > 0) {
            $pages[] = implode('', $page);
        }

        return $pages;
    }
}
