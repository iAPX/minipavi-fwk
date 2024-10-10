<?php

/**
 * Abstract class for actions
 */

namespace MiniPaviFwk\actions;

use MiniPaviFwk\controllers\VideotexController;

abstract class Action
{
    protected VideotexController $controller;
    protected string $output = '';

    public function getController(): VideotexController
    {
        return $this->controller;
    }

    public function getOutput(): string
    {
        return $this->output;
    }
}
