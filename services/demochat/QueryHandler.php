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

        // Cleanup
        $chatHelper = new \service\helpers\ChatHelper();
        $currentConnecte = $chatHelper->getCurrentConnecte();
        $chatHelper->deconnecteCurrentUser();
        $chatHelper->removeAllMessagesForCurrentUser();

        // Send Ligne00 message to others, thanks to my friend @ludosevilla creator of minipavi and minipavicli!
        $uniqueIds = [];
        $messages = [];
        $connectes = $chatHelper->getConnectes();
        foreach ($connectes as $connecte) {
            if ($connecte['uniqueId'] !== \MiniPavi\MiniPaviCli::$uniqueId) {
                $uniqueIds[] = $connecte['uniqueId'];
                $messages[] = \MiniPavi\MiniPaviCli::toG2($currentConnecte['pseudonyme'] . " est parti!");
            }
        }

        // C'est votre dernier mot jean-Pierre? Yes my comments are sometimes useless. Sorry!
        parent::queryDcx($sessionHandlerClassName);
        if (count($uniqueIds) > 0) {
            DEBUG && trigger_error("queryDcx overloading for DemoChat - message pour " . print_r($uniqueIds, true));
            $cmd = \MiniPaviFwk\cmd\PushServiceMsgCmd::createMiniPaviCmd($uniqueIds, $messages);
            \MiniPavi\MiniPaviCli::send("", "", "", true, $cmd, false);
        }

        DEBUG && trigger_error("queryDcx overloading for DemoChat - end");
    }
}
