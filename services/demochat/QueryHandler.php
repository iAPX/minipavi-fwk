<?php

/**
 * Handles dcx
 */

namespace service;

class QueryHandler extends \MiniPaviFwk\handlers\QueryHandler
{
    public static function queryDcx(string $sessionHandlerClassName): \MiniPaviFwk\actions\Action
    {
        DEBUG && trigger_error("Deconnecte user");

        $chatHelper = new \service\helpers\ChatHelper();
        $chatHelper->deconnecteCurrentUser();
        $chatHelper->removeAllMessagesForCurrentUser();

        return parent::queryDcx($sessionHandlerClassName);
    }
}
