<?php

/**
 * Action to go to a page by its name, either by {Pagename}Controller if exists or by XmlController with params
 *
 * Notice that if the pagename have dash, every part will be capitalized:
 * "demo" -> DemoController
 * "demo-ecran" -> DemoEcranController
 * "demo-ecran-code" -> DemoEcranCodeController
 *
 * \service\controllers\*Controller are instantiated directly, using compooser autoloaders.
 *
 * A reminder to do "composer install", "composer update" and "composer dump-autoload" when needed!
 */

namespace MiniPaviFwk\actions;

use MiniPaviFwk\controllers\XmlController;

class PageAction extends Action
{
    public function __construct(array $context, string $pagename, string $xmlfilename = '')
    {
        trigger_error("Action: Changement de page - " . $pagename, E_USER_NOTICE);
        $context['xml_page'] = $pagename;
        // Enjoy ;)
        !empty($xmlfilename) && $context['xml_filename'] = $xmlfilename;

        // Enable Overriding by \service\{Pagename}Controller. XmlController or a VideotexController
        // xxxx becomes XxxxController; xxxx-yyyy becomes XxxxYyyyController; and so on
        $cleanControllerName = '';
        foreach (explode('-', $pagename) as $pagename_part) {
            $car = substr($pagename_part, 0, 1);
            if ($car < 'a' || $car > 'z') {
                // Not a letter, could not be ucfirsted, we change the dash '-' to underscore '_'
                $cleanControllerName .= '_';
            }    
            $cleanControllerName .= \MiniPaviFwk\helpers\mb_ucfirst(mb_strtolower($pagename_part));
        }
        $overriderControllerName = "\\service\\controllers\\" . $cleanControllerName . 'Controller';
        if (class_exists($overriderControllerName)) {
            // We rely on the Composer Autoloaders - don't forget to install and update them: composer dump-autoload
            trigger_error("Action: Controleur surcharge - " . $overriderControllerName, E_USER_NOTICE);
            $this->controller = new $overriderControllerName($context);
        } else {
            trigger_error("Action: Controleur XmlController par defaut", E_USER_NOTICE);
            $this->controller = new XmlController($context);
        }
        $this->output = $this->controller->ecran();
    }
}
