<?php

/**
 * Disconnect the user through the LibCnx minipavi command
 */

namespace MiniPaviFwk\cmd;

use MiniPavi\MiniPaviCli;

class DeconnexionCmd extends Cmd
{
    public static function createMiniPaviCmd(): array
    {
        return MiniPaviCli::createLibCnxCmd();
    }
}
