<?php

use PHPUnit\Framework\TestCase;
use MiniPavi\MiniPaviCli;
use MiniPaviFwk\actions\ControllerAction;
use Tests\Mocks\MockController;

class ControllerActionTest extends TestCase
{
    public function test()
    {
        $action = new ControllerAction('\\Tests\\Mocks\\MockController', []);

        $controller = $action->getController();
        $this->assertInstanceOf(MockController::class, $controller);

        $this->assertEquals("*MOCK_ECRAN*", $action->getOutput());
    }
}
