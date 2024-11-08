<?php

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\controllers\VideotexController;
use MiniPaviFwk\actions\Ligne00Action;
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
        $this->assertEquals(chr(12) . "*** Ecran() absent. ***", $controller->ecran());
    }

    public function testGetSaisieAction()
    {
        // Isolated tests as getSaisieAction() is complex and not easily testable
        $controller = new VideotexController([]);
        $action = $controller->getSaisieAction("", "REPETITION");
        $this->assertEquals(RepetitionAction::class, get_class($action));
    }

    public function testGetMessageAction()
    {
        // Isolated tests as getSaisieAction() is complex and not easily testable
        $controller = new VideotexController([]);
        $action = $controller->getMessageAction([""], "REPETITION");
        $this->assertEquals(Ligne00Action::class, get_class($action));
    }
}
