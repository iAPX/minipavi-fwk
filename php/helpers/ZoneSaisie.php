<?php

/**
 * Handles the Zone saisie parameters
 */

namespace helpers;

class ZoneSaisie
{
    public int $ligne;
    public int $col;
    public int $longueur;
    public bool $curseur;
    public string $spaceChar;
    public string $replacementChar;
    public string $prefill;

    public function __construct(
        int $ligne = 24,
        int $col = 40,
        int $longueur = 1,
        bool $curseur = true,
        string $spaceChar = " ",
        string $replacementChar = '',
        string $prefill = ''
    ) {
        $this->ligne = $ligne;
        $this->col = $col;
        $this->longueur = $longueur;
        $this->curseur = $curseur;
        $this->spaceChar = $spaceChar;
        $this->replacementChar = $replacementChar;
        $this->prefill = $prefill;
    }
}
