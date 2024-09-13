<?php

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\actions\VideotexOutputAction;
use Tests\Mocks\MockController;

class VideotexOutputActionTest extends TestCase
{
    public function test()
    {
        $mockController = new MockController([]);
        $mockController->setEcranTest('Test ecran');

        $action = new VideotexOutputAction($mockController, 'Videotex');

        $this->assertInstanceOf(MockController::class, $action->getController());
        $this->assertEquals('Videotex', $action->getOutput());
    }
}
