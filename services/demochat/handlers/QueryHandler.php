<?php

/**
 * Handles dcx by overriding \MiniPaviFwk\handlers\QueryHandler
 */

namespace service\handlers;

class QueryHandler extends \MiniPaviFwk\handlers\QueryHandler
{
    public static function queryDcx(): void
    {
        trigger_error("Deconnecte user - overloaded by DemoChat QueryHandler");

        // Cleanup
        $chatHelper = new \service\helpers\ChatHelper();
        $currentConnecte = $chatHelper->getCurrentConnecte();
        $chatHelper->deconnecteCurrentUser();
        $chatHelper->removeAllMessagesForCurrentUser();

        // Send Ligne00 message to others, thanks to my friend @ludosevilla creator of minipavi and minipavicli!
        list($uniqueIds, $messages) = $chatHelper->createOtherUsersLigne00Msg(
            $currentConnecte['pseudonyme'] . " est parti."
        );

        // C'est votre dernier mot jean-Pierre? Yes my comments are sometimes useless. Sorry!
        parent::queryDcx();
        if (count($uniqueIds) > 0) {
            trigger_error("queryDcx overloading for DemoChat - message pour " . print_r($uniqueIds, true));
            $cmd = \MiniPaviFwk\cmd\PushServiceMsgCmd::createMiniPaviCmd($uniqueIds, $messages);

            $session_handler = ConstantHelper::getConstValueByName(
                'SESSION_HANDLER_CLASSNAME',
                SessionHandler::class
            );

            \MiniPavi\MiniPaviCli::send("", "", $session_handler::getMiniPaviContext(), true, $cmd, false);
        }

        trigger_error("queryDcx overloading for DemoChat - end");
    }
}
