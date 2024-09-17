<?php

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\xml\EcranXml;


class EcranXmlTest extends TestCase
{
    public function test()
    {
        $xmlpage = simplexml_load_string(<<<XML
            <page nom="demoxml-accueil">
                <ecran>
                    <ecrit texte=" &lt;page nom='demoxml-accueil'/&gt; "/>
                </ecran>
            </page>
        XML);
        
        $this->assertEquals(" <page nom='demoxml-accueil'/> ", EcranXml::ecran($xmlpage));
    }
}
