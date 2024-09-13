<?php

/**
 * Action to send back to the homepage of the service
 */

namespace MiniPaviFwk\actions;

class AccueilAction extends Action
{
    public function __construct($defaultControllerName, $defaultXMLfilename, $context)
    {
        DEBUG && trigger_error("Action: Accueil");
        if (empty($defaultControllerName)) {
            // XML default file
            $context['xml_filename'] = $defaultXMLfilename;
            $context['xml_page'] = false;
            $this->controller = new \MiniPaviFwk\controllers\XmlController($context);
        } else {
            // Default service controller
            $this->controller = new $defaultControllerName($context);
        }
        $this->output = $this->controller->ecran();
    }
}
