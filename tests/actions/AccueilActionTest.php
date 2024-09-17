<?php

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\actions\AccueilAction;
use Tests\Mocks\MockController;
use MiniPaviFwk\controllers\XmlController;

class AccueilActionTest extends TestCase
{
    public function testVideotexController()
    {
        $action = new AccueilAction('\\Tests\\Mocks\\MockController', '', []);
        $this->assertInstanceOf(MockController::class, $action->getController());
    }

    public function testXmlController()
    {
        $default_xml_file = mb_substr(end(explode('/', glob('service/xml/*.xml')[0])), 0, -4);
        // define("DEFAULT_XML_FILE", $default_xml_file);
        define("XML_PAGES_URL", false);

        $action = new AccueilAction(false, $default_xml_file, []);
        $this->assertInstanceOf(XmlController::class, $action->getController());
        $this->AssertFalse(empty($action->getOutput()));
    }
}
