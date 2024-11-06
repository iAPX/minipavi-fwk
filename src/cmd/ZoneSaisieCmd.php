<?php

/**
 * Handles the Zone saisie parameters
 */

namespace MiniPaviFwk\cmd;

use MiniPavi\MiniPaviCli;
use MiniPaviFwk\helpers\ValidationHelper;

class ZoneSaisieCmd extends Cmd
{
    public static function createMiniPaviCmd(
        ?int $validation = null,
        int $ligne = 24,
        int $col = 40,
        int $longueur = 1,
        bool $curseur = true,
        string $spaceChar = " ",
        string $replacementChar = '',
        string $prefill = ''
    ): array {
        return MiniPaviCli::createInputTxtCmd(
            $col,
            $ligne,
            $longueur,
            is_null($validation) ? ValidationHelper::ALL : $validation,
            $curseur,
            $spaceChar,
            $replacementChar,
            $prefill,
        );
    }
}
