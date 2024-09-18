<?php

/**
 * Disconnect the user through the LibCnx minipavi command
 */

namespace MiniPaviFwk\cmd;

class DeconnexionCmd extends Cmd
{
    public static function createMiniPaviCmd(): array
    {
        return \MiniPavi\MiniPaviCli::createLibCnxCmd();
    }
}
