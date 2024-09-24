<?php

/**
 * Handle the Sessions, extendable by services/{serviceName}/SessionHandler.php
 */

namespace MiniPaviFwk\handlers;

class SessionHandler
{
    public static function startSession(): void
    {
        DEBUG && trigger_error("Session - Start Session");
        session_id(static::getSessionId());
        session_start();
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
