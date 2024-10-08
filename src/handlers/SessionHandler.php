<?php

/**
 * Handle the Sessions, extendable by services/{serviceName}/SessionHandler.php
 */

namespace MiniPaviFwk\handlers;

class SessionHandler
{
    public static function startSession(): void
    {
        // Disable session cookies, session is identified through minipavi's uniqueId
        ini_set('session.use_cookies', '0');
        ini_set('session.use_only_cookies', '0');

        DEBUG && trigger_error("Session - Start Session");
        session_id(static::getSessionId());
        session_start();

        // Initalize Session missing or default data
        if (!isset($_SESSION['is_drcs'])) {
            $_SESSION['is_drcs'] = false;
        }
    }

    public static function destroySession(): void
    {
        DEBUG && trigger_error("Session - Destroy Session");
        session_id(static::getSessionId());
        session_destroy();
    }

    public static function getContext(): array
    {
        return $_SESSION['context'];
    }

    public static function setContext(array $context): void
    {
        DEBUG && trigger_error("Session - Save context : " . print_r($context, true));
        $_SESSION['context'] = $context;
    }

    protected static function getSessionId(): string
    {
        return \MiniPavi\MiniPaviCli::$uniqueId;
    }
}
