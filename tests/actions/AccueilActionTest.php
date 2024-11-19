<?php

use PHPUnit\Framework\TestCase;
use MiniPavi\MiniPaviCli;
use MiniPaviFwk\actions\AccueilAction;
use Tests\Mocks\MockController;

class AccueilActionTest extends TestCase
{
    public function test()
    {
        $action = new AccueilAction(['context_test' => 'context_ok']);

        $controller = $action->getController();
        $this->assertInstanceOf(MockController::class, $controller);

        $output = "*TEST*";
        $controller->setEcranTest($output);
        $this->assertEquals(
            MiniPaviCli::clearScreen() . PRO_MIN . PRO_LOCALECHO_OFF . '*MOCK_ECRAN*',
            $action->getOutput()
        );

        $this->assertEquals('context_ok', $controller->getContext()['context_test']);
        // $this->assertEquals($controller->getContext()['context_test']['params']['param_test'], 'param_present');
    }
}
