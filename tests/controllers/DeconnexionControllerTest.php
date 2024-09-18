<?php

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\controllers\DeconnexionController;
use MiniPaviFwk\actions\DeconnexionAction;

class DeconnexionControllerTest extends TestCase
{
    public function test()
    {
        $controller = new DeconnexionController([]);

        $this->assertEquals("*** Ecran absent. ***", $controller->ecran());
        // $this->assertInstanceOf(DeconnexionAction::class, $controller->getSaisieAction([""], ""));
    }
}
