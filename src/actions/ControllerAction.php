<?php

/**
 * Action to change controller
 */

namespace MiniPaviFwk\actions;

class ControllerAction extends Action
{
    public function __construct(string $newControllerName, array $context, array $params = [])
    {
        DEBUG && trigger_error("Action: Nouveau controleur - " . $newControllerName);
        $this->controller = new ($newControllerName)($this->context, $this->params);
        $this->output = $this->controller->ecran();
    }
}
