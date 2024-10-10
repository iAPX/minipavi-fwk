<?php

/**
 * Action to send back to the homepage of the service
 */

namespace MiniPaviFwk\actions;

use MiniPavi\MiniPaviCli;
use MiniPaviFwk\controllers\XmlController;

class AccueilAction extends Action
{
    public function __construct($defaultControllerName, $defaultXMLfilename, $context)
    {
        trigger_error("Action: Accueil", E_USER_NOTICE);
        if (empty($defaultControllerName)) {
            $context['xml_filename'] = $defaultXMLfilename;
            $context['xml_page'] = false;
            $this->controller = new XmlController($context);
        } else {
            $this->controller = new $defaultControllerName($context);
        }
        $this->output = MiniPaviCli::clearScreen() . PRO_MIN . PRO_LOCALECHO_OFF . $this->controller->ecran();
    }
}
