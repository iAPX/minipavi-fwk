<?php

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\actions\Ligne00Action;
use Tests\Mocks\MockController;

class Ligne00ActionTest extends TestCase
{
    public function test()
    {
        $mockController = new MockController([]);

        $action = new Ligne00Action($mockController, 'Accentué');

        $this->assertEquals($mockController, $action->getController());
        $this->assertEquals(\MiniPavi\MiniPaviCli::writeLine0('Accentué'), $action->getOutput());
    }
}
