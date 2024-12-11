<?php

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\cmd\ZoneSaisieCmd;

class ZoneSaisieCmdTest extends TestCase
{
    public function testCreateMiniPaviCmd()
    {
        $cmd = ZoneSaisieCmd::createMiniPaviCmd();
        $this->assertEquals('InputTxt', $cmd['COMMAND']['name']);

        $this->assertEquals(24, $cmd['COMMAND']['param']['y']);
        $this->assertEquals(40, $cmd['COMMAND']['param']['x']);
        $this->assertEquals(1, $cmd['COMMAND']['param']['l']);
        $this->assertEquals('off' , $cmd['COMMAND']['param']['cursor']);
    }
}
