<?php

/**
 * Abstract class for actions
 */

namespace MiniPaviFwk\actions;

abstract class Action
{
    protected \MiniPaviFwk\controllers\VideotexController $controller;
    protected string $output = '';

    public function getController(): \MiniPaviFwk\controllers\VideotexController
    {
        return $this->controller;
    }

    public function getOutput(): string
    {
        return $this->output;
    }
}
