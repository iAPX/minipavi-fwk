<?php

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\xml\ZonesaisieXml;
use MiniPaviFwk\ZoneSaisie;


class ZonesaisieXmlTest extends TestCase
{
    public function test()
    {
        $xmlpage = simplexml_load_string(<<<XML
            <page nom="demoxml-accueil">
                <entree>
                    <zonesaisie ligne="12" col="34" longueur="5" curseur="visible" />
                </entree>
            </page>
        XML);
        
        $zonesaisie = ZonesaisieXml::zonesaisie($xmlpage);
        $this->assertInstanceOf(ZoneSaisie::class, $zonesaisie);
        $this->assertEquals(12, $zonesaisie->ligne);
        $this->assertEquals(34, $zonesaisie->col);
        $this->assertEquals(5, $zonesaisie->longueur);
        $this->assertEquals(true, $zonesaisie->curseur);
    }
}
