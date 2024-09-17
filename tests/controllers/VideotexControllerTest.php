<?php

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\controllers\VideotexController;
use MiniPaviFwk\actions\RepetitionAction;
use Tests\Mocks\MockController;

class VideotexControllerTest extends TestCase
{
    public function test()
    {
        $context = ['test'=>'test value'];
        $params = ['test param'=>'test param value'];
        $controller = new VideotexController($context, $params);

        $expected_context = ["test"=>"test value", "controller"=>"MiniPaviFwk\\controllers\\VideotexController", "params"=>["test param"=>"test param value"]];
        $this->assertEquals($expected_context, $controller->getContext());
        $this->assertEquals("*** Ecran absent. ***", $controller->ecran());
    }

    public function testGetAction()
    {
        // Isolated tests as getAction() is complex and not easily testable
        $controller = new VideotexController([]);
        $action = $controller->getAction("", "REPETITION");
        $this->assertEquals(RepetitionAction::class, get_class($action));
    }
}
