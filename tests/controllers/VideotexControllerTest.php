<?php

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\controllers\VideotexController;
use MiniPaviFwk\actions\Ligne00Action, MiniPaviFwk\actions\RepetitionAction;
use Tests\Mocks\MockController, Tests\Mocks\MockFormController;

class VideotexControllerTest extends TestCase
{
    public function test()
    {
        $context = ['test'=>'test value'];
        $params = ['test param'=>'test param value'];
        $controller = new VideotexController($context, $params);

        $context = $controller->getContext();
        $this->assertEquals('test value', $context['test']);
        $this->assertEquals('test param value', $context['params']['test param']);

        $this->assertEquals(chr(12) . "*** Ecran() absent. ***", $controller->ecran());
    }

    public function testGetSaisieAction()
    {
        // Repetition; * Repetition; Envoi; 1 Envoi; Suite; * Suite; Sommaire; test + Envoi
        $controller = new MockController([]);

        $action = $controller->getSaisieAction("", "REPETITION");
        $this->assertEquals('choixRepetition()', $action->mock_id);

        $action = $controller->getSaisieAction("1", "ENVOI");
        $this->assertEquals('choix1Envoi()', $action->mock_id);

        $action = $controller->getSaisieAction("*", "SUITE");
        $this->assertEquals('toucheSuite("*")', $action->mock_id);

        $action = $controller->getSaisieAction("*", "SOMMAIRE");
        $this->assertEquals('choix("SOMMAIRE", "*")', $action->mock_id);

        $action = $controller->getSaisieAction("*", "RETOUR");
        $this->assertEquals('nonPropose("RETOUR", "*")', $action->mock_id);
    }

    public function testGetMessageAction()
    {
        $controller = new MockController([]);
        $action = $controller->getMessageAction(["l1", "l2", "l3"], "ENVOI");
        $this->assertEquals('message("ENVOI")', $action->mock_id);
        $this->assertEquals(["l1", "l2", "l3"], $controller->test_message);
    }

    public function testFormulaireAction()
    {
        $controller = new MockFormController([]);
        $action = $controller->getMessageAction(["field1", "field2", "field3"], "ENVOI");
        $this->assertEquals('formulaire("ENVOI")', $action->mock_id);
    }

    public function testGetCmd()
    {
        $controller = new VideotexController([]);
        $cmd = $controller->getCmd();
        $this->assertEquals('InputTxt', $cmd['COMMAND']['name']);
    }
}
