<?php

/**
 * Handles the Directcall for the demo chat
 */

namespace service\helpers;

class DirectQueryHelper
{
    public static function handler(): array
    {
        // Use the $_SESSION DIRECT_QUERY_* to send the messages, with clean up
        $uniqueIds = [];
        if (isset($_SESSION['DIRECT_QUERY_IDS'])) {
            $uniqueIds = $_SESSION['DIRECT_QUERY_IDS'];
            unset($_SESSION['DIRECT_QUERY_IDS']);
        }

        $message = "Évènement non reconnu.";
        if (isset($_SESSION['DIRECT_QUERY_MSG'])) {
            $messages = $_SESSION['DIRECT_QUERY_MSG'];
            unset($_SESSION['DIRECT_QUERY_MSG']);
        }

        // Gets the session data to check what should be returned
        return \MiniPaviFwk\cmd\PushServiceMsgCmd::createMiniPaviCmd($uniqueIds, $messages);
    }
}
