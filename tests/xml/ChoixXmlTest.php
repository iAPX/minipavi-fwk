<?php

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\xml\ChoixXml;


class ChoixXmlTest extends TestCase
{
    public function test()
    {
        $xmlpage = simplexml_load_string(<<<XML
            <page nom="demoxml-accueil">
                <action defaut="Choix non proposé">
                    <saisie touche="repetition" suivant="demoxml-accueil" />
                    <saisie touche="sommaire" suivant="demoxml-sommaire" />
                </action>
            </page>
        XML);
        
        $this->assertNull(ChoixXml::choix($xmlpage, "*", "ENVOI", []));

        $action = ChoixXml::choix($xmlpage, "REPETITION", "", []);
        $this->assertInstanceOf(\MiniPaviFwk\actions\pageAction::class, $action);
    }
}
