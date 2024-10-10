<?php

/**
 * Handles the Zone Message parameters and valid keys to create the aassociated command
 */

namespace MiniPaviFwk\cmd;

use MiniPavi\MiniPaviCli;
use MiniPaviFwk\Validation;

class ZoneMessageCmd extends Cmd
{
    public static function createMiniPaviCmd(
        Validation $validation,
        int $ligne = 24,
        int $hauteur = 1,
        bool $curseur = true,
        string $spaceChar = " ",
        string $prefill = '',
        int $col = 1,
        int $longueur = 40
    ): array {
        return MiniPaviCli::createInputMsgCmd(
            $col,
            $ligne,
            $longueur,
            $hauteur,
            $validation->getKeyMask(),
            $curseur,
            $spaceChar,
            $prefill,
        );
    }
}
