<?php

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\actions\AccueilAction;
use Tests\Mocks\MockController;
use MiniPaviFwk\controllers\XmlController;

class AccueilActionTest extends TestCase
{
    public function testVideotexController()
    {
        $action = new AccueilAction('\\Tests\\Mocks\\MockController', '', []);
        $this->assertInstanceOf(MockController::class, $action->getController());
    }

    public function testXmlController()
    {
    }
}
