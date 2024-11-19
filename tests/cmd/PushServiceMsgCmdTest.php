<?php

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\cmd\PushServiceMsgCmd;


class PushServiceMsgCmdTest extends TestCase
{
    public function testCreateMiniPaviCmd()
    {
        $cmd = PushServiceMsgCmd::createMiniPaviCmd([123, 456], ["test message", "another message"]);
        $this->assertEquals('PushServiceMsg', $cmd['COMMAND']['name']);

        $this->assertEquals([123, 456], $cmd['COMMAND']['param']['uniqueids']);
        $this->assertEquals(["test message", "another message"], $cmd['COMMAND']['param']['message']);
    }
}
