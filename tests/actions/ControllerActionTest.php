<?php

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\actions\ControllerAction;
use Tests\Mocks\MockController;

class ControllerActionTest extends TestCase
{
    public function test()
    {
        $action = new ControllerAction('\\Tests\\Mocks\\MockController', []);
        $this->assertInstanceOf(MockController::class, $action->getController());
    }
}
