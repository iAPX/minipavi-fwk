<?php

/**
 * Handle the Sessions, extendable by services/{serviceName}/SessionHandler.php
 */

namespace MiniPaviFwk\handlers;

use MiniPavi\MiniPaviCli;

class MiniPaviSessionHandler extends SessionHandler
{
    public static function startSession(): void
    {
        //// global $_SESSION;
        $_SESSION = json_decode(MiniPaviCli::$context, true);

        trigger_error("MiniPaviSession - Start Session" . print_r($_SESSION, true), E_USER_NOTICE);

        // Initalize Session missing or default data, @TODO should be elsewhere
        if (!isset($_SESSION['is_drcs'])) {
            $_SESSION['is_drcs'] = false;
        }
    }

    public static function destroySession(): void
    {
        // Useless for MiniPavi
        trigger_error("MiniPaviSession - Destroy Session", E_USER_NOTICE);
    }

    public static function getMiniPaviContext(): string
    {
        trigger_error("MiniPaviSession - getMiniPaviContext Session" . print_r($_SESSION, true), E_USER_NOTICE);
        return json_encode($_SESSION);
    }
}
