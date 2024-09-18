<?php

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\xml\ZoneSaisieMessageCmdXml;
use Tests\Mocks\MockController;
use MiniPaviFwk\Validation;

class ZoneSaisieMessageCmdXmlTest extends TestCase
{
    public function testZoneSaisie()
    {
        $xmlpage = simplexml_load_string(<<<XML
            <page nom="demoxml-accueil">
                <entree>
                    <zonesaisie ligne="12" col="34" longueur="5" curseur="visible" />
                </entree>
            </page>
        XML);
        
        $validation = new Validation(new MockController([]));
        $zonesaisie = ZoneSaisieMessageCmdXml::createMiniPaviCmd($validation, $xmlpage);

        $expected = \MiniPavi\MiniPaviCli::createInputTxtCmd(
            34,
            12,
            5,
            8,
        );
        $this->assertEquals($expected, $zonesaisie);
    }

    public function testZoneMessage()
    {
        $xmlpage = simplexml_load_string(<<<XML
            <page nom="demoxml-accueil">
                <entree>
                    <zonemessage ligne="5" hauteur="4" curseur="visible" />
                </entree>
            </page>
        XML);
        
        $validation = new Validation(new MockController([]));
        $zonemessage = ZoneSaisieMessageCmdXml::createMiniPaviCmd($validation, $xmlpage);

        $expected = \MiniPavi\MiniPaviCli::createInputMsgCmd(
            1,
            5,
            40,
            4,
            8,
        );
        $this->assertEquals($expected, $zonemessage);
    }
}
