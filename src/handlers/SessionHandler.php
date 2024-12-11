<?php

/**
 * Handle the Sessions, extendable by services/{serviceName}/SessionHandler.php
 */

namespace MiniPaviFwk\handlers;

use MiniPavi\MiniPaviCli;

class SessionHandler
{
    public static function startSession(): void
    {
        trigger_error("Session - Start Session", E_USER_NOTICE);
        session_id(MiniPaviCli::$uniqueId);
        session_start([
            'use_cookies' => 0,
            'use_only_cookies' => 0,
            'use_trans_sid' => 1,
        ]);

        // Initalize Session missing or default data, @TODO should be elsewhere
        if (!isset($_SESSION['is_drcs'])) {
            $_SESSION['is_drcs'] = false;
        }
    }

    public static function destroySession(): void
    {
        trigger_error("Session - Destroy Session", E_USER_NOTICE);
        session_destroy();
    }

    public static function getContext(): array
    {
        return $_SESSION['context'];
    }

    public static function setContext(array $context): void
    {
        trigger_error("Session - Save context : " . print_r($context, true), E_USER_NOTICE);
        $_SESSION['context'] = $context;
    }

    public static function getMiniPaviContext(): string
    {
        trigger_error("Session - return empty MiniPavi context : ", E_USER_NOTICE);
        return '';
    }
}
