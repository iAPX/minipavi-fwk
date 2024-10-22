<?php

/**
 * Action to send back to the homepage of the service
 */

namespace MiniPaviFwk\actions;

use MiniPavi\MiniPaviCli;
use MiniPaviFwk\controllers\XmlController;

class AccueilAction extends Action
{
    public function __construct($context)
    {
        $default_controller = MiniPaviFwk\helpers\ConstantHelper::getConstValueByName('DEFAULT_CONTROLLER', false);
        $default_xml_file = MiniPaviFwk\helpers\ConstantHelper::getConstValueByName('DEFAULT_XML_FILE', false);

        trigger_error("Action: Accueil", E_USER_NOTICE);
        if (empty($default_controller)) {
            $context['xml_filename'] = $default_xml_file;
            $context['xml_page'] = false;
            $this->controller = new XmlController($context);
        } else {
            $this->controller = new $default_controller($context);
        }

        $this->output = MiniPaviCli::clearScreen() . PRO_MIN . PRO_LOCALECHO_OFF . $this->controller->ecran();
    }
}
