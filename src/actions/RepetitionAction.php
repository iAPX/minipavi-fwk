<?php

/**
 * Repeat the output of the current controller.
 * Could be abused by instantiating a new controller and putting it on parameters, for tricky effects ;)
 */

namespace MiniPaviFwk\actions;

use MiniPaviFwk\controllers\VideotexController;

class RepetitionAction extends Action
{
    public function __construct(VideotexController $thisController)
    {
        trigger_error("Action: Repetition", E_USER_NOTICE);
        $this->controller = $thisController;
        $thisController->preAffichage();
        $this->output = $thisController->ecran();
    }
}
