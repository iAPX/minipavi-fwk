<?php

/**
 * Action to go to a page by its name, either by {Pagename}Controller if exists or by XmlController with params
 *
 */

namespace MiniPaviFwk\actions;

class PageAction extends Action
{
    public function __construct(array $context, string $pagename, string $xmlfilename = '')
    {
        DEBUG && trigger_error("Action: Changement de page - " . $pagename);
        $context['xml_page'] = $pagename;
        // Enjoy ;)
        !empty($xmlfilename) && $context['xml_filename'] = $xmlfilename;

        // Enable Overriding by \service\{Pagename}Controller. XmlController or a VideotexController
        // xxxx becomes XxxxController; xxxx-yyyy becomes XxxxYyyyController; and so on
        $cleanControllerName = '';
        foreach (explode('-', $pagename) as $pagename_part) {
            $cleanControllerName .= \MiniPaviFwk\strings\mb_ucfirst(mb_strtolower($pagename_part));
        }
        $overriderControllerName = "\\service\\controllers\\" . $cleanControllerName . 'Controller';
        if (class_exists($overriderControllerName)) {
            // We rely on the Composer Autoloaders - don't forget to install and update them: composer dump-autoload
            DEBUG && trigger_error("Action: Controleur surcharge - " . $overriderControllerName);
            $this->controller = new $overriderControllerName($context);
        } else {
            DEBUG && trigger_error("Action: Controleur XmlController par defaut");
            $this->controller = new \MiniPaviFwk\controllers\XmlController($context);
        }
        $this->output = $this->controller->ecran();
    }
}
