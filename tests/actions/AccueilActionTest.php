<?php

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\actions\AccueilAction;
use Tests\Mocks\MockController;

class AccueilActionTest extends TestCase
{
    public function testVideotexController()
    {
        $action = new AccueilAction([]);
        $this->assertInstanceOf(MockController::class, $action->getController());
    }
}
