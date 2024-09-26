<?php

/**
 * Handles dcx
 */

namespace service;

class QueryHandler extends \MiniPaviFwk\handlers\QueryHandler
{
    public static function queryDcx(string $sessionHandlerClassName): void
    {
        DEBUG && trigger_error("Deconnecte user - overloaded by DemoChat QueryHandler");

        // Cleaanup
        $chatHelper = new \service\helpers\ChatHelper();
        $currentConnecte = $chatHelper->getCurrentConnecte();
        $chatHelper->deconnecteCurrentUser();
        $chatHelper->removeAllMessagesForCurrentUser();

        // Send Ligne00 message to others

        parent::queryDcx($sessionHandlerClassName);
    }
}
