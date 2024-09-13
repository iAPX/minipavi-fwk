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

        // Enable Overriding by \service\{Pagename}Controlle. XmlController or a VideotexController
        $formatted_pagename = \MiniPaviFwk\strings\mb_ucfirst(mb_strtolower($pagename));
        $overriderControllerName = "\service\\" . $formatted_pagename . 'Controller';
        $overriderFileName = "service/" . \MiniPaviFwk\strings\mb_ucfirst(mb_strtolower($pagename)) . 'Controller.php';
        if (file_exists($overriderFileName)) {
            // Loads the overrider file, but still check if the class itself exists!
            require_once $overriderFileName;
        }
        if (class_exists($overriderControllerName)) {
            DEBUG && trigger_error("Action: Controleur surcharge - " . $overriderControllerName);
            $this->controller = new $overriderControllerName($context);
        } else {
            $this->controller = new \MiniPaviFwk\controllers\XmlController($context);
        }
        $this->output = $this->controller->ecran();
    }
}
