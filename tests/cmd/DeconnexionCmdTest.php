<?php

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\cmd\DeconnexionCmd;

class DeconnexionCmdTest extends TestCase
{
    public function testCreateMiniPaviCmd()
    {
        $cmd = DeconnexionCmd::createMiniPaviCmd();
        $this->assertEquals('libCnx', $cmd['COMMAND']['name']);
    }
}
