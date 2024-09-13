<?php

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\actions\UnicodeOutputAction;
use Tests\Mocks\MockController;

class UnicodeOutputActionTest extends TestCase
{
    public function test()
    {
        $mockController = new MockController([]);
        $mockController->setEcranTest('Test ecran');

        $action = new UnicodeOutputAction($mockController, 'Accentué');

        $this->assertInstanceOf(MockController::class, $action->getController());
        $this->assertEquals(\MiniPavi\MiniPaviCli::toG2('Accentué'), $action->getOutput());
    }
}
