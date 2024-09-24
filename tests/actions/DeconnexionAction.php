<?php

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\actions\DeconnexionAction;

class DeconnnexionActionTest extends TestCase
{
    public function testDefault()
    {
        $action = new DeconnexionAction();
        $this->assertInstanceOf(DeconnexionAction::class, $action->getController());
        $this->assertEquals(\MiniPavi\MiniPaviCli::writeLine0('Déconnexion service.'), $action->getOutput());
    }

    public function testCustomLigne00()
    {
        $action = new DeconnexionAction('Test ligne 00');
        $this->assertInstanceOf(DeconnexionAction::class, $action->getController());
        $this->assertEquals(\MiniPavi\MiniPaviCli::writeLine0('Test ligne 00'), $action->getOutput());
    }

    public function testOverridenController()
    {
        
    }
}
