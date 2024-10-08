<?php

/**
 * Handles the Zone Message parameters and valid keys to create the aassociated command
 */

namespace MiniPaviFwk\cmd;

class PushServiceMsgCmd extends Cmd
{
    public static function createMiniPaviCmd(array $uniqueIds, array $messages): array
    {
        $cmd = \MiniPavi\MiniPaviCli::createPushServiceMsgCmd($messages, $uniqueIds);
        trigger_error("PushServiceMsgCmd::createMiniPaviCmd : " . print_r($cmd, true), E_USER_NOTICE);
        return $cmd;
    }
}
