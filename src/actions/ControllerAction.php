<?php

/**
 * Action to change controller
 */

namespace MiniPaviFwk\actions;

class ControllerAction extends Action
{
    public function __construct(string $newControllerName, array $context, array $params = [])
    {
        trigger_error("Action: Nouveau controleur - " . $newControllerName, E_USER_NOTICE);
        $this->controller = new ($newControllerName)($context, $params);
        $this->output = $this->controller->ecran();
    }
}
