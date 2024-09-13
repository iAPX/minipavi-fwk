<?php
/**
 * Action to change controller
 */

namespace helpers\actions;

class ControllerAction extends Action
{
    public function __construct(string $newControllerName, array $context, array $params = [])
    {
        DEBUG && trigger_error("Action: Nouveau controleur - " . $newController::class);
        $this->controller = new ($newController)($this->context, $this->params);
        $this->output = $this->controller->ecran();
    }
}
