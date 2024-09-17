<?php

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\xml\ValidationXml;


class ValidationXmlTest extends TestCase
{
    public function test()
    {
        $xmlpage = simplexml_load_string(<<<XML
            <page nom="demoxml-accueil">
                <entree>
                    <validation touche="repetition" />
                    <validation touche="sommaire" />
                </entree>
            </page>
        XML);
        
        $this->assertEquals(["repetition", "sommaire"], ValidationXml::validationKeys($xmlpage));
    }
}
