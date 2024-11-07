<?php

/**
 * Defines a Form Field
 */

namespace MiniPaviFwk\helpers;

class FormField
{
    public int $ligne;
    public int $col;
    public int $longueur;
    public string $prefill;

    public function __construct(int $ligne, int $col, int $longueur, string $prefill = '') {
        $this->ligne = $ligne;
        $this->col = $col;
        $this->longueur = $longueur;
        $this->prefill = $prefill;
    }
}
