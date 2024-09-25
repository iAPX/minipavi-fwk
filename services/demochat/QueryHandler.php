<?php

/**
 * Handles dcx
 */

namespace service;

class QueryHandler extends \MiniPaviFwk\handlers\QueryHandler
{
    public static function queryDcx(string $sessionHandlerClassName): void
    {
        DEBUG && trigger_error("Deconnecte user");

        $chatHelper = new \service\helpers\ChatHelper();
        $chatHelper->deconnecteCurrentUser();
        $chatHelper->removeAllMessagesForCurrentUser();

        parent::queryDcx($sessionHandlerClassName);
    }

    public static function queryDirect(): void
    {
        trigger_error("fctn : DIRECT - overloaded by DemoChat QueryHandler");
        trigger_error("context : " . print_r(\MiniPaviFwk\handlers\SessionHandler::getContext(), true));
        trigger_error("session : " . print_r($_SESSION, true));

        $cmd = \service\helpers\DirectQueryHelper::handler();
        // $nextPage = static::getNextPageUrl();

        // Ends here!
        \MiniPavi\MiniPaviCli::send("\x07", "", "", true, $cmd);
    
        exit;
    }
}
