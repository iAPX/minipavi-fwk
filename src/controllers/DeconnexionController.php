<?php

/**
 * Controller to disconnect the user, overriding every behaviours!
 *
 * Use specific Minipavi cmd to enable graceful disconnection,
 * displaying list of available service on the minipavi side.
 * Ensure no future actions possible until reconnected.
 */

namespace MiniPaviFwk\controllers;

use MiniPaviFwk\cmd\DeconnexionCmd;

class DeconnexionController extends VideotexController
{
    public function getCmd(): array
    {
        return DeconnexionCmd::createMiniPaviCmd();
    }
}
