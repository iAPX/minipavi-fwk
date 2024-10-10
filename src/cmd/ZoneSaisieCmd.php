<?php

/**
 * Handles the Zone saisie parameters
 */

namespace MiniPaviFwk\cmd;

use MiniPavi\MiniPaviCli;
use MiniPaviFwk\Validation;

class ZoneSaisieCmd extends Cmd
{
    public static function createMiniPaviCmd(
        Validation $validation,
        int $ligne = 24,
        int $col = 40,
        int $longueur = 1,
        bool $curseur = true,
        string $spaceChar = " ",
        string $replacementChar = '',
        string $prefill = ''
    ) {
        return MiniPaviCli::createInputTxtCmd(
            $col,
            $ligne,
            $longueur,
            $validation->getKeyMask(),
            $curseur,
            $spaceChar,
            $replacementChar,
            $prefill,
        );
    }
}
