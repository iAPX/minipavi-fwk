<?php

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\controllers\DeconnexionController;
use MiniPaviFwk\actions\DeconnexionAction;

class DeconnexionControllerTest extends TestCase
{
    public function test()
    {
        $controller = new DeconnexionController([]);

        $this->assertEquals(chr(12) . "*** Ecran() absent. ***", $controller->ecran());
    }
}
