<?php

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\actions\RepetitionAction;
use Tests\Mocks\MockController;

class RepetitionActionTest extends TestCase
{
    public function test()
    {
        $mockController = new MockController([]);
        $mockController->setEcranTest('Test ecran');

        $action = new RepetitionAction($mockController);

        $this->assertInstanceOf(MockController::class, $action->getController());
        $this->assertEquals('Test ecran', $action->getOutput());
    }
}
