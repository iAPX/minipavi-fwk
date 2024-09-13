<?php
/**
 * Abstract class for actions
 */

namespace helpers\actions;

abstract class Action
{
    protected \helpers\controllers\VideotexController $controller;
    protected string $output = '';

    public function getController(): \helpers\controllers\VideotexController
    {
        return $this->controller;
    }

    public function getOutput(): string
    {
        return $this->output;
    }
}
