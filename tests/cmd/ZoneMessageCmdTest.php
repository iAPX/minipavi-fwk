<?php

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\cmd\ZoneMessageCmd;
use MiniPaviFwk\helpers\FormField;

class ZoneMessageCmdTest extends TestCase
{
    public function testCreateMiniPaviCmd()
    {
        $cmd = ZoneMessageCmd::createMiniPaviCmd(null, 5, 4);
        $this->assertEquals('InputMsg', $cmd['COMMAND']['name']);

        $this->assertEquals(5, $cmd['COMMAND']['param']['y']);
        $this->assertEquals(4, $cmd['COMMAND']['param']['h']);
    }
}
