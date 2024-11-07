<?php

/**
 * Handles the Zone Message parameters and valid keys to create the aassociated command
 */

namespace MiniPaviFwk\cmd;

use MiniPavi\MiniPaviCli;
use MiniPaviFwk\helpers\ValidationHelper;

class ZoneMessageCmd extends Cmd
{
    public static function createMiniPaviCmd(
        ?int $validation,
        int $ligne = 24,
        int $hauteur = 2,
        bool $curseur = true,
        string $spaceChar = " ",
        string $prefill = '',
        int $col = 1,
        int $longueur = 40
    ): array {
        if ($hauteur < 2) {
            trigger_error("At least 2 message lines are required for ZoneMessageCmd", E_USER_ERROR);
        }

        return MiniPaviCli::createInputMsgCmd(
            $col,
            $ligne,
            $longueur,
            $hauteur,
            is_null($validation) ? ValidationHelper::ALL : $validation | ValidationHelper::ENVOI,
            $curseur,
            $spaceChar,
            $prefill,
        );
    }
}
