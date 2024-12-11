<?php

/**
 * Action to send back to the homepage of the service
 */

namespace MiniPaviFwk\actions;

use MiniPavi\MiniPaviCli;
use MiniPaviFwk\helpers\ConstantHelper;

class AccueilAction extends Action
{
    public function __construct(array $context = [])
    {
        trigger_error("Action: Accueil", E_USER_NOTICE);
        $default_controller = ConstantHelper::getConstValueByName('DEFAULT_CONTROLLER', 'AccueilController');
        $this->controller = new $default_controller($context);
        // Hook entry
        $this->controller->entree();
        // hook before display
        $this->controller->preAffichage();
        $this->output = MiniPaviCli::clearScreen() . PRO_MIN . PRO_LOCALECHO_OFF . $this->controller->ecran();
    }
}
