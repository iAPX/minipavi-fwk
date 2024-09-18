<?php

/**
 * Handles the Zone saisie parameters
 */

namespace MiniPaviFwk\cmd;

class ZoneSaisieCmd extends Cmd
{
    public static function createMiniPaviCmd(
        \MiniPaviFwk\Validation $validation,
        int $ligne = 24,
        int $col = 40,
        int $longueur = 1,
        bool $curseur = true,
        string $spaceChar = " ",
        string $replacementChar = '',
        string $prefill = ''
    ) {
        return \MiniPavi\MiniPaviCli::createInputTxtCmd(
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
