<?php

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\cmd\InputFormCmd;
use MiniPaviFwk\helpers\FormField;

class InputFormCmdTest extends TestCase
{
    public function testCreateMiniPaviCmd()
    {
        $fields = [
            new FormField(4, 7, 34),
            new FormField(5, 10, 31),
            new FormField(6, 15, 5),
        ];
        $cmd = InputFormCmd::createMiniPaviCmd(null, $fields);
        $this->assertEquals('InputForm', $cmd['COMMAND']['name']);

        $this->assertEquals(3, count($cmd['COMMAND']['param']['x']));
        $this->assertEquals(3, count($cmd['COMMAND']['param']['y']));
        $this->assertEquals(3, count($cmd['COMMAND']['param']['l']));
    }
}
