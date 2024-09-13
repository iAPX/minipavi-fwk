<?php

/**
 * Repeat the output of the current controller.
 * Could be corrupt by instantiating a new controller and putting it on parameters, for tricky effects ;)
 */

namespace MiniPaviFwk\actions;

class RepetitionAction extends Action
{
    public function __construct(\MiniPaviFwk\controllers\VideotexController $thisController)
    {
        DEBUG && trigger_error("Action: Repetition");
        $this->controller = $thisController;
        $this->output = $thisController->ecran();
    }
}