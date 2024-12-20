<?php

/**
 * Action to go to a Controller by its readable name.
 *
 * Notice that if the pagename have dash, every part will be capitalized:
 * "demo" -> DemoController
 * "demo-ecran" -> DemoEcranController
 * "demo-ecran-code" -> DemoEcranCodeController
 *
 * \service\controllers\*Controller are instantiated directly, using composer autoloaders.
 *
 * A reminder to do "composer install", "composer update" and "composer dump-autoload" when needed!
 */

namespace MiniPaviFwk\actions;

class PageAction extends Action
{
    public function __construct(array $context, string $pagename)
    {
        trigger_error("Action: Changement de page - " . $pagename, E_USER_NOTICE);

        // Also accept Controller Name as Page Name
        if (strpos($pagename, '-') === false) {
            $fullControllerName = "\\service\\controllers\\" . $pagename . 'Controller';
            if (class_exists($fullControllerName)) {
                $this->setControllerAndOutput($fullControllerName, $context);
                return;
            }
        }

        // Derive Page name to controller name, as a fallback
        $cleanControllerName = '';
        foreach (explode('-', $pagename) as $pagename_part) {
            $car = substr($pagename_part, 0, 1);
            if (($car < 'a' || $car > 'z') && ($car < 'A' || $car > 'Z')) {
                // Not a letter, could not be ucfirsted, we change the dash '-' to underscore '_'
                $cleanControllerName .= '_' . mb_strtolower($pagename_part);
            } else {
                $cleanControllerName .= \MiniPaviFwk\helpers\mb_ucfirst(mb_strtolower($pagename_part));
            }
        }
        $fullControllerName = "\\service\\controllers\\" . $cleanControllerName . 'Controller';
        $this->setControllerAndOutput($fullControllerName, $context);
    }

    private function setControllerAndOutput(string $newControllerName, array $context): void
    {
        trigger_error("Action: Controleur - " . $fullControllerName, E_USER_NOTICE);
        $this->controller = new $newControllerName($context);
        $this->controller->entree();
        $this->controller->preAffichage();
        $this->output = $this->controller->ecran();
    }
}
