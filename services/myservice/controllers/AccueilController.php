<?php

/**
 * Minimalistic homepage controller
 */

namespace service\controllers;

class AccueilController extends \MiniPaviFwk\controllers\VideotexController
{
    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $videotex->effaceEcran()->ecritUnicode("Hello world! 'myservice' is running.");
        return $videotex->getOutput();
    }
}
